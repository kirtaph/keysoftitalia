<?php
// test_import_cli.php
require_once 'config/config.php';

// Mock Session
session_start();
$_SESSION['user_id'] = 1;

$file = 'test_products.csv';
$handle = fopen($file, "r");

if ($handle === FALSE) {
    die("Error opening file");
}

// Helpers
function parseCurrency($str) {
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

$imported = 0;
$row = 0;

// Get Devices map
$stmt = $pdo->query("SELECT id, name FROM devices");
$devices = $stmt->fetchAll(PDO::FETCH_KEY_PAIR); 
$deviceMap = array_flip($devices); 
$defaultDeviceId = $deviceMap['Smartphone'] ?? 1;

try {
    $pdo->beginTransaction();

    while (($data = fgetcsv($handle, 0, ";")) !== FALSE) {
        $row++;
        if ($row === 1) continue; // Skip Header

        $sku = trim($data[0] ?? '');
        if (empty($sku)) continue;

        echo "Processing SKU: $sku\n";

        // Check if product exists
        $stmt = $pdo->prepare("SELECT id FROM products WHERE sku = ?");
        $stmt->execute([$sku]);
        if ($stmt->fetchColumn()) {
            echo " -> Skipped (Exists)\n";
            continue;
        }

        $rawDesc = trim($data[3] ?? '');
        $price = parseCurrency($data[22] ?? '0');
        $qty = intval($data[6] ?? 0);
        $isAvailable = $qty > 0 ? 1 : 0;
        $fullDesc = trim($data[41] ?? '');
        
        $rawGrade = strtolower(trim($data[45] ?? ''));
        $grade = 'Nuovo'; 
        if (strpos($rawGrade, 'usato') !== false) {
            $grade = 'A'; 
        }

        $rawCat = $data[5] ?? '';
        $deviceId = $defaultDeviceId;
        $parts = explode('/', $rawCat);
        $catName = end($parts);
        if (isset($deviceMap[$catName])) {
            $deviceId = $deviceMap[$catName];
        }

        $brandName = trim($data[38] ?? '');
        $modelName = trim($data[39] ?? '');
        
        if (empty($brandName) || empty($modelName)) {
            $supplierCode = $data[2] ?? '';
            if (strpos($supplierCode, '/') !== false) {
                $parts = explode('/', $supplierCode);
                if (empty($brandName)) $brandName = $parts[0];
                if (empty($modelName)) $modelName = $parts[1];
            }
        }
        
        if (empty($brandName)) {
            if (stripos($rawDesc, 'Samsung') !== false) $brandName = 'Samsung';
            elseif (stripos($rawDesc, 'Iphone') !== false) $brandName = 'Apple';
            elseif (stripos($rawDesc, 'Redmi') !== false) $brandName = 'Xiaomi';
            elseif (stripos($rawDesc, 'Xiaomi') !== false) $brandName = 'Xiaomi';
            elseif (stripos($rawDesc, 'Oppo') !== false) $brandName = 'Oppo';
            else $brandName = 'Generico';
        }
        
        if (empty($modelName)) {
            $modelName = substr($rawDesc, 0, 50);
        }

        $brandName = ucfirst(strtolower(str_replace('R-', '', $brandName)));
        $modelName = str_replace('R-', '', $modelName);

        echo " -> Brand: $brandName, Model: $modelName\n";

        $brandId = getOrCreateInfo($pdo, 'brands', 'name', $brandName, 'device_id', $deviceId);
        $modelId = getOrCreateInfo($pdo, 'models', 'name', $modelName, 'brand_id', $brandId);

        $storage = 0;
        if (preg_match('/(\d+)\s*GB/i', $rawDesc, $matches)) {
            $storage = intval($matches[1]);
        } elseif (preg_match('/(\d+)\s*TB/i', $rawDesc, $matches)) {
            $storage = intval($matches[1]) * 1024;
        }
        echo " -> Storage: $storage GB\n";

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
            $sku,
            'Nero', 
            $storage,
            $grade,
            $price * 1.2, 
            $price,
            $rawDesc,
            $fullDesc,
            $isAvailable
        ]);

        $imported++;
        echo " -> Imported!\n";
    }

    $pdo->commit();
    echo "Total Imported: $imported\n";

} catch (Exception $e) {
    $pdo->rollBack();
    echo "Error: " . $e->getMessage();
}

fclose($handle);
?>
