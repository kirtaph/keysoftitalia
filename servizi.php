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
$page_title       = "Servizi - Key Soft Italia | Riparazioni, Vendita, Assistenza, Sviluppo";
$page_description = "Scopri tutti i servizi di Key Soft Italia: riparazioni express, vendita dispositivi, assistenza informatica, sviluppo web, consulenza IT e telefonia.";
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
    'icon'     => 'ri-tools-line',
    'color'    => 'orange',
    'title'    => 'Riparazioni & Assistenza',
    'subtitle' => 'Diagnosi rapida, interventi board-level e ricambi premium',
    'url'      => 'servizi/riparazioni.php',
    'features' => [
      'Smartphone e tablet di tutti i marchi',
      'Saldature e micro-rework su scheda (board-level)',
      'Recupero dati e migrazione sicura',
      'Smart TV, monitor, console (PS/Xbox/Switch)',
      'Pulizia avanzata per danni da liquidi',
      'Servizio express 24h* su ricambi disponibili'
    ],
    'badges' => ['Diagnosi Gratuita', 'Garanzia 12 mesi', 'Express 24h*']
  ],
  [
    'icon'     => 'ri-shopping-bag-line',
    'color'    => 'green',
    'title'    => 'Vendita & Ricondizionati',
    'subtitle' => 'Nuovo e Key-Renew certificato, con setup incluso',
    'url'      => 'servizi/vendita.php',
    'features' => [
      'Smartphone e laptop (nuovo/usato garantito)',
      'Ricondizionati Key-Renew testati 30+ punti',
      'Permuta usato con valutazione immediata',
      'Accessori originali e compatibili selezionati',
      'Trasferimento dati e configurazione account',
      'Garanzia fino a 12 mesi'
    ],
    'badges' => ['Prezzi Trasparenti', 'Permuta Subito', 'Garanzia']
  ],
  [
    'icon'     => 'ri-server-line',
    'color'    => 'blue',
    'title'    => 'Consulenza IT & Reti (B2B)',
    'subtitle' => 'Reti, sicurezza, backup/DR e videosorveglianza',
    'url'      => 'servizi/consulenza-it.php',
    'features' => [
      'Reti cablate/Wi-Fi aziendali e segmentazione',
      'Firewall e policy Zero-Trust',
      'Backup e Disaster Recovery on-prem/cloud',
      'Virtualizzazione e cloud ibrido',
      'Videosorveglianza (EZVIZ) e NVR',
      'Contratti di assistenza con SLA'
    ],
    'badges' => ['Supporto 24/7', 'SLA su misura', 'Audit Sicurezza']
  ],
  [
    'icon'     => 'ri-window-line',
    'color'    => 'indigo',
    'title'    => 'Sviluppo Web',
    'subtitle' => 'Siti vetrina performanti orientati alle conversioni',
    'url'      => 'servizi/sviluppo-web.php',
    'features' => [
      'Landing e siti vetrina (PHP + Bootstrap 5)',
      'UX per lead-gen: form, CTA, tracking',
      'SEO on-page base e performance tuning',
      'Integrazione form → WhatsApp/Email/CRM',
      'Blog/News e micro-aree dinamiche',
      'Assistenza hosting e deploy'
    ],
    'badges' => ['Mobile-first', 'SEO base', 'Ottimizzato Lead']
  ],
  [
    'icon'     => 'ri-apps-2-line',
    'color'    => 'teal',
    'title'    => 'App, Automazioni & LibertyCommerce',
    'subtitle' => 'Web app su misura e distribuzione gestionale LibertyCommerce',
    'url'      => 'servizi/app-automazioni.php',
    'features' => [
      'Distribuzione, setup e formazione LibertyCommerce',
      'Magazzino, documenti, POS e fatturazione elettronica',
      'Migrazione dati e personalizzazioni operative',
      'Integrazioni API: corrispettivi, energy, CRM',
      'Flussi WhatsApp (whatsappweb.js) e template',
      'Key-OS: moduli ordini/ricondizionati con ruoli'
    ],
    'badges' => ['Partner LibertyCommerce', 'Formazione', 'Migrazione Dati']
  ],
  [
    'icon'     => 'ri-sim-card-line',
    'color'    => 'red',
    'title'    => 'Telefonia & Servizi Casa',
    'subtitle' => 'Confronto offerte mobile, fibra, luce e gas',
    'url'      => 'servizi/telefonia.php',
    'features' => [
      'Attivazione SIM e portabilità numero',
      'Fibra/FTTC con verifica copertura',
      'Luce e gas, comparazione trasparente',
      'Pacchetti family e business',
      'Assistenza cambio operatore',
      'Pagamenti e rinnovi in negozio'
    ],
    'badges' => ['Tutti gli Operatori', 'Zero Costi Attivazione', 'Risparmio Reale']
  ],
  [
    'icon'     => 'ri-shield-check-line',
    'color'    => 'purple',
    'title'    => 'MyShape Protection (Plus)',
    'subtitle' => 'Protezione su misura per display e device',
    'url'      => 'servizi/myshape.php',
    'features' => [
      'Pellicole tagliate al millimetro in negozio',
      'Protezione display anti-urto/anti-graffio',
      'Applicazione professionale senza bolle',
      'Piani protezione con sostituzione agevolata',
      'Bundle consigliato con riparazioni/vendita',
      'Ampia compatibilità modelli'
    ],
    'badges' => ['Partner/Plus', 'Protezione Totale', 'Attivazione Immediata']
  ],
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

    <link rel="stylesheet" href="<?php echo asset('css/pages/servizi.css'); ?>">
</head>
<body data-aos-easing="ease-in-out" data-aos-duration="800" data-aos-once="true">
<?php include 'includes/header.php'; ?>

<!-- HERO -->
<section class="hero hero-secondary text-center">
  <div class="hero-pattern"></div>
  <div class="container position-relative z-2" data-aos="fade-up">
    <div class="hero-icon mb-3" data-aos="zoom-in">
      <i class="ri-service-line"></i>
    </div>
    <h1 class="hero-title text-white" data-aos="fade-up" data-aos-delay="100">
      I Nostri <span class="text-gradient">Servizi</span>
    </h1>
    <p class="hero-subtitle" data-aos="fade-up" data-aos-delay="200">
      Dal supporto tecnico allo sviluppo software, offriamo soluzioni integrate per <strong>privati e aziende</strong>
    </p>
    <div class="hero-cta" data-aos="fade-up" data-aos-delay="300">
      <a href="#services-grid" class="btn btn-primary btn-lg smooth-scroll" aria-label="Scopri la nostra storia">
        <i class="ri-arrow-down-line me-1"></i> Scopri i Servizi
      </a>
    </div>
    <div class="hero-breadcrumb mt-4" data-aos="fade-up" data-aos-delay="400">
      <?= generate_breadcrumbs($breadcrumbs); ?>
    </div>
  </div>
</section>

<!-- SERVICES GRID -->
<section id="services-grid" class="section section-services-grid" aria-labelledby="tutti-servizi">
  <div class="container">
    <div class="section-header text-center">
      <h2 id="tutti-servizi" class="section-title" data-aos="fade-up">Tutti i nostri servizi</h2>
      <p class="section-subtitle" data-aos="fade-up" data-aos-delay="100">
        Scegli il servizio di cui hai bisogno e scopri come possiamo aiutarti
      </p>
    </div>

    <?php $delay = 0; foreach ($services as $service): $delay += 100; ?>
      <article class="service-block mb-5" data-aos="fade-up" data-aos-delay="<?php echo (int)$delay; ?>"
               itemscope itemtype="https://schema.org/Service">
        <meta itemprop="serviceType" content="<?php echo htmlspecialchars($service['title']); ?>">

        <header class="service-block-header">
          <div class="service-block-icon bg-<?php echo htmlspecialchars($service['color']); ?>" aria-hidden="true">
            <i class="<?php echo htmlspecialchars($service['icon']); ?>"></i>
          </div>

          <div class="service-block-meta">
            <h3 class="service-block-title" itemprop="name"><?php echo htmlspecialchars($service['title']); ?></h3>
            <p class="service-block-subtitle" itemprop="description"><?php echo htmlspecialchars($service['subtitle']); ?></p>
          </div>

          <a href="<?php echo url($service['url']); ?>"
             class="btn btn-outline-<?php echo htmlspecialchars($service['color']); ?>"
             aria-label="Scopri di più su <?php echo htmlspecialchars($service['title']); ?>"
             itemprop="url">
             Scopri di più <i class="ri-arrow-right-line" aria-hidden="true"></i>
          </a>
        </header>

        <div class="service-block-content">
          <div class="row g-3" role="list">
            <?php foreach ($service['features'] as $feature): ?>
              <div class="col-md-6" role="listitem">
                <div class="service-feature">
                  <i class="ri-check-line text-green" aria-hidden="true"></i>
                  <span><?php echo htmlspecialchars($feature); ?></span>
                </div>
              </div>
            <?php endforeach; ?>
          </div>

          <div class="service-block-badges mt-4" aria-label="Punti di forza">
            <?php foreach ($service['badges'] as $badge): ?>
              <span class="badge bg-<?php echo htmlspecialchars($service['color']); ?>"><?php echo htmlspecialchars($badge); ?></span>
            <?php endforeach; ?>
          </div>
        </div>
      </article>
    <?php endforeach; ?>
  </div>
</section>

<!-- WHY CHOOSE -->
<section class="section section-why bg-light" aria-labelledby="perche-sceglierci" itemscope itemtype="https://schema.org/ItemList">
  <meta itemprop="name" content="Perché scegliere Key Soft Italia">
  <div class="container">
    <div class="section-header text-center">
      <h2 id="perche-sceglierci" class="section-title" data-aos="fade-up">Perché scegliere i nostri servizi</h2>
      <p class="section-subtitle" data-aos="fade-up" data-aos-delay="100">Valore reale, tempi chiari e supporto che non ti molla</p>
    </div>

    <div class="row g-4 mt-3 equalize" role="list">
      <?php
      $why = [
        [
          'icon' => 'ri-user-star-line',
          'title' => 'Esperienza che si vede',
          'text'  => 'Riparazioni avanzate e progetti IT/web ogni settimana: procedure rodate e risultati costanti.',
        ],
        [
          'icon' => 'ri-shield-check-line',
          'title' => 'Ricambi & Garanzia',
          'text'  => 'Componenti selezionati e garanzia fino a 12 mesi su interventi e ricondizionati.',
        ],
        [
          'icon' => 'ri-timer-flash-line',
          'title' => 'Express 24h*',
          'text'  => 'Riparazioni rapide quando il ricambio è disponibile. Ti teniamo aggiornato step-by-step.',
        ],
        [
          'icon' => 'ri-price-tag-3-line',
          'title' => 'Prezzi trasparenti',
          'text'  => 'Preventivo chiaro prima di partire: nessuna sorpresa, solo ciò che serve davvero.',
        ],
        [
          'icon' => 'ri-customer-service-2-line',
          'title' => 'Supporto continuo',
          'text'  => 'WhatsApp, telefono e negozio: assistenza prima, durante e dopo l’intervento.',
        ],
        [
          'icon' => 'ri-briefcase-4-line',
          'title' => 'Per privati e aziende',
          'text'  => 'Dalla riparazione singola a contratti con SLA, backup/DR e soluzioni su misura.',
        ],
      ];

      $d = 0;
      foreach ($why as $idx => $item):
        $d += 100;
      ?>
        <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="<?php echo (int)$d; ?>" role="listitem" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
          <meta itemprop="position" content="<?php echo $idx + 1; ?>">
          <div class="why-card">
            <div class="why-icon" aria-hidden="true"><i class="<?php echo $item['icon']; ?>"></i></div>
            <h4 class="why-title" itemprop="name"><?php echo $item['title']; ?></h4>
            <p class="why-text" itemprop="description"><?php echo $item['text']; ?></p>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <p class="why-note mt-4" aria-label="Nota tempi">
      *Tempi indicativi: l’express 24h dipende dalla disponibilità dei ricambi e dalla coda lavori.
    </p>
  </div>
</section>

<?php
// Metriche "proof" (modifica i valori quando hai i dati definitivi)
$metrics = [
  ['icon' => 'ri-settings-4-line', 'label' => 'Dispositivi riparati',   'value' => 2000,  'suffix' => '+'],
  ['icon' => 'ri-store-2-line',    'label' => 'Ricondizionati consegnati',   'value' => 180,  'suffix' => '+'],
  ['icon' => 'ri-briefcase-4-line','label' => 'Clienti business seguiti',    'value' => 40,   'suffix' => '+'],
  ['icon' => 'ri-window-line',     'label' => 'Siti web consegnati',         'value' => 5,   'suffix' => '+'],  // portfolio in crescita
  ['icon' => 'ri-apps-2-line',     'label' => 'Impianti Videosorveglianza',    'value' => 6,    'suffix' => '+'],  // onesti sullo stato
];
?>

<section class="section section-proof bg-gradient-orange">
  <div class="container position-relative">
    <div class="section-header text-center">
      <h2 class="section-title text-white">Alcuni dei nostri numeri</h2>
      <p class="section-subtitle text-white-80">Numeri indicativi su base annuale</p>
    </div>

    <div class="row g-4 justify-content-center equalize">
      <?php foreach ($metrics as $m): ?>
        <div class="col-6 col-md-4 col-lg-2">
          <div class="proof-card">
            <div class="proof-icon" aria-hidden="true"><i class="<?php echo $m['icon']; ?>"></i></div>
            <div class="proof-value">
              <span class="num" data-target="<?php echo (int)$m['value']; ?>"><?php echo (int)$m['value']; ?></span><span class="suffix"><?php echo htmlspecialchars($m['suffix']); ?></span>
            </div>
            <div class="proof-label"><?php echo htmlspecialchars($m['label']); ?></div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <p class="why-note mt-3 text-center text-white-70">
  </div>
</section>

<script>
// Counter che parte al primo ingresso in viewport della sezione Proof
(function(){
  const section = document.querySelector('.section-proof');
  if(!section) return;

  let started = false;

  function animateCounters(){
    if (started) return;
    started = true;

    const nums = section.querySelectorAll('.proof-card .num');
    nums.forEach(el => {
      const target = parseInt(el.dataset.target || el.textContent, 10) || 0;
      const duration = 900; // ms totali
      const start = performance.now();

      function tick(now){
        const p = Math.min(1, (now - start) / duration);
        const val = Math.floor(target * p);
        el.textContent = val;
        if(p < 1) requestAnimationFrame(tick);
        else el.textContent = target; // clamp finale
      }
      requestAnimationFrame(tick);
    });
  }

  // Usa IntersectionObserver per far partire dopo lo scroll
  if ('IntersectionObserver' in window){
    const io = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if(entry.isIntersecting){
          // piccola attesa per lasciar finire AOS sulla sezione
          setTimeout(animateCounters, 150);
          io.disconnect();
        }
      });
    }, { root:null, threshold:0.35 });
    io.observe(section);
  } else {
    // fallback: avvia dopo il load con un piccolo delay
    window.addEventListener('load', () => setTimeout(animateCounters, 300));
  }
})();
</script>




<!-- CTA -->
<section class="section section-cta text-center" aria-labelledby="cta-servizi">
  <div class="container" data-aos="zoom-in">
    <h2 id="cta-servizi" class="cta-title">Hai bisogno di più informazioni?</h2>
    <p class="cta-subtitle">Contattaci ora per un preventivo gratuito e senza impegno</p>
    <div class="cta-buttons">
      <a href="<?php echo url('preventivo.php'); ?>" class="btn btn-primary btn-lg me-2">
        <i class="ri-file-list-3-line" aria-hidden="true"></i> Richiedi Preventivo
      </a>
      <a href="<?php echo url('contatti.php'); ?>" class="btn btn-outline-primary btn-lg">
        <i class="ri-phone-line" aria-hidden="true"></i> Contattaci Ora
      </a>
    </div>
  </div>
</section>

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

<?php include 'includes/footer.php'; ?>

<!-- JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo asset('js/main.js'); ?>"></script>
<script src="https://unpkg.com/aos@next/dist/aos.js"></script>
<script>AOS.init();</script>
</body>
</html>
