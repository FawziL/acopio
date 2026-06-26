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
    SELECT c.id, c.direccion, c.telefono, c.foto_url,
           m.nombre AS municipio,
           (SELECT COUNT(*) FROM inventario i WHERE i.centro_id = c.id AND i.activo = 1) AS total_items
    FROM centros c
    JOIN municipios m ON m.id = c.municipio_id
    WHERE c.estado_id = :estado_id
";

if ($municipioId) {
    $sql .= " AND c.municipio_id = :municipio_id";
    $params[':municipio_id'] = $municipioId;
}

$sql .= " ORDER BY c.created_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$centros = $stmt->fetchAll();

jsonResponse(['data' => $centros]);
