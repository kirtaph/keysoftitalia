<?php
// assets/ajax/brands.php
if (!defined('BASE_PATH')) {
    define('BASE_PATH', dirname(dirname(__DIR__)) . '/');
    require_once BASE_PATH . 'config/config.php';
}
header('Content-Type: application/json; charset=utf-8');

$device = $_GET['device'] ?? '';
if (!$device) {
  echo json_encode(['brands' => []]);
  exit;
}

// cerchiamo l'id device
$stmt = $pdo->prepare("SELECT id FROM devices WHERE slug = :slug LIMIT 1");
$stmt->execute(['slug' => $device]);
$device_id = $stmt->fetchColumn();
if (!$device_id) {
  echo json_encode(['brands' => [['id' => '', 'name' => 'Altra Marca', 'has_models' => false]]]);
  exit;
}

$stmt = $pdo->prepare("
  SELECT b.id, b.name,
         EXISTS(SELECT 1 FROM models m WHERE m.brand_id = b.id AND m.is_active=1) AS has_models
  FROM brands b
  WHERE b.device_id = :device_id AND b.is_active=1
  ORDER BY b.name ASC
");
$stmt->execute(['device_id' => $device_id]);
$brands = $stmt->fetchAll(PDO::FETCH_ASSOC);

// aggiungi sempre "Altra Marca"
$brands[] = ['id' => '', 'name' => 'Altra Marca', 'has_models' => false];

echo json_encode(['brands' => $brands], JSON_UNESCAPED_UNICODE);
