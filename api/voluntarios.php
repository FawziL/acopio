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

// GET: Listar voluntarios con búsqueda
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    global $pdo;

    $search    = trim($_GET['search'] ?? '');
    $estado    = trim($_GET['estado'] ?? '');
    $municipio = trim($_GET['municipio'] ?? '');
    $limite    = 50;
    $pagina    = max(1, (int)($_GET['pagina'] ?? 1));
    $offset    = ($pagina - 1) * $limite;

    $where  = [];
    $params = [];

    if ($search !== '') {
        $where[] = '(zona LIKE :search OR nombre LIKE :search2)';
        $params[':search']  = '%' . $search . '%';
        $params[':search2'] = '%' . $search . '%';
    }
    if ($estado !== '') {
        $where[] = 'zona LIKE :estado';
        $params[':estado'] = '%' . $estado . '%';
    }
    if ($municipio !== '') {
        $where[] = 'zona LIKE :municipio';
        $params[':municipio'] = '%' . $municipio . ', ' . $municipio . '%';
    }

    $sqlWhere = count($where) ? 'WHERE ' . implode(' AND ', $where) : '';

    $stmt = $pdo->prepare("SELECT COUNT(*) FROM voluntarios $sqlWhere");
    $stmt->execute($params);
    $total = (int)$stmt->fetchColumn();

    $stmt = $pdo->prepare("
        SELECT id, nombre, telefono, zona, tiene_transporte, necesita_transporte, tipo_apoyo, created_at
        FROM voluntarios
        $sqlWhere
        ORDER BY created_at DESC
        LIMIT $limite OFFSET $offset
    ");
    $stmt->execute($params);
    $voluntarios = $stmt->fetchAll();

    foreach ($voluntarios as &$v) {
        $v['tipo_apoyo'] = json_decode($v['tipo_apoyo'], true) ?? [];
        $v['tiene_transporte'] = (bool)$v['tiene_transporte'];
        $v['necesita_transporte'] = (bool)$v['necesita_transporte'];
    }
    unset($v);

    jsonResponse([
        'data'  => $voluntarios,
        'total' => $total,
    ]);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(['error' => 'Método no permitido.'], 405);
}

$input = json_decode(file_get_contents('php://input'), true);
if (!$input) {
    jsonResponse(['error' => 'Datos inválidos.'], 400);
}

$nombre     = trim($input['nombre'] ?? '');
$telefono   = trim($input['telefono'] ?? '');
$zona       = trim($input['zona'] ?? '');
$transporte = $input['transporte'] ?? [];
$tipoApoyo  = $input['tipo_apoyo'] ?? [];

if (empty($nombre)) {
    jsonResponse(['error' => 'El nombre es obligatorio.'], 400);
}
if (empty($telefono)) {
    jsonResponse(['error' => 'El teléfono es obligatorio.'], 400);
}
if (empty($tipoApoyo) || !is_array($tipoApoyo) || count($tipoApoyo) === 0) {
    jsonResponse(['error' => 'Selecciona al menos un tipo de apoyo.'], 400);
}

if (!validarTurnstile($input['turnstile_token'] ?? '')) {
    jsonResponse(['error' => 'Verificación de seguridad fallida.'], 403);
}

$ip = getClientIP();
if (!checkRateLimit($ip)) {
    jsonResponse(['error' => 'Demasiadas solicitudes. Intenta más tarde.'], 429);
}

if (tienePalabrasProhibidas($nombre) || tienePalabrasProhibidas($zona) || tienePalabrasProhibidas(json_encode($tipoApoyo))) {
    jsonResponse(['error' => 'El mensaje contiene lenguaje inapropiado.'], 400);
}

global $pdo;

if (mb_strlen($nombre) > 200) {
    $nombre = mb_substr($nombre, 0, 200);
}
if (mb_strlen($telefono) > 20) {
    $telefono = mb_substr($telefono, 0, 20);
}
if (mb_strlen($zona) > 300) {
    $zona = mb_substr($zona, 0, 300);
}

$tieneTransporte      = in_array('tiene', $transporte) ? 1 : 0;
$necesitaTransporte   = in_array('necesita', $transporte) ? 1 : 0;
$tipoApoyoJson        = json_encode($tipoApoyo, JSON_UNESCAPED_UNICODE);

$stmt = $pdo->prepare("
    INSERT INTO voluntarios (nombre, telefono, zona, tiene_transporte, necesita_transporte, tipo_apoyo)
    VALUES (:nombre, :telefono, :zona, :tiene_transporte, :necesita_transporte, :tipo_apoyo)
");
$stmt->execute([
    ':nombre'              => $nombre,
    ':telefono'            => $telefono,
    ':zona'                => $zona ?: '',
    ':tiene_transporte'    => $tieneTransporte,
    ':necesita_transporte' => $necesitaTransporte,
    ':tipo_apoyo'          => $tipoApoyoJson,
]);

jsonResponse([
    'mensaje' => 'Registro exitoso. ¡Gracias por ofrecer tu apoyo!',
], 201);
