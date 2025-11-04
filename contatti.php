<?php
/**
 * Key Soft Italia - Contatti
 * Struttura allineata alle altre pagine (head include, OG/canonical, AOS)
 */
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/assets/php/functions.php';

// SEO
$page_title       = "Contatti - Key Soft Italia | Ginosa, Via Diaz 46";
$page_description = "Contatta Key Soft Italia a Ginosa per riparazioni, assistenza e vendita. Via Diaz 46 • Tel: ".COMPANY_PHONE." • WhatsApp ed email.";
$page_keywords    = "contatti key soft italia, negozio informatica ginosa, assistenza computer taranto";

// Breadcrumbs
$breadcrumbs = [
  ['label' => 'Contatti', 'url' => 'contatti.php']
];

// Endpoint AJAX (adegua al tuo path reale)
$contact_endpoint = url('ajax/contact_submit.php');
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
  <link rel="stylesheet" href="<?= asset('css/pages/contatti.css'); ?>">
</head>
<body>

  <?php include 'includes/header.php'; ?>

  <!-- HERO -->
<section class="hero hero-secondary text-center">
  <div class="hero-pattern"></div>
  <div class="container position-relative z-2" data-aos="fade-up">
    <div class="hero-icon mb-3" data-aos="zoom-in">
      <i class="ri-customer-service-2-line"></i>
    </div>
    <h1 class="hero-title text-white" data-aos="fade-up" data-aos-delay="100">
      I Nostri <span class="text-gradient">Contatti</span>
    </h1>
    <p class="hero-subtitle" data-aos="fade-up" data-aos-delay="200">
      Siamo qui per aiutarti. Scegli il modo <strong>più comodo</strong> per contattarci.
    </p>
        <div class="hero-cta" data-aos="fade-up" data-aos-delay="300">
      <a href="#modulo-contatto" class="btn btn-primary btn-lg smooth-scroll" aria-label="Scopri la nostra storia">
        <i class="ri-arrow-down-line me-1"></i> Contattaci ora!
      </a>
    </div>
    <div class="hero-breadcrumb mt-4" data-aos="fade-up" data-aos-delay="400">
      <?= generate_breadcrumbs($breadcrumbs); ?>
    </div>
  </div>
</section>

<!-- CONTACT CARDS -->
<section class="section section-contact-cards">
  <div class="container">
    <div class="row g-4">
      <div class="col-lg-3 col-md-6" data-aos="fade-up">
        <div class="contact-card card-theme--map">
          <div class="contact-card-icon"><i class="ri-map-pin-line" aria-hidden="true"></i></div>
          <h4 class="contact-card-title">Indirizzo</h4>
          <p class="contact-card-text">
            <?= COMPANY_ADDRESS; ?><br><?= COMPANY_CITY; ?> (TA) • 74013
          </p>
          <a class="contact-card-link" href="<?= GOOGLE_MAPS_LINK; ?>" target="_blank" rel="noopener">
            Indicazioni stradali <i class="ri-arrow-right-line"></i>
          </a>
        </div>
      </div>

      <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="100">
        <div class="contact-card card-theme--phone">
          <div class="contact-card-icon"><i class="ri-phone-line" aria-hidden="true"></i></div>
          <h4 class="contact-card-title">Telefono</h4>
          <p class="contact-card-text">
            <a href="tel:<?= str_replace(' ', '', COMPANY_PHONE); ?>"><?= COMPANY_PHONE; ?></a><br>
            Lun-Ven: 9:00-19:00
          </p>
          <a class="contact-card-link" href="tel:<?= str_replace(' ', '', COMPANY_PHONE); ?>">
            Chiama ora <i class="ri-arrow-right-line"></i>
          </a>
        </div>
      </div>

      <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
        <div class="contact-card card-theme--whatsapp">
          <div class="contact-card-icon"><i class="ri-whatsapp-line" aria-hidden="true"></i></div>
          <h4 class="contact-card-title">WhatsApp</h4>
          <p class="contact-card-text">
            <?= COMPANY_WHATSAPP; ?><br>Risposta immediata
          </p>
          <a class="contact-card-link" href="<?= whatsapp_link('Ciao Key Soft Italia, ho bisogno di informazioni'); ?>" target="_blank" rel="noopener">
            Scrivici su WhatsApp <i class="ri-arrow-right-line"></i>
          </a>
        </div>
      </div>

      <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="300">
        <div class="contact-card card-theme--email">
          <div class="contact-card-icon"><i class="ri-mail-line" aria-hidden="true"></i></div>
          <h4 class="contact-card-title">Email</h4>
          <p class="contact-card-text">
            <a href="mailto:<?= COMPANY_EMAIL; ?>"><?= COMPANY_EMAIL; ?></a><br>24/7 Support
          </p>
          <a class="contact-card-link" href="mailto:<?= COMPANY_EMAIL; ?>">
            Invia email <i class="ri-arrow-right-line"></i>
          </a>
        </div>
      </div>

    </div>
  </div>
</section>


  <!-- FORM + MAP -->
  <section class="section section-contact-main" id="modulo-contatto">
    <div class="container">
      <div class="row g-5">
        <!-- FORM -->
        <div class="col-lg-6" data-aos="fade-right">
          <div class="contact-form-wrapper">
            <h2 class="section-title">Invia un messaggio</h2>
            <p class="section-subtitle mb-4">Compila il form e ti risponderemo entro 24 ore</p>

            <form id="contactForm"
                  class="contact-form"
                  method="post"
                  action="<?= $contact_endpoint; ?>"
                  data-ajax="true"
                  novalidate>
              <?= csrf_field(); ?>

              <!-- Honeypot antispam -->
              <input type="text" name="website" autocomplete="off" tabindex="-1" class="hp-field" aria-hidden="true">

              <div class="row g-3">
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="form-label" for="cf-name">Nome *</label>
                    <input id="cf-name" type="text" class="form-control" name="name" required>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="form-label" for="cf-surname">Cognome *</label>
                    <input id="cf-surname" type="text" class="form-control" name="surname" required>
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="form-group">
                    <label class="form-label" for="cf-email">Email *</label>
                    <input id="cf-email" type="email" class="form-control" name="email" required>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="form-label" for="cf-phone">Telefono</label>
                    <input id="cf-phone" type="tel" class="form-control" name="phone" placeholder="000 000 0000" inputmode="tel">
                  </div>
                </div>

                <div class="col-12">
                  <div class="form-group">
                    <label class="form-label" for="cf-subject">Oggetto *</label>
                    <select id="cf-subject" class="form-select" name="subject" required>
                      <option value="">Seleziona un argomento</option>
                      <option value="riparazione">Richiesta Riparazione</option>
                      <option value="preventivo">Richiesta Preventivo</option>
                      <option value="assistenza">Assistenza Tecnica</option>
                      <option value="vendita">Informazioni Vendita</option>
                      <option value="altro">Altro</option>
                    </select>
                  </div>
                </div>

                <div class="col-12">
                  <div class="form-group">
                    <label class="form-label" for="cf-message">Messaggio *</label>
                    <textarea id="cf-message" class="form-control" name="message" rows="5" required placeholder="Descrivi la tua richiesta..."></textarea>
                  </div>
                </div>

                <div class="col-12">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="privacy" name="privacy" required>
                    <label class="form-check-label" for="privacy">
                      Accetto la <a href="<?= url('privacy.php'); ?>" target="_blank" rel="noopener">privacy policy</a> *
                    </label>
                  </div>
                </div>

                <div class="col-12">
                  <button type="submit" class="btn btn-primary btn-lg w-100">
                    <i class="ri-send-plane-line"></i> Invia Messaggio
                  </button>
                </div>
              </div>

              <div class="alert alert-success mt-3 d-none" id="successMessage" role="status" aria-live="polite">
                <i class="ri-check-line"></i> Messaggio inviato con successo! Ti risponderemo presto.
              </div>

              <div class="alert alert-danger mt-3 d-none" id="errorMessage" role="alert" aria-live="assertive">
                <i class="ri-error-warning-line"></i> Si è verificato un errore. Riprova più tardi.
              </div>
            </form>
          </div>
        </div>

        <!-- MAP + HOURS -->
        <div class="col-lg-6" data-aos="fade-left">
          <div class="map-wrapper">
            <h2 class="section-title">Dove siamo</h2>
            <p class="section-subtitle mb-4">Vieni a trovarci nel nostro negozio a Ginosa</p>

            <div class="map-container">
            <!-- Google Maps embed, no redirect -->
            <iframe
                class="gmap-embed"
                src="https://www.google.com/maps?q=<?= urlencode(COMPANY_ADDRESS . ', ' . COMPANY_CITY . ' 74013'); ?>&hl=it&z=16&output=embed"
                title="Mappa: Key Soft Italia"
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"
                allowfullscreen
                aria-label="Mappa interattiva di Key Soft Italia">
            </iframe>

            <!-- Guard per evitare che la mappa “rubì” lo scroll: clicca e si sblocca -->
            <button type="button" class="map-unlock" aria-label="Attiva interazione mappa">
                <i class="ri-hand-pointer-line" aria-hidden="true"></i> Clicca per interagire
            </button>
            </div>

            <div class="opening-hours-box oh-box mt-4" id="opening-hours">
            <div class="oh-head d-flex align-items-center justify-content-between">
                <h4 class="mb-0">
                <i class="ri-time-line" aria-hidden="true"></i> Orari di Apertura
                </h4>
                <span class="oh-chip <?= $__chip_class; ?>">
                <i class="<?= $__chip_icon; ?>" aria-hidden="true"></i> <?= $__chip_label; ?>
                </span>
            </div>

            <?php if (!empty($__todayNotice)): ?>
                <div class="oh-alert oh-alert--special mt-2">
                <i class="ri-megaphone-line" aria-hidden="true"></i>
                <?= htmlspecialchars($__todayNotice, ENT_QUOTES, 'UTF-8'); ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($__note)): ?>
                <div class="oh-note small text-muted mt-1"><?= $__note; ?></div>
            <?php endif; ?>

            <div class="opening-hours-list oh-list mt-3">
                <?php for ($d=1; $d<=7; $d++): 
                $is_today = ($d === (int)$__now->format('N'));
                $row_cls  = $is_today ? 'oh-row is-today' : 'oh-row';
                $intervals = $__table[$d] ?? [];
                ?>
                <div class="<?= $row_cls; ?>">
                    <span class="oh-day"><?= ks_day_label($d); ?></span>
                    <strong class="oh-time"><?= ks_format_intervals($intervals); ?></strong>
                </div>
                <?php endfor; ?>
            </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </section>

  <!-- FAQ -->
  <section class="section section-faq bg-light">
    <div class="container">
      <div class="section-header text-center" data-aos="fade-up">
        <h2 class="section-title">Domande Frequenti</h2>
        <p class="section-subtitle">Le risposte alle domande più comuni</p>
      </div>

      <div class="row justify-content-center" data-aos="fade-up" data-aos-delay="100">
        <div class="col-lg-8">
          <div class="accordion" id="faqAccordion">
            <div class="accordion-item">
              <h3 class="accordion-header">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                  <i class="ri-question-line me-2" aria-hidden="true"></i>Quali sono i vostri orari di apertura?
                </button>
              </h3>
              <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                <div class="accordion-body">
                  Siamo aperti dal lunedì al venerdì dalle 9:00 alle 19:00 (orario continuato) e il sabato dalle 9:00 alle 13:00. La domenica siamo chiusi.
                </div>
              </div>
            </div>

            <div class="accordion-item">
              <h3 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                  <i class="ri-question-line me-2" aria-hidden="true"></i>Offrite servizio di ritiro e consegna?
                </button>
              </h3>
              <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                <div class="accordion-body">
                  Sì, offriamo ritiro e consegna a domicilio per Ginosa e comuni limitrofi. Il servizio ha un costo aggiuntivo variabile in base alla distanza.
                </div>
              </div>
            </div>

            <div class="accordion-item">
              <h3 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                  <i class="ri-question-line me-2" aria-hidden="true"></i>Quanto tempo serve per una riparazione?
                </button>
              </h3>
              <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                <div class="accordion-body">
                  Per le riparazioni più comuni offriamo servizio express in 24-48 ore. Per interventi complessi possono servire 3-5 giorni lavorativi.
                </div>
              </div>
            </div>

            <div class="accordion-item">
              <h3 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                  <i class="ri-question-line me-2" aria-hidden="true"></i>Posso avere un preventivo telefonico?
                </button>
              </h3>
              <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                <div class="accordion-body">
                  Possiamo fornire una stima indicativa per telefono; per un preventivo preciso è necessaria una diagnosi in sede. La diagnosi è gratuita e senza impegno.
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>

    </div>
  </section>

  <!-- CTA -->
  <section class="section section-cta" id="section-cta">
    <div class="container">
      <div class="cta-box text-center" data-aos="zoom-in">
        <h2 class="cta-title">Preferisci contattarci direttamente?</h2>
        <p class="cta-subtitle mb-4">Scegli il metodo che preferisci per contattarci immediatamente</p>
        <div class="cta-buttons">
          <a href="<?= whatsapp_link('Ciao Key Soft Italia!'); ?>" target="_blank" rel="noopener" class="btn btn-success btn-lg">
            <i class="ri-whatsapp-line"></i> WhatsApp
          </a>
          <a href="tel:<?= str_replace(' ', '', COMPANY_PHONE); ?>" class="btn btn-primary btn-lg">
            <i class="ri-phone-line"></i> Chiama Ora
          </a>
        </div>
      </div>
    </div>
  </section>

  <?php include 'includes/footer.php'; ?>

  <!-- JSON-LD LocalBusiness -->
  <script type="application/ld+json">
  {
    "@context":"https://schema.org",
    "@type":"Store",
    "name":"Key Soft Italia",
    "image":"<?= asset('images/og-image.jpg'); ?>",
    "telephone":"<?= COMPANY_PHONE; ?>",
    "email":"<?= COMPANY_EMAIL; ?>",
    "address":{
      "@type":"PostalAddress",
      "streetAddress":"<?= COMPANY_ADDRESS; ?>",
      "addressLocality":"<?= COMPANY_CITY; ?>",
      "postalCode":"74013",
      "addressCountry":"IT"
    },
    "url":"https://<?= $_SERVER['HTTP_HOST']; ?>",
    "sameAs": ["<?= GOOGLE_MAPS_LINK; ?>"],
    "hasMap":"<?= GOOGLE_MAPS_LINK; ?>",
    "openingHoursSpecification":[
      {"@type":"OpeningHoursSpecification","dayOfWeek":["Monday","Tuesday","Wednesday","Thursday","Friday"],"opens":"09:00","closes":"19:00"},
      {"@type":"OpeningHoursSpecification","dayOfWeek":["Saturday"],"opens":"09:00","closes":"13:00"}
    ]
  }
  </script>

  <script src="<?= asset('js/main.js'); ?>" defer></script>
  <script>
  document.addEventListener('DOMContentLoaded', () => {
    // Config per JS
    window.KS_CONFIG = {
      baseUrl: '<?= BASE_URL; ?>',
      whatsappNumber: '<?= WHATSAPP_NUMBER; ?>'
    };

    // Inizializza handler form (usa la tua classe esistente)
    if (typeof FormHandler === 'function') {
      new FormHandler('#contactForm', {
        endpoint: '<?= $contact_endpoint; ?>',
        onSuccess: () => {
          document.getElementById('successMessage')?.classList.remove('d-none');
          document.getElementById('errorMessage')?.classList.add('d-none');
        },
        onError: () => {
          document.getElementById('errorMessage')?.classList.remove('d-none');
          document.getElementById('successMessage')?.classList.add('d-none');
        }
      });
    }

    // Nascondi honeypot ai reader visuali
    const hp = document.querySelector('.hp-field');
    if (hp) { hp.setAttribute('aria-hidden','true'); hp.tabIndex = -1; hp.style.position='absolute'; hp.style.left='-9999px'; }
  });
  </script>
  <script>
document.addEventListener('DOMContentLoaded', function () {
  // Sblocco mappa su click (evita “scroll trapping”)
  const mapBox   = document.querySelector('.map-container');
  const unlockBt = mapBox?.querySelector('.map-unlock');
  const iframe   = mapBox?.querySelector('.gmap-embed');

  if (mapBox && unlockBt && iframe) {
    const enableMap = () => {
      mapBox.classList.add('is-active');
      iframe.style.pointerEvents = 'auto';
      unlockBt.remove();
    };
    unlockBt.addEventListener('click', enableMap);
    unlockBt.addEventListener('keydown', (e) => {
      if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); enableMap(); }
    });
  }
});
</script>
</body>
</html>
