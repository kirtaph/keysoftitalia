<?php
declare(strict_types=1);

require_once __DIR__ . '/../src/ApiCredentialStore.php';

use KeySoftItalia\Api\ApiCredentialStore;

$directory = sys_get_temp_dir() . '/ksi-credentials-' . bin2hex(random_bytes(4));
$path = $directory . '/keyos-api.php';
$store = new ApiCredentialStore($path);

try {
    $first = $store->generate();
    $loaded = $store->load();
    if ($loaded !== $first || !str_starts_with($first['api_key'], 'ksi_') || strlen($first['secret']) !== 64) {
        throw new RuntimeException('Generazione o lettura non valida');
    }
    $second = $store->generate();
    if ($second['api_key'] === $first['api_key'] || $store->load() !== $second) {
        throw new RuntimeException('Rotazione non valida');
    }
    echo "PASS generazione, lettura e rotazione credenziali\n";
} finally {
    if (is_file($path)) unlink($path);
    if (is_dir($directory)) rmdir($directory);
}
