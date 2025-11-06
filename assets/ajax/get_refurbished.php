<?php
/**
 * AJAX: get_refurbished
 * Ritorna i ricondizionati per lo slider home (JSON)
 * Parametri GET:
 *   featured=1|0 (default 1 → solo in vetrina)
 *   limit=numero (default 5, max 20)
 */

declare(strict_types=1);

session_start();

if (!defined('BASE_PATH')) {
    define('BASE_PATH', dirname(dirname(__DIR__)) . '/');
    require_once BASE_PATH . 'config/config.php';
}
header('Content-Type: application/json; charset=utf-8');

// Assicura $pdo
if (!isset($pdo) || !($pdo instanceof PDO)) {
  try {
    $pdo = new PDO($db_dsn, $db_user, $db_pass, [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
  } catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => 'DB connection error']);
    exit;
  }
}

// Helpers
$featured = isset($_GET['featured']) ? (int)($_GET['featured'] === '0' ? 0 : 1) : 1;
$limit    = isset($_GET['limit']) ? max(1, min(20, (int)$_GET['limit'])) : 5;

// Query
$sql = "
SELECT
  p.id,
  p.sku,
  p.price_eur,
  p.short_desc,
  p.full_desc,
  p.grade,
  p.storage_gb,
  p.color,
  b.name  AS brand,
  m.name  AS model,
  d.name  AS device,
  COALESCE(
    MAX(CASE WHEN pi.is_cover = 1 THEN pi.path END),
    MAX(pi.path)
  ) AS image_path
FROM refurbished_products p
JOIN models  m ON p.model_id = m.id
JOIN brands  b ON m.brand_id = b.id
JOIN devices d ON b.device_id = d.id
LEFT JOIN product_images pi ON pi.product_id = p.id
WHERE p.is_available = 1
  ".($featured ? "AND p.is_featured = 1" : "")."
GROUP BY p.id
ORDER BY p.is_featured DESC, p.created_at DESC
LIMIT :limit
";

try {
  $stmt = $pdo->prepare($sql);
  $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
  $stmt->execute();
  $rows = $stmt->fetchAll();
} catch (Throwable $e) {
  http_response_code(500);
  echo json_encode(['ok' => false, 'error' => 'Query error']);
  exit;
}

// Mapping output
$baseCover = asset('img/recond/placeholder.avif'); // metti un placeholder reale
$out = [];
foreach ($rows as $r) {
  $titleBits = array_filter([$r['brand'] ?? null, $r['model'] ?? null]);
  //if (!empty($r['storage_gb'])) $titleBits[] = ($r['storage_gb'].'GB');
  if (!empty($r['color']))      $titleBits[] = $r['color'];
  //if (!empty($r['grade']))      $titleBits[] = 'Grado '.$r['grade'];

  $title = implode(' ', $titleBits);
  $img   = !empty($r['image_path']) ? $r['image_path'] : $baseCover;

  // URL dettaglio (adatta se usi routing diverso)
  $detailUrl = url('ricondizionati.php', ['sku' => $r['sku']]);

  $out[] = [
    'title'   => $title ?: ($r['brand'].' '.$r['model']),
    'grade'   => $r['grade'] ?? null,
    'storage' => !empty($r['storage_gb']) ? (int)$r['storage_gb'] : null,
    'price'   => number_format((float)$r['price_eur'], 2, ',', '.'),
    'img'     => $img,
    'sku'     => $r['sku'],
    'url'     => $detailUrl,
    'excerpt' => $r['short_desc'] ?? null,
  ];
}

echo json_encode([
  'ok'       => true,
  'count'    => count($out),
  'products' => $out
], JSON_UNESCAPED_UNICODE);
