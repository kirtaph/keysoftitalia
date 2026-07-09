<?php
declare(strict_types=1);
require_once __DIR__ . '/../../config/config.php';

if (!isset($pdo) || !($pdo instanceof PDO)) {
  echo json_encode(['ok'=>false,'error'=>'database connection error']); exit;
}

header('Content-Type: application/json; charset=utf-8');

$sku = isset($_GET['sku']) ? trim($_GET['sku']) : '';
if ($sku === '') { echo json_encode(['ok'=>false,'error'=>'missing sku']); exit; }

try {
  $stmt = $pdo->prepare("
    SELECT p.id, p.sku, p.title, p.list_price, p.price_eur, p.short_desc, p.full_desc, p.grade, p.storage_gb, p.color,
           p.specs_json, p.battery_pct, p.condition_notes, p.accessories_json, p.warranty_months,
           b.name AS brand, m.name AS model
    FROM products p
    JOIN models  m ON p.model_id = m.id
    JOIN brands  b ON m.brand_id = b.id
    WHERE p.sku = :sku
      AND p.api_status IN ('publish', 'sold')
    LIMIT 1
  ");
  $stmt->execute([':sku'=>$sku]);
  $r = $stmt->fetch(PDO::FETCH_ASSOC);
  if (!$r) { echo json_encode(['ok'=>false,'error'=>'not found']); exit; }

  $stmt2 = $pdo->prepare("SELECT path FROM product_images WHERE product_id = :pid ORDER BY is_cover DESC, sort_order ASC, id ASC");
  $stmt2->execute([':pid'=>$r['id']]);
  $imgs = array_map(function($x){ return $x['path']; }, $stmt2->fetchAll(PDO::FETCH_ASSOC));

  $customSpecs = json_decode((string)($r['specs_json'] ?? ''), true);
  if (!is_array($customSpecs)) $customSpecs = [];
  $baseSpecs = [
    'Marca' => $r['brand'], 'Modello' => $r['model'], 'Colore' => $r['color'],
    'Storage' => $r['storage_gb'] ? ($r['storage_gb'].' GB') : null, 'Grado' => $r['grade'],
  ];
  echo json_encode([
    'ok'=>true,
    'product'=>[
      'sku'         => $r['sku'],
      'title'       => $r['title'],
      'list_price'  => isset($r['list_price']) ? number_format((float)$r['list_price'], 2, ',', '.') : null,
      'price'       => number_format((float)$r['price_eur'], 2, ',', '.'),
      'full_desc'   => $r['full_desc'],
      'images'      => $imgs,
      'specs'       => array_filter(array_merge($baseSpecs, $customSpecs), static fn($v) => $v !== null && $v !== ''),
      'condition'   => [
        'battery_pct' => $r['battery_pct'] !== null ? (int)$r['battery_pct'] : null,
        'notes' => $r['condition_notes'],
        'accessories' => json_decode((string)($r['accessories_json'] ?? '[]'), true) ?: [],
      ],
      'warranty_months' => $r['warranty_months'] !== null ? (int)$r['warranty_months'] : null,
    ]
  ], JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
  echo json_encode(['ok'=>false,'error'=>'query'], JSON_UNESCAPED_UNICODE);
}
