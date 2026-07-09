<?php
declare(strict_types=1);

$base = rtrim($argv[1] ?? '', '/') . '/api/v1/refurbished';
$key = (string)getenv('KSI_API_KEY');
$secret = (string)getenv('KSI_API_SECRET');
if ($base === '/api/v1/refurbished' || $key === '' || $secret === '') {
    fwrite(STDERR, "Uso: KSI_API_KEY=... KSI_API_SECRET=... php scripts/verify-refurbished-api.php https://host\n");
    exit(2);
}

$request = static function (string $method, string $path, string $body, ?int $timestamp = null, bool $badSignature = false) use ($base, $key, $secret): array {
    $timestamp ??= time();
    $signature = $badSignature ? str_repeat('0', 64) : hash_hmac('sha256', $body, $secret);
    $headers = [
        'Content-Type: application/json',
        "X-KSI-Key: $key",
        "X-KSI-Timestamp: $timestamp",
        "X-KSI-Signature: $signature",
    ];
    $context = stream_context_create(['http' => [
        'method' => $method, 'header' => implode("\r\n", $headers), 'content' => $body,
        'ignore_errors' => true, 'timeout' => 20,
    ]]);
    $raw = file_get_contents($base . $path, false, $context);
    $line = $http_response_header[0] ?? '';
    preg_match('/\s(\d{3})\s/', $line, $match);
    return [(int)($match[1] ?? 0), json_decode((string)$raw, true)];
};
$assert = static function (string $name, bool $ok, mixed $actual = null): void {
    echo ($ok ? 'PASS ' : 'FAIL ') . $name . ($ok ? '' : ' ' . json_encode($actual)) . PHP_EOL;
    if (!$ok) $GLOBALS['failed'] = true;
};

[$code] = $request('GET', '/ping', '');
$assert('ping', $code === 200, $code);
[$code] = $request('GET', '/ping', '', null, true);
$assert('firma errata', $code === 401, $code);
[$code] = $request('GET', '/ping', '', time() - 301);
$assert('timestamp scaduto', $code === 401, $code);

$payload = [
    'external_ref' => 'RIC999999', 'status' => 'draft',
    'identity' => ['serial' => 'KSI-API-TEST'],
    'device' => [
        'category' => 'Smartphone', 'brand' => 'Test API', 'model' => 'Modello test',
        'title' => 'Prodotto test API', 'description' => 'Creato dallo script di verifica',
        'specs' => ['Colore' => 'Nero', 'Memoria' => '64GB'],
    ],
    'condition' => ['grade' => 'A', 'battery_pct' => 90, 'accessories' => []],
    'commercial' => ['price_eur' => 1.00, 'warranty_months' => 12],
    'photos' => [], 'source' => ['system' => 'keyos-test', 'device_id' => 999999, 'pushed_at' => date(DATE_ATOM)],
];
$invalid = $payload;
$invalid['commercial']['price_eur'] = 0;
$invalid['identity'] = [];
[$code, $json] = $request('POST', '/upsert', json_encode($invalid, JSON_UNESCAPED_SLASHES));
$assert('validazione prezzo e identità', $code === 422 && isset($json['errors']['commercial.price_eur'], $json['errors']['identity']), [$code, $json]);

$body = json_encode($payload, JSON_UNESCAPED_SLASHES);
[$createCode, $created] = $request('POST', '/upsert', $body);
$assert('create/upsert', in_array($createCode, [200, 201], true) && !empty($created['product_id']), [$createCode, $created]);
sleep(1);
[$updateCode, $updated] = $request('POST', '/upsert', $body);
$assert('update idempotente', $updateCode === 200 && ($updated['product_id'] ?? null) === ($created['product_id'] ?? null), [$updateCode, $updated]);
[$code, $json] = $request('POST', '/RIC999999/status', '{"status":"hidden"}');
$assert('cambio stato', $code === 200 && ($json['status'] ?? null) === 'hidden', [$code, $json]);

exit(!empty($GLOBALS['failed']) ? 1 : 0);
