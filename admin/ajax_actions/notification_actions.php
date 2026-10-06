<?php
require_once __DIR__ . '/init.php';
require_once __DIR__ . '/../../src/AdminNotifications.php';
require_once __DIR__ . '/../../src/AdminPush.php';
use KeySoftItalia\AdminNotifications as Inbox;
use KeySoftItalia\AdminPush as Push;
$user=(int)$_SESSION['user_id'];
$action=$_REQUEST['action'] ?? '';
try {
    if ($action==='setup_push') { Push::initialize();jsonSuccess(['publicKey'=>Push::configuration()['publicKey'],'message'=>'Web Push configurato. Ora puoi attivare le notifiche su questo browser.']); }
    if ($action==='get') {
        $stmt=$pdo->prepare('SELECT * FROM admin_notifications WHERE id=?');$stmt->execute([(int)($_GET['id'] ?? 0)]);
        $row=$stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) jsonError('Notifica non disponibile.');
        jsonSuccess(['notification'=>$row]);
    }
    if ($action==='list') {
        Inbox::sync($pdo);
        $result=Inbox::listing($pdo,$user,max(1,(int)($_GET['page'] ?? 1)),($_GET['unread'] ?? '')==='1');
        $result['publicKey']=Push::configuration()['publicKey'];
        jsonSuccess($result);
    }
    if ($action==='read') { Inbox::mark($pdo,$user,(int)($_POST['id'] ?? 0));jsonSuccess(); }
    if ($action==='read_all') { Inbox::markAll($pdo,$user,(int)($_POST['through'] ?? -1));jsonSuccess(); }
    if ($action==='subscribe') { Push::subscribe($pdo,$user,(string)($_POST['subscription'] ?? ''));jsonSuccess(); }
    if ($action==='unsubscribe') { Push::remove($pdo,$user,(string)($_POST['subscription'] ?? ''));jsonSuccess(); }
    if ($action==='test_push') { Push::test($pdo,$user,(string)($_POST['subscription'] ?? ''));jsonSuccess(['message'=>'Prova accettata dal servizio push. Se non compare, controlla le notifiche del browser e del sistema operativo.']); }
    jsonError('Azione non valida.');
} catch (InvalidArgumentException $e) { jsonError($e->getMessage(),$e); }
