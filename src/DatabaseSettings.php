<?php
declare(strict_types=1);
namespace KeySoftItalia;

final class DatabaseSettings
{
    /** Environment wins, then private local configuration, then existing defaults. */
    public static function resolve(array $local, ?callable $environment = null): array
    {
        $environment ??= static fn(string $key): string|false => getenv($key);
        $defaults = ['DB_HOST'=>'localhost','DB_PORT'=>3306,'DB_NAME'=>'ks_site_db',
            'DB_USER'=>'keysoftfi_db','DB_PASS'=>'','DB_CHARSET'=>'utf8mb4'];
        $settings = [];
        foreach ($defaults as $key=>$default) {
            $value = $environment($key);
            if ($value === false || ($value === '' && $key !== 'DB_PASS')) $value = $local[$key] ?? $default;
            if (!is_scalar($value)) throw new \InvalidArgumentException('Configurazione database non valida: ' . $key);
            $settings[$key] = $key === 'DB_PORT' ? (int)$value : (string)$value;
        }
        return $settings;
    }
}
