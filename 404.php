<?php
/**
 * Key Soft Italia — 404 Not Found
 * Questa pagina mantiene struttura Header/Footer come il resto del sito.
 */
http_response_code(404);

// BASE_PATH fallback se non definito
if (!defined('BASE_PATH')) {
    define('BASE_PATH', rtrim(str_replace('\\', '/', __DIR__), '/') . '/');
}

// Config principale (carica helpers, costanti, BASE_URL, ecc.)
require_once BASE_PATH . 'config/config.php';

// --- SEO / Meta
$page_title       = "Pagina non trovata (404) | " . (defined('SITE_NAME') ? SITE_NAME : 'Sito');
$page_description = "La pagina che cerchi potrebbe essere stata spostata o non esiste più.";
$page_keywords    = "404, pagina non trovata, errore";
$noindex          = true; // usato nei partials/head se previsto

// --- Helpers sicuri
$baseUrl = defined('BASE_URL') ? rtrim(BASE_URL, '/') . '/' : '/';
$h = static function ($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); };

// URL helper soft: prova url(), altrimenti concatena BASE_URL
$u = static function ($path='') use ($baseUrl) {
    if (function_exists('url')) return url($path);
    $path = ltrim($path, '/');
    return $baseUrl . $path;
};

// Dati utili
$reqUri   = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
$referrer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';

// Link rapidi
$homeUrl   = $u('/');
$servizi   = $u('servizi.php');
$contatti  = $u('contatti.php');
$shopRic   = $u('ricondizionati.php'); // se esiste
$searchUrl = $u('cerca.php');          // se esiste

// Contatti (fallback)
$companyPhone   = defined('COMPANY_PHONE') ? COMPANY_PHONE : '';
$companyWhats   = defined('COMPANY_WHATSAPP') ? COMPANY_WHATSAPP : '';
$telLink        = $companyPhone ? ('tel:' . preg_replace('/\s+/', '', $companyPhone)) : '#';
$waNumberDigits = $companyWhats ? preg_replace('/\D+/', '', $companyWhats) : '';
$waLink         = $waNumberDigits
    ? ('https://wa.me/' . $waNumberDigits . '?text=' . rawurlencode("Ciao! Ho trovato un link rotto: " . $reqUri))
    : '#';

// Breadcrumbs (se usi un componente, potrai ignorare questo blocco HTML)
$breadcrumbs = [
    ['label' => 'Home', 'url' => $homeUrl],
    ['label' => 'Errore 404', 'url' => '']
];

// Percorsi partials
$headPath    = BASE_PATH . 'includes/head.php';
$headerPath  = BASE_PATH . 'includes/header.php';
$footerPath  = BASE_PATH . 'includes/footer.php';
$scriptsPath = BASE_PATH . 'includes/scripts.php';
?>
<!DOCTYPE html>
<html lang="it">
<head>
<?php if (is_file($headPath)): ?>
  <?php include $headPath; ?>
<?php else: ?>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= $h($page_title); ?></title>
  <meta name="description" content="<?= $h($page_description); ?>">
  <?php if (!empty($noindex)): ?>
    <meta name="robots" content="noindex, follow">
  <?php endif; ?>
  <!-- Fallback CSS minimo se non usi il partial -->
  <style>
    .ks-404 { padding: 80px 0 48px; text-align:center; }
    .ks-404 h1 { font-size: clamp(48px, 6vw, 88px); line-height: 1; margin-bottom: 8px; font-weight: 800; letter-spacing: -0.02em; }
    .ks-404 p.lead { font-size: clamp(16px, 2.2vw, 20px); color: #555; margin-bottom: 28px; }
    .ks-404 .btn { margin: 6px 6px; padding: 12px 18px; border-radius: 12px; text-decoration:none; display:inline-block; }
    .btn-primary{ background:#0d6efd; color:#fff; }
    .btn-outline{ border:1px solid #0d6efd; color:#0d6efd; background:#fff; }
    .ks-404 .search { max-width: 680px; margin: 18px auto 8px; display:flex; gap:8px; }
    .ks-404 input[type="search"]{ flex:1; padding:12px 14px; border:1px solid #ddd; border-radius:12px; }
    .ks-404 .grid { display:grid; grid-template-columns: repeat( auto-fit, minmax(220px, 1fr) ); gap:14px; margin-top: 22px; }
    .ks-card { border:1px solid #eee; border-radius:16px; padding:18px; text-align:left; transition:.2s; background:#fff; }
    .ks-card:hover { transform: translateY(-2px); box-shadow:0 10px 24px rgba(0,0,0,.06); }
    .ks-card .title { font-weight:700; margin-bottom:6px; }
    .ks-crumbs { font-size: 14px; margin: 10px auto 0; text-align:center; color:#666;}
    .ks-crumbs a { color: inherit; text-decoration: none; }
    .ks-crumbs span { opacity:.8; }
    .container { width: min(1100px, 92%); margin: 0 auto; }
    .muted { color:#777; font-size:13px; }
  </style>
<?php endif; ?>
</head>
<body>

<?php if (is_file($headerPath)) include $headerPath; ?>

<main class="ks-404">
  <div class="container">
    <!-- Breadcrumbs leggeri (se non usi i tuoi) -->
    <nav class="ks-crumbs" aria-label="breadcrumb">
      <?php foreach ($breadcrumbs as $i => $b): ?>
        <?php if (!empty($b['url']) && $i < count($breadcrumbs)-1): ?>
          <a href="<?= $h($b['url']); ?>"><?= $h($b['label']); ?></a> &nbsp;/&nbsp;
        <?php else: ?>
          <span><?= $h($b['label']); ?></span>
        <?php endif; ?>
      <?php endforeach; ?>
    </nav>

    <h1>404</h1>
    <p class="lead">Ops! La pagina che cerchi non esiste più, è stata spostata oppure l’URL è errato.</p>

    <!-- Ricerca -->
    <form class="search" action="<?= $h($searchUrl); ?>" method="get" role="search">
      <input type="search" name="q" placeholder="Cerca nel sito (modello, servizio, contatto…)" aria-label="Cerca">
      <button class="btn btn-primary" type="submit">Cerca</button>
    </form>

    <!-- CTA principali -->
    <div style="margin-top:14px;">
      <a class="btn btn-outline" href="<?= $h($homeUrl); ?>">Torna alla Home</a>
      <a class="btn btn-outline" href="<?= $h($servizi); ?>">Vai ai Servizi</a>
      <a class="btn btn-outline" href="<?= $h($shopRic); ?>">Ricondizionati</a>
      <a class="btn btn-primary" href="<?= $h($contatti); ?>">Contattaci</a>
    </div>

    <!-- Pannelli utili -->
    <section class="grid" style="margin-top:26px;">
      <a class="ks-card" href="<?= $h($contatti); ?>">
        <div class="title">Assistenza & Riparazioni</div>
        <div class="text">Prenota un intervento, richiedi diagnosi o un ritiro a domicilio.</div>
      </a>
      <a class="ks-card" href="<?= $h($shopRic); ?>">
        <div class="title">Dispositivi Ricondizionati</div>
        <div class="text">iPhone, Samsung, tablet e notebook garantiti e testati.</div>
      </a>
      <a class="ks-card" href="<?= $h($servizi); ?>">
        <div class="title">Tutti i Servizi</div>
        <div class="text">Reti, videosorveglianza, consulenza IT, protezione schermi MyShape.</div>
      </a>
      <a class="ks-card" href="<?= $h($waLink); ?>">
        <div class="title">Segnala link rotto</div>
        <div class="text">Scrivici su WhatsApp: ci aiuti a sistemare più in fretta.</div>
      </a>
    </section>

  </div>
</main>

<?php if (is_file($footerPath)) include $footerPath; ?>

<?php if (is_file($scriptsPath)) include $scriptsPath; ?>
</body>
</html>
