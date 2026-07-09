<?php
declare(strict_types=1);

namespace KeySoftItalia\Api;

use RuntimeException;

final class ApiCredentialStore
{
    public function __construct(private readonly string $path) {}

    public function load(): array
    {
        if (!is_file($this->path)) {
            return ['api_key' => '', 'secret' => '', 'created_at' => null];
        }
        $credentials = require $this->path;
        if (!is_array($credentials)) {
            throw new RuntimeException('File credenziali API non valido');
        }
        return [
            'api_key' => (string)($credentials['api_key'] ?? ''),
            'secret' => (string)($credentials['secret'] ?? ''),
            'created_at' => $credentials['created_at'] ?? null,
        ];
    }

    public function generate(): array
    {
        $credentials = [
            'api_key' => 'ksi_' . bin2hex(random_bytes(16)),
            'secret' => bin2hex(random_bytes(32)),
            'created_at' => date(DATE_ATOM),
        ];
        $directory = dirname($this->path);
        if (!is_dir($directory) && !mkdir($directory, 0750, true) && !is_dir($directory)) {
            throw new RuntimeException('Impossibile creare la directory credenziali');
        }

        $contents = "<?php\n// Generato dal pannello amministrativo. Non versionare.\nreturn "
            . var_export($credentials, true) . ";\n";
        $temporary = $this->path . '.tmp-' . bin2hex(random_bytes(6));
        if (file_put_contents($temporary, $contents, LOCK_EX) === false) {
            throw new RuntimeException('Impossibile salvare le credenziali API');
        }
        @chmod($temporary, 0640);
        if (!@rename($temporary, $this->path)) {
            // Windows non sostituisce atomicamente un file esistente.
            if ((is_file($this->path) && !unlink($this->path)) || !rename($temporary, $this->path)) {
                @unlink($temporary);
                throw new RuntimeException('Impossibile attivare le credenziali API');
            }
        }
        return $credentials;
    }

    public function status(): array
    {
        $credentials = $this->load();
        $key = $credentials['api_key'];
        return [
            'configured' => $key !== '' && $credentials['secret'] !== '',
            'masked_key' => $key === '' ? null : substr($key, 0, 8) . '••••••••' . substr($key, -4),
            'created_at' => $credentials['created_at'],
        ];
    }
}
