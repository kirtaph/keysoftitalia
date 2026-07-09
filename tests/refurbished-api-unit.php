<?php
declare(strict_types=1);

require_once __DIR__ . '/../src/RefurbishedApi.php';

use KeySoftItalia\Api\ApiException;
use KeySoftItalia\Api\FileStore;
use KeySoftItalia\Api\HmacAuthenticator;
use KeySoftItalia\Api\RefurbishedValidator;

$failures = 0;
$test = static function (string $name, callable $fn) use (&$failures): void {
    try {
        $fn();
        echo "PASS $name\n";
    } catch (Throwable $e) {
        $failures++;
        echo "FAIL $name: {$e->getMessage()}\n";
    }
};
$expectApi = static function (int $status, callable $fn): ApiException {
    try {
        $fn();
    } catch (ApiException $e) {
        if ($e->status !== $status) {
            throw new RuntimeException("Atteso HTTP $status, ricevuto {$e->status}");
        }
        return $e;
    }
    throw new RuntimeException('ApiException attesa');
};

$valid = [
    'external_ref' => 'RIC999999',
    'status' => 'publish',
    'identity' => ['imei' => '123'],
    'device' => ['brand' => 'Apple', 'model' => 'iPhone', 'title' => 'Test'],
    'commercial' => ['price_eur' => 10],
];

$test('payload valido', static fn() => RefurbishedValidator::validateUpsert($valid));
$test('prezzo zero', function () use ($valid, $expectApi): void {
    $payload = $valid;
    $payload['commercial']['price_eur'] = 0;
    $e = $expectApi(422, static fn() => RefurbishedValidator::validateUpsert($payload));
    if (!isset($e->details['commercial.price_eur'])) throw new RuntimeException('Errore prezzo assente');
});
$test('identità mancante', function () use ($valid, $expectApi): void {
    $payload = $valid;
    $payload['identity'] = [];
    $e = $expectApi(422, static fn() => RefurbishedValidator::validateUpsert($payload));
    if (!isset($e->details['identity'])) throw new RuntimeException('Errore identità assente');
});

$runtime = sys_get_temp_dir() . '/ksi-api-test-' . bin2hex(random_bytes(4));
$auth = new HmacAuthenticator('key', 'secret', new FileStore($runtime));
$body = '{"hello":"world"}';
$test('firma valida', static fn() => $auth->authenticate([
    'X-KSI-Key' => 'key',
    'X-KSI-Timestamp' => '1000',
    'X-KSI-Signature' => hash_hmac('sha256', $body, 'secret'),
], $body, 1000));
$test('firma errata', function () use ($auth, $body, $expectApi): void {
    $expectApi(401, static fn() => $auth->authenticate([
        'X-KSI-Key' => 'key', 'X-KSI-Timestamp' => '1001', 'X-KSI-Signature' => str_repeat('0', 64),
    ], $body, 1001));
});
$test('timestamp scaduto', function () use ($auth, $body, $expectApi): void {
    $expectApi(401, static fn() => $auth->authenticate([
        'X-KSI-Key' => 'key',
        'X-KSI-Timestamp' => '1002',
        'X-KSI-Signature' => hash_hmac('sha256', $body, 'secret'),
    ], $body, 2000));
});
$test('replay', function () use ($auth, $body, $expectApi): void {
    $expectApi(401, static fn() => $auth->authenticate([
        'X-KSI-Key' => 'key',
        'X-KSI-Timestamp' => '1000',
        'X-KSI-Signature' => hash_hmac('sha256', $body, 'secret'),
    ], $body, 1000));
});

exit($failures === 0 ? 0 : 1);
