<?php
declare(strict_types=1);

namespace KeySoftItalia;

use KeySoftItalia\Api\FileStore;

require_once __DIR__ . '/RefurbishedApi.php';

/**
 * Scores public form submissions. Bots that load the page keep a valid CSRF
 * token and skip hidden honeypots, so content, timing and per-IP volume decide.
 */
final class SpamGuard
{
    /** Score at which a request is refused with a visible message. */
    public const BLOCK = 5;
    /** Score at which a request is answered with a fake success, so bots learn nothing. */
    public const SILENT = 9;
    /** Humans need longer than this between page view and submission. */
    public const MIN_SECONDS = 3;

    private const SKIP = ['csrf_token', 'privacy', 'privacy_accepted', 'mode', 'website'];
    private const SHORTENERS = '~\b(?:telegra\.ph|t\.me|bit\.ly|tinyurl\.com|goo\.gl|cutt\.ly|rebrand\.ly|is\.gd|linktr\.ee|shorturl\.at|rb\.gy)\b~i';
    private const KEYWORDS = '~\b(?:earn(?:ing)?|profit|crypto|bitcoin|btc|forex|casino|betting|viagra|cialis|porn|sex|dating|loan|backlinks?|investment|passive income|make money|per month|per day|millions?|giveaway|airdrop|click here|guaranteed|news)\b~i';

    /** @return array{score:int, reasons:list<string>} */
    public static function evaluate(array $post, ?int $elapsed = null): array
    {
        $score = 0;
        $reasons = [];
        $add = static function (int $points, string $reason) use (&$score, &$reasons): void {
            $score += $points;
            $reasons[] = $reason;
        };

        if (trim((string)($post['website'] ?? '')) !== '') {
            $add(self::SILENT, 'honeypot');
        }

        $names = [];
        $texts = [];
        foreach ($post as $key => $value) {
            if (in_array($key, self::SKIP, true)) continue;
            $values = is_array($value) ? $value : [$value];
            foreach ($values as $item) {
                if (!is_string($item) || trim($item) === '') continue;
                if (preg_match('/name|nome|cognome/i', (string)$key)) $names[] = $item;
                if (!preg_match('/mail/i', (string)$key)) $texts[] = $item;
            }
        }
        $text = implode("\n", $texts);

        $links = preg_match_all('~https?://|www\.|\b[a-z0-9-]+\.[a-z]{2,}/\S~i', $text);
        if ($links > 0) $add($links > 1 ? 4 : 2, 'link');
        if (preg_match(self::SHORTENERS, $text)) $add(4, 'shortener');
        if (preg_match('~\[url|\[link|<a\s~i', $text)) $add(4, 'markup');
        if (preg_match('~\$\s?\d[\d.,]{3,}|\d[\d.,]{3,}\s?(?:\$|usd|dollars?)\b~i', $text)) $add(2, 'money');

        $keywords = preg_match_all(self::KEYWORDS, $text, $matches);
        if ($keywords > 0) {
            $distinct = count(array_unique(array_map('strtolower', $matches[0])));
            $add(min(6, 2 * $distinct), 'keywords');
        }

        if (preg_match_all('/[\p{Cyrillic}\p{Han}\p{Arabic}\p{Hebrew}\p{Thai}\p{Hangul}\p{Hiragana}\p{Katakana}]/u', $text) >= 3) {
            $add(4, 'script');
        }

        $letters = preg_match_all('/\p{L}/u', $text);
        if ($letters >= 20 && preg_match_all('/\p{Lu}/u', $text) / $letters > 0.7) $add(1, 'caps');

        $english = preg_match_all('~\b(?:the|and|you|your|our|we|with|this|that|are|have|will|from|business|website)\b~i', $text);
        $italian = preg_match_all('~\b(?:il|la|di|che|non|per|un|una|sono|ho|mi|del|della|con|buongiorno|salve|grazie|vorrei)\b~i', $text);
        if ($english >= 3 && $english > 2 * $italian) $add(2, 'english');

        foreach ($names as $name) {
            // Typical bot names: "Charlestal CharlestalNZ", "Robertgum RobertgumXY".
            if (preg_match('/\b(\p{L}{3,})\s+\1\p{Lu}{1,3}\b/u', $name) || preg_match('/\p{Ll}\p{Lu}{2,3}\b/u', $name)) {
                $add(4, 'name');
                break;
            }
        }

        if ($elapsed !== null && $elapsed < self::MIN_SECONDS) $add(3, 'too-fast');

        return ['score' => $score, 'reasons' => $reasons];
    }

    public function __construct(private FileStore $store, private int $burst = 5, private int $daily = 15) {}

    /** Records a submission and reports whether the IP exceeded its quota. */
    public function overLimit(string $ip, ?int $now = null): bool
    {
        // Loopback means local tests or a local proxy where every visitor would share one IP.
        if (in_array($ip, ['127.0.0.1', '::1'], true)) return false;
        $now ??= time();
        $key = hash('sha256', 'public:' . $ip);
        return $this->store->update('submissions.json', function (array &$state) use ($key, $now): bool {
            foreach ($state as $k => $times) {
                $state[$k] = array_values(array_filter((array)$times, static fn($t) => is_int($t) && $t > $now - 86400));
                if (!$state[$k]) unset($state[$k]);
            }
            $times = $state[$key] ?? [];
            $recent = count(array_filter($times, static fn($t) => $t > $now - 600));
            if ($recent >= $this->burst || count($times) >= $this->daily) return true;
            $times[] = $now;
            $state[$key] = $times;
            return false;
        });
    }
}
