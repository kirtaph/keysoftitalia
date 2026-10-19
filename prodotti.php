<?php
/**
 * Key Soft Italia - Prodotti (Nuovi, Expo, Ricondizionati)
 * Allineato allo stile globale (head include, OG/canonical, AOS)
 * Dati caricati via AJAX da assets/ajax/get_products.php
 */
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/assets/php/functions.php';

// SEO
$page_title       = "Smartphone, tablet e computer nuovi e ricondizionati a Ginosa | Key Soft Italia";
$page_description = "Smartphone, tablet, computer e accessori nuovi, expo e ricondizionati, con garanzia e assistenza nel negozio Key Soft Italia di Ginosa.";
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
  <!-- CSS di pagina -->
  <link rel="stylesheet" href="<?= asset_version('css/pages/prodotti.css'); ?>">
  <link rel="stylesheet" href="<?= asset_version('css/pages/prodotti-shop.css'); ?>">
</head>
<body class="site-aligned">
<?php include 'includes/header.php'; ?>
<main id="main-content">

<section class="shop-head" aria-labelledby="shop-title" data-glide-from>
  <picture class="shop-head-still">
    <source media="(max-width:767px)" srcset="<?= asset('img/prodotti/showroom-mobile.webp'); ?>">
    <img src="<?= asset('img/prodotti/showroom-1920.webp'); ?>" alt="" width="1920" height="1086" fetchpriority="high" decoding="async">
  </picture>
  <span class="shop-head-grade" aria-hidden="true"></span>
  <div class="container shop-head-copy">
    <h1 id="shop-title">Il prossimo <span>è qui.</span></h1>
    <p>Smartphone, tablet e computer: nuovi, expo e ricondizionati.</p>
    <a class="shop-link" href="#catalogo">Sfoglia il catalogo <i class="ri-arrow-down-line" aria-hidden="true"></i></a>
    <div class="hero-breadcrumb"><?= generate_breadcrumbs($breadcrumbs); ?></div>
  </div>
</section>

<section class="shop-catalogue" id="catalogo" data-glide-to aria-label="Catalogo">
  <div class="container">
    <form id="filtersForm" class="shop-toolbar" role="search">
      <div class="shop-toolbar-row">
        <div class="shop-chips" id="catChips" role="group" aria-label="Categoria">
          <button type="button" class="shop-chip" data-cat="" aria-pressed="true">Tutti</button>
        </div>
        <div class="shop-toolbar-tools">
          <label class="shop-search">
            <span class="visually-hidden">Cerca un prodotto</span>
            <i class="ri-search-line" aria-hidden="true"></i>
            <input type="search" name="q" id="f-q" placeholder="Cerca un modello" autocomplete="off">
          </label>
          <button class="shop-filter-toggle" type="button" id="toggleFilters" aria-expanded="false" aria-controls="filtersContent">
            <i class="ri-equalizer-line" aria-hidden="true"></i> Filtri <span class="shop-filter-count" id="filterCount" hidden></span>
          </button>
        </div>
      </div>

      <select name="category" id="f-category" hidden aria-hidden="true" tabindex="-1">
        <option value="">Tutte</option>
      </select>

      <div class="shop-filters filters-content" id="filtersContent">
        <label class="shop-field"><span>Marca</span>
          <select class="form-select" name="brand" id="f-brand"><option value="">Tutte</option></select>
        </label>
        <label class="shop-field"><span>Prezzo</span>
          <select class="form-select" name="price" id="f-price">
            <option value="">Tutti</option>
            <option value="0-300">Fino a 300 €</option>
            <option value="300-500">300–500 €</option>
            <option value="500-800">500–800 €</option>
            <option value="800+">Oltre 800 €</option>
          </select>
        </label>
        <label class="shop-field"><span>Condizione</span>
          <select class="form-select" name="grade" id="f-grade">
            <option value="">Tutte</option>
            <option value="Nuovo">Nuovo</option>
            <option value="Expo">Expo</option>
            <option value="A+">Grado A+ (come nuovo)</option>
            <option value="A">Grado A (ottimo)</option>
            <option value="B">Grado B (buono)</option>
            <option value="C">Grado C (discreto)</option>
          </select>
        </label>
        <label class="shop-field"><span>Ordina per</span>
          <select class="form-select" name="sort" id="f-sort">
            <option value="featured">In evidenza</option>
            <option value="price-asc">Prezzo crescente</option>
            <option value="price-desc">Prezzo decrescente</option>
            <option value="newest">Più recenti</option>
          </select>
        </label>
        <label class="shop-field"><span>Per pagina</span>
          <select class="form-select" name="per" id="f-per">
            <option value="12">12</option>
            <option value="20" selected>20</option>
            <option value="24">24</option>
            <option value="36">36</option>
          </select>
        </label>
        <label class="shop-check">
          <input class="form-check-input" type="checkbox" name="available" value="1" id="f-available" checked>
          <span>Solo disponibili</span>
        </label>
        <a class="shop-reset" href="prodotti.php">Azzera i filtri</a>
      </div>
    </form>

    <p class="results-count" aria-live="polite"><strong id="countTotal">Caricamento…</strong></p>
    <div class="shop-grid" id="productsGrid"></div>
    <div id="productsPager"></div>
  </div>
</section>

<section class="shop-promises" aria-label="Garanzie e servizi">
  <div class="container shop-promises-grid">
    <a href="<?= url('servizi/vendita.php'); ?>"><span>Garanzia</span> di 12 mesi sui ricondizionati <i class="ri-arrow-right-up-line" aria-hidden="true"></i></a>
    <a href="<?= url('valuta-usato.php'); ?>"><span>Permuta</span> del tuo usato, scontata subito <i class="ri-arrow-right-up-line" aria-hidden="true"></i></a>
    <a href="<?= url('contatti.php'); ?>"><span>Ritiro</span> in negozio o spedizione <i class="ri-arrow-right-up-line" aria-hidden="true"></i></a>
  </div>
</section>

<section class="shop-contact" id="section-cta" aria-labelledby="shop-contact-title">
  <div class="container">
    <h2 id="shop-contact-title">Non sai quale scegliere?<br><span>Parliamone.</span></h2>
    <div class="shop-contact-bottom">
      <div class="shop-contact-actions">
        <a class="shop-button" href="<?= whatsapp_link('Ciao Key Soft Italia, vorrei un consiglio su quale prodotto scegliere'); ?>" target="_blank" rel="noopener">Scrivici su WhatsApp <i class="ri-whatsapp-line" aria-hidden="true"></i></a>
        <a class="shop-link" href="tel:<?= str_replace(' ', '', COMPANY_PHONE); ?>">Chiamaci: <?= COMPANY_PHONE; ?> <i class="ri-arrow-right-up-line" aria-hidden="true"></i></a>
        <a class="shop-link" href="mailto:<?= COMPANY_EMAIL; ?>">Scrivici una email <i class="ri-arrow-right-up-line" aria-hidden="true"></i></a>
      </div>
    </div>
  </div>
</section>

<div class="modal fade" id="productModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content product-modal">
      <div class="modal-header">
        <h2 class="modal-title h5" id="pmTitle">Dettagli prodotto</h2>
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
          <i class="ri-share-line"></i> Condividi il link
        </button>
        <a href="#" class="btn btn-primary" id="pmBuy" target="_blank" rel="noopener">
          <i class="ri-whatsapp-line"></i> Acquista su WhatsApp
        </a>
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Chiudi</button>
      </div>
    </div>
  </div>
</div>

</main>
<?php include 'includes/footer.php'; ?>
<script src="<?= asset_version('js/opening-glide.js'); ?>" defer></script>

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
    chips: document.getElementById('catChips'),
    filterCount: document.getElementById('filterCount'),

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
  const headerEl = document.querySelector('.header');
  const setHeader = () => document.body.style.setProperty('--shop-header', (headerEl ? headerEl.offsetHeight : 0) + 'px');
  if (headerEl && scrollY < 5) document.body.style.setProperty('--shop-head-offset', headerEl.offsetHeight + 'px');
  setHeader(); addEventListener('resize', setHeader);
  if (headerEl && 'ResizeObserver' in window) new ResizeObserver(setHeader).observe(headerEl);
  attachEvents();
  loadFilters().then(() => {
    // sincronizza form con URL (se arrivi con ?device_slug=smartphone&brand_id=3...)
    syncFormWithUrl();
    // leggi UI -> STATE, fetch
    applyUIToState();
    fetchAndRender().then(() => {
      const usp = new URLSearchParams(window.location.search);
      const sku = usp.get('sku');
      if (sku) {
        openProductModalBySku(sku);
      }
    });
  });

  // ==== EVENTI ===============================================================
  function attachEvents(){
    el.toggle?.addEventListener('click', () => {
      const open = el.filtersWrap?.classList.toggle('show');
      el.toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
    });

    // Filters apply as soon as they change; the search waits for a pause in typing.
    const applyNow = () => { STATE.page = 1; applyUIToState(); updateUrlFromState(); fetchAndRender(); };
    [el.fBrand, el.fPrice, el.fGrade, el.fAvail].forEach(node => node?.addEventListener('change', applyNow));
    let searchTimer = 0;
    el.fQuery?.addEventListener('input', () => { clearTimeout(searchTimer); searchTimer = setTimeout(applyNow, 350); });
    el.chips?.addEventListener('click', (e) => {
      const chip = e.target.closest('[data-cat]');
      if (!chip || !el.fCategory) return;
      el.fCategory.value = chip.dataset.cat;
      applyNow();
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

    // Rimuove il parametro SKU dall'URL quando si chiude la modale
    const mEl = document.getElementById('productModal');
    if (mEl) {
      mEl.addEventListener('hidden.bs.modal', () => {
        const usp = new URLSearchParams(window.location.search);
        if (usp.has('sku')) {
          usp.delete('sku');
          const qs = usp.toString();
          window.history.replaceState(null, '', window.location.pathname + (qs ? '?' + qs : ''));
        }
      });
    }
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
        if (el.chips) el.chips.innerHTML = `<button type="button" class="shop-chip" data-cat="" aria-pressed="true">Tutti</button>` +
          json.filters.devices.map(d =>
            `<button type="button" class="shop-chip" data-cat="${escapeHtml(d.slug)}" aria-pressed="false">${escapeHtml(d.name)}</button>`
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
        const grades = json.filters.grades.filter(g => g.grade && String(g.grade).trim());
        el.fGrade.innerHTML = `<option value="">Tutte</option>` +
          grades.map(g =>
            `<option value="${escapeHtml(g.grade)}">${escapeHtml(describeGrade(g.grade))}</option>`
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
    if (el.fAvail && usp.has('available')){
      el.fAvail.checked = usp.get('available') === '1';
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
    if (STATE.available === 1) p.set('available', '1'); else p.set('available', '0');
    history.replaceState(null, '', location.pathname + '?' + p.toString());
  }

  // ==== FETCH + RENDER =======================================================
function syncToolbar(){
  el.chips?.querySelectorAll('[data-cat]').forEach(c => c.setAttribute('aria-pressed', c.dataset.cat === (STATE.device_slug || '') ? 'true' : 'false'));
  const n = [STATE.brand_id, STATE.grade, STATE.min_price ?? STATE.max_price].filter(v => v !== null && v !== '' && v !== undefined).length;
  if (el.filterCount){ el.filterCount.hidden = !n; el.filterCount.textContent = n ? String(n) : ''; }
}

async function fetchAndRender(){
  syncToolbar();
  // skeleton prima del fetch
  renderSkeletons(8);

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
    if (el.count) el.count.textContent = norm.total === 1 ? '1 prodotto' : `${norm.total} prodotti`;
  } catch (err) {
    if (err?.name === 'AbortError') return; // ignoriamo fetch abortiti per nuovo caricamento
    console.error('fetchAndRender failed:', err);
    renderError('Non riusciamo a caricare il catalogo in questo momento. Riprova tra poco o scrivici su WhatsApp.');
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
      d.className = 'shop-card is-skeleton';
      d.setAttribute('aria-hidden', 'true');
      d.innerHTML = `<div class="shop-card-media"></div><div class="shop-skel w-40"></div><div class="shop-skel w-80"></div><div class="shop-skel w-30"></div>`;
      el.grid.appendChild(d);
    }
  }

  function renderError(msg){
    if (el.grid) el.grid.innerHTML = `<div class="shop-empty"><p>${esc(msg)}</p><a href="prodotti.php">Azzera i filtri</a></div>`;
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
  if (/placeholder\.(jpe?g|png|webp)$/i.test(img)) img = ''; // the catalogue shows the device icon instead
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
    brand: x.brand || '',
    model: x.model || '',
    device: x.device || '',
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

  if (!items || !items.length){ renderError('Nessun prodotto con questi filtri.'); return; }

  el.grid.innerHTML = '';
  for (const p of items){
    const {brand, modelRest} = splitBrandFromTitle(p.title);
    const storage     = formatStorage(p.storage);
    const unavailable = !p.is_available;
    const disc        = (typeof p.discountPct === 'number') ? p.discountPct : getDiscountPct(p);

    const name = (String(modelRest || p.title).replace(/\s*ricondizionat[oa]\s+grado\s+\S+\s*$/i, '').trim() || p.title)
      .replace(/\b([A-ZÀ-Ý]{4,})$/, w => w.charAt(0) + w.slice(1).toLowerCase()); // a trailing colour in capitals
    const make = p.brand || brand;
    const icon = deviceIcon(p.device);
    const card = document.createElement('article');
    card.className = 'shop-card' + (unavailable ? ' is-unavailable' : '');
    card.dataset.sku = p.sku;
    card.innerHTML = `
      <div class="shop-card-media${p.image ? '' : ' is-missing'}">
        ${p.image ? `<img src="${esc(p.image)}" alt="" loading="lazy" decoding="async" onerror="this.parentNode.classList.add('is-missing');this.remove()">` : ''}
        <i class="${icon}" aria-hidden="true"></i>
        <span class="shop-card-tags">
          ${p.grade ? `<span>${esc(labelGrade(p.grade))}</span>` : ''}
          ${storage ? `<span>${esc(storage)}</span>` : ''}
        </span>
        ${unavailable ? `<span class="shop-card-state">Non disponibile</span>` : ''}
      </div>
      ${make ? `<p class="shop-card-brand">${esc(make)}</p>` : ''}
      <h2 class="shop-card-name">${esc(name)}</h2>
      <p class="shop-card-price">
        <strong>€ ${formatEuro(p.price)}</strong>
        ${disc ? `<s>€ ${formatEuro(p.list_price)}</s><span class="shop-card-off">−${disc}%</span>` : ''}
      </p>
      <button type="button" class="shop-card-open" aria-label="Dettagli: ${esc(p.title)}"></button>`;
    card.querySelector('.shop-card-open').addEventListener('click', () => openProductModal(p));

    el.grid.appendChild(card);
  }

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
  async function openProductModalBySku(sku){
    let p = LAST_ITEMS.find(it => it.sku === sku);
    if (!p) {
      try {
        const res = await fetch(ENDPOINT_DETAIL + '?sku=' + encodeURIComponent(sku), {credentials:'same-origin'});
        if (!res.ok) return;
        const json = await res.json();
        if (json && json.ok !== false && json.product) {
          const prod = json.product;
          p = {
            sku: prod.sku,
            title: (prod.specs && prod.specs.Marca && prod.specs.Modello) 
              ? (prod.specs.Marca + ' ' + prod.specs.Modello + (prod.specs.Colore ? ' ' + prod.specs.Colore : ''))
              : (prod.title || 'Prodotto'),
            price: prod.price,
            list_price: prod.list_price,
            grade: prod.specs?.Grado || prod.grade || '',
            storage: prod.specs?.Storage ? parseInt(prod.specs.Storage) : (prod.storage || null),
            short_desc: prod.short_desc || '',
            full_desc: prod.full_desc || '',
            image: (prod.images && prod.images.length) ? prod.images[0] : '',
            images: prod.images || [],
            url: location.origin + location.pathname + '?sku=' + encodeURIComponent(prod.sku),
            _enriched: true
          };
          if (p.image && !/^https?:\/\//i.test(p.image)) {
            p.image = '<?= rtrim(BASE_URL,"/"); ?>/' + String(p.image).replace(/^\/+/, '');
          }
          if (p.images) {
            p.images = p.images.map(src => /^https?:\/\//i.test(src) ? src : ('<?= rtrim(BASE_URL,"/"); ?>/'+String(src).replace(/^\/+/,'')));
          }
        }
      } catch (err) {
        console.error("Errore nel recupero dettagli SKU:", err);
      }
    }
    if (p) {
      openProductModal(p);
    }
  }

  async function openProductModal(p){
    // Aggiorna l'URL del browser con la SKU corrente
    const usp = new URLSearchParams(window.location.search);
    usp.set('sku', p.sku || '');
    window.history.replaceState(null, '', window.location.pathname + '?' + usp.toString());

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
    document.getElementById('pmTitle').textContent = "Dettagli prodotto";
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

    // specs
    const specsEl = document.getElementById('pmSpecs');
    specsEl.innerHTML = '';
    if (enriched.specs) {
      const specIcons = {
        'Marca': 'ri-building-line',
        'Modello': 'ri-smartphone-line',
        'Colore': 'ri-palette-line',
        'Storage': 'ri-database-2-line',
        'Grado': 'ri-shield-flash-line',
        'Condizione': 'ri-shield-flash-line'
      };
      
      let html = '<div class="pm-specs-grid">';
      for (const [key, val] of Object.entries(enriched.specs)) {
        if (!val) continue;
        const icon = specIcons[key] || 'ri-checkbox-circle-line';
        html += `
          <div class="pm-spec">
            <i class="${icon}"></i>
            <span class="pm-spec-label">${esc(key)}</span>
            <span class="pm-spec-val">${esc(val)}</span>
          </div>`;
      }
      html += '</div>';
      specsEl.innerHTML = html;
    }

    // galleria
    const images = Array.isArray(enriched.images)&&enriched.images.length
      ? enriched.images
      : [ enriched.image || p.image ].filter(Boolean);

    const photo  = document.querySelector('#pmPhoto img');
    const thumbs = document.getElementById('pmThumbs');
    thumbs.innerHTML='';

    if (images.length){
      photo.src = images[0]; photo.alt = enriched.title || p.title;
      photo.onerror = () => {
        photo.onerror = null;
        photo.src = '<?= asset("img/recond/placeholder.jpg"); ?>';
      };

      images.forEach((src,i)=>{
        const t=document.createElement('button'); t.type='button'; t.className='pm-thumb'+(i===0?' is-active':'');
        t.innerHTML = `<img src="${esc(src)}" alt="thumb ${i+1}">`;
        t.querySelector('img').onerror = (e) => {
          e.target.onerror = null;
          e.target.src = '<?= asset("img/recond/placeholder.jpg"); ?>';
        };
        t.addEventListener('click',()=>{ 
          photo.src=src; 
          thumbs.querySelectorAll('.pm-thumb').forEach(x=>x.classList.remove('is-active')); 
          t.classList.add('is-active'); 
        });
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
      pmBuy.onclick = (ev) => {
        ev.preventDefault();
        window.open(waUrl, '_blank', 'noopener');
      };
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
  function deviceIcon(device){
    const d = String(device || '').toLowerCase();
    if (d.includes('smartphone')) return 'ri-smartphone-line';
    if (d.includes('tablet')) return 'ri-tablet-line';
    if (d.includes('notebook') || d.includes('laptop') || d.includes('computer')) return 'ri-macbook-line';
    if (d.includes('desktop')) return 'ri-computer-line';
    if (d.includes('watch')) return 'ri-time-line';
    if (d.includes('console')) return 'ri-gamepad-line';
    return 'ri-shopping-bag-3-line';
  }
  // Filter labels say what a grade means; card tags stay short.
  function describeGrade(g){
    const meaning = { 'A+':'come nuovo', 'A':'ottimo', 'B':'buono', 'C':'discreto' }[g];
    return meaning ? `Grado ${g} (${meaning})` : labelGrade(g);
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
    if (navigator.share){ 
      navigator.share({title, text:title, url:shareUrl}).catch(()=>{}); 
      return; 
    }
    // Fallback: copia negli appunti
    if (navigator.clipboard && navigator.clipboard.writeText) {
      navigator.clipboard.writeText(shareUrl).then(() => {
        alert("Link copiato: incollalo dove vuoi condividerlo.");
      }).catch(() => {
        const l=(screen.width/2)-300,t=(screen.height/2)-300;
        window.open('https://www.facebook.com/sharer/sharer.php?u='+encodeURIComponent(shareUrl),'_blank',`width=600,height=600,top=${t},left=${l}`);
      });
    } else {
      const l=(screen.width/2)-300,t=(screen.height/2)-300;
      window.open('https://www.facebook.com/sharer/sharer.php?u='+encodeURIComponent(shareUrl),'_blank',`width=600,height=600,top=${t},left=${l}`);
    }
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
