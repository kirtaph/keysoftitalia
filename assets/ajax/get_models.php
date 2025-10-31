<?php
// ajax_models.php
if (!defined('BASE_PATH')) {
    define('BASE_PATH', dirname(dirname(__DIR__)) . '/');
    require_once BASE_PATH . 'config/config.php';
}
header('Content-Type: application/json; charset=utf-8');

$brand = $_GET['brand_id'] ?? '';
if (!$brand) {
  echo json_encode(['models' => []]);
  exit;
}

$stmt = $pdo->prepare("
  SELECT id, name
  FROM models
  WHERE brand_id = :brand_id AND is_active=1
  ORDER BY name ASC
");
$stmt->execute(['brand_id' => $brand]);
$models = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode(['models' => $models], JSON_UNESCAPED_UNICODE);