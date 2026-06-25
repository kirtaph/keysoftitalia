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

// --- Load config & DB ---
require_once __DIR__ . '/../../config/config.php';

// --- JSON response header ---
header('Content-Type: application/json');

// --- CSRF validation for POST requests ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
    if (!isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $token)) {
        http_response_code(403);
        echo json_encode(['status' => 'error', 'message' => 'Richiesta non valida (CSRF).']);
        exit;
    }
}

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
