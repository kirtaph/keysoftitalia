<?php
/**
 * Key Soft Italia - Database Installation Script
 * Run this script to create database tables
 * 
 * USAGE: Navigate to /install/install.php in your browser
 */

// Prevent timeout for large operations
set_time_limit(0);

// Define BASE_PATH
define('BASE_PATH', dirname(__DIR__) . '/');

// Include configuration
require_once BASE_PATH . 'config/config.php';

// Start output
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Installazione Database - Key Soft Italia</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .container {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            max-width: 600px;
            width: 100%;
        }
        h1 {
            color: #333;
            margin-bottom: 30px;
            text-align: center;
        }
        .status-box {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
            max-height: 400px;
            overflow-y: auto;
        }
        .status-item {
            padding: 8px 0;
            border-bottom: 1px solid #e9ecef;
        }
        .status-item:last-child {
            border-bottom: none;
        }
        .success { color: #28a745; }
        .error { color: #dc3545; }
        .warning { color: #ffc107; }
        .info { color: #17a2b8; }
        .btn {
            display: inline-block;
            padding: 12px 30px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
            transition: background 0.3s;
        }
        .btn:hover {
            background: #764ba2;
        }
        .alert {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .alert-warning {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffeeba;
        }
        .progress {
            width: 100%;
            height: 30px;
            background: #f0f0f0;
            border-radius: 15px;
            overflow: hidden;
            margin: 20px 0;
        }
        .progress-bar {
            height: 100%;
            background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
            width: 0%;
            transition: width 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🚀 Installazione Database Key Soft Italia</h1>
        
        <?php
        $errors = [];
        $warnings = [];
        $success = [];
        
        // Check PHP version
        if (version_compare(PHP_VERSION, '7.4.0', '<')) {
            $errors[] = "PHP 7.4+ richiesto. Versione attuale: " . PHP_VERSION;
        } else {
            $success[] = "✓ PHP Version: " . PHP_VERSION;
        }
        
        // Check PDO extension
        if (!extension_loaded('pdo_mysql')) {
            $errors[] = "Estensione PDO MySQL non trovata";
        } else {
            $success[] = "✓ PDO MySQL disponibile";
        }
        
        // Show initial checks
        if (!empty($errors)) {
            echo '<div class="alert alert-danger">';
            echo '<strong>⚠️ Errori rilevati:</strong><br>';
            foreach ($errors as $error) {
                echo $error . '<br>';
            }
            echo '</div>';
            echo '<p>Correggi questi errori prima di procedere.</p>';
            echo '</div></body></html>';
            exit;
        }
        
        // If installation is requested
        if (isset($_POST['install'])) {
            echo '<div class="status-box">';
            
            try {
                // Test database connection
                echo '<div class="status-item info">📡 Connessione al database...</div>';
                
                $dsn = "mysql:host=" . DB_HOST . ";charset=utf8mb4";
                $pdo = new PDO($dsn, DB_USER, DB_PASS, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                ]);
                
                echo '<div class="status-item success">✓ Connesso al server MySQL</div>';
                
                // Create database if not exists
                echo '<div class="status-item info">📦 Creazione database...</div>';
                $pdo->exec("CREATE DATABASE IF NOT EXISTS `" . DB_NAME . "` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
                $pdo->exec("USE `" . DB_NAME . "`");
                echo '<div class="status-item success">✓ Database ' . DB_NAME . ' pronto</div>';
                
                // Read SQL file
                echo '<div class="status-item info">📄 Lettura file SQL...</div>';
                $sqlFile = BASE_PATH . 'install/database.sql';
                
                if (!file_exists($sqlFile)) {
                    throw new Exception("File database.sql non trovato");
                }
                
                $sql = file_get_contents($sqlFile);
                echo '<div class="status-item success">✓ File SQL caricato</div>';
                
                // Split SQL into individual queries
                $queries = array_filter(array_map('trim', explode(';', $sql)));
                $totalQueries = count($queries);
                $executedQueries = 0;
                
                echo '<div class="status-item info">⚙️ Esecuzione ' . $totalQueries . ' query...</div>';
                
                // Execute each query
                foreach ($queries as $query) {
                    if (!empty($query) && stripos($query, 'USE keysoftitalia') === false) {
                        try {
                            $pdo->exec($query . ';');
                            $executedQueries++;
                            
                            // Extract table name if it's a CREATE TABLE query
                            if (preg_match('/CREATE TABLE.*?`(\w+)`/i', $query, $matches)) {
                                echo '<div class="status-item success">✓ Tabella ' . $matches[1] . ' creata</div>';
                            }
                            // Extract operation for INSERT queries
                            elseif (preg_match('/INSERT INTO.*?`(\w+)`/i', $query, $matches)) {
                                echo '<div class="status-item success">✓ Dati inseriti in ' . $matches[1] . '</div>';
                            }
                        } catch (PDOException $e) {
                            if (strpos($e->getMessage(), 'already exists') !== false) {
                                echo '<div class="status-item warning">⚠ Tabella già esistente (saltata)</div>';
                            } else {
                                throw $e;
                            }
                        }
                    }
                }
                
                echo '<div class="status-item success"><strong>✅ Installazione completata con successo!</strong></div>';
                echo '</div>';
                
                // Success message
                echo '<div class="alert alert-success">';
                echo '<strong>🎉 Database installato correttamente!</strong><br>';
                echo 'Tutte le tabelle sono state create e i dati di esempio sono stati inseriti.<br><br>';
                echo '<strong>Credenziali Admin:</strong><br>';
                echo 'Username: admin<br>';
                echo 'Password: admin123<br>';
                echo '<small>(Cambia la password al primo accesso!)</small>';
                echo '</div>';
                
                // Security warning
                echo '<div class="alert alert-warning">';
                echo '<strong>⚠️ IMPORTANTE - Sicurezza:</strong><br>';
                echo '1. Elimina o rinomina la cartella /install/ per sicurezza<br>';
                echo '2. Cambia la password admin al primo accesso<br>';
                echo '3. Configura le impostazioni SMTP in config.php per l\'invio email';
                echo '</div>';
                
                echo '<a href="../index.php" class="btn">Vai al Sito</a>';
                
            } catch (Exception $e) {
                echo '</div>';
                echo '<div class="alert alert-danger">';
                echo '<strong>❌ Errore durante l\'installazione:</strong><br>';
                echo $e->getMessage();
                echo '</div>';
                
                // Show debug info if in debug mode
                if (DEBUG_MODE) {
                    echo '<details>';
                    echo '<summary>Dettagli errore (Debug Mode)</summary>';
                    echo '<pre>' . $e->getTraceAsString() . '</pre>';
                    echo '</details>';
                }
            }
            
        } else {
            // Show installation form
            ?>
            <div class="alert alert-warning">
                <strong>⚠️ Attenzione:</strong> Questa procedura creerà/sovrascriverà le tabelle del database.
                Assicurati di avere un backup se stai aggiornando un'installazione esistente.
            </div>
            
            <div class="status-box">
                <div class="status-item success">✓ PHP <?php echo PHP_VERSION; ?></div>
                <div class="status-item success">✓ PDO MySQL disponibile</div>
                <div class="status-item info">📌 Database: <?php echo DB_NAME; ?></div>
                <div class="status-item info">📌 Host: <?php echo DB_HOST; ?></div>
                <div class="status-item info">📌 User: <?php echo DB_USER; ?></div>
            </div>
            
            <form method="post" onsubmit="return confirm('Sei sicuro di voler procedere con l\'installazione?');">
                <button type="submit" name="install" class="btn" style="width: 100%; text-align: center;">
                    🚀 Avvia Installazione
                </button>
            </form>
            <?php
        }
        ?>
    </div>
</body>
</html>