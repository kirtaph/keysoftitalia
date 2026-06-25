<?php
/**
 * Key Soft Italia - 404 Not Found (layout semplice)
 */
http_response_code(404);
require_once __DIR__ . '/config/config.php';

// Meta
$page_title       = "Ops! Pagina non trovata (404) - Key Soft Italia";
$page_description = "La pagina che cerchi non esiste più o è stata spostata. Torna alla Home o contattaci.";
$meta_robots      = "noindex, follow";

// Helper
$h = static fn($s)=>htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8');
$u = static fn($p='') => function_exists('url') ? url($p) : '/' . ltrim($p,'/');

// Dati / link
$reqUri   = $_SERVER['REQUEST_URI'] ?? '';
$email    = defined('COMPANY_EMAIL') ? COMPANY_EMAIL : 'info@example.com';
$phone    = defined('COMPANY_PHONE') ? COMPANY_PHONE : '';
$phoneUri = $phone ? 'tel:'.preg_replace('/\s+/', '', $phone) : '#';

$addrLine = trim((defined('COMPANY_ADDRESS')?COMPANY_ADDRESS:'') . ', ' . (defined('COMPANY_CITY')?COMPANY_CITY:''), ', ');
$mapsQ    = $addrLine ? rawurlencode($addrLine) : '';
$mapsUrl  = $mapsQ ? "https://www.google.com/maps/search/?api=1&query={$mapsQ}" : '#';

$waRaw    = defined('COMPANY_WHATSAPP') ? preg_replace('/\D+/', '', COMPANY_WHATSAPP) : '';
$waMsg    = rawurlencode("Ciao! Link non funzionante: {$reqUri}");
$waLink   = $waRaw ? "https://wa.me/{$waRaw}?text={$waMsg}" : $u('contatti.php');

// Schema: page not found (not ComputerStore)
$page_schema = [
    '@context'  => 'https://schema.org',
    '@type'     => 'WebPage',
    'name'      => $page_title,
    'description'=> $page_description,
    'url'       => url(),
];

// Pagine interne (adatta gli slug se diversi)
$homeUrl   = $u('/');
$contatti  = $u('contatti.php');
$servizi   = $u('servizi.php');
$blog      = $u('blog.php');          // se non esiste, puoi togliere la card
$preventivo= $u('preventivo.php');    // “Pricing” -> Preventivo
$documenti = $u('contatti.php#faq');           // “Documentation” -> FAQ/Documentazione
?>
<!DOCTYPE html>
<html lang="it">
<head>
  <?php include 'includes/head.php'; ?>
  <link rel="stylesheet" href="<?= asset_version('css/pages/404.css'); ?>">
</head>
<body>

<?php include 'includes/header.php'; ?>

<section class="hero hero-secondary text-center">
  <div class="container">
    <div class="hero-content text-white">
      <div class="hero-icon">
        <i class="ri-error-warning-line"></i>
      </div>
      <h1 class="hero-title">404</h1>
      <p class="hero-subtitle">Oops, la pagina che cerchi non esiste!<br><strong>Tranquillo:</strong> ti riportiamo sulla strada giusta.</p>
      <div class="hero-cta">
        <a href="<?= $homeUrl; ?>" class="btn btn-primary btn-lg">
          <i class="ri-home-4-line me-1"></i> Torna alla Home
        </a>
        <a href="<?= $contatti; ?>" class="btn btn-outline-light btn-lg ms-2">
          <i class="ri-customer-service-2-line me-1"></i> Contattaci
        </a>
      </div>
    </div>
  </div>
</section>

<section class="ks-404-links section">
  <div class="container">
    <div class="text-center mb-5">
      <h2 class="section-title">Oppure esplora queste risorse</h2>
    </div>

    <div class="cards row g-3 g-md-4 justify-content-center">
      <div class="col-12 col-md-4">
        <a class="info-card" href="mailto:<?= $h($email); ?>">
          <div class="ic"><i class="ri-mail-line"></i></div>
          <div class="txt">
            <div class="label">Email</div>
            <div class="value"><?= $h($email); ?></div>
          </div>
        </a>
      </div>
      <div class="col-12 col-md-4">
        <a class="info-card" href="<?= $h($mapsUrl); ?>" target="_blank" rel="noopener">
          <div class="ic"><i class="ri-map-pin-line"></i></div>
          <div class="txt">
            <div class="label">Indirizzo</div>
            <div class="value"><?= $h($addrLine ?: 'Via Diaz 46, Ginosa'); ?></div>
          </div>
        </a>
      </div>
      <div class="col-12 col-md-4">
        <a class="info-card" href="<?= $h($phoneUri); ?>">
          <div class="ic"><i class="ri-phone-line"></i></div>
          <div class="txt">
            <div class="label">Telefono</div>
            <div class="value"><?= $h($phone ?: '—'); ?></div>
          </div>
        </a>
      </div>
      <div class="col-12 col-md-4">
        <a class="info-card" href="<?= $h($preventivo); ?>">
          <div class="ic"><i class="ri-currency-line"></i></div>
          <div class="txt">
            <div class="label">Preventivo</div>
            <div class="value">Vuoi un prezzo al volo?</div>
          </div>
        </a>
      </div>
      <div class="col-12 col-md-4">
        <a class="info-card" href="<?= $h($documenti); ?>">
          <div class="ic"><i class="ri-file-list-2-line"></i></div>
          <div class="txt">
            <div class="label">Documentazione</div>
            <div class="value">FAQ, condizioni e garanzie</div>
          </div>
        </a>
      </div>
      <div class="col-12 col-md-4">
        <a class="info-card" href="<?= $h($waLink); ?>">
          <div class="ic"><i class="ri-whatsapp-line"></i></div>
          <div class="txt">
            <div class="label">WhatsApp</div>
            <div class="value">Scrivici subito</div>
          </div>
        </a>
      </div>
    </div>

    <?php if (defined('ENV') && strtolower((string)constant('ENV'))==='dev'): ?>
      <p class="text-center text-muted mt-4 small">
        Debug (DEV): URI <code><?= $h($reqUri); ?></code>
      </p>
    <?php endif; ?>
  </div>
</section>

<?php include 'includes/footer.php'; ?>
<script src="<?= asset('js/main.js'); ?>" defer></script>
</body>
</html>
