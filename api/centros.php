<?php

require_once __DIR__ . '/helper.php';

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];

// -- GET: Listar centros o detalle --
if ($method === 'GET') {
    if (!empty($_GET['id'])) {
        obtenerCentro((int)$_GET['id']);
    } else {
        listarCentros();
    }
}

// -- POST: Crear centro --
if ($method === 'POST') {
    crearCentro();
}

// ==============================
//  FUNCIONES
// ==============================

function listarCentros(): void
{
    global $pdo;

    $estado    = $_GET['estado'] ?? '';
    $municipio = $_GET['municipio'] ?? '';
    $pagina    = max(1, (int)($_GET['pagina'] ?? 1));
    $limite    = 20;
    $offset    = ($pagina - 1) * $limite;

    $where  = [];
    $params = [];

    if ($estado !== '') {
        $where[]       = 'c.estado_id = :estado';
        $params[':estado'] = (int)$estado;
    }
    if ($municipio !== '') {
        $where[]           = 'c.municipio_id = :municipio';
        $params[':municipio'] = (int)$municipio;
    }

    $sqlWhere = count($where) ? 'WHERE ' . implode(' AND ', $where) : '';

    // Total para paginación
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM centros c $sqlWhere");
    $stmt->execute($params);
    $total = (int)$stmt->fetchColumn();

    // Datos
    $sql = "
        SELECT
            c.id,
            c.direccion,
            c.foto_url,
            c.telefono,
            c.created_at,
            e.nombre AS estado,
            m.nombre AS municipio,
            (SELECT GROUP_CONCAT(CONCAT(i.tipo, ':', i.item) SEPARATOR '|')
             FROM inventario i
             WHERE i.centro_id = c.id AND i.activo = 1) AS inventario_resumen
        FROM centros c
        JOIN estados e ON e.id = c.estado_id
        JOIN municipios m ON m.id = c.municipio_id
        $sqlWhere
        ORDER BY c.created_at DESC
        LIMIT $limite OFFSET $offset
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $centros = $stmt->fetchAll();

    // Parsear resumen de inventario
    foreach ($centros as &$centro) {
        $centro['falta'] = [];
        $centro['sobra'] = [];
        if (!empty($centro['inventario_resumen'])) {
            $items = explode('|', $centro['inventario_resumen']);
            foreach ($items as $item) {
                $parts = explode(':', $item, 2);
                if (count($parts) === 2) {
                    $tipo = $parts[0];
                    $nombre = $parts[1];
                    if ($tipo === 'falta') {
                        $centro['falta'][] = $nombre;
                    } else {
                        $centro['sobra'][] = $nombre;
                    }
                }
            }
        }
        unset($centro['inventario_resumen']);
    }

    jsonResponse([
        'data'       => $centros,
        'total'      => $total,
        'pagina'     => $pagina,
        'total_paginas' => (int)ceil($total / $limite),
    ]);
}

function obtenerCentro(int $id): void
{
    global $pdo;

    $stmt = $pdo->prepare("
        SELECT
            c.id,
            c.estado_id,
            c.municipio_id,
            c.parroquia_id,
            c.direccion,
            c.foto_url,
            c.telefono,
            c.created_at,
            c.updated_at,
            e.nombre AS estado,
            m.nombre AS municipio,
            p.nombre AS parroquia
        FROM centros c
        JOIN estados e ON e.id = c.estado_id
        JOIN municipios m ON m.id = c.municipio_id
        LEFT JOIN parroquias p ON p.id = c.parroquia_id
        WHERE c.id = :id
    ");
    $stmt->execute([':id' => $id]);
    $centro = $stmt->fetch();

    if (!$centro) {
        jsonResponse(['error' => 'Centro no encontrado.'], 404);
    }

    // Obtener inventario
    $stmtInv = $pdo->prepare("
        SELECT id, item, tipo, cantidad, updated_at
        FROM inventario
        WHERE centro_id = :centro_id AND activo = 1
        ORDER BY tipo, item
    ");
    $stmtInv->execute([':centro_id' => $id]);
    $inventario = $stmtInv->fetchAll();

    $centro['inventario'] = $inventario;

    jsonResponse($centro);
}

function crearCentro(): void
{
    global $pdo;

    $input = json_decode(file_get_contents('php://input'), true);

    if (!$input) {
        jsonResponse(['error' => 'Datos invalidos.'], 400);
    }

    // Validar campos requeridos
    $requeridos = ['estado_id', 'municipio_id', 'direccion', 'telefono'];
    foreach ($requeridos as $campo) {
        if (empty($input[$campo])) {
            jsonResponse(['error' => "El campo '$campo' es obligatorio."], 400);
        }
    }

    // Validar formato teléfono Venezuela: 0412/0414/0416/0424/0426 + 7 dígitos
    $tel = preg_replace('/[^0-9]/', '', $input['telefono']);
    if (!preg_match('/^(0412|0414|0416|0424|0426)\d{7}$/', $tel)) {
        jsonResponse(['error' => 'El telefono debe ser un numero venezolano valido (ej: 0412-1234567).'], 400);
    }
    $input['telefono'] = $tel;

    // Validar Turnstile
    if (!validarTurnstile($input['turnstile_token'] ?? '')) {
        jsonResponse(['error' => 'Verificacion de seguridad fallida. Intenta de nuevo.'], 403);
    }

    // Rate limiting
    $ip = getClientIP();
    if (!checkRateLimit($ip)) {
        jsonResponse(['error' => 'Demasiadas solicitudes. Intenta mas tarde.'], 429);
    }

    // Generar hash único
    $hash = generarHashDireccion(
        (int)$input['estado_id'],
        (int)$input['municipio_id'],
        $input['direccion']
    );

    // Verificar duplicado
    $stmt = $pdo->prepare("SELECT id FROM centros WHERE direccion_hash = :hash");
    $stmt->execute([':hash' => $hash]);
    if ($existente = $stmt->fetch()) {
        jsonResponse([
            'error'  => 'Ya existe un centro registrado con esta direccion.',
            'centro_id' => (int)$existente['id'],
        ], 409);
    }

    // Insertar
    $stmt = $pdo->prepare("
        INSERT INTO centros (estado_id, municipio_id, parroquia_id, direccion, direccion_hash, foto_url, telefono)
        VALUES (:estado_id, :municipio_id, :parroquia_id, :direccion, :direccion_hash, :foto_url, :telefono)
    ");
    $stmt->execute([
        ':estado_id'      => (int)$input['estado_id'],
        ':municipio_id'   => (int)$input['municipio_id'],
        ':parroquia_id'   => !empty($input['parroquia_id']) ? (int)$input['parroquia_id'] : null,
        ':direccion'      => trim($input['direccion']),
        ':direccion_hash' => $hash,
        ':foto_url'       => trim($input['foto_url'] ?? ''),
        ':telefono'       => trim($input['telefono']),
    ]);

    $nuevoId = (int)$pdo->lastInsertId();

    jsonResponse([
        'mensaje'    => 'Centro registrado exitosamente.',
        'centro_id'  => $nuevoId,
    ], 201);
}
