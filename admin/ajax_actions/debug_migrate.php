<?php
// admin/ajax_actions/debug_migrate.php

// Mock Session for CLI/Debug
session_start();
$_SESSION['user_id'] = 1;

require_once __DIR__ . '/../../config/config.php';

$migrationDir = __DIR__ . '/../../database/migrations/';

echo "Migration Dir: " . $migrationDir . "\n";
echo "Is Dir? " . (is_dir($migrationDir) ? 'Yes' : 'No') . "\n";

try {
    // 1. Ensure migrations table exists
    $pdo->exec("CREATE TABLE IF NOT EXISTS migrations (
        id INT AUTO_INCREMENT PRIMARY KEY,
        filename VARCHAR(255) NOT NULL,
        executed_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");

    // 2. Get executed migrations
    $stmt = $pdo->query("SELECT filename FROM migrations");
    $executed = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo "Executed Migrations: " . count($executed) . "\n";
    print_r($executed);

    // 3. Scan directory
    $files = scandir($migrationDir);
    $pending = [];

    foreach ($files as $file) {
        if (pathinfo($file, PATHINFO_EXTENSION) === 'sql') {
            echo "Found SQL file: $file\n";
            if (!in_array($file, $executed)) {
                $pending[] = $file;
                echo " -> PENDING\n";
            } else {
                echo " -> ALREADY EXECUTED\n";
            }
        }
    }

    echo "Pending Count: " . count($pending) . "\n";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
