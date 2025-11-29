<?php
// debug_csv_row.php

$line = "14467;;Samsung/A12;Smartphone Samsung A12 4/64;PERMUTE;Telefonia/Smartphone;1;Pz;€ 36,40;0%;€ 36,40;€ 29,20;45408;0;€ 115,00;€ 36,40;€ 36,40;€ 36,40;€ 36,40;€ 36,40;€ 36,40;€ 36,40;€ 115,00;€ 36,40;€ 36,40;€ 36,40;€ 36,40;€ 36,40;€ 36,40;€ 36,40;0%;0%;0%;0%;0%;0%;0%;0%;;;Samsung/A12;;;Usato;;;0;0;0;False;0;0;0;False;;;-1;-2;-3;-4;-5;-6;-7;-8;;;;;;;;;web_sconto_offerta_1;web_sconto_offerta_2;web_sconto_offerta_3;web_sconto_offerta_4;web_sconto_offerta_5;web_sconto_offerta_6;web_sconto_offerta_7;web_sconto_offerta_8;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;0;Smartphone Samsung A12 4/64;0;False;False;False;;";

$data = str_getcsv($line, ";");

echo "Total Columns: " . count($data) . "\n";

// Indices based on user info
$idx_sku = 1;
$idx_desc = 3;
$idx_price = 14;
$idx_brand = 38;
$idx_model = 39;

echo "--- RAW VALUES ---\n";
echo "[$idx_sku] SKU: " . $data[$idx_sku] . "\n";
echo "[$idx_desc] Desc: " . $data[$idx_desc] . "\n";
echo "[$idx_price] Price (Listino A): " . $data[$idx_price] . "\n";
echo "[$idx_brand] Brand: " . $data[$idx_brand] . "\n";
echo "[$idx_model] Model: " . $data[$idx_model] . "\n";

// Test Currency Parsing
function parseCurrency($str) {
    echo "Parsing '$str' -> ";
    $str = str_replace(['€', ' '], '', $str);
    $str = str_replace(',', '.', $str);
    echo "Result: " . floatval($str) . "\n";
    return floatval($str);
}

echo "\n--- PARSING TEST ---\n";
parseCurrency($data[$idx_price]);

// Test Model Parsing
$rawDesc = $data[$idx_desc];
$brandName = trim($data[$idx_brand]);
$modelName = trim($data[$idx_model]);

if (empty($brandName) || empty($modelName)) {
    echo "\nParsing Description for Model...\n";
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

echo "Final Brand: $brandName\n";
echo "Final Model: $modelName\n";

?>
