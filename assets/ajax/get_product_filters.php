<?php
declare(strict_types=1);
session_start();

if (!defined('BASE_PATH')) {
  define('BASE_PATH', dirname(dirname(__DIR__)) . '/');
  require_once BASE_PATH . 'config/config.php';
}
header('Content-Type: application/json; charset=utf-8');

try {
  if (!isset($pdo) || !($pdo instanceof PDO)) {
    $pdo = new PDO($db_dsn, $db_user, $db_pass, [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
  }

  // BRAND realmente presenti (id + name)
  $brands = $pdo->query("
    SELECT b.id, b.name, COUNT(*) AS n
    FROM products p
    JOIN models  m ON p.model_id = m.id
    JOIN brands  b ON m.brand_id  = b.id
    WHERE p.is_available = 1
    GROUP BY b.id, b.name
    ORDER BY b.name ASC
  ")->fetchAll();

  // DEVICE realmente presenti (slug + name)
  $devices = $pdo->query("
    SELECT d.id, d.slug, d.name, COUNT(*) AS n
    FROM products p
    JOIN models  m ON p.model_id  = m.id
    JOIN brands  b ON m.brand_id  = b.id
    JOIN devices d ON b.device_id = d.id
    WHERE p.is_available = 1
    GROUP BY d.id, d.slug, d.name
    ORDER BY d.sort_order ASC, d.name ASC
  ")->fetchAll();

  // GRADI realmente presenti
  $grades = $pdo->query("
    SELECT p.grade, COUNT(*) AS n
    FROM products p
    WHERE p.is_available = 1
    GROUP BY p.grade
    ORDER BY FIELD(p.grade,'Nuovo','Expo','A+','A','B','C'), p.grade
  ")->fetchAll();

  // RANGE prezzo / storage (per slider o range UI futuri)
  $priceRange = $pdo->query("
    SELECT MIN(p.price_eur) AS min_price, MAX(p.price_eur) AS max_price
    FROM products p
    WHERE p.is_available = 1
  ")->fetch();

  $storageRange = $pdo->query("
    SELECT MIN(p.storage_gb) AS min_storage, MAX(p.storage_gb) AS max_storage
    FROM products p
    WHERE p.is_available = 1 AND p.storage_gb IS NOT NULL
  ")->fetch();

  echo json_encode([
    'ok'      => true,
    'filters' => [
      'brands'   => $brands,
      'devices'  => $devices,
      'grades'   => $grades,
      'price'    => $priceRange,
      'storage'  => $storageRange,
    ]
  ], JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
  http_response_code(500);
  echo json_encode(['ok'=>false, 'error'=>'Query error']);
}
