<?php
declare(strict_types=1);
ini_set('display_errors', '0');
ini_set('log_errors', '1');
require_once __DIR__ . '/../src/BackendHttp.php';
require_once __DIR__ . '/../src/LoginThrottle.php';
\KeySoftItalia\BackendHttp::startSession();
if (!defined('KSI_JSON_ENDPOINT')) define('KSI_JSON_ENDPOINT', true);
if (!defined('KSI_ADMIN_LOGIN')) define('KSI_ADMIN_LOGIN', true);
header('Cache-Control: no-store');
set_exception_handler(static function (Throwable $e): never {
    error_log('[Admin login] ' . $e->getMessage());
    \KeySoftItalia\BackendHttp::send(['success' => false, 'message' => 'Accesso momentaneamente non disponibile.'], 500);
});
require_once __DIR__ . '/../config/config.php';

header('Content-Type: application/json');

// --- Rate limiting ---
$maxAttempts = 5;
$blockMinutes = 15;
$attemptsKey = 'login_attempts';
$blockKey = 'login_blocked_until';

if (isset($_SESSION[$blockKey]) && time() < $_SESSION[$blockKey]) {
    $remaining = ceil(($_SESSION[$blockKey] - time()) / 60);
    echo json_encode(['success' => false, 'message' => "Troppi tentativi. Riprova tra {$remaining} minuto/i."]);
    exit;
}

// Reset block if expired
if (isset($_SESSION[$blockKey]) && time() >= $_SESSION[$blockKey]) {
    unset($_SESSION[$attemptsKey], $_SESSION[$blockKey]);
}

// --- PDO ready ---
if (!isset($pdo) || !$pdo instanceof PDO) {
    echo json_encode(['success' => false, 'message' => 'Errore di connessione al database.']);
    exit;
}

// --- Solo POST ---
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header('Allow: POST');
    echo json_encode(['success' => false, 'message' => 'Metodo non consentito.']);
    exit;
}

// --- Input & basic validation ---
if (!is_string($_POST['username'] ?? '') || !is_string($_POST['password'] ?? '')) {
    \KeySoftItalia\BackendHttp::send(['success' => false, 'message' => 'Credenziali non valide.'], 422);
}
$username = isset($_POST['username']) ? trim((string)$_POST['username']) : '';
$password = isset($_POST['password']) ? (string)$_POST['password'] : '';

if ($username === '' || $password === '') {
    echo json_encode(['success' => false, 'message' => 'Username e password sono obbligatori.']);
    exit;
}

try {
    $throttle = new \KeySoftItalia\LoginThrottle(new \KeySoftItalia\Api\FileStore(BASE_PATH . 'config/runtime/login'));
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $remaining = $throttle->remaining($ip, $username);
    if ($remaining > 0) {
        header('Retry-After: ' . $remaining);
        \KeySoftItalia\BackendHttp::send(['success' => false, 'message' => 'Troppi tentativi. Riprova più tardi.'], 429);
    }
    $stmt = $pdo->prepare('SELECT id, username, password FROM users WHERE username = :u LIMIT 1');
    $stmt->execute([':u' => $username]);
    $user = $stmt->fetch();

    if (!$user) {
        $throttle->failed($ip, $username);
        $_SESSION[$attemptsKey] = ($_SESSION[$attemptsKey] ?? 0) + 1;
        if ($_SESSION[$attemptsKey] >= $maxAttempts) {
            $_SESSION[$blockKey] = time() + ($blockMinutes * 60);
        }
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
        $throttle->succeeded($ip, $username);
        // Reset rate limiter on success
        unset($_SESSION[$attemptsKey], $_SESSION[$blockKey]);
        session_regenerate_id(true);
        $_SESSION['user_id']  = (int)$user['id'];
        $_SESSION['username'] = (string)$user['username'];
        echo json_encode(['success' => true]);
        exit;
    } else {
        $throttle->failed($ip, $username);
        // Track failed attempt
        $_SESSION[$attemptsKey] = ($_SESSION[$attemptsKey] ?? 0) + 1;
        if ($_SESSION[$attemptsKey] >= $maxAttempts) {
            $_SESSION[$blockKey] = time() + ($blockMinutes * 60);
        }
        echo json_encode(['success' => false, 'message' => 'Username o password non validi.']);
        exit;
    }
} catch (PDOException $e) {
    error_log('[Admin login database] ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Errore del database.']);
    exit;
}

