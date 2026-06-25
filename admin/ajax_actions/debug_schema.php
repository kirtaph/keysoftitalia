<?php
// Security Check: Ensure user is logged in
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

require_once __DIR__ . '/../../config/config.php';

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
