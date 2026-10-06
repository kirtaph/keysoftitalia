<?php
declare(strict_types=1);

namespace KeySoftItalia;

final class BackendHttp
{
    public static function encode(mixed $data, int $flags = 0): string
    {
        return json_encode($data, $flags | JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE | JSON_THROW_ON_ERROR);
    }

    public static function send(array $data, int $status = 200): never
    {
        try {
            $body = self::encode($data);
        } catch (\JsonException $e) {
            error_log('[JSON response] ' . $e->getMessage());
            $status = 500;
            $body = '{"status":"error","ok":false,"success":false,"message":"Errore nella risposta del server."}';
        }
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        header('Cache-Control: no-store');
        echo $body;
        exit;
    }

    public static function startSession(): void
    {
        if (session_status() !== PHP_SESSION_NONE) return;
        $https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
            || (int)($_SERVER['SERVER_PORT'] ?? 0) === 443;
        session_set_cookie_params(['lifetime' => 0, 'path' => '/', 'secure' => $https,
            'httponly' => true, 'samesite' => 'Lax']);
        session_start();
    }
}
