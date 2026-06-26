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

if ($method === 'GET') {
    if (!empty($_GET['id'])) {
        obtenerRefugio((int)$_GET['id']);
    } else {
        listarRefugios();
    }
}

if ($method === 'POST') {
    crearRefugio();
}

function listarRefugios(): void
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
        $where[]       = 'r.estado_id = :estado';
        $params[':estado'] = (int)$estado;
    }
    if ($municipio !== '') {
        $where[]           = 'r.municipio_id = :municipio';
        $params[':municipio'] = (int)$municipio;
    }

    $sqlWhere = count($where) ? 'WHERE ' . implode(' AND ', $where) : '';

    $stmt = $pdo->prepare("SELECT COUNT(*) FROM refugios r $sqlWhere");
    $stmt->execute($params);
    $total = (int)$stmt->fetchColumn();

    $sql = "
        SELECT
            r.id,
            r.direccion,
            r.foto_url,
            r.telefono,
            r.created_at,
            e.nombre AS estado,
            m.nombre AS municipio,
            (SELECT GROUP_CONCAT(CONCAT(i.tipo, ':', i.item) SEPARATOR '|')
             FROM inventario_refugios i
             WHERE i.refugio_id = r.id AND i.activo = 1) AS inventario_resumen
        FROM refugios r
        JOIN estados e ON e.id = r.estado_id
        JOIN municipios m ON m.id = r.municipio_id
        $sqlWhere
        ORDER BY r.created_at DESC
        LIMIT $limite OFFSET $offset
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $refugios = $stmt->fetchAll();

    foreach ($refugios as &$refugio) {
        $refugio['falta'] = [];
        $refugio['sobra'] = [];
        if (!empty($refugio['inventario_resumen'])) {
            $items = explode('|', $refugio['inventario_resumen']);
            foreach ($items as $item) {
                $parts = explode(':', $item, 2);
                if (count($parts) === 2) {
                    $tipo = $parts[0];
                    $nombre = $parts[1];
                    if ($tipo === 'falta') {
                        $refugio['falta'][] = $nombre;
                    } else {
                        $refugio['sobra'][] = $nombre;
                    }
                }
            }
        }
        unset($refugio['inventario_resumen']);
    }

    jsonResponse([
        'data'       => $refugios,
        'total'      => $total,
        'pagina'     => $pagina,
        'total_paginas' => (int)ceil($total / $limite),
    ]);
}

function obtenerRefugio(int $id): void
{
    global $pdo;

    $stmt = $pdo->prepare("
        SELECT
            r.id,
            r.estado_id,
            r.municipio_id,
            r.parroquia_id,
            r.direccion,
            r.foto_url,
            r.telefono,
            r.created_at,
            r.updated_at,
            e.nombre AS estado,
            m.nombre AS municipio,
            p.nombre AS parroquia
        FROM refugios r
        JOIN estados e ON e.id = r.estado_id
        JOIN municipios m ON m.id = r.municipio_id
        LEFT JOIN parroquias p ON p.id = r.parroquia_id
        WHERE r.id = :id
    ");
    $stmt->execute([':id' => $id]);
    $refugio = $stmt->fetch();

    if (!$refugio) {
        jsonResponse(['error' => 'Refugio no encontrado.'], 404);
    }

    $stmtInv = $pdo->prepare("
        SELECT id, item, tipo, cantidad, updated_at
        FROM inventario_refugios
        WHERE refugio_id = :refugio_id AND activo = 1
        ORDER BY tipo, item
    ");
    $stmtInv->execute([':refugio_id' => $id]);
    $inventario = $stmtInv->fetchAll();

    $refugio['inventario'] = $inventario;

    jsonResponse($refugio);
}

function crearRefugio(): void
{
    global $pdo;

    $input = json_decode(file_get_contents('php://input'), true);

    if (!$input) {
        jsonResponse(['error' => 'Datos invalidos.'], 400);
    }

    $requeridos = ['estado_id', 'municipio_id', 'direccion', 'telefono'];
    foreach ($requeridos as $campo) {
        if (empty($input[$campo])) {
            jsonResponse(['error' => "El campo '$campo' es obligatorio."], 400);
        }
    }

    $tel = preg_replace('/[^0-9]/', '', $input['telefono']);
    if (!preg_match('/^(0412|0414|0416|0424|0426)\d{7}$/', $tel)) {
        jsonResponse(['error' => 'El telefono debe ser un numero venezolano valido (ej: 0412-1234567).'], 400);
    }
    $input['telefono'] = $tel;

    if (!validarTurnstile($input['turnstile_token'] ?? '')) {
        jsonResponse(['error' => 'Verificacion de seguridad fallida. Intenta de nuevo.'], 403);
    }

    $ip = getClientIP();
    if (!checkRateLimit($ip)) {
        jsonResponse(['error' => 'Demasiadas solicitudes. Intenta mas tarde.'], 429);
    }

    $hash = generarHashDireccion(
        (int)$input['estado_id'],
        (int)$input['municipio_id'],
        $input['direccion']
    );

    $stmt = $pdo->prepare("SELECT id FROM refugios WHERE direccion_hash = :hash");
    $stmt->execute([':hash' => $hash]);
    if ($existente = $stmt->fetch()) {
        jsonResponse([
            'error'     => 'Ya existe un refugio registrado con esta direccion.',
            'refugio_id' => (int)$existente['id'],
        ], 409);
    }

    $stmt = $pdo->prepare("
        INSERT INTO refugios (estado_id, municipio_id, parroquia_id, direccion, direccion_hash, foto_url, telefono)
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
        'mensaje'    => 'Refugio registrado exitosamente.',
        'refugio_id' => $nuevoId,
    ], 201);
}
