<?php
/**
 * Key Soft Italia - Servizi Riparazione
 * Pagina dettaglio servizi di riparazione (Allineata a chi-siamo.php)
 */

// Define BASE_PATH if not defined
if (!defined('BASE_PATH')) {
    define('BASE_PATH', dirname(__DIR__) . '/');
}

require_once BASE_PATH . 'config/config.php';

// SEO Meta
$page_title = "Laboratorio riparazioni smartphone e computer a Ginosa | Key Soft Italia";
$page_description = "Riparazione professionale di computer, notebook, smartphone, tablet e console a Ginosa. Diagnosi gratuita, preventivo prima di intervenire e garanzia scritta di 12 mesi.";
$page_keywords = "riparazione smartphone ginosa, riparazione computer ginosa, riparazione tablet, assistenza pc, riparazione schermo iphone, cambio batteria";

$page_schema = [
    '@context' => 'https://schema.org',
    '@type' => 'Service',
    'name' => 'Riparazione Computer e Smartphone',
    'description' => $page_description,
    'provider' => [
        '@type' => 'ComputerStore',
        'name' => COMPANY_NAME,
        'image' => asset('images/logo.png'),
        'telephone' => PHONE_PRIMARY,
        'address' => [
            '@type' => 'PostalAddress',
            'streetAddress' => COMPANY_ADDRESS,
            'addressLocality' => COMPANY_CITY,
            'addressRegion' => COMPANY_PROVINCE,
            'postalCode' => COMPANY_ZIP,
            'addressCountry' => 'IT'
        ]
    ],
    'areaServed' => [
        '@type' => 'AdministrativeArea',
        'name' => COMPANY_CITY
    ]
];

// Breadcrumbs
$breadcrumbs = [
    ['label' => 'Servizi', 'url' => '../servizi.php'],
    ['label' => 'Laboratorio', 'url' => 'riparazioni.php']
];
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <?php include '../includes/head.php'; ?>
    <!-- CSS di pagina -->
    <link rel="stylesheet" href="<?php echo asset_version('css/pages/laboratorio.css'); ?>">
</head>
<body class="site-aligned laboratory-page">
    
    <!-- Header -->
    <?php include '../includes/header.php'; ?>
<main id="main-content">

<?php
$lab_repairs = [
    'Smartphone e tablet' => ['Display', 'Batteria', 'Fotocamera', 'Altoparlante e microfono', 'Connettore di ricarica', 'Danni da liquidi'],
    'Computer e notebook' => ['Componenti e schede', 'Display, tastiera e cerniere', 'Upgrade SSD e RAM', 'Recupero dati', 'Virus e malware', 'Formattazione'],
    'Console e TV' => ['Console di gioco', 'Smart TV e monitor', 'Micro-saldature sulla scheda'],
];
$lab_picture = function (string $name, string $alt, bool $priority = false): string {
    $img = fn(string $w) => asset("img/laboratorio/{$name}-{$w}.webp");
    return '<picture><source media="(max-width:767px)" srcset="' . $img('720') . '">'
        . '<img src="' . $img('1536') . '" alt="' . htmlspecialchars($alt) . '" width="1536" height="1024" decoding="async" '
        . ($priority ? 'fetchpriority="high"' : 'loading="lazy"') . '></picture>';
};
?>
    <section class="lab-hero" aria-labelledby="lab-title" data-glide-from>
      <figure class="lab-still lab-hero-still">
        <?= $lab_picture('repair-bench', 'Mani di un tecnico che interviene su uno smartphone aperto al banco, immagine illustrativa', true) ?>
        <span class="lab-grade" aria-hidden="true"><i></i></span>
        <figcaption>Immagine illustrativa</figcaption>
      </figure>
      <div class="container lab-hero-copy">
        <h1 id="lab-title">Il nostro <span>laboratorio.</span></h1>
        <p>Smartphone, computer e console: troviamo il guasto, lo ripariamo e lo collaudiamo qui a Ginosa.</p>
        <div class="lab-actions">
          <a href="<?= url('prenota-riparazione.php') ?>" class="lab-button">Prenota una riparazione <i class="ri-arrow-right-up-line" aria-hidden="true"></i></a>
          <a href="#interventi" class="lab-link">Cosa ripariamo <i class="ri-arrow-down-line" aria-hidden="true"></i></a>
        </div>
        <div class="hero-breadcrumb"><?php echo generate_breadcrumbs($breadcrumbs); ?></div>
      </div>
    </section>

    <?php
    $lab_steps = [
        ['Diagnosi', 'Diagnosi gratuita', 'Troviamo la causa del guasto e ti diamo un preventivo chiaro.'],
        ['Preventivo', 'Decidi tu', 'Ripariamo solo dopo il tuo sì al preventivo. Nessuna sorpresa alla fine.'],
        ['Riparazione', 'Ripariamo in laboratorio', 'Tecnici qualificati sostituiscono i componenti guasti su banchi con protezione antistatica.'],
        ['Collaudo', 'Collaudo e consegna', 'Prima di riconsegnartelo controlliamo che tutto funzioni.'],
    ];
    $lab_film = 'img/home/cinematic-v2/chapter-04';
    ?>
    <section id="come-funziona" data-glide-to class="lab-process" aria-labelledby="come-funziona-title"
      data-film-desktop="<?= asset($lab_film . '/desktop/sequence/frame-') ?>"
      data-film-mobile="<?= asset($lab_film . '/mobile/sequence/frame-') ?>">
      <div class="lab-process-stage">
        <picture class="lab-process-poster">
          <source media="(max-width:767px)" srcset="<?= asset($lab_film . '/mobile/sequence/frame-0130.webp') ?>">
          <img src="<?= asset($lab_film . '/desktop/sequence/frame-0130.webp') ?>" alt="" width="1440" height="810" loading="lazy" decoding="async">
        </picture>
        <canvas class="lab-process-film" aria-hidden="true"></canvas>
        <span class="lab-grade" aria-hidden="true"><i></i></span>
        <div class="container lab-process-copy">
          <h2 id="come-funziona-title">Come funziona <span>la riparazione.</span></h2>
          <ol class="lab-steps">
            <?php foreach ($lab_steps as $i => [$short, $title, $text]): ?>
            <li data-step="<?= $i ?>"><h3><?= $title ?></h3><p><?= $text ?></p></li>
            <?php endforeach; ?>
          </ol>
        </div>
        <nav class="container lab-process-timeline" aria-label="Passaggi del servizio">
          <span class="lab-process-meter" aria-hidden="true"></span>
          <ol>
            <?php foreach ($lab_steps as $i => [$short]): ?>
            <li><button type="button" data-goto="<?= $i ?>"><b><?= str_pad((string)($i + 1), 2, '0', STR_PAD_LEFT) ?></b> <span><?= $short ?></span></button></li>
            <?php endforeach; ?>
          </ol>
        </nav>
      </div>
    </section>

    <section id="garanzia" class="lab-warranty" aria-labelledby="garanzia-title">
      <div class="container lab-warranty-layout">
        <div data-lab-reveal>
          <h2 id="garanzia-title">Garantito <span>per iscritto.</span></h2>
        </div>
        <dl class="lab-facts" data-lab-reveal>
          <div><dt>Garanzia scritta</dt><dd>12 mesi</dd></div>
          <div><dt>Riparazioni express</dt><dd>24/48 ore</dd></div>
          <div><dt>Diagnosi</dt><dd>Gratuita</dd></div>
          <div><dt>Ricambi</dt><dd>Certificati</dd></div>
        </dl>
      </div>
    </section>

    <section id="interventi" class="lab-repairs" aria-labelledby="interventi-title">
      <figure class="lab-still lab-band">
        <?= $lab_picture('notebook-upgrade', 'Installazione di un SSD su un notebook aperto, immagine illustrativa') ?>
        <span class="lab-grade" aria-hidden="true"><i></i></span>
        <figcaption>Immagine illustrativa</figcaption>
      </figure>
      <div class="container">
        <h2 id="interventi-title" data-lab-reveal>Cosa <span>ripariamo.</span></h2>
        <div class="lab-repair-index" data-lab-reveal>
          <?php foreach ($lab_repairs as $device => $repairs): ?>
          <div class="lab-repair-group">
            <h3><?= $device ?></h3>
            <ul role="list">
              <?php foreach ($repairs as $repair): ?><li><?= $repair ?></li><?php endforeach; ?>
            </ul>
          </div>
          <?php endforeach; ?>
        </div>
        <p class="lab-repair-note" data-lab-reveal>Il tuo guasto non è qui? <a href="<?= whatsapp_link('Salve, vorrei sapere se riparate questo guasto') ?>" target="_blank" rel="noopener noreferrer">Scrivicelo su WhatsApp <i class="ri-arrow-right-up-line" aria-hidden="true"></i></a></p>
      </div>
    </section>

    <section id="section-cta" class="lab-contact" aria-labelledby="lab-contact-title">
      <div class="container">
        <h2 id="lab-contact-title">Hai bisogno di<br><span>una riparazione?</span></h2>
        <div class="lab-contact-bottom">
          <p>Prenota online, chiedi un preventivo o scrivici. La diagnosi è gratuita.</p>
          <div class="lab-contact-actions">
            <a href="<?= url('prenota-riparazione.php') ?>" class="lab-button">Prenota una riparazione <i class="ri-arrow-right-up-line" aria-hidden="true"></i></a>
            <a href="<?= url('preventivo.php') ?>" class="lab-link">Richiedi un preventivo <i class="ri-arrow-right-up-line" aria-hidden="true"></i></a>
            <a href="<?= whatsapp_link('Salve, avrei bisogno di una riparazione') ?>" class="lab-link" target="_blank" rel="noopener noreferrer">Scrivici su WhatsApp <i class="ri-whatsapp-line" aria-hidden="true"></i></a>
            <a href="<?= url('assistenza.php') ?>" class="lab-link">Assistenza a domicilio o da remoto <i class="ri-arrow-right-up-line" aria-hidden="true"></i></a>
          </div>
        </div>
      </div>
    </section>

    <!-- Set BASE_URL for JavaScript -->
    <script>
        window.KS_CONFIG = {
            baseUrl: '<?php echo BASE_URL; ?>',
            whatsappNumber: '<?php echo WHATSAPP_NUMBER; ?>'
        };
    </script>

    <!-- Footer -->
    </main>
<?php include '../includes/footer.php'; ?>
<script type="module" src="<?php echo asset_version('js/pages/laboratorio.js'); ?>"></script>
<script src="<?php echo asset_version('js/opening-glide.js'); ?>" defer></script>
</body>
</html>
