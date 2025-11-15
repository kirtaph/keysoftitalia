<?php
if (!defined('BASE_PATH')) {
  define('BASE_PATH', rtrim(str_replace('\\','/', dirname(__DIR__)), '/') . '/');
}
require_once BASE_PATH.'config/config.php';
require_once BASE_PATH.'assets/php/functions.php';

$recond_id       = $recond_id       ?? 'recond-swiper';
$recond_limit    = max(1, min(20, (int)($recond_limit ?? 5)));
$recond_featured = (int)($recond_featured ?? 1);
$recond_title    = isset($recond_title) ? trim((string)$recond_title) : null;

// endpoint
$endpoint = url('assets/ajax/get_products.php', [
  'featured'  => $recond_featured,
  'per'       => $recond_limit,
  'available' => 1,              // per lo slider meglio solo disponibili
  'sort'      => 'featured'
]);

// normalizza numero WhatsApp -> wa.me
$ks_wa = preg_replace('/\D+/', '', COMPANY_WHATSAPP ?? '');
if ($ks_wa !== '' && strpos($ks_wa, '39') !== 0) { $ks_wa = '39'.$ks_wa; }

// base URL per rendere assolute eventuali immagini relative
$KS_BASE = rtrim(BASE_URL, '/').'/';
?>
<?php if ($recond_title): ?>
  <div class="section-header d-flex align-items-center justify-content-between mb-2">
    <h3 class="mb-0"><?= htmlspecialchars($recond_title, ENT_QUOTES, 'UTF-8'); ?></h3>
  </div>
<?php endif; ?>

<div class="recond-swiper swiper" id="<?= htmlspecialchars($recond_id, ENT_QUOTES, 'UTF-8'); ?>">
  <div class="swiper-wrapper" id="<?= htmlspecialchars($recond_id, ENT_QUOTES, 'UTF-8'); ?>-wrapper">
    <!-- slides injected -->
  </div>
  <div class="swiper-pagination"></div>
</div>

<script>
(() => {
  const WRAP_ID   = '<?= $recond_id; ?>-wrapper';
  const ROOT_SEL  = '#<?= $recond_id; ?>';
  const ENDPOINT  = '<?= $endpoint; ?>';
  const KS_BASE   = '<?= $KS_BASE; ?>';
  const WA_NUM    = '<?= $ks_wa; ?>';
  window.KS_WA_NUMBER = WA_NUM;

  const wrap = document.getElementById(WRAP_ID);
  if (!wrap) return;

  // --- Utils ---
  const esc = s => String(s ?? '').replace(/[&<>"]/g, m => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;'}[m]));
  const toAbs = src => (!src ? '' : (/^https?:\/\//i.test(src) ? src : (KS_BASE + String(src).replace(/^\/+/,''))));
  const parsePrice = v => {
    if (v==null) return 0;
    if (typeof v==='number') return v;
    const n = parseFloat(String(v).replace(/\./g,'').replace(',','.').replace(/[^\d.]/g,''));
    return isNaN(n) ? 0 : n;
  };
  const formatEuro = v => parsePrice(v).toLocaleString('it-IT', {minimumFractionDigits:2, maximumFractionDigits:2});
  const storageLabel = s => {
    const n = parseInt(s,10) || 0;
    if (!n) return '';
    return (n>=1024 && n%1024===0) ? (n/1024)+'TB' : n+'GB';
  };
  const gradeClass = g => {
    const k = String(g||'').toUpperCase();
    if (k==='NUOVO') return 'grade-new';
    if (k==='EXPO')  return 'grade-expo';
    if (k==='A+')    return 'grade-a-plus';
    if (k==='A')     return 'grade-a';
    if (k==='B')     return 'grade-b';
    if (k==='C')     return 'grade-c';
    if (k==='D')     return 'grade-d';
    return '';
  };
  const gradeLabel = g => {
    const k = String(g||'').toUpperCase();
    if (k==='NUOVO') return 'Nuovo';
    if (k==='EXPO')  return 'Da Vetrina';
    if (k==='A+')    return 'Grado A+';
    if (k==='A')     return 'Grado A';
    if (k==='B')     return 'Grado B';
    if (k==='C')     return 'Grado C';
    if (k==='D')     return 'Grado D';
    return '';
  };
  const discountPct = (list, price) => {
    const lp = parsePrice(list), pr = parsePrice(price);
    return (lp>0 && pr>0 && pr<lp) ? Math.round((lp-pr)/lp*100) : 0;
  };
  const whatsappLink = (title, price, sku, pageUrl) => {
    const msg  = `Ciao Key Soft Italia! 👋%0AHo visto questo prodotto sul sito e vorrei info:%0A%0A` +
                 `• Prodotto: ${encodeURIComponent(title)}%0A` +
                 (sku   ? `• SKU: ${encodeURIComponent(sku)}%0A` : '') +
                 (price ? `• Prezzo: € ${encodeURIComponent(price)}%0A` : '') +
                 (pageUrl ? `• Pagina: ${encodeURIComponent(pageUrl)}%0A` : '') +
                 `Grazie!`;
    // usa Utils.whatsappLink se disponibile (con UTM), altrimenti wa.me
    if (window.Utils && typeof Utils.whatsappLink === 'function') {
      return Utils.whatsappLink(
        decodeURIComponent(msg.replace(/%0A/g, '\n')),
        { utm_campaign: 'recond-swiper', utm_content: sku||'' }
      );
    }
    return `https://wa.me/${WA_NUM}?text=${msg}`;
  };

  // --- Card slide ---
  const cardSlide = (p, delay=0) => {
    const title   = esc(p.title || '');
    const sku     = esc(p.sku   || '');
    const img     = toAbs(p.img || p.image || '');
    const url     = p.url || (KS_BASE + 'ricondizionati.php?sku='+encodeURIComponent(p.sku||''));
    const grade   = p.grade || '';
    const storage = p.storage || null;

    const listStr = p.list_price ?? null;
    const disc    = discountPct(listStr, p.price);
    const oldHtml = disc ? `<span class="price-old">€ ${formatEuro(listStr)}</span>` : '';
    const price   = `€ ${formatEuro(p.price)}`;

    const chips = `
      <div class="recond-chips" aria-hidden="true">
        <div class="recond-chips-left">
          ${storage ? `<span class="recond-chip storage">${esc(storageLabel(storage))}</span>` : ``}
        </div>
        <div class="recond-chips-right">
          ${grade ? `<span class="recond-chip grade ${gradeClass(grade)}">${esc(gradeLabel(grade))}</span>` : ``}
        </div>
      </div>`;

    const badge = disc ? `<span class="offer-badge">-${disc}%</span>` : '';
    const waHref = whatsappLink(p.title||'', p.price||'', p.sku||'', url);

    return `
      <div class="swiper-slide">
        <div class="recond-card" data-aos="fade-up" data-aos-duration="600" data-aos-delay="${delay}">
          <div class="recond-img-wrap">
            ${badge}
            <img src="${img}" alt="${title}" class="recond-img" loading="lazy">
            ${chips}
          </div>
          <div class="recond-body">
            <h4 class="recond-title">${title}</h4>
            <div class="recond-price">
              ${oldHtml}<span class="price-current">${price}</span>
            </div>
            <a href="${waHref}" target="_blank" rel="noopener" class="btn-wa w-100 mt-3"
               aria-label="Richiedi info su ${title} via WhatsApp">
              <i class="ri-shopping-cart-2-line"></i> Acquista
            </a>
          </div>
        </div>
      </div>`;
  };

  // --- Render skeleton mentre carica ---
  const renderSkeletons = (n=<?= $recond_limit; ?>) => {
    wrap.innerHTML = Array.from({length:n}).map(()=>`
      <div class="swiper-slide">
        <div class="recond-card is-skeleton">
          <div class="recond-img-wrap"><div class="sk-block"></div></div>
          <div class="recond-body">
            <div class="sk-line w-70"></div>
            <div class="sk-line w-40"></div>
          </div>
        </div>
      </div>`).join('');
  };

  // --- Fetch + mount ---
  renderSkeletons();
  fetch(ENDPOINT, {credentials:'same-origin'})
    .then(r => r.ok ? r.json() : Promise.reject())
    .then(json => {
      const items = (json && (json.products||json.items||[])) || [];
      if (!items.length){
        wrap.innerHTML = `
          <div class="swiper-slide">
            <div class="recond-card text-center p-4">
              <p class="mb-1">Nessun prodotto in evidenza al momento.</p>
            </div>
          </div>`;
      } else {
        let d = 120;
        wrap.innerHTML = items.map(p => (d+=80, cardSlide(p, d))).join('');
      }

      new Swiper(ROOT_SEL, {
        slidesPerView: 1.2,
        spaceBetween: 16,
        pagination: { el: ROOT_SEL+' .swiper-pagination', clickable: true },
        breakpoints: { 576:{slidesPerView:2}, 992:{slidesPerView:3}, 1200:{slidesPerView:4} }
      });

      if (window.AOS?.refreshHard) AOS.refreshHard();
      else if (window.AOS?.refresh) AOS.refresh();
    })
    .catch(() => {
      wrap.innerHTML = `
        <div class="swiper-slide">
          <div class="recond-card text-center p-4">
            <p class="mb-1">Impossibile caricare i prodotti.</p>
            <small class="text-muted">Riprova più tardi.</small>
          </div>
        </div>`;
      new Swiper(ROOT_SEL, { slidesPerView:1.2, spaceBetween:16, pagination:{ el: ROOT_SEL+' .swiper-pagination', clickable:true }});
    });
})();
</script>

<style>
/* Scoped: solo per lo slider */
#<?= $recond_id; ?> .recond-img-wrap{position:relative;border-radius:16px;overflow:hidden;background:var(--ks-gray-100);}
#<?= $recond_id; ?> .recond-img{display:block;width:100%;height:220px;object-fit:contain;padding:16px;}
#<?= $recond_id; ?> .offer-badge{position:absolute;top:50px;right:10px;width:56px;height:56px;border-radius:50%;
  display:grid;place-items:center;background:var(--ks-orange);color:#fff;font-weight:800;box-shadow:0 8px 18px rgba(255,122,0,.25);}
#<?= $recond_id; ?> .recond-chips{position:absolute;inset:0;pointer-events:none}
#<?= $recond_id; ?> .recond-chips-left{position:absolute;top:10px;left:10px}
#<?= $recond_id; ?> .recond-chips-right{position:absolute;top:10px;right:10px}
#<?= $recond_id; ?> .recond-chip{display:inline-block;padding:6px 10px;border-radius:999px;font-weight:800;font-size:.8rem;background:#fff;box-shadow:var(--ks-shadow-sm);}
#<?= $recond_id; ?> .recond-chip.storage{color:#111;border:1px solid var(--ks-gray-200);}
#<?= $recond_id; ?> .recond-chip.grade{color:#fff;border:0}
#<?= $recond_id; ?> .recond-chip.grade.grade-new{background:#EF4444}
#<?= $recond_id; ?> .recond-chip.grade.grade-expo{background:#f59e0b}
#<?= $recond_id; ?> .recond-chip.grade.grade-a-plus{background:#16a34a}
#<?= $recond_id; ?> .recond-chip.grade.grade-a{background:#22c55e}
#<?= $recond_id; ?> .recond-chip.grade.grade-b{background:#6366f1}
#<?= $recond_id; ?> .recond-chip.grade.grade-c{background:#0ea5e9}
#<?= $recond_id; ?> .recond-card{background:#fff;border:1px solid var(--ks-gray-200);border-radius:18px;overflow:hidden;box-shadow:var(--ks-shadow-sm);}
#<?= $recond_id; ?> .recond-body{padding:12px 14px 16px}
#<?= $recond_id; ?> .recond-title{font-size:1rem;font-weight:800;margin:4px 0 6px}
#<?= $recond_id; ?> .recond-price{display:flex;align-items:baseline;gap:8px}
#<?= $recond_id; ?> .recond-price .price-old{text-decoration:line-through;color:var(--ks-gray-500)}
#<?= $recond_id; ?> .recond-price .price-current{font-weight:900;color:var(--ks-orange)}
#<?= $recond_id; ?> .btn-wa{display:inline-flex;align-items:center;justify-content:center;gap:8px;height:44px;border-radius:12px;background:var(--ks-orange);color:#fff;font-weight:800;border:2px solid transparent}
#<?= $recond_id; ?> .btn-wa:hover{filter:brightness(.98);box-shadow:0 8px 22px rgba(0,0,0,.1)}
/* skeleton */
#<?= $recond_id; ?> .is-skeleton .sk-block{height:220px;background:linear-gradient(90deg,#eee,#f5f5f5,#eee);animation:sk 1.2s infinite}
#<?= $recond_id; ?> .is-skeleton .sk-line{height:14px;margin:10px 0;background:linear-gradient(90deg,#eee,#f5f5f5,#eee);animation:sk 1.2s infinite;border-radius:8px}
#<?= $recond_id; ?> .is-skeleton .sk-line.w-70{width:70%} #<?= $recond_id; ?> .is-skeleton .sk-line.w-40{width:40%}
@keyframes sk{0%{background-position:-120px 0}100%{background-position:120px 0}}
</style>
