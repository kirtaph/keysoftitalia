<?php
declare(strict_types=1);
session_start();
require_once '../config/config.php';

header('Content-Type: application/json');

// --- PDO ready ---
try {
    if (!isset($pdo) || !$pdo instanceof PDO) {
        // Assumo che in config.php hai $db_dsn, $db_user, $db_pass
        $pdo = new PDO($db_dsn, $db_user, $db_pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    } else {
        // assicuriamoci della fetch mode
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Errore di connessione al database.']);
    exit;
}

// --- Solo POST ---
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Metodo non consentito.']);
    exit;
}

// --- Input & basic validation ---
$username = isset($_POST['username']) ? trim((string)$_POST['username']) : '';
$password = isset($_POST['password']) ? (string)$_POST['password'] : '';

if ($username === '' || $password === '') {
    echo json_encode(['success' => false, 'message' => 'Username e password sono obbligatori.']);
    exit;
}

try {
    // Attenzione: cambia i nomi colonna se nel tuo DB sono diversi
    $stmt = $pdo->prepare('SELECT id, username, password FROM users WHERE username = :u LIMIT 1');
    $stmt->execute([':u' => $username]);
    $user = $stmt->fetch();

    if (!$user) {
        echo json_encode(['success' => false, 'message' => 'Username o password non validi.']);
        exit;
    }

    $stored = (string)$user['password'];
    $verified = false;

    // 1) Hash moderni (bcrypt/argon2)
    if (preg_match('/^\$2y\$/', $stored) || preg_match('/^\$argon2(id|i)\$/', $stored)) {
        $verified = password_verify($password, $stored);

        // Rehash se serve (es. cost diverso o migrazione algoritmo)
        if ($verified && password_needs_rehash($stored, PASSWORD_BCRYPT, ['cost' => 12])) {
            $newHash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
            $upd = $pdo->prepare('UPDATE users SET password = :p WHERE id = :id');
            $upd->execute([':p' => $newHash, ':id' => $user['id']]);
        }
    }
    // 2) Legacy MD5 (32 hex) -> verifica e rehash immediato
    elseif (preg_match('/^[a-f0-9]{32}$/i', $stored)) {
        $verified = hash_equals($stored, md5($password));
        if ($verified) {
            $newHash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
            $upd = $pdo->prepare('UPDATE users SET password = :p WHERE id = :id');
            $upd->execute([':p' => $newHash, ':id' => $user['id']]);
        }
    }
    // 3) Legacy in chiaro -> verifica e rehash immediato
    else {
        $verified = hash_equals($stored, $password);
        if ($verified) {
            $newHash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
            $upd = $pdo->prepare('UPDATE users SET password = :p WHERE id = :id');
            $upd->execute([':p' => $newHash, ':id' => $user['id']]);
        }
    }

    if ($verified) {
        session_regenerate_id(true);
        $_SESSION['user_id']  = (int)$user['id'];
        $_SESSION['username'] = (string)$user['username'];
        echo json_encode(['success' => true]);
        exit;
    } else {
        echo json_encode(['success' => false, 'message' => 'Username o password non validi.']);
        exit;
    }
} catch (PDOException $e) {
    // error_log($e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Errore del database.']);
    exit;
}

