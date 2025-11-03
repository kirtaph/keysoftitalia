<?php
require_once '../config/config.php';

$brand_id = $_GET['brand_id'] ?? null;

if ($brand_id) {
    $stmt = $pdo->prepare('SELECT id, name FROM models WHERE brand_id = ? ORDER BY name ASC');
    $stmt->execute([$brand_id]);
    $models = $stmt->fetchAll(PDO::FETCH_ASSOC);
    header('Content-Type: application/json');
    echo json_encode($models);
} else {
    echo json_encode([]);
}
