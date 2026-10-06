<?php
declare(strict_types=1);
namespace KeySoftItalia;

final class AdminNotifications
{
    public const SOURCES = [
        'quote' => ['quotes', 'Preventivo riparazione', 'quotes.php'],
        'used' => ['used_device_quotes', 'Valutazione usato', 'used_quotes.php'],
        'booking' => ['repair_bookings', 'Prenotazione riparazione', 'bookings.php'],
        'telephony' => ['telephony_requests', 'Richiesta telefonia', 'telefonia.php'],
        'utility' => ['utility_requests', 'Richiesta luce/gas', 'forniture.php'],
        'liberty' => ['liberty_demo_requests', 'Demo Liberty', 'liberty_demo.php'],
        'contact' => [null, 'Messaggio contatti', 'notifications.php'],
        'assistance' => [null, 'Richiesta assistenza', 'notifications.php'],
    ];
    public static function publish(\PDO $pdo, string $source, ?int $sourceId = null, ?string $detail = null, ?string $date = null): void
    {
        if (!isset(self::SOURCES[$source])) throw new \InvalidArgumentException('Tipo richiesta non valido.');
        $stmt = $pdo->prepare('INSERT IGNORE INTO admin_notifications (source,source_id,title,detail,created_at) VALUES (?,?,?,?,COALESCE(?,NOW()))');
        $stmt->execute([$source, $sourceId, self::SOURCES[$source][1], $detail, $date]);
    }
    // Recover records even if the public handler was temporarily unable to write the event.
    // Missing IDs are found with NOT EXISTS, so out-of-order commits cannot be skipped.
    public static function sync(\PDO $pdo): void
    {
        foreach (self::SOURCES as $source => [$table, $title]) {
            if (!$table) continue;
            $stmt = $pdo->prepare("INSERT IGNORE INTO admin_notifications (source,source_id,title,created_at)
                SELECT ?,r.id,?,r.created_at FROM $table r WHERE NOT EXISTS
                (SELECT 1 FROM admin_notifications n WHERE n.source=? AND n.source_id=r.id)
                ORDER BY r.id LIMIT 250");
            $stmt->execute([$source, $title, $source]);
        }
    }
    public static function latestId(\PDO $pdo): int { return (int)$pdo->query('SELECT COALESCE(MAX(id),0) FROM admin_notifications')->fetchColumn(); }
    public static function listing(\PDO $pdo, int $user, int $page = 1, bool $unread = false): array
    {
        $join = ' FROM admin_notifications n LEFT JOIN admin_notification_reads r ON r.notification_id=n.id AND r.user_id=?';
        $where = $unread ? ' WHERE r.notification_id IS NULL' : '';
        $stmt = $pdo->prepare('SELECT COUNT(*)' . $join . $where); $stmt->execute([$user]); $total = (int)$stmt->fetchColumn();
        $stmt = $pdo->prepare('SELECT COUNT(*)' . $join . ' WHERE r.notification_id IS NULL'); $stmt->execute([$user]); $new = (int)$stmt->fetchColumn();
        $pages = max(1, (int)ceil($total/25)); $page = max(1, min($pages, $page));
        $stmt = $pdo->prepare('SELECT n.*,r.read_at' . $join . $where . ' ORDER BY n.created_at DESC,n.id DESC LIMIT 25 OFFSET ' . (($page-1)*25));
        $stmt->execute([$user]); $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        foreach ($rows as &$row) $row['url'] = self::url($row);
        return ['rows'=>$rows,'total'=>$total,'unread'=>$new,'page'=>$page,'pages'=>$pages,'cursor'=>self::latestId($pdo)];
    }
    public static function url(array $row): string
    {
        $page = self::SOURCES[$row['source']][2] ?? 'notifications.php';
        if (in_array($row['source'], ['quote','used','booking'], true)) return $page . '?q=' . (int)$row['source_id'] . '&open=' . (int)$row['source_id'];
        if (in_array($row['source'], ['contact','assistance'], true)) return 'notifications.php?open=' . (int)$row['id'];
        if (in_array($row['source'], ['telephony','utility'], true)) return $page . '#requests-panel';
        return $page;
    }
    public static function mark(\PDO $pdo, int $user, int $id): void
    {
        if ($id < 1) throw new \InvalidArgumentException('Notifica non valida.');
        $pdo->prepare('INSERT IGNORE INTO admin_notification_reads (user_id,notification_id) SELECT ?,id FROM admin_notifications WHERE id=?')->execute([$user,$id]);
    }
    public static function markAll(\PDO $pdo, int $user, int $through): void
    {
        if ($through < 0) throw new \InvalidArgumentException('Limite notifiche non valido.');
        $pdo->prepare('INSERT IGNORE INTO admin_notification_reads (user_id,notification_id) SELECT ?,id FROM admin_notifications WHERE id<=?')->execute([$user,$through]);
    }
    public static function capture(\PDO $pdo, string $source, array $data): void
    {
        try { self::publish($pdo,$source,null,json_encode($data, JSON_UNESCAPED_UNICODE|JSON_INVALID_UTF8_SUBSTITUTE|JSON_THROW_ON_ERROR)); }
        catch (\Throwable $e) { error_log('[Notification capture] ' . $e->getMessage()); }
    }
}
