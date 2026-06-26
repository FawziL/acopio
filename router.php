<?php
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Depuración: si visitas /router-test, confirma que el router funciona
if ($uri === '/router-test') {
    echo "Router funcionando correctamente.";
    return true;
}

// Raíz → index.php
if ($uri === '/') {
    require __DIR__ . '/index.php';
    return true;
}

$filePath = __DIR__ . $uri;

// Si el archivo existe físicamente, lo servimos directo
if (is_file($filePath)) {
    return false;
}

// Mapeo de rutas amigables a archivos PHP en views/
$phpFile = __DIR__ . '/views' . $uri . '.php';
if (is_file($phpFile)) {
    require $phpFile;
    return true;
}

// Ruta amigable: /centro-acopio/{id}
if (preg_match('#^/centro-acopio/(\d+)$#', $uri, $m)) {
    $_GET['id'] = (int)$m[1];
    require __DIR__ . '/views/centro.php';
    return true;
}

// Ruta amigable: /refugio/{id}
if (preg_match('#^/refugio/(\d+)$#', $uri, $m)) {
    $_GET['id'] = (int)$m[1];
    require __DIR__ . '/views/refugio.php';
    return true;
}

// 404
http_response_code(404);
echo 'Not found: ' . htmlspecialchars($uri);
return true;
