<?php
declare(strict_types=1);
namespace KeySoftItalia;

final class AdminPush
{
    public static function initialize(): void
    {
        $config = self::configuration();
        if ($config['publicKey'] && $config['privateKey']) return;
        if ($config['publicKey'] || $config['privateKey']) throw new \InvalidArgumentException('Configurazione incompleta: imposta entrambe le chiavi WEB_PUSH_PUBLIC_KEY e WEB_PUSH_PRIVATE_KEY.');
        $autoload = __DIR__ . '/../vendor/autoload.php';
        if (!is_file($autoload)) throw new \InvalidArgumentException('Dipendenze mancanti: installa i pacchetti Composer sul server prima di configurare Web Push.');
        require_once $autoload;
        if (!class_exists(\Minishlink\WebPush\VAPID::class)) throw new \InvalidArgumentException('Libreria Web Push mancante: aggiorna le dipendenze Composer sul server.');
        $path = __DIR__ . '/../config/runtime/web-push.json';
        if (!is_dir(dirname($path)) && !mkdir(dirname($path),0700,true) && !is_dir(dirname($path))) {
            throw new \InvalidArgumentException('Il server non può creare config/runtime. Verifica i permessi della cartella.');
        }
        if (is_file($path)) throw new \InvalidArgumentException('Il file Web Push esiste ma non contiene chiavi valide. Controlla la configurazione senza sostituire le chiavi già utilizzate.');
        // Generate before opening; exclusive creation prevents replacing another request's keys.
        $keys = \Minishlink\WebPush\VAPID::createVapidKeys();
        $json = json_encode($keys,JSON_THROW_ON_ERROR);
        $file = @fopen($path,'x');
        if (!$file) {
            $saved = self::configuration();
            if ($saved['publicKey'] && $saved['privateKey']) return;
            throw new \InvalidArgumentException('Impossibile salvare le chiavi. Verifica che config/runtime sia scrivibile dal server.');
        }
        try {
            chmod($path,0600);
            if (fwrite($file,$json)!==strlen($json) || !fflush($file)) {
                throw new \RuntimeException('Salvataggio delle chiavi Web Push non riuscito.');
            }
        } catch (\Throwable $e) {
            fclose($file);unlink($path);throw $e;
        }
        fclose($file);
    }
    public static function configuration(): array
    {
        $path = __DIR__ . '/../config/runtime/web-push.json';
        $saved = is_file($path) ? json_decode((string)file_get_contents($path), true) : [];
        return ['publicKey'=>getenv('WEB_PUSH_PUBLIC_KEY') ?: ($saved['publicKey'] ?? ''),
            'privateKey'=>getenv('WEB_PUSH_PRIVATE_KEY') ?: ($saved['privateKey'] ?? ''),
            'subject'=>getenv('WEB_PUSH_SUBJECT') ?: 'mailto:info@keysoftitalia.it'];
    }
    public static function subscription(string $json): array
    {
        if (strlen($json)>6000) throw new \InvalidArgumentException('Sottoscrizione troppo lunga.');
        $data = json_decode($json,true);
        if (!is_array($data) || !is_array($data['keys'] ?? null)) throw new \InvalidArgumentException('Sottoscrizione non valida.');
        $endpoint = $data['endpoint'] ?? null;
        if (!is_string($endpoint) || !filter_var($endpoint,FILTER_VALIDATE_URL)) throw new \InvalidArgumentException('Endpoint non valido.');
        $url = parse_url($endpoint);
        $host = strtolower($url['host'] ?? '');
        $allowed = $host === 'fcm.googleapis.com' || $host === 'updates.push.services.mozilla.com'
            || $host === 'web.push.apple.com' || str_ends_with($host,'.notify.windows.com')
            || str_ends_with($host,'.push.apple.com');
        if (!$allowed || ($url['scheme'] ?? '') !== 'https' || isset($url['user']) || isset($url['pass'])
            || isset($url['fragment']) || (isset($url['port']) && $url['port'] !== 443)) throw new \InvalidArgumentException('Servizio push non supportato.');
        foreach (['p256dh'=>65,'auth'=>16] as $key=>$bytes) {
            $value = $data['keys'][$key] ?? null;
            if (!is_string($value) || !preg_match('/^[A-Za-z0-9_-]+={0,2}$/D',$value)
                || strlen((string)base64_decode(strtr($value,'-_','+/'),true))!==$bytes) throw new \InvalidArgumentException('Chiave push non valida.');
        }
        return ['endpoint'=>$endpoint,'keys'=>['p256dh'=>$data['keys']['p256dh'],'auth'=>$data['keys']['auth']]];
    }
    public static function subscribe(\PDO $pdo,int $user,string $json): void
    {
        $data=self::subscription($json); $hash=hash('sha256',$data['endpoint']);
        $stmt=$pdo->prepare('SELECT user_id FROM admin_push_subscriptions WHERE endpoint_hash=?');$stmt->execute([$hash]);
        $owner=$stmt->fetchColumn();
        if ($owner!==false && (int)$owner!==$user) throw new \InvalidArgumentException('Questo browser è già collegato a un altro amministratore. Disattiva le notifiche da quell’account.');
        $pdo->prepare('INSERT INTO admin_push_subscriptions (user_id,endpoint_hash,subscription,last_notification_id)
            VALUES (?,?,?,?) ON DUPLICATE KEY UPDATE subscription=VALUES(subscription),failures=0')->execute([$user,$hash,json_encode($data),AdminNotifications::latestId($pdo)]);
    }
    public static function remove(\PDO $pdo,int $user,string $json): void
    {
        $data=self::subscription($json);
        $pdo->prepare('DELETE FROM admin_push_subscriptions WHERE user_id=? AND endpoint_hash=?')->execute([$user,hash('sha256',$data['endpoint'])]);
    }
    public static function test(\PDO $pdo, int $user, string $json, ?\Minishlink\WebPush\WebPush $sender = null): void
    {
        $data = self::subscription($json);
        $stmt = $pdo->prepare('SELECT id FROM admin_push_subscriptions WHERE user_id=? AND endpoint_hash=?');
        $stmt->execute([$user,hash('sha256',$data['endpoint'])]);
        if (!$stmt->fetchColumn()) throw new \InvalidArgumentException('Attiva prima le notifiche su questo browser con questo account.');
        $config = self::configuration();
        if (!$config['publicKey'] || !$config['privateKey']) throw new \InvalidArgumentException('Configurazione Web Push mancante.');
        require_once __DIR__ . '/../vendor/autoload.php';
        $push = $sender ?? new \Minishlink\WebPush\WebPush(['VAPID'=>$config],['TTL'=>300,'urgency'=>'high'],15,['allow_redirects'=>false]);
        try {
            $report = $push->sendOneNotification(\Minishlink\WebPush\Subscription::create($data),json_encode(['title'=>'Notifica di prova','body'=>'Il collegamento Web Push funziona su questo browser.']));
        } catch (\Throwable $e) {
            throw new \InvalidArgumentException('Il server non riesce a contattare il servizio push. Verifica la connessione HTTPS in uscita e le estensioni PHP richieste.',0,$e);
        }
        if ($report->isSubscriptionExpired()) throw new \InvalidArgumentException('La registrazione del browser è scaduta. Disattiva e riattiva le notifiche.');
        if (!$report->isSuccess()) {
            $code = $report->getResponse()?->getStatusCode();
            throw new \InvalidArgumentException('Il servizio push ha rifiutato la prova' . ($code ? ' (HTTP ' . $code . ')' : '') . '. Verifica le chiavi Web Push o riprova.');
        }
    }
    public static function payload(\PDO $pdo, int $after, int $through): array
    {
        $stmt = $pdo->prepare('SELECT source,COUNT(*) AS count FROM admin_notifications WHERE id>? AND id<=? GROUP BY source ORDER BY source');
        $stmt->execute([$after,$through]);
        $groups = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $total = array_sum(array_column($groups,'count'));
        if ($total === 1) {
            $stmt = $pdo->prepare('SELECT id,source,source_id FROM admin_notifications WHERE id>? AND id<=? LIMIT 1');
            $stmt->execute([$after,$through]);$row = $stmt->fetch(\PDO::FETCH_ASSOC);
            return ['title'=>AdminNotifications::SOURCES[$row['source']][1] ?? 'Nuova richiesta',
                'body'=>'È arrivata una nuova richiesta. Apri per vedere i dettagli.',
                'url'=>AdminNotifications::url($row),'tag'=>'ksi-request-' . $row['id']];
        }
        $summary = [];
        foreach ($groups as $group) $summary[] = $group['count'] . ' × ' . (AdminNotifications::SOURCES[$group['source']][1] ?? 'Richiesta');
        return ['title'=>$total . ' nuove richieste sul sito','body'=>implode(' · ',$summary),
            'url'=>'notifications.php?unread=1','tag'=>'ksi-requests-' . $through];
    }
    public static function deliver(\PDO $pdo, ?\Minishlink\WebPush\WebPush $sender = null): array
    {
        $config=self::configuration();
        if (!$config['publicKey'] || !$config['privateKey']) throw new \RuntimeException('Configurazione Web Push mancante.');
        require_once __DIR__ . '/../vendor/autoload.php';
        $push=$sender ?? new \Minishlink\WebPush\WebPush(['VAPID'=>$config],['TTL'=>3600,'urgency'=>'normal'],15, ['allow_redirects'=>false]);
        $latest=AdminNotifications::latestId($pdo); $ok=0;$failed=0;$expired=0;
        // Group arrivals per device and identify request types without customer data.
        foreach ($pdo->query('SELECT s.* FROM admin_push_subscriptions s JOIN users u ON u.id=s.user_id WHERE s.last_notification_id<' . $latest . ' ORDER BY s.id LIMIT 100')->fetchAll(\PDO::FETCH_ASSOC) as $row) {
            try {
                $data=self::subscription($row['subscription']);
                $payload=self::payload($pdo,(int)$row['last_notification_id'],$latest);
                $report=$push->sendOneNotification(\Minishlink\WebPush\Subscription::create($data),json_encode($payload,JSON_UNESCAPED_UNICODE|JSON_THROW_ON_ERROR));
                if ($report->isSuccess()) {
                    $pdo->prepare('UPDATE admin_push_subscriptions SET last_notification_id=?,failures=0,last_success_at=NOW() WHERE id=?')->execute([$latest,$row['id']]); ++$ok;
                } elseif ($report->isSubscriptionExpired()) {
                    $pdo->prepare('DELETE FROM admin_push_subscriptions WHERE id=?')->execute([$row['id']]);++$expired;
                } else { $pdo->prepare('UPDATE admin_push_subscriptions SET failures=failures+1 WHERE id=?')->execute([$row['id']]);++$failed; }
            } catch (\Throwable $e) { ++$failed; error_log('[Web Push] Invio non riuscito per sottoscrizione ' . (int)$row['id']); }
        }
        return compact('ok','failed','expired');
    }
}
