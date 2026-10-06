<?php
require_once __DIR__ . '/../../src/AjaxRequest.php';

// ajax_issues.php
if (!defined('BASE_PATH')) {
    define('BASE_PATH', dirname(dirname(__DIR__)) . '/');
    require_once BASE_PATH . 'config/config.php';
}
header('Content-Type: application/json; charset=utf-8');

$device = $_GET['device'] ?? '';
if (!$device) {
  echo \KeySoftItalia\BackendHttp::encode(['issues' => []]);
  exit;
}

// trova l'id del device
$stmt = $pdo->prepare("SELECT id FROM devices WHERE slug = :slug LIMIT 1");
$stmt->execute(['slug' => $device]);
$device_id = $stmt->fetchColumn();
if (!$device_id) {
  echo \KeySoftItalia\BackendHttp::encode(['issues' => []]);
  exit;
}

$stmt = $pdo->prepare("
  SELECT id, label, severity
  FROM issues
  WHERE device_id = :device_id AND is_active=1
  ORDER BY sort_order ASC, label ASC
");
$stmt->execute(['device_id' => $device_id]);
$issues = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo \KeySoftItalia\BackendHttp::encode(['issues' => $issues], JSON_UNESCAPED_UNICODE);
