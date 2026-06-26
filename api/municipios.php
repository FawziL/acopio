<?php

require_once __DIR__ . '/helper.php';

header('Content-Type: application/json; charset=utf-8');

$estadoId = (int)($_GET['estado_id'] ?? 0);

if (!$estadoId) {
    jsonResponse(['error' => 'estado_id es requerido.'], 400);
}

$stmt = $pdo->prepare("SELECT id, nombre FROM municipios WHERE estado_id = :estado_id ORDER BY nombre");
$stmt->execute([':estado_id' => $estadoId]);
$municipios = $stmt->fetchAll();

jsonResponse(['data' => $municipios]);
