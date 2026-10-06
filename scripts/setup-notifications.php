<?php
declare(strict_types=1);
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
require_once __DIR__ . '/../config/config.php';
set_exception_handler(static function(Throwable $e): never { fwrite(STDERR, 'Setup notifiche: ' . $e->getMessage() . "\nSu Windows, imposta OPENSSL_CONF prima di avviare PHP.\n");exit(1); });
if (!in_array('--apply', $argv, true)) { echo "Applica le tre nuove tabelle e genera le chiavi VAPID con --apply. Nessuna tabella esistente viene modificata.\n";exit; }
if (!in_array(DB_HOST,['localhost','127.0.0.1','::1'],true) && !in_array('--deployment',$argv,true)) throw new RuntimeException('Per il server pubblico usa --deployment.');
$sql=file_get_contents(__DIR__ . '/../database/migrations/2026_10_05_002_admin_notifications.sql');
foreach (explode(';',$sql) as $statement) if (trim($statement)!=='') $pdo->exec($statement);
require_once __DIR__ . '/../vendor/autoload.php';
$path=__DIR__ . '/../config/runtime/web-push.json';
if (!is_file($path)) {
    if (!getenv('OPENSSL_CONF') && PHP_OS_FAMILY === 'Windows') {
        $opensslConfig = dirname(PHP_BINARY) . '/extras/ssl/openssl.cnf';
        if (is_file($opensslConfig)) putenv('OPENSSL_CONF=' . $opensslConfig);
    }
    if (!is_dir(dirname($path))) mkdir(dirname($path),0700,true);
    $keys=\Minishlink\WebPush\VAPID::createVapidKeys();
    file_put_contents($path,json_encode($keys,JSON_PRETTY_PRINT),LOCK_EX);chmod($path,0600);
}
require_once __DIR__ . '/../src/AdminNotifications.php';
\KeySoftItalia\AdminNotifications::sync($pdo);
echo "Tabelle e chiavi pronte. Conservare config/runtime/web-push.json sul server.\n";
