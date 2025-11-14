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
} catch (Throwable $e) {
  http_response_code(500);
  echo json_encode(['ok'=>false,'error'=>'DB connection error']);
  exit;
}

// ------ Params ------
$featured    = isset($_GET['featured']) ? (int)($_GET['featured']==='0'?0:1) : 1;
$page        = max(1, (int)($_GET['page'] ?? 1));
$per         = max(1, min(40, (int)($_GET['per'] ?? 20)));
$brand_id    = isset($_GET['brand_id']) ? (int)$_GET['brand_id'] : null;       // NEW: preferito
$brand_name  = trim((string)($_GET['brand'] ?? ''));                            // fallback
$device_slug = trim((string)($_GET['device_slug'] ?? ''));                      // NEW: preferito
$device_name = trim((string)($_GET['device'] ?? ''));                           // fallback
$grade       = trim((string)($_GET['grade'] ?? ''));
$min_price   = isset($_GET['min_price']) ? (float)$_GET['min_price'] : null;
$max_price   = isset($_GET['max_price']) ? (float)$_GET['max_price'] : null;
$storage_min = isset($_GET['storage_min']) ? (int)$_GET['storage_min'] : null;
$storage_max = isset($_GET['storage_max']) ? (int)$_GET['storage_max'] : null;
$available   = isset($_GET['available']) ? (int)($_GET['available'] ? 1 : 0) : 1;
$q           = trim((string)($_GET['q'] ?? ''));
$sort        = trim((string)($_GET['sort'] ?? 'featured'));

$offset = ($page-1)*$per;

// ------ WHERE dinamico ------
$where = ["p.is_available = :ava"];
$params = [':ava' => $available];

if ($featured) { $where[] = "p.is_featured = 1"; }

if (!is_null($brand_id) && $brand_id > 0) {
  $where[] = "b.id = :brand_id";
  $params[':brand_id'] = $brand_id;
} elseif ($brand_name !== '') {
  $where[] = "b.name = :brand_name";
  $params[':brand_name'] = $brand_name;
}

if ($device_slug !== '') {
  $where[] = "d.slug = :dslug";
  $params[':dslug'] = $device_slug;
} elseif ($device_name !== '') {
  $where[] = "d.name = :dname";
  $params[':dname'] = $device_name;
}

if ($grade  !== '') { $where[] = "p.grade = :grade"; $params[':grade']  = $grade; }
if ($min_price!==null){ $where[]="p.price_eur >= :pmin"; $params[':pmin']=$min_price; }
if ($max_price!==null){ $where[]="p.price_eur <= :pmax"; $params[':pmax']=$max_price; }
if ($storage_min!==null){ $where[]="p.storage_gb >= :smin"; $params[':smin']=$storage_min; }
if ($storage_max!==null){ $where[]="p.storage_gb <= :smax"; $params[':smax']=$storage_max; }
if ($q!==''){
  $where[] = "(m.name LIKE :q OR b.name LIKE :q OR d.name LIKE :q OR p.color LIKE :q OR p.sku LIKE :q)";
  $params[':q'] = '%'.$q.'%';
}
$whereSql = $where ? ('WHERE '.implode(' AND ', $where)) : '';

/** ------ ORDER ------ */
switch ($sort) {
  case 'price_asc':  $order = "p.price_eur ASC, p.created_at DESC"; break;
  case 'price_desc': $order = "p.price_eur DESC, p.created_at DESC"; break;
  case 'newest':     $order = "p.created_at DESC"; break;
  default:           $order = "p.is_featured DESC, p.created_at DESC";
}

/** ------ Count totale per paginazione ------ */
$countSql = "
  SELECT COUNT(*) AS tot
  FROM products p
  JOIN models m ON p.model_id=m.id
  JOIN brands b ON m.brand_id=b.id
  JOIN devices d ON b.device_id=d.id
  $whereSql
";
$stmt = $pdo->prepare($countSql);
$stmt->execute($params);
$total = (int)($stmt->fetchColumn() ?: 0);
error_log("Total products found: $total");

/** ------ Query items ------ */
$sql = "
SELECT
  p.id, p.sku, p.list_price, p.price_eur, p.short_desc, p.full_desc, p.grade, p.storage_gb, p.color,
  p.is_available, p.is_featured,
  b.name AS brand, m.name AS model, d.name AS device,
  COALESCE(MAX(CASE WHEN pi.is_cover=1 THEN pi.path END), MAX(pi.path)) AS image_path
FROM products p
JOIN models  m ON p.model_id = m.id
JOIN brands  b ON m.brand_id = b.id
JOIN devices d ON b.device_id = d.id
LEFT JOIN product_images pi ON pi.product_id = p.id
$whereSql
GROUP BY p.id
ORDER BY $order
LIMIT :limit OFFSET :offset
";
try {
  $stmt = $pdo->prepare($sql);
  foreach ($params as $k=>$v) { $stmt->bindValue($k, $v); }
  $stmt->bindValue(':limit', $per, PDO::PARAM_INT);
  $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
  $stmt->execute();
  $rows = $stmt->fetchAll();
} catch (Throwable $e) {
  http_response_code(500);
  echo json_encode(['ok'=>false,'error'=>'Query error']);
  exit;
}

/** ------ Mapping ------ */
$baseCover = asset('img/recond/placeholder.jpg');

$items = array_map(function($r) use ($baseCover){
  $titleBits = array_filter([$r['brand'] ?? null, $r['model'] ?? null, $r['color'] ?? null]);
  $title = implode(' ', $titleBits);
  $img   = !empty($r['image_path']) ? $r['image_path'] : $baseCover;
  $detailUrl = url('prodotti.php', ['sku' => $r['sku']]);

  return [
    'title'       => $title ?: ($r['brand'].' '.$r['model']),
    'brand'       => $r['brand'] ?? null,
    'model'       => $r['model'] ?? null,
    'device'      => $r['device'] ?? null,
    'grade'       => $r['grade'] ?? null,
    'storage'     => !empty($r['storage_gb']) ? (int)$r['storage_gb'] : null,
    'color'       => $r['color'] ?? null,
    'list_price'  => isset($r['list_price']) ? number_format((float)$r['list_price'], 2, ',', '.') : null,
    'price'       => number_format((float)$r['price_eur'], 2, ',', '.'),
    'img'         => $img,
    'sku'         => $r['sku'],
    'url'         => $detailUrl,
    'excerpt'     => $r['short_desc'] ?? null,
    'full_desc'   => $r['full_desc'] ?? null,
    'is_available'=> (int)$r['is_available'],
    'is_featured' => (int)$r['is_featured'],
  ];
}, $rows);

echo json_encode([
  'ok'       => true,
  'page'     => $page,
  'per'      => $per,
  'total'    => $total,
  'count'    => count($items),
  'products' => $items
], JSON_UNESCAPED_UNICODE);
