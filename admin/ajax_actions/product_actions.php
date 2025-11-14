<?php
include_once '../../config/config.php';

header('Content-Type: application/json');

$action = $_REQUEST['action'] ?? null;

try {
    switch ($action) {
        case 'list':
            $stmt = $pdo->query("
                SELECT rp.*, m.name as model_name, b.name as brand_name
                FROM products rp
                JOIN models m ON rp.model_id = m.id
                JOIN brands b ON m.brand_id = b.id
                ORDER BY rp.created_at DESC
            ");
            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode(['status' => 'success', 'products' => $products]);
            break;

        case 'get':
            $id = $_GET['id'] ?? null;
            if (!$id) {
                throw new Exception('ID prodotto non fornito.');
            }
            $stmt = $pdo->prepare('SELECT * FROM products WHERE id = ?');
            $stmt->execute([$id]);
            $product = $stmt->fetch(PDO::FETCH_ASSOC);

            $img_stmt = $pdo->prepare('SELECT id, path, is_cover, sort_order FROM product_images WHERE product_id = ? ORDER BY sort_order');
            $img_stmt->execute([$id]);
            $images = $img_stmt->fetchAll(PDO::FETCH_ASSOC);
            $product['images'] = $images;

            echo json_encode(['status' => 'success', 'product' => $product]);
            break;

        case 'add':
        case 'edit':
            $id = $_POST['id'] ?? null;
            $model_id = $_POST['model_id'];
            $sku = $_POST['sku'];
            $list_price = $_POST['list_price'] ?: null;
            $price_eur = $_POST['price_eur'];
            $is_available = isset($_POST['is_available']) ? 1 : 0;
            $is_featured = isset($_POST['is_featured']) ? 1 : 0;

            if ($action === 'add') {
                $stmt = $pdo->prepare(
                    'INSERT INTO products (model_id, sku, color, storage_gb, grade, list_price, price_eur, short_desc, full_desc, is_available, is_featured) 
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
                );
                $stmt->execute([
                    $model_id, $sku, $_POST['color'], $_POST['storage_gb'], $_POST['grade'], $list_price, $price_eur, 
                    $_POST['short_desc'], $_POST['full_desc'], $is_available, $is_featured
                ]);
                $productId = $pdo->lastInsertId();
            } else {
                $stmt = $pdo->prepare(
                    'UPDATE products SET model_id = ?, sku = ?, color = ?, storage_gb = ?, grade = ?, 
                     list_price = ?, price_eur = ?, short_desc = ?, full_desc = ?, is_available = ?, is_featured = ? WHERE id = ?'
                );
                $stmt->execute([
                    $model_id, $sku, $_POST['color'], $_POST['storage_gb'], $_POST['grade'], $list_price, $price_eur, 
                    $_POST['short_desc'], $_POST['full_desc'], $is_available, $is_featured, $id
                ]);
                $productId = $id;
            }

            if (isset($_FILES['product_images'])) {
                $uploadDir = '../../assets/img/recond/';
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }

                $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
                $maxSize = 2 * 1024 * 1024; // 2MB

                foreach ($_FILES['product_images']['tmp_name'] as $key => $tmp_name) {
                    if (!empty($tmp_name) && $_FILES['product_images']['error'][$key] == UPLOAD_ERR_OK) {
                        // Validate file type
                        $fileType = mime_content_type($tmp_name);
                        if (!in_array($fileType, $allowedTypes)) {
                            continue; // Skip invalid file types
                        }

                        // Validate file size
                        if ($_FILES['product_images']['size'][$key] > $maxSize) {
                            continue; // Skip files that are too large
                        }

                        $fileName = uniqid() . '-' . basename($_FILES['product_images']['name'][$key]);
                        $filePath = $uploadDir . $fileName;

                        if (move_uploaded_file($tmp_name, $filePath)) {
                            $imagePath = 'assets/img/recond/' . $fileName;
                            
                            // Check if a cover image already exists
                            $stmt = $pdo->prepare('SELECT COUNT(*) FROM product_images WHERE product_id = ? AND is_cover = 1');
                            $stmt->execute([$productId]);
                            $coverExists = $stmt->fetchColumn() > 0;

                            $isCover = !$coverExists && $key === 0 ? 1 : 0;

                            $stmt = $pdo->prepare('INSERT INTO product_images (product_id, path, is_cover) VALUES (?, ?, ?)');
                            $stmt->execute([$productId, $imagePath, $isCover]);
                        }
                    }
                }
            }
            
            echo json_encode(['status' => 'success', 'message' => 'Prodotto salvato con successo.']);
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
                    $filePath = '../../' . $image['path'];
                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }
                }
            }

            echo json_encode(['status' => 'success', 'message' => 'Prodotto eliminato con successo.']);
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
                $filePath = '../../' . $image['path'];
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }

            echo json_encode(['status' => 'success', 'message' => 'Immagine eliminata con successo.']);
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
                $stmt = $pdo->prepare('UPDATE product_images SET is_cover = 0 WHERE product_id = ?');
                $stmt->execute([$productId]);
                $stmt = $pdo->prepare('UPDATE product_images SET is_cover = 1 WHERE id = ? AND product_id = ?');
                $stmt->execute([$coverImageId, $productId]);
            }

            $pdo->commit();
            echo json_encode(['status' => 'success', 'message' => 'Dettagli immagine aggiornati.']);
            break;
        
        default:
            throw new Exception('Azione non valida.');
            break;
    }
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
