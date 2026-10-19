<?php
if (!defined('BASE_PATH')) define('BASE_PATH', __DIR__ . '/');
require_once BASE_PATH . 'config/config.php';
$esc = static fn($value): string => htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
$homeMedia = static function (?string $path): ?string {
    if (!$path) return null;
    $root = str_replace('\\', '/', (string)realpath(BASE_PATH)) . '/';
    if (filter_var($path, FILTER_VALIDATE_URL)) {
        if (!in_array(parse_url($path, PHP_URL_SCHEME), ['http', 'https'], true)) return null;
        if (parse_url($path, PHP_URL_HOST) !== parse_url(BASE_URL, PHP_URL_HOST)) return $path;
        $path = rawurldecode((string)parse_url($path, PHP_URL_PATH));
        $base = (string)parse_url(BASE_URL, PHP_URL_PATH);
        if ($base && str_starts_with($path, $base)) $path = substr($path, strlen($base));
    }
    foreach ([ltrim($path, '/'), 'assets/' . ltrim($path, '/')] as $candidate) {
        $full = realpath(BASE_PATH . $candidate);
        if ($full && is_file($full) && str_starts_with(str_replace('\\', '/', $full), $root)) return url($candidate);
    }
    return null;
};
$featured_flyer = null; $homeVideo = null; $homeProducts = [];
try {
    $stmt = $pdo->prepare('SELECT title, slug, end_date, cover_image, pdf_file FROM flyers WHERE status = 1 AND show_home = 1 AND start_date <= :start AND end_date >= :end ORDER BY start_date DESC, id DESC LIMIT 1');
    $today = (new DateTimeImmutable('now', new DateTimeZone(KS_TZ)))->format('Y-m-d');
    $stmt->execute([':start' => $today, ':end' => $today]); $featured_flyer = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
} catch (Throwable $e) { error_log('[Home flyer] ' . $e->getMessage()); }
$homeFlyerCover = $homeMedia($featured_flyer['cover_image'] ?? null);
$homeFlyerUrl = $homeMedia($featured_flyer['pdf_file'] ?? null) ?? url('volantini.php?flyer=' . rawurlencode($featured_flyer['slug'] ?? ''));
try {
    $homeVideo = $pdo->query('SELECT title, fb_video_url, cover_image FROM videos WHERE status = 1 ORDER BY is_featured DESC, created_at DESC LIMIT 1')->fetch(PDO::FETCH_ASSOC) ?: null;
} catch (Throwable $e) { error_log('[Home video] ' . $e->getMessage()); }
try {
    $homeProducts = $pdo->query('SELECT p.sku, p.title, p.price_eur, p.grade, b.name AS brand, m.name AS model, d.name AS device, COALESCE(MAX(CASE WHEN pi.is_cover = 1 THEN pi.path END), MAX(pi.path)) AS image_path FROM products p JOIN models m ON p.model_id = m.id JOIN brands b ON m.brand_id = b.id JOIN devices d ON b.device_id = d.id LEFT JOIN product_images pi ON pi.product_id = p.id WHERE p.is_available = 1 AND p.is_featured = 1 GROUP BY p.id ORDER BY p.created_at DESC LIMIT 4')->fetchAll(PDO::FETCH_ASSOC);
} catch (Throwable $e) { error_log('[Home products] ' . $e->getMessage()); }
$videoUrl = $homeVideo['fb_video_url'] ?? '';
$videoHost = strtolower((string)parse_url($videoUrl, PHP_URL_HOST));
$videoPlayable = filter_var($videoUrl, FILTER_VALIDATE_URL) && parse_url($videoUrl, PHP_URL_SCHEME) === 'https' && ($videoHost === 'facebook.com' || str_ends_with($videoHost, '.facebook.com'));
$videoCover = $homeMedia($homeVideo['cover_image'] ?? null) ?? asset('img/hero-it.webp');
$heroImage = asset('img/home/cinematic-v2/chapter-01/desktop/sequence/frame-0000.webp');
$page_title = 'Key Soft Italia | Riparazioni, tecnologia e ricondizionati a Ginosa';
$page_description = 'Riparazioni di smartphone e computer, dispositivi nuovi e ricondizionati, assistenza e soluzioni per aziende. Key Soft Italia a Ginosa, in Via Diaz 46.';
$page_keywords = 'riparazioni smartphone Ginosa, assistenza computer Ginosa, ricondizionati Ginosa, Key Soft Italia';
$canonical = url(); $og_image = $heroImage;
$skip_swiper = true; // The home has no carousels.
?>
<!DOCTYPE html><html lang="it"><head>
<?php include __DIR__ . '/includes/head.php'; ?>
<link rel="stylesheet" href="<?= asset_version('css/pages/home-restyle.css') ?>">
<link rel="stylesheet" href="<?= asset_version('css/pages/home-spatial.css') ?>">
<link rel="stylesheet" href="<?= asset_version('css/pages/home-cinematic.css') ?>">
<link rel="stylesheet" href="<?= asset_version('css/pages/home-continuum.css') ?>">
<link rel="stylesheet" href="<?= asset_version('css/pages/home-cinematic-v2.css') ?>">
<script src="<?= asset_version('js/pages/home-boot.js') ?>"></script>
</head><body class="home-restyle">
<div class="welcome-loader" role="status" aria-live="polite"><span class="welcome-spinner" aria-hidden="true"></span><span>Caricamento…</span></div>
<?php include __DIR__ . '/includes/header.php'; ?>
<main id="main-content">
<?php include __DIR__ . '/includes/home-cinematic-v2.php'; ?>
<?php include __DIR__ . '/includes/home-continuum.php'; ?>
</main>
<?php if ($homeVideo && $videoPlayable): ?><dialog class="home-video-dialog" id="home-video-dialog" data-video-url="<?= $esc($videoUrl) ?>" aria-labelledby="home-dialog-title"><div class="home-dialog-header"><h2 id="home-dialog-title"><?= $esc($homeVideo['title']) ?></h2><button type="button" data-home-video-close aria-label="Chiudi il video"><i class="ri-close-line" aria-hidden="true"></i></button></div><div id="home-video-player" class="home-video-player"></div><a href="<?= $esc($videoUrl) ?>" target="_blank" rel="noopener">Apri su Facebook ↗</a></dialog><?php endif; ?>
<?php include __DIR__ . '/includes/footer.php'; ?>
<script src="<?= asset_version('js/pages/home-restyle.js') ?>" defer></script>
<script type="module" src="<?= asset_version('js/pages/home-cinematic-v2.js') ?>"></script>
<script src="<?= asset_version('js/pages/home-continuum.js') ?>" defer></script>
</body></html>
