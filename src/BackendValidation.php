<?php
declare(strict_types=1);

namespace KeySoftItalia;
require_once __DIR__ . '/SecondaryValidation.php';

final class BackendValidation
{
    public static function money(mixed $value, string $field, bool $optional = false): ?string
    {
        if (($value === null || $value === '') && $optional) return null;
        if (!is_scalar($value)) throw new \InvalidArgumentException("Importo non valido: $field.");
        $value = str_replace(',', '.', trim((string)$value));
        if (!preg_match('/^\d{1,8}(?:\.\d{1,2})?$/D', $value)) {
            throw new \InvalidArgumentException("Importo non valido: $field.");
        }
        return number_format((float)$value, 2, '.', '');
    }

    public static function scalarFields(array $data, array $arrays = []): void
    {
        foreach ($data as $key => $value) {
            if (!is_scalar($value) && $value !== null && !in_array($key, $arrays, true)) {
                throw new \InvalidArgumentException('Formato non valido per il campo ' . $key . '.');
            }
        }
    }

    public static function admin(array $get, array &$post, string $handler): void
    {
        self::scalarFields($get);
        self::scalarFields($post, ['hours', 'segments', 'sort_order']);
        SecondaryValidation::admin($get, $post, $handler);
        foreach (array_replace($get, $post) as $key => $value) {
            if (($key === 'id' || str_ends_with((string)$key, '_id')) && $value !== '' && $value !== null) {
                if (!is_scalar($value) || !preg_match('/^[1-9]\d*$/D', (string)$value)
                    || filter_var($value, FILTER_VALIDATE_INT) === false) {
                    throw new \InvalidArgumentException('Identificativo non valido: ' . $key . '.');
                }
            }
        }
        $action = $post['action'] ?? '';
        $schemas = [
            'product_actions.php' => ['add' => ['model_id', 'sku', 'price_eur'], 'edit' => ['id', 'model_id', 'sku', 'price_eur']],
            'user_actions.php' => ['add' => ['username', 'email', 'password'], 'edit' => ['id', 'username', 'email']],
            'booking_actions.php' => ['edit' => ['id', 'status']],
            'used_quote_actions.php' => ['edit' => ['id', 'status']],
            'quote_actions.php' => ['edit' => ['id', 'status'], 'create_price_rule' => ['quote_id', 'price']],
            'price_rule_actions.php' => ['add' => ['device_id', 'issue_id', 'min_price'], 'edit' => ['id', 'device_id', 'issue_id', 'min_price']],
        ];
        foreach ($schemas[$handler][$action] ?? [] as $field) {
            if (!isset($post[$field]) || trim((string)$post[$field]) === '') {
                throw new \InvalidArgumentException('Campo obbligatorio: ' . $field . '.');
            }
        }
        if ($handler === 'user_actions.php' && in_array($action, ['add', 'edit'], true)) {
            if (!filter_var($post['email'], FILTER_VALIDATE_EMAIL) || strlen($post['username']) > 50 || strlen($post['email']) > 100) {
                throw new \InvalidArgumentException('Username o email non validi.');
            }
        }
        $statuses = [
            'booking_actions.php' => ['pending', 'confirmed', 'cancelled', 'completed'],
            'used_quote_actions.php' => ['pending', 'reviewed', 'contacted', 'accepted', 'rejected'],
            'quote_actions.php' => ['pending', 'replied', 'accepted', 'rejected'],
        ];
        if ($action === 'edit' && isset($statuses[$handler]) && !in_array($post['status'], $statuses[$handler], true)) {
            throw new \InvalidArgumentException('Stato non valido.');
        }
        if (isset($schemas[$handler])) {
            foreach (['price_eur', 'list_price', 'expected_price', 'est_min', 'est_max', 'min_price', 'max_price', 'price'] as $field) {
                if (array_key_exists($field, $post)) {
                    $optional = in_array($field, ['list_price', 'expected_price', 'est_min', 'est_max', 'max_price'], true);
                    $post[$field] = self::money($post[$field], $field, $optional);
                }
            }
            foreach ([['est_min', 'est_max'], ['min_price', 'max_price']] as [$min, $max]) {
                if (isset($post[$min], $post[$max]) && (float)$post[$min] > (float)$post[$max]) {
                    throw new \InvalidArgumentException('Il prezzo massimo deve essere maggiore o uguale al minimo.');
                }
            }
        }
        if ($handler === 'product_actions.php' && in_array($action, ['add', 'edit'], true)) {
            if (strlen($post['sku']) > 40 || !in_array($post['grade'] ?? '', ['Nuovo', 'Expo', 'A+', 'A', 'B', 'C'], true)) {
                throw new \InvalidArgumentException('SKU o grado prodotto non validi.');
            }
            $storage = $post['storage_gb'] ?? '';
            if ($storage !== '' && (!ctype_digit((string)$storage) || (int)$storage > 65535)) {
                throw new \InvalidArgumentException('Capacità di memoria non valida.');
            }
            $post += ['color' => '', 'storage_gb' => '', 'short_desc' => '', 'full_desc' => ''];
        }
    }
}
