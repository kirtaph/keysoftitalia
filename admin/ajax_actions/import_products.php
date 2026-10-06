<?php
require_once __DIR__ . '/init.php';
require_once __DIR__ . '/../../src/ProductImport.php';

$action = $_POST['action'] ?? 'preview';

// Helpers
function parseCurrency($str) {
    return \KeySoftItalia\ProductImport::currency((string)$str);
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
        if (!isset($_FILES['csv_file']) || !is_array($_FILES['csv_file']) || ($_FILES['csv_file']['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
            throw new Exception('Nessun file caricato');
        }
        $csvExt = strtolower(pathinfo($_FILES['csv_file']['name'], PATHINFO_EXTENSION));
        if ($csvExt !== 'csv') {
            throw new Exception('Sono ammessi solo file CSV.');
        }
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $csvMime = finfo_file($finfo, $_FILES['csv_file']['tmp_name']);
        finfo_close($finfo);
        if (!in_array($csvMime, ['text/csv', 'text/plain', 'application/csv', 'application/vnd.ms-excel'])) {
            throw new Exception('Tipo MIME non consentito: ' . $csvMime);
        }
        if ($_FILES['csv_file']['size'] > 10 * 1024 * 1024) {
            throw new Exception('File CSV troppo grande. Massimo 10MB.');
        }

        $file = $_FILES['csv_file']['tmp_name'];
        
        // Detect Separator
        $handle = fopen($file, "r");
        if ($handle === FALSE) throw new Exception('Impossibile aprire il file');
        
        $firstLine = fgets($handle);
        if ($firstLine === false || trim($firstLine) === '') {
            fclose($handle);
            throw new InvalidArgumentException('File CSV vuoto.');
        }
        $separator = (substr_count($firstLine, ';') > substr_count($firstLine, ',')) ? ';' : ',';
        rewind($handle);


        $previewData = [];
        $row = 0;

        while (($data = fgetcsv($handle, 0, $separator)) !== FALSE) {
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
            $rawPriceA = $data[14] ?? '0';
            $rawPriceB = $data[22] ?? '0';
            
            $price = parseCurrency($rawPriceA); 
            if ($price <= 0) {
                $price = parseCurrency($rawPriceB); 
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

        echo \KeySoftItalia\BackendHttp::encode(['status' => 'success', 'data' => $previewData]);

    } elseif ($action === 'import') {
        $products = \KeySoftItalia\ProductImport::products($_POST['products'] ?? '[]');

        // Get Devices map
        $stmt = $pdo->query("SELECT id, name FROM devices");
        $devices = $stmt->fetchAll(PDO::FETCH_KEY_PAIR); 
        $deviceMap = array_flip($devices); 
        foreach ($products as $product) {
            if (!isset($deviceMap[$product['device_type']])) throw new InvalidArgumentException('Tipo dispositivo sconosciuto.');
        }

        $imported = 0;
        $pdo->beginTransaction();

        foreach ($products as $p) {
            $deviceId = $deviceMap[$p['device_type']];
            
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
                number_format((float)$p['price'] * 1.2, 2, '.', ''),
                $p['price'],
                $p['short_desc'],
                $p['full_desc'],
                $p['qty'] > 0 ? 1 : 0
            ]);
            $imported++;
        }

        $pdo->commit();
        echo \KeySoftItalia\BackendHttp::encode(['status' => 'success', 'message' => "Importati $imported prodotti."]);
    } else {
        throw new InvalidArgumentException('Azione non valida.');
    }

} catch (Throwable $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    jsonError($e instanceof InvalidArgumentException ? $e->getMessage() : 'Errore del server.', $e);
}
?>
