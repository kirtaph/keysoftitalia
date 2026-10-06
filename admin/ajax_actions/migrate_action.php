<?php
require_once __DIR__ . '/init.php';
require_once __DIR__ . '/../../src/MigrationSafety.php';

$action = $_REQUEST['action'] ?? 'check';
$migrationDir = __DIR__ . '/../../database/migrations/';

try {
    $hasTable = $pdo->query("SHOW TABLES LIKE 'migrations'")->fetchColumn() !== false;
    $executed = $hasTable ? $pdo->query("SELECT filename FROM migrations ORDER BY id ASC")->fetchAll(PDO::FETCH_COLUMN) : [];

    if (!is_dir($migrationDir)) {
        throw new RuntimeException('Directory delle migrazioni non disponibile.');
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
        // Preflight all files before the first DDL statement; old dumps can DROP tables.
        foreach ($pending as $file) {
            $sql = file_get_contents($migrationDir . $file);
            if ($sql === false) throw new RuntimeException('Migrazione non leggibile.');
            \KeySoftItalia\MigrationSafety::check($sql, $file);
        }
        if (empty($pending)) {
            jsonSuccess(['message' => 'Nessun aggiornamento necessario.']);
        }

        $executedCount = 0;
        if ((int)$pdo->query("SELECT GET_LOCK('ksi_migrations', 0)")->fetchColumn() !== 1) {
            jsonError('Un aggiornamento del database è già in corso.');
        }
        try {
        $pdo->exec("CREATE TABLE IF NOT EXISTS migrations (
            id INT AUTO_INCREMENT PRIMARY KEY,
            filename VARCHAR(255) NOT NULL,
            executed_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )");
        $alreadyExecuted = $pdo->query('SELECT filename FROM migrations')->fetchAll(PDO::FETCH_COLUMN);
        foreach ($pending as $file) {
            if (in_array($file, $alreadyExecuted, true)) continue;
            $sql = file_get_contents($migrationDir . $file);
            
            try {
                $pdo->exec($sql);
                $stmt = $pdo->prepare("INSERT INTO migrations (filename) VALUES (?)");
                $stmt->execute([$file]);
                $executedCount++;
            } catch (Exception $e) {
                throw new RuntimeException("Errore durante l'esecuzione della migrazione $file.", 0, $e);
            }
        }
        } finally {
            $pdo->query("SELECT RELEASE_LOCK('ksi_migrations')");
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
