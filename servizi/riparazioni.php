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
$page_title = "Servizi Riparazione Computer e Smartphone - Key Soft Italia";
$page_description = "Riparazione professionale di computer, notebook, smartphone, tablet e console a Ginosa. Interventi rapidi e garantiti con componenti originali.";
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
    ['label' => 'Riparazioni', 'url' => 'riparazioni.php']
];
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <?php include '../includes/head.php'; ?>
    <!-- CSS di pagina -->
    <link rel="stylesheet" href="<?php echo asset_version('css/pages/riparazioni.css'); ?>">
</head>
<body>
    
    <!-- Header -->
    <?php include '../includes/header.php'; ?>

    <!-- HERO -->
    <section class="hero hero-secondary text-center">
      <div class="hero-pattern"></div>
      <div class="container position-relative z-2" data-aos="fade-up">
        <div class="hero-icon mb-3" data-aos="zoom-in">
          <i class="ri-tools-line"></i>
        </div>
        <h1 class="hero-title text-white" data-aos="fade-up" data-aos-delay="100">
          Servizi di <span class="text-gradient">Riparazione</span>
        </h1>
        <p class="hero-subtitle" data-aos="fade-up" data-aos-delay="200">
          Riparazioni professionali con componenti di alta qualità e garanzia di 12 mesi
        </p>
        <div class="hero-cta" data-aos="fade-up" data-aos-delay="300">
          <a href="#computer-notebook" class="btn btn-primary btn-lg smooth-scroll" aria-label="Scopri i nostri servizi di riparazione">
            <i class="ri-arrow-down-line me-1"></i> Scopri i Servizi
          </a>
        </div>
        <div class="hero-breadcrumb mt-4" data-aos="fade-up" data-aos-delay="400">
          <?php echo generate_breadcrumbs($breadcrumbs); ?>
        </div>
      </div>
    </section>

    <!-- Computer & Notebook (White Background) -->
    <section id="computer-notebook" class="service-category">
        <div class="container">
            <div class="section-header text-center mb-5" data-aos="fade-up">
                <h2 class="section-title">Riparazione <span class="text-gradient">Computer e Notebook</span></h2>
                <p class="section-subtitle">Interventi rapidi, sicuri e professionali su PC fisso e portatili di ogni marca</p>
            </div>
            
            <div class="row g-4 mt-2">
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="ri-computer-line"></i>
                        </div>
                        <h4>Sostituzione Hardware</h4>
                        <p>RAM, SSD ad alte prestazioni, schede video, alimentatori e schede madri.</p>
                        <div class="price-badge">Da €30</div>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="ri-bug-line"></i>
                        </div>
                        <h4>Rimozione Virus</h4>
                        <p>Pulizia totale da virus, malware, adware e ottimizzazione completa delle prestazioni.</p>
                        <div class="price-badge">€40</div>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="ri-install-line"></i>
                        </div>
                        <h4>Formattazione e Ripristino</h4>
                        <p>Installazione pulita del sistema operativo Windows o macOS, driver e programmi essenziali.</p>
                        <div class="price-badge">€50</div>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="ri-macbook-line"></i>
                        </div>
                        <h4>Riparazione Notebook</h4>
                        <p>Sostituzione display rotto, tastiera usurata, batterie interne, cerniere e connettori.</p>
                        <div class="price-badge">Da €60</div>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="ri-database-2-line"></i>
                        </div>
                        <h4>Recupero Dati</h4>
                        <p>Recupero di file e cartelle importanti da memorie guaste o cancellate accidentalmente.</p>
                        <div class="price-badge">Da €100</div>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="ri-speed-line"></i>
                        </div>
                        <h4>Upgrade & Velocizzazione</h4>
                        <p>Installazione SSD ultra-veloci e aumento RAM per ridare vita al tuo vecchio computer.</p>
                        <div class="price-badge">Da €35</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Smartphone & Tablet (Light Background with Pattern) -->
    <section id="smartphone-tablet" class="section section-values bg-light">
        <div class="container position-relative z-2">
            <div class="section-header text-center mb-5" data-aos="fade-up">
                <h2 class="section-title">Riparazione <span class="text-gradient">Smartphone e Tablet</span></h2>
                <p class="section-subtitle">Riparazioni express su schermi, batterie e problemi di accensione o ricarica</p>
            </div>
            
            <div class="row g-4 mt-2">
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="ri-smartphone-line"></i>
                        </div>
                        <h4>Sostituzione Display</h4>
                        <p>Schermo rotto, touch screen non responsivo o problemi di retroilluminazione LCD.</p>
                        <div class="price-badge">Da €80</div>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="ri-battery-charge-line"></i>
                        </div>
                        <h4>Sostituzione Batteria</h4>
                        <p>Sostituzione immediata di batterie degradate, gonfie o che non tengono la carica.</p>
                        <div class="price-badge">Da €40</div>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="ri-camera-lens-line"></i>
                        </div>
                        <h4>Riparazione Fotocamera</h4>
                        <p>Risoluzione di problemi di autofocus, fotocamera nera o vetrino posteriore scheggiato.</p>
                        <div class="price-badge">Da €50</div>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="ri-volume-up-line"></i>
                        </div>
                        <h4>Speaker e Microfono</h4>
                        <p>Riparazione altoparlanti gracchianti, audio debole o microfono non funzionante.</p>
                        <div class="price-badge">Da €35</div>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="ri-usb-line"></i>
                        </div>
                        <h4>Connettore di Ricarica</h4>
                        <p>Sostituzione porta di ricarica USB-C o Lightning danneggiata che rende difficile la ricarica.</p>
                        <div class="price-badge">Da €45</div>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="ri-drop-line"></i>
                        </div>
                        <h4>Danni da Liquidi</h4>
                        <p>Trattamento ad ultrasuoni, rimozione ossido e ripristino di dispositivi bagnati.</p>
                        <div class="price-badge">Da €70</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Console, TV e Micro-saldature (White Background) -->
    <section id="altre-riparazioni" class="service-category">
        <div class="container">
            <div class="section-header text-center mb-5" data-aos="fade-up">
                <h2 class="section-title">Console, TV e <span class="text-gradient">Micro-saldature</span></h2>
                <p class="section-subtitle">Interventi board-level su micro-componenti, Smart TV e console di gioco</p>
            </div>
            
            <div class="row g-4 mt-2 justify-content-center">
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="ri-cpu-line"></i>
                        </div>
                        <h4>Micro-saldature Board-Level</h4>
                        <p>Riparazioni elettroniche avanzate su schede madri, cortocircuiti e connettori saldati.</p>
                        <div class="price-badge">Su Preventivo</div>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="ri-gamepad-line"></i>
                        </div>
                        <h4>Console Gaming</h4>
                        <p>Riparazioni e pulizia avanzata di PlayStation, Xbox e Nintendo Switch (HDMI, lettori, pasta termica).</p>
                        <div class="price-badge">Da €40</div>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="ri-tv-line"></i>
                        </div>
                        <h4>Smart TV & Monitor</h4>
                        <p>Risoluzione problemi di alimentazione, retroilluminazione LED e schede video TV.</p>
                        <div class="price-badge">Su Preventivo</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- WARRANTY & QUALITY (Styled like section-mission of chi-siamo.php) -->
    <section id="garanzia" class="section section-mission text-white" style="--mission-bg: url('<?= asset('img/mission-lab.png'); ?>');">
      <div class="mission-overlay"></div>
      <div class="container position-relative">
        <div class="row align-items-center">
          <div class="col-lg-7" data-aos="fade-right">
            <h2 class="mission-title">
              <i class="ri-shield-check-line me-2"></i> Garanzia <span class="text-highlight">Totale</span>
            </h2>
            <p class="mission-text">
              Ogni riparazione effettuata nel nostro laboratorio di Ginosa è coperta da una <strong>garanzia scritta di 12 mesi</strong>.
              Utilizziamo esclusivamente ricambi di qualità certificata e sottoponiamo ogni dispositivo a severi test di collaudo prima della consegna.
              La tua tranquillità è la nostra priorità assoluta.
            </p>
            <a href="#section-cta" class="btn btn-light mt-3 smooth-scroll" aria-label="Prenota riparazione o richiedi preventivo">
              Richiedi Assistenza <i class="ri-arrow-right-line"></i>
            </a>
          </div>
          <div class="col-lg-5 mt-5 mt-lg-0" data-aos="fade-left">
            <div class="mission-stats-grid">
              <div class="mission-stat">
                <i class="ri-shield-user-line"></i>
                <div class="stat-value">12 Mesi</div>
                <div class="stat-label">Garanzia Scritta</div>
              </div>
              <div class="mission-stat">
                <i class="ri-verified-badge-line"></i>
                <div class="stat-value">100%</div>
                <div class="stat-label">Ricambi Certificati</div>
              </div>
              <div class="mission-stat">
                <i class="ri-time-line"></i>
                <div class="stat-value">24/48h</div>
                <div class="stat-label">Riparazioni Express</div>
              </div>
              <div class="mission-stat">
                <i class="ri-thumb-up-line"></i>
                <div class="stat-value">100%</div>
                <div class="stat-label">Garanzia Servizi</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Process Timeline -->
    <section id="come-funziona" class="section section-values bg-light">
        <div class="container position-relative z-2">
            <div class="section-header text-center mb-5" data-aos="fade-up">
                <h2 class="section-title">Come Funziona il <span class="text-gradient">Servizio</span></h2>
                <p class="section-subtitle">Il percorso semplice e trasparente dal guasto alla consegna del tuo dispositivo</p>
            </div>
            
            <div class="process-timeline mt-5">
                <div class="process-step" data-aos="fade-right">
                    <div class="process-number">1</div>
                    <div class="process-content">
                        <h4>Diagnosi Gratuita</h4>
                        <p>Analizziamo accuratamente il problema del tuo dispositivo e formuliamo un preventivo chiaro e trasparente.</p>
                    </div>
                </div>
                
                <div class="process-step" data-aos="fade-left">
                    <div class="process-number">2</div>
                    <div class="process-content">
                        <h4>Accettazione Preventivo</h4>
                        <p>Procediamo con la riparazione solo dopo la tua espressa approvazione. Nessuna sorpresa finale.</p>
                    </div>
                </div>
                
                <div class="process-step" data-aos="fade-right">
                    <div class="process-number">3</div>
                    <div class="process-content">
                        <h4>Riparazione Professionale</h4>
                        <p>I nostri tecnici qualificati sostituiscono i componenti guasti in laboratorio elettrostatico attrezzato.</p>
                    </div>
                </div>
                
                <div class="process-step" data-aos="fade-left">
                    <div class="process-number">4</div>
                    <div class="process-content">
                        <h4>Collaudo e Consegna</h4>
                        <p>Sottoponiamo il dispositivo a un collaudo finale approfondito per garantire il perfetto funzionamento.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Clean Section -->
    <section class="section section-cta-clean text-center" data-aos="fade-up" id="section-cta">
        <div class="container">
            <h2 class="cta-title">Hai Bisogno di una Riparazione?</h2>
            <p class="cta-subtitle">Contattaci subito per una stima gratuita o prenota l'assistenza online</p>
            <div class="cta-buttons">
                <a href="<?php echo url('preventivo.php'); ?>" class="btn btn-primary btn-lg" aria-label="Richiedi un preventivo gratuito">
                    <i class="ri-file-list-3-line me-2"></i> Richiedi Preventivo
                </a>
                <a href="<?php echo url('assistenza.php'); ?>" class="btn btn-outline-primary btn-lg" aria-label="Contattaci ora">
                    <i class="ri-customer-service-2-line me-2"></i> Assistenza Clienti
                </a>
                <a href="<?php echo whatsapp_link('Salve, avrei bisogno di una riparazione'); ?>" 
                   class="btn btn-success btn-lg" target="_blank" rel="noopener noreferrer" aria-label="Contattaci su WhatsApp">
                    <i class="ri-whatsapp-line me-2"></i> WhatsApp
                </a>
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
    <?php include '../includes/footer.php'; ?>
</body>
</html>