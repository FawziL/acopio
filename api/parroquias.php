<?php

require_once __DIR__ . '/helper.php';

header('Content-Type: application/json; charset=utf-8');

$municipioId = (int)($_GET['municipio_id'] ?? 0);

if (!$municipioId) {
    jsonResponse(['error' => 'municipio_id es requerido.'], 400);
}

$stmt = $pdo->prepare("SELECT id, nombre FROM parroquias WHERE municipio_id = :municipio_id ORDER BY nombre");
$stmt->execute([':municipio_id' => $municipioId]);
$parroquias = $stmt->fetchAll();

jsonResponse(['data' => $parroquias]);
