<?php
// database/migrate.php

// Adjust path to config as needed. Assuming this file is in /database/ and config is in /admin/config/
require_once __DIR__ . '/../config/config.php';

echo "<h1>Database Migration Tool</h1>";
echo "<pre>";

try {
    // 1. Create migrations table if not exists
    $pdo->exec("CREATE TABLE IF NOT EXISTS migrations (
        id INT AUTO_INCREMENT PRIMARY KEY,
        filename VARCHAR(255) NOT NULL,
        executed_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");
    echo "Checked 'migrations' table... OK\n";

    // 2. Get executed migrations
    $stmt = $pdo->query("SELECT filename FROM migrations");
    $executed = $stmt->fetchAll(PDO::FETCH_COLUMN);

    // 3. Scan migrations folder
    $migrationDir = __DIR__ . '/migrations/';
    if (!is_dir($migrationDir)) {
        mkdir($migrationDir, 0777, true);
    }
    
    $files = scandir($migrationDir);
    $pending = [];

    foreach ($files as $file) {
        if (pathinfo($file, PATHINFO_EXTENSION) === 'sql') {
            if (!in_array($file, $executed)) {
                $pending[] = $file;
            }
        }
    }

    // 4. Execute pending migrations
    if (empty($pending)) {
        echo "Database is up to date. No new migrations.\n";
    } else {
        echo "Found " . count($pending) . " pending migrations:\n";
        
        foreach ($pending as $file) {
            echo "Executing: $file ... ";
            
            $sql = file_get_contents($migrationDir . $file);
            
            try {
                // Execute SQL (support multiple statements)
                $pdo->exec($sql);
                
                // Log execution
                $stmt = $pdo->prepare("INSERT INTO migrations (filename) VALUES (?)");
                $stmt->execute([$file]);
                
                echo "DONE\n";
            } catch (Exception $e) {
                echo "FAILED\n";
                echo "Error: " . $e->getMessage() . "\n";
                break; // Stop on first error
            }
        }
    }

} catch (PDOException $e) {
    echo "Critical Error: " . $e->getMessage();
}

echo "</pre>";
echo "<a href='../admin/dashboard.php'>Back to Dashboard</a>";
?>
