<?php
declare(strict_types=1);
if (PHP_SAPI!=='cli' || !in_array('--local',$argv,true)) exit("Use --local\n");
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../src/AdminNotifications.php';
require_once __DIR__ . '/../src/AdminPush.php';
require_once __DIR__ . '/../vendor/autoload.php';
use KeySoftItalia\AdminNotifications as Inbox;
use KeySoftItalia\AdminPush as Push;
if (!in_array(DB_HOST,['localhost','127.0.0.1','::1'],true)) throw new RuntimeException('Loopback database required.');
$pass=0;$fail=0;
$check=static function(string $name,callable $run) use (&$pass,&$fail) { try { if (!$run()) throw new RuntimeException('Assertion failed');++$pass;echo "PASS $name\n"; } catch(Throwable $e) { ++$fail;echo "FAIL $name: {$e->getMessage()}\n"; } };
$before=[];
foreach (['admin_notifications','admin_notification_reads','admin_push_subscriptions'] as $table) {
    $engine=$pdo->prepare('SELECT ENGINE FROM information_schema.TABLES WHERE TABLE_SCHEMA=? AND TABLE_NAME=?');$engine->execute([DB_NAME,$table]);
    if ($engine->fetchColumn()!=='InnoDB') throw new RuntimeException('InnoDB required.');
    $before[$table]=(int)$pdo->query("SELECT COUNT(*) FROM $table")->fetchColumn();
}
$pdo->beginTransaction();
try {
    $user=(int)$pdo->query('SELECT id FROM users ORDER BY id LIMIT 1')->fetchColumn();$other=90000002;$sourceId=900000000001;
    if (!$user) throw new RuntimeException('An existing local admin is required.');
    Inbox::publish($pdo,'quote',$sourceId);$id=Inbox::latestId($pdo);
    $check('source record is idempotent',function()use($pdo,$sourceId,$id){Inbox::publish($pdo,'quote',$sourceId);return Inbox::latestId($pdo)===$id;});
    $initial=Inbox::listing($pdo,$user)['unread'];
    $check('read receipt belongs to current user',function()use($pdo,$user,$other,$id,$initial){Inbox::mark($pdo,$user,$id);return Inbox::listing($pdo,$user)['unread']===$initial-1 && Inbox::listing($pdo,$other)['unread']===$initial;});
    $check('mark all excludes later arrivals',function()use($pdo,$user,$id){Inbox::publish($pdo,'booking',900000000002);Inbox::markAll($pdo,$user,$id);return Inbox::listing($pdo,$user,1,true)['total']===1;});
    $check('unread listing has stable pagination',function()use($pdo,$user){for($i=0;$i<30;++$i)Inbox::publish($pdo,'used',900000000010+$i);$one=Inbox::listing($pdo,$user,1,true);$two=Inbox::listing($pdo,$user,2,true);return count($one['rows'])===25 && count($two['rows'])===6 && !array_intersect(array_column($one['rows'],'id'),array_column($two['rows'],'id'));});
    $check('contact payload is saved independently of email',function()use($pdo){Inbox::capture($pdo,'contact',['name'=>'KSI TEST','problem_description'=>'<script>test</script>']);$stmt=$pdo->query('SELECT detail FROM admin_notifications ORDER BY id DESC LIMIT 1');return json_decode($stmt->fetchColumn(),true)['problem_description']==='<script>test</script>';});
    $check('quote destination has exact record and opens details',fn()=>Inbox::url(['source'=>'quote','source_id'=>55,'id'=>1])==='quotes.php?q=55&open=55');
    $check('utility destination selects request tab',fn()=>Inbox::url(['source'=>'utility','source_id'=>1,'id'=>1])==='forniture.php#requests-panel');
    $keys=\Minishlink\WebPush\VAPID::createVapidKeys();
    $subscription=['endpoint'=>'https://fcm.googleapis.com/fcm/send/ksi-local-test','keys'=>['p256dh'=>$keys['publicKey'],'auth'=>rtrim(strtr(base64_encode(random_bytes(16)),'+/','-_'),'=')]];
    $json=json_encode($subscription);
    $check('valid browser subscription accepted',fn()=>Push::subscription($json)['endpoint']===$subscription['endpoint']);
    foreach (['http://fcm.googleapis.com/test','https://127.0.0.1/test','https://fcm.googleapis.com.evil.test/test','https://user@fcm.googleapis.com/test','https://fcm.googleapis.com:8443/test'] as $endpoint) {
        $check('reject unsafe endpoint '.$endpoint,function()use($subscription,$endpoint){$subscription['endpoint']=$endpoint;try{Push::subscription(json_encode($subscription));return false;}catch(InvalidArgumentException){return true;}});
    }
    $check('new subscription starts after existing events',function()use($pdo,$user,$json){Push::subscribe($pdo,$user,$json);return (int)$pdo->query('SELECT last_notification_id FROM admin_push_subscriptions WHERE user_id=' . $user)->fetchColumn()===Inbox::latestId($pdo);});
    $check('another account cannot claim the browser',function()use($pdo,$other,$json){try{Push::subscribe($pdo,$other,$json);return false;}catch(InvalidArgumentException){return true;}});
    // Use the real encryption/VAPID library with a mock HTTP transport. No external message sent.
    Inbox::publish($pdo,'booking',900000000099);
    $sender=static function(int $code) { $mock=new \GuzzleHttp\Handler\MockHandler([new \GuzzleHttp\Psr7\Response($code)]);return new \Minishlink\WebPush\WebPush(['VAPID'=>Push::configuration()],[],5,['handler'=>\GuzzleHttp\HandlerStack::create($mock),'allow_redirects'=>false]); };
    $check('temporary delivery failure preserves cursor for retry',function()use($pdo,$sender,$user){$before=(int)$pdo->query('SELECT last_notification_id FROM admin_push_subscriptions WHERE user_id=' . $user)->fetchColumn();$result=Push::deliver($pdo,$sender(503));return $result['failed']===1 && (int)$pdo->query('SELECT last_notification_id FROM admin_push_subscriptions WHERE user_id=' . $user)->fetchColumn()===$before;});
    $check('successful delivery advances durable cursor',function()use($pdo,$sender,$user){$result=Push::deliver($pdo,$sender(201));return $result['ok']===1 && (int)$pdo->query('SELECT last_notification_id FROM admin_push_subscriptions WHERE user_id=' . $user)->fetchColumn()===Inbox::latestId($pdo);});
    $check('expired subscriptions are removed',function()use($pdo,$sender,$user){Inbox::publish($pdo,'quote',900000000100);$result=Push::deliver($pdo,$sender(410));return $result['expired']===1 && !(int)$pdo->query('SELECT COUNT(*) FROM admin_push_subscriptions WHERE user_id=' . $user)->fetchColumn();});
    $check('repeated sync does not duplicate source events',function()use($pdo){Inbox::sync($pdo);$before=Inbox::latestId($pdo);Inbox::sync($pdo);return Inbox::latestId($pdo)===$before;});
} finally {
    $pdo->rollBack();
    foreach($before as $table=>$count) if((int)$pdo->query("SELECT COUNT(*) FROM $table")->fetchColumn()!==$count) throw new RuntimeException('Fixture cleanup mismatch.');
}
echo "$pass passed, $fail failed; fixtures rolled back; push transport mocked.\n";exit($fail?1:0);
