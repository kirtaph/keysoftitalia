<?php
require_once '../config/config.php';

$device_id = $_GET['device_id'] ?? null;

if ($device_id) {
    $stmt = $pdo->prepare('SELECT id, label FROM issues WHERE device_id = ? ORDER BY label ASC');
    $stmt->execute([$device_id]);
    $issues = $stmt->fetchAll(PDO::FETCH_ASSOC);
    header('Content-Type: application/json');
    echo json_encode($issues);
} else {
    echo json_encode([]);
}
