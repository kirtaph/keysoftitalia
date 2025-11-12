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
    SELECT p.id, p.sku, p.price_eur, p.short_desc, p.full_desc, p.grade, p.storage_gb, p.color,
           b.name AS brand, m.name AS model
    FROM products p
    JOIN models  m ON p.model_id = m.id
    JOIN brands  b ON m.brand_id = b.id
    WHERE p.sku = :sku
    LIMIT 1
  ");
  $stmt->execute([':sku'=>$sku]);
  $r = $stmt->fetch(PDO::FETCH_ASSOC);
  if (!$r) { echo json_encode(['ok'=>false,'error'=>'not found']); exit; }

  $stmt2 = $pdo->prepare("SELECT path FROM product_images WHERE product_id = :pid ORDER BY is_cover DESC, sort_order ASC, id ASC");
  $stmt2->execute([':pid'=>$r['id']]);
  $imgs = array_map(function($x){ return $x['path']; }, $stmt2->fetchAll(PDO::FETCH_ASSOC));

  echo json_encode([
    'ok'=>true,
    'product'=>[
      'sku'       => $r['sku'],
      'full_desc' => $r['full_desc'],
      'images'    => $imgs,
      'specs'     => [
        'Marca'    => $r['brand'],
        'Modello'  => $r['model'],
        'Colore'   => $r['color'],
        'Storage'  => $r['storage_gb'] ? ($r['storage_gb'].' GB') : null,
        'Grado'    => $r['grade'],
      ]
    ]
  ], JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
  echo json_encode(['ok'=>false,'error'=>'query'], JSON_UNESCAPED_UNICODE);
}
