<?php
declare(strict_types=1);

if (PHP_SAPI !== 'cli') { http_response_code(403); exit('CLI only.'); }
require_once __DIR__ . '/../src/MigrationSafety.php';

$apply = ($argv[1] ?? '') === '--apply';
$filename = $apply ? ($argv[2] ?? '') : ($argv[1] ?? '');
if ($filename === '' || basename($filename) !== $filename || !preg_match('/^[a-zA-Z0-9_]+\.sql$/D', $filename)) {
    fwrite(STDERR, "Usage: php scripts/apply-safe-migration.php [--apply] migration.sql\n");
    exit(1);
}
$path = __DIR__ . '/../database/migrations/' . $filename;
if (!is_file($path)) { fwrite(STDERR, "Migration not found.\n"); exit(1); }
$sql = file_get_contents($path);
try {
    \KeySoftItalia\MigrationSafety::check($sql, $filename);
    if (!$apply) { echo "Validated $filename. No database changes.\n"; exit; }
    require_once __DIR__ . '/../config/config.php';
    if ((int)$pdo->query("SELECT GET_LOCK('ksi_migrations', 0)")->fetchColumn() !== 1) {
        throw new RuntimeException('Another migration is running.');
    }
    try {
        $pdo->exec('CREATE TABLE IF NOT EXISTS migrations (id INT AUTO_INCREMENT PRIMARY KEY,
            filename VARCHAR(255) NOT NULL, executed_at DATETIME DEFAULT CURRENT_TIMESTAMP)');
        $stmt = $pdo->prepare('SELECT id FROM migrations WHERE filename = ?');
        $stmt->execute([$filename]);
        if ($stmt->fetchColumn() !== false) {
            echo "Already applied: $filename\n";
        } else {
            $pdo->exec($sql);
            $stmt = $pdo->prepare('INSERT INTO migrations (filename) VALUES (?)');
            $stmt->execute([$filename]);
            echo "Applied $filename\n";
        }
    } finally {
        $pdo->query("SELECT RELEASE_LOCK('ksi_migrations')");
    }
} catch (Throwable $e) {
    error_log('[Migration] ' . $e->getMessage());
    fwrite(STDERR, "Migration failed; inspect the PHP server log.\n");
    exit(1);
}
