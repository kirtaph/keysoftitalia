<?php
declare(strict_types=1);

require_once __DIR__ . '/../src/SpamGuard.php';

use KeySoftItalia\SpamGuard;
use KeySoftItalia\Api\FileStore;

$failures = 0;
$test = static function (string $name, callable $fn) use (&$failures): void {
    try { $fn(); echo "PASS $name\n"; }
    catch (Throwable $e) { ++$failures; echo "FAIL $name: {$e->getMessage()}\n"; }
};
$assert = static function (bool $value, string $detail = ''): void { if (!$value) throw new RuntimeException('Unexpected result ' . $detail); };

$legit = [
    ['name' => 'Mario', 'surname' => 'Rossi', 'email' => 'mario.rossi@gmail.com', 'phone' => '3331234567',
        'message' => 'Buongiorno, il mio iPhone 13 non si accende più dopo una caduta. Quanto costa la riparazione?'],
    ['name' => 'Giulia', 'surname' => 'De Luca', 'email' => 'giulia@studio.it',
        'message' => 'Salve, vorrei un preventivo per un sito web come www.esempio.it con SEO e shop online.'],
    ['name' => 'Anna', 'problem_description' => 'Il PC è lento e si blocca spesso, ho bisogno di assistenza a domicilio.'],
    ['name' => 'John', 'surname' => 'Smith', 'message' => 'Hello, my phone screen is broken, can you fix it today?'],
];
$spam = [
    ['name' => 'Charlestal', 'surname' => 'CharlestalNZ', 'email' => 'braun_marlon@yahoo.de', 'phone' => '81643148113',
        'message' => 'READ THE NEWS AND EARN $50,000,000 PER MONTH OPTIMISTIC https://telegra.ph/Read-the-latest-news-and-earn-50000000-in-a-month-Message-ID-453506-10-05'],
    ['name' => 'Robertgum', 'message' => 'Hello! We offer backlinks for your website, check https://seo-best.xyz/offer and https://x.top/a'],
    ['name' => 'Ivan', 'message' => 'Привет, лучшие цены на кредиты http://example.ru'],
    ['name' => 'Bot', 'message' => 'Ciao', 'website' => 'http://spam.example'],
];

$test('legitimate Italian and foreign customers pass', static function () use ($legit, $assert): void {
    foreach ($legit as $post) {
        $verdict = SpamGuard::evaluate($post, 60);
        $assert($verdict['score'] < SpamGuard::BLOCK, $post['name'] . ' ' . json_encode($verdict));
    }
});
$test('known spam is blocked', static function () use ($spam, $assert): void {
    foreach ($spam as $post) {
        $verdict = SpamGuard::evaluate($post, 60);
        $assert($verdict['score'] >= SpamGuard::BLOCK, $post['name'] . ' ' . json_encode($verdict));
    }
});
$test('production sample gets silent fake success', static function () use ($spam, $assert): void {
    $assert(SpamGuard::evaluate($spam[0], 60)['score'] >= SpamGuard::SILENT);
});
$test('instant submission adds score without blocking alone', static function () use ($legit, $assert): void {
    $verdict = SpamGuard::evaluate($legit[0], 1);
    $assert(in_array('too-fast', $verdict['reasons'], true) && $verdict['score'] < SpamGuard::BLOCK);
});

$directory = sys_get_temp_dir() . '/ksi-spam-' . bin2hex(random_bytes(4));
try {
    $guard = new SpamGuard(new FileStore($directory), 2, 3);
    $test('per-IP burst and daily limits', static function () use ($guard, $assert): void {
        $assert(!$guard->overLimit('1.1.1.1', 1000));
        $assert(!$guard->overLimit('1.1.1.1', 1001));
        $assert($guard->overLimit('1.1.1.1', 1002));
        $assert(!$guard->overLimit('2.2.2.2', 1002));
        $assert(!$guard->overLimit('1.1.1.1', 2000));
        $assert($guard->overLimit('1.1.1.1', 3000));
        $assert(!$guard->overLimit('1.1.1.1', 1000 + 86401));
    });
} finally {
    foreach (glob($directory . '/*') ?: [] as $file) unlink($file);
    if (is_dir($directory)) rmdir($directory);
}
exit($failures > 0 ? 1 : 0);
