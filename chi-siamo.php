<?php
/**
 * Key Soft Italia - Chi Siamo
 * Pagina informazioni aziendali (struttura allineata a index.php)
 */

require_once __DIR__ . '/config/config.php';

// SEO Meta (corretto anno a 2004 per coerenza)
$page_title       = "Chi Siamo - Key Soft Italia | La Nostra Storia dal 2004";
$page_description = "Scopri la storia di Key Soft Italia, leader a Ginosa per riparazioni tecnologiche, vendita ricondizionati e assistenza informatica dal 2004.";
$page_keywords    = "key soft italia, chi siamo, team tecnico ginosa, esperienza informatica";

// Breadcrumbs (reso opzionale, solo se >1 item)
$breadcrumbs = [
  ['label' => 'Chi Siamo', 'url' => 'chi-siamo.php']
];
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
  <link rel="stylesheet" href="<?= asset_version('css/pages/chi-siamo.css'); ?>">
</head>
<body>

  <?php include 'includes/header.php'; ?>

<!-- HERO -->
<section class="hero hero-secondary text-center">
  <div class="hero-pattern"></div>
  <div class="container position-relative z-2" data-aos="fade-up">
    <div class="hero-icon mb-3" data-aos="zoom-in">
      <i class="ri-team-line"></i>
    </div>
    <h1 class="hero-title text-white" data-aos="fade-up" data-aos-delay="100">
      Chi <span class="text-gradient">Siamo</span>
    </h1>
    <p class="hero-subtitle" data-aos="fade-up" data-aos-delay="200">
      Il tuo partner tecnologico di fiducia <strong>dal 2004</strong>
    </p>
    <div class="hero-cta" data-aos="fade-up" data-aos-delay="300">
      <a href="#nostra-storia" class="btn btn-primary btn-lg smooth-scroll" aria-label="Scopri la nostra storia">
        <i class="ri-arrow-down-line me-1"></i> Scopri la nostra storia
      </a>
    </div>
    <div class="hero-breadcrumb mt-4" data-aos="fade-up" data-aos-delay="400">
      <?= generate_breadcrumbs($breadcrumbs); ?>
    </div>
  </div>
</section>

<!-- STORY -->
<section id="nostra-storia" class="section section-story">
  <div class="container">
    <div class="row align-items-center">
      <!-- Testo -->
      <div class="col-lg-6 mb-5 mb-lg-0" data-aos="fade-right">
        <h2 class="section-title">
          La Nostra <span class="text-gradient">Storia</span>
        </h2>
        <p class="lead mb-4">
          Key Soft Italia è una realtà imprenditoriale nata nell’ormai lontano <strong>2004</strong>,
          dal sogno condiviso di tre amici uniti dalla passione per la tecnologia e la voglia di creare qualcosa di grande.
        </p>
        <p>
          Dopo aver condiviso gli anni delle scuole medie e dell’Istituto Tecnico Industriale
          <strong>G.B. Pentasuglia di Matera</strong>, i tre soci — coetanei e affiatati —
          decidono di lanciarsi nel mondo del lavoro.  
          Con impegno e risorse proprie, il <strong>4 dicembre 2004</strong> inaugurano
          il loro primo punto vendita in via Francese a Ginosa.
        </p>
        <p>
          La passione, la professionalità e la voglia di migliorarsi portano presto
          all’apertura di un secondo punto vendita a Castellaneta e al trasferimento
          della sede principale in <strong>via Diaz a Ginosa</strong>,
          oggi centro operativo e cuore pulsante dell’azienda.
        </p>
        <p class="mb-4">
          Qui è possibile trovare un vasto assortimento di prodotti informatici,
          elettronici e di piccolo elettrodomestico, insieme a servizi di
          assistenza, consulenza e sviluppo software.
        </p>
        <p class="fw-semibold text-dark">
          <i class="ri-hand-heart-line text-orange me-1"></i>
          <strong>Cordialità</strong> e <strong>professionalità</strong> sono da sempre il giusto mix
          su cui i tre soci fondano il loro rapporto con i clienti: un legame costruito
          su fiducia, competenza e passione.
        </p>
      </div>
      <!-- Immagine e dati -->
      <div class="col-lg-6" data-aos="fade-left">
        <div class="story-image-wrapper">
          <div class="story-image position-relative rounded-4 overflow-hidden shadow-lg">
            <img src="<?= asset('img/about-story.png'); ?>" alt="Il nostro laboratorio tecnico di Key Soft Italia a Ginosa" class="img-fluid w-100" loading="lazy">
            <span class="year-badge">Dal 2004</span>
          </div>
          <div class="story-stats mt-4 d-flex justify-content-center gap-4">
            <div class="stat-card">
              <div class="stat-number">20+</div>
              <div class="stat-label">Anni di Esperienza</div>
            </div>
            <div class="stat-card">
              <div class="stat-number">15000+</div>
              <div class="stat-label">Clienti Soddisfatti</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- VALUES -->
<section class="section section-values bg-light">
  <div class="container">
    <div class="section-header text-center" data-aos="fade-up">
      <h2 class="section-title">
        I Nostri <span class="text-gradient">Valori</span>
      </h2>
      <p class="section-subtitle">
        I principi che guidano ogni nostra scelta, ogni riparazione, ogni sorriso.
      </p>
    </div>
    <div class="row g-4 mt-4">
      <?php
      $about_values = [
        [
          'icon' => 'ri-flask-line',
          'title' => 'Innovazione Costante',
          'text'  => 'Sperimentiamo, testiamo, miglioriamo ogni giorno. L’innovazione è la chiave per restare sempre un passo avanti.',
          'aos'   => 'fade-right'
        ],
        [
          'icon' => 'ri-hand-heart-line',
          'title' => 'Fiducia e Trasparenza',
          'text'  => 'Crediamo nel rapporto diretto e sincero con i nostri clienti. Ogni intervento è spiegato, garantito e tracciabile.',
          'aos'   => 'fade-up'
        ],
        [
          'icon' => 'ri-user-star-line',
          'title' => 'Orientamento al Cliente',
          'text'  => 'Ascoltiamo davvero: personalizziamo ogni servizio sulle tue esigenze, perché la tua soddisfazione è il nostro obiettivo.',
          'aos'   => 'fade-down'
        ],
        [
          'icon' => 'ri-flashlight-line',
          'title' => 'Efficienza e Rapidità',
          'text'  => 'Riparazioni express in 24 ore, consegne puntuali, processi ottimizzati. Ogni minuto conta, anche il tuo.',
          'aos'   => 'fade-left'
        ]
      ];
      $delay = 0;
      foreach ($about_values as $v): $delay += 100; ?>
        <div class="col-lg-3 col-md-6" data-aos="<?= $v['aos']; ?>" data-aos-delay="<?= $delay; ?>">
          <div class="value-card">
            <div class="value-icon">
              <i class="<?= $v['icon']; ?>"></i>
            </div>
            <h4 class="value-title"><?= $v['title']; ?></h4>
            <p class="value-text"><?= $v['text']; ?></p>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- TEAM -->
<section class="section section-team">
  <div class="container">
    <div class="section-header text-center" data-aos="fade-up">
      <h2 class="section-title">Il Nostro <span class="text-gradient">Team</span></h2>
      <p class="section-subtitle">Persone, prima ancora che professionisti</p>
    </div>

    <!-- Versione Desktop: Grid -->
    <div class="row g-4 justify-content-center mt-4 d-none d-md-flex">
      <?php
      $about_team = [
        [
          'name' => 'Patrizio Cuscito',
          'role' => 'Founder & CEO',
          'photo' => 'patrizio.png',
          'bio'  => 'Tecnico e sviluppatore con oltre 20 anni di esperienza. Esperto in riparazioni elettroniche avanzate e sviluppo software, guida Key Soft Italia unendo competenza e visione innovativa.',
          'skills' => ['Diagnostica', 'Sviluppo', 'Leadership'],
          'aos' => 'fade-right'
        ],
        [
          'name' => 'Vito Moro',
          'role' => 'Founder & Marketing Expert',
          'photo' => 'vito.png',
          'bio'  => 'Co-fondatore di Key Soft Italia, è il motore creativo del gruppo. Si occupa di marketing e comunicazione con un approccio strategico e sempre orientato al cliente.',
          'skills' => ['Marketing', 'Strategia', 'Branding'],
          'aos' => 'fade-up'
        ],
        [
          'name' => 'Giulio Ricciardi',
          'role' => 'Founder & IT Expert',
          'photo' => 'giulio.png',
          'bio'  => 'Specialista in assistenza tecnica informatica e reti aziendali. Coordina gli interventi e garantisce supporto costante ai clienti business e privati.',
          'skills' => ['Networking', 'Assistenza IT', 'Gestione Clienti'],
          'aos' => 'fade-left'
        ],
        [
          'name' => 'Francesca Angelillo',
          'role' => 'Customer Care',
          'photo' => 'francesca.png',
          'bio'  => 'Accogliente e precisa, è il primo sorriso che i clienti incontrano in negozio. Gestisce preventivi, comunicazioni e relazioni con grande professionalità.',
          'skills' => ['Relazioni', 'Organizzazione', 'Comunicazione'],
          'aos' => 'fade-right'
        ],
        [
          'name' => 'Niccolò Colafemmina',
          'role' => 'Tecnico Informatico',
          'photo' => 'niccolo.png',
          'bio'  => 'Tecnico giovane e appassionato, si occupa di assistenza hardware e software, aggiornamenti e installazioni con rapidità e precisione.',
          'skills' => ['Hardware', 'Software', 'Supporto Tecnico'],
          'aos' => 'fade-up'
        ]
      ];
      $delay = 0;
      foreach ($about_team as $member): $delay += 100; ?>
        <div class="col-lg-4 col-md-6" data-aos="<?= $member['aos']; ?>" data-aos-delay="<?= $delay; ?>">
          <div class="team-card">
            <div class="team-avatar">
              <img src="<?= asset('img/team/' . $member['photo']); ?>" alt="Foto di <?= $member['name']; ?>, <?= $member['role']; ?> di Key Soft Italia" class="team-photo img-fluid" loading="lazy">
            </div>
            <h4 class="team-name"><?= $member['name']; ?></h4>
            <p class="team-role"><?= $member['role']; ?></p>
            <p class="team-bio"><?= $member['bio']; ?></p>
            <div class="team-skills">
              <?php foreach ($member['skills'] as $skill): ?>
                <span class="skill-tag"><?= $skill; ?></span>
              <?php endforeach; ?>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

<!-- Versione Mobile: Swiper Carousel -->
<div class="team-swiper d-md-none mt-4" data-aos="fade-up">
  <div class="swiper teamSwiper">
    <div class="swiper-wrapper">
      <?php foreach ($about_team as $member): ?>
        <div class="swiper-slide">
          <div class="team-card mx-auto">
            <div class="team-avatar">
              <img src="<?= asset('img/team/' . $member['photo']); ?>"
                   alt="Foto di <?= $member['name']; ?>, <?= $member['role']; ?> di Key Soft Italia"
                   class="team-photo img-fluid" loading="lazy">
            </div>
            <h4 class="team-name"><?= $member['name']; ?></h4>
            <p class="team-role"><?= $member['role']; ?></p>
            <p class="team-bio"><?= $member['bio']; ?></p>
            <div class="team-skills">
              <?php foreach ($member['skills'] as $skill): ?>
                <span class="skill-tag"><?= $skill; ?></span>
              <?php endforeach; ?>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <!-- Pagination -->
    <div class="swiper-pagination"></div>
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

<!-- MISSION -->
<section class="section section-mission text-white" style="--mission-bg: url('<?= asset('img/mission-lab.png'); ?>');">
  <div class="mission-overlay"></div>
  <div class="container position-relative">
    <div class="row align-items-center">
      <div class="col-lg-7" data-aos="fade-right">
        <h2 class="mission-title">
          <i class="ri-rocket-line me-2"></i> La nostra <span class="text-highlight">Missione</span>
        </h2>
        <p class="mission-text">
          Dal 2004 mettiamo la tecnologia al servizio delle persone e delle imprese.  
          Crediamo in un futuro in cui innovazione e fiducia camminano insieme,  
          offrendo soluzioni su misura, assistenza continua e trasparenza totale.  
          Ogni dispositivo che ripariamo, ogni sistema che progettiamo, è un passo in più  
          verso un mondo più connesso, semplice e affidabile.
        </p>
        <a href="#section-cta" class="btn btn-light mt-3 smooth-scroll" aria-label="Scopri di più sulla nostra missione">
          Scopri di più <i class="ri-arrow-right-line"></i>
        </a>
      </div>
      <div class="col-lg-5 mt-5 mt-lg-0" data-aos="fade-left">
        <div class="mission-stats-grid">
          <div class="mission-stat">
            <i class="ri-user-smile-line"></i>
            <div class="stat-value">15000+</div>
            <div class="stat-label">Clienti Soddisfatti</div>
          </div>
          <div class="mission-stat">
            <i class="ri-calendar-2-line"></i>
            <div class="stat-value">20+</div>
            <div class="stat-label">Anni di Esperienza</div>
          </div>
          <div class="mission-stat">
            <i class="ri-time-line"></i>
            <div class="stat-value">24h</div>
            <div class="stat-label">Riparazioni Express</div>
          </div>
          <div class="mission-stat">
            <i class="ri-shield-check-line"></i>
            <div class="stat-value">100%</div>
            <div class="stat-label">Garanzia Servizi</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- CTA Finale -->
<section class="section section-cta-clean text-center" data-aos="fade-up" id="section-cta">
  <div class="container">
    <h2 class="cta-title">Scopri cosa possiamo fare per te</h2>
    <p class="cta-subtitle">Contattaci oggi stesso per una consulenza gratuita e senza impegno</p>
    <div class="cta-buttons">
      <a href="<?= url('preventivo.php'); ?>" class="btn btn-primary btn-lg" aria-label="Richiedi un preventivo gratuito">
        <i class="ri-file-list-3-line me-2"></i> Richiedi Preventivo
      </a>
      <a href="<?= url('contatti.php'); ?>" class="btn btn-outline-primary btn-lg" aria-label="Contattaci ora">
        <i class="ri-phone-line me-2"></i> Contattaci
      </a>
    </div>
</section>

  <?php include 'includes/footer.php'; ?>
  <script src="<?= asset('js/main.js'); ?>" defer></script>
  <script>
document.addEventListener('DOMContentLoaded', () => {
  const swiper = new Swiper('.teamSwiper', {
    slidesPerView: 1,
    spaceBetween: 24,
    centeredSlides: true,
    loop: true,
    pagination: {
      el: '.swiper-pagination',
      clickable: true,
    },
    autoplay: {
      delay: 4500,
      disableOnInteraction: false,
    },
    breakpoints: {
      576: { slidesPerView: 1.2 },
      768: { slidesPerView: 2 },
    },
  });
});
</script>
</body>
</html>