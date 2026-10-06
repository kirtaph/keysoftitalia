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
    error_log('[Public AJAX] ' . $e->getMessage());
    $validation = $e instanceof InvalidArgumentException;
    \KeySoftItalia\BackendHttp::send(['ok' => false,
        'error' => $validation ? 'invalid_input' : 'server_error',
        'message' => $validation ? $e->getMessage() : 'Servizio momentaneamente non disponibile.'], $validation ? 422 : 500);
});
if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'GET') {
    header('Allow: GET');
    \KeySoftItalia\BackendHttp::send(['ok' => false, 'message' => 'Metodo non consentito.'], 405);
}
\KeySoftItalia\BackendValidation::scalarFields($_GET);
