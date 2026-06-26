<?php

require_once __DIR__ . '/../config/database.php';

/**
 * Normaliza una dirección: sin tildes, sin eñes, minúsculas, sin espacios extra.
 */
function normalizarDireccion(string $direccion): string
{
    $buscar  = ['á', 'é', 'í', 'ó', 'ú', 'ñ', 'Á', 'É', 'Í', 'Ó', 'Ú', 'Ñ', 'ü', 'Ü'];
    $reemplazar = ['a', 'e', 'i', 'o', 'u', 'n', 'A', 'E', 'I', 'O', 'U', 'N', 'u', 'U'];

    $limpia = str_replace($buscar, $reemplazar, $direccion);
    $limpia = strtolower(trim(preg_replace('/\s+/', ' ', $limpia)));

    return $limpia;
}

/**
 * Genera el hash único para un centro basado en estado, municipio y dirección.
 */
function generarHashDireccion(int $estado_id, int $municipio_id, string $direccion): string
{
    $normalizada = normalizarDireccion($direccion);
    return sha1($estado_id . '_' . $municipio_id . '_' . $normalizada);
}

/**
 * Envía una respuesta JSON y termina la ejecución.
 */
function jsonResponse(mixed $data, int $status = 200): void
{
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

/**
 * Valida el token de Cloudflare Turnstile.
 */
function validarTurnstile(string $token): bool
{
    $secret = defined('TURNSTILE_SECRET') ? TURNSTILE_SECRET : '';

    if (empty($secret) || empty($token)) {
        return false;
    }

    $ch = curl_init('https://challenges.cloudflare.com/turnstile/v0/siteverify');
    curl_setopt_array($ch, [
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => http_build_query(['secret' => $secret, 'response' => $token]),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 10,
    ]);
    $resp = curl_exec($ch);
    curl_close($ch);

    $data = json_decode($resp, true);
    return !empty($data['success']);
}

/**
 * Rate limiting simple por IP (archivo).
 */
function checkRateLimit(string $ip, int $max = 10, int $windowMinutes = 60): bool
{
    $file = sys_get_temp_dir() . '/rate_' . sha1($ip) . '.lock';

    $now = time();
    $window = $windowMinutes * 60;

    $attempts = [];
    if (file_exists($file)) {
        $attempts = json_decode(file_get_contents($file), true) ?? [];
        $attempts = array_filter($attempts, fn($t) => ($now - $t) < $window);
    }

    if (count($attempts) >= $max) {
        return false;
    }

    $attempts[] = $now;
    file_put_contents($file, json_encode(array_values($attempts)), LOCK_EX);

    return true;
}

/**
 * Obtiene la IP real del cliente.
 */
function getClientIP(): string
{
    if (!empty($_SERVER['HTTP_CF_CONNECTING_IP'])) {
        return $_SERVER['HTTP_CF_CONNECTING_IP'];
    }
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
    }
    return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
}

/**
 * Filtro básico de palabras clave para evitar spam/vandalismo.
 * Devuelve true si el texto contiene palabras prohibidas.
 */
function tienePalabrasProhibidas(string $texto): bool
{
    $prohibidas = [
        'puto', 'puta', 'mierda', 'coño', 'verga', 'carajo',
        'huevon', 'huevón', 'pendejo', 'marico', 'marica',
        'malparido', 'hijueputa', 'gonorrea', 'hp ', ' ctm',
        '<script', 'javascript:', 'onclick=', 'onerror=',
    ];

    $texto = strtolower($texto);
    foreach ($prohibidas as $palabra) {
        if (str_contains($texto, $palabra)) {
            return true;
        }
    }
    return false;
}

/**
 * Rate limiting específico por IP + centro (para reportes).
 * Máximo 1 reporte por IP cada 3 horas en el mismo centro.
 */
function checkRateLimitPorCentro(string $ip, int $centroId, int $horas = 3): bool
{
    $file = sys_get_temp_dir() . '/rate_centro_' . sha1($ip . '_' . $centroId) . '.lock';
    $now = time();
    $window = $horas * 3600;

    if (file_exists($file)) {
        $lastTime = (int)file_get_contents($file);
        if (($now - $lastTime) < $window) {
            return false;
        }
    }

    file_put_contents($file, (string)$now, LOCK_EX);
    return true;
}
