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
        Seleziona la categoria per vedere le promo <strong>in corso</strong>, <strong>in arrivo</strong> o l’<strong>archivio</strong>.
      </p>
    </div>

    <!-- Tabs / Filtri -->
    <div class="flyers-filters" role="tablist" aria-label="Filtra volantini per stato">
      <button type="button"
              class="flyer-tab is-active"
              data-flyer-tab
              data-status="current"
              role="tab"
              aria-selected="true">
        <i class="ri-flashlight-line me-1" aria-hidden="true"></i> In corso
      </button>
      <button type="button"
              class="flyer-tab"
              data-flyer-tab
              data-status="upcoming"
              role="tab"
              aria-selected="false">
        <i class="ri-calendar-event-line me-1" aria-hidden="true"></i> In arrivo
      </button>
      <button type="button"
              class="flyer-tab"
              data-flyer-tab
              data-status="archived"
              role="tab"
              aria-selected="false">
        <i class="ri-archive-line me-1" aria-hidden="true"></i> Archivio
      </button>
    </div>

    <!-- Meta / messaggi -->
    <div class="flyers-meta d-flex justify-content-between align-items-center flex-wrap mt-3">
      <p class="mb-1 small text-muted">
        <span id="flyersCount">0</span> volantini trovati
      </p>
      <p class="mb-1 small text-muted">
        I volantini vengono aggiornati periodicamente in base alle promozioni disponibili.
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
<section class="section section-cta text-center" aria-labelledby="cta-volantini">
  <div class="container" data-aos="zoom-in">
    <h2 id="cta-volantini" class="cta-title">Hai visto un’offerta che ti interessa?</h2>
    <p class="cta-subtitle">Scrivici su WhatsApp o passa in negozio: ti aiutiamo a scegliere il dispositivo giusto per te.</p>
    <div class="cta-buttons">
      <a href="<?php echo whatsappLink(COMPANY_WHATSAPP, 'Ciao! Ho visto un volantino sul sito e vorrei maggiori informazioni.'); ?>"
         class="btn btn-success btn-lg me-2">
        <i class="ri-whatsapp-line" aria-hidden="true"></i> Scrivici su WhatsApp
      </a>
      <a href="<?php echo url('contatti.php'); ?>" class="btn btn-outline-primary btn-lg">
        <i class="ri-store-2-line" aria-hidden="true"></i> Come raggiungerci
      </a>
    </div>
  </div>
</section>

<!-- MODAL VIEWER PDF -->
<div class="modal fade" id="flyerViewerModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered flyer-modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="flyerViewerTitle">Sfoglia il volantino</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Chiudi"></button>
      </div>

      <div class="modal-body flyer-modal-body">
        <!-- TOOLBAR -->
        <div class="pdfjs-toolbar">
          <div class="pdfjs-page-indicator">
            Pagina <span id="pdfPageCurrent">1</span> di <span id="pdfPageTotal">1</span>
          </div>
          <div class="pdfjs-zoom-controls">
            <button type="button" class="btn btn-sm btn-outline-light" data-pdf-zoom-out>
              &minus;
            </button>
            <button type="button" class="btn btn-sm btn-outline-light ms-2" data-pdf-zoom-in>
              +
            </button>
          </div>
        </div>

        <!-- VIEWER -->
        <div id="flyerPdfViewer" class="pdfjs-viewer">
          <div class="pdfjs-loading">Caricamento volantino...</div>
        </div>
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
<!-- PDF.js (viewer integrato) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>

<script>
(function(){
  const FLYERS_ENDPOINT = "<?php echo asset('ajax/get_flyers.php'); ?>";

  const tabs    = document.querySelectorAll('[data-flyer-tab]');
  const grid    = document.getElementById('flyersGrid');
  const msgBox  = document.getElementById('flyersMessage');
  const counter = document.getElementById('flyersCount');

  if (!grid || !tabs.length) return;

  const params      = new URLSearchParams(window.location.search);
  const slugParam   = params.get('flyer'); // slug passato da home
  const STATUS_ORDER = ['current', 'upcoming', 'archived'];

  // ---------- UI helpers ----------
  function setActiveTab(status){
    tabs.forEach(btn => {
      const isActive = btn.getAttribute('data-status') === status;
      btn.classList.toggle('is-active', isActive);
      btn.setAttribute('aria-selected', isActive ? 'true' : 'false');
    });
  }

  function statusEmptyMessage(status){
    switch(status){
      case 'upcoming': return 'Al momento non ci sono volantini in arrivo. Torna a trovarci tra qualche giorno 😉';
      case 'archived': return 'Non ci sono ancora volantini in archivio.';
      case 'current':
      default:         return 'Al momento non ci sono volantini attivi. Passa in negozio per scoprire le offerte disponibili.';
    }
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
  function fetchFlyers(status){
    const url = new URL(FLYERS_ENDPOINT, window.location.origin);
    url.searchParams.set('status', status);

    return fetch(url.toString(), {
      headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(res => {
      if (!res.ok) throw new Error('HTTP ' + res.status);
      return res.json();
    });
  }

  // ---------- RENDER ----------
  function renderFlyers(flyers, status){
    clearGrid();
    counter.textContent = flyers.length;

    if (!flyers.length){
      msgBox.textContent = statusEmptyMessage(status);
      return;
    }

    msgBox.textContent = '';

    let delay = 0;

    flyers.forEach(f => {
      delay += 80;

      const col = document.createElement('div');
      col.className = 'col-md-6 col-lg-4';
      col.setAttribute('data-aos', 'fade-up');
      col.setAttribute('data-aos-delay', String(delay));

      const card = document.createElement('article');
      card.className = 'flyer-card h-100';

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
      statusBadge.className = 'badge flyer-badge flyer-badge-' + (f.status_code || 'current');
      statusBadge.textContent = f.status_label || '';
      badgesWrap.appendChild(statusBadge);

      if (f.show_home){
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

      if (f.pdf_url){
        const btnView = document.createElement('button');
        btnView.type = 'button';
        btnView.className = 'btn btn-primary btn-sm';
        btnView.setAttribute('data-bs-toggle', 'modal');
        btnView.setAttribute('data-bs-target', '#flyerViewerModal');
        btnView.dataset.pdf   = f.pdf_url;
        btnView.dataset.title = f.title;
        btnView.dataset.slug  = f.slug; // IMPORTANTE per apertura da ?flyer=slug
        btnView.innerHTML = '<i class="ri-eye-line me-1" aria-hidden="true"></i> Sfoglia online';
        actions.appendChild(btnView);

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
  function loadFlyers(status, opts){
    opts = opts || {};
    const focusSlug = opts.focusSlug || null;

    setActiveTab(status);
    showLoading();

    return fetchFlyers(status)
      .then(data => {
        hideLoading();

        if (!data || !data.ok){
          msgBox.textContent = 'Si è verificato un errore durante il caricamento dei volantini.';
          counter.textContent = '0';
          clearGrid();
          return { found: false };
        }

        const flyers = data.flyers || [];
        renderFlyers(flyers, status);

        let found = false;
        if (focusSlug){
          const btn = grid.querySelector('button[data-slug="' + CSS.escape(focusSlug) + '"]');
          if (btn){
            // apre la modale del volantino target
            btn.click();
            found = true;
          }
        }

        return { found: found };
      })
      .catch(() => {
        hideLoading();
        msgBox.textContent = 'Si è verificato un problema di connessione. Riprova tra qualche istante.';
        counter.textContent = '0';
        clearGrid();
        return { found: false };
      });
  }

  async function initPage(){
    // Se abbiamo uno slug in query (?flyer=xxx)
    if (slugParam){
      for (const st of STATUS_ORDER){
        const res = await loadFlyers(st, { focusSlug: slugParam });
        if (res && res.found){
          return; // trovato, abbiamo aperto la modale
        }
      }
      // Slug non trovato in nessuno stato: mostro comunque "in corso"
      await loadFlyers('current');
      return;
    }

    // Caso normale: nessun slug, carico "in corso"
    await loadFlyers('current');
  }

  // ---------- Bind tabs ----------
  tabs.forEach(btn => {
    btn.addEventListener('click', () => {
      const status = btn.getAttribute('data-status') || 'current';
      loadFlyers(status);
    });
  });

  // ---------- Init ----------
  initPage();
})();
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const viewerModal = document.getElementById('flyerViewerModal');
  const viewerEl    = document.getElementById('flyerPdfViewer');
  const titleEl     = document.getElementById('flyerViewerTitle');

  const pageCurrentEl = document.getElementById('pdfPageCurrent');
  const pageTotalEl   = document.getElementById('pdfPageTotal');
  const zoomInBtn     = document.querySelector('[data-pdf-zoom-in]');
  const zoomOutBtn    = document.querySelector('[data-pdf-zoom-out]');

  if (!viewerModal || !viewerEl) return;

  // Fallback: se PDF.js non è caricato, apro il PDF in nuova scheda
  if (typeof window['pdfjsLib'] === 'undefined') {
    viewerModal.addEventListener('show.bs.modal', function (event) {
      const button = event.relatedTarget;
      const pdfUrl = button?.getAttribute('data-pdf');
      if (pdfUrl) {
        window.open(pdfUrl, '_blank', 'noopener');
      }
    });
    return;
  }

  // Config worker PDF.js
  pdfjsLib.GlobalWorkerOptions.workerSrc =
    "https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js";

  let pdfDoc      = null;
  let currentScale = 1.2;
  const ZOOM_STEP  = 0.2;
  const ZOOM_MIN   = 0.7;
  const ZOOM_MAX   = 2.0;

  let scrollRafId = null;

  function setPageIndicator(current, total) {
    if (pageCurrentEl) pageCurrentEl.textContent = String(current || 1);
    if (pageTotalEl)   pageTotalEl.textContent   = String(total || 1);
  }

  function updateCurrentPageOnScroll() {
    if (!pdfDoc) return;

    const canvases = viewerEl.querySelectorAll('.pdfjs-page-canvas');
    if (!canvases.length) return;

    const containerRect   = viewerEl.getBoundingClientRect();
    const viewportCenterY = containerRect.top + viewerEl.clientHeight / 2;

    let bestPage = 1;
    let bestScore = Infinity;

    canvases.forEach(canvas => {
      const rect = canvas.getBoundingClientRect();
      const canvasCenterY = rect.top + rect.height / 2;
      const score = Math.abs(canvasCenterY - viewportCenterY);
      if (score < bestScore) {
        bestScore = score;
        bestPage  = parseInt(canvas.dataset.pageNumber || '1', 10);
      }
    });

    setPageIndicator(bestPage, pdfDoc.numPages || 1);
  }

  function bindScrollIndicator() {
    viewerEl.addEventListener('scroll', () => {
      if (scrollRafId) cancelAnimationFrame(scrollRafId);
      scrollRafId = requestAnimationFrame(updateCurrentPageOnScroll);
    });
  }

  function renderAllPages() {
    if (!pdfDoc) return;

    viewerEl.innerHTML = '';
    const numPages = pdfDoc.numPages || 1;
    setPageIndicator(1, numPages);

    const renderPromises = [];
    for (let pageNum = 1; pageNum <= numPages; pageNum++) {
      renderPromises.push(
        pdfDoc.getPage(pageNum).then(page => {
          const viewport = page.getViewport({ scale: currentScale });

          const canvas = document.createElement('canvas');
          canvas.className = 'pdfjs-page-canvas';
          canvas.dataset.pageNumber = String(pageNum);

          const context = canvas.getContext('2d');
          canvas.width  = viewport.width;
          canvas.height = viewport.height;

          viewerEl.appendChild(canvas);

          const renderContext = { canvasContext: context, viewport: viewport };
          return page.render(renderContext).promise;
        })
      );
    }

    return Promise.all(renderPromises).then(() => {
      // dopo il render iniziale forzo l'update del contatore
      updateCurrentPageOnScroll();
    }).catch((error) => {
      console.error('Errore rendering PDF:', error);
      viewerEl.innerHTML =
        '<div class="pdfjs-error">Impossibile visualizzare il volantino. ' +
        '<a href="' + encodeURI(pdfDoc._transport._params.url) +
        '" target="_blank" rel="noopener">Apri il PDF in una nuova scheda</a>.</div>';
    });
  }

  function loadPdf(url) {
    viewerEl.innerHTML = '<div class="pdfjs-loading">Caricamento volantino...</div>';
    pdfDoc = null;
    currentScale = 1.2;

    const loadingTask = pdfjsLib.getDocument(url);
    loadingTask.promise.then(function (pdf) {
      pdfDoc = pdf;
      return renderAllPages();
    }).catch(function (error) {
      console.error('Errore PDF.js:', error);
      viewerEl.innerHTML =
        '<div class="pdfjs-error">Impossibile caricare il volantino. ' +
        '<a href="' + encodeURI(url) +
        '" target="_blank" rel="noopener">Apri il PDF in una nuova scheda</a>.</div>';
    });
  }

  function applyZoom(delta) {
    if (!pdfDoc) return;

    const container = viewerEl;
    const prevScrollRatio = container.scrollTop / (container.scrollHeight || 1);

    let nextScale = currentScale + delta;
    nextScale = Math.max(ZOOM_MIN, Math.min(ZOOM_MAX, nextScale));
    if (nextScale === currentScale) return;

    currentScale = nextScale;
    renderAllPages().then(() => {
      // riposiziono lo scroll più o meno dove stava
      container.scrollTop = prevScrollRatio * (container.scrollHeight || 1);
    });
  }

  // Zoom +/-
  if (zoomInBtn) {
    zoomInBtn.addEventListener('click', function () {
      applyZoom(ZOOM_STEP);
    });
  }
  if (zoomOutBtn) {
    zoomOutBtn.addEventListener('click', function () {
      applyZoom(-ZOOM_STEP);
    });
  }

  // Bind scroll per l'indicatore pagina
  bindScrollIndicator();

  // Apertura modale: carico il PDF e setto il titolo
  viewerModal.addEventListener('show.bs.modal', function (event) {
    const button = event.relatedTarget;
    const pdfUrl = button?.getAttribute('data-pdf');
    const title  = button?.getAttribute('data-title') || 'Sfoglia il volantino';

    if (titleEl) {
      titleEl.textContent = title;
    }
    if (pdfUrl) {
      loadPdf(pdfUrl);
    }
  });

  // Chiusura modale: pulisco
  viewerModal.addEventListener('hidden.bs.modal', function () {
    viewerEl.innerHTML = '';
    pdfDoc = null;
    setPageIndicator(1, 1);
  });
});
</script>

</body>
</html>
