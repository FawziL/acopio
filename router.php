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

// Ruta amigable: /centro-acopio/{id} (antes del auto-mapper para evitar conflicto)
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

// Ruta amigable: /averia/{id}
if (preg_match('#^/averia/(\d+)$#', $uri, $m)) {
    $_GET['id'] = (int)$m[1];
    require __DIR__ . '/views/averia.php';
    return true;
}

// Mapeo de rutas amigables a archivos PHP en views/
$phpFile = __DIR__ . '/views' . $uri . '.php';
if (is_file($phpFile)) {
    require $phpFile;
    return true;
}

// Ruta: /voluntarios
if ($uri === '/voluntarios') {
    require __DIR__ . '/views/voluntarios.php';
    return true;
}

// Ruta: /voluntarios/lista
if ($uri === '/voluntarios/lista') {
    require __DIR__ . '/views/voluntarios-lista.php';
    return true;
}

// Ruta: /averias (formulario)
if ($uri === '/averias') {
    require __DIR__ . '/views/averias.php';
    return true;
}

// Ruta: /averias/lista
if ($uri === '/averias/lista') {
    require __DIR__ . '/views/averias-lista.php';
    return true;
}

// 404
http_response_code(404);
echo 'Not found: ' . htmlspecialchars($uri);
return true;
