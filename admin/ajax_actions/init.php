<?php
declare(strict_types=1);

/**
 * Centralized bootstrap for admin AJAX action handlers.
 * Handles: session, auth, CSRF, JSON header, safe error handling.
 */

// --- Session start (idempotent) ---
require_once __DIR__ . '/../../src/BackendHttp.php';
require_once __DIR__ . '/../../src/BackendValidation.php';
\KeySoftItalia\BackendHttp::startSession();
if (!defined('KSI_JSON_ENDPOINT')) define('KSI_JSON_ENDPOINT', true);
header('Cache-Control: no-store');
ini_set('display_errors', '0');
ini_set('log_errors', '1');
set_exception_handler(static function (Throwable $e): never { jsonError('Errore del server.', $e); });

// --- Auth check ---
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    header('Content-Type: application/json');
    echo \KeySoftItalia\BackendHttp::encode(['status' => 'error', 'message' => 'Non autorizzato.']);
    exit;
}

// --- JSON response header ---
header('Content-Type: application/json');

// GET is reserved for read actions. Mutations always require POST and CSRF.
$method = $_SERVER['REQUEST_METHOD'] ?? '';
$action = $_POST['action'] ?? $_GET['action'] ?? '';
$readActions = ['get', 'list', 'check', 'get_weekly', 'get_exceptions', 'get_holidays',
    'list_requests', 'list_partners', 'get_partner', 'list_packages', 'get_package',
    'list_showcase', 'get_showcase'];
if ($method !== 'POST' && !($method === 'GET' && is_string($action) && in_array($action, $readActions, true))) {
    http_response_code(405);
    header('Allow: GET, POST');
    echo \KeySoftItalia\BackendHttp::encode(['status' => 'error', 'message' => 'Metodo non consentito.']);
    exit;
}

// --- CSRF validation for POST requests ---
if ($method === 'POST') {
    $token = $_POST['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
    if (!is_string($token) || !isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $token)) {
        http_response_code(403);
        echo \KeySoftItalia\BackendHttp::encode(['status' => 'error', 'message' => 'Richiesta non valida (CSRF).']);
        exit;
    }
}

// Validate the request before opening a database connection.
try {
    \KeySoftItalia\BackendValidation::admin($_GET, $_POST, basename($_SERVER['SCRIPT_FILENAME'] ?? ''));
    $_REQUEST = array_replace($_GET, $_POST);
} catch (InvalidArgumentException $e) {
    jsonError($e->getMessage(), $e);
}
require_once __DIR__ . '/../../config/config.php';

/**
 * Safe JSON error response — NEVER exposes internal details.
 * Logs the real error server-side instead.
 */
function jsonError(string $message = 'Errore del server.', ?Throwable $e = null): never {
    $db = $GLOBALS['pdo'] ?? null;
    if ($db instanceof PDO && $db->inTransaction()) $db->rollBack();
    if ($e !== null) {
        error_log(sprintf(
            '[Admin Action] %s in %s:%d — %s',
            $e->getMessage(),
            $e->getFile(),
            $e->getLine(),
            $e->getTraceAsString()
        ));
    }
    $status = $e instanceof InvalidArgumentException ? 422 : ($e !== null ? 500 : 400);
    \KeySoftItalia\BackendHttp::send(['status' => 'error', 'message' => $message], $status);
}

/**
 * Safe JSON success response.
 */
function jsonSuccess(array $data = []): never {
    \KeySoftItalia\BackendHttp::send(array_merge(['status' => 'success'], $data));
}
