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
$action = $_GET['action'] ?? '';

// -- GET: Listar reportes activos de un centro --
if ($method === 'GET') {
    $centroId = (int)($_GET['centro_id'] ?? 0);
    if (!$centroId) {
        jsonResponse(['error' => 'centro_id es requerido.'], 400);
    }
    listarReportes($centroId);
}

// -- POST: Crear reporte --
if ($method === 'POST' && $action === 'denunciar') {
    denunciarReporte();
}

if ($method === 'POST' && !$action) {
    crearReporte();
}

// ==============================

function listarReportes(int $centroId): void
{
    global $pdo;

    // Obtener conteo de denuncias por reporte
    $stmtDen = $pdo->prepare("
        SELECT reporte_id, COUNT(*) AS total
        FROM reportes_denuncias
        WHERE reporte_id IN (SELECT id FROM reportes WHERE centro_id = :centro_id AND activo = 1)
        GROUP BY reporte_id
    ");
    $stmtDen->execute([':centro_id' => $centroId]);
    $denuncias = [];
    foreach ($stmtDen->fetchAll() as $row) {
        $denuncias[(int)$row['reporte_id']] = (int)$row['total'];
    }

    $stmt = $pdo->prepare("
        SELECT id, nombre_anonimo, tipo_reporte, mensaje, created_at
        FROM reportes
        WHERE centro_id = :centro_id AND activo = 1
        ORDER BY created_at DESC
        LIMIT 50
    ");
    $stmt->execute([':centro_id' => $centroId]);
    $reportes = $stmt->fetchAll();

    foreach ($reportes as &$r) {
        $r['denuncias'] = $denuncias[(int)$r['id']] ?? 0;
    }
    unset($r);

    jsonResponse(['data' => $reportes]);
}

function crearReporte(): void
{
    global $pdo;

    $input = json_decode(file_get_contents('php://input'), true);
    if (!$input) {
        jsonResponse(['error' => 'Datos invalidos.'], 400);
    }

    $centroId = (int)($input['centro_id'] ?? 0);
    $mensaje  = trim($input['mensaje'] ?? '');
    $tipo     = $input['tipo'] ?? 'comentario';
    $nombre   = trim($input['nombre'] ?? 'Anónimo');

    if (!$centroId || empty($mensaje)) {
        jsonResponse(['error' => 'centro_id y mensaje son obligatorios.'], 400);
    }

    if (!in_array($tipo, ['valida', 'alerta', 'denuncia', 'comentario'])) {
        $tipo = 'comentario';
    }

    // Validar que el centro exista
    $stmt = $pdo->prepare("SELECT id FROM centros WHERE id = :id");
    $stmt->execute([':id' => $centroId]);
    if (!$stmt->fetch()) {
        jsonResponse(['error' => 'Centro no encontrado.'], 404);
    }

    // Turnstile
    if (!validarTurnstile($input['turnstile_token'] ?? '')) {
        jsonResponse(['error' => 'Verificacion de seguridad fallida.'], 403);
    }

    // Filtro de palabras prohibidas
    if (tienePalabrasProhibidas($mensaje) || tienePalabrasProhibidas($nombre)) {
        jsonResponse(['error' => 'El mensaje contiene lenguaje inapropiado.'], 400);
    }

    // Rate limiting por IP + centro (1 cada 3 horas)
    $ip = getClientIP();
    if (!checkRateLimitPorCentro($ip, $centroId, 3)) {
        jsonResponse([
            'error' => 'Ya reportaste en este centro hace poco. Puedes volver a reportar en 3 horas.',
        ], 429);
    }

    if (mb_strlen($nombre) > 100) {
        $nombre = mb_substr($nombre, 0, 100);
    }
    if (mb_strlen($mensaje) > 2000) {
        $mensaje = mb_substr($mensaje, 0, 2000);
    }

    $stmt = $pdo->prepare("
        INSERT INTO reportes (centro_id, nombre_anonimo, tipo_reporte, mensaje)
        VALUES (:centro_id, :nombre, :tipo, :mensaje)
    ");
    $stmt->execute([
        ':centro_id' => $centroId,
        ':nombre'    => $nombre ?: 'Anónimo',
        ':tipo'      => $tipo,
        ':mensaje'   => $mensaje,
    ]);

    jsonResponse([
        'mensaje'    => 'Reporte enviado. Gracias por ayudar a la comunidad.',
        'reporte_id' => (int)$pdo->lastInsertId(),
    ], 201);
}

function denunciarReporte(): void
{
    global $pdo;

    $input = json_decode(file_get_contents('php://input'), true);
    if (!$input) {
        jsonResponse(['error' => 'Datos invalidos.'], 400);
    }

    $reporteId = (int)($input['reporte_id'] ?? 0);
    if (!$reporteId) {
        jsonResponse(['error' => 'reporte_id es requerido.'], 400);
    }

    $ip  = getClientIP();
    $hash = sha1($ip);

    // Verificar que el reporte exista y esté activo
    $stmt = $pdo->prepare("SELECT id FROM reportes WHERE id = :id AND activo = 1");
    $stmt->execute([':id' => $reporteId]);
    if (!$stmt->fetch()) {
        jsonResponse(['error' => 'Reporte no encontrado.'], 404);
    }

    // Evitar doble denuncia desde la misma IP
    $stmt = $pdo->prepare("SELECT id FROM reportes_denuncias WHERE reporte_id = :rid AND ip_hash = :ip");
    $stmt->execute([':rid' => $reporteId, ':ip' => $hash]);
    if ($stmt->fetch()) {
        jsonResponse(['error' => 'Ya reportaste este comentario.'], 409);
    }

    // Insertar denuncia
    $stmt = $pdo->prepare("INSERT INTO reportes_denuncias (reporte_id, ip_hash) VALUES (:rid, :ip)");
    $stmt->execute([':rid' => $reporteId, ':ip' => $hash]);

    // Contar denuncias: si llega a 3, ocultar automáticamente
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM reportes_denuncias WHERE reporte_id = :rid");
    $stmt->execute([':rid' => $reporteId]);
    $totalDenuncias = (int)$stmt->fetchColumn();

    $ocultado = false;
    if ($totalDenuncias >= 3) {
        $stmtUpd = $pdo->prepare("UPDATE reportes SET activo = 0 WHERE id = :id");
        $stmtUpd->execute([':id' => $reporteId]);
        $ocultado = true;
    }

    jsonResponse([
        'mensaje'   => 'Denuncia registrada.',
        'ocultado'  => $ocultado,
        'denuncias' => $totalDenuncias,
    ]);
}
