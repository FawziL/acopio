<?php

// Cargar variables de entorno desde .env
$envFile = __DIR__ . '/../.env';
if (file_exists($envFile)) {
    $env = parse_ini_file($envFile);
    foreach ($env as $key => $value) {
        if (!defined($key)) {
            define($key, $value);
        }
    }
}

// Constantes con defaults por si faltan en .env
defined('DB_HOST')    or define('DB_HOST', 'localhost');
defined('DB_PORT')    or define('DB_PORT', '3306');
defined('DB_NAME')    or define('DB_NAME', 'acopio_venezuela');
defined('DB_USER')    or define('DB_USER', 'root');
defined('DB_PASS')    or define('DB_PASS', '');
defined('DB_CHARSET') or define('DB_CHARSET', 'utf8mb4');

defined('TURNSTILE_SITE_KEY') or define('TURNSTILE_SITE_KEY', '');
defined('TURNSTILE_SECRET')   or define('TURNSTILE_SECRET', '');

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET,
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );
} catch (PDOException $e) {
    http_response_code(500);
    die(json_encode(['error' => 'Error de conexion a la base de datos.']));
}
