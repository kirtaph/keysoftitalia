<?php
if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit('Accesso consentito solo da CLI.');
}
require_once 'config/config.php';
try {
    $stmt = $pdo->query("DESCRIBE products");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($columns as $col) {
        echo $col['Field'] . " (" . $col['Type'] . ")\n";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
