<?php
declare(strict_types=1);

// Explicit local integration suite: real MySQL, real HTTP/multipart parsing.
// Each request owns an outer transaction which always rolls back. Production
// commits use savepoints. Mail is captured; uploaded test files are removed.
if (PHP_SAPI === 'cli-server') {
    $expectedToken = getenv('KSI_TEST_TOKEN') ?: '';
    if (strlen($expectedToken) < 32 || !hash_equals($expectedToken, $_SERVER['HTTP_X_KSI_TEST_TOKEN'] ?? '')) {
        http_response_code(403); exit;
    }
    $scenario = json_decode(base64_decode($_SERVER['HTTP_X_KSI_SCENARIO'] ?? ''), true, 512, JSON_THROW_ON_ERROR);
    $root = dirname(__DIR__);
    $mail = [];
    function send_assistance_email(array $data, array $opts = []): array {
        $GLOBALS['mail'][] = ['data' => $data, 'opts' => $opts];
        return ['ok' => empty($GLOBALS['scenario']['mail_failure']), 'error' => empty($GLOBALS['scenario']['mail_failure']) ? null : 'Test transport failure'];
    }
    require $root . '/config/config.php';
    if (!in_array(DB_HOST, ['localhost', '127.0.0.1', '::1'], true)) {
        throw new RuntimeException('Integration tests require a loopback MySQL host.');
    }
    final class IntegrationPdo extends PDO {
        private int $depth = 0;
        public function beginTransaction(): bool { $this->exec('SAVEPOINT ksi_test_' . ++$this->depth); return true; }
        public function commit(): bool { $this->exec('RELEASE SAVEPOINT ksi_test_' . $this->depth--); return true; }
        public function rollBack(): bool {
            if ($this->depth) { $this->exec('ROLLBACK TO SAVEPOINT ksi_test_' . $this->depth--); return true; }
            return parent::rollBack();
        }
        public function startTest(): void { parent::beginTransaction(); }
        public function finishTest(): void { if (parent::inTransaction()) parent::rollBack(); }
    }
    $pdo = new IntegrationPdo('mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET,
        DB_USER, DB_PASS, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]);
    $tables = ['quotes', 'used_device_quotes', 'repair_bookings', 'products', 'product_images', 'brands', 'models', 'devices', 'liberty_demo_requests', 'telephony_requests', 'utility_requests', 'telephony_promotions', 'utility_promotions', 'telephony_partners', 'utility_partners', 'flyers', 'videos', 'team_members', 'ks_store_hours_weekly', 'ks_store_hours_exceptions', 'ks_store_holidays'];
    $tables = array_merge($tables, ['admin_notifications','admin_notification_reads','admin_push_subscriptions']);
    $counts = [];
    foreach ($tables as $table) {
        $stmt = $pdo->prepare('SELECT ENGINE FROM information_schema.TABLES WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ?');
        $stmt->execute([DB_NAME, $table]);
        if ($stmt->fetchColumn() !== 'InnoDB') throw new RuntimeException('Rollback requires InnoDB: ' . $table);
        $counts[$table] = (int)$pdo->query("SELECT COUNT(*) FROM `$table`")->fetchColumn();
    }
    $pdo->startTest();
    $_SESSION = empty($scenario['unauthenticated']) ? ['user_id' => 1, 'csrf_token' => 'integration-token'] : [];
    $prefix = 'KSI_TEST_' . bin2hex(random_bytes(6));
    $fixtureId = null;
    $insert = static function (string $table, array $values) use ($pdo): string {
        $fields = implode(',', array_keys($values));
        $marks = implode(',', array_fill(0, count($values), '?'));
        $pdo->prepare("INSERT INTO `$table` ($fields) VALUES ($marks)")->execute(array_values($values));
        return $pdo->lastInsertId();
    };
    $deviceId = $pdo->query('SELECT id FROM devices ORDER BY id LIMIT 1')->fetchColumn();
    $modelId = $pdo->query('SELECT id FROM models ORDER BY id LIMIT 1')->fetchColumn();
    $customer = ['customer_first_name' => $prefix, 'customer_last_name' => '<b>Test & prova</b>',
        'customer_email' => 'test@example.invalid', 'customer_phone' => '0000000000'];
    $fixture = $scenario['fixture'] ?? '';
    if ($fixture === 'quotes') {
        $fixtureId = $insert('quotes', ['device_id' => $deviceId, 'brand_text' => $prefix, 'model_text' => 'Test',
            'problems_json' => '["Test"]', 'first_name' => $prefix, 'last_name' => '<b>Test & prova</b>',
            'email' => 'test@example.invalid', 'phone' => '0000000000', 'ip_address' => inet_pton('192.168.1.255')]);
    } elseif ($fixture === 'used_device_quotes') {
        $fixtureId = $insert($fixture, $customer + ['device_type' => 'smartphone', 'brand_name' => $prefix,
            'model_name' => 'Test', 'device_condition' => 'buono', 'defects' => '[]', 'accessories' => '[]',
            'ip_address' => inet_pton('192.168.1.255')]);
    } elseif ($fixture === 'repair_bookings') {
        $fixtureId = $insert($fixture, $customer + ['device_type' => 'smartphone', 'brand_name' => $prefix,
            'model_name' => 'Test', 'preferred_date' => date('Y-m-d', strtotime('+2 days')), 'preferred_time_slot' => 'mattina']);
    } elseif ($fixture === 'products') {
        $fixtureId = $insert($fixture, ['model_id' => $modelId, 'sku' => $prefix, 'price_eur' => '100.00']);
        if (!empty($scenario['image'])) {
            $imageId = $insert('product_images', ['product_id' => $fixtureId, 'path' => 'https://example.invalid/test.png', 'is_cover' => 1]);
            $_POST['cover_image_id'] = $imageId;
            $_POST['sort_order'] = [$imageId];
        }
    } elseif (in_array($fixture, ['telephony_promotions', 'utility_promotions'], true)) {
        $values = ['plan_name' => $prefix, 'price' => '10.50', 'status' => 1];
        if ($fixture === 'telephony_promotions') $values['operator_name'] = 'Test';
        $fixtureId = $insert($fixture, $values);
        $_POST['promotion_id'] = $fixtureId;
    }
    if (in_array($fixture, ['telephony_partners', 'utility_partners'], true)) {
        $fixtureId = $insert($fixture, ['name' => $prefix, 'logo_path' => null]);
        $_POST['partner_id'] = $fixtureId;
        if (!empty($scenario['promotion'])) {
            $values = ['partner_id' => $fixtureId, 'plan_name' => $prefix, 'price' => '10.50'];
            if ($fixture === 'telephony_partners') $values['operator_name'] = $prefix;
            $fixtureId = $insert(str_replace('_partners', '_promotions', $fixture), $values);
        }
    } elseif ($fixture === 'videos') {
        $fixtureId = $insert('videos', ['title' => $prefix, 'fb_video_url' => 'https://www.facebook.com/watch/?v=1', 'is_featured' => 1]);
    } elseif ($fixture === 'flyers') {
        $fixtureId = $insert('flyers', ['title' => $prefix, 'slug' => $prefix, 'start_date' => '2099-01-01', 'end_date' => '2099-02-01']);
    } elseif ($fixture === 'team_members') {
        $fixtureId = $insert('team_members', ['name' => $prefix, 'role' => 'Test', 'photo_path' => 'https://example.invalid/photo.png']);
    } elseif ($fixture === 'ks_store_hours_weekly') {
        $pdo->exec('DELETE FROM ks_store_hours_weekly WHERE dow=7 AND seg=2');
        if (empty($scenario['missing_weekly'])) $fixtureId = $insert($fixture, ['dow' => 7, 'seg' => 2, 'open_time' => '09:00', 'close_time' => '13:00']);
    } elseif ($fixture === 'ks_store_hours_exceptions') {
        $pdo->exec("DELETE FROM ks_store_hours_exceptions WHERE date='2099-02-02'");
        $fixtureId = $insert($fixture, ['date' => '2099-02-02', 'seg' => 1, 'is_closed' => 1]);
    } elseif ($fixture === 'ks_store_holidays') {
        $fixtureId = $insert($fixture, ['name' => $prefix, 'rule_type' => 'fixed', 'month' => 2, 'day' => 2]);
    } elseif (in_array($fixture, ['telephony_requests', 'utility_requests'], true)) {
        $values = ['operator_name' => $prefix, 'plan_name' => 'Test', 'current_spend' => '29.90', 'phone' => '0000000000', 'estimated_savings' => '100.00'];
        if ($fixture === 'utility_requests') $values['utility_type'] = 'luce';
        $fixtureId = $insert($fixture, $values);
    }
    foreach (['_GET', '_POST'] as $key) {
        foreach ($GLOBALS[$key] as &$value) {
            if ($value === '@fixture') $value = $fixtureId;
            if ($value === '@model') $value = $modelId;
            if ($value === '@sku') $value = $prefix;
        }
        unset($value);
    }
    $_REQUEST = array_replace($_GET, $_POST);
    $oldMedia = null;
    if (!empty($scenario['old_media']) && $fixture === 'flyers') {
        $path = 'uploads/flyers/' . $prefix . '.png';
        $oldMedia = $root . '/' . $path;
        file_put_contents($oldMedia, 'Test old file');
        $pdo->prepare('UPDATE flyers SET cover_image = ? WHERE id = ?')->execute([$path, $fixtureId]);
    }
    ob_start();
    register_shutdown_function(static function () use ($pdo, $tables, $counts, $scenario, $fixtureId, $prefix, &$mail, $oldMedia): void {
        $output = ob_get_clean();
        $rows = [];
        $paths = [];
        $covers = 0;
        $verified = false;
        $oldMediaExists = $oldMedia && is_file($oldMedia);
        try {
            $table = $scenario['inspect'] ?? null;
            if ($table && in_array($table, $tables, true)) {
                // Only fixture rows or rows freshly inserted in this uncommitted transaction.
                $id = ($table === ($scenario['fixture'] ?? '') || (!empty($scenario['promotion']) && str_ends_with($table, '_promotions'))) ? $fixtureId : $pdo->lastInsertId();
                if (isset($scenario['inspect_sku']) && $table === 'products' && str_starts_with($scenario['inspect_sku'], 'KSI_IMPORT_TEST_')) {
                    $stmt = $pdo->prepare('SELECT * FROM products WHERE sku = ?'); $stmt->execute([$scenario['inspect_sku']]);
                } elseif ($table === 'products' && !isset($scenario['fixture'])) {
                    $stmt = $pdo->prepare('SELECT * FROM products WHERE sku = ?'); $stmt->execute([$prefix]);
                } elseif ($table === 'ks_store_hours_weekly' && !empty($scenario['missing_weekly'])) {
                    $stmt = $pdo->query('SELECT * FROM ks_store_hours_weekly WHERE dow=7 AND seg=2');
                } elseif ($table === 'ks_store_hours_exceptions' && !empty($scenario['inspect_date'])) {
                    $stmt = $pdo->query("SELECT * FROM ks_store_hours_exceptions WHERE date='2099-02-02' ORDER BY seg");
                } elseif (in_array($table, ['flyers','videos','team_members','telephony_partners','utility_partners','ks_store_holidays'], true) && $table !== ($scenario['fixture'] ?? '')) {
                    $field = in_array($table, ['flyers','videos'], true) ? 'title' : 'name';
                    $stmt = $pdo->prepare("SELECT * FROM `$table` WHERE `$field` = ?"); $stmt->execute([$prefix]);
                } else {
                    $stmt = $pdo->prepare("SELECT * FROM `$table` WHERE id = ?"); $stmt->execute([$id]);
                }
                $rows = $stmt->fetchAll();
                foreach ($rows as &$row) unset($row['ip_address']);
                unset($row);
            }
            $stmt = $pdo->prepare('SELECT pi.path FROM product_images pi JOIN products p ON pi.product_id = p.id WHERE p.sku LIKE ?');
            $stmt->execute([$prefix . '%']); $paths = $stmt->fetchAll(PDO::FETCH_COLUMN);
            foreach (['telephony_partners' => ['name','logo_path'], 'utility_partners' => ['name','logo_path'], 'flyers' => ['title','cover_image','pdf_file'], 'videos' => ['title','cover_image'], 'team_members' => ['name','photo_path']] as $mediaTable => $fields) {
                $field = array_shift($fields);
                $stmt = $pdo->prepare("SELECT " . implode(',', $fields) . " FROM `$mediaTable` WHERE `$field` = ?"); $stmt->execute([$prefix]);
                foreach ($stmt->fetchAll() as $row) foreach ($row as $path) if ($path) $paths[] = str_starts_with($path, 'img/team/') ? 'assets/' . $path : $path;
            }

            $stmt = $pdo->prepare('SELECT COUNT(*) FROM product_images pi JOIN products p ON pi.product_id = p.id WHERE p.sku LIKE ? AND pi.is_cover = 1');
            $stmt->execute([$prefix . '%']); $covers = (int)$stmt->fetchColumn();
        } finally {
            $pdo->finishTest();
            if ($oldMedia && is_file($oldMedia)) unlink($oldMedia);
            foreach ($paths as $path) {
                foreach (['uploads/operators', 'uploads/utilities', 'uploads/flyers', 'uploads/videos', 'assets/img/team'] as $folder) {
                    if (str_starts_with($path, $folder . '/')) { require_once dirname(__DIR__) . '/src/AdminMedia.php'; \KeySoftItalia\AdminMedia::remove($path, $folder); }
                }
                if (str_starts_with($path, 'assets/img/recond/')) {
                    require_once dirname(__DIR__) . '/src/ProductImageFile.php';
                    $file = productImageFile($path); if ($file && is_file($file)) unlink($file);
                }
            }
            $verified = true;
            foreach ($tables as $table) {
                if ((int)$pdo->query("SELECT COUNT(*) FROM `$table`")->fetchColumn() !== $counts[$table]) $verified = false;
            }
        }
        header('Content-Type: application/json');
        echo json_encode(['http' => http_response_code() ?: 200, 'body' => json_decode($output, true),
            'html' => !empty($scenario['html']) ? $output : null, 'rows' => $rows, 'mail' => $mail,
            'uploaded' => count(array_filter($paths, static fn($p) => str_starts_with($p, 'assets/img/recond/'))),
            'covers' => $covers,
            'old_media_exists' => $oldMediaExists,
            'rollback_verified' => $verified], JSON_INVALID_UTF8_SUBSTITUTE | JSON_THROW_ON_ERROR);
    });
    $endpoint = $scenario['endpoint'];
    $allowed = ['admin/ajax_actions/quote_actions.php', 'admin/ajax_actions/used_quote_actions.php',
        'admin/ajax_actions/booking_actions.php', 'admin/ajax_actions/product_actions.php', 'admin/ajax_actions/import_products.php',
        'admin/print_quote.php', 'admin/print_used_quote.php', 'admin/print_booking.php',
        'assets/process/process_quote.php', 'assets/process/process_used_quote.php', 'assets/process/process_booking.php',
        'assets/process/process_contact.php', 'assets/process/process_assistance.php', 'assets/process/process_liberty_demo.php',
        'assets/process/process_telephony_request.php', 'assets/process/process_utility_request.php'];
    foreach (['telephony','utility','flyer','video','team','weekly_hours','exceptions','holidays','hours'] as $module) $allowed[] = "admin/ajax_actions/{$module}_actions.php";
    $allowed[] = 'admin/ajax_actions/notification_actions.php';
    if (!in_array($endpoint, $allowed, true)) throw new RuntimeException('Invalid test endpoint');
    $_SERVER['SCRIPT_FILENAME'] = $root . '/' . $endpoint;
    chdir(dirname($_SERVER['SCRIPT_FILENAME']));
    require $_SERVER['SCRIPT_FILENAME'];
    exit;
}

if (PHP_SAPI !== 'cli' || ($argv[1] ?? '') !== '--local') {
    exit("Run explicitly with: php tests/backend-integration.php --local\n");
}
$socket = stream_socket_server('tcp://127.0.0.1:0', $errno, $error);
$address = stream_socket_get_name($socket, false); fclose($socket);
$token = bin2hex(random_bytes(24));
$log = tempnam(sys_get_temp_dir(), 'ksi-integration-');
$process = proc_open([PHP_BINARY, '-S', $address, __FILE__],
    [0 => ['pipe', 'r'], 1 => ['file', $log, 'a'], 2 => ['file', $log, 'a']], $pipes, dirname(__DIR__),
    array_merge(getenv(), ['KSI_TEST_TOKEN' => $token]));
if (!is_resource($process)) throw new RuntimeException('Cannot start local test server');
fclose($pipes[0]);
$failures = 0; $passed = 0;
$assert = static function (bool $condition): void { if (!$condition) throw new RuntimeException('Unexpected result'); };
$request = static function (array $scenario, array $fields = [], array $files = [], bool $get = false) use ($address, $token, $assert): array {
    $boundary = 'ksi-' . bin2hex(random_bytes(12)); $body = '';
    if (!$get) {
        $fields += ['csrf_token' => 'integration-token', 'privacy' => 'on'];
        foreach ($fields as $key => $value) {
            $body .= "--$boundary\r\nContent-Disposition: form-data; name=\"$key\"\r\n\r\n$value\r\n";
        }
        foreach ($files as $file) {
            $body .= "--$boundary\r\nContent-Disposition: form-data; name=\"{$file['field']}\"; filename=\"{$file['name']}\"\r\nContent-Type: {$file['type']}\r\n\r\n{$file['data']}\r\n";
        }
        $body .= "--$boundary--\r\n";
    }
    $headers = "X-Requested-With: XMLHttpRequest\r\nX-KSI-Test-Token: $token\r\nX-KSI-Scenario: " . base64_encode(json_encode($scenario)) . "\r\nContent-Type: multipart/form-data; boundary=$boundary\r\n";
    $context = stream_context_create(['http' => ['method' => $get ? 'GET' : 'POST', 'header' => $headers, 'content' => $body, 'ignore_errors' => true, 'follow_location' => 0, 'timeout' => 15]]);
    $out = file_get_contents('http://' . $address . '/' . ($get ? '?' . http_build_query($fields) : ''), false, $context);
    $result = json_decode($out, true, 512, JSON_THROW_ON_ERROR);
    $assert($result['rollback_verified'] === true);
    return $result;
};
$test = static function (string $name, callable $fn) use (&$failures, &$passed): void {
    try { $fn(); ++$passed; echo "PASS $name\n"; }
    catch (Throwable $e) { ++$failures; echo "FAIL $name: {$e->getMessage()}\n"; }
};
try {
    for ($i = 0; $i < 50; ++$i) {
        $probe = @stream_socket_client('tcp://' . $address, $errno, $error, 0.1);
        if ($probe) { fclose($probe); break; } usleep(100000);
    }
    foreach (['quotes' => 'quote', 'used_device_quotes' => 'used_quote', 'repair_bookings' => 'booking'] as $table => $module) {
        $test("$module detail with binary IP", static function () use ($request, $assert, $table, $module): void {
            $r = $request(['endpoint' => "admin/ajax_actions/{$module}_actions.php", 'fixture' => $table], ['action' => 'get', 'id' => '@fixture'], [], true);
            $assert($r['http'] === 200 && $r['body']['status'] === 'success');
        });
        $statuses = match($module) {'quote' => ['replied', 'accepted', 'rejected'], 'used_quote' => ['reviewed', 'contacted', 'accepted', 'rejected'], default => ['confirmed', 'cancelled', 'completed']};
        foreach ($statuses as $status) {
            $test("$module saves $status", static function () use ($request, $assert, $table, $module, $status): void {
                $r = $request(['endpoint' => "admin/ajax_actions/{$module}_actions.php", 'fixture' => $table, 'inspect' => $table],
                    ['action' => 'edit', 'id' => '@fixture', 'status' => $status, 'notes' => 'Test & prova', 'est_min' => '', 'est_max' => '', 'expected_price' => '99,90']);
                $assert($r['http'] === 200 && $r['body']['status'] === 'success' && $r['rows'][0]['status'] === $status && $r['rows'][0]['notes'] === 'Test & prova');
            });
        }
        $test("$module print escapes customer text", static function () use ($request, $assert, $table, $module): void {
            $r = $request(['endpoint' => "admin/print_{$module}.php", 'fixture' => $table, 'html' => true], ['id' => '@fixture'], [], true);
            $assert($r['http'] === 200 && str_contains($r['html'], '&lt;b&gt;Test &amp; prova&lt;/b&gt;') && !str_contains($r['html'], '<b>Test & prova</b>'));
        });
    }
    $product = ['action' => 'add', 'model_id' => '@model', 'sku' => '@sku', 'price_eur' => '99,90', 'grade' => 'A', 'storage_gb' => '', 'list_price' => ''];
    $test('product creation preserves cents and NULL storage', static function () use ($request, $assert, $product): void {
        $r = $request(['endpoint' => 'admin/ajax_actions/product_actions.php', 'inspect' => 'products'], $product);
        $assert($r['body']['status'] === 'success' && $r['rows'][0]['price_eur'] === '99.90' && $r['rows'][0]['storage_gb'] === null);
    });
    $test('real multipart image upload creates cover', static function () use ($request, $assert, $product): void {
        $png = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+a7X8AAAAASUVORK5CYII=');
        $r = $request(['endpoint' => 'admin/ajax_actions/product_actions.php'], $product, [['field' => 'product_images[]', 'name' => 'ksi-test.png', 'type' => 'image/png', 'data' => $png]]);
        $assert($r['body']['status'] === 'success' && $r['uploaded'] === 1 && $r['covers'] === 1);
    });
    foreach (['fake image' => 'not an image', 'oversized image' => str_repeat('x', 2 * 1024 * 1024 + 1)] as $name => $data) {
        $test("product rejects $name without inserting", static function () use ($request, $assert, $product, $data): void {
            $r = $request(['endpoint' => 'admin/ajax_actions/product_actions.php', 'inspect' => 'products'], $product,
                [['field' => 'product_images[]', 'name' => 'test.png', 'type' => 'image/png', 'data' => $data]]);
            $assert($r['http'] === 422 && $r['body']['status'] === 'error' && $r['rows'] === [] && $r['uploaded'] === 0);
        });
    }
    $test('product image cover update commits within rollback boundary', static function () use ($request, $assert): void {
        $r = $request(['endpoint' => 'admin/ajax_actions/product_actions.php', 'fixture' => 'products', 'image' => true], ['action' => 'update_image_details', 'product_id' => '@fixture']);
        $assert($r['body']['status'] === 'success' && $r['covers'] === 1);
    });
    $test('product edit preserves fields and cents', static function () use ($request, $assert, $product): void {
        $r = $request(['endpoint' => 'admin/ajax_actions/product_actions.php', 'fixture' => 'products', 'inspect' => 'products'],
            array_replace($product, ['action' => 'edit', 'id' => '@fixture', 'color' => 'Blu', 'storage_gb' => '128']));
        $assert($r['body']['status'] === 'success' && $r['rows'][0]['color'] === 'Blu' && (int)$r['rows'][0]['storage_gb'] === 128);
    });
    $test('product delete cascades image records', static function () use ($request, $assert): void {
        $r = $request(['endpoint' => 'admin/ajax_actions/product_actions.php', 'fixture' => 'products', 'image' => true, 'inspect' => 'products'], ['action' => 'delete', 'id' => '@fixture']);
        $assert($r['body']['status'] === 'success' && $r['rows'] === []);
    });
    $imported = ['sku' => 'KSI_IMPORT_TEST_' . bin2hex(random_bytes(4)), 'brand' => 'KSI Integration %', 'model' => 'Integration _',
        'device_type' => 'Smartphone', 'color' => 'Nero', 'storage' => 128, 'grade' => 'A', 'price' => '199.90', 'qty' => 1, 'short_desc' => 'Test', 'full_desc' => 'Test'];
    $test('CSV import persists validated product with decimal price', static function () use ($request, $assert, $imported): void {
        $r = $request(['endpoint' => 'admin/ajax_actions/import_products.php', 'inspect' => 'products', 'inspect_sku' => $imported['sku']], ['action' => 'import', 'products' => json_encode([$imported])]);
        $assert($r['body']['status'] === 'success' && $r['rows'][0]['price_eur'] === '199.90' && $r['rows'][0]['list_price'] === '239.88');
    });
    $test('CSV preview retains decimal dot price', static function () use ($request, $assert): void {
        $row = array_fill(0, 46, ''); $row[1] = 'KSI_CSV_TEST'; $row[3] = 'Smartphone Test 128GB';
        $row[6] = '1'; $row[14] = '199.90'; $row[38] = 'Test'; $row[39] = 'Integration';
        $file = fopen('php://temp', 'r+'); fputcsv($file, array_fill(0, 46, 'header'), ';'); fputcsv($file, $row, ';'); rewind($file);
        $data = stream_get_contents($file); fclose($file);
        $r = $request(['endpoint' => 'admin/ajax_actions/import_products.php'], ['action' => 'preview'],
            [['field' => 'csv_file', 'name' => 'test.csv', 'type' => 'text/csv', 'data' => $data]]);
        $assert($r['body']['status'] === 'success' && $r['body']['data'][0]['price'] === '199.90');
    });
    foreach (['broken JSON' => '{', 'negative price' => json_encode([array_replace($imported, ['price' => '-1'])]),
        'duplicate SKU' => json_encode([$imported, $imported])] as $name => $payload) {
        $test("CSV rejects $name", static function () use ($request, $assert, $payload): void {
            $r = $request(['endpoint' => 'admin/ajax_actions/import_products.php'], ['action' => 'import', 'products' => $payload]);
            $assert($r['http'] === 422 && $r['body']['status'] === 'error');
        });
    }
    $customer = ['firstName' => 'Test', 'lastName' => 'Integration', 'email' => 'test@example.invalid', 'phone' => '0000000000'];
    $test('public quote save reaches database and mail capture', static function () use ($request, $assert, $customer): void {
        $r = $request(['endpoint' => 'assets/process/process_quote.php', 'inspect' => 'quotes'], $customer +
            ['mode' => 'save', 'source' => 'email', 'device' => 'smartphone', 'brand' => 'Test', 'model' => 'Test', 'issues[]' => 'Integration']);
        $assert($r['body']['ok'] === true && count($r['rows']) === 1 && count($r['mail']) === 1);
    });
    $test('public used quote save reaches database and mail capture', static function () use ($request, $assert, $customer): void {
        $r = $request(['endpoint' => 'assets/process/process_used_quote.php', 'inspect' => 'used_device_quotes'], $customer +
            ['device' => 'smartphone', 'brand' => 'Test', 'model' => 'Test', 'device_condition' => 'buono', 'expected_price' => '99,90']);
        $assert($r['body']['ok'] === true && $r['rows'][0]['expected_price'] === '99.90' && count($r['mail']) === 1);
    });
    $test('public booking save reaches database and mail capture', static function () use ($request, $assert, $customer): void {
        $r = $request(['endpoint' => 'assets/process/process_booking.php', 'inspect' => 'repair_bookings'], $customer +
            ['device_type' => 'smartphone', 'brand_name' => 'Test', 'model_name' => 'Test', 'problem_summary' => 'Integration',
                'preferred_date' => date('Y-m-d', strtotime('+2 days')), 'preferred_time_slot' => 'mattina']);
        $assert($r['body']['ok'] === true && count($r['rows']) === 1 && count($r['mail']) === 1);
    });
    foreach (['contact' => ['name' => 'Test', 'surname' => 'Integration', 'email' => 'test@example.invalid', 'message' => 'Test integrazione'],
        'assistance' => ['name' => 'Test', 'phone' => '0000000000', 'device_type' => 'PC', 'problem_description' => 'Test integrazione']] as $form => $fields) {
        $test("$form form reaches mail capture", static function () use ($request, $assert, $form, $fields): void {
            $r = $request(['endpoint' => "assets/process/process_$form.php"], $fields);
            $assert($r['http'] === 200 && count($r['mail']) === 1);
        });
        $test("$form reports mail failure", static function () use ($request, $assert, $form, $fields): void {
            $r = $request(['endpoint' => "assets/process/process_$form.php", 'mail_failure' => true], $fields);
            $assert($r['http'] === 500 && count($r['mail']) === 1 && !str_contains(json_encode($r['body']), 'Test transport failure'));
        });
    }
    $test('Liberty request persists before download response', static function () use ($request, $assert): void {
        $r = $request(['endpoint' => 'assets/process/process_liberty_demo.php', 'inspect' => 'liberty_demo_requests'],
            ['name' => 'Test', 'company' => 'Test', 'email' => 'test@example.invalid', 'phone' => '0000000000', 'city' => 'Test']);
        $assert($r['http'] === 200 && count($r['rows']) === 1 && isset($r['body']['download_url']));
    });
    foreach (['telephony', 'utility'] as $form) {
        $test("$form saves comma decimal and correct savings", static function () use ($request, $assert, $form): void {
            $r = $request(['endpoint' => "assets/process/process_{$form}_request.php", 'fixture' => "{$form}_promotions", 'inspect' => "{$form}_requests"],
                ['current_spend' => '29,90', 'phone' => '0000000000', 'num_lines' => '1']);
            $assert($r['http'] === 200 && $r['body']['ok'] && $r['rows'][0]['current_spend'] === '29.90' && $r['rows'][0]['estimated_savings'] === '232.80');
        });
        $test("$form rejects malformed spend", static function () use ($request, $assert, $form): void {
            $r = $request(['endpoint' => "assets/process/process_{$form}_request.php", 'fixture' => "{$form}_promotions"],
                ['current_spend' => '29euros', 'phone' => '0000000000']);
            $assert($r['http'] === 422 && !$r['body']['ok']);
        });
    }
    require __DIR__ . '/backend-secondary-cases.php';
    require __DIR__ . '/backend-notification-cases.php';
} finally {
    proc_terminate($process); proc_close($process);
    if ($failures) echo file_get_contents($log);
    unlink($log);
}
echo "$passed passed, $failures failed. All requests rolled back; mail captured.\n";
exit($failures ? 1 : 0);
