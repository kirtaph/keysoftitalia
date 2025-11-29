<?php
$file = 'test_products.csv';
$handle = fopen($file, "r");

function parseCurrency($str) {
    echo "Raw: [" . $str . "] ";
    $str = str_replace(['€', ' ', '.'], '', $str); 
    echo "After strip: [" . $str . "] ";
    $str = str_replace(',', '.', $str);
    echo "After comma: [" . $str . "] ";
    $val = floatval($str);
    echo "Float: [" . $val . "]\n";
    return $val;
}

$row = 0;
while (($data = fgetcsv($handle, 0, ";")) !== FALSE) {
    $row++;
    if ($row === 1) continue;
    
    echo "Row $row: ";
    $price = parseCurrency($data[14] ?? '0');
}
fclose($handle);
?>
