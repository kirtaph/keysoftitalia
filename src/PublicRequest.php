<?php
declare(strict_types=1);

require_once __DIR__ . '/BackendHttp.php';
require_once __DIR__ . '/BackendValidation.php';
if (!defined('KSI_JSON_ENDPOINT')) define('KSI_JSON_ENDPOINT', true);
\KeySoftItalia\BackendHttp::startSession();
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');
ini_set('display_errors', '0');
ini_set('log_errors', '1');

set_exception_handler(static function (Throwable $e): never {
    error_log('[Public request] ' . $e->getMessage());
    $validation = $e instanceof InvalidArgumentException;
    \KeySoftItalia\BackendHttp::send(['ok' => false, 'success' => false,
        'message' => $validation ? $e->getMessage() : 'Servizio momentaneamente non disponibile.'], $validation ? 422 : 500);
});

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    header('Allow: POST');
    \KeySoftItalia\BackendHttp::send(['ok' => false, 'success' => false, 'message' => 'Metodo non consentito.'], 405);
}
\KeySoftItalia\BackendValidation::scalarFields($_POST, ['issues', 'defects', 'accessories']);
foreach (['issues', 'defects', 'accessories'] as $field) {
    if (is_array($_POST[$field] ?? null)) {
        foreach ($_POST[$field] as $value) {
            if (!is_string($value)) throw new InvalidArgumentException('Elenco non valido: ' . $field . '.');
        }
    }
}
// Quote previews don't persist customer data and remain available without consent.
$preview = basename($_SERVER['SCRIPT_FILENAME'] ?? '') === 'process_quote.php'
    && ($_POST['mode'] ?? 'preview') === 'preview';
if (!$preview) {
    $token = $_POST['csrf_token'] ?? '';
    if (!is_string($token) || !isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $token)) {
        \KeySoftItalia\BackendHttp::send(['ok' => false, 'success' => false, 'error' => 'csrf',
            'message' => 'Sessione scaduta o richiesta non valida. Ricarica la pagina.'], 403);
    }
    if (!in_array($_POST['privacy'] ?? $_POST['privacy_accepted'] ?? '', ['on', '1', 'true', 'yes'], true)) {
        \KeySoftItalia\BackendHttp::send(['ok' => false, 'success' => false,
            'message' => 'Devi accettare la Privacy Policy.', 'errors' => ['privacy' => 'Consenso richiesto.']], 422);
    }
}
