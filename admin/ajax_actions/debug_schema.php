<?php
require_once 'e:/xampp/htdocs/keysoftitalia/config/config.php';

try {
    $tables = ['issues', 'brands', 'models'];
    foreach ($tables as $table) {
        echo "<h2>Table: $table</h2>";
        $stmt = $pdo->query("DESCRIBE `$table`");
        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo "<pre>";
        print_r($columns);
        echo "</pre>";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
