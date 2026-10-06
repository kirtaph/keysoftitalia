<?php
declare(strict_types=1);

namespace KeySoftItalia;

use KeySoftItalia\Api\FileStore;

require_once __DIR__ . '/RefurbishedApi.php';

/** Failed logins are counted independently of browser cookies. */
final class LoginThrottle
{
    public function __construct(private FileStore $store, private int $window = 900) {}

    private function keys(string $ip, string $username): array
    {
        return [hash('sha256', 'ip:' . $ip) => 30,
            hash('sha256', 'account:' . $ip . ':' . strtolower(trim($username))) => 5];
    }

    public function remaining(string $ip, string $username, ?int $now = null): int
    {
        $now ??= time();
        return $this->store->update('attempts.json', function (array &$state) use ($ip, $username, $now): int {
            $state = array_filter($state, static fn($entry) => is_array($entry) && ($entry['expires'] ?? 0) > $now);
            $remaining = 0;
            foreach ($this->keys($ip, $username) as $key => $limit) {
                if (($state[$key]['count'] ?? 0) >= $limit) {
                    $remaining = max($remaining, (int)$state[$key]['expires'] - $now);
                }
            }
            return $remaining;
        });
    }

    public function failed(string $ip, string $username, ?int $now = null): void
    {
        $now ??= time();
        $this->store->update('attempts.json', function (array &$state) use ($ip, $username, $now): void {
            $state = array_filter($state, static fn($entry) => is_array($entry) && ($entry['expires'] ?? 0) > $now);
            foreach ($this->keys($ip, $username) as $key => $limit) {
                $entry = $state[$key] ?? ['count' => 0, 'expires' => $now + $this->window];
                $entry['count']++;
                $state[$key] = $entry;
            }
        });
    }

    public function succeeded(string $ip, string $username): void
    {
        $key = array_key_last($this->keys($ip, $username));
        $this->store->update('attempts.json', static function (array &$state) use ($key): void { unset($state[$key]); });
    }
}
