<?php
require_once __DIR__ . '/init.php';
require_once __DIR__ . '/../../src/ProductImageFile.php';

require_once __DIR__ . '/../../src/ProductUpload.php';
$movedFiles = [];

$action = $_REQUEST['action'] ?? null;

try {
    switch ($action) {
        case 'list':
            $search = $_GET['search'] ?? '';
            $brand_id = $_GET['brand_id'] ?? '';
            $grade = $_GET['grade'] ?? '';
            $status = $_GET['status'] ?? '';

            $sql = "SELECT rp.*, m.name as model_name, b.name as brand_name,
                    (SELECT path FROM product_images WHERE product_id = rp.id ORDER BY is_cover DESC, sort_order ASC LIMIT 1) as cover_image
                    FROM products rp
                    JOIN models m ON rp.model_id = m.id
                    JOIN brands b ON m.brand_id = b.id
                    WHERE 1=1";
            
            $params = [];

            if ($search) {
                $sql .= " AND (m.name LIKE ? OR rp.sku LIKE ?)";
                $params[] = "%$search%";
                $params[] = "%$search%";
            }

            if ($brand_id) {
                $sql .= " AND m.brand_id = ?";
                $params[] = $brand_id;
            }

            if ($grade) {
                $sql .= " AND rp.grade = ?";
                $params[] = $grade;
            }

            if ($status === 'available') {
                $sql .= " AND rp.is_available = 1";
            } elseif ($status === 'featured') {
                $sql .= " AND rp.is_featured = 1";
            } elseif ($status === 'unavailable') {
                $sql .= " AND rp.is_available = 0";
            }

            $sql .= " ORDER BY rp.created_at DESC";

            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo \KeySoftItalia\BackendHttp::encode(['status' => 'success', 'products' => $products]);
            break;

        case 'get':
            $id = $_GET['id'] ?? null;
            if (!$id) {
                throw new Exception('ID prodotto non fornito.');
            }
            $stmt = $pdo->prepare('SELECT * FROM products WHERE id = ?');
            $stmt->execute([$id]);
            $product = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$product) {
                jsonError('Prodotto non trovato.');
            }

            $img_stmt = $pdo->prepare('SELECT id, path, is_cover, sort_order FROM product_images WHERE product_id = ? ORDER BY sort_order');
            $img_stmt->execute([$id]);
            $images = $img_stmt->fetchAll(PDO::FETCH_ASSOC);
            $product['images'] = $images;

            echo \KeySoftItalia\BackendHttp::encode(['status' => 'success', 'product' => $product]);
            break;

        case 'add':
        case 'edit':
            $id = $_POST['id'] ?? null;
            $model_id = $_POST['model_id'];
            $sku = $_POST['sku'];
            $list_price = ($_POST['list_price'] ?? '') !== '' ? $_POST['list_price'] : null;
            $storage_gb = ($_POST['storage_gb'] ?? '') !== '' ? $_POST['storage_gb'] : null;
            $price_eur = $_POST['price_eur'];
            $is_available = isset($_POST['is_available']) ? 1 : 0;
            $is_featured = isset($_POST['is_featured']) ? 1 : 0;

            $uploads = \KeySoftItalia\ProductUpload::images($_FILES['product_images'] ?? null);
            $pdo->beginTransaction();

            if ($action === 'add') {
                $stmt = $pdo->prepare(
                    'INSERT INTO products (model_id, sku, color, storage_gb, grade, list_price, price_eur, short_desc, full_desc, is_available, is_featured) 
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
                );
                $stmt->execute([
                    $model_id, $sku, $_POST['color'], $storage_gb, $_POST['grade'], $list_price, $price_eur,
                    $_POST['short_desc'], $_POST['full_desc'], $is_available, $is_featured
                ]);
                $productId = $pdo->lastInsertId();
            } else {
                $stmt = $pdo->prepare(
                    'UPDATE products SET model_id = ?, sku = ?, color = ?, storage_gb = ?, grade = ?, 
                     list_price = ?, price_eur = ?, short_desc = ?, full_desc = ?, is_available = ?, is_featured = ? WHERE id = ?'
                );
                $stmt->execute([
                    $model_id, $sku, $_POST['color'], $storage_gb, $_POST['grade'], $list_price, $price_eur,
                    $_POST['short_desc'], $_POST['full_desc'], $is_available, $is_featured, $id
                ]);
                $productId = $id;
            }

            $uploadDir = __DIR__ . '/../../assets/img/recond/';
            if ($uploads && !is_dir($uploadDir) && !mkdir($uploadDir, 0755, true) && !is_dir($uploadDir)) {
                throw new RuntimeException('Impossibile creare la cartella immagini.');
            }
            foreach ($uploads as $upload) {
                $fileName = bin2hex(random_bytes(16)) . '.' . $upload['extension'];
                $filePath = $uploadDir . $fileName;
                if (!move_uploaded_file($upload['tmp'], $filePath)) {
                    throw new RuntimeException('Impossibile salvare l’immagine.');
                }
                $movedFiles[] = $filePath;
                $stmt = $pdo->prepare('SELECT COUNT(*) FROM product_images WHERE product_id = ? AND is_cover = 1');
                $stmt->execute([$productId]);
                $isCover = $stmt->fetchColumn() > 0 ? 0 : 1;
                $stmt = $pdo->prepare('INSERT INTO product_images (product_id, path, is_cover) VALUES (?, ?, ?)');
                $stmt->execute([$productId, 'assets/img/recond/' . $fileName, $isCover]);
            }
            $pdo->commit();

            echo \KeySoftItalia\BackendHttp::encode(['status' => 'success', 'message' => 'Prodotto salvato con successo.']);
            break;

        case 'delete':
            $id = $_POST['id'] ?? null;
            if (!$id) {
                throw new Exception('ID prodotto non fornito.');
            }

            // Get image paths before deleting product
            $stmt = $pdo->prepare('SELECT path FROM product_images WHERE product_id = ?');
            $stmt->execute([$id]);
            $images = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Delete product (images in DB will be deleted by ON DELETE CASCADE)
            $stmt = $pdo->prepare('DELETE FROM products WHERE id = ?');
            $stmt->execute([$id]);

            // Delete image files from filesystem
            foreach ($images as $image) {
                if (isset($image['path'])) {
                    $filePath = productImageFile($image['path']);
                    if ($filePath !== null && is_file($filePath)) {
                        unlink($filePath);
                    }
                }
            }

            echo \KeySoftItalia\BackendHttp::encode(['status' => 'success', 'message' => 'Prodotto eliminato con successo.']);
            break;

        case 'delete_image':
            $id = $_POST['id'] ?? null;
            if (!$id) {
                throw new Exception('ID immagine non fornito.');
            }

            // Get image path before deleting from DB
            $stmt = $pdo->prepare('SELECT path FROM product_images WHERE id = ?');
            $stmt->execute([$id]);
            $image = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($image && isset($image['path'])) {
                // Delete from DB
                $stmt = $pdo->prepare('DELETE FROM product_images WHERE id = ?');
                $stmt->execute([$id]);

                // Delete from filesystem
                $filePath = productImageFile($image['path']);
                if ($filePath !== null && is_file($filePath)) {
                    unlink($filePath);
                }
            }

            echo \KeySoftItalia\BackendHttp::encode(['status' => 'success', 'message' => 'Immagine eliminata con successo.']);
            break;

        case 'update_image_details':
            $productId = $_POST['product_id'] ?? null;
            $coverImageId = $_POST['cover_image_id'] ?? null;
            $sortOrder = $_POST['sort_order'] ?? [];

            if (!$productId) {
                throw new Exception('ID prodotto non fornito.');
            }

            $pdo->beginTransaction();

            // Update sort order
            foreach ($sortOrder as $index => $imageId) {
                $stmt = $pdo->prepare('UPDATE product_images SET sort_order = ? WHERE id = ? AND product_id = ?');
                $stmt->execute([$index, $imageId, $productId]);
            }

            // Update cover image
            if ($coverImageId) {
                $stmt = $pdo->prepare('SELECT id FROM product_images WHERE id = ? AND product_id = ?');
                $stmt->execute([$coverImageId, $productId]);
                if (!$stmt->fetchColumn()) {
                    throw new RuntimeException('Immagine di copertina non appartenente al prodotto.');
                }
                $stmt = $pdo->prepare('UPDATE product_images SET is_cover = 0 WHERE product_id = ?');
                $stmt->execute([$productId]);
                $stmt = $pdo->prepare('UPDATE product_images SET is_cover = 1 WHERE id = ? AND product_id = ?');
                $stmt->execute([$coverImageId, $productId]);
            }

            $pdo->commit();
            echo \KeySoftItalia\BackendHttp::encode(['status' => 'success', 'message' => 'Dettagli immagine aggiornati.']);
            break;
        
        default:
            throw new Exception('Azione non valida.');
            break;
    }
} catch (Throwable $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    foreach ($movedFiles as $file) {
        if (is_file($file)) unlink($file);
    }
    jsonError($e instanceof InvalidArgumentException ? $e->getMessage() : 'Errore del server.', $e);
}
