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
    $stmt = $pdo->prepare('SELECT title, slug, end_date FROM flyers WHERE status = 1 AND show_home = 1 AND start_date <= :start AND end_date >= :end ORDER BY start_date DESC LIMIT 1');
    $today = (new DateTimeImmutable('now', new DateTimeZone(KS_TZ)))->format('Y-m-d');
    $stmt->execute([':start' => $today, ':end' => $today]); $featured_flyer = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
} catch (Throwable $e) { error_log('[Home flyer] ' . $e->getMessage()); }
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
$heroImage = asset('img/home/cinematic-v1/poster.webp');
$page_title = 'Key Soft Italia | Riparazioni, tecnologia e ricondizionati a Ginosa';
$page_description = 'Riparazioni di smartphone e computer, dispositivi nuovi e ricondizionati, assistenza e soluzioni per aziende. Key Soft Italia a Ginosa, in Via Diaz 46.';
$page_keywords = 'riparazioni smartphone Ginosa, assistenza computer Ginosa, ricondizionati Ginosa, Key Soft Italia';
$canonical = url(); $og_image = $heroImage;
?>
<!DOCTYPE html><html lang="it"><head>
<?php include __DIR__ . '/includes/head.php'; ?>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= asset_version('css/pages/home-restyle.css') ?>">
<link rel="stylesheet" href="<?= asset_version('css/pages/home-spatial.css') ?>">
<link rel="stylesheet" href="<?= asset_version('css/pages/home-cinematic.css') ?>">
</head><body class="home-restyle">
<?php include __DIR__ . '/includes/header.php'; ?>
<main id="main-content">
<section id="home-cinematic" class="cinematic-story" aria-label="Dal guasto, a una nuova possibilità" data-manifest="<?= asset_version('img/home/cinematic-v1/manifest.json') ?>">
 <div class="cinematic-stage">
  <img class="cinematic-poster" src="<?= $esc($heroImage) ?>" alt="Uno smartphone con vetro incrinato, protagonista di un film illustrativo sui nostri servizi" width="1280" height="720" fetchpriority="high">
  <canvas class="cinematic-canvas" aria-hidden="true"></canvas>
  <div class="cinematic-content home-shell">
   <div class="cinematic-chapter" data-chapter="opening">
    <p class="home-location"><span aria-hidden="true"></span> Key Soft Italia · Ginosa, dal 2004</p>
    <h1 id="home-title">La tua tecnologia merita <span>un'altra possibilità.</span></h1>
    <p>Smartphone e computer: riparazioni, assistenza e dispositivi ricondizionati. A Ginosa, vicino a te.</p>
    <div class="home-actions"><a class="home-button home-button-dark" href="<?= url('prenota-riparazione.php') ?>">Richiedi una riparazione <i class="ri-arrow-right-up-line" aria-hidden="true"></i></a><a class="home-button home-button-outline" href="<?= url('prodotti.php') ?>">Esplora i prodotti <i class="ri-arrow-right-line" aria-hidden="true"></i></a></div>
   </div>
   <div class="cinematic-chapter" data-chapter="care">
    <p class="home-location">Il laboratorio</p><h2>Dentro ogni soluzione, <span>c'è cura.</span></h2>
    <p>Diagnosi e assistenza per trovare la strada giusta per il tuo dispositivo. Con competenza e qualcuno che ti ascolta.</p>
    <a class="home-button home-button-dark" href="<?= url('servizi/riparazioni.php') ?>">Scopri il laboratorio <i class="ri-arrow-right-up-line" aria-hidden="true"></i></a>
   </div>
   <div class="cinematic-chapter" data-chapter="restart">
    <p class="home-location">Una nuova prospettiva</p><h2>Pronto per quello <span>che viene dopo.</span></h2>
    <p>Ripara il tuo dispositivo o scegli il prossimo, nuovo o ricondizionato. Ti aiutiamo a trovare quello giusto per te.</p>
    <a class="home-button home-button-dark" href="<?= url('prodotti.php') ?>">Esplora il catalogo <i class="ri-arrow-right-up-line" aria-hidden="true"></i></a>
   </div>
   <p class="cinematic-caption">Film illustrativo: un dispositivo, dal guasto al ritorno in uso.</p>
  </div>
  <div class="cinematic-footer home-shell"><div class="cinematic-note"><div class="cinematic-meter" role="progressbar" aria-label="Avanzamento del racconto" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"></div><span>Scorri per una nuova possibilità</span></div><button class="cinematic-skip" type="button" hidden>Vai ai servizi <i class="ri-arrow-down-line" aria-hidden="true"></i></button></div>
 </div>
</section>
<div class="home-assurance" aria-label="I nostri servizi"><div class="home-shell home-assurance-inner"><span><i class="ri-tools-line" aria-hidden="true"></i> Un laboratorio al tuo servizio</span><span><i class="ri-shield-check-line" aria-hidden="true"></i> Ricondizionati con garanzia</span><span><i class="ri-chat-smile-2-line" aria-hidden="true"></i> Persone, prima di tutto</span><a href="<?= url('contatti.php') ?>"><i class="ri-map-pin-line" aria-hidden="true"></i> Via Diaz 46, Ginosa ↗</a></div></div>
<section id="home-services" class="home-section home-shell" aria-labelledby="home-services-title">
 <div class="home-section-heading"><h2 id="home-services-title">Un problema?<br>Facciamolo diventare<br><span>una soluzione.</span></h2><p>Uno schermo rotto, un computer lento, un'idea da realizzare. Da noi trovi competenza tecnica e qualcuno che ti ascolta davvero.</p></div>
 <div class="home-service-layout">
  <a class="home-repair-panel" href="<?= url('servizi/riparazioni.php') ?>"><img src="<?= asset('img/hero-repair.webp') ?>" alt="Strumenti di precisione per la riparazione di dispositivi elettronici" width="1024" height="541" loading="lazy" decoding="async"><div class="home-repair-copy"><span class="home-service-tag">Il laboratorio</span><h3>Rimettiamo in moto<br>la tua giornata.</h3><p>Smartphone, tablet e computer.<br>Diagnosi, riparazioni e assistenza.</p><span class="home-inline-link">Scopri le riparazioni ↗</span></div></a>
  <div class="home-service-links"><a class="home-service-link" href="<?= url('prodotti.php') ?>"><span class="home-service-tag">Il negozio</span><h3>Il dispositivo giusto.<br>Per te.</h3><p>Nuovo o ricondizionato, scelto con cura. Ti aiutiamo a trovare quello che ti serve.</p><span class="home-round-arrow" aria-hidden="true">↗</span></a><a class="home-service-link home-service-link-business" href="<?= url('servizi/sviluppo-web.php') ?>"><span class="home-service-tag">Per la tua attività</span><h3>Le tue idee.<br>La nostra tecnologia.</h3><p>Siti web, software e consulenza IT. Soluzioni che crescono con il tuo lavoro.</p><span class="home-round-arrow" aria-hidden="true">↗</span></a></div>
 </div><div class="home-extra-services"><span>E poi, molto altro.</span><a href="<?= url('servizi/telefonia.php') ?>">Telefonia ↗</a><a href="<?= url('servizi/forniture.php') ?>">Luce e gas ↗</a><a href="<?= url('servizi/liberty-commerce.php') ?>">Gestionali ↗</a><a href="<?= url('servizi.php') ?>">Tutti i servizi ↗</a></div>
</section>
<section class="home-catalogue" aria-labelledby="home-catalogue-title"><div class="home-shell">
 <div class="home-catalogue-intro"><div class="home-catalogue-photo"><img src="<?= asset('img/hero-ricondizionati.webp') ?>" alt="Una selezione di smartphone, tablet e notebook" width="1024" height="541" loading="lazy" decoding="async"><span>Nuove possibilità. Anche ricondizionate.</span></div><div class="home-catalogue-copy"><span class="home-service-tag">Scegli bene. Usa a lungo.</span><h2 id="home-catalogue-title">Il tuo prossimo<br>colpo di <span>tech.</span></h2><p>Dispositivi nuovi e ricondizionati, consigli chiari e assistenza anche dopo l'acquisto. La tecnologia giusta è quella che funziona per te.</p><a class="home-button home-button-light" href="<?= url('prodotti.php') ?>">Esplora il catalogo ↗</a><a class="home-trade-link" href="<?= url('valuta-usato.php') ?>">Hai un dispositivo da valutare? ↗</a></div></div>
 <div class="home-products-heading"><h3>Adesso in vetrina</h3><span>La selezione del negozio, aggiornata per te.</span></div><div class="home-products">
 <?php foreach ($homeProducts as $product): $photo = $homeMedia($product['image_path']); ?>
  <a class="home-product" href="<?= url('prodotti.php?sku=' . rawurlencode($product['sku'])) ?>"><div class="home-product-image"><?php if ($photo): ?><img src="<?= $esc($photo) ?>" alt="<?= $esc($product['title']) ?>" width="480" height="480" loading="lazy" decoding="async"><?php else: ?><span class="home-product-no-photo"><i class="ri-smartphone-line" aria-hidden="true"></i>Foto in aggiornamento</span><?php endif; ?><?php if ($product['grade']): ?><span class="home-product-grade">Grado <?= $esc($product['grade']) ?></span><?php endif; ?></div><div class="home-product-info"><span><?= $esc($product['brand']) ?></span><h4><?= $esc($product['title']) ?></h4><div class="home-product-price"><strong>€ <?= $esc(number_format((float)$product['price_eur'], 2, ',', '.')) ?></strong><i class="ri-arrow-right-up-line" aria-hidden="true"></i></div></div></a>
 <?php endforeach; ?></div>
 <?php if (!$homeProducts): ?><p class="home-catalogue-empty">Scopri i dispositivi disponibili nel <a href="<?= url('prodotti.php') ?>">catalogo</a>.</p><?php endif; ?>
</div></section>
<?php if ($homeVideo && $videoPlayable): ?>
<section class="home-video-section home-shell" aria-labelledby="home-video-title"><div><h2 id="home-video-title">La tecnologia,<br><span>da vicino.</span></h2><p>Novità, dispositivi e consigli pratici. Ti mostriamo le cose con parole semplici e un po' di curiosità.</p><a class="home-inline-link" href="<?= url('video.php') ?>">Tutti i nostri video ↗</a></div><div><button class="home-video-poster" type="button" data-home-video aria-label="Guarda il video: <?= $esc($homeVideo['title']) ?>"><img src="<?= $esc($videoCover) ?>" alt="" width="1024" height="541" loading="lazy"><span class="home-play" aria-hidden="true"><i class="ri-play-fill"></i></span></button><div class="home-video-caption"><h3><?= $esc($homeVideo['title']) ?></h3><span>Video su Facebook · riproduzione su richiesta</span></div><noscript><a href="<?= $esc($videoUrl) ?>" target="_blank" rel="noopener">Guarda il video su Facebook</a></noscript></div></section>
<?php endif; ?>
<section class="home-story" aria-labelledby="home-story-title"><div class="home-shell home-story-layout"><div class="home-story-photo"><img src="<?= asset('img/about-story.webp') ?>" alt="Una fotografia dalla storia di Key Soft Italia" width="800" height="600" loading="lazy"><span>Da qui è iniziato tutto.</span></div><div class="home-story-copy"><p class="home-location">Una storia di persone, dal 2004.</p><h2 id="home-story-title">La tecnologia cambia.<br>La fiducia <span>resta.</span></h2><p>Siamo Key Soft Italia. A Ginosa aiutiamo persone e attività a vivere meglio la tecnologia: ascoltiamo, consigliamo, ripariamo.</p><p>Dietro ogni dispositivo c'è qualcuno. E dietro il nostro banco ci siamo noi.</p><a class="home-inline-link" href="<?= url('chi-siamo.php') ?>">Conosci il team ↗</a></div></div></section>
<section class="home-faq home-section home-shell" aria-labelledby="home-faq-title"><div class="home-section-heading"><h2 id="home-faq-title">Prima di passare<br>da noi.</h2><p>Qualche risposta per partire con le idee chiare.</p></div><div class="home-faq-list"><details><summary>Come posso richiedere una riparazione?</summary><p>Puoi <a href="<?= url('prenota-riparazione.php') ?>">prenotare online</a> o passare in negozio in Via Diaz 46, a Ginosa.</p></details><details><summary>Posso conoscere il costo prima di procedere?</summary><p>Puoi <a href="<?= url('preventivo.php') ?>">richiedere un preventivo</a>. Valutiamo il dispositivo e ti aiutiamo a capire come procedere.</p></details><details><summary>Posso far valutare il mio dispositivo usato?</summary><p>Sì, parti dalla <a href="<?= url('valuta-usato.php') ?>">valutazione dell'usato</a> oppure contattaci.</p></details></div></section>
<?php if ($featured_flyer): ?><section class="home-flyer home-shell"><div><span class="home-service-tag">Le offerte del momento</span><h2><?= $esc($featured_flyer['title']) ?></h2></div><a class="home-button home-button-dark" href="<?= url('volantini.php?flyer=' . rawurlencode($featured_flyer['slug'])) ?>">Sfoglia il volantino ↗</a></section><?php endif; ?>
<section class="home-contact" aria-labelledby="home-contact-title"><div class="home-shell"><span class="home-service-tag">Un messaggio. Una visita. Una soluzione.</span><h2 id="home-contact-title">Ci vediamo<br>in negozio?</h2><div class="home-contact-bottom"><p>Via Diaz, 46 · Ginosa<br>Oppure scrivici. Partiamo da ciò che ti serve.</p><div class="home-actions"><a class="home-button home-button-dark" href="<?= $esc(whatsapp_link('Ciao Key Soft Italia! Vorrei parlare con voi.', ['utm_campaign' => 'home-contact'])) ?>" target="_blank" rel="noopener">Scrivici su WhatsApp <i class="ri-whatsapp-line" aria-hidden="true"></i></a><a href="https://maps.google.com/maps?q=Via+Diaz+46+Ginosa+TA" target="_blank" rel="noopener">Come raggiungerci ↗</a><a href="tel:0998293794">099 829 3794</a></div></div></div></section>
</main>
<?php if ($homeVideo && $videoPlayable): ?><dialog class="home-video-dialog" id="home-video-dialog" data-video-url="<?= $esc($videoUrl) ?>" aria-labelledby="home-dialog-title"><div class="home-dialog-header"><h2 id="home-dialog-title"><?= $esc($homeVideo['title']) ?></h2><button type="button" data-home-video-close aria-label="Chiudi il video"><i class="ri-close-line" aria-hidden="true"></i></button></div><div id="home-video-player" class="home-video-player"></div><a href="<?= $esc($videoUrl) ?>" target="_blank" rel="noopener">Apri su Facebook ↗</a></dialog><?php endif; ?>
<?php include __DIR__ . '/includes/footer.php'; ?>
<script src="<?= asset_version('js/pages/home-restyle.js') ?>" defer></script>
<script type="module" src="<?= asset_version('js/pages/home-cinematic.js') ?>"></script>
</body></html>
