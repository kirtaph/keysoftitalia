<?php
declare(strict_types=1);
if (PHP_SAPI !== 'cli') { http_response_code(404);exit; }
require_once __DIR__ . '/../config/config.php';
set_exception_handler(static function(Throwable $e): never { fwrite(STDERR, 'Invio notifiche: ' . $e->getMessage() . "\n");exit(1); });
require_once __DIR__ . '/../src/AdminNotifications.php';
require_once __DIR__ . '/../src/AdminPush.php';
// MySQL advisory lock prevents concurrent cron runs duplicating deliveries.
if (!(int)$pdo->query("SELECT GET_LOCK('ksi_admin_push',0)")->fetchColumn()) exit;
try {
    \KeySoftItalia\AdminNotifications::sync($pdo);
    $result=\KeySoftItalia\AdminPush::deliver($pdo);
    echo json_encode($result) . "\n";
    if ($result['failed']) exit(1);
} finally { $pdo->query("SELECT RELEASE_LOCK('ksi_admin_push')"); }
