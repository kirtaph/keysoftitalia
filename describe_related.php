<?php
require_once 'config/config.php';
try {
    echo "--- MODELS ---\n";
    $stmt = $pdo->query("DESCRIBE models");
    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $col) echo $col['Field'] . "\n";

    echo "\n--- BRANDS ---\n";
    $stmt = $pdo->query("DESCRIBE brands");
    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $col) echo $col['Field'] . "\n";

    echo "\n--- PRODUCT_IMAGES ---\n"; // Guessing table name
    $stmt = $pdo->query("SHOW TABLES LIKE '%image%'");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    if ($tables) {
        foreach ($tables as $table) {
            echo "Table: $table\n";
            $stmt2 = $pdo->query("DESCRIBE $table");
            foreach ($stmt2->fetchAll(PDO::FETCH_ASSOC) as $col) echo $col['Field'] . "\n";
        }
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
