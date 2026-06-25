<?php
require_once __DIR__ . '/init.php';

$action = $_REQUEST['action'] ?? 'check';
$migrationDir = __DIR__ . '/../../database/migrations/';

try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS migrations (
        id INT AUTO_INCREMENT PRIMARY KEY,
        filename VARCHAR(255) NOT NULL,
        executed_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");

    $stmt = $pdo->query("SELECT filename FROM migrations");
    $executed = $stmt->fetchAll(PDO::FETCH_COLUMN);

    if (!is_dir($migrationDir)) {
        mkdir($migrationDir, 0777, true);
    }
    
    $files = scandir($migrationDir);
    $pending = [];
    $lastVersion = 'v1.0.0';

    if (!empty($executed)) {
        $lastFile = end($executed);
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
        jsonSuccess([
            'pending_count' => count($pending),
            'last_version' => $lastVersion,
            'pending_files' => $pending
        ]);
    } elseif ($action === 'execute') {
        if (empty($pending)) {
            jsonSuccess(['message' => 'Nessun aggiornamento necessario.']);
        }

        $executedCount = 0;
        foreach ($pending as $file) {
            $sql = file_get_contents($migrationDir . $file);
            
            try {
                $pdo->exec($sql);
                $stmt = $pdo->prepare("INSERT INTO migrations (filename) VALUES (?)");
                $stmt->execute([$file]);
                $executedCount++;
            } catch (Exception $e) {
                jsonError("Errore durante l'esecuzione della migrazione $file.", $e);
            }
        }

        jsonSuccess([
            'message' => "Database aggiornato con successo! ($executedCount migrazioni eseguite)"
        ]);
    } else {
        jsonError('Azione non valida');
    }

} catch (Throwable $e) {
    jsonError('Errore del server.', $e);
}
?>
