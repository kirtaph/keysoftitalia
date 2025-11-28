<?php
/**
 * Key Soft Italia - Prenota Riparazione (Wizard 3 step)
 * Step 1: Dispositivo/Marca/Modello (brand da DB, modello facoltativo)
 * Step 2: Dettagli prenotazione (data, fascia oraria, modalità consegna, breve descrizione)
 * Step 3: Dati cliente + riepilogo + invio (mail o WhatsApp)
 */

if (!defined('BASE_PATH')) {
  define('BASE_PATH', __DIR__ . '/');
}
require_once BASE_PATH . 'config/config.php';

$page_title       = "Prenota la tua Riparazione - Key Soft Italia | Appuntamento Veloce";
$page_description = "Prenota online la riparazione del tuo dispositivo: scegli giorno, fascia oraria e modalità di consegna. Conferma rapida senza attese al telefono.";
$page_keywords    = "prenota riparazione, appuntamento assistenza, riparazione smartphone prenotazione, key soft italia";

$breadcrumbs = [
  ['label' => 'Prenota Riparazione', 'url' => 'prenota-riparazione.php']
];

// Riferimento esterno (es. ORD-2025-0001) passato via link WhatsApp ?ref=...
$booking_ref = isset($_GET['ref']) ? preg_replace('/[^A-Za-z0-9\-\_]/', '', $_GET['ref']) : '';
?>
<!DOCTYPE html>
<html lang="it">
<head>
  <?php include 'includes/head.php'; ?>
  <title><?php echo htmlspecialchars($page_title); ?></title>
  <?php echo generate_meta_tags([
      'title'       => $page_title,
      'description' => $page_description,
      'keywords'    => $page_keywords,
      'url'         => url('prenota-riparazione.php')
  ]); ?>
  <link rel="stylesheet" href="<?php echo asset_version('css/pages/prenota-riparazione.css'); ?>">
</head>
<body data-aos-easing="ease-in-out" data-aos-duration="800" data-aos-once="true">
<?php include 'includes/header.php'; ?>

<!-- HERO -->
<section class="hero hero-secondary text-center">
  <div class="hero-pattern"></div>
  <div class="container position-relative z-2">
    <div class="hero-icon mb-3" data-aos="zoom-in"><i class="ri-calendar-check-line"></i></div>
    <h1 class="hero-title text-white" data-aos="fade-up">
      Prenota la tua <span class="text-gradient">Riparazione</span>
    </h1>
    <p class="hero-subtitle" data-aos="fade-up" data-aos-delay="100">
      Fissa giorno e orario in soli 3 step.
    </p>
    <div class="hero-breadcrumb mt-3" data-aos="fade-up" data-aos-delay="150">
      <?= generate_breadcrumbs($breadcrumbs); ?>
    </div>
  </div>
</section>

<!-- WIZARD -->
<section class="section quote-wizard" aria-labelledby="titolo-wizard">
  <div class="container">
    <div class="form-card" data-aos="fade-up">
      <!-- Stepper -->
      <div class="ks-stepper" aria-hidden="true">
        <div class="ks-step active" data-step="1">
          <span class="ks-step-num">1</span>
          <span class="ks-step-label">Dispositivo</span>
        </div>
        <div class="ks-step" data-step="2">
          <span class="ks-step-num">2</span>
          <span class="ks-step-label">Appuntamento</span>
        </div>
        <div class="ks-step" data-step="3">
          <span class="ks-step-num">3</span>
          <span class="ks-step-label">Dati & Conferma</span>
        </div>
      </div>

      <h2 id="wizard_title" class="form-title">Prenota la tua Riparazione</h2>
      <p id="wizard_subtitle" class="form-subtitle">
        Completa i 3 step: <strong>dispositivo</strong> → <strong>appuntamento</strong> → <strong>dati e conferma</strong>.
      </p>

      <form id="bookingWizard"
            method="POST"
            action="<?php echo url('assets/process/process_booking.php'); ?>"
            novalidate>
        <?php echo generate_csrf_field(); ?>
        <input type="text" name="website" class="d-none" tabindex="-1" autocomplete="off"><!-- honeypot -->

        <?php if ($booking_ref): ?>
          <input type="hidden" name="booking_ref" value="<?php echo htmlspecialchars($booking_ref); ?>">
        <?php endif; ?>

        <!-- ================= STEP 1 ================= -->
        <fieldset class="ks-step-pane" data-step="1">
          <div class="step-block">
            <h3 class="block-title"><i class="ri-device-line"></i> Seleziona il dispositivo</h3>
            <div class="device-grid" role="group" aria-label="Tipi di dispositivo">
              <button type="button" class="device-card" data-device="smartphone" aria-pressed="false">
                <i class="ri-smartphone-line"></i><span>Smartphone</span>
              </button>
              <button type="button" class="device-card" data-device="tablet" aria-pressed="false">
                <i class="ri-tablet-line"></i><span>Tablet</span>
              </button>
              <button type="button" class="device-card" data-device="computer" aria-pressed="false">
                <i class="ri-computer-line"></i><span>Computer/Notebook</span>
              </button>
              <button type="button" class="device-card" data-device="console" aria-pressed="false">
                <i class="ri-gamepad-line"></i><span>Console</span>
              </button>
              <button type="button" class="device-card" data-device="tv" aria-pressed="false">
                <i class="ri-tv-2-line"></i><span>TV</span>
              </button>
              <button type="button" class="device-card" data-device="altro" aria-pressed="false">
                <i class="ri-more-line"></i><span>Altro</span>
              </button>
            </div>
          </div>

          <div class="step-block">
            <h3 class="block-title"><i class="ri-price-tag-3-line"></i> Marca & Modello</h3>
            <div class="row g-3 align-items-end">
              <!-- Brand (da DB) -->
              <div class="col-md-4">
                <label class="form-label" for="brand">Marca *</label>
                <select class="form-select" id="brand" name="brand" required disabled>
                  <option value="">Seleziona il dispositivo prima…</option>
                </select>
              </div>

              <!-- Brand “Altro” -->
              <div class="col-md-4 d-none" id="brand-other-wrap">
                <label class="form-label" for="brand_other">Specifica marca *</label>
                <input type="text" class="form-control" id="brand_other" name="brand_other">
              </div>

              <!-- Modello -->
              <div class="col-md-4" id="model-wrap">
                <label class="form-label" id="model-label" for="model">Modello</label>
                <!-- testo libero di default -->
                <input type="text" class="form-control" id="model" name="model" placeholder="es. iPhone 13">
                <!-- select model da DB -->
                <select class="form-select d-none mt-2" id="model_select" name="model_select"></select>
              </div>

              <!-- Altro modello -->
              <div class="col-md-4 d-none" id="model-other-wrap">
                <label class="form-label" for="model_other">Specifica modello *</label>
                <input type="text" class="form-control" id="model_other" name="model_other">
              </div>
            </div>
          </div>

          <div class="ks-actions">
            <div class="ks-left">
              <button type="button" class="btn btn-light ks-goto-emergency">
                <i class="ri-alarm-warning-line me-1"></i> <span class="btn-text">Ho un’urgenza</span>
              </button>
            </div>
            <div class="ks-right">
              <button type="button" class="btn btn-next ks-next">
                <span class="btn-text">Avanti</span> <i class="ri-arrow-right-line ms-1"></i>
              </button>
            </div>
          </div>
        </fieldset>

        <!-- ================= STEP 2 ================= -->
        <fieldset class="ks-step-pane d-none" data-step="2">
          <div class="step-block">
            <h3 class="block-title"><i class="ri-calendar-line"></i> Dettagli appuntamento</h3>
            <div class="row g-3">
              <div class="col-md-4">
                <label class="form-label" for="preferred_date">Giorno preferito *</label>
                <input type="date" class="form-control" id="preferred_date" name="preferred_date" required>
              </div>
              <div class="col-md-4">
                <label class="form-label" for="preferred_time_slot">Fascia oraria *</label>
                <select class="form-select" id="preferred_time_slot" name="preferred_time_slot" required>
                  <option value="">Seleziona…</option>
                  <option value="mattina">Mattina</option>
                  <option value="pomeriggio">Pomeriggio</option>
                  <option value="sera">Sera</option>
                </select>
              </div>
              <div class="col-md-4">
                <label class="form-label" for="dropoff_type">Modalità di consegna *</label>
                <select class="form-select" id="dropoff_type" name="dropoff_type" required>
                  <option value="">Seleziona…</option>
                  <option value="in_store">Porto il dispositivo in negozio</option>
                  <option value="pickup">Ritiro a domicilio (se disponibile)</option>
                  <option value="on_site">Assistenza a domicilio (solo PC)</option>
                </select>
              </div>
              <div class="col-12">
                <label class="form-label" for="problem_summary">Breve descrizione del problema *</label>
                <textarea class="form-control"
                          id="problem_summary"
                          name="problem_summary"
                          rows="3"
                          required
                          placeholder="Esempio: sostituzione display già concordata, non si accende dopo la caduta, batteria dura poco…"></textarea>
              </div>
              <div class="col-12">
                <label class="form-label" for="notes">Note aggiuntive (opzionale)</label>
                <textarea class="form-control"
                          id="notes"
                          name="notes"
                          rows="2"
                          placeholder="Es. dispositivo di lavoro, orari particolari, accesso al citofono, ecc."></textarea>
              </div>
            </div>
          </div>

          <div class="ks-actions">
            <div class="ks-left">
              <button type="button" class="btn btn-outline-secondary ks-prev">
                <i class="ri-arrow-left-line me-1"></i> <span class="btn-text">Indietro</span>
              </button>
            </div>
            <div class="ks-right">
              <button type="button" class="btn btn-next ks-next">
                <span class="btn-text">Avanti</span> <i class="ri-arrow-right-line ms-1"></i>
              </button>
            </div>
          </div>
        </fieldset>

        <!-- ================= STEP 3 ================= -->
        <fieldset class="ks-step-pane d-none" data-step="3">
          <div class="step-block">
            <h3 class="block-title"><i class="ri-user-line"></i> I tuoi dati</h3>
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label" for="firstName">Nome *</label>
                <input type="text" class="form-control" id="firstName" name="firstName" required>
              </div>
              <div class="col-md-6">
                <label class="form-label" for="lastName">Cognome *</label>
                <input type="text" class="form-control" id="lastName" name="lastName" required>
              </div>
              <div class="col-md-6">
                <label class="form-label" for="email">Email *</label>
                <input type="email" class="form-control" id="email" name="email" required>
              </div>
              <div class="col-md-6">
                <label class="form-label" for="phone">Telefono *</label>
                <input type="tel"
                       class="form-control"
                       id="phone"
                       name="phone"
                       required
                       inputmode="tel"
                       placeholder="3xx xxx xxxx">
              </div>
              <div class="col-12">
                <label class="form-label" for="company">Azienda (opzionale)</label>
                <input type="text" class="form-control" id="company" name="company" placeholder="Nome dell'azienda (facoltativo)">
              </div>
              <div class="col-12">
                <label class="form-label" for="contact_channel">Preferenza di contatto</label>
                <select class="form-select" id="contact_channel" name="contact_channel">
                  <option value="">Seleziona…</option>
                  <option value="whatsapp">WhatsApp</option>
                  <option value="telefono">Telefonata</option>
                  <option value="email">Email</option>
                </select>
              </div>
            </div>

            <div class="mt-3">
              <div class="form-check mb-2">
                <input class="form-check-input" type="checkbox" value="1" id="backup_done" name="backup_done">
                <label class="form-check-label" for="backup_done">
                  Ho già eseguito il backup dei miei dati (se possibile)
                </label>
              </div>
              <div class="form-check mb-2">
                <input class="form-check-input" type="checkbox" value="1" id="tests_ok" name="tests_ok">
                <label class="form-check-label" for="tests_ok">
                  Autorizzo il laboratorio a effettuare test funzionali sul dispositivo
                </label>
              </div>
              <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" value="1" id="privacy" name="privacy" required>
                <label class="form-check-label" for="privacy">
                  Ho letto e accetto la <a href="<?php echo url('privacy.php'); ?>" target="_blank" rel="noopener">Privacy Policy</a> *
                </label>
              </div>
            </div>
          </div>

          <div class="step-block">
            <h3 class="block-title"><i class="ri-file-list-2-line"></i> Riepilogo</h3>
            <div id="summary-box" class="summary-box">
              <ul id="summary-list" class="summary-list"></ul>
              <p class="summary-note small text-muted mb-0">
                Controlla che i dati siano corretti prima di confermare la prenotazione.
              </p>
            </div>
          </div>

          <div class="ks-actions">
            <div class="ks-left">
              <button type="button" class="btn btn-outline-secondary ks-prev">
                <i class="ri-arrow-left-line me-1"></i> <span class="btn-text">Indietro</span>
              </button>
            </div>
            <div class="ks-right">
              <button type="button" class="btn btn-primary ks-submit" id="btn_submit">
                <i class="ri-calendar-check-line me-1"></i> <span class="btn-text">Conferma prenotazione</span>
              </button>
              <button type="button" class="btn btn-outline-success ks-submit" id="btn_whatsapp">
                <i class="ri-whatsapp-line me-1"></i> <span class="btn-text">Conferma e apri WhatsApp</span>
              </button>
            </div>
          </div>
        </fieldset>
      </form>
    </div>
  </div>
</section>

<!-- EMERGENZA (riuso stile preventivo) -->
<section class="emergency-wide-wrapper">
  <div class="container">
    <div class="row mt-4" data-aos="fade-up">
      <div class="col-12">
        <section class="emergency-wide em-centered" role="region" aria-labelledby="emergenza-title">
          <div class="ew-header">
            <div class="ew-beacon" aria-hidden="true">
              <span class="pulse"></span>
              <i class="ri-alarm-warning-line"></i>
            </div>
            <div>
              <h3 id="emergenza-title" class="mb-1">Riparazione urgente?</h3>
              <p class="mb-0">Attiva la <strong>corsia prioritaria</strong> via chiamata o WhatsApp.</p>
            </div>
          </div>
          <ul class="ew-points centered">
            <li><i class="ri-flashlight-line"></i> Gestione prioritaria</li>
            <li><i class="ri-shield-check-line"></i> Tecnici certificati</li>
            <li><i class="ri-time-line"></i> Risposta entro poche ore</li>
          </ul>
          <div class="ew-actions">
            <a href="tel:<?php echo PHONE_PRIMARY; ?>" class="btn btn-em-call btn-lg" rel="noopener">
              <i class="ri-phone-line me-1"></i> Chiama Subito
              <span class="phone-number">: <?php echo PHONE_PRIMARY; ?></span>
            </a>
            <a href="<?php echo whatsapp_link('🚨 EMERGENZA RIPARAZIONE: richiesta prioritaria'); ?>"
               class="btn btn-em-wa btn-lg"
               id="wa-emergency"
               target="_blank"
               rel="noopener">
              <i class="ri-whatsapp-line me-1"></i> WhatsApp Urgente
            </a>
            <small class="ew-note">Fuori orario? Scrivi su WhatsApp, ti rispondiamo alla prima apertura.</small>
          </div>
        </section>
      </div>
    </div>
  </div>
</section>

<!-- COME FUNZIONA -->
<section class="section section-process fancy-process">
  <div class="container">
    <div class="section-header text-center">
      <h2 class="section-title">Come funziona la prenotazione</h2>
      <p class="section-subtitle">Dalla richiesta al ritiro, in 4 passi</p>
    </div>

    <ol class="row g-4 process-steps align-items-stretch">
      <li class="col-md-6 col-lg-3">
        <div class="process-item" data-aos="fade-up">
          <div class="step-head">
            <span class="step-badge">1</span>
            <span class="step-icon"><i class="ri-edit-line" aria-hidden="true"></i></span>
          </div>
          <h3 class="step-title">Prenotazione</h3>
          <p class="step-text">Compili il form o ci scrivi su WhatsApp con i dettagli del problema.</p>
        </div>
      </li>

      <li class="col-md-6 col-lg-3">
        <div class="process-item" data-aos="fade-up" data-aos-delay="60">
          <div class="step-head">
            <span class="step-badge">2</span>
            <span class="step-icon"><i class="ri-calendar-line" aria-hidden="true"></i></span>
          </div>
          <h3 class="step-title">Conferma</h3>
          <p class="step-text">Ti confermiamo giorno, orario e modalità di consegna.</p>
        </div>
      </li>

      <li class="col-md-6 col-lg-3">
        <div class="process-item" data-aos="fade-up" data-aos-delay="120">
          <div class="step-head">
            <span class="step-badge">3</span>
            <span class="step-icon"><i class="ri-tools-line" aria-hidden="true"></i></span>
          </div>
          <h3 class="step-title">Riparazione</h3>
          <p class="step-text">Il tecnico esegue diagnostica e riparazione, tenendoti aggiornato.</p>
        </div>
      </li>

      <li class="col-md-6 col-lg-3">
        <div class="process-item" data-aos="fade-up" data-aos-delay="180">
          <div class="step-head">
            <span class="step-badge">4</span>
            <span class="step-icon"><i class="ri-checkbox-circle-line" aria-hidden="true"></i></span>
          </div>
          <h3 class="step-title">Ritiro / Consegna</h3>
          <p class="step-text">Ritiri il dispositivo in negozio o lo riconsegniamo (se previsto).</p>
        </div>
      </li>
    </ol>

    <!-- Trust badges -->
    <div class="trust-badges" data-aos="fade-up" data-aos-delay="240" aria-label="Punti di fiducia">
      <div class="tb-item">
        <i class="ri-shield-check-line" aria-hidden="true"></i>
        <span>Garanzia 3 mesi</span>
      </div>
      <div class="tb-item">
        <i class="ri-tools-line" aria-hidden="true"></i>
        <span>Ricambi di qualità</span>
      </div>
      <div class="tb-item">
        <i class="ri-time-line" aria-hidden="true"></i>
        <span>Diagnosi rapida</span>
      </div>
    </div>
  </div>
</section>

<!-- FAQ -->
<section class="section section-faq bg-light">
  <div class="container">
    <div class="section-header text-center">
      <h2 class="section-title">Domande frequenti sulla prenotazione</h2>
    </div>
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <div class="accordion accordion-faq" id="faqBooking">
          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faqb1">
                La prenotazione è vincolante?
              </button>
            </h2>
            <div id="faqb1" class="accordion-collapse collapse show" data-bs-parent="#faqBooking">
              <div class="accordion-body">
                No, la prenotazione è <strong>senza impegno</strong> fino alla conferma definitiva con il tecnico.
                Se cambia qualcosa puoi avvisarci e spostiamo l’appuntamento.
              </div>
            </div>
          </div>

          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqb2">
                Devo lasciare un acconto?
              </button>
            </h2>
            <div id="faqb2" class="accordion-collapse collapse" data-bs-parent="#faqBooking">
              <div class="accordion-body">
                Di norma <strong>non chiediamo acconti</strong> per la prenotazione.
                In caso di ricambi particolari o su misura ti verrà comunicato prima della conferma.
              </div>
            </div>
          </div>

          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqb3">
                Cosa devo portare insieme al dispositivo?
              </button>
            </h2>
            <div id="faqb3" class="accordion-collapse collapse" data-bs-parent="#faqBooking">
              <div class="accordion-body">
                Porta almeno il <strong>dispositivo</strong> e, se possibile, il
                <strong>caricabatterie originale</strong>. Se hai backup già fatto, indicalo nel form:
                ci aiuta a velocizzare i test.
              </div>
            </div>
          </div>

          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqb4">
                Posso spostare o annullare la prenotazione?
              </button>
            </h2>
            <div id="faqb4" class="accordion-collapse collapse" data-bs-parent="#faqBooking">
              <div class="accordion-body">
                Certo, basta contattarci via telefono o WhatsApp il prima possibile:
                trovi i recapiti in fondo alla pagina e nel messaggio di conferma.
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
</section>

<?php include 'includes/footer.php'; ?>

<!-- JS -->
<script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script defer src="<?php echo asset('js/main.js'); ?>"></script>
<script defer src="https://unpkg.com/aos@next/dist/aos.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function(){
  if (window.AOS) AOS.init();

  // Imposta data minima = oggi
  const dateInput = document.getElementById('preferred_date');
  if (dateInput){
    const today = new Date();
    const iso = today.toISOString().split('T')[0];
    dateInput.setAttribute('min', iso);
  }
});
</script>

<!-- Wizard Booking -->
<script>
(function(){
  // ---- Endpoints (riuso get_brands / get_models)
  const ENDPOINTS = {
    brands: "<?= url('assets/ajax/get_brands.php'); ?>",
    models: "<?= url('assets/ajax/get_models.php'); ?>",
    process: "<?= url('assets/process/process_booking.php'); ?>"
  };
  const ALT_BRAND_VALUE = "__other_brand__";
  const ALT_MODEL_VALUE = "__other_model__";

  // ---- Elements
  const wizard   = document.getElementById('bookingWizard');
  if (!wizard) return;

  const panes    = [...wizard.querySelectorAll('.ks-step-pane')];
  const steps    = [...document.querySelectorAll('.ks-stepper .ks-step')];
  const nextBtns = wizard.querySelectorAll('.ks-next');
  const prevBtns = wizard.querySelectorAll('.ks-prev');
  const goEmerg  = wizard.querySelectorAll('.ks-goto-emergency');

  const brandSel        = document.getElementById('brand');
  const brandOtherWrap  = document.getElementById('brand-other-wrap');
  const brandOther      = document.getElementById('brand_other');

  const modelWrap       = document.getElementById('model-wrap');
  const modelLabel      = document.getElementById('model-label');
  const modelSelect     = document.getElementById('model_select');
  const modelInput      = document.getElementById('model');
  const modelOtherWrap  = document.getElementById('model-other-wrap');
  const modelOther      = document.getElementById('model_other');

  const problemSummary  = document.getElementById('problem_summary');
  const preferredDate   = document.getElementById('preferred_date');
  const preferredSlot   = document.getElementById('preferred_time_slot');
  const dropoffType     = document.getElementById('dropoff_type');

  const firstName       = document.getElementById('firstName');
  const lastName        = document.getElementById('lastName');
  const email           = document.getElementById('email');
  const phone           = document.getElementById('phone');
  const company         = document.getElementById('company');
  const contactChannel  = document.getElementById('contact_channel');
  const privacy         = document.getElementById('privacy');

  const summaryBox  = document.getElementById('summary-box');
  const summaryList = document.getElementById('summary-list');

  const waBtn   = document.getElementById('btn_whatsapp');
  const mailBtn = document.getElementById('btn_submit');

  const emSection = document.querySelector('.emergency-wide');
  const csrfEl    = wizard.querySelector('input[name="csrf_token"], input[name="_csrf"], input[name="csrf"]');

  // ---- Data
  let current = 1;
  let selectedDevice = null;

  // ---- Helpers
  function normalizeText(s){ return (s || '').toString().trim().replace(/\s+/g,' '); }

  function waLinkFromText(text){
    const phone = "<?= preg_replace('/\D+/', '', (defined('PHONE_WHATSAPP') ? PHONE_WHATSAPP : PHONE_PRIMARY)); ?>";
    return `https://wa.me/${phone}?text=${encodeURIComponent(text)}`;
  }

  function getBrandDisplay(){
    if (brandSel.value === ALT_BRAND_VALUE) return normalizeText(brandOther.value);
    const opt = brandSel.options[brandSel.selectedIndex];
    return (opt ? (opt.dataset.name || opt.textContent) : '').trim();
  }
  function getModelDisplay(){
    if (!modelInput.classList.contains('d-none')){
      return normalizeText(modelInput.value);
    }
    if (modelSelect.value === ALT_MODEL_VALUE) return normalizeText(modelOther.value);
    return modelSelect.value || '';
  }

  // ---- Device cards
  const deviceCards = document.querySelectorAll('.device-card');
  deviceCards.forEach(btn=>{
    btn.addEventListener('click', ()=>{
      deviceCards.forEach(b=>{
        const sel = (b===btn);
        b.classList.toggle('selected', sel);
        b.setAttribute('aria-pressed', sel ? 'true' : 'false');
      });
      selectedDevice = btn.dataset.device;
      loadBrandsForDevice(selectedDevice);
    });
  });

  // ---- Brands / Models (DB)
  async function loadBrandsForDevice(device){
    brandSel.disabled = true;
    brandSel.innerHTML = `<option value="">Carico marche…</option>`;
    brandOtherWrap.classList.add('d-none'); brandOther.value = '';
    resetModelUI();

    if (!device){
      brandSel.innerHTML = `<option value="">Seleziona il dispositivo prima…</option>`;
      return;
    }

    try{
      const res = await fetch(`${ENDPOINTS.brands}?device=${encodeURIComponent(device)}`, {
        headers: { 'Accept':'application/json' },
        credentials: 'same-origin'
      });
      const data = await res.json();
      const brands = (data && Array.isArray(data.brands)) ? data.brands : [];
      if (!brands.length){
        brandSel.innerHTML = `<option value="">Nessuna marca trovata, scrivi la marca…</option>`;
        brandSel.disabled = true;
        brandOtherWrap.classList.remove('d-none');
        return;
      }

      brandSel.innerHTML = `<option value="">Seleziona…</option>` + brands.map(b=>{
        const id = b.id ?? '';
        const name = (b.name ?? '').toString();
        const hasModels = !!b.has_models;
        return `<option value="${id}" data-name="${name.replace(/"/g,'&quot;')}" data-has-models="${hasModels ? '1':'0'}">${name}</option>`;
      }).join('');
      brandSel.disabled = false;
    } catch(e){
      brandSel.innerHTML = `<option value="">Errore: ricarica la pagina</option>`;
      showToast({type:'danger', title:'Marche', message:'Impossibile caricare le marche.'});
    }
  }

  function resetModelUI(){
    modelSelect.classList.add('d-none');
    modelSelect.innerHTML = '';
    modelInput.classList.remove('d-none');
    modelInput.value = '';
    modelOtherWrap.classList.add('d-none');
    modelOther.value = '';
  }

  async function loadModelsForBrand(brandId){
    resetModelUI();
    if (!brandId) return;

    const opt = brandSel.options[brandSel.selectedIndex];
    const hasModels = opt && opt.dataset.hasModels === '1';
    if (!hasModels){
      // non ci sono modelli in DB, resta input libero
      return;
    }

    modelSelect.classList.remove('d-none');
    modelSelect.innerHTML = `<option value="">Carico modelli…</option>`;
    modelInput.classList.add('d-none');

    try{
      const res = await fetch(`${ENDPOINTS.models}?brand_id=${encodeURIComponent(brandId)}`, {
        headers: { 'Accept':'application/json' },
        credentials: 'same-origin'
      });
      const data = await res.json();
      const models = (data && Array.isArray(data.models)) ? data.models : [];
      if (!models.length){
        modelSelect.classList.add('d-none');
        modelInput.classList.remove('d-none');
        return;
      }

      const opts = [`<option value="">Seleziona…</option>`].concat(
        models.map(m => {
          const id = m.id ?? '';
          const name = (m.name ?? '').toString();
          return `<option value="${name.replace(/"/g,'&quot;')}" data-id="${id}">${name}</option>`;
        })
      );
      // aggiungo "Altro modello"
      opts.push(`<option value="${ALT_MODEL_VALUE}">Altro modello…</option>`);
      modelSelect.innerHTML = opts.join('');
    } catch(e){
      showToast({type:'danger', title:'Modelli', message:'Impossibile caricare i modelli.'});
      modelSelect.classList.add('d-none');
      modelInput.classList.remove('d-none');
    }
  }

  // Brand change
  brandSel.addEventListener('change', ()=>{
    const val = brandSel.value;
    const isOther = (val === ALT_BRAND_VALUE);
    brandOtherWrap.classList.toggle('d-none', !isOther);
    if (!isOther) brandOther.value = '';
    if (!val) {
      resetModelUI();
      return;
    }
    if (!isOther) loadModelsForBrand(val);
    else resetModelUI();
  });

  // Modello select change
  modelSelect.addEventListener('change', ()=>{
    const val = modelSelect.value;
    const isOtherModel = (val === ALT_MODEL_VALUE);
    modelOtherWrap.classList.toggle('d-none', !isOtherModel);
    if (!isOtherModel) modelOther.value = '';
  });

  // ---- Stepper
  function setStep(n){
    current = n;
    panes.forEach(p => p.classList.toggle('d-none', +p.dataset.step !== n));
    steps.forEach(s => s.classList.toggle('active', +s.dataset.step === n));

    const formCard = document.querySelector('.form-card');
    const wizardTitle = document.getElementById('wizard_title');
    const wizardSubtitle = document.getElementById('wizard_subtitle');

    const isSummary = false; // niente step 4 qui
    if (formCard){
      formCard.classList.toggle('summary-mode', isSummary);
    }
    if (wizardTitle)    wizardTitle.style.display    = '';
    if (wizardSubtitle) wizardSubtitle.style.display = '';

    if (n === 3) renderSummary();

    // Scroll to top of wizard
    const wizardContainer = document.querySelector('.quote-wizard');
    if (wizardContainer) {
        wizardContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
  }

  function validateStep(n){
    if (n === 1){
      if (!selectedDevice){
        showToast({type:'danger', title:'Dispositivo', message:'Seleziona un dispositivo per continuare.'});
        return false;
      }
      if (!brandSel.value){
        showToast({type:'danger', title:'Marca', message:'Seleziona la marca.'});
        brandSel.classList.add('is-invalid'); return false;
      }
      brandSel.classList.remove('is-invalid');

      if (brandSel.value === ALT_BRAND_VALUE && !normalizeText(brandOther.value)){
        showToast({type:'danger', title:'Marca', message:'Specifica la marca.'});
        brandOther.classList.add('is-invalid'); return false;
      }
      brandOther.classList.remove('is-invalid');

      if (!modelInput.classList.contains('d-none')){
        // input testo
        // facoltativo, nessun controllo
        return true;
      } else {
        // select
        if (!modelSelect.value){
          showToast({type:'danger', title:'Modello', message:'Seleziona o specifica il modello.'});
          modelSelect.classList.add('is-invalid'); return false;
        }
        modelSelect.classList.remove('is-invalid');

        if (modelSelect.value === ALT_MODEL_VALUE && !normalizeText(modelOther.value)){
          showToast({type:'danger', title:'Modello', message:'Specifica il modello.'});
          modelOther.classList.add('is-invalid'); return false;
        }
        modelOther.classList.remove('is-invalid');
      }
      return true;
    }

    if (n === 2){
      if (!preferredDate.value){
        showToast({type:'danger', title:'Data', message:'Seleziona un giorno per l’appuntamento.'});
        preferredDate.classList.add('is-invalid'); return false;
      }
      preferredDate.classList.remove('is-invalid');

      if (!preferredSlot.value){
        showToast({type:'danger', title:'Fascia oraria', message:'Seleziona una fascia oraria.'});
        preferredSlot.classList.add('is-invalid'); return false;
      }
      preferredSlot.classList.remove('is-invalid');

      if (!dropoffType.value){
        showToast({type:'danger', title:'Consegna', message:'Seleziona la modalità di consegna.'});
        dropoffType.classList.add('is-invalid'); return false;
      }
      dropoffType.classList.remove('is-invalid');

      if (!normalizeText(problemSummary.value)){
        showToast({type:'danger', title:'Problema', message:'Descrivi brevemente il problema.'});
        problemSummary.classList.add('is-invalid'); return false;
      }
      problemSummary.classList.remove('is-invalid');

      return true;
    }

    if (n === 3){
      // Validazione finale (dati cliente)
      if (!normalizeText(firstName.value)){ showToast({type:'danger', title:'Nome', message:'Inserisci il nome.'}); return false; }
      if (!normalizeText(lastName.value)){ showToast({type:'danger', title:'Cognome', message:'Inserisci il cognome.'}); return false; }
      if (!email.value || !email.value.includes('@')){ showToast({type:'danger', title:'Email', message:'Email non valida.'}); return false; }
      if (!normalizeText(phone.value)){ showToast({type:'danger', title:'Telefono', message:'Inserisci il telefono.'}); return false; }

      if (!privacy.checked){
        showToast({type:'danger', title:'Privacy', message:'Accetta la privacy policy.'});
        return false;
      }
      return true;
    }
    return true;
  }

  function renderSummary(){
    const dev = selectedDevice ? (selectedDevice.charAt(0).toUpperCase() + selectedDevice.slice(1)) : '—';
    const brand = getBrandDisplay() || '—';
    const model = getModelDisplay() || '—';
    const date = preferredDate.value || '—';
    const slot = preferredSlot.value || '—';
    const drop = dropoffType.options[dropoffType.selectedIndex]?.text || '—';
    const prob = normalizeText(problemSummary.value) || '—';

    summaryList.innerHTML = `
      <li><span>Dispositivo:</span> <strong>${dev}</strong></li>
      <li><span>Marca/Modello:</span> <strong>${brand} ${model}</strong></li>
      <li><span>Data:</span> <strong>${date}</strong></li>
      <li><span>Fascia:</span> <strong>${slot}</strong></li>
      <li><span>Consegna:</span> <strong>${drop}</strong></li>
      <li><span>Problema:</span> <strong>${prob}</strong></li>
    `;
  }

  // ---- Buttons
  nextBtns.forEach(btn=>{
    btn.addEventListener('click', ()=>{
      if (validateStep(current)){
        setStep(current + 1);
      }
    });
  });

  prevBtns.forEach(btn=>{
    btn.addEventListener('click', ()=>{
      setStep(current - 1);
    });
  });

  goEmerg.forEach(btn=>{
    btn.addEventListener('click', ()=>{
      emSection.scrollIntoView({behavior:'smooth'});
    });
  });

  // ---- Submit
  async function submitForm(isWhatsapp){
    if (!validateStep(3)) return;

    // Spinner
    const btn = isWhatsapp ? waBtn : mailBtn;
    const originalHtml = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Attendere…`;

    const formData = new FormData(wizard);
    // Aggiungo campi extra
    formData.set('device_type', selectedDevice || '');
    formData.set('brand_name', getBrandDisplay());
    formData.set('model_name', getModelDisplay());
    formData.set('channel', 'web');
    formData.set('source', 'form_prenotazione');

    try{
      const res = await fetch(ENDPOINTS.process, {
        method: 'POST',
        body: formData,
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
      });
      const data = await res.json();

      if (data.ok){
        // Successo
        const bookingId = data.id || '???';
        const msg = `Prenotazione #${bookingId} inviata con successo!`;
        showToast({type:'success', title:'Prenotazione', message:msg});

        if (isWhatsapp){
          // Costruisci link WA
          const lines = [
            `📅 *Nuova Prenotazione Riparazione*`,
            `ID: #${bookingId}`,
            ``,
            `• Dispositivo: ${selectedDevice} ${getBrandDisplay()} ${getModelDisplay()}`,
            `• Data: ${preferredDate.value}`,
            `• Fascia: ${preferredSlot.value}`,
            `• Consegna: ${dropoffType.options[dropoffType.selectedIndex]?.text}`,
            ``,
            `• Problema: ${normalizeText(problemSummary.value)}`,
            ``,
            `👤 Cliente: ${firstName.value} ${lastName.value}`,
            `📞 Tel: ${phone.value}`
          ];
          const waText = lines.join('\n');
          setTimeout(()=>{
            window.open(waLinkFromText(waText), '_blank');
            window.location.reload();
          }, 1500);
        } else {
          // Email / Standard
          setTimeout(()=>{
            window.location.reload();
          }, 2000);
        }
      } else {
        // Errore server
        showToast({type:'danger', title:'Errore', message: data.message || 'Errore durante il salvataggio.'});
        btn.disabled = false;
        btn.innerHTML = originalHtml;
      }
    } catch(e){
      showToast({type:'danger', title:'Errore', message:'Errore di rete o server.'});
      btn.disabled = false;
      btn.innerHTML = originalHtml;
    }
  }

  waBtn.addEventListener('click', ()=> submitForm(true));
  mailBtn.addEventListener('click', ()=> submitForm(false));

})();
</script>
</body>
</html>
