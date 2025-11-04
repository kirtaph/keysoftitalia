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
                        <h2 class="section-title">Invia un messaggio</h2>
            <p class="section-subtitle mb-4">Compila il form e ti risponderemo entro 24 ore</p>
          <div class="contact-form-wrapper">


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
<!-- ACCESSIBILITÀ & PARCHEGGIO -->
<section class="section section-accessibility" id="accessibilita">
  <div class="container">
    <div class="section-header text-center" data-aos="fade-up">
      <h2 class="section-title">Accessibilità &amp; Parcheggio</h2>
      <p class="section-subtitle">Info pratiche per raggiungerci senza stress</p>
    </div>

    <div class="row g-4">
      <!-- Accessibilità -->
      <div class="col-lg-6" data-aos="fade-right">
        <div class="acp-card">
          <div class="acp-row">
            <i class="ri-door-open-line" aria-hidden="true"></i>
            <div>
              <div class="acp-label">Ingresso</div>
              <div class="acp-text">Accesso a raso (senza gradini). Luce porta ≈ 90&nbsp;cm. <span class="acp-note"></span></div>
            </div>
          </div>
          <div class="acp-row">
            <i class="ri-wheelchair-line" aria-hidden="true"></i>
            <div>
              <div class="acp-label">Mobilità ridotta</div>
              <div class="acp-text">Spazio manovra interno ≈ 120&nbsp;cm. Assistenza su richiesta all’ingresso.</div>
            </div>
          </div>
          <div class="acp-row">
            <i class="ri-notification-3-line" aria-hidden="true"></i>
            <div>
              <div class="acp-label">Campanello/Supporto</div>
              <div class="acp-text">Nessun campanello, supporto in negozio per salita/discesa.</div>
            </div>
          </div>
          <div class="acp-help alert alert-light mt-2">
            <i class="ri-hand-heart-line" aria-hidden="true"></i>
            Hai bisogno di aiuto al marciapiede? <a href="tel:<?= str_replace(' ', '', COMPANY_PHONE); ?>">Chiamaci</a> e usciamo noi.
          </div>
        </div>
      </div>

      <!-- Parcheggio & Trasporti -->
      <div class="col-lg-6" data-aos="fade-left">
        <div class="acp-card">
          <div class="acp-row">
            <i class="ri-parking-box-line" aria-hidden="true"></i>
            <div>
              <div class="acp-label">Parcheggi vicini</div>
              <div class="acp-text">Stalli lungo Via Diaz, V.le Martiri D'Ungheria e vie adiacenti. <span class="acp-note"></span></div>
            </div>
          </div>
         <div class="acp-row">
            <i class="ri-wheelchair-line" aria-hidden="true"></i>
            <div>
              <div class="acp-label">Parcheggi disabili</div>
              <div class="acp-text">Stalli per Disabili disponibili all'ingresso. <span class="acp-note"></span></div>
            </div>
          </div>
          <div class="acp-row">
            <i class="ri-road-map-line" aria-hidden="true"></i>
            <div>
              <div class="acp-label">Indicazioni</div>
              <div class="acp-text">Siamo in <?= COMPANY_ADDRESS; ?>, 74013 <?= COMPANY_CITY; ?> (TA).</div>
            </div>
          </div>
          <div class="acp-help alert alert-light mt-2">
            <a href="<?= GOOGLE_MAPS_LINK; ?>" target="_blank" rel="noopener">
              <i class="ri-map-pin-line"></i> Apri in Google Maps
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- TEMPI DI RISPOSTA (SLA) -->
<section class="section section-sla">
  <div class="container">
    <div class="section-header text-center" data-aos="fade-up">
      <h2 class="section-title">Tempi di Risposta</h2>
      <p class="section-subtitle">Stime reali basate sulla nostra operatività</p>
    </div>

    <div class="row g-4 justify-content-center">
      <div class="col-md-4" data-aos="zoom-in">
        <div class="sla-card sla--wa">
          <div class="sla-icon"><i class="ri-whatsapp-line" aria-hidden="true"></i></div>
          <div class="sla-title">WhatsApp</div>
          <div class="sla-time">~ 15 minuti</div>
          <div class="sla-note">Durante l’orario di apertura</div>
        </div>
      </div>
      <div class="col-md-4" data-aos="zoom-in" data-aos-delay="100">
        <div class="sla-card sla--tel">
          <div class="sla-icon"><i class="ri-phone-line" aria-hidden="true"></i></div>
          <div class="sla-title">Telefono</div>
          <div class="sla-time">Immediato</div>
          <div class="sla-note">In negozio o da laboratorio</div>
        </div>
      </div>
      <div class="col-md-4" data-aos="zoom-in" data-aos-delay="200">
        <div class="sla-card sla--mail">
          <div class="sla-icon"><i class="ri-mail-send-line" aria-hidden="true"></i></div>
          <div class="sla-title">Email</div>
          <div class="sla-time">entro 24&nbsp;ore</div>
          <div class="sla-note">Risposte dettagliate e tracciate</div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- AGGIUNGI AI CONTATTI (vCard) -->
<section class="section section-vcard" id="vcard">
  <div class="container">
    <div class="vc-simple" data-aos="zoom-in">
      <div class="vc-simple-text">
        <h3 class="vc-simple-title">
          <i class="ri-contacts-book-2-line" aria-hidden="true"></i>
          Aggiungi ai contatti
        </h3>
        <p class="vc-simple-subtitle">
          Salva in rubrica i nostri riferimenti: telefono, WhatsApp, email e indirizzo.
        </p>
        <small class="vc-simple-note">
          Compatibile con iOS, Android, Outlook e Google Contacts.
        </small>
      </div>
      <div class="vc-simple-actions">
        <a class="btn vc-btn vc-btn--light btn-lg" href="<?= url('vcard.php'); ?>">
          <i class="ri-download-2-line" aria-hidden="true"></i> Scarica vCard (.vcf)
        </a>
      </div>
    </div>
  </div>
</section>

<!-- FAQ -->
<section class="section section-faq bg-light" id="faq">
  <div class="container">
    <div class="section-header text-center" data-aos="fade-up">
      <h2 class="section-title">Domande Frequenti</h2>
      <p class="section-subtitle">Risposte chiare su servizi, tempi e garanzie</p>
    </div>

    <?php
      // Piccolo helper per mostrare gli orari di OGGI in modo dinamico
      $tz        = new DateTimeZone(KS_TZ);
      $now       = new DateTime('now', $tz);
      $todayStr  = strip_tags( ks_format_intervals( ks_intervals_for_date($now) ) );
    ?>

    <div class="row justify-content-center" data-aos="fade-up" data-aos-delay="100">
      <div class="col-lg-8">
        <div class="accordion" id="faqAccordion">

          <!-- Orari -->
          <div class="accordion-item">
            <h3 class="accordion-header">
              <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                <i class="ri-time-line me-2" aria-hidden="true"></i>Quali sono i vostri orari di apertura?
              </button>
            </h3>
            <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
              <div class="accordion-body">
                Oggi: <strong><?= $todayStr ?: 'Chiuso'; ?></strong>.  
                Gli orari completi e lo stato “aperti/chiusi” in tempo reale sono nel box <a href="#opening-hours">Orari di Apertura</a> e nella topbar.
              </div>
            </div>
          </div>

          <!-- Appuntamento sì/no -->
          <div class="accordion-item">
            <h3 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                <i class="ri-calendar-check-line me-2" aria-hidden="true"></i>Serve appuntamento o posso venire direttamente?
              </button>
            </h3>
            <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
              <div class="accordion-body">
                Puoi venire senza appuntamento. Se vuoi velocizzare diagnosi e presa in carico, scrivici prima su WhatsApp: compilando due info essenziali arriviamo pronti.
              </div>
            </div>
          </div>

          <!-- Diagnosi/preventivo -->
          <div class="accordion-item">
            <h3 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                <i class="ri-search-eye-line me-2" aria-hidden="true"></i>La diagnosi è a pagamento?
              </button>
            </h3>
            <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
              <div class="accordion-body">
                La diagnosi è <strong>gratuita e senza impegno</strong>. Prima di procedere con qualsiasi riparazione ti comunichiamo costi, tempi e garanzie.
              </div>
            </div>
          </div>

          <!-- Tempi riparazioni -->
          <div class="accordion-item">
            <h3 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                <i class="ri-timer-line me-2" aria-hidden="true"></i>Quanto tempo serve per una riparazione?
              </button>
            </h3>
            <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
              <div class="accordion-body">
                Per le riparazioni più comuni su smartphone/PC offriamo servizio <strong>express 24–48h</strong>.  
                Interventi complessi (es. <em>microsaldature</em> su scheda logica) richiedono in media <strong>3–5 giorni lavorativi</strong>.  
                Se servono ricambi non in stock, i tempi dipendono dalla fornitura: ti aggiorniamo noi.
              </div>
            </div>
          </div>

          <!-- Ricambi & Garanzia -->
          <div class="accordion-item">
            <h3 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq5">
                <i class="ri-shield-check-line me-2" aria-hidden="true"></i>Usate ricambi originali? Che garanzia ho?
              </button>
            </h3>
            <div id="faq5" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
              <div class="accordion-body">
                Utilizziamo <strong>ricambi originali</strong> quando disponibili, oppure <strong>ricambi top quality/OEM</strong> testati in laboratorio.  
                Ogni intervento è coperto da <strong>garanzia su ricambio e lavorazione</strong> (durata e condizioni sono indicate nel preventivo e nel documento di consegna).
              </div>
            </div>
          </div>

          <!-- Dati & Privacy -->
          <div class="accordion-item">
            <h3 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq6">
                <i class="ri-lock-2-line me-2" aria-hidden="true"></i>Perdo i miei dati? Come gestite la privacy?
              </button>
            </h3>
            <div id="faq6" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
              <div class="accordion-body">
                In genere <strong>non perdi i dati</strong>. Se un intervento può comportare rischio per i dati, ti informiamo prima e proponiamo un <strong>backup</strong>.  
                Trattiamo i dispositivi nel rispetto della <strong>privacy</strong> e del principio di <em>minimo accesso</em>. Su richiesta eseguiamo <strong>wipe sicuro</strong> prima della riconsegna.
              </div>
            </div>
          </div>

          <!-- Ritiro & Consegna -->
          <div class="accordion-item">
            <h3 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq7">
                <i class="ri-truck-line me-2" aria-hidden="true"></i>Fate ritiro e consegna a domicilio?
              </button>
            </h3>
            <div id="faq7" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
              <div class="accordion-body">
                Sì, su <strong>Ginosa e comuni limitrofi</strong>. Il costo dipende dalla distanza e dall’urgenza.  
                Contattaci su WhatsApp per una quotazione rapida e per fissare giorno/ora.
              </div>
            </div>
          </div>

          <!-- Assistenza remota -->
          <div class="accordion-item">
            <h3 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq8">
                <i class="ri-remote-control-2-line me-2" aria-hidden="true"></i>Fornite assistenza remota?
              </button>
            </h3>
            <div id="faq8" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
              <div class="accordion-body">
                Certo. Scarica <strong>AnyDesk/TeamViewer</strong>, condividi l’ID e interveniamo da remoto.  
                Trovi la sezione dedicata più su (“Assistenza remota”) con pulsanti di <em>download</em> e invio ID via WhatsApp.
              </div>
            </div>
          </div>

          <!-- Ricondizionati -->
          <div class="accordion-item">
            <h3 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq9">
                <i class="ri-smartphone-line me-2" aria-hidden="true"></i>Vendete smartphone/PC ricondizionati?
              </button>
            </h3>
            <div id="faq9" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
              <div class="accordion-body">
                Sì, selezione <strong>ricondizionati</strong> testati in laboratorio e <strong>garantiti</strong>, con accessori e scontrino/fattura.  
                Passa in negozio per disponibilità e valutazione <em>permuta</em> dell’usato.
              </div>
            </div>
          </div>

          <!-- Pagamenti & Fattura -->
          <div class="accordion-item">
            <h3 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq10">
                <i class="ri-receipt-line me-2" aria-hidden="true"></i>Quali pagamenti accettate? Fate fattura?
              </button>
            </h3>
            <div id="faq10" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
              <div class="accordion-body">
                Accettiamo <strong>contanti, carte e bancomat</strong>. Rilasciamo <strong>scontrino o fattura</strong> (anche per aziende) su richiesta.
              </div>
            </div>
          </div>

          <!-- Pellicole MyShape & protezioni -->
          <div class="accordion-item">
            <h3 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq11">
                <i class="ri-scissors-cut-line me-2" aria-hidden="true"></i>Fate pellicole su misura (MyShape)?
              </button>
            </h3>
            <div id="faq11" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
              <div class="accordion-body">
                Sì: <strong>taglio su misura</strong> in negozio (display e retro) con finiture a scelta.  
                Applichiamo noi per una resa perfetta e garantita.
              </div>
            </div>
          </div>

        </div><!-- /accordion -->
      </div>
    </div>
  </div>
</section>

 <!-- CTA -->
<section class="section section-cta" id="section-cta">
  <div class="container">
    <?php
      $tz   = new DateTimeZone(KS_TZ);
      $now  = new DateTime('now', $tz);
      $st   = ks_is_open_now($now);
      $open = $st['open'];

      $chipCls = $open ? 'cta-chip cta-chip--open' : 'cta-chip cta-chip--closed';
      $chipIco = $open ? 'ri-checkbox-circle-line' : 'ri-close-circle-line';
      $chipTxt = $open ? 'Aperti ora' : 'Chiusi ora';

      if ($open) {
        $note = 'Chiude alle '.$st['end']->format('H:i').' (tra '.ks_human_diff($now, $st['end']).')';
      } else {
        $nxt  = ks_next_open_after($now);
        $note = $nxt
          ? 'Riapre '.($nxt->format('Ymd')===$now->format('Ymd') ? 'alle '.$nxt->format('H:i') : ks_day_label((int)$nxt->format('N')).' alle '.$nxt->format('H:i'))
            .' (tra '.ks_human_diff($now, $nxt).')'
          : 'Chiuso';
      }
    ?>

    <div class="cta-panel" data-aos="zoom-in">
      <div class="row align-items-center g-4">
        <!-- Testo -->
        <div class="col-lg-7">
          <div class="d-flex align-items-center gap-2 mb-2">
            <span class="<?= $chipCls; ?>"><i class="<?= $chipIco; ?>" aria-hidden="true"></i> <?= $chipTxt; ?></span>
            <small class="cta-note"><?= $note; ?></small>
          </div>
          <h2 class="cta-title">Parliamo adesso?</h2>
          <p class="cta-subtitle">Scegli il canale che preferisci: rispondiamo davvero.</p>

          <!-- micro-SLA -->
          <ul class="cta-sla list-unstyled d-flex flex-wrap gap-3 mb-0">
            <li><i class="ri-whatsapp-line" aria-hidden="true"></i> WhatsApp <strong>~15 min</strong></li>
            <li><i class="ri-phone-line" aria-hidden="true"></i> Telefono <strong>immediato</strong></li>
            <li><i class="ri-mail-send-line" aria-hidden="true"></i> Email <strong>entro 24h</strong></li>
          </ul>
        </div>

        <!-- Bottoni -->
        <div class="col-lg-5">
          <div class="cta-buttons">
            <a href="<?= whatsapp_link('Ciao Key Soft Italia!'); ?>" target="_blank" rel="noopener" class="btn cta-btn cta-btn--wa btn-lg w-100">
              <i class="ri-whatsapp-line"></i> Scrivici su WhatsApp
            </a>
            <a href="tel:<?= str_replace(' ', '', COMPANY_PHONE); ?>" class="btn cta-btn cta-btn--tel btn-lg w-100">
              <i class="ri-phone-line"></i> Chiama Ora
            </a>
            <a href="mailto:<?= COMPANY_EMAIL; ?>" class="btn cta-btn cta-btn--mail btn-lg w-100">
              <i class="ri-mail-line"></i> Invia Email
            </a>
          </div>
        </div>
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
