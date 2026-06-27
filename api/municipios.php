<?php

require_once __DIR__ . '/helper.php';

header('Content-Type: application/json; charset=utf-8');

$estadoId = (int)($_GET['estado_id'] ?? 0);
$estadoNombre = trim($_GET['estado_nombre'] ?? '');

if ($estadoId) {
    $stmt = $pdo->prepare("SELECT id, nombre FROM municipios WHERE estado_id = :estado_id ORDER BY nombre");
    $stmt->execute([':estado_id' => $estadoId]);
} elseif ($estadoNombre !== '') {
    $stmt = $pdo->prepare("SELECT m.id, m.nombre FROM municipios m JOIN estados e ON e.id = m.estado_id WHERE e.nombre = :nombre ORDER BY m.nombre");
    $stmt->execute([':nombre' => $estadoNombre]);
} else {
    jsonResponse(['error' => 'estado_id o estado_nombre es requerido.'], 400);
}

$municipios = $stmt->fetchAll();

jsonResponse(['data' => $municipios]);
