<?php
require_once '../config/config.php';

$device_id = $_GET['device_id'] ?? null;

if ($device_id) {
    $stmt = $pdo->prepare('SELECT id, name FROM brands WHERE device_id = ? ORDER BY name ASC');
    $stmt->execute([$device_id]);
    $brands = $stmt->fetchAll(PDO::FETCH_ASSOC);
    header('Content-Type: application/json');
    echo json_encode($brands);
} else {
    echo json_encode([]);
}
