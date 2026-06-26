<?php

require_once __DIR__ . '/helper.php';

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $refugioId = $_GET['refugio_id'] ?? 0;
    if (!$refugioId) {
        jsonResponse(['error' => 'refugio_id es requerido.'], 400);
    }
    listarInventario((int)$refugioId);
}

if ($method === 'POST') {
    agregarItem();
}

if ($method === 'DELETE') {
    eliminarItem();
}

function listarInventario(int $refugioId): void
{
    global $pdo;

    $stmt = $pdo->prepare("
        SELECT id, item, tipo, cantidad, updated_at
        FROM inventario_refugios
        WHERE refugio_id = :refugio_id AND activo = 1
        ORDER BY tipo, item
    ");
    $stmt->execute([':refugio_id' => $refugioId]);
    $items = $stmt->fetchAll();

    jsonResponse(['data' => $items]);
}

function agregarItem(): void
{
    global $pdo;

    $input = json_decode(file_get_contents('php://input'), true);

    if (!$input) {
        jsonResponse(['error' => 'Datos invalidos.'], 400);
    }

    $requeridos = ['refugio_id', 'item', 'tipo'];
    foreach ($requeridos as $campo) {
        if (empty($input[$campo])) {
            jsonResponse(['error' => "El campo '$campo' es obligatorio."], 400);
        }
    }

    if (!in_array($input['tipo'], ['falta', 'sobra'])) {
        jsonResponse(['error' => 'tipo debe ser "falta" o "sobra".'], 400);
    }

    $stmt = $pdo->prepare("SELECT id FROM refugios WHERE id = :id");
    $stmt->execute([':id' => (int)$input['refugio_id']]);
    if (!$stmt->fetch()) {
        jsonResponse(['error' => 'Refugio no encontrado.'], 404);
    }

    if (!validarTurnstile($input['turnstile_token'] ?? '')) {
        jsonResponse(['error' => 'Verificacion de seguridad fallida.'], 403);
    }

    $ip = getClientIP();
    if (!checkRateLimit($ip)) {
        jsonResponse(['error' => 'Demasiadas solicitudes. Intenta mas tarde.'], 429);
    }

    $stmt = $pdo->prepare("
        INSERT INTO inventario_refugios (refugio_id, item, tipo, cantidad)
        VALUES (:refugio_id, :item, :tipo, :cantidad)
    ");
    $stmt->execute([
        ':refugio_id' => (int)$input['refugio_id'],
        ':item'       => trim($input['item']),
        ':tipo'       => $input['tipo'],
        ':cantidad'   => trim($input['cantidad'] ?? ''),
    ]);

    jsonResponse([
        'mensaje' => 'Item agregado exitosamente.',
        'item_id' => (int)$pdo->lastInsertId(),
    ], 201);
}

function eliminarItem(): void
{
    global $pdo;

    $input = json_decode(file_get_contents('php://input'), true);

    if (!$input) {
        jsonResponse(['error' => 'Datos invalidos.'], 400);
    }

    $itemId = (int)($input['id'] ?? 0);
    if (!$itemId) {
        jsonResponse(['error' => 'ID del item es requerido.'], 400);
    }

    if (!validarTurnstile($input['turnstile_token'] ?? '')) {
        jsonResponse(['error' => 'Verificacion de seguridad fallida.'], 403);
    }

    $ip = getClientIP();
    if (!checkRateLimit($ip)) {
        jsonResponse(['error' => 'Demasiadas solicitudes. Intenta mas tarde.'], 429);
    }

    $stmt = $pdo->prepare("UPDATE inventario_refugios SET activo = 0 WHERE id = :id");
    $stmt->execute([':id' => $itemId]);

    if ($stmt->rowCount() === 0) {
        jsonResponse(['error' => 'Item no encontrado.'], 404);
    }

    jsonResponse(['mensaje' => 'Item eliminado correctamente.']);
}
