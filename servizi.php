<?php
/**
 * Key Soft Italia - Servizi
 * Pagina indice dei servizi
 */

if (!defined('BASE_PATH')) {
    define('BASE_PATH', __DIR__ . '/');
}

require_once BASE_PATH . 'config/config.php';

// SEO Meta
$page_title       = "Servizi a Ginosa: riparazioni, vendita, IT, web e telefonia | Key Soft Italia";
$page_description = "Riparazioni, vendita e ricondizionati, consulenza IT, siti web e app, LibertyCommerce, telefonia, luce e gas: tutti i servizi di Key Soft Italia a Ginosa.";
$page_keywords    = "servizi informatici ginosa, riparazioni smartphone, assistenza pc, sviluppo web taranto";

// Breadcrumbs (se hai un partial per renderli puoi usarlo)
$breadcrumbs = [
    ['label' => 'Servizi', 'url' => 'servizi.php']
];

// Canonical URL (fallback sicuro)
$is_https      = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443);
$canonical_url = ($is_https ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

// Dataset servizi (usato sia per HTML che JSON-LD)

$services = [
  [
    'title'    => 'Riparazioni & Assistenza',
    'subtitle' => 'Diagnosi rapida, interventi board-level e ricambi premium',
    'url'      => 'servizi/riparazioni.php',
    'name'     => 'Riparazioni',
    'line'     => 'Smartphone, computer e console, con diagnosi gratuita.',
    'image'    => 'riparazioni'
  ],
  [
    'title'    => 'Vendita & Ricondizionati',
    'subtitle' => 'Nuovo e ricondizionato certificato in laboratorio, con pellicole MyShape e setup incluso',
    'url'      => 'servizi/vendita.php',
    'name'     => 'Vendita e ricondizionati',
    'line'     => 'Nuovo e ricondizionato certificato, pronto all’uso.',
    'image'    => 'vendita'
  ],
  [
    'title'    => 'Consulenza IT & Reti (B2B)',
    'subtitle' => 'Reti, sicurezza, backup/DR e videosorveglianza',
    'url'      => 'servizi/consulenza-it.php',
    'name'     => 'Consulenza IT e reti',
    'line'     => 'Reti, sicurezza, backup e videosorveglianza per aziende.',
    'image'    => 'consulenza-it'
  ],
  [
    'title'    => 'Sviluppo Web e App',
    'subtitle' => 'Siti, e-commerce e web app su misura, responsive e ottimizzati per la ricerca',
    'url'      => 'servizi/sviluppo-web.php',
    'name'     => 'Siti web e app',
    'line'     => 'Siti, e-commerce e web app su misura.',
    'image'    => 'sviluppo-web'
  ],
  [
    'title'    => 'LibertyCommerce e Comanda Facile',
    'subtitle' => 'Gestionali per negozi, aziende e ristoranti: installazione, formazione e supporto',
    'url'      => 'servizi/liberty-commerce.php',
    'name'     => 'LibertyCommerce',
    'line'     => 'Gestionali per negozi e ristoranti, con Comanda Facile.',
    'image'    => 'gestionali'
  ],
  [
    'title'    => 'Telefonia Fissa e Mobile',
    'subtitle' => 'Attivazione SIM, portabilità e fibra per la casa',
    'url'      => 'servizi/telefonia.php',
    'name'     => 'Telefonia',
    'line'     => 'SIM, portabilità e fibra per la casa.',
    'image'    => 'telefonia'
  ],
  [
    'title'    => 'Forniture Domestiche',
    'subtitle' => 'Confronto e attivazione di offerte luce e gas',
    'url'      => 'servizi/forniture.php',
    'name'     => 'Luce e gas',
    'line'     => 'Confrontiamo le offerte e ti aiutiamo a risparmiare.',
    'image'    => 'forniture'
  ]
];

?>
<!DOCTYPE html>
<html lang="it">
<head>
    <?php include 'includes/head.php'; ?>
    <link rel="stylesheet" href="<?php echo asset_version('css/pages/servizi-index.css'); ?>">
</head>
<body class="site-aligned">
<?php include 'includes/header.php'; ?>
<main id="main-content">

<section class="svc-head" aria-labelledby="svc-title" data-glide-from>
  <picture class="svc-head-still">
    <source media="(max-width:767px)" srcset="<?= asset('img/servizi/negozio-mobile.webp'); ?>">
    <img src="<?= asset('img/servizi/negozio-desktop.webp'); ?>" alt="Il bancone e il laboratorio di un negozio di tecnologia la sera, immagine illustrativa" width="1920" height="1080" fetchpriority="high" decoding="async">
  </picture>
  <span class="svc-head-grade" aria-hidden="true"></span>
  <div class="container svc-head-copy">
    <h1 id="svc-title">Cosa facciamo <span>per te.</span></h1>
    <p>Per privati e aziende, dal negozio di Ginosa.</p>
    <div class="hero-breadcrumb"><?= generate_breadcrumbs($breadcrumbs); ?></div>
  </div>
</section>

<section class="svc-reel" id="services-grid" data-glide-to aria-labelledby="svc-reel-title" style="--svc-count:<?= count($services); ?>">
  <h2 id="svc-reel-title" class="visually-hidden">I nostri servizi</h2>
  <div class="svc-reel-stage">
    <div class="svc-reel-frames" aria-hidden="true">
      <?php foreach ($services as $i => $service): ?>
      <picture>
        <source media="(max-width:860px)" srcset="<?= asset('img/servizi/' . $service['image'] . '-mobile.webp'); ?>">
        <img src="<?= asset('img/servizi/' . $service['image'] . '-desktop.webp'); ?>" alt="" width="1500" height="1200" loading="<?= $i < 2 ? 'eager' : 'lazy' ?>" decoding="async">
      </picture>
      <?php endforeach; ?>
    </div>
    <span class="svc-lens" aria-hidden="true"><i></i></span>
    <div class="svc-reel-ui">
      <p class="svc-reel-count" aria-hidden="true"><b>01</b><span>/ <?= str_pad((string)count($services), 2, '0', STR_PAD_LEFT); ?></span><i class="svc-reel-meter"></i></p>
      <ol class="svc-reel-list">
        <?php foreach ($services as $i => $service): ?>
        <li class="svc-reel-item" data-i="<?= $i ?>">
          <a href="<?= url($service['url']); ?>">
            <span class="svc-reel-num"><?= str_pad((string)($i + 1), 2, '0', STR_PAD_LEFT); ?></span>
            <span class="svc-reel-name"><?= htmlspecialchars($service['name']); ?></span>
          </a>
          <span class="svc-reel-frame" aria-hidden="true"><img src="<?= asset('img/servizi/' . $service['image'] . '-desktop.webp'); ?>" alt="" width="1500" height="1200" loading="lazy" decoding="async"></span>
          <p class="svc-reel-line"><?= htmlspecialchars($service['line']); ?></p>
          <a class="svc-reel-cta" href="<?= url($service['url']); ?>" tabindex="-1" aria-hidden="true">Scopri il servizio <i class="ri-arrow-right-up-line"></i></a>
        </li>
        <?php endforeach; ?>
      </ol>
    </div>
  </div>
</section>

<?php
// Numeri indicativi su base annuale (aggiornare quando ci sono i dati definitivi).
$metrics = [
  ['value' => 2000, 'label' => 'dispositivi riparati'],
  ['value' => 180,  'label' => 'ricondizionati consegnati'],
  ['value' => 40,   'label' => 'clienti business seguiti'],
  ['value' => 6,    'label' => 'impianti di videosorveglianza'],
  ['value' => 5,    'label' => 'siti web consegnati'],
];
?>
<section class="svc-numbers" aria-labelledby="svc-numbers-title">
  <div class="container">
    <h2 id="svc-numbers-title">Un anno <span>di lavoro.</span></h2>
    <dl class="svc-numbers-grid">
      <?php foreach ($metrics as $m): ?>
      <div>
        <dt><?= htmlspecialchars($m['label']); ?></dt>
        <dd><span data-count="<?= (int)$m['value']; ?>"><?= number_format((int)$m['value'], 0, ',', '.'); ?></span>+</dd>
      </div>
      <?php endforeach; ?>
    </dl>
    <p class="svc-numbers-note">Numeri indicativi su base annuale.</p>
  </div>
</section>

<section class="svc-facts" aria-label="Perché noi">
  <div class="container svc-facts-grid">
    <p><strong>Dal 2004</strong> a Ginosa</p>
    <p><strong>Diagnosi gratuita</strong> e preventivo prima di intervenire</p>
    <p><strong>Garanzia scritta</strong> di 12 mesi sulle riparazioni</p>
  </div>
</section>

<section class="svc-contact" aria-labelledby="svc-contact-title">
  <div class="container">
    <h2 id="svc-contact-title">Da dove partiamo?<br><span>Parliamone.</span></h2>
    <div class="svc-contact-actions">
      <a class="svc-button" href="<?= url('preventivo.php'); ?>">Richiedi un preventivo <i class="ri-arrow-right-up-line" aria-hidden="true"></i></a>
      <a class="svc-link" href="<?= whatsapp_link('Ciao Key Soft Italia, vorrei informazioni sui vostri servizi'); ?>" target="_blank" rel="noopener">Scrivici su WhatsApp <i class="ri-whatsapp-line" aria-hidden="true"></i></a>
      <a class="svc-link" href="<?= url('contatti.php'); ?>">Contatti e orari <i class="ri-arrow-right-up-line" aria-hidden="true"></i></a>
    </div>
  </div>
</section>

<script>
// The opening fills the first screen under the header as it is at the top of the page. Read once,
// so the opening never changes height when the header compacts on scroll.
(function () {
  var h = document.querySelector('.header');
  if (h && scrollY < 5) document.body.style.setProperty('--svc-head-offset', h.offsetHeight + 'px');
})();

// One conductor: native scroll through the pinned chapter turns a reel of services. Each service
// holds for reading, then the reel turns and the photograph dissolves into the next. Reversible.
(function () {
  var reel = document.querySelector('.svc-reel');
  if (!reel || matchMedia('(prefers-reduced-motion: reduce)').matches) return;
  var stage = reel.querySelector('.svc-reel-stage'), items = [].slice.call(reel.querySelectorAll('.svc-reel-item'));
  var frames = [].slice.call(reel.querySelectorAll('.svc-reel-frames img')), n = items.length;
  var count = reel.querySelector('.svc-reel-count b'), header = document.querySelector('.header');
  var clamp = function (v, a, b) { return Math.min(b, Math.max(a, v)); };
  var ease = function (t) { return t < .5 ? 4 * t * t * t : 1 - Math.pow(-2 * t + 2, 3) / 2; };
  var raf = 0, shown = -1, minScale = .4, gap = 18, sizes = [];
  reel.classList.add('is-reel');
  // Each item is measured once per layout: the name at full size, and what the active state adds
  // (line and button). The reel then stacks real heights, so long or wrapped names never collide.
  function measure() {
    document.body.style.setProperty('--svc-header', (header ? header.offsetHeight : 0) + 'px');
    var mobile = innerWidth <= 860;
    minScale = mobile ? .5 : .4; gap = mobile ? 12 : 18;
    sizes = items.map(function (it) {
      var name = it.querySelector('a').offsetHeight;
      return { name: name, extra: Math.max(0, it.scrollHeight - name) };
    });
  }
  function position() {
    var r = reel.getBoundingClientRect(), travel = reel.offsetHeight - stage.offsetHeight;
    var p = clamp((-r.top + (header ? header.offsetHeight : 0)) / travel, 0, 1);
    var raw = p * (n - 1), k = Math.min(n - 2, Math.floor(raw)), f = raw - k;
    // Hold the first and last third of each step, turn in the middle third.
    return { p: p, at: n === 1 ? 0 : k + ease(clamp((f - .32) / .36, 0, 1)) };
  }
  function update() {
    raf = 0;
    var s = position(), at = s.at, active = Math.round(at);
    reel.style.setProperty('--svc-progress', s.p.toFixed(4));
    var tops = [], y = 0;
    items.forEach(function (it, i) {
      var near = clamp(1 - Math.abs(i - at), 0, 1), sc = minScale + (1 - minScale) * near;
      tops.push(y);
      y += sizes[i].name * sc + sizes[i].extra * Math.pow(near, 2) + gap;
    });
    var k = Math.min(n - 1, Math.floor(at)), focus = k < n - 1 ? tops[k] + (tops[k + 1] - tops[k]) * (at - k) : tops[k];
    items.forEach(function (it, i) {
      var ad = Math.abs(i - at), near = clamp(1 - ad, 0, 1);
      it.style.transform = 'translate3d(0,' + (tops[i] - focus).toFixed(1) + 'px,0)';
      it.style.opacity = clamp(1 - ad * .32, 0, 1).toFixed(3);
      it.style.setProperty('--near', near.toFixed(3));
      it.style.visibility = ad > 3.2 ? 'hidden' : 'visible';
    });
    frames.forEach(function (fr, i) {
      var ad = Math.abs(i - at), o = clamp(1 - ad * 1.6, 0, 1);
      fr.style.opacity = o.toFixed(3);
      fr.style.transform = 'scale(' + (1 + Math.min(ad, 1) * .06).toFixed(4) + ')';
      fr.style.visibility = o > 0 ? 'visible' : 'hidden';
    });
    if (active !== shown) {
      shown = active;
      items.forEach(function (it, i) { it.classList.toggle('is-active', i === active); });
      if (count) count.textContent = String(active + 1).padStart(2, '0');
    }
  }
  function schedule() { if (!raf) raf = requestAnimationFrame(update); }
  // Keyboard: focusing a service scrolls the reel to it.
  reel.addEventListener('focusin', function (e) {
    var it = e.target.closest('.svc-reel-item'); if (!it) return;
    var i = +it.dataset.i, travel = reel.offsetHeight - stage.offsetHeight;
    var top = reel.getBoundingClientRect().top + scrollY - (header ? header.offsetHeight : 0) + travel * (i / Math.max(1, n - 1));
    scrollTo({ top: top, behavior: 'smooth' });
  });
  addEventListener('scroll', schedule, { passive: true });
  addEventListener('resize', function () { measure(); schedule(); });
  // The header compacts once the page scrolls: follow its real height.
  if (header && 'ResizeObserver' in window) new ResizeObserver(function () { measure(); schedule(); }).observe(header);
  measure(); update();
  if (document.fonts && document.fonts.ready) document.fonts.ready.then(function () { measure(); update(); });
  // As with the home film, WhatsApp and back-to-top step aside while the chapter is pinned.
  if ('IntersectionObserver' in window) new IntersectionObserver(function (en) {
    document.body.classList.toggle('svc-stage-in-view', en[0].isIntersecting);
  }, { rootMargin: '-30% 0px -30% 0px' }).observe(reel);
})();

// Numbers count up once when they come into view; the final value is already in the HTML.
(function () {
  var nums = document.querySelectorAll('[data-count]');
  if (!nums.length || !('IntersectionObserver' in window) || matchMedia('(prefers-reduced-motion: reduce)').matches) return;
  var fmt = { format: function (n) { return String(n).replace(/\B(?=(\d{3})+(?!\d))/g, '.'); } };
  function run(el) {
    var end = +el.dataset.count, t0 = performance.now(), dur = 1400;
    (function tick(now) {
      var k = Math.min(1, (now - t0) / dur), e = 1 - Math.pow(1 - k, 4);
      el.textContent = fmt.format(Math.round(end * e));
      if (k < 1) requestAnimationFrame(tick);
    })(t0);
  }
  var io = new IntersectionObserver(function (entries) {
    entries.forEach(function (en) { if (en.isIntersecting) { run(en.target); io.unobserve(en.target); } });
  }, { rootMargin: '0px 0px -15% 0px' });
  nums.forEach(function (el) { el.textContent = '0'; io.observe(el); });
})();
</script>

<?php
// JSON-LD ItemList per i servizi
$ld_services = [
    '@context' => 'https://schema.org',
    '@type'    => 'ItemList',
    'name'     => 'Servizi Key Soft Italia',
    'itemListElement' => array_map(function ($svc, $idx) {
        return [
            '@type'    => 'ListItem',
            'position' => $idx + 1,
            'item'     => [
                '@type'        => 'Service',
                'name'         => $svc['title'],
                'description'  => $svc['subtitle'],
                'url'          => url($svc['url']),
                'serviceType'  => $svc['title']
            ]
        ];
    }, $services, array_keys($services))
];
?>
<script type="application/ld+json">
<?php echo json_encode($ld_services, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT); ?>
</script>

</main>
<?php include 'includes/footer.php'; ?>
<script src="<?= asset_version('js/opening-glide.js'); ?>" defer></script>

</body>
</html>
