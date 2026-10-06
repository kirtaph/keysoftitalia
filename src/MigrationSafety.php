<?php
declare(strict_types=1);

namespace KeySoftItalia;

final class MigrationSafety
{
    public static function check(string $sql, string $filename): void
    {
        $statements = preg_replace('/\/\*[\s\S]*?\*\/|^[ \t]*--[^\r\n]*/m', '', $sql);
        if (trim((string)$statements) === '') throw new \InvalidArgumentException('Migrazione vuota: ' . $filename);
        if (preg_match('/\b(?:DROP\s+(?:TABLE|DATABASE)|TRUNCATE\s+(?:TABLE\s+)?|DELETE\s+FROM)\b/i', (string)$statements)) {
            throw new \InvalidArgumentException('Migrazione con cancellazione dati: ' . $filename . '. Eseguirla manualmente dopo aver verificato il backup.');
        }
    }
}
