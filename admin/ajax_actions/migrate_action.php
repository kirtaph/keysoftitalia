<?php
require_once __DIR__ . '/../../config/config.php';

header('Content-Type: application/json');

// Security Check: Ensure user is logged in
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

$action = $_REQUEST['action'] ?? 'check';
$migrationDir = __DIR__ . '/../../database/migrations/';

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

    // 3. Scan directory
    if (!is_dir($migrationDir)) {
        mkdir($migrationDir, 0777, true);
    }
    
    $files = scandir($migrationDir);
    $pending = [];
    $lastVersion = 'v1.0.0'; // Default

    // Determine last executed version
    if (!empty($executed)) {
        $lastFile = end($executed);
        // Extract version-like string or just use filename
        $lastVersion = $lastFile; 
    }

    foreach ($files as $file) {
        if (pathinfo($file, PATHINFO_EXTENSION) === 'sql') {
            if (!in_array($file, $executed)) {
                $pending[] = $file;
            }
        }
    }

    if ($action === 'check') {
        echo json_encode([
            'status' => 'success',
            'pending_count' => count($pending),
            'last_version' => $lastVersion,
            'pending_files' => $pending
        ]);
    } elseif ($action === 'execute') {
        if (empty($pending)) {
            echo json_encode(['status' => 'success', 'message' => 'Nessun aggiornamento necessario.']);
            exit;
        }

        $executedCount = 0;
        foreach ($pending as $file) {
            $sql = file_get_contents($migrationDir . $file);
            
            try {
                // Execute SQL
                $pdo->exec($sql);
                
                // Log execution
                $stmt = $pdo->prepare("INSERT INTO migrations (filename) VALUES (?)");
                $stmt->execute([$file]);
                
                $executedCount++;
            } catch (Exception $e) {
                echo json_encode([
                    'status' => 'error', 
                    'message' => "Errore nel file $file: " . $e->getMessage()
                ]);
                exit;
            }
        }

        echo json_encode([
            'status' => 'success', 
            'message' => "Database aggiornato con successo! ($executedCount migrazioni eseguite)"
        ]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Azione non valida']);
    }

} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Errore Database: ' . $e->getMessage()]);
}
?>
