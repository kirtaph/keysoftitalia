<?php
declare(strict_types=1);

require_once __DIR__ . '/init.php';
require_once __DIR__ . '/../../src/ApiCredentialStore.php';

use KeySoftItalia\Api\ApiCredentialStore;

try {
    if (($_POST['action'] ?? '') !== 'generate') {
        jsonError('Azione non valida.');
    }
    $confirmation = trim((string)($_POST['confirmation'] ?? ''));
    if ($confirmation !== 'RUOTA') {
        jsonError('Conferma non valida.');
    }

    $store = new ApiCredentialStore(BASE_PATH . 'config/runtime/keyos-api.php');
    $credentials = $store->generate();
    error_log(sprintf(
        '[KeyOS API] Credenziali generate/ruotate da user_id=%s alle %s',
        (string)$_SESSION['user_id'],
        $credentials['created_at']
    ));
    jsonSuccess([
        'message' => 'Credenziali generate e attivate.',
        'api_key' => $credentials['api_key'],
        'secret' => $credentials['secret'],
        'created_at' => $credentials['created_at'],
    ]);
} catch (Throwable $e) {
    jsonError('Impossibile generare le credenziali. Verificare i permessi di config/runtime.', $e);
}
