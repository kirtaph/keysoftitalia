<?php
/**
 * Key Soft Italia - 404 Not Found (layout semplice)
 */
http_response_code(404);
require_once __DIR__ . '/config/config.php';

// Meta
$page_title       = "Ops! Pagina non trovata (404) - Key Soft Italia";
$page_description = "La pagina che cerchi non esiste più o è stata spostata. Torna alla Home o contattaci.";
$noindex          = true;

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

// Pagine interne (adatta gli slug se diversi)
$homeUrl   = $u('/');
$contatti  = $u('contatti.php');
$servizi   = $u('servizi.php');
$blog      = $u('blog.php');          // se non esiste, puoi togliere la card
$preventivo= $u('preventivo.php');    // “Pricing” -> Preventivo
$documenti = $u('faq.php');           // “Documentation” -> FAQ/Documentazione
?>
<!DOCTYPE html>
<html lang="it">
<head>
  <?php include 'includes/head.php'; ?>
  <title><?= $h($page_title); ?></title>
  <meta name="description" content="<?= $h($page_description); ?>">
  <?php if(!empty($noindex)): ?><meta name="robots" content="noindex,follow"><?php endif; ?>
  <meta property="og:title" content="<?= $h($page_title); ?>">
  <meta property="og:description" content="<?= $h($page_description); ?>">
  <meta property="og:type" content="website">
  <meta property="og:url" content="https://<?= $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>">
  <link rel="stylesheet" href="<?= asset_version('css/pages/404.css'); ?>">
</head>
<body>

<?php include 'includes/header.php'; ?>

<main class="ks-404-simple">
  <div class="container">
    <div class="text-center head">
      <img src="assets/img/404.png" width="40%" aria-hidden="true">
      <h1 class="title">Oops, la pagina che cerchi non esiste!</h1>
      <p class="subtitle">
        <strong>Tranquillo:</strong> ti riportiamo sulla strada giusta. Esplora i link qui sotto oppure contattaci.
      </p>
    </div>

    <div class="cards row g-3 g-md-4">
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

      <?php /* Se il blog non esiste ancora, commenta questa card */ ?>
      <div class="col-12 col-md-4">
        <a class="info-card" href="<?= $h($blog); ?>">
          <div class="ic"><i class="ri-pushpin-line"></i></div>
          <div class="txt">
            <div class="label">Blog</div>
            <div class="value">Articoli e novità dal negozio</div>
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
    </div>

    <div class="divider"><span>oppure</span></div>

    <div class="cta text-center">
      <a href="<?= $homeUrl; ?>" class="btn btn-brand">
        <i class="ri-home-4-line me-1"></i> Vai alla Home
      </a>
      <a href="<?= $waLink; ?>" class="btn btn-whatsapp">
        <i class="ri-whatsapp-line me-1"></i> WhatsApp
      </a>
      <a href="<?= $contatti; ?>" class="btn btn-outline">
        <i class="ri-customer-service-2-line me-1"></i> Apri ticket
      </a>
    </div>

    <?php if (defined('ENV') && strtolower((string)constant('ENV'))==='dev'): ?>
      <p class="muted text-center mt-3">
        Debug (DEV): URI <code><?= $h($reqUri); ?></code>
      </p>
    <?php endif; ?>
  </div>
</main>

<?php include 'includes/footer.php'; ?>
<script src="<?= asset('js/main.js'); ?>" defer></script>
</body>
</html>
