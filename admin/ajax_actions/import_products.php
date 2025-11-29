<?php
require_once '../../config/config.php';

header('Content-Type: application/json');

// Security Check
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

$action = $_POST['action'] ?? 'preview';

// Helpers
function parseCurrency($str) {
    $str = str_replace(['€', ' ', '.'], '', $str); 
    $str = str_replace(['€', ' '], '', $str);
    $str = str_replace(',', '.', $str);
    return floatval($str);
}

function getOrCreateInfo($pdo, $table, $column, $value, $parentIdCol = null, $parentIdVal = null) {
    if (empty($value)) return null;
    
    $sql = "SELECT id FROM $table WHERE $column = :val";
    $params = [':val' => $value];
    
    if ($parentIdCol && $parentIdVal) {
        $sql .= " AND $parentIdCol = :parent";
        $params[':parent'] = $parentIdVal;
    }
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $id = $stmt->fetchColumn();
    
    if ($id) return $id;
    
    // Create
    $sql = "INSERT INTO $table ($column, is_active";
    if ($parentIdCol) $sql .= ", $parentIdCol";
    $sql .= ") VALUES (:val, 1";
    if ($parentIdCol) $sql .= ", :parent";
    $sql .= ")";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $pdo->lastInsertId();
}

try {
    if ($action === 'preview') {
        if (!isset($_FILES['csv_file'])) {
            throw new Exception('Nessun file caricato');
        }

        $file = $_FILES['csv_file']['tmp_name'];
        $handle = fopen($file, "r");
        if ($handle === FALSE) throw new Exception('Impossibile aprire il file');

        $previewData = [];
        $row = 0;

        while (($data = fgetcsv($handle, 0, ";")) !== FALSE) {
            $row++;
            if ($row === 1) continue; // Skip Header

            // 1. SKU
            $sku = trim($data[1] ?? '');
            if (empty($sku)) {
                $sku = trim($data[0] ?? ''); 
            }
            if (empty($sku)) continue;

            // Check if product exists
            $stmt = $pdo->prepare("SELECT id FROM products WHERE sku = ?");
            $stmt->execute([$sku]);
            if ($stmt->fetchColumn()) continue; // Skip existing

            // 2. Basic Data
            $rawDesc = trim($data[3] ?? '');
            $price = parseCurrency($data[14] ?? '0'); 
            if ($price <= 0) {
                $price = parseCurrency($data[22] ?? '0'); 
            }
            
            $qty = intval($data[6] ?? 0); 
            $fullDesc = trim($data[41] ?? ''); 
            
            // 3. Brand & Model Parsing
            $brandName = trim($data[38] ?? '');
            $modelName = trim($data[39] ?? '');
            
            if (empty($brandName) || empty($modelName)) {
                $descParts = explode(' ', $rawDesc);
                $detectedBrand = '';
                $detectedModelParts = [];
                $knownBrands = ['Samsung', 'Apple', 'Xiaomi', 'Redmi', 'Oppo', 'Huawei', 'Realme', 'Motorola', 'Nokia', 'Honor', 'OnePlus', 'Google', 'Sony', 'LG', 'Asus'];
                
                foreach ($descParts as $part) {
                    if (stripos($part, 'Smartphone') !== false || stripos($part, 'Cellulare') !== false) continue;
                    foreach ($knownBrands as $kb) {
                        if (strcasecmp($part, $kb) === 0) {
                            $detectedBrand = $kb;
                            break;
                        }
                    }
                    if ($detectedBrand && strcasecmp($part, $detectedBrand) === 0) continue; 
                    if (!$detectedBrand && stripos($part, 'Iphone') !== false) {
                        $detectedBrand = 'Apple';
                        $detectedModelParts[] = $part; 
                        continue;
                    }
                    $detectedModelParts[] = $part;
                }
                if (empty($brandName)) $brandName = $detectedBrand ?: 'Generico';
                if (empty($modelName)) $modelName = implode(' ', $detectedModelParts);
            }

            // Clean names
            $brandName = ucfirst(strtolower(str_replace('R-', '', $brandName)));
            $modelName = trim(str_replace('R-', '', $modelName));
            
            $modelName = preg_replace('/\b\d+\/\d+\b/', '', $modelName); 
            $modelName = preg_replace('/\b\d+\s*GB\b/i', '', $modelName); 
            $modelName = preg_replace('/\s+/', ' ', $modelName); 
            $modelName = trim($modelName);

            // Normalize Brands
            if (stripos($brandName, 'iphone') !== false) {
                $brandName = 'Apple';
                if (stripos($modelName, 'iphone') === false) $modelName = 'iPhone ' . $modelName;
            } elseif (stripos($brandName, 'redmi') !== false) {
                $brandName = 'Xiaomi';
                if (stripos($modelName, 'redmi') === false) $modelName = 'Redmi ' . $modelName;
            }

            // 4. Device Logic
            $deviceType = 'Smartphone';
            if (stripos($rawDesc, 'Tablet') !== false || stripos($rawDesc, 'Ipad') !== false) {
                $deviceType = 'Tablet';
            }

            // 5. Storage
            $storage = 0;
            if (preg_match('/(\d+)\s*GB/i', $rawDesc, $matches)) {
                $storage = intval($matches[1]);
            } elseif (preg_match('/(\d+)\s*TB/i', $rawDesc, $matches)) {
                $storage = intval($matches[1]) * 1024;
            } elseif (preg_match('/\/\s*(\d+)/', $rawDesc, $matches)) { 
                $storage = intval($matches[1]);
            }

            // 6. Grade
            $grade = 'Nuovo';
            if (stripos($rawDesc, 'Usato') !== false || stripos($data[45] ?? '', 'Usato') !== false) {
                $grade = 'A';
            }

            $previewData[] = [
                'sku' => $sku,
                'brand' => $brandName,
                'model' => $modelName,
                'device_type' => $deviceType,
                'storage' => $storage,
                'color' => 'Nero', // Default
                'grade' => $grade,
                'price' => $price,
                'qty' => $qty,
                'short_desc' => $rawDesc,
                'full_desc' => $fullDesc
            ];
        }
        fclose($handle);

        echo json_encode(['status' => 'success', 'data' => $previewData]);

    } elseif ($action === 'import') {
        $products = json_decode($_POST['products'] ?? '[]', true);
        if (empty($products)) throw new Exception('Nessun prodotto selezionato');

        // Get Devices map
        $stmt = $pdo->query("SELECT id, name FROM devices");
        $devices = $stmt->fetchAll(PDO::FETCH_KEY_PAIR); 
        $deviceMap = array_flip($devices); 
        $defaultDeviceId = $deviceMap['Smartphone'] ?? 1;

        $imported = 0;
        $pdo->beginTransaction();

        foreach ($products as $p) {
            $deviceId = $deviceMap[$p['device_type']] ?? $defaultDeviceId;
            
            $brandId = getOrCreateInfo($pdo, 'brands', 'name', $p['brand'], 'device_id', $deviceId);
            $modelId = getOrCreateInfo($pdo, 'models', 'name', $p['model'], 'brand_id', $brandId);

            $stmt = $pdo->prepare("INSERT INTO products (
                model_id, sku, color, storage_gb, grade, 
                list_price, price_eur, short_desc, full_desc, 
                is_available, is_featured, created_at
            ) VALUES (
                ?, ?, ?, ?, ?, 
                ?, ?, ?, ?, 
                ?, 0, NOW()
            )");

            $stmt->execute([
                $modelId,
                $p['sku'],
                $p['color'],
                $p['storage'],
                $p['grade'],
                $p['price'] * 1.2, 
                $p['price'],
                $p['short_desc'],
                $p['full_desc'],
                $p['qty'] > 0 ? 1 : 0
            ]);
            $imported++;
        }

        $pdo->commit();
        echo json_encode(['status' => 'success', 'message' => "Importati $imported prodotti."]);
    }

} catch (Exception $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>
