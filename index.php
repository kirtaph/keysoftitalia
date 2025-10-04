<?php
/**
 * Key Soft Italia - Homepage
 * Pagina principale del sito
 */

if (!defined('BASE_PATH')) {
    define('BASE_PATH', __DIR__ . '/');
}

require_once BASE_PATH . 'config/config.php';

// SEO Meta
$page_title = "Key Soft Italia - L'universo della Tecnologia | Riparazioni, Vendita e Assistenza a Ginosa";
$page_description = "Key Soft Italia a Ginosa - Riparazioni in 24h, vendita dispositivi ricondizionati, assistenza informatica, sviluppo web. Il tuo partner tecnologico di fiducia dal 2008.";
$page_keywords = "riparazioni smartphone ginosa, assistenza computer taranto, ricondizionati, sviluppo web, key soft italia";
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <?php include 'includes/head.php'; ?>
    <title><?php echo $page_title; ?></title>
    <meta name="description" content="<?php echo $page_description; ?>">
    <meta name="keywords" content="<?php echo $page_keywords; ?>">
    <meta property="og:title" content="<?php echo $page_title; ?>">
    <meta property="og:description" content="<?php echo $page_description; ?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>">
    <meta property="og:image" content="<?php echo asset('images/og-image.jpg'); ?>">
    <link rel="stylesheet" href="<?php echo asset('css/pages/home.css'); ?>">
    <!-- AOS CSS -->
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
</head>
<body>

    <?php include 'includes/header.php'; ?>

<!-- HERO CAROUSEL -->
<section id="heroCarousel" class="carousel slide hero-carousel" data-bs-ride="carousel" data-bs-interval="6000" data-bs-pause="false" data-bs-touch="true" data-bs-wrap="true" data-bs-keyboard="true" aria-label="Carousel di presentazione servizi" data-aos="fade-in" data-aos-duration="1000">
  <div class="carousel-inner">

    <!-- Slide 1: Riparazioni -->
    <div class="carousel-item active">
      <div class="hero-slide bg-hero-1 d-flex align-items-center">
        <div class="container position-relative" style="z-index:2;">
          <div class="row align-items-center">
            <div class="col-lg-6">
              <div class="hero-content text-white" data-aos="fade-right" data-aos-duration="800" data-aos-delay="200">
                <div class="hero-badge">
                  <i class="ri-time-line"></i>
                  <span>Riparazione in 24h*</span>
                </div>
                <h1 class="hero-title">Riparazioni <span class="hero-title-excited">rapide</span> e <span class="hero-title-excited">garantite</span></h1>
                <p class="hero-description">Smartphone, tablet e computer riparati in tempi record. Garanzia inclusa e assistenza completa.</p>
                <div class="hero-actions">
                  <a href="<?php echo url('preventivo.php'); ?>" class="btn btn-primary btn-lg" aria-label="Richiedi un preventivo per riparazioni">
                    <i class="ri-tools-line"></i> Richiedi un preventivo
                  </a>
                  <a href="<?php echo url('assistenza.php'); ?>" class="btn btn-secondary btn-lg" aria-label="Richiedi assistenza per dispositivi">
                    <i class="ri-customer-service-line"></i> Richiedi assistenza
                  </a>
                </div>
              </div>
            </div>
            <div class="col-lg-6 d-none d-lg-block">
              <div class="hero-features">
                <div class="row g-3">
                  <div class="col-6">
                    <div class="feature-card" data-aos="zoom-in" data-aos-duration="600" data-aos-delay="300">
                      <i class="ri-exchange-line"></i>
                      <h4>Diagnosi gratuita</h4>
                      <p>Identifichiamo il problema senza costi iniziali</p>
                    </div>
                  </div>
                  <div class="col-6">
                    <div class="feature-card" data-aos="zoom-in" data-aos-duration="600" data-aos-delay="400">
                      <i class="ri-user-settings-line"></i>
                      <h4>Garanzia 3 mesi</h4>
                      <p>Tranquillità e sicurezza dopo ogni intervento</p>
                    </div>
                  </div>
                  <div class="col-6">
                    <div class="feature-card" data-aos="zoom-in" data-aos-duration="600" data-aos-delay="500">
                      <i class="ri-shield-check-line"></i>
                      <h4>Dispositivo sostitutivo</h4>
                      <p>Continui a lavorare senza fermarti</p>
                    </div>
                  </div>
                  <div class="col-6">
                    <div class="feature-card" data-aos="zoom-in" data-aos-duration="600" data-aos-delay="600">
                      <i class="ri-smartphone-line"></i>
                      <h4>Ricambi originali</h4>
                      <p>Montiamo solo Service Pack genuini</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="overlay"></div>
      </div>
    </div>

    <!-- Slide 2: Ricondizionati -->
    <div class="carousel-item">
      <div class="hero-slide bg-hero-2 d-flex align-items-center">
        <div class="container position-relative" style="z-index:2;">
          <div class="row align-items-center">
            <div class="col-lg-6">
              <div class="hero-content text-white" data-aos="fade-right" data-aos-duration="800" data-aos-delay="200">
                <div class="hero-badge">
                  <i class="ri-time-line"></i>
                  <span>Garanzia 12 Mesi*</span>
                </div>
                <h1 class="hero-title">Scopri i nostri dispositivi <span class="hero-title-excited">ricondizionati</span> garantiti</h1>
                <p class="hero-description">Smartphone, notebook e tablet testati e certificati. Prezzo conveniente, qualità come nuova.</p>
                <div class="hero-actions">
                  <a href="<?php echo url('ricondizionati.php'); ?>" class="btn btn-primary btn-lg" aria-label="Vedi dispositivi ricondizionati">
                    <i class="ri-shopping-bag-line"></i> Vedi i ricondizionati
                  </a>
                  <a href="<?php echo url('preventivo.php'); ?>" class="btn btn-secondary btn-lg" aria-label="Richiedi una valutazione per usato">
                    <i class="ri-recycle-line"></i> Richiedi una valutazione
                  </a>
                </div>
              </div>
            </div>
            <div class="col-lg-6 d-none d-lg-block">
              <div class="hero-features">
                <div class="row g-3">
                  <div class="col-6">
                    <div class="feature-card" data-aos="zoom-in" data-aos-duration="600" data-aos-delay="300">
                      <i class="ri-exchange-line"></i>
                      <h4>40+ test di qualità</h4>
                      <p>Ogni dispositivo è certificato</p>
                    </div>
                  </div>
                  <div class="col-6">
                    <div class="feature-card" data-aos="zoom-in" data-aos-duration="600" data-aos-delay="400">
                      <i class="ri-user-settings-line"></i>
                      <h4>Risparmi fino al 50%</h4>
                      <p>Massima convenienza senza rinunce</p>
                    </div>
                  </div>
                  <div class="col-6">
                    <div class="feature-card" data-aos="zoom-in" data-aos-duration="600" data-aos-delay="500">
                      <i class="ri-shield-check-line"></i>
                      <h4>Garanzia 12 mesi</h4>
                      <p>Inclusa in ogni acquisto</p>
                    </div>
                  </div>
                  <div class="col-6">
                    <div class="feature-card" data-aos="zoom-in" data-aos-duration="600" data-aos-delay="600">
                      <i class="ri-smartphone-line"></i>
                      <h4>Passaggio dati incluso</h4>
                      <p>Consegna pronta all’uso</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="overlay"></div>
      </div>
    </div>

    <!-- Slide 3: Consulenza IT & Reti -->
    <div class="carousel-item">
      <div class="hero-slide bg-hero-3 d-flex align-items-center">
        <div class="container position-relative" style="z-index:2;">
          <div class="row align-items-center">
            <div class="col-lg-6">
              <div class="hero-content text-white" data-aos="fade-right" data-aos-duration="800" data-aos-delay="200">
                <div class="hero-badge">
                  <i class="ri-time-line"></i>
                  <span>20+ Anni di Esperienza</span>
                </div>
                <h1 class="hero-title">La tua azienda merita <span class="hero-title-excited">soluzioni IT</span> su misura</h1>
                <p class="hero-description">Consulenza informatica, reti aziendali e sicurezza digitale. Dal 2004 partner tecnologico di imprese e professionisti.</p>
                <div class="hero-actions">
                  <a href="<?php echo url('preventivo.php'); ?>" class="btn btn-primary btn-lg" aria-label="Richiedi consulenza IT">
                    <i class="ri-tools-line"></i> Richiedi consulenza IT
                  </a>
                  <a href="<?php echo url('servizi.php'); ?>" class="btn btn-secondary btn-lg" aria-label="Scopri tutti i servizi IT">
                    <i class="ri-customer-service-line"></i> Scopri tutti i servizi
                  </a>
                </div>
              </div>
            </div>
            <div class="col-lg-6 d-none d-lg-block">
              <div class="hero-features">
                <div class="row g-3">
                  <div class="col-6">
                    <div class="feature-card" data-aos="zoom-in" data-aos-duration="600" data-aos-delay="300">
                      <i class="ri-exchange-line"></i>
                      <h4>Reti aziendali sicure</h4>
                      <p>Progettazione e installazione</p>
                    </div>
                  </div>
                  <div class="col-6">
                    <div class="feature-card" data-aos="zoom-in" data-aos-duration="600" data-aos-delay="400">
                      <i class="ri-user-settings-line"></i>
                      <h4>Cybersecurity</h4>
                      <p>Protezione dei tuoi dati aziendali</p>
                    </div>
                  </div>
                  <div class="col-6">
                    <div class="feature-card" data-aos="zoom-in" data-aos-duration="600" data-aos-delay="500">
                      <i class="ri-shield-check-line"></i>
                      <h4>Assistenza da remoto</h4>
                      <p>Supporto rapido e professionale</p>
                    </div>
                  </div>
                  <div class="col-6">
                    <div class="feature-card" data-aos="zoom-in" data-aos-duration="600" data-aos-delay="600">
                      <i class="ri-smartphone-line"></i>
                      <h4>Consulenza su misura</h4>
                      <p>Soluzioni scalabili per PMI e privati</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="overlay"></div>
      </div>
    </div>

  </div>

  <!-- Indicators (pallini) -->
  <div class="carousel-indicators">
    <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
    <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
    <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
  </div>
</section>

<!-- SERVICES -->
<section class="section section-services bg-light" role="region" aria-label="I nostri servizi" data-aos="fade-up" data-aos-duration="1000" data-aos-once="true">
  <div class="container">

    <!-- Sezione Header -->
    <div class="section-header text-center mb-5" data-aos="zoom-in" data-aos-duration="800" data-aos-delay="100">
      <h2 class="section-title">Cosa facciamo</h2>
      <p class="section-subtitle">Soluzioni tecnologiche complete per ogni esigenza</p>
    </div>

    <div class="row align-items-center g-5">
      <!-- Services List -->
      <div class="col-lg-8">
        <div class="services-grid">
          <!-- Riparazioni -->
          <div class="service-item" data-aos="fade-up" data-aos-duration="600" data-aos-delay="200">
            <i class="ri-tools-line" aria-hidden="true"></i>
            <div>
              <h4>Riparazioni & Assistenza</h4>
              <p>Riparazione professionale di smartphone, tablet, PC e console gaming</p>
            </div>
          </div>

          <!-- Vendita -->
          <div class="service-item" data-aos="fade-up" data-aos-duration="600" data-aos-delay="300">
            <i class="ri-shopping-bag-line" aria-hidden="true"></i>
            <div>
              <h4>Vendita al Dettaglio</h4>
              <p>Dispositivi nuovi e ricondizionati con garanzia, accessori originali</p>
            </div>
          </div>

          <!-- MyShape -->
          <div class="service-item" data-aos="fade-up" data-aos-duration="600" data-aos-delay="400">
            <i class="ri-shield-check-line" aria-hidden="true"></i>
            <div>
              <h4>MyShape Protection</h4>
              <p>Pellicole ANTISHOCK e AUTO-RIGENERANTI, perfette per proteggere gli schermi dei dispositivi</p>
            </div>
          </div>

          <!-- Sviluppo -->
          <div class="service-item" data-aos="fade-up" data-aos-duration="600" data-aos-delay="500">
            <i class="ri-global-line" aria-hidden="true"></i>
            <div>
              <h4>Sviluppo Web & App</h4>
              <p>Creazione siti web, e-commerce e applicazioni mobile su misura</p>
            </div>
          </div>

          <!-- Consulenza -->
          <div class="service-item" data-aos="fade-up" data-aos-duration="600" data-aos-delay="600">
            <i class="ri-server-line" aria-hidden="true"></i>
            <div>
              <h4>Consulenza IT & Reti</h4>
              <p>Progettazione reti aziendali, sistemi di sicurezza e videosorveglianza</p>
            </div>
          </div>

          <!-- Telefonia -->
          <div class="service-item" data-aos="fade-up" data-aos-duration="600" data-aos-delay="700">
            <i class="ri-sim-card-line" aria-hidden="true"></i>
            <div>
              <h4>Telefonia & Servizi Casa</h4>
              <p>Attivazione SIM, offerte internet casa, luce e gas con i migliori operatori</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Experience Box (centrato verticalmente solo rispetto alla services-grid) -->
      <div class="col-lg-4">
        <div class="experience-box" data-aos="flip-left" data-aos-duration="1000" data-aos-delay="300">
          <div class="experience-number">20+</div>
          <div class="experience-text">anni di esperienza</div>
          <div class="experience-stats">
            <div class="stat-item">
              <div class="stat-number">15000+</div>
              <div class="stat-label">Clienti soddisfatti</div>
            </div>
            <div class="stat-item">
              <div class="stat-number">24h</div>
              <div class="stat-label">Tempo medio riparazione</div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- CTA (spostato fuori dalla row per non influenzare la centratura) -->
    <div class="text-center mt-5" data-aos="fade-up" data-aos-duration="600" data-aos-delay="800">
      <a href="<?php echo url('servizi.php'); ?>" class="btn btn-primary" aria-label="Scopri tutti i servizi">
        Scopri tutti i servizi <i class="ri-arrow-right-line"></i>
      </a>
    </div>

  </div>
</section>

<!-- RECONDITIONED SECTION -->
<section class="section section-recond" role="region" aria-label="Dispositivi ricondizionati in evidenza" data-aos="fade-up" data-aos-duration="1000" data-aos-once="true">
  <div class="container">
    <div class="section-header text-center" data-aos="zoom-in" data-aos-duration="800" data-aos-delay="100">
      <h2 class="section-title">Ricondizionati in evidenza</h2>
      <p class="section-subtitle">Smartphone e dispositivi ricondizionati con garanzia 12 mesi</p>
    </div>

    <!-- Swiper -->
    <div class="swiper recond-swiper">
      <div class="swiper-wrapper">
        <?php
        // Esempio mockup (in futuro sostituire con query DB)
        $products = [
          ["title" => "iPhone 13 Ricondizionato", "price" => "499,00", "img" => asset('img/recond/iphone13.avif')],
          ["title" => "Samsung Galaxy S21", "price" => "399,00", "img" => asset('img/recond/galaxys21.avif')],
          ["title" => "MacBook Air M1", "price" => "799,00", "img" => asset('img/recond/macbookair.avif')],
          ["title" => "iPad Pro 11\"", "price" => "599,00", "img" => asset('img/recond/ipadpro.avif')],
          ["title" => "Xiaomi Redmi Note 12", "price" => "249,00", "img" => asset('img/recond/redminote12.avif')],
        ];

        $delay = 200; // Delay staggered per card
        foreach ($products as $p): ?>
          <div class="swiper-slide">
            <div class="recond-card" data-aos="fade-up" data-aos-duration="600" data-aos-delay="<?php echo $delay; ?>">
              <img src="<?php echo $p['img']; ?>" alt="<?php echo $p['title']; ?>" class="recond-img" loading="lazy">
              <div class="recond-body">
                <h4 class="recond-title"><?php echo $p['title']; ?></h4>
                <div class="recond-price">€ <?php echo $p['price']; ?></div>
                <a href="<?php echo url('ricondizionati.php'); ?>" class="btn btn-primary btn-sm w-100 mt-3" aria-label="Dettagli su <?php echo $p['title']; ?>">
                  <i class="ri-shopping-cart-2-line"></i> Dettagli
                </a>
              </div>
            </div>
          </div>
        <?php $delay += 100; endforeach; ?>
      </div>

      <!-- Pagination -->
      <div class="swiper-pagination"></div>
    </div>
  </div>
</section>

<!-- WHY US -->
<section class="section section-why-us" role="region" aria-label="Perché scegliere noi" data-aos="fade-up" data-aos-duration="1000" data-aos-once="true">
  <div class="container">
    <div class="section-header text-center" data-aos="zoom-in" data-aos-duration="800" data-aos-delay="100">
      <h2 class="section-title">Perché scegliere Key Soft Italia</h2>
      <p class="section-subtitle">Non solo riparazioni: il nostro valore aggiunto in ogni servizio</p>
    </div>
    <div class="row g-4">
      <?php 
      $advantages = [
          ["icon" => "ri-shield-check-line", "title" => "Ricondizionati Certificati", "text" => "Ogni dispositivo è testato e garantito 12 mesi, come nuovo."],
          ["icon" => "ri-headphone-line", "title" => "Assistenza Continua", "text" => "Supporto dedicato anche dopo la vendita, sempre disponibili."],
          ["icon" => "ri-global-line", "title" => "Consulenza IT & Reti", "text" => "Soluzioni su misura per aziende, networking e sicurezza."],
          ["icon" => "ri-smartphone-line", "title" => "Telefonia & Casa", "text" => "Offerte su SIM, internet, luce e gas: risparmio garantito."],
          ["icon" => "ri-code-s-slash-line", "title" => "Innovazione & Sviluppo", "text" => "Web, e-commerce e software per far crescere la tua attività."],
          ["icon" => "ri-community-line", "title" => "Affidabilità dal 2004", "text" => "Siamo un punto di riferimento per famiglie e aziende."],
      ];
      $delay = 200;
      foreach ($advantages as $a): ?>
      <div class="col-lg-4 col-md-6">
        <div class="advantage-card" data-aos="fade-up" data-aos-duration="600" data-aos-delay="<?php echo $delay; ?>">
          <div class="advantage-icon"><i class="<?php echo $a['icon']; ?>" aria-hidden="true"></i></div>
          <h4 class="advantage-title"><?php echo $a['title']; ?></h4>
          <p class="advantage-text"><?php echo $a['text']; ?></p>
        </div>
      </div>
      <?php $delay += 100; endforeach; ?>
    </div>
    <div class="text-center mt-5" data-aos="fade-up" data-aos-duration="600" data-aos-delay="800">
      <a href="<?php echo url('chi-siamo.php'); ?>" class="btn btn-primary" aria-label="Scopri di più su Key Soft Italia">
        Scopri di più <i class="ri-arrow-right-line"></i>
      </a>
    </div>
  </div>
</section>

<!-- CTA PANELS -->
<section class="section section-cta-panels" role="region" aria-label="Azioni rapide" data-aos="fade-up" data-aos-duration="1000" data-aos-once="true">
  <div class="container">
    <div class="row g-4">
      <!-- Richiedi Preventivo -->
      <div class="col-lg-6">
        <div class="cta-card cta-orange" data-aos="slide-right" data-aos-duration="800" data-aos-delay="200">
          <div class="cta-icon">
            <i class="ri-file-list-3-line" aria-hidden="true"></i>
          </div>
          <h3 class="cta-title">Richiedi Preventivo</h3>
          <p class="cta-text">Ottieni un preventivo gratuito per riparazioni, consulenza IT o qualsiasi altro servizio.</p>
          <a href="<?php echo url('preventivo.php'); ?>" class="btn btn-cta" aria-label="Richiedi preventivo gratuito">
            <i class="ri-file-list-3-line"></i> Richiedi ora
          </a>
        </div>
      </div>

      <!-- Vendi il tuo usato -->
      <div class="col-lg-6">
        <div class="cta-card cta-green" data-aos="slide-left" data-aos-duration="800" data-aos-delay="300">
          <div class="cta-icon">
            <i class="ri-recycle-line" aria-hidden="true"></i>
          </div>
          <h3 class="cta-title">Vendi il tuo usato</h3>
          <p class="cta-text">Anche se rotto! Valutiamo e acquistiamo il tuo dispositivo usato al miglior prezzo.</p>
          <a href="<?php echo url('servizi/vendita.php#permuta'); ?>" class="btn btn-cta" aria-label="Vendi il tuo dispositivo usato">
            <i class="ri-recycle-line"></i> Vendi ora
          </a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- PARTNER BRANDS -->
<section class="section section-brands" role="region" aria-label="I nostri partner" data-aos="fade-up" data-aos-duration="1000" data-aos-once="true">
  <div class="container">
    <div class="section-header text-center" data-aos="zoom-in" data-aos-duration="800" data-aos-delay="100">
      <h2 class="section-title">Partner e Brand Trattati</h2>
      <p class="section-subtitle">Collaboriamo con i migliori marchi per offrirti qualità e affidabilità</p>
    </div>

    <!-- Swiper Carousel -->
    <div class="swiper brand-swiper">
      <div class="swiper-wrapper">
        <!-- LOGHI DEMO (da sostituire con immagini reali in /assets/img/brands/) -->
        <div class="swiper-slide" data-aos="fade-in" data-aos-duration="600" data-aos-delay="200">
          <img src="<?php echo asset('img/brands/apple.png'); ?>" alt="Logo Apple" loading="lazy">
        </div>
        <div class="swiper-slide" data-aos="fade-in" data-aos-duration="600" data-aos-delay="300">
          <img src="<?php echo asset('img/brands/samsung.png'); ?>" alt="Logo Samsung" loading="lazy">
        </div>
        <div class="swiper-slide" data-aos="fade-in" data-aos-duration="600" data-aos-delay="400">
          <img src="<?php echo asset('img/brands/huawei.png'); ?>" alt="Logo Huawei" loading="lazy">
        </div>
        <div class="swiper-slide" data-aos="fade-in" data-aos-duration="600" data-aos-delay="500">
          <img src="<?php echo asset('img/brands/xiaomi.png'); ?>" alt="Logo Xiaomi" loading="lazy">
        </div>
        <div class="swiper-slide" data-aos="fade-in" data-aos-duration="600" data-aos-delay="600">
          <img src="<?php echo asset('img/brands/lenovo.png'); ?>" alt="Logo Lenovo" loading="lazy">
        </div>
        <div class="swiper-slide" data-aos="fade-in" data-aos-duration="600" data-aos-delay="700">
          <img src="<?php echo asset('img/brands/hp.png'); ?>" alt="Logo HP" loading="lazy">
        </div>
        <div class="swiper-slide" data-aos="fade-in" data-aos-duration="600" data-aos-delay="700">
          <img src="<?php echo asset('img/brands/asus.png'); ?>" alt="Logo ASUS" loading="lazy">
        </div>
        <div class="swiper-slide" data-aos="fade-in" data-aos-duration="600" data-aos-delay="700">
          <img src="<?php echo asset('img/brands/brother.png'); ?>" alt="Logo Brother" loading="lazy">
        </div>
        <div class="swiper-slide" data-aos="fade-in" data-aos-duration="600" data-aos-delay="700">
          <img src="<?php echo asset('img/brands/canon.png'); ?>" alt="Logo Canon" loading="lazy">
        </div>
        <div class="swiper-slide" data-aos="fade-in" data-aos-duration="600" data-aos-delay="700">
          <img src="<?php echo asset('img/brands/acer.png'); ?>" alt="Logo Acer" loading="lazy">
        </div>
        <div class="swiper-slide" data-aos="fade-in" data-aos-duration="600" data-aos-delay="700">
          <img src="<?php echo asset('img/brands/motorola.png'); ?>" alt="Logo Motorola" loading="lazy">
        </div>
        <div class="swiper-slide" data-aos="fade-in" data-aos-duration="600" data-aos-delay="700">
          <img src="<?php echo asset('img/brands/realme.png'); ?>" alt="Logo Realme" loading="lazy">
        </div>
        <div class="swiper-slide" data-aos="fade-in" data-aos-duration="600" data-aos-delay="700">
          <img src="<?php echo asset('img/brands/tplink.png'); ?>" alt="Logo TP-Link" loading="lazy">
        </div>
      </div>
    </div>
  </div>
</section>

<!-- TESTIMONIALS SECTION -->
<section class="section section-testimonials" role="region" aria-label="Recensioni clienti" data-aos="fade-up" data-aos-duration="1000" data-aos-once="true">
  <div class="container">
    <div class="section-header text-center" data-aos="zoom-in" data-aos-duration="800" data-aos-delay="100">
      <h2 class="section-title">Cosa dicono i nostri clienti</h2>
      <p class="section-subtitle">Recensioni autentiche da Google</p>
    </div>

    <div class="swiper testimonial-swiper">
      <div class="swiper-wrapper">
        <?php 
        // 10 recensioni demo - da sostituire con reali
        $testimonials = [
          ["name"=>"Angela P.","rating"=>5,"text"=>"Ragazzi molto disponibili e competenti nel loro lavoro."],
          ["name"=>"Johnny P.","rating"=>5,"text"=>"Personale attento alle esigenze del cliente, preparatissimo tecnicamente. Consigliatissimo."],
          ["name"=>"Vito R.","rating"=>4,"text"=>"Personale cordiale e competente e tutta la tecnologia del momento."],
          ["name"=>"Patrizia P.","rating"=>5,"text"=>"Professionisti sempre pronti ad aiutarci e consigliarti i prodotti migliori."],
          ["name"=>"Pietro R.","rating"=>5,"text"=>"Ottimo servizio di vendita e assistenza."],
          ["name"=>"Eva L.","rating"=>5,"text"=>"Sempre gentili e professionali,  molto forniti."],
          ["name"=>"Paola P.","rating"=>5,"text"=>"Gentilissimi, disponibili e competenti sempre!"],
          ["name"=>"Lilliana P.","rating"=>4,"text"=>"Sono molto gentili."],
          ["name"=>"Nicola B.","rating"=>4,"text"=>"Professionalità, gentilezza, velocità e servizi top!"],
          ["name"=>"Erasmo S.","rating"=>5,"text"=>"Cordialità e competenza al TOP."],
        ];
        $delay = 200;
        foreach ($testimonials as $t): 
          // Prendi iniziali dal nome
          $initials = implode('', array_map(fn($part) => mb_substr($part, 0, 1), explode(' ', $t['name'])));
        ?>
          <div class="swiper-slide">
            <div class="testimonial-card" role="article" aria-label="Recensione di <?php echo $t['name']; ?>" data-aos="fade-up" data-aos-duration="600" data-aos-delay="<?php echo $delay; ?>">
              <div class="testimonial-top">
                <div class="testimonial-avatar"><?php echo $initials; ?></div>
                <div class="testimonial-info">
                  <div class="testimonial-author"><?php echo $t['name']; ?></div>
                  <div class="testimonial-rating" aria-label="Valutazione <?php echo $t['rating']; ?> stelle su 5">
                    <?php for ($i=1;$i<=5;$i++): ?>
                      <?php echo $i <= $t['rating'] ? '<i class="ri-star-fill" aria-hidden="true"></i>' : '<i class="ri-star-line" aria-hidden="true"></i>'; ?>
                    <?php endfor; ?>
                  </div>
                </div>
              </div>
              <p class="testimonial-text">"<?php echo $t['text']; ?>"</p>
            </div>
          </div>
        <?php $delay += 100; endforeach; ?>
      </div>
      <div class="swiper-pagination"></div>
    </div>

    <div class="text-center mt-4" data-aos="fade-up" data-aos-duration="600" data-aos-delay="800">
      <a href="https://www.google.it/maps/place/Key+Soft+Italia" target="_blank" rel="noopener" class="btn btn-primary" aria-label="Leggi tutte le recensioni su Google">
        Leggi tutte le recensioni su Google <i class="ri-arrow-right-line"></i>
      </a>
    </div>
  </div>
</section>

<!-- FINAL CTA -->
<section class="section section-final-cta" role="region" aria-label="Contattaci per assistenza" data-aos="fade-up" data-aos-duration="1000" data-aos-once="true">
  <div class="container text-center">
    <h2 class="final-cta-title" data-aos="zoom-in" data-aos-duration="800" data-aos-delay="100">Hai bisogno di assistenza immediata?</h2>
    <p class="final-cta-subtitle" data-aos="fade-up" data-aos-duration="600" data-aos-delay="200">Il nostro team di esperti è pronto ad aiutarti. Contattaci per un supporto rapido e professionale.</p>
    <div class="final-cta-actions" data-aos="fade-up" data-aos-duration="600" data-aos-delay="300">
      <a href="tel:<?php echo PHONE_PRIMARY; ?>" class="btn btn-primary btn-lg" aria-label="Chiama al numero <?php echo PHONE_PRIMARY; ?>">
        <i class="ri-phone-line"></i> Chiama ora: <?php echo PHONE_PRIMARY; ?>
      </a>
      <a href="<?php echo whatsapp_link('Ciao Key Soft Italia, ho bisogno di assistenza immediata!', ['utm_campaign' => 'footer-cta']); ?>" 
         target="_blank" rel="noopener" 
         class="btn btn-outline-primary btn-lg" aria-label="Contattaci su WhatsApp">
        <i class="ri-whatsapp-line"></i> WhatsApp
      </a>
    </div>
  </div>
</section>

    <?php include 'includes/footer.php'; ?>

    <!-- AOS JS -->
    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script>
      AOS.init({
        duration: 800, // Durata default
        easing: 'ease-in-out', // Easing fluido
        once: true, // Anima solo una volta
        mirror: false // Non anima al reverse scroll
      });
    </script>
</body>
</html>