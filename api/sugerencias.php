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

$mensaje = trim($input['mensaje'] ?? '');
$nombre  = trim($input['nombre'] ?? '');
$email   = trim($input['email'] ?? '');

if (empty($mensaje)) {
    jsonResponse(['error' => 'El mensaje es obligatorio.'], 400);
}

if ($email && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    jsonResponse(['error' => 'Correo electrónico no válido.'], 400);
}

if (!validarTurnstile($input['turnstile_token'] ?? '')) {
    jsonResponse(['error' => 'Verificación de seguridad fallida.'], 403);
}

$ip = getClientIP();
if (!checkRateLimit($ip)) {
    jsonResponse(['error' => 'Demasiadas solicitudes. Intenta más tarde.'], 429);
}

if (tienePalabrasProhibidas($mensaje) || tienePalabrasProhibidas($nombre)) {
    jsonResponse(['error' => 'El mensaje contiene lenguaje inapropiado.'], 400);
}

global $pdo;

if (mb_strlen($nombre) > 100) {
    $nombre = mb_substr($nombre, 0, 100);
}
if (mb_strlen($email) > 200) {
    $email = mb_substr($email, 0, 200);
}
if (mb_strlen($mensaje) > 2000) {
    $mensaje = mb_substr($mensaje, 0, 2000);
}

$stmt = $pdo->prepare("
    INSERT INTO sugerencias (nombre, email, mensaje)
    VALUES (:nombre, :email, :mensaje)
");
$stmt->execute([
    ':nombre'  => $nombre ?: 'Anónimo',
    ':email'   => $email ?: null,
    ':mensaje' => $mensaje,
]);

jsonResponse([
    'mensaje' => 'Sugerencia enviada. ¡Gracias por ayudarnos a mejorar!',
], 201);
