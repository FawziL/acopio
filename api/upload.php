<?php

require_once __DIR__ . '/helper.php';

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(['error' => 'Metodo no permitido.'], 405);
}

$ip = getClientIP();
if (!checkRateLimit($ip, 5, 60)) {
    jsonResponse(['error' => 'Demasiadas solicitudes. Intenta mas tarde.'], 429);
}

if (empty($_FILES['foto']) || $_FILES['foto']['error'] !== UPLOAD_ERR_OK) {
    jsonResponse(['error' => 'No se recibio ninguna imagen.'], 400);
}

$archivo = $_FILES['foto'];

$tiposPermitidos = ['image/jpeg', 'image/png', 'image/webp'];
if (!in_array($archivo['type'], $tiposPermitidos)) {
    jsonResponse(['error' => 'Formato no permitido. Usa JPG, PNG o WebP.'], 400);
}

if ($archivo['size'] > 5 * 1024 * 1024) {
    jsonResponse(['error' => 'La imagen excede 5 MB.'], 400);
}

$dir = __DIR__ . '/../uploads';
if (!is_dir($dir)) {
    mkdir($dir, 0755, true);
}

$extension = pathinfo($archivo['name'], PATHINFO_EXTENSION) ?: 'jpg';
$nombre = 'centro_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $extension;
$ruta = $dir . '/' . $nombre;

if (!move_uploaded_file($archivo['tmp_name'], $ruta)) {
    jsonResponse(['error' => 'Error al guardar la imagen.'], 500);
}

jsonResponse([
    'mensaje'  => 'Imagen subida exitosamente.',
    'foto_url' => '/uploads/' . $nombre,
], 201);
