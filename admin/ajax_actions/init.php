<?php
declare(strict_types=1);

/**
 * Centralized bootstrap for admin AJAX action handlers.
 * Handles: session, auth, CSRF, JSON header, safe error handling.
 */

// --- Session start (idempotent) ---
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// --- Auth check ---
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => 'Non autorizzato.']);
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
    echo json_encode(['status' => 'error', 'message' => 'Metodo non consentito.']);
    exit;
}

// --- CSRF validation for POST requests ---
if ($method === 'POST') {
    $token = $_POST['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
    if (!is_string($token) || !isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $token)) {
        http_response_code(403);
        echo json_encode(['status' => 'error', 'message' => 'Richiesta non valida (CSRF).']);
        exit;
    }
}

// Validate the request before opening a database connection.
require_once __DIR__ . '/../../config/config.php';

/**
 * Safe JSON error response — NEVER exposes internal details.
 * Logs the real error server-side instead.
 */
function jsonError(string $message = 'Errore del server.', ?Throwable $e = null): never {
    if ($e !== null) {
        error_log(sprintf(
            '[Admin Action] %s in %s:%d — %s',
            $e->getMessage(),
            $e->getFile(),
            $e->getLine(),
            $e->getTraceAsString()
        ));
    }
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => $message]);
    exit;
}

/**
 * Safe JSON success response.
 */
function jsonSuccess(array $data = []): never {
    echo json_encode(array_merge(['status' => 'success'], $data));
    exit;
}
