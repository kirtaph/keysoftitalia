<?php
declare(strict_types=1);
namespace KeySoftItalia;

final class AdminList
{
    public const STATUSES = [
        'quotes' => ['pending' => 'In attesa', 'replied' => 'Risposto', 'accepted' => 'Accettato', 'rejected' => 'Rifiutato'],
        'used' => ['pending' => 'In attesa', 'reviewed' => 'Revisionata', 'contacted' => 'Contattato', 'accepted' => 'Accettata', 'rejected' => 'Rifiutata'],
        'bookings' => ['pending' => 'In attesa', 'confirmed' => 'Confermata', 'cancelled' => 'Cancellata', 'completed' => 'Completata'],
    ];
    public static function load(\PDO $pdo, string $module, array $input): array
    {
        if (!isset(self::STATUSES[$module])) throw new \InvalidArgumentException('Lista sconosciuta.');
        $scalar = static fn(string $key): string => is_scalar($input[$key] ?? null) ? trim((string)$input[$key]) : '';
        $q = mb_substr($scalar('q'), 0, 160);
        $status = array_key_exists($scalar('status'), self::STATUSES[$module]) ? $scalar('status') : '';
        $device = $module === 'quotes' ? mb_substr($scalar('device'), 0, 100) : '';
        $perPage = in_array($scalar('per_page'), ['25', '50', '100'], true) ? (int)$scalar('per_page') : 25;
        $requested = ctype_digit($scalar('page')) ? max(1, min(1000000, (int)$scalar('page'))) : 1;
        $from = match($module) {
            'quotes' => 'quotes q JOIN devices d ON q.device_id = d.id',
            'used' => 'used_device_quotes q',
            'bookings' => 'repair_bookings q',
        };
        $fields = $module === 'quotes'
            ? ["CONCAT_WS(' ', q.first_name, q.last_name)", 'q.email', 'q.phone', 'q.company', 'q.brand_text', 'q.model_text']
            : ["CONCAT_WS(' ', q.customer_first_name, q.customer_last_name)", 'q.customer_email', 'q.customer_phone', 'q.brand_name', 'q.model_name'];
        $where = []; $values = [];
        $open = $scalar('open');
        if (preg_match('/^[1-9]\d{0,17}$/D', $open)) { $where[] = 'q.id = ?'; $values[] = $open; }
        if ($q !== '') {
            $fields[] = 'CAST(q.id AS CHAR)';
            $where[] = '(' . implode(' OR ', array_map(static fn($field) => "$field LIKE ? ESCAPE '!'", $fields)) . ')';
            $pattern = '%' . str_replace(['!', '%', '_'], ['!!', '!%', '!_'], $q) . '%';
            $values = array_merge($values, array_fill(0, count($fields), $pattern));
        }
        if ($status !== '') { $where[] = 'q.status = ?'; $values[] = $status; }
        if ($device !== '') { $where[] = 'd.name = ?'; $values[] = $device; }
        $clause = $where ? ' WHERE ' . implode(' AND ', $where) : '';
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM $from$clause"); $stmt->execute($values);
        $total = (int)$stmt->fetchColumn();
        $pages = max(1, (int)ceil($total / $perPage)); $page = min($requested, $pages);
        $select = $module === 'quotes' ? 'q.*, d.name AS device_name' : 'q.*';
        $stmt = $pdo->prepare("SELECT $select FROM $from$clause ORDER BY q.created_at DESC, q.id DESC LIMIT ? OFFSET ?");
        foreach ($values as $index => $value) $stmt->bindValue($index + 1, $value, \PDO::PARAM_STR);
        $stmt->bindValue(count($values) + 1, $perPage, \PDO::PARAM_INT);
        $stmt->bindValue(count($values) + 2, ($page - 1) * $perPage, \PDO::PARAM_INT);
        $stmt->execute();
        return ['rows' => $stmt->fetchAll(\PDO::FETCH_ASSOC), 'total' => $total, 'pages' => $pages,
            'page' => $page, 'per_page' => $perPage, 'q' => $q, 'status' => $status, 'device' => $device, 'module' => $module];
    }
    public static function url(array $list, int $page): string
    {
        return '?' . http_build_query(array_filter(['q' => $list['q'], 'status' => $list['status'], 'device' => $list['device'], 'per_page' => $list['per_page'], 'page' => $page], static fn($v) => $v !== ''));
    }
}
