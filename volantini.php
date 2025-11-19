<?php
/**
 * Key Soft Italia - Volantini & Offerte
 * Pagina pubblica per visualizzare i volantini
 */

if (!defined('BASE_PATH')) {
    define('BASE_PATH', __DIR__ . '/');
}

require_once BASE_PATH . 'config/config.php';

/**
 * Genera un link WhatsApp precompilato.
 * @param string $phone Numero di telefono (solo numeri, con prefisso internazionale, es: 393401234567)
 * @param string $message Messaggio opzionale
 * @return string URL WhatsApp
 */
function whatsappLink($phone, $message = '') {
    $phone = preg_replace('/\D+/', '', $phone);
    $url = 'https://wa.me/' . $phone;
    if ($message) {
        $url .= '?text=' . rawurlencode($message);
    }
    return $url;
}

// SEO Meta
$page_title       = "Volantini & Offerte - Key Soft Italia | Promo attive e archivio";
$page_description = "Scopri i volantini e le offerte di Key Soft Italia: promozioni attive, volantini in arrivo e archivio delle promo passate.";
$page_keywords    = "volantini key soft italia, offerte tecnologia ginosa, promo smartphone ricondizionati, sconti elettronica";

// Breadcrumbs
$breadcrumbs = [
    ['label' => 'Volantini & Offerte', 'url' => 'volantini.php']
];

// Canonical URL
$is_https      = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443);
$canonical_url = ($is_https ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

// JSON-LD (lista volantini pubblicati - opzionale, ma buono per SEO)
$flyer_items = [];
try {
    $stmt = $pdo->prepare("
        SELECT title, slug, description, start_date, end_date
        FROM flyers
        WHERE status = 1
        ORDER BY start_date DESC
        LIMIT 50
    ");
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $pos = 0;
    foreach ($rows as $row) {
        $pos++;
        $flyer_items[] = [
            '@type'    => 'ListItem',
            'position' => $pos,
            'item'     => [
                '@type'        => 'CreativeWork',
                'name'         => $row['title'],
                'description'  => $row['description'] ?: '',
                'url'          => url('volantino.php?slug=' . urlencode($row['slug'])),
                'datePublished'=> $row['start_date'],
                'expires'      => $row['end_date'],
            ]
        ];
    }
} catch (Throwable $e) {
    $flyer_items = [];
}

$ld_flyers = [
    '@context'        => 'https://schema.org',
    '@type'           => 'ItemList',
    'name'            => 'Volantini e offerte Key Soft Italia',
    'itemListElement' => $flyer_items
];
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <?php include 'includes/head.php'; ?>
    <title><?php echo htmlspecialchars($page_title); ?></title>

    <meta name="description" content="<?php echo htmlspecialchars($page_description); ?>">
    <meta name="keywords" content="<?php echo htmlspecialchars($page_keywords); ?>">

    <link rel="canonical" href="<?php echo htmlspecialchars($canonical_url); ?>">

    <meta property="og:title" content="<?php echo htmlspecialchars($page_title); ?>">
    <meta property="og:description" content="<?php echo htmlspecialchars($page_description); ?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo htmlspecialchars($canonical_url); ?>">
    <meta property="og:image" content="<?php echo asset('images/og-image.jpg'); ?>">

    <link rel="stylesheet" href="<?php echo asset_version('css/pages/volantini.css'); ?>">
</head>
<body data-aos-easing="ease-in-out" data-aos-duration="800" data-aos-once="true">
<?php include 'includes/header.php'; ?>

<!-- HERO -->
<section class="hero hero-secondary hero-flyers text-center">
  <div class="hero-pattern"></div>
  <div class="container position-relative z-2" data-aos="fade-up">
    <div class="hero-icon mb-3" data-aos="zoom-in">
      <i class="ri-price-tag-3-line"></i>
    </div>
    <h1 class="hero-title text-white" data-aos="fade-up" data-aos-delay="100">
      Volantini &amp; <span class="text-gradient">Offerte</span>
    </h1>
    <p class="hero-subtitle" data-aos="fade-up" data-aos-delay="200">
      Qui trovi le <strong>promo attive</strong>, i volantini in arrivo e l’<strong>archivio</strong> delle offerte passate
    </p>
    <div class="hero-cta" data-aos="fade-up" data-aos-delay="300">
      <a href="#flyers-section" class="btn btn-primary btn-lg smooth-scroll" aria-label="Vai ai volantini">
        <i class="ri-arrow-down-line me-1"></i> Guarda i Volantini
      </a>
    </div>
    <div class="hero-breadcrumb mt-4" data-aos="fade-up" data-aos-delay="400">
      <?= generate_breadcrumbs($breadcrumbs); ?>
    </div>
  </div>
</section>

<!-- FLYERS GRID -->
<section id="flyers-section" class="section section-flyers" aria-labelledby="tutti-volantini">
  <div class="container">
    <div class="section-header text-center">
      <h2 id="tutti-volantini" class="section-title" data-aos="fade-up">
        Volantini e promozioni
      </h2>
      <p class="section-subtitle" data-aos="fade-up" data-aos-delay="100">
        I volantini vengono aggiornati periodicamente in base alle promozioni disponibili.
      </p>
    </div>


    <!-- Meta / messaggi -->
    <div class="flyers-meta d-flex justify-content-between align-items-center flex-wrap mt-3">
      <p class="mb-1 small text-muted">
        <span id="flyersCount">0</span> volantini trovati
      </p>
    </div>

    <div id="flyersMessage" class="flyers-message text-center mt-3" aria-live="polite"></div>

    <!-- Grid -->
    <div id="flyersGrid" class="row g-4 mt-2" data-aos="fade-up" data-aos-delay="150">
      <!-- Popolato via JS -->
    </div>
  </div>
</section>

<!-- CTA FINALE -->
<section class="section section-cta section-cta-flyers" aria-labelledby="cta-volantini">
  <div class="container">
    <div class="cta-flyers-box text-center" data-aos="zoom-in">
      <h2 id="cta-volantini" class="cta-title">
        Hai visto un’offerta che ti interessa?
      </h2>
      <p class="cta-subtitle">
        Inviaci uno screenshot del volantino o del prodotto:
        ti aiutiamo a scegliere il dispositivo giusto per te.
      </p>

      <div class="cta-buttons">
        <!-- WhatsApp -->
        <a href="<?php echo whatsappLink(COMPANY_WHATSAPP, 'Ciao! Ho visto un volantino sul sito e vorrei maggiori informazioni.'); ?>"
           class="btn btn-whatsapp btn-lg">
          <i class="ri-whatsapp-line" aria-hidden="true"></i>
          Scrivici su WhatsApp
        </a>

        <!-- Chiamata -->
        <a href="tel:<?php echo preg_replace('/\s+/', '', COMPANY_PHONE); ?>"
           class="btn btn-outline-primary btn-lg">
          <i class="ri-phone-line" aria-hidden="true"></i>
          Chiamaci in negozio
        </a>

        <!-- Come raggiungerci -->
        <a href="<?php echo url('contatti.php'); ?>"
           class="btn btn-outline-primary btn-lg">
          <i class="ri-map-pin-line" aria-hidden="true"></i>
          Come raggiungerci
        </a>
      </div>

      <p class="cta-flyers-note">
        Rispondiamo durante gli orari di apertura del negozio.  
        Per urgenze, il canale più rapido è WhatsApp.
      </p>
    </div>
  </div>
</section>

<!-- RECONDITIONED SECTION -->
<section class="section section-recond" role="region" aria-label="I nostri prodotti in evidenza" data-aos="fade-up" data-aos-duration="1000" data-aos-once="true">
  <div class="container">
    <div class="section-header text-center" data-aos="zoom-in" data-aos-duration="800" data-aos-delay="100">
      <h2 class="section-title">Altre offerte in evidenza</h2>
      <p class="section-subtitle">Smartphone e altri dispositivi in offerta speciale</p>
    </div>

<?php
$recond_id = 'recond-flyer';     // id univoco per la pagina
$recond_limit = 8;              // quanti prodotti
$recond_featured = 1;           // solo vetrina per home
$recond_title = '';
include __DIR__.'/includes/recond_swiper.php';
?>

    <!-- CTA catalogo completo -->
    <div class="text-center mt-5" data-aos="fade-up" data-aos-duration="600" data-aos-delay="800">
      <a href="<?php echo url('prodotti.php'); ?>" class="btn btn-primary" aria-label="Scopri tutti i nostri ricondizionati">
        Scopri tutti i nostri prodotti <i class="ri-arrow-right-line"></i>
      </a>
    </div>

    <noscript>
      <p class="text-center mt-3">Attiva JavaScript per vedere i prodotti in evidenza.</p>
    </noscript>
  </div>
</section>

<!-- MODAL VIEWER PDF -->
<div class="modal fade" id="flyerViewerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-fullscreen-lg-down modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="flyerViewerTitle">Sfoglia il volantino</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Chiudi"></button>
            </div>
            <div class="modal-body p-0">
                <iframe id="flyerPdfIframe" src="" style="width: 100%; height: 100%; border: none;"></iframe>
            </div>
        </div>
    </div>
</div>

<!-- JSON-LD -->
<?php if (!empty($flyer_items)): ?>
<script type="application/ld+json">
<?php echo json_encode($ld_flyers, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT); ?>
</script>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>

<!-- JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo asset('js/main.js'); ?>"></script>
<script src="https://unpkg.com/aos@next/dist/aos.js"></script>
<script>AOS.init();</script>
<script>
(function(){
  const FLYERS_ENDPOINT = "<?php echo asset('ajax/get_flyers.php'); ?>";

  const grid    = document.getElementById('flyersGrid');
  const msgBox  = document.getElementById('flyersMessage');
  const counter = document.getElementById('flyersCount');

  if (!grid) return;

  const params      = new URLSearchParams(window.location.search);
  const slugParam   = params.get('flyer'); // slug passato da home

  // ---------- UI helpers ----------
  function statusEmptyMessage(){
    return 'Al momento non ci sono volantini disponibili. Passa in negozio per scoprire le offerte!';
  }

  function clearGrid(){
    grid.innerHTML = '';
  }

  function showLoading(){
    msgBox.textContent = 'Caricamento volantini...';
    msgBox.classList.add('is-loading');
    clearGrid();
  }

  function hideLoading(){
    msgBox.classList.remove('is-loading');
  }

  function formatDate(str){
    if (!str) return '';
    const parts = str.split('-'); // YYYY-MM-DD
    if (parts.length !== 3) return str;
    return parts[2] + '/' + parts[1] + '/' + parts[0];
  }

  function formatPeriod(start, end){
    if (!start && !end) return '';
    if (!end) return 'Valido dal ' + formatDate(start);
    return 'Valido dal ' + formatDate(start) + ' al ' + formatDate(end);
  }

  // ---------- FETCH ----------
  function fetchFlyers(){
    const url = new URL(FLYERS_ENDPOINT, window.location.origin);

    return fetch(url.toString(), {
      headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(res => {
      if (!res.ok) throw new Error('HTTP ' + res.status);
      return res.json();
    });
  }

  // ---------- RENDER ----------
 function renderFlyers(flyers){
  clearGrid();
  counter.textContent = flyers.length;

  if (!flyers.length){
    msgBox.textContent = statusEmptyMessage();
    return;
  }

  msgBox.textContent = '';

  let delay = 0;

  flyers.forEach(f => {
    delay += 80;

    // capire se il volantino è scaduto/archiviato
    const isExpired =
      status === 'archived' ||
      (f.status_code && f.status_code === 'archived') ||
      (typeof f.is_expired !== 'undefined' && !!f.is_expired);

    const col = document.createElement('div');
    col.className = 'col-md-6 col-lg-4';
    col.setAttribute('data-aos', 'fade-up');
    col.setAttribute('data-aos-delay', String(delay));

    const card = document.createElement('article');
    card.className = 'flyer-card h-100';
    if (isExpired) {
      card.classList.add('flyer-card-expired');
    }

    // HEADER
    const header = document.createElement('header');
    header.className = 'flyer-card-header';

    const coverWrap = document.createElement('div');
    coverWrap.className = 'flyer-cover';

    if (f.cover_image_url){
      const img = document.createElement('img');
      img.src = f.cover_image_url;
      img.alt = 'Copertina volantino ' + f.title;
      img.loading = 'lazy';
      coverWrap.appendChild(img);
    } else {
      const placeholder = document.createElement('div');
      placeholder.className = 'flyer-cover-placeholder';
      placeholder.innerHTML = '<i class="ri-price-tag-3-line"></i>';
      coverWrap.appendChild(placeholder);
    }

    const meta = document.createElement('div');
    meta.className = 'flyer-meta';

    const titleEl = document.createElement('h3');
    titleEl.className = 'flyer-title';
    titleEl.textContent = f.title;

    const periodEl = document.createElement('p');
    periodEl.className = 'flyer-period';
    periodEl.textContent = formatPeriod(f.start_date, f.end_date);

    const badgesWrap = document.createElement('div');
    badgesWrap.className = 'flyer-badges';

    const statusBadge = document.createElement('span');
    statusBadge.className = 'badge flyer-badge flyer-badge-' + (f.status_code || status || 'current');
    statusBadge.textContent = f.status_label || '';
    badgesWrap.appendChild(statusBadge);

    if (f.show_home && !isExpired){
      const homeBadge = document.createElement('span');
      homeBadge.className = 'badge flyer-badge-highlight';
      homeBadge.textContent = 'In evidenza';
      badgesWrap.appendChild(homeBadge);
    }

    meta.appendChild(titleEl);
    meta.appendChild(periodEl);
    meta.appendChild(badgesWrap);

    header.appendChild(coverWrap);
    header.appendChild(meta);

    // BODY
    const body = document.createElement('div');
    body.className = 'flyer-card-body';

    if (f.description){
      const desc = document.createElement('p');
      desc.className = 'flyer-description';
      desc.textContent = f.description;
      body.appendChild(desc);
    }

    // ACTIONS
    const actions = document.createElement('div');
    actions.className = 'flyer-card-actions';

    if (isExpired) {
      // ✅ NESSUN ELEMENTO CLICCABILE
      const note = document.createElement('p');
      note.className = 'flyer-note small text-muted mb-0';
      note.textContent = 'Volantino scaduto • offerte non più valide';
      actions.appendChild(note);
    } else if (f.pdf_url) {
      const isMobile = window.innerWidth < 992;
      if (isMobile) {
        const btnView = document.createElement('a');
        btnView.href = f.pdf_url;
        btnView.target = '_blank';
        btnView.rel = 'noopener';
        btnView.className = 'btn btn-primary btn-sm';
        btnView.innerHTML = '<i class="ri-eye-line me-1" aria-hidden="true"></i> Sfoglia online';
        actions.appendChild(btnView);
      } else {
        const btnView = document.createElement('button');
        btnView.type = 'button';
        btnView.className = 'btn btn-primary btn-sm';
        btnView.setAttribute('data-bs-toggle', 'modal');
        btnView.setAttribute('data-bs-target', '#flyerViewerModal');
        btnView.dataset.pdf   = f.pdf_url;
        btnView.dataset.title = f.title;
        btnView.dataset.slug  = f.slug; // usato per l’apertura da ?flyer=slug
        btnView.innerHTML = '<i class="ri-eye-line me-1" aria-hidden="true"></i> Sfoglia online';
        actions.appendChild(btnView);
      }

      const linkDownload = document.createElement('a');
      linkDownload.href   = f.pdf_url;
      linkDownload.target = '_blank';
      linkDownload.rel    = 'noopener';
      linkDownload.className = 'btn btn-outline-secondary btn-sm';
      linkDownload.innerHTML = '<i class="ri-download-2-line me-1" aria-hidden="true"></i> Scarica PDF';
      actions.appendChild(linkDownload);
    } else {
      const note = document.createElement('p');
      note.className = 'flyer-note small text-muted mb-0';
      note.textContent = 'PDF non disponibile per questo volantino.';
      actions.appendChild(note);
    }

    body.appendChild(actions);

    card.appendChild(header);
    card.appendChild(body);

    col.appendChild(card);
    grid.appendChild(col);
  });
}

  // ---------- LOAD + opzionale apertura slug ----------
  function loadFlyers(){
    showLoading();

    return fetchFlyers()
      .then(data => {
        hideLoading();

        if (!data || !data.ok){
          msgBox.textContent = 'Si è verificato un errore durante il caricamento dei volantini.';
          counter.textContent = '0';
          clearGrid();
          return;
        }

        const flyers = data.flyers || [];
        renderFlyers(flyers);

        if (slugParam){
          const isMobile = window.innerWidth < 992;
          if (isMobile) {
            const flyer = flyers.find(f => f.slug === slugParam);
            if (flyer && flyer.pdf_url) {
              window.location.href = flyer.pdf_url;
            }
          } else {
            const btn = grid.querySelector('button[data-slug="' + CSS.escape(slugParam) + '"]');
            if (btn){
              btn.click();
            }
          }
        }
      })
      .catch(() => {
        hideLoading();
        msgBox.textContent = 'Si è verificato un problema di connessione. Riprova tra qualche istante.';
        counter.textContent = '0';
        clearGrid();
      });
  }

  // ---------- Init ----------
  loadFlyers();
})();
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const viewerModal = document.getElementById('flyerViewerModal');
    const titleEl = document.getElementById('flyerViewerTitle');
    const iframeEl = document.getElementById('flyerPdfIframe');

    if (!viewerModal || !iframeEl) return;

    viewerModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const pdfUrl = button.getAttribute('data-pdf');
        const title = button.getAttribute('data-title') || 'Sfoglia il volantino';

        if (titleEl) {
            titleEl.textContent = title;
        }
        if (iframeEl) {
            iframeEl.setAttribute('src', pdfUrl);
        }
    });

    viewerModal.addEventListener('hidden.bs.modal', function () {
        if (iframeEl) {
            iframeEl.setAttribute('src', '');
        }
    });
});
</script>
</body>
</html>
