<?php
declare(strict_types=1);

// Isolated endpoint tests: replace only the DB bootstrap with a PDO test double.
// No real database connection, email delivery or product upload is performed.
final class BackendPdo extends PDO
{
    public array $queries = [];
    public bool $transaction = false;
    public bool $rolledBack = false;
    public array $rules = ['Schermo' => ['min_price' => '49.90', 'max_price' => '49.90', 'notes' => '']];
    public function __construct() {}
    public function prepare(string $query, array $options = []): PDOStatement|false
    {
        return new BackendStatement($this, $query);
    }
    public function lastInsertId(?string $name = null): string|false { return '123'; }
    public function beginTransaction(): bool { $this->transaction = true; return true; }
    public function commit(): bool { $this->transaction = false; return true; }
    public function inTransaction(): bool { return $this->transaction; }
    public function rollBack(): bool { $this->transaction = false; $this->rolledBack = true; return true; }
}

final class BackendStatement extends PDOStatement
{
    private array $params = [];
    public function __construct(private BackendPdo $db, private string $sql) {}
    public function execute(?array $params = null): bool
    {
        $this->params = $params ?? [];
        $this->db->queries[] = [$this->sql, $this->params];
        return true;
    }
    public function fetchColumn(int $column = 0): mixed
    {
        if (str_contains($this->sql, 'FROM devices')) return $this->params[0] === 'smartphone' ? 1 : false;
        if (str_contains($this->sql, 'FROM brands WHERE id')) return $this->params === [2, 1] ? 2 : false;
        if (str_contains($this->sql, 'FROM brands')) return false;
        if (str_contains($this->sql, 'FROM models')) return false;
        if (str_contains($this->sql, 'FROM issues')) return $this->params[1] === 'Schermo' ? 10 : false;
        return false;
    }
    public function fetch(int $mode = PDO::FETCH_DEFAULT, int $cursorOrientation = PDO::FETCH_ORI_NEXT, int $cursorOffset = 0): mixed
    {
        if (str_contains($this->sql, 'FROM users')) return false;
        if (str_contains($this->sql, 'FROM price_rules')) return $this->db->rules['Schermo'] ?? false;
        return false;
    }
}

if (($argv[1] ?? '') === '--endpoint') {
    $request = json_decode(base64_decode($argv[2]), true, 512, JSON_THROW_ON_ERROR);
    define('BASE_PATH', dirname(__DIR__) . '/');
    define('KS_TZ', 'Europe/Rome');
    ini_set('display_errors', '0');
    session_start();
    $_SESSION = ['user_id' => 1, 'csrf_token' => 'valid-token'];
    $_SERVER['REQUEST_METHOD'] = $request['method'] ?? 'POST';
    $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
    $_POST = $request['post'] ?? [];
    $_GET = $request['get'] ?? [];
    $_REQUEST = array_merge($_GET, $_POST);
    $pdo = new BackendPdo();
    $throttleDirectory = sys_get_temp_dir() . '/ksi-backend-' . bin2hex(random_bytes(6));
    ob_start();
    register_shutdown_function(static function () use ($pdo, $throttleDirectory): void {
        $output = ob_get_clean();
        echo json_encode(['http' => http_response_code() ?: 200, 'body' => json_decode($output, true),
            'session' => $_SESSION, 'queries' => $pdo->queries, 'rolled_back' => $pdo->rolledBack, 'output' => $output]);
        foreach (glob($throttleDirectory . '/*') ?: [] as $file) unlink($file);
        if (is_dir($throttleDirectory)) rmdir($throttleDirectory);
    });
    $paths = ['quote' => 'assets/process/process_quote.php', 'admin' => 'admin/ajax_actions/init.php',
        'login' => 'admin/ajax_login.php', 'contact' => 'assets/process/process_contact.php',
        'product' => 'admin/ajax_actions/product_actions.php', 'booking_admin' => 'admin/ajax_actions/booking_actions.php',
        'used_admin' => 'admin/ajax_actions/used_quote_actions.php', 'quote_admin' => 'admin/ajax_actions/quote_actions.php',
        'user_admin' => 'admin/ajax_actions/user_actions.php', 'price_admin' => 'admin/ajax_actions/price_rule_actions.php',
        'booking_public' => 'assets/process/process_booking.php', 'utility_public' => 'assets/process/process_utility_request.php',
        'telephony_public' => 'assets/process/process_telephony_request.php', 'assistance_public' => 'assets/process/process_assistance.php',
        'used_public' => 'assets/process/process_used_quote.php', 'liberty_public' => 'assets/process/process_liberty_demo.php',
        'products_ajax' => 'assets/ajax/get_products.php', 'filters_ajax' => 'assets/ajax/get_product_filters.php',
        'detail_ajax' => 'assets/ajax/get_product_detail.php', 'brands_ajax' => 'assets/ajax/get_brands.php',
        'models_ajax' => 'assets/ajax/get_models.php', 'issues_ajax' => 'assets/ajax/get_issues.php',
        'flyers_ajax' => 'assets/ajax/get_flyers.php'];
    $source = file_get_contents(BASE_PATH . $paths[$request['endpoint']]);
    $_SERVER['SCRIPT_FILENAME'] = BASE_PATH . $paths[$request['endpoint']];
    $source = str_replace("__DIR__ . '/../../src/", "BASE_PATH . 'src/", $source);
    $source = str_replace("BASE_PATH . 'config/runtime/login'", '$throttleDirectory', $source);
    // Keep all production request logic; inject the database dependency only.
    $source = preg_replace('/^require_once .*config\/config\.php.*$/m', '', $source);
    $config = file_get_contents(BASE_PATH . 'config/config.php');
    eval(substr($config, 5, strpos($config, "require_once BASE_PATH . 'assets/php/functions.php';") - 5));
    if ($request['endpoint'] === 'contact') {
        function send_assistance_email(array $data, array $opts = []): array {
            if ($data['phone'] === '' && ($opts['require_phone'] ?? true)) {
                return ['ok' => false, 'error' => 'Campi obbligatori mancanti'];
            }
            return ['ok' => true, 'error' => null];
        }
    }
    require_once BASE_PATH . 'assets/php/functions.php';
    if (str_starts_with($paths[$request['endpoint']], 'admin/ajax_actions/') && $request['endpoint'] !== 'admin') {
        $init = file_get_contents(BASE_PATH . 'admin/ajax_actions/init.php');
        $init = str_replace("__DIR__ . '/../../src/", "BASE_PATH . 'src/", $init);
        $init = preg_replace('/^require_once .*config\/config\.php.*$/m', '', $init);
        eval(substr($init, 5));
        $source = str_replace("require_once __DIR__ . '/init.php';", '', $source);
        $source = str_replace("require_once __DIR__ . '/../../src/ProductImageFile.php';",
            "require_once BASE_PATH . 'src/ProductImageFile.php';", $source);
    }
    eval(substr($source, 5));
    exit;
}

require_once __DIR__ . '/../src/QuoteEstimate.php';
require_once __DIR__ . '/../src/ProductImageFile.php';
$failures = 0;
$test = static function (string $name, callable $fn) use (&$failures): void {
    try { $fn(); echo "PASS $name\n"; }
    catch (Throwable $e) { ++$failures; echo "FAIL $name: {$e->getMessage()}\n"; }
};
$assert = static function (bool $condition): void {
    if (!$condition) throw new RuntimeException('Unexpected result');
};
$endpoint = static function (array $request): array {
    $pipes = [];
    $process = proc_open([PHP_BINARY, __FILE__, '--endpoint', base64_encode(json_encode($request))],
        [0 => ['pipe', 'r'], 1 => ['pipe', 'w'], 2 => ['pipe', 'w']], $pipes);
    if (!is_resource($process)) throw new RuntimeException('Cannot start PHP');
    fclose($pipes[0]);
    $out = stream_get_contents($pipes[1]); fclose($pipes[1]);
    $err = stream_get_contents($pipes[2]); fclose($pipes[2]);
    $code = proc_close($process);
    if ($code !== 0) throw new RuntimeException($err ?: $out);
    return json_decode($out, true, 512, JSON_THROW_ON_ERROR);
};
$valid = ['mode' => 'save', 'csrf_token' => 'valid-token', 'privacy' => 'on', 'device' => 'smartphone',
    'brand' => 'Apple', 'model' => 'Test', 'issues' => ['Schermo'], 'firstName' => 'Mario',
    'lastName' => 'Rossi', 'email' => 'test@example.com', 'phone' => '3331234567'];

$test('contact bootstrap and validation return JSON', static function () use ($endpoint, $assert): void {
    $r = $endpoint(['endpoint' => 'contact', 'post' => ['csrf_token' => 'valid-token', 'privacy' => 'on']]);
    $assert($r['http'] === 422 && isset($r['body']['errors']['email']));
});
$test('contact form accepts an optional empty phone number', static function () use ($endpoint, $assert): void {
    $r = $endpoint(['endpoint' => 'contact', 'post' => ['csrf_token' => 'valid-token', 'name' => 'Mario',
        'surname' => 'Rossi', 'email' => 'test@example.com', 'message' => 'Richiedo informazioni.', 'privacy' => 'on']]);
    $assert($r['http'] === 200 && $r['body']['success']);
});
foreach (['delete', 'delete_request', 'execute', 'generate', 'save_weekly'] as $action) {
    $test("admin rejects GET $action", static function () use ($endpoint, $assert, $action): void {
        $r = $endpoint(['endpoint' => 'admin', 'method' => 'GET', 'get' => ['action' => $action, 'id' => '1']]);
        $assert($r['http'] === 405 && $r['queries'] === []);
    });
}
$test('admin accepts read GET', static function () use ($endpoint, $assert): void {
    $r = $endpoint(['endpoint' => 'admin', 'method' => 'GET', 'get' => ['action' => 'get']]);
    $assert($r['http'] === 200);
});
$test('admin malformed CSRF returns 403', static function () use ($endpoint, $assert): void {
    $r = $endpoint(['endpoint' => 'admin', 'post' => ['action' => 'delete', 'csrf_token' => ['invalid']]]);
    $assert($r['http'] === 403 && $r['queries'] === []);
});
foreach ([['csrf_token' => 'bad'], ['csrf_token' => ['bad']], ['privacy' => ''], ['mode' => 'typo'],
    ['email' => 'invalid'], ['issues' => [['bad']]], ['device' => ['invalid']],
    ['wizard_payload' => '{"device":[]}']] as $change) {
    $test('quote rejects ' . json_encode($change), static function () use ($valid, $change, $endpoint, $assert): void {
        $r = $endpoint(['endpoint' => 'quote', 'post' => array_replace($valid, $change)]);
        $assert(in_array($r['http'], [403, 422], true));
        $assert(!array_filter($r['queries'], static fn($q) => str_contains($q[0], 'INSERT')));
    });
}
$test('duplicate issues charged once and cents retained', static function () use ($valid, $endpoint, $assert): void {
    $r = $endpoint(['endpoint' => 'quote', 'post' => array_replace($valid, ['issues' => ['Schermo', 'Schermo']])]);
    $assert($r['body']['ok'] && $r['body']['estimate']['min'] === 49.9);
    $inserts = array_values(array_filter($r['queries'], static fn($q) => str_contains($q[0], 'INSERT')));
    $assert(count($inserts) === 1 && $inserts[0][1][':problems_json'] === '["Schermo"]');
});
$test('unknown issue does not become a free repair', static function () use ($assert): void {
    $r = compute_estimate(new BackendPdo(), 'smartphone', null, '', '', ['Sconosciuto']);
    $assert($r['type'] === 'unknown' && $r['min'] === null && $r['max'] === null);
});
$test('mixed known and unknown issues have no fixed upper bound', static function () use ($assert): void {
    $r = compute_estimate(new BackendPdo(), 'smartphone', null, '', '', ['Schermo', 'Sconosciuto']);
    $assert($r['type'] === 'from' && $r['min'] === 49.9 && $r['max'] === null);
});
$test('range estimate retains both endpoints', static function () use ($assert): void {
    $pdo = new BackendPdo();
    $pdo->rules['Schermo'] = ['min_price' => '49.90', 'max_price' => '89.50', 'notes' => ''];
    $r = compute_estimate($pdo, 'smartphone', null, '', '', ['Schermo']);
    $assert($r['type'] === 'range' && $r['min'] === 49.9 && $r['max'] === 89.5);
});
$test('brand ID must belong to selected device', static function () use ($assert): void {
    $assert(get_brand_id(new BackendPdo(), 1, 999, '') === null);
    $assert(get_brand_id(new BackendPdo(), 1, 2, '') === 2);
});
$test('unknown login username counts toward lockout', static function () use ($endpoint, $assert): void {
    $r = $endpoint(['endpoint' => 'login', 'post' => ['username' => 'missing', 'password' => 'test']]);
    $assert($r['body']['success'] === false && $r['session']['login_attempts'] === 1);
});
$test('product file resolver rejects remote images and traversal', static function () use ($assert): void {
    $assert(productImageFile('https://example.com/image.jpg') === null);
    $assert(productImageFile('assets/img/recond/../../../config/config.php') === null);
    $assert(productImageFile('config/config.php') === null);
    $files = glob(__DIR__ . '/../assets/img/recond/*');
    foreach ($files as $file) {
        if (is_file($file)) {
            $assert(productImageFile('assets/img/recond/' . basename($file)) === realpath($file));
            break;
        }
    }
});
$test('missing product returns an error instead of an empty product', static function () use ($endpoint, $assert): void {
    $r = $endpoint(['endpoint' => 'product', 'method' => 'GET', 'get' => ['action' => 'get', 'id' => '999']]);
    $assert($r['http'] === 400 && $r['body']['status'] === 'error');
});
$test('blank optional product storage is stored as NULL', static function () use ($endpoint, $assert): void {
    $r = $endpoint(['endpoint' => 'product', 'post' => ['action' => 'add', 'csrf_token' => 'valid-token',
        'model_id' => '1', 'sku' => 'TEST', 'color' => '', 'storage_gb' => '', 'grade' => 'A',
        'list_price' => '', 'price_eur' => '99', 'short_desc' => '', 'full_desc' => '']]);
    $assert($r['body']['status'] === 'success' && $r['queries'][0][1][3] === null);
});
$test('foreign cover image does not erase product cover and rolls back', static function () use ($endpoint, $assert): void {
    $r = $endpoint(['endpoint' => 'product', 'post' => ['action' => 'update_image_details',
        'csrf_token' => 'valid-token', 'product_id' => '1', 'cover_image_id' => '999']]);
    $assert($r['body']['status'] === 'error' && $r['rolled_back']);
    $assert(!array_filter($r['queries'], static fn($q) => str_contains($q[0], 'SET is_cover = 0')));
});
foreach (['booking_admin', 'used_admin', 'quote_admin'] as $handler) {
    $test("$handler rejects unknown status before SQL", static function () use ($endpoint, $assert, $handler): void {
        $r = $endpoint(['endpoint' => $handler, 'post' => ['action' => 'edit', 'id' => '1', 'status' => 'typo', 'csrf_token' => 'valid-token']]);
        $assert($r['http'] === 422 && $r['queries'] === []);
    });
}
foreach (['-1', '1e6', '9999999999999999999999999'] as $id) {
    $test("admin rejects invalid ID $id", static function () use ($endpoint, $assert, $id): void {
        $r = $endpoint(['endpoint' => 'product', 'method' => 'GET', 'get' => ['action' => 'get', 'id' => $id]]);
        $assert($r['http'] === 422 && $r['queries'] === []);
    });
}
$test('empty quote bounds become SQL NULL', static function () use ($endpoint, $assert): void {
    $r = $endpoint(['endpoint' => 'quote_admin', 'post' => ['action' => 'edit', 'id' => '1', 'status' => 'pending',
        'csrf_token' => 'valid-token', 'est_min' => '', 'est_max' => '']]);
    $assert($r['body']['status'] === 'success' && $r['queries'][0][1][2] === null && $r['queries'][0][1][3] === null);
});
$test('quote rejects reversed price bounds', static function () use ($endpoint, $assert): void {
    $r = $endpoint(['endpoint' => 'quote_admin', 'post' => ['action' => 'edit', 'id' => '1', 'status' => 'pending',
        'csrf_token' => 'valid-token', 'est_min' => '100', 'est_max' => '20']]);
    $assert($r['http'] === 422 && $r['queries'] === []);
});
$test('product rejects negative price', static function () use ($endpoint, $assert): void {
    $r = $endpoint(['endpoint' => 'product', 'post' => ['action' => 'add', 'csrf_token' => 'valid-token',
        'model_id' => '1', 'sku' => 'TEST', 'price_eur' => '-10', 'grade' => 'A']]);
    $assert($r['http'] === 422 && $r['queries'] === []);
});
$test('user rejects malformed email before SQL', static function () use ($endpoint, $assert): void {
    $r = $endpoint(['endpoint' => 'user_admin', 'post' => ['action' => 'add', 'csrf_token' => 'valid-token',
        'username' => 'test', 'email' => 'invalid', 'password' => 'test']]);
    $assert($r['http'] === 422 && $r['queries'] === []);
});
foreach (['booking_public', 'utility_public', 'telephony_public', 'assistance_public', 'used_public', 'liberty_public'] as $handler) {
    $test("$handler rejects GET and missing consent", static function () use ($endpoint, $assert, $handler): void {
        $get = $endpoint(['endpoint' => $handler, 'method' => 'GET']);
        $post = $endpoint(['endpoint' => $handler, 'post' => ['csrf_token' => 'valid-token', 'privacy' => 'false']]);
        $assert($get['http'] === 405 && $post['http'] === 422 && $post['queries'] === []);
    });
}
$test('quote preview still works without customer consent', static function () use ($endpoint, $assert): void {
    $r = $endpoint(['endpoint' => 'quote', 'post' => ['mode' => 'preview', 'device' => 'smartphone', 'issues' => ['Schermo']]]);
    $assert($r['http'] === 200 && $r['body']['estimate']['min'] === 49.9);
});
$test('booking rejects impossible calendar date', static function () use ($endpoint, $assert): void {
    $r = $endpoint(['endpoint' => 'booking_public', 'post' => ['csrf_token' => 'valid-token', 'privacy' => 'on',
        'device_type' => 'smartphone', 'brand_name' => 'Apple', 'model_name' => 'Test', 'preferred_date' => '2099-02-30',
        'preferred_time_slot' => 'mattina', 'firstName' => 'Mario', 'lastName' => 'Rossi', 'email' => 'test@example.com', 'phone' => '3331234567']]);
    $assert($r['http'] === 422 && isset($r['body']['errors']['preferred_date']) && $r['queries'] === []);
});
foreach (['products_ajax', 'filters_ajax', 'detail_ajax', 'brands_ajax', 'models_ajax', 'issues_ajax', 'flyers_ajax'] as $handler) {
    $test("$handler rejects POST and malformed query values", static function () use ($endpoint, $assert, $handler): void {
        $post = $endpoint(['endpoint' => $handler]);
        $get = $endpoint(['endpoint' => $handler, 'method' => 'GET', 'get' => ['brand_id' => ['invalid']]]);
        $assert($post['http'] === 405 && $get['http'] === 422 && $get['queries'] === []);
    });
}
exit($failures > 0 ? 1 : 0);
