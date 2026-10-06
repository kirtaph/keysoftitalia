<?php
declare(strict_types=1);
namespace KeySoftItalia;

final class SecondaryValidation
{
    public static function date(mixed $value): string
    {
        if (!is_string($value)) throw new \InvalidArgumentException('Data non valida.');
        $date = \DateTimeImmutable::createFromFormat('!Y-m-d', $value);
        if (!$date || $date->format('Y-m-d') !== $value) throw new \InvalidArgumentException('Data non valida.');
        return $value;
    }
    private static function time(mixed $value): string
    {
        if (!is_string($value) || !preg_match('/^(?:[01]\d|2[0-3]):[0-5]\d(?::00)?$/D', $value)) throw new \InvalidArgumentException('Orario non valido.');
        return substr($value, 0, 5);
    }
    public static function interval(array $row, bool $active): void
    {
        if (!$active) return;
        if (self::time($row['open_time'] ?? null) >= self::time($row['close_time'] ?? null)) throw new \InvalidArgumentException('La chiusura deve essere successiva all’apertura.');
    }
    private static function flag(mixed $value): void
    {
        if (!in_array((string)$value, ['0', '1'], true)) throw new \InvalidArgumentException('Stato non valido.');
    }
    public static function admin(array $get, array &$post, string $handler): void
    {
        $media = ['telephony_actions.php', 'utility_actions.php', 'flyer_actions.php', 'video_actions.php', 'team_actions.php'];
        $hours = ['weekly_hours_actions.php', 'exceptions_actions.php', 'holidays_actions.php', 'hours_actions.php'];
        if (!in_array($handler, array_merge($media, $hours), true)) return;
        $action = $post['action'] ?? '';
        if (in_array($action, ['edit', 'delete', 'edit_partner', 'delete_partner', 'delete_request', 'update_request_status', 'delete_holiday'], true) && empty($post['id'])) throw new \InvalidArgumentException('ID obbligatorio.');
        foreach (['active', 'is_closed', 'show_home', 'is_featured'] as $field) if (isset($post[$field])) self::flag($post[$field]);
        if (isset($post['status']) && $action !== 'update_request_status') self::flag($post['status']);
        if (isset($post['sort_order']) && (!is_scalar($post['sort_order']) || !preg_match('/^-?\d{1,9}$/D', (string)$post['sort_order']))) throw new \InvalidArgumentException('Ordine non valido.');
        if ($action === 'update_request_status' && !in_array($post['status'] ?? '', ['In attesa', 'Contattato', 'Completato', 'Annullato'], true)) throw new \InvalidArgumentException('Stato richiesta non valido.');
        foreach (['title' => 255, 'slug' => 255, 'name' => 100, 'role' => 100, 'plan_name' => 150, 'price_detail' => 50, 'icon_class' => 50, 'icon_color' => 50, 'duration' => 10, 'category' => 50, 'skills' => 255, 'aos_animation' => 50, 'notice' => 255, 'fb_video_url' => 255] as $field => $length) {
            if (isset($post[$field]) && mb_strlen((string)$post[$field]) > $length) throw new \InvalidArgumentException('Campo troppo lungo: ' . $field . '.');
        }
        if (in_array($action, ['add', 'edit', 'add_partner', 'edit_partner'], true)) {
            $required = match($handler) {
                'flyer_actions.php' => ['title', 'slug', 'start_date', 'end_date'],
                'video_actions.php' => ['title', 'fb_video_url'],
                'team_actions.php' => ['name', 'role'],
                'telephony_actions.php', 'utility_actions.php' => str_contains($action, 'partner') ? ['name'] : ['partner_id', 'plan_name', 'price'],
                default => [],
            };
            foreach ($required as $field) if (!isset($post[$field]) || trim((string)$post[$field]) === '') throw new \InvalidArgumentException('Campo obbligatorio: ' . $field . '.');
            if (isset($post['price'])) $post['price'] = BackendValidation::money($post['price'], 'price');
            if ($handler === 'flyer_actions.php') {
                if (self::date($post['start_date']) > self::date($post['end_date'])) throw new \InvalidArgumentException('La scadenza precede l’inizio.');
            }
        }
        if (!in_array($handler, $hours, true)) return;
        if ($action === 'get_exceptions') {
            $start = self::date($get['start'] ?? date('Y-m-01')); $end = self::date($get['end'] ?? date('Y-m-t'));
            if ($start > $end || (int)substr($end, 0, 4) - (int)substr($start, 0, 4) > 10) throw new \InvalidArgumentException('Intervallo calendario non valido.');
        }
        if (in_array($action, ['save_exception', 'delete_exception'], true) || ($handler === 'exceptions_actions.php' && in_array($action, ['add', 'edit'], true))) self::date($post['date'] ?? null);
        if ($handler === 'weekly_hours_actions.php' && in_array($action, ['add', 'edit'], true)) {
            if (!in_array((string)($post['dow'] ?? ''), ['1','2','3','4','5','6','7'], true)) throw new \InvalidArgumentException('Giorno non valido.');
        }
        if (in_array($handler, ['weekly_hours_actions.php','exceptions_actions.php'], true) && in_array($action, ['add', 'edit'], true)) {
            if (!in_array((string)($post['seg'] ?? ''), ['1','2'], true)) throw new \InvalidArgumentException('Segmento non valido.');
            self::interval($post, $handler === 'weekly_hours_actions.php' || empty($post['is_closed']));
        }
        if ($action === 'save_weekly') {
            if (!is_array($post['hours'] ?? null) || !$post['hours']) throw new \InvalidArgumentException('Orari mancanti.');
            $seen = [];
            foreach ($post['hours'] as $row) {
                if (!is_array($row)) throw new \InvalidArgumentException('Orari non validi.');
                BackendValidation::scalarFields($row);
                if (!in_array((string)($row['dow'] ?? ''), ['1','2','3','4','5','6','7'], true) || !in_array((string)($row['seg'] ?? ''), ['1','2'], true)) throw new \InvalidArgumentException('Giorno o segmento non valido.');
                $key = $row['dow'] . '-' . $row['seg'];
                if (isset($seen[$key])) throw new \InvalidArgumentException('Segmento orario duplicato.');
                $seen[$key] = true;
                self::flag($row['active'] ?? ''); self::interval($row, (string)$row['active'] === '1');
            }
        }
        if ($action === 'save_exception') {
            if (!is_array($post['segments'] ?? null) || !$post['segments']) throw new \InvalidArgumentException('Segmenti mancanti.');
            foreach ($post['segments'] as $key => $row) {
                if (!in_array((string)$key, ['1','2'], true) || !is_array($row)) throw new \InvalidArgumentException('Segmento non valido.');
                BackendValidation::scalarFields($row); self::flag($row['active'] ?? '0'); self::interval($row, !empty($row['active']));
            }
        }
        if ($action === 'save_holiday' || ($handler === 'holidays_actions.php' && in_array($action, ['add','edit'], true))) {
            if (trim((string)($post['name'] ?? '')) === '') throw new \InvalidArgumentException('Nome festività obbligatorio.');
            if (($post['rule_type'] ?? '') === 'fixed') {
                if (!ctype_digit((string)($post['month'] ?? '')) || !ctype_digit((string)($post['day'] ?? '')) || !checkdate((int)$post['month'], (int)$post['day'], 2000)) throw new \InvalidArgumentException('Data festività non valida.');
                $post['offset_days'] = 0;
            } elseif (($post['rule_type'] ?? '') === 'easter') {
                if (!preg_match('/^-?\d{1,3}$/D', (string)($post['offset_days'] ?? ''))) throw new \InvalidArgumentException('Offset Pasqua non valido.');
                $post['month'] = $post['day'] = null;
            } else throw new \InvalidArgumentException('Regola festività non valida.');
        }
    }
}
