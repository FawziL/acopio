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

$requeridos = ['nombre', 'cedula', 'edad'];
foreach ($requeridos as $campo) {
    if (empty($input[$campo])) {
        jsonResponse(['error' => "El campo '$campo' es obligatorio."], 400);
    }
}

$cedula = trim($input['cedula']);
if (!preg_match('/^[0-9]{1,8}$/', $cedula)) {
    jsonResponse(['error' => 'La cédula debe tener entre 1 y 8 dígitos.'], 400);
}

$edad = (int)$input['edad'];
if ($edad < 0 || $edad > 150) {
    jsonResponse(['error' => 'Edad no válida.'], 400);
}

if (!validarTurnstile($input['turnstile_token'] ?? '')) {
    jsonResponse(['error' => 'Verificación de seguridad fallida.'], 403);
}

$ip = getClientIP();
if (!checkRateLimit($ip)) {
    jsonResponse(['error' => 'Demasiadas solicitudes. Intenta más tarde.'], 429);
}

global $pdo;

$nombre    = trim($input['nombre']);
$telefono  = trim($input['telefono'] ?? '');
$ubicacion = trim($input['ubicacion'] ?? '');
$fotoUrl   = trim($input['foto_url'] ?? '');

if (mb_strlen($nombre) > 200) $nombre = mb_substr($nombre, 0, 200);
if (mb_strlen($telefono) > 20) $telefono = mb_substr($telefono, 0, 20);
if (mb_strlen($ubicacion) > 300) $ubicacion = mb_substr($ubicacion, 0, 300);
if (mb_strlen($fotoUrl) > 500) $fotoUrl = mb_substr($fotoUrl, 0, 500);

$stmt = $pdo->prepare("
    INSERT INTO damnificados (nombre, cedula, edad, telefono, ubicacion, foto_url)
    VALUES (:nombre, :cedula, :edad, :telefono, :ubicacion, :foto_url)
");
$stmt->execute([
    ':nombre'    => $nombre,
    ':cedula'    => $cedula,
    ':edad'      => $edad,
    ':telefono'  => $telefono,
    ':ubicacion' => $ubicacion,
    ':foto_url'  => $fotoUrl,
]);

$nuevoId = (int)$pdo->lastInsertId();

jsonResponse([
    'mensaje'       => 'Damnificado registrado exitosamente.',
    'damnificado_id' => $nuevoId,
], 201);
