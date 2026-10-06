<?php
declare(strict_types=1);

require_once __DIR__ . '/../src/BackendHttp.php';
require_once __DIR__ . '/../src/BackendValidation.php';
require_once __DIR__ . '/../src/LoginThrottle.php';
require_once __DIR__ . '/../src/MigrationSafety.php';
require_once __DIR__ . '/../src/ProductImport.php';

use KeySoftItalia\BackendHttp;
use KeySoftItalia\BackendValidation;
use KeySoftItalia\LoginThrottle;
use KeySoftItalia\MigrationSafety;
use KeySoftItalia\Api\FileStore;
use KeySoftItalia\ProductImport;

$failures = 0;
$test = static function (string $name, callable $fn) use (&$failures): void {
    try { $fn(); echo "PASS $name\n"; }
    catch (Throwable $e) { ++$failures; echo "FAIL $name: {$e->getMessage()}\n"; }
};
$assert = static function (bool $value): void { if (!$value) throw new RuntimeException('Unexpected result'); };
$reject = static function (callable $fn): void {
    try { $fn(); } catch (InvalidArgumentException $e) { return; }
    throw new RuntimeException('Expected validation error');
};
$test('invalid UTF-8 does not silently erase JSON response', static function () use ($assert): void {
    $json = BackendHttp::encode(['value' => "\xc0\xa8\x01\x01"]);
    $assert(is_array(json_decode($json, true)));
});
$test('unencodable values raise explicit errors', static function () use ($assert): void {
    try { BackendHttp::encode(['amount' => INF]); }
    catch (JsonException $e) { return; }
    $assert(false);
});
$test('money supports zero, cents and Italian decimal separator', static function () use ($assert): void {
    $assert(BackendValidation::money('0', 'price') === '0.00');
    $assert(BackendValidation::money('49,90', 'price') === '49.90');
    $assert(BackendValidation::money('', 'price', true) === null);
});
$test('money rejects negatives, exponent, excess precision and overflow', static function () use ($reject): void {
    foreach (['-1', '1e9', 'NaN', '2.999', '100000000'] as $price) {
        $reject(static fn() => BackendValidation::money($price, 'price'));
    }
});
$test('CSV prices preserve decimal dot, comma and Italian thousands', static function () use ($assert): void {
    foreach (['199.90' => '199.90', '199,90' => '199.90', '1.200,00' => '1200.00', '1.200' => '1200.00', '€ 49,90' => '49.90'] as $input => $expected) {
        $assert(ProductImport::currency($input) === $expected);
    }
});
$test('CSV prices reject malformed groups and negative values', static function () use ($reject): void {
    foreach (['-199.90', '19.9,90', '199 euros', '1e3', '1,234.56'] as $input) {
        $reject(static fn() => ProductImport::currency($input));
    }
});
$test('safe status migration accepted', static fn() => MigrationSafety::check(file_get_contents(__DIR__ . '/../database/migrations/2026_10_05_001_extend_used_quote_statuses.sql'), 'statuses.sql'));
$test('destructive legacy migration refused before execution', static function () use ($reject): void {
    $reject(static fn() => MigrationSafety::check(file_get_contents(__DIR__ . '/../database/migrations/2025_11_29_001_upgrade_used_quotes.sql'), 'legacy.sql'));
});
$test('comments do not cause false destructive migration detections', static fn() => MigrationSafety::check("-- DROP TABLE example\nALTER TABLE example ADD COLUMN active INT;", 'safe.sql'));

$directory = sys_get_temp_dir() . '/ksi-login-test-' . bin2hex(random_bytes(8));
try {
    $store = new FileStore($directory);
    $throttle = new LoginThrottle($store, 60);
    $test('login counter persists across sessions and expires', static function () use ($store, $throttle, $assert): void {
        for ($i = 0; $i < 5; ++$i) $throttle->failed('127.0.0.1', 'admin', 1000);
        $otherSession = new LoginThrottle($store, 60);
        $assert($otherSession->remaining('127.0.0.1', 'ADMIN', 1010) === 50);
        $assert($otherSession->remaining('127.0.0.2', 'admin', 1010) === 0);
        $assert($otherSession->remaining('127.0.0.1', 'admin', 1060) === 0);
    });
    $test('rotating usernames still reaches IP limit', static function () use ($throttle, $assert): void {
        for ($i = 0; $i < 30; ++$i) $throttle->failed('127.0.0.3', 'user-' . $i, 2000);
        $assert($throttle->remaining('127.0.0.3', 'new-name', 2001) === 59);
        $throttle->succeeded('127.0.0.3', 'new-name');
        $assert($throttle->remaining('127.0.0.3', 'new-name', 2001) === 59);
    });
} finally {
    foreach (glob($directory . '/*') ?: [] as $file) unlink($file);
    if (is_dir($directory)) rmdir($directory);
}
exit($failures > 0 ? 1 : 0);
