<?php

require_once __DIR__ . '/helper.php';

header('Content-Type: application/json; charset=utf-8');

$estadoId    = (int)($_GET['estado_id'] ?? 0);
$municipioId = (int)($_GET['municipio_id'] ?? 0);

if (!$estadoId) {
    jsonResponse(['error' => 'estado_id es requerido.'], 400);
}

$params = [':estado_id' => $estadoId];
$sql = "
    SELECT r.id, r.direccion, r.telefono, r.foto_url,
           m.nombre AS municipio,
           (SELECT COUNT(*) FROM inventario_refugios i WHERE i.refugio_id = r.id AND i.activo = 1) AS total_items
    FROM refugios r
    JOIN municipios m ON m.id = r.municipio_id
    WHERE r.estado_id = :estado_id
";

if ($municipioId) {
    $sql .= " AND r.municipio_id = :municipio_id";
    $params[':municipio_id'] = $municipioId;
}

$sql .= " ORDER BY r.created_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$refugios = $stmt->fetchAll();

jsonResponse(['data' => $refugios]);
