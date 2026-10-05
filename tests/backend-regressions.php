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
    ob_start();
    register_shutdown_function(static function () use ($pdo): void {
        $output = ob_get_clean();
        echo json_encode(['http' => http_response_code() ?: 200, 'body' => json_decode($output, true),
            'session' => $_SESSION, 'queries' => $pdo->queries, 'rolled_back' => $pdo->rolledBack, 'output' => $output]);
    });
    $paths = ['quote' => 'assets/process/process_quote.php', 'admin' => 'admin/ajax_actions/init.php',
        'login' => 'admin/ajax_login.php', 'contact' => 'assets/process/process_contact.php',
        'product' => 'admin/ajax_actions/product_actions.php'];
    $source = file_get_contents(BASE_PATH . $paths[$request['endpoint']]);
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
    if ($request['endpoint'] === 'product') {
        $init = file_get_contents(BASE_PATH . 'admin/ajax_actions/init.php');
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
    $r = $endpoint(['endpoint' => 'contact', 'post' => ['csrf_token' => 'valid-token']]);
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
exit($failures > 0 ? 1 : 0);
