<?php
/**
 * Key Soft Italia - Prodotti (Nuovi, Expo, Ricondizionati)
 * Allineato allo stile globale (head include, OG/canonical, AOS)
 * Dati caricati via AJAX da assets/ajax/get_products.php
 */
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/assets/php/functions.php';

// SEO
$page_title       = "Prodotti - Key Soft Italia | Smartphone, Tablet, Laptop";
$page_description = "Catalogo prodotti: Nuovi, Expo e Ricondizionati. Smartphone, tablet, laptop e accessori con assistenza Key Soft Italia.";
$page_keywords    = "prodotti, smartphone, tablet, laptop, ricondizionati, expo, nuovo";

// Breadcrumbs
$breadcrumbs = [
  ['label' => 'Prodotti', 'url' => 'prodotti.php']
];

// Endpoint AJAX
$products_endpoint = url('assets/ajax/get_products.php');
?>
<!DOCTYPE html>
<html lang="it">
<head>
  <?php include 'includes/head.php'; ?>
  <title><?= $page_title; ?></title>
  <meta name="description" content="<?= $page_description; ?>">
  <meta name="keywords" content="<?= $page_keywords; ?>">
  <link rel="canonical" href="https://<?= $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>">

  <!-- Open Graph -->
  <meta property="og:title" content="<?= $page_title; ?>">
  <meta property="og:description" content="<?= $page_description; ?>">
  <meta property="og:type" content="website">
  <meta property="og:url" content="https://<?= $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>">
  <meta property="og:image" content="<?= asset('images/og-image.jpg'); ?>">

  <!-- CSS di pagina -->
  <link rel="stylesheet" href="<?= asset_version('css/pages/prodotti.css'); ?>">
</head>
<body>
<?php include 'includes/header.php'; ?>

<!-- HERO -->
<section class="hero hero-secondary text-center hero-prodotti">
  <div class="hero-pattern"></div>
  <div class="container position-relative z-2" data-aos="fade-up">
    <div class="hero-icon mb-3" data-aos="zoom-in"><i class="ri-shopping-bag-3-line"></i></div>
    <h1 class="hero-title text-white" data-aos="fade-up" data-aos-delay="100">
      I nostri <span class="text-gradient">Prodotti</span>
    </h1>
    <p class="hero-subtitle" data-aos="fade-up" data-aos-delay="200">
      Nuovi, Expo e Ricondizionati • Consulenza e assistenza <strong>in negozio</strong>
    </p>
    <div class="hero-cta" data-aos="fade-up" data-aos-delay="300">
      <a href="#catalogo" class="btn btn-primary btn-lg smooth-scroll" aria-label="Vai al catalogo">
        <i class="ri-arrow-down-line me-1"></i> Esplora il catalogo
      </a>
    </div>
    <div class="hero-breadcrumb mt-4" data-aos="fade-up" data-aos-delay="400">
      <?= generate_breadcrumbs($breadcrumbs); ?>
    </div>
  </div>
</section>

<!-- FILTRI -->
<section class="section section-filters" id="filtri">
  <div class="container">
    <form id="filtersForm" class="filters-wrapper" data-aos="fade-up">
      <div class="filters-header">
        <h5 class="filters-title"><i class="ri-filter-3-line"></i> Filtra Prodotti</h5>
        <button class="btn btn-sm btn-outline-secondary d-md-none" type="button" id="toggleFilters">
          <i class="ri-equalizer-line"></i> Mostra Filtri
        </button>
      </div>

      <div class="filters-content" id="filtersContent">
        <div class="row g-3">
          <div class="col-md-3">
            <label class="filter-label">Categoria</label>
            <select class="form-select" name="category" id="f-category">
              <option value="">Tutte</option>
              <option value="smartphone">Smartphone</option>
              <option value="tablet">Tablet</option>
              <option value="laptop">Laptop</option>
              <option value="desktop">Desktop</option>
              <option value="smartwatch">Smartwatch</option>
            </select>
          </div>
          <div class="col-md-3">
            <label class="filter-label">Marca</label>
            <select class="form-select" name="brand" id="f-brand">
              <option value="">Tutte</option>
              <!-- idratata da facets se disponibili -->
            </select>
          </div>
          <div class="col-md-3">
            <label class="filter-label">Prezzo</label>
            <select class="form-select" name="price" id="f-price">
              <option value="">Tutti</option>
              <option value="0-300">Fino a €300</option>
              <option value="300-500">€300 - €500</option>
              <option value="500-800">€500 - €800</option>
              <option value="800+">Oltre €800</option>
            </select>
          </div>
          <div class="col-md-3">
            <label class="filter-label">Condizione</label>
            <select class="form-select" name="grade" id="f-grade">
              <option value="">Tutte</option>
              <option value="Nuovo">Nuovo</option>
              <option value="Expo">Expo</option>
              <option value="A+">A+ (come nuovo)</option>
              <option value="A">A (ottimo)</option>
              <option value="B">B (buono)</option>
              <option value="C">C (discreto)</option>
            </select>
          </div>
        </div>

        <div class="row g-3 align-items-end mt-1">
          <div class="col-md-6">
            <label class="filter-label">Cerca</label>
            <input type="text" class="form-control" name="q" id="f-q" placeholder="Cerca modello, SKU, descrizione…">
          </div>
          <div class="col-md-3">
            <div class="form-check mt-4">
              <input class="form-check-input" type="checkbox" name="available" value="1" id="f-available" checked>
              <label class="form-check-label" for="f-available">Solo disponibili</label>
            </div>
          </div>
          <div class="col-md-3 text-md-end">
            <div class="filters-actions mt-3 mt-md-0">
              <button class="btn btn-primary" type="submit"><i class="ri-check-line"></i> Applica</button>
              <a class="btn btn-outline-secondary" href="prodotti.php"><i class="ri-refresh-line"></i> Reset</a>
            </div>
          </div>
        </div>

        <div class="mt-3 d-flex align-items-center gap-3">
          <label class="me-2 mb-0">Ordina per:</label>
          <select class="form-select form-select-sm" style="width:auto" name="sort" id="f-sort">
            <option value="featured">In evidenza</option>
            <option value="price-asc">Prezzo crescente</option>
            <option value="price-desc">Prezzo decrescente</option>
            <option value="newest">Più recenti</option>
          </select>
          <select class="form-select form-select-sm" style="width:auto" name="per" id="f-per">
            <option value="12" selected>12 per pagina</option>
            <option value="24">24 per pagina</option>
            <option value="36">36 per pagina</option>
          </select>
        </div>
      </div>
    </form>
  </div>
</section>

<!-- CATALOGO -->
<section class="section section-products" id="catalogo">
  <div class="container">
    <div class="results-header" data-aos="fade-up">
      <div class="results-count">
        <span class="text-muted">Trovati</span>
        <strong id="countTotal">0 prodotti</strong>
        <span class="text-muted ms-2" id="countQuery"></span>
      </div>
    </div>

    <div class="products-grid">
      <div class="row g-4" id="productsGrid" data-aos="fade-up" data-aos-delay="100"></div>
    </div>

    <div id="productsPager" class="mt-4"></div>
  </div>
</section>

<!-- INFO -->
<section class="section section-info bg-light">
  <div class="container">
    <div class="row g-4">
      <div class="col-lg-4" data-aos="fade-up">
        <div class="info-box">
          <div class="info-icon"><i class="ri-shield-check-line"></i></div>
          <h4 class="info-title">Garanzia & Assistenza</h4>
          <p class="info-text">Per i ricondizionati: garanzia Key Soft Italia. Per i nuovi: supporto post-vendita.</p>
          <a href="<?= url('garanzia.php'); ?>" class="info-link">Dettagli <i class="ri-arrow-right-line"></i></a>
        </div>
      </div>
      <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
        <div class="info-box">
          <div class="info-icon"><i class="ri-recycle-line"></i></div>
          <h4 class="info-title">Permuta & Ritiro Usato</h4>
          <p class="info-text">Valutiamo il tuo usato per sconto immediato su nuovo/ricondizionato.</p>
          <a href="<?= url('vendere-usato.php'); ?>" class="info-link">Come funziona <i class="ri-arrow-right-line"></i></a>
        </div>
      </div>
      <div class="col-lg-4" data-aos="fade-up" data-aos-delay="200">
        <div class="info-box">
          <div class="info-icon"><i class="ri-secure-payment-line"></i></div>
          <h4 class="info-title">Pagamenti & Spedizioni</h4>
          <p class="info-text">Pagamenti tracciati. Ritiro in negozio o spedizione rapida.</p>
          <a href="<?= url('pagamenti-spedizioni.php'); ?>" class="info-link">Info utili <i class="ri-arrow-right-line"></i></a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- CTA finale -->
<section class="section section-cta" id="section-cta">
  <div class="container">
    <div class="cta-min" data-aos="zoom-in">
      <h2 class="cta-min__title">Serve un consiglio sul modello giusto?</h2>
      <div class="cta-min__buttons">
        <a class="cta-min__btn is-fill" href="<?= whatsapp_link('Ciao Key Soft Italia, vorrei un consiglio su quale prodotto scegliere'); ?>" target="_blank" rel="noopener">
          <span class="ico ico--wa"><i class="ri-whatsapp-line"></i></span>
          <span class="label">WhatsApp</span>
          <span class="sla">(≈15 min)</span>
        </a>
        <a class="cta-min__btn is-ghost" href="tel:<?= str_replace(' ', '', COMPANY_PHONE); ?>">
          <span class="ico ico--tel"><i class="ri-phone-line"></i></span>
          <span class="label">Chiama</span>
          <span class="sla">(immediato)</span>
        </a>
      </div>
      <a class="cta-min__email" href="mailto:<?= COMPANY_EMAIL; ?>"><i class="ri-mail-line"></i> Email (entro 24h)</a>
    </div>
  </div>
</section>

<div class="modal fade" id="productModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content product-modal">
      <div class="modal-header">
        <h5 class="modal-title" id="pmTitle">Dettagli prodotto</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Chiudi"></button>
      </div>
      <div class="modal-body">
        <div class="row g-4">
          <div class="col-lg-6">
            <div class="pm-gallery">
              <div class="pm-photo" id="pmPhoto"><img src="" alt="" /></div>
              <div class="pm-thumbs" id="pmThumbs"></div>
            </div>
          </div>

          <div class="col-lg-6">
            <div class="pm-meta">
              <div class="pm-badges">
                <span class="cap-pill" id="pmStorage"></span>
                <span class="grade-pill" id="pmGrade"></span>
              </div>

              <h3 class="pm-name" id="pmName"></h3>

              <div class="pm-price">
                <span class="pm-price-eur" id="pmPrice"></span>
                <small class="pm-sku" id="pmSku"></small>
              </div>

              <p class="pm-excerpt" id="pmExcerpt"></p>
              <div class="pm-full" id="pmFull"></div>

              <div class="pm-specs" id="pmSpecs"></div>
            </div>
          </div>
        </div>
      </div>

      <div class="modal-footer pm-actions">
        <button type="button" class="btn btn-light" id="pmShare">
          <i class="ri-share-line"></i> Condividi
        </button>
        <a href="#" class="btn btn-primary" id="pmBuy" target="_blank" rel="noopener">
          <i class="ri-shopping-cart-line"></i> Acquista
        </a>
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Chiudi</button>
      </div>
    </div>
  </div>
</div>

<?php include 'includes/footer.php'; ?>

<script src="<?= asset('js/main.js'); ?>" defer></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
  // ==== ENDPOINTS ============================================================
  const ENDPOINT_PRODUCTS = '<?= $products_endpoint ?? url("assets/ajax/get_products.php"); ?>';
  const ENDPOINT_FILTERS  = '<?= url("assets/ajax/get_product_filters.php"); ?>';
  const ENDPOINT_DETAIL   = '<?= url("assets/ajax/get_product_detail.php"); ?>';

  // ==== ELEMENTI UI ==========================================================
  const el = {
    form:  document.getElementById('filtersForm'),
    grid:  document.getElementById('productsGrid'),
    pager: document.getElementById('productsPager'),
    count: document.querySelector('.results-count strong'),
    toggle:document.getElementById('toggleFilters'),

    // filtri (rispettano gli id che mi hai dato)
    fCategory: document.getElementById('f-category'),   // -> device_slug
    fBrand:    document.getElementById('f-brand'),      // -> brand_id
    fPrice:    document.getElementById('f-price'),      // -> min/max
    fGrade:    document.getElementById('f-grade'),
    fQuery:    document.getElementById('f-q'),
    fAvail:    document.getElementById('f-available'),
    fSort:     document.getElementById('f-sort'),
    fPer:      document.getElementById('f-per'),
    filtersWrap: document.getElementById('filtersContent')
  };

// --- STATO ---
const STATE = {
  featured: 0,
  page: 1,
  per: 20,
  sort: 'featured',
  brand_id: null,
  device_slug: '',
  grade: '',
  min_price: null,
  max_price: null,
  storage_min: null,
  storage_max: null,
  q: '',
  available: null // <— null = tutti; 1 = solo disponibili
};

  // per chip marca + split titolo
  let BRAND_LIST = []; // array di nomi brand (per matchare l'inizio del titolo)
  let FR_CTRL = null; // AbortController per cancellare i fetch sovrapposti

  // ==== INIT ================================================================
  attachEvents();
  loadFilters().then(() => {
    // sincronizza form con URL (se arrivi con ?device_slug=smartphone&brand_id=3...)
    syncFormWithUrl();
    // leggi UI -> STATE, fetch
    applyUIToState();
    fetchAndRender();
  });

  // ==== EVENTI ===============================================================
  function attachEvents(){
    el.toggle?.addEventListener('click', () => {
      el.filtersWrap?.classList.toggle('show');
    });

    el.form?.addEventListener('submit', (e) => {
      e.preventDefault();
      STATE.page = 1;
      applyUIToState();
      updateUrlFromState();
      fetchAndRender();
    });

    el.fSort?.addEventListener('change', () => {
      STATE.sort = normalizeSort(el.fSort.value);
      STATE.page = 1;
      fetchAndRender();
    });
    el.fPer?.addEventListener('change', () => {
      STATE.per = parseInt(el.fPer.value, 10) || 20;
      STATE.page = 1;
      fetchAndRender();
    });
  }

  // ==== FILTRI: popolamento da endpoint =====================================
  async function loadFilters(){
    try{
      const res = await fetch(ENDPOINT_FILTERS, {credentials:'same-origin'});
      const json = await res.json();
      if (!json || json.ok === false) return;

      // devices (categoria) -> value = slug
      if (el.fCategory && Array.isArray(json.filters?.devices)){
        el.fCategory.innerHTML = `<option value="">Tutte</option>` +
          json.filters.devices.map(d =>
            `<option value="${escapeHtml(d.slug)}">${escapeHtml(d.name)} (${d.n})</option>`
          ).join('');
      }

      // brands -> value = id
      if (el.fBrand && Array.isArray(json.filters?.brands)){
        BRAND_LIST = json.filters.brands.map(b => String(b.name)).sort((a,b)=>b.length-a.length);
        el.fBrand.innerHTML = `<option value="">Tutte</option>` +
          json.filters.brands.map(b =>
            `<option value="${Number(b.id)}">${escapeHtml(b.name)} (${b.n})</option>`
          ).join('');
      }

      // grade
      if (el.fGrade && Array.isArray(json.filters?.grades)){
        el.fGrade.innerHTML = `<option value="">Tutte</option>` +
          json.filters.grades.map(g =>
            `<option value="${escapeHtml(g.grade)}">${escapeHtml(labelGrade(g.grade))}</option>`
          ).join('');
      }
    } catch { /* fallback */ }
  }

  // ==== URL <-> FORM/STATE ===================================================
  function syncFormWithUrl(){
    const usp = new URLSearchParams(location.search);

    // set select/inputs se presenti in URL
    setter(el.fCategory, usp.get('device_slug'));
    setter(el.fBrand,    usp.get('brand_id'));
    setter(el.fGrade,    usp.get('grade'));
    setter(el.fPrice,    joinPrice(usp.get('min_price'), usp.get('max_price')));
    setter(el.fQuery,    usp.get('q'));
    if (el.fAvail){
    el.fAvail.checked = usp.get('available') === '1'; // solo se presente
  }
    setter(el.fSort, fixSortForUi(usp.get('sort') || 'featured'));
    setter(el.fPer,  usp.get('per') || '20');

    function setter(node, val){ if (node && val !== null && val !== '') node.value = val; }
    function joinPrice(mi, ma){
      if (!mi && !ma) return '';
      if (mi && !ma && Number(mi) >= 800) return '800+';
      return `${mi||0}-${ma||''}`;
    }
  }

  function applyUIToState(){
    STATE.device_slug = el.fCategory?.value || '';
    STATE.brand_id    = el.fBrand?.value ? Number(el.fBrand.value) : null;
    STATE.grade       = el.fGrade?.value || '';
    STATE.q           = (el.fQuery?.value || '').trim();
    STATE.available = (el.fAvail && el.fAvail.checked) ? 1 : null;
    STATE.sort        = normalizeSort(el.fSort?.value || 'featured');
    STATE.per         = parseInt(el.fPer?.value || '20', 10) || 20;

    if (el.fPrice){
      const v = el.fPrice.value;
      if (!v){ STATE.min_price = STATE.max_price = null; }
      else if (v === '800+'){ STATE.min_price = 800; STATE.max_price = null; }
      else {
        const [a,b] = v.split('-');
        STATE.min_price = a ? parseInt(a,10) : null;
        STATE.max_price = b ? parseInt(b,10) : null;
      }
    }
  }

  function updateUrlFromState(){
    const p = new URLSearchParams();
    p.set('page', String(STATE.page));
    p.set('per', String(STATE.per));
    p.set('sort', STATE.sort);
    p.set('featured', String(STATE.featured));
    if (STATE.device_slug) p.set('device_slug', STATE.device_slug);
    if (STATE.brand_id)    p.set('brand_id', String(STATE.brand_id));
    if (STATE.grade)       p.set('grade', STATE.grade);
    if (STATE.min_price!=null) p.set('min_price', String(STATE.min_price));
    if (STATE.max_price!=null) p.set('max_price', String(STATE.max_price));
    if (STATE.storage_min!=null) p.set('storage_min', String(STATE.storage_min));
    if (STATE.storage_max!=null) p.set('storage_max', String(STATE.storage_max));
    if (STATE.q) p.set('q', STATE.q);
    p.set('available', String(STATE.available));
    history.replaceState(null, '', location.pathname + '?' + p.toString());
  }

  // ==== FETCH + RENDER =======================================================
async function fetchAndRender(){
  // skeleton prima del fetch
  renderSkeletons(10);

  // aborta un eventuale fetch precedente
  try { FR_CTRL?.abort(); } catch {}
  FR_CTRL = new AbortController();

  const url = ENDPOINT_PRODUCTS + '?' + buildQuery(STATE) + '&_ts=' + Date.now(); // cache-buster
  try {
    const res = await fetch(url, {
      credentials: 'same-origin',
      headers: { 'X-Requested-With': 'XMLHttpRequest' },
      signal: FR_CTRL.signal
    });

    if (!res.ok) {
      // prova a leggere il body per loggare eventuali errori PHP
      let snippet = '';
      try { snippet = (await res.text()).slice(0, 280); } catch {}
      console.error('HTTP error', res.status, snippet);
      throw new Error(`HTTP ${res.status}`);
    }

    // parse robusto: JSON o testo che contiene JSON
    const ct = res.headers.get('content-type') || '';
    let json;
    if (ct.includes('application/json')) {
      json = await res.json();
    } else {
      const txt = await res.text();
      try { json = JSON.parse(txt); }
      catch (e) {
        console.error('Non-JSON response snippet:', txt.slice(0, 280));
        throw new Error('Risposta non valida dal server');
      }
    }

    // backend ha una chiave ok=false?
    if (typeof json === 'object' && 'ok' in json && json.ok === false) {
      const msg = json.error || 'Errore dal backend';
      console.error('Backend error:', msg);
      throw new Error(msg);
    }

    const norm = normalizeResponse(json, { page: STATE.page, per: STATE.per });
    renderProducts(norm.items);
    renderPager(norm.total, norm.page, norm.per);
    if (el.count) el.count.textContent = `${norm.total} prodotti`;
  } catch (err) {
    if (err?.name === 'AbortError') return; // ignoriamo fetch abortiti per nuovo caricamento
    console.error('fetchAndRender failed:', err);
    renderError(`Errore di caricamento${err?.message ? ` (${err.message})` : ''}.`);
  } finally {
    FR_CTRL = null;
  }
}

  function buildQuery(s){
    const p = new URLSearchParams();
    p.set('featured', String(s.featured));
    p.set('page', String(s.page));
    p.set('per', String(s.per));
    p.set('sort', s.sort);
    if (s.device_slug) p.set('device_slug', s.device_slug);
    if (s.brand_id)    p.set('brand_id', String(s.brand_id));
    if (s.grade)       p.set('grade', s.grade);
    if (s.min_price!=null) p.set('min_price', String(s.min_price));
    if (s.max_price!=null) p.set('max_price', String(s.max_price));
    if (s.storage_min!=null) p.set('storage_min', String(s.storage_min));
    if (s.storage_max!=null) p.set('storage_max', String(s.storage_max));
    if (s.q) p.set('q', s.q);
    if (s.available === 1) p.set('available','1');
    return p.toString();
  }

  // ==== RENDERING ============================================================
  let LAST_ITEMS = [];

  function renderSkeletons(n=10){
    if (!el.grid) return;
    el.grid.innerHTML = '';
    for (let i=0;i<n;i++){
      const d = document.createElement('div');
      d.className = 'product-grid-item';
      d.innerHTML = `
        <article class="product-card is-skeleton">
          <div class="product-image skeleton-block"></div>
          <div class="product-content">
            <div class="skeleton-line w-30"></div>
            <div class="skeleton-line w-80"></div>
            <div class="skeleton-line w-60"></div>
          </div>
        </article>`;
      el.grid.appendChild(d);
    }
  }

  function renderError(msg){
    if (el.grid) el.grid.innerHTML = `<div class="col-12"><div class="alert alert-light border">${esc(msg)}</div></div>`;
    if (el.pager) el.pager.innerHTML = '';
  }

  function normalizeResponse(json, params){
    const raw = Array.isArray(json) ? json
              : (json.products || json.items || json.data || json.results || []);
    const total = (json.total ?? json.count ?? raw.length) | 0;
    const page  = +((json.page ?? params.page) || 1);
    const per   = +((json.per  ?? params.per ) || raw.length || 20);
    return { items: raw.map(normalizeProduct), total, page, per };
  }

function normalizeProduct(x){
  let img = x.img || x.image || '';
  if (img && !/^https?:\/\//i.test(img)) {
    img = '<?= rtrim(BASE_URL,"/"); ?>/' + String(img).replace(/^\/+/, '');
  }
  const list_price = x.list_price ?? x.list_price_eur ?? null;
  const price      = x.price;

  return {
    id:  x.sku || x.id || '',
    sku: x.sku || '',
    title: (x.title||'').trim(),
    price,
    list_price,
    discountPct: computeDiscount(price, list_price), // <-- nuovo
    grade: x.grade || '',
    storage: x.storage || null,
    short_desc: x.excerpt || x.short_desc || '',
    full_desc:  x.full_desc || '',
    is_available: ('is_available' in x) ? !!(+x.is_available) : true,
    url: x.url || '',
    image: img
  };
}

function parsePrice(val){
  if (val == null) return 0;
  if (typeof val === 'number') return val;
  const s = String(val).trim()
    .replace(/\./g, '')     // rimuovi separatore migliaia
    .replace(',', '.')      // virgola -> punto
    .replace(/[^\d.]/g,''); // togli simboli
  const n = parseFloat(s);
  return isNaN(n) ? 0 : n;
}

function getDiscountPct(p){
  const lp = parsePrice(p.list_price);
  const pr = parsePrice(p.price);
  if (lp > 0 && pr > 0 && pr < lp) return Math.round((lp - pr) / lp * 100);
  return 0;
}

function renderProducts(items){
  LAST_ITEMS = items || [];
  if (!el.grid) return;

  if (!items || !items.length){ renderError('Nessun prodotto trovato. Modifica i filtri.'); return; }

  el.grid.innerHTML = '';
  for (const p of items){
    const {brand, modelRest} = splitBrandFromTitle(p.title);
    const storage     = formatStorage(p.storage);
    const unavailable = !p.is_available;
    const disc        = (typeof p.discountPct === 'number') ? p.discountPct : getDiscountPct(p);

    const card = document.createElement('div');
    card.className = 'product-grid-item';
    card.innerHTML = `
      <article class="product-card product-card--compact ${unavailable?'is-unavailable':''}" data-sku="${esc(p.sku)}">
        <div class="pc-top">
          ${storage ? `<span class="cap-pill">${esc(storage)}</span>` : ''}
          ${p.grade ? `<span class="grade-pill ${gradeClass(p.grade)}">${p.grade==='Nuovo'?'Nuovo':'Grado '+esc(p.grade)}</span>` : ''}
        </div>

        ${unavailable ? `<div class="pc-overlay"><span class="pc-o-text"><i class="ri-forbid-2-line"></i> Non disponibile</span></div>` : ''}

        <div class="product-image">
          ${disc ? `<span class="offer-badge">-${disc}%</span>` : ''}
          <img src="${esc(p.image)}" alt="${esc(p.title)}" loading="lazy">
        </div>

        <div class="product-content">
          <h3 class="product-title">${esc(modelRest || p.title)}</h3>
          ${brand ? `<div class="product-brand">${esc(brand)}</div>` : ''}

          <div class="product-price">
            ${disc ? `<span class="price-old">€ ${formatEuro(p.list_price)}</span>` : ''}
            <span class="price-current">€ ${formatEuro(p.price)}</span>
          </div>

          <div class="product-actions" data-no-link>
            <button class="btn btn-outline-primary btn-sm js-details" data-sku="${esc(p.sku)}" ${unavailable?'disabled':''}>
              <i class="ri-information-line"></i> Dettagli
            </button>
            <button class="btn btn-primary btn-sm js-buy" data-sku="${esc(p.sku)}" data-title="${esc(p.title)}" ${unavailable?'disabled':''}>
              <i class="ri-shopping-cart-line"></i> Acquista
            </button>
          </div>

          ${p.url ? `<a class="stretched-link" href="${esc(p.url)}" aria-label="Apri ${esc(p.title)}"></a>` : ''}
        </div>
      </article>`;
    el.grid.appendChild(card);
  }

  // bind CTA (come prima)
  el.grid.querySelectorAll('.js-buy').forEach(b=>{
    b.addEventListener('click', () => {
      const sku = b.getAttribute('data-sku');
      const prod = LAST_ITEMS.find(x => x.sku === sku);
      const message = `Ciao Key Soft Italia, vorrei acquistare ${prod?.title||''} (SKU: ${sku}).`;
      const url = Utils.whatsappLink(message, { utm_campaign:'prodotti-purchase', utm_content: sku });
      window.open(url, '_blank');
    });
  });
  el.grid.querySelectorAll('.js-details').forEach(b=>{
    b.addEventListener('click', () => openProductModalBySku(b.getAttribute('data-sku')));
  });
}

  // ==== PAGINAZIONE ==========================================================
  function renderPager(total, page, per){
    if (!el.pager) return;
    const pages = Math.max(1, Math.ceil(total/per));
    if (pages <= 1){ el.pager.innerHTML = ''; return; }

    const btn = (p, label, dis=false, act=false) =>
      `<button type="button" class="pg-btn ${act?'is-active':''}" data-page="${p}" ${dis?'disabled':''}>${label}</button>`;
    let html = '';
    html += btn(Math.max(1,page-1), '«', page===1);
    let start = Math.max(1, page-2);
    let end   = Math.min(pages, page+2);
    if (start>1) html += btn(1, '1');
    if (start>2) html += `<span class="pg-ellipsis">…</span>`;
    for (let i=start;i<=end;i++) html += btn(i, String(i), false, i===page);
    if (end<pages-1) html += `<span class="pg-ellipsis">…</span>`;
    if (end<pages) html += btn(pages, String(pages));
    html += btn(Math.min(pages,page+1), '»', page===pages);

    el.pager.innerHTML = `<div class="pager">${html}</div>`;
    el.pager.querySelectorAll('.pg-btn').forEach(b=>{
      b.addEventListener('click', () => {
        STATE.page = parseInt(b.getAttribute('data-page'),10)||1;
        updateUrlFromState();
        fetchAndRender();
        const anchor = document.getElementById('catalogo');
        if (anchor) window.scrollTo({ top: anchor.offsetTop - 90, behavior:'smooth' });
      });
    });
  }

  // ==== MODALE DETTAGLI ======================================================
  function openProductModalBySku(sku){
    const p = LAST_ITEMS.find(it => it.sku === sku);
    if (p) openProductModal(p);
  }

  async function openProductModal(p){
    const enriched = await enrichProduct(p).catch(()=>p);
    const mEl = document.getElementById('productModal');
    // prezzo in modale (con listino barrato se presente)
    const disc = computeDiscount(enriched.price ?? p.price, enriched.list_price ?? p.list_price);
    const pmPrice = document.getElementById('pmPrice');
    pmPrice.innerHTML = `
      ${ (enriched.list_price ?? p.list_price) && disc ? `<span class="price-old me-2">€ ${formatEuro(enriched.list_price ?? p.list_price)}</span>` : '' }
      <span class="price-current">€ ${formatEuro(enriched.price ?? p.price)}</span>
`;
    // header + pill
    document.getElementById('pmTitle').textContent = "Dettagli Prodotto";
    document.getElementById('pmName').textContent  = enriched.title || p.title;
    document.getElementById('pmSku').textContent   = enriched.sku ? `SKU: ${enriched.sku}` : '';

    const gradeEl = document.getElementById('pmGrade');
    gradeEl.textContent = enriched.grade ? (enriched.grade==='Nuovo'?'Nuovo':'Grado '+enriched.grade) : '';
    gradeEl.className = 'grade-pill ' + (enriched.grade ? gradeClass(enriched.grade) : '');

    const storageEl = document.getElementById('pmStorage');
    storageEl.textContent = enriched.storage ? formatStorage(enriched.storage) : '';

    // descrizioni
    document.getElementById('pmExcerpt').textContent = (enriched.short_desc || p.short_desc || '').toString().trim();
    document.getElementById('pmFull').innerHTML = (enriched.full_desc || p.full_desc)
      ? `<div class="pm-full-inner">${escapeNL(enriched.full_desc || p.full_desc)}</div>` : '';

    // galleria
    const images = Array.isArray(enriched.images)&&enriched.images.length
      ? enriched.images
      : [ enriched.image || p.image ].filter(Boolean);

    const photo  = document.querySelector('#pmPhoto img');
    const thumbs = document.getElementById('pmThumbs');
    thumbs.innerHTML='';

    if (images.length){
      photo.src = images[0]; photo.alt = enriched.title || p.title;
      images.forEach((src,i)=>{
        const t=document.createElement('button'); t.type='button'; t.className='pm-thumb'+(i===0?' is-active':'');
        t.innerHTML = `<img src="${esc(src)}" alt="thumb ${i+1}">`;
        t.addEventListener('click',()=>{ photo.src=src; thumbs.querySelectorAll('.pm-thumb').forEach(x=>x.classList.remove('is-active')); t.classList.add('is-active'); });
        thumbs.appendChild(t);
      });
    }

 // bottone Acquista: forza apertura nuova scheda
const pmBuy = document.getElementById('pmBuy');
const waUrl = Utils.whatsappLink(
  `Ciao Key Soft Italia, ho visto sul vostro sito questo prodotto ${(enriched.title||p.title)} (SKU: ${(enriched.sku||p.sku)}).`,
  { utm_campaign:'prodotti-modal-buy', utm_content: (enriched.sku||p.sku) }
);
if (pmBuy){
  pmBuy.setAttribute('href', waUrl);
  pmBuy.setAttribute('target','_blank');
  pmBuy.setAttribute('rel','noopener');
  pmBuy.addEventListener('click', (ev) => {
    // se fosse un <button> senza href o bootstrap lo blocca:
    ev.preventDefault();
    window.open(waUrl, '_blank', 'noopener');
  }, { once:true });
}

    document.getElementById('pmShare').onclick = () => shareProduct(enriched);

    if (window.bootstrap && bootstrap.Modal){
      bootstrap.Modal.getOrCreateInstance(mEl).show();
    } else {
      mEl.classList.add('show'); mEl.style.display='block'; mEl.removeAttribute('aria-hidden');
    }
  }

  async function enrichProduct(p){
    if (p._enriched) return p;
    const url = ENDPOINT_DETAIL + '?sku=' + encodeURIComponent(p.sku||'');
    try{
      const res = await fetch(url, {credentials:'same-origin'}); if(!res.ok) return p;
      const json = await res.json(); if(!json || json.ok===false || !json.product) return p;

      let imgs=[];
      if (Array.isArray(json.product.images)){
        imgs = json.product.images.map(src => /^https?:\/\//i.test(src) ? src : ('<?= rtrim(BASE_URL,"/"); ?>/'+String(src).replace(/^\/+/,'')));
      }
      return {
        ...p,
        images: imgs.length?imgs:(p.image?[p.image]:[]),
        full_desc: json.product.full_desc || p.full_desc || '',
        short_desc: p.short_desc,
        specs: json.product.specs || null,
        _enriched: true
      };
    } catch { return p; }
  }

  // ==== UTILS ================================================================
  function normalizeSort(v){ return (v||'featured').replace('-', '_'); }
  function fixSortForUi(v){ return (v||'featured').replace('_','-'); }

  function formatStorage(v){
    const n = +v || 0; if (!n) return '';
    if (n >= 1024 && n % 1024 === 0) return (n/1024)+'TB';
    return n+'GB';
  }
  function gradeClass(g){
    const key=(g+'').toLowerCase().replace(/\+/g,'plus');
    if (key==='nuovo') return 'grade--new';
    if (key==='expo')  return 'grade--expo';
    return 'grade--'+key; // aplus,a,b,c
  }
  function labelGrade(g){ return g==='Nuovo'?'Nuovo':(g==='Expo'?'Expo':('Grado '+g)); }
  function esc(s){ return (s+'').replace(/[&<>"']/g, m=>({ '&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;'}[m])); }
  function escapeHtml(s){ return esc(s); }
  function escapeNL(s){ return esc(String(s)).replace(/\n/g,'<br>'); }

  function splitBrandFromTitle(title){
    const t = String(title||'');
    let brand='', rest=t;
    // matcha marca più lunga che è prefisso del titolo
    for (const b of BRAND_LIST){
      const pref = b + ' ';
      if (t === b || t.startsWith(pref)){ brand = b; rest = t.slice(b.length).trim(); break; }
    }
    if (!brand){
      const first = t.split(' ')[0] || '';
      brand = first; rest = t.slice(first.length).trim();
    }
    return { brand, modelRest: rest };
  }

  function shareProduct(p){
    const shareUrl = p.url || (location.origin + location.pathname + '?sku=' + encodeURIComponent(p.sku||''));
    const title = p.title || 'Prodotto Key Soft Italia';
    if (navigator.share){ navigator.share({title, text:title, url:shareUrl}).catch(()=>{}); return; }
    const l=(screen.width/2)-300,t=(screen.height/2)-300;
    window.open('https://www.facebook.com/sharer/sharer.php?u='+encodeURIComponent(shareUrl),'_blank',`width=600,height=600,top=${t},left=${l}`);
  }
});

// --- helper numerico robusto IT -> Number
function toNumber(val){
  if (val == null) return NaN;
  if (typeof val === 'number') return val;
  const s = String(val).trim().replace(/\./g,'').replace(',', '.').replace(/[^\d.]/g,'');
  const n = parseFloat(s);
  return isNaN(n) ? NaN : n;
}

function formatEuro(val){
  const n = toNumber(val);
  if (isNaN(n)) return String(val ?? '');
  return n.toLocaleString('it-IT',{minimumFractionDigits:2, maximumFractionDigits:2});
}

// percentuale sconto arrotondata
function computeDiscount(price, list){
  const p = toNumber(price), l = toNumber(list);
  if (isNaN(p) || isNaN(l) || l <= 0 || p >= l) return 0;
  return Math.round(((l - p) / l) * 100);
}
</script>

</body>
</html>
