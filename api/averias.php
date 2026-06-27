<?php

require_once __DIR__ . '/helper.php';

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(['error' => 'Método no permitido.'], 405);
}

$input = json_decode(file_get_contents('php://input'), true);
if (!$input) {
    jsonResponse(['error' => 'Datos inválidos.'], 400);
}

$action = $input['action'] ?? 'crear';

if ($action === 'actualizar_estado') {
    actualizarEstado($input);
} else {
    crearAveria($input);
}

function crearAveria(array $input): void
{
    $requeridos = ['nombre', 'estado_id', 'municipio_id', 'referencia'];
    foreach ($requeridos as $campo) {
        if (empty($input[$campo])) {
            jsonResponse(['error' => "El campo '$campo' es obligatorio."], 400);
        }
    }

    if (!validarTurnstile($input['turnstile_token'] ?? '')) {
        jsonResponse(['error' => 'Verificación de seguridad fallida.'], 403);
    }

    $ip = getClientIP();
    if (!checkRateLimit($ip)) {
        jsonResponse(['error' => 'Demasiadas solicitudes. Intenta más tarde.'], 429);
    }

    global $pdo;

    $nombre     = trim($input['nombre']);
    $referencia = trim($input['referencia']);
    $contacto   = trim($input['contacto'] ?? '');
    $fotoUrl    = trim($input['foto_url'] ?? '');

    if (mb_strlen($nombre) > 200) {
        $nombre = mb_substr($nombre, 0, 200);
    }
    if (mb_strlen($contacto) > 100) {
        $contacto = mb_substr($contacto, 0, 100);
    }
    if (mb_strlen($fotoUrl) > 500) {
        $fotoUrl = mb_substr($fotoUrl, 0, 500);
    }

    $stmt = $pdo->prepare("
        INSERT INTO averias (nombre, estado_id, municipio_id, parroquia_id, referencia, contacto, foto_url)
        VALUES (:nombre, :estado_id, :municipio_id, :parroquia_id, :referencia, :contacto, :foto_url)
    ");
    $stmt->execute([
        ':nombre'        => $nombre,
        ':estado_id'     => (int)$input['estado_id'],
        ':municipio_id'  => (int)$input['municipio_id'],
        ':parroquia_id'  => !empty($input['parroquia_id']) ? (int)$input['parroquia_id'] : null,
        ':referencia'    => $referencia,
        ':contacto'      => $contacto,
        ':foto_url'      => $fotoUrl,
    ]);

    $nuevoId = (int)$pdo->lastInsertId();

    jsonResponse([
        'mensaje'  => 'Avería reportada exitosamente.',
        'averia_id' => $nuevoId,
    ], 201);
}

function actualizarEstado(array $input): void
{
    $id = (int)($input['id'] ?? 0);
    $nuevoEstado = trim($input['estado'] ?? '');

    if (!$id) {
        jsonResponse(['error' => 'ID de avería no válido.'], 400);
    }

    $validos = ['reportado', 'verificado', 'en_proceso', 'resuelto'];
    if (!in_array($nuevoEstado, $validos)) {
        jsonResponse(['error' => 'Estado no válido.'], 400);
    }

    if (!validarTurnstile($input['turnstile_token'] ?? '')) {
        jsonResponse(['error' => 'Verificación de seguridad fallida.'], 403);
    }

    $ip = getClientIP();
    if (!checkRateLimit($ip, 5, 60)) {
        jsonResponse(['error' => 'Demasiadas solicitudes. Intenta más tarde.'], 429);
    }

    global $pdo;

    $stmtCheck = $pdo->prepare("SELECT id, estado FROM averias WHERE id = :id");
    $stmtCheck->execute([':id' => $id]);
    $actual = $stmtCheck->fetch();

    if (!$actual) {
        jsonResponse(['error' => 'Avería no encontrada.'], 404);
    }

    if ($actual['estado'] === $nuevoEstado) {
        jsonResponse(['mensaje' => 'El estado ya es ' . $nuevoEstado . '.', 'estado' => $nuevoEstado]);
    }

    $stmt = $pdo->prepare("UPDATE averias SET estado = :estado WHERE id = :id");
    $stmt->execute([
        ':estado' => $nuevoEstado,
        ':id'     => $id,
    ]);

    if ($stmt->rowCount() === 0) {
        jsonResponse(['error' => 'Error al actualizar el estado.'], 500);
    }

    jsonResponse([
        'mensaje' => 'Estado actualizado exitosamente.',
        'estado'  => $nuevoEstado,
    ]);
}
