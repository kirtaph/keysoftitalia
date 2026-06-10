<?php
require_once __DIR__ . '/../config/config.php';
try {
    $stmt = $pdo->query("SELECT COUNT(*) FROM products WHERE is_featured = 1");
    echo "Featured products count: " . $stmt->fetchColumn() . "\n";

    $sql = "
    SELECT
      p.id, p.sku,
      p.list_price,             -- listino
      p.price_eur,              -- prezzo
      p.short_desc, p.full_desc,
      p.grade, p.storage_gb, p.color,
      p.is_available, p.is_featured,
      b.name AS brand,
      m.name AS model,
      d.name AS device,
      COALESCE(
        MAX(CASE WHEN pi.is_cover = 1 THEN pi.path END),
        MAX(pi.path)
      ) AS image_path
    FROM products p
    JOIN models  m ON p.model_id = m.id
    JOIN brands  b ON m.brand_id = b.id
    JOIN devices d ON b.device_id = d.id
    LEFT JOIN product_images pi ON pi.product_id = p.id
    WHERE p.is_featured = 1
    GROUP BY p.id
    LIMIT 3
    ";
    $stmt = $pdo->query($sql);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    print_r($products);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
