<?php
/**
 * Key Soft Italia - Preventivo (Wizard 4 step)
 * Step 1: Dispositivo/Marca/Modello (device+brand obbligatori; modello facoltativo con "Altro modello")
 * Step 2: Problemi (multi-select) + Descrizione (obbligatoria solo se tra i problemi c'è "Altro")
 * Step 3: Dati + Privacy (niente stima qui) – click WhatsApp o Invia => salvataggio e passaggio a Step 4
 * Step 4: Riepilogo + Stima da server, messaggio di conferma, solo "Ricomincia"
 */

if (!defined('BASE_PATH')) {
  define('BASE_PATH', __DIR__ . '/');
}
require_once BASE_PATH . 'config/config.php';

$page_title       = "Richiedi Preventivo - Key Soft Italia | Stima Rapida e Invio";
$page_description = "Calcola una stima indicativa in pochi step e invia la richiesta di preventivo. Risposta entro 24 ore lavorative.";
$page_keywords    = "preventivo informatica, stima riparazione smartphone, stima pc, sviluppo software preventivo";

$breadcrumbs = [
  ['label' => 'Preventivo', 'url' => 'preventivo.php']
];
?>
<!DOCTYPE html>
<html lang="it">
<head>
  <?php include 'includes/head.php'; ?>
  <title><?php echo htmlspecialchars($page_title); ?></title>
  <?php echo generate_meta_tags([
      'title' => $page_title,
      'description' => $page_description,
      'keywords'    => $page_keywords,
      'url'         => url('preventivo.php')
  ]); ?>
  <link rel="stylesheet" href="<?php echo asset('css/pages/preventivo.css'); ?>">
</head>
<body data-aos-easing="ease-in-out" data-aos-duration="800" data-aos-once="true">

<?php include 'includes/header.php'; ?>

<!-- HERO -->
<section class="hero hero-secondary text-center">
  <div class="hero-pattern"></div>
  <div class="container position-relative z-2">
    <div class="hero-icon mb-3" data-aos="zoom-in"><i class="ri-calculator-line"></i></div>
    <h1 class="hero-title text-white" data-aos="fade-up">Calcola il tuo <span class="text-gradient">Preventivo</span></h1>
    <p class="hero-subtitle" data-aos="fade-up" data-aos-delay="100">Stima indicativa e invio in 4 step</p>
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
        <div class="ks-step active" data-step="1"><span class="ks-step-num">1</span><span class="ks-step-label">Dispositivo</span></div>
        <div class="ks-step" data-step="2"><span class="ks-step-num">2</span><span class="ks-step-label">Problemi</span></div>
        <div class="ks-step" data-step="3"><span class="ks-step-num">3</span><span class="ks-step-label">Dati</span></div>
        <div class="ks-step" data-step="4"><span class="ks-step-num">4</span><span class="ks-step-label">Riepilogo</span></div>
      </div>

      <h2 id="wizard_title" class="form-title">Richiedi Preventivo</h2>
      <p id="wizard_subtitle" class="form-subtitle">Completa i 4 step: <strong>scelta</strong> → <strong>problemi</strong> → <strong>dati</strong> → <strong>riepilogo</strong>.</p>

      <form id="quoteWizard" method="POST" action="<?php echo url('assets/process/process_quote.php'); ?>" novalidate>
        <?php echo generate_csrf_field(); ?>
        <input type="text" name="website" class="d-none" tabindex="-1" autocomplete="off"><!-- honeypot -->

        <!-- Hidden serialize (per server/WA) -->
        <input type="hidden" name="estimate_min" id="est_min">
        <input type="hidden" name="estimate_max" id="est_max">
        <input type="hidden" name="wizard_payload" id="wizard_payload">

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
                <label class="form-label">Marca *</label>
                <select class="form-select" id="brand" name="brand" required disabled>
                  <option value="">Seleziona il dispositivo prima…</option>
                </select>
              </div>

              <!-- Brand “Altro” -->
              <div class="col-md-4 d-none" id="brand-other-wrap">
                <label class="form-label">Specifica marca *</label>
                <input type="text" class="form-control" id="brand_other" name="brand_other" placeholder="Scrivi la marca">
              </div>

              <!-- Modello (select da DB, con Altro modello…) -->
              <div class="col-md-4" id="model-wrap">
                <label class="form-label" id="model-label">Modello <span class="text-muted">(opzionale)</span></label>
                <select class="form-select d-none" id="model_select" name="model_select"></select>
                <input type="text" class="form-control" id="model" name="model" placeholder="Es. iPhone 12, IdeaPad 3…" />
              </div>

              <!-- Modello “Altro modello…” -->
              <div class="col-md-4 d-none" id="model-other-wrap">
                <label class="form-label">Specifica modello</label>
                <input type="text" class="form-control" id="model_other" name="model_other" placeholder="Scrivi il modello">
              </div>
            </div>
            <small class="form-text text-muted d-block mt-1">Se non selezioni il modello, la stima sarà “<em>a partire da</em>” per quella marca.</small>
          </div>

          <div class="ks-actions">
            <div class="ks-left">
              <button type="button" class="btn btn-emergency ks-goto-emergency">
                <i class="ri-alert-line"></i> <span class="btn-text">Emergenza</span>
              </button>
            </div>
            <div class="ks-right">
              <button type="button" class="btn btn-next ks-next">
                <i class="ri-arrow-right-line"></i> <span class="btn-text">Avanti</span>
              </button>
            </div>
          </div>
        </fieldset>

        <!-- ================= STEP 2 ================= -->
        <fieldset class="ks-step-pane d-none" data-step="2">
          <div class="step-block">
            <h3 class="block-title"><i class="ri-tools-line"></i> Seleziona i problemi (uno o più)</h3>
            <div id="problem-grid" class="problem-grid" role="group" aria-label="Problemi selezionabili"><!-- via JS --></div>
            <small class="text-muted d-block mt-1">Nessun segno di spunta: si evidenziano le card scelte. La descrizione è obbligatoria solo se selezioni “Altro”.</small>
          </div>

          <div class="step-block">
            <h3 class="block-title"><i class="ri-edit-2-line"></i> Descrizione</h3>
            <textarea class="form-control" id="problem_description" name="problem_description" rows="5" placeholder="Descrivi il problema (opzionale, eccetto per 'Altro')"></textarea>
            <div class="form-text">Più dettagli = diagnosi più precisa.</div>
          </div>

          <div class="ks-actions">
            <div class="ks-left">
              <button type="button" class="btn btn-emergency ks-goto-emergency">
                <i class="ri-alert-line"></i> <span class="btn-text">Emergenza</span>
              </button>
            </div>
            <div class="ks-right">
              <button type="button" class="btn btn-prev ks-prev">
                <i class="ri-arrow-left-line"></i> <span class="btn-text">Indietro</span>
              </button>
              <button type="button" class="btn btn-next ks-next">
                <i class="ri-arrow-right-line"></i> <span class="btn-text">Avanti</span>
              </button>
            </div>
          </div>
        </fieldset>

        <!-- ================= STEP 3 (no stima qui) ================= -->
        <fieldset class="ks-step-pane d-none" data-step="3">
          <div class="step-block">
            <h3 class="block-title"><i class="ri-user-line"></i> I tuoi dati</h3>
            <div class="row g-3">
              <div class="col-md-6"><label class="form-label">Nome *</label><input type="text" class="form-control" name="firstName" required></div>
              <div class="col-md-6"><label class="form-label">Cognome *</label><input type="text" class="form-control" name="lastName" required></div>
              <div class="col-md-6"><label class="form-label">Email *</label><input type="email" class="form-control" name="email" required></div>
              <div class="col-md-6"><label class="form-label">Telefono *</label><input type="tel" class="form-control" name="phone" required inputmode="tel" placeholder="3xx xxx xxxx"></div>
              <div class="col-12"><label class="form-label">Azienda <span class="text-muted">(opzionale)</span></label><input type="text" class="form-control" name="company" placeholder="Nome dell'azienda (opzionale)"></div>
            </div>
            <div class="form-check mt-3">
              <input class="form-check-input" type="checkbox" id="privacy" name="privacy" required>
              <label class="form-check-label" for="privacy">Ho letto e accetto la <a href="<?php echo url('privacy.php'); ?>" target="_blank" rel="noopener">Privacy Policy</a> *</label>
            </div>
          </div>

          <div class="ks-actions">
            <div class="ks-left">
              <button type="button" class="btn btn-prev ks-prev">
                <i class="ri-arrow-left-line"></i> <span class="btn-text">Indietro</span>
              </button>
            </div>
            <div class="ks-right">
              <a class="btn btn-wa" id="btn_whatsapp" href="#" rel="noopener">
                <i class="ri-whatsapp-line"></i> <span class="btn-text">WhatsApp</span>
              </a>
              <button type="button" class="btn btn-mail" id="btn_submit">
                <i class="ri-mail-send-line"></i> <span class="btn-text">Invia</span>
              </button>
            </div>
          </div>
        </fieldset>

        <!-- ================= STEP 4 (RIEPILOGO) ================= -->
        <fieldset class="ks-step-pane d-none" data-step="4">
          <div class="step-block">
            <h3 class="block-title"><i class="ri-check-double-line"></i> Riepilogo richiesta</h3>

            <div class="summary-box">

              <div class="summary-estimate">
                <div class="summary-estimate-title">
                  <i class="ri-money-euro-circle-line"></i> Stima indicativa
                </div>

                <div class="summary-estimate-box">
                  <div class="summary-price">
                    <span class="from" id="sum_from_label" hidden>da</span>
                    <span id="sum_estimate_value">€ —</span>
                  </div>
                </div>

                <div class="summary-caption">
                  IVA inclusa, salvo diagnosi. Il prezzo finale viene confermato dal tecnico.
                </div>
              </div>
              <ul class="summary-list">
                <li><strong>Dispositivo:</strong> <span id="sum_device">—</span></li>
                <li><strong>Marca:</strong> <span id="sum_brand">—</span></li>
                <li><strong>Modello:</strong> <span id="sum_model">—</span></li>
                <li><strong>Problemi selezionati:</strong> <span id="sum_issues">—</span></li>
                <li><strong>Descrizione:</strong> <span id="sum_desc">—</span></li>
              </ul>
              <div class="summary-note mt-3">
                ✅ Richiesta registrata. Ti contatteremo al più presto per confermare tempi e prezzo.
              </div>
            </div>
          </div>

          <div class="ks-actions">
            <div class="ks-left">
              <button type="button" class="btn btn-light" id="btn_restart">
                <i class="ri-refresh-line"></i> <span class="btn-text">Ricomincia</span>
              </button>
            </div>
            <div class="ks-right"><!-- intentionally empty --></div>
          </div>
        </fieldset>
      </form>
    </div>
  </div>
</section>

<!-- EMERGENCY -->
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
              <h3 id="emergenza-title" class="mb-1">Emergenza? Salta la coda</h3>
              <p class="mb-0">Attiva la <strong>corsia prioritaria</strong> via WhatsApp o chiamata.</p>
            </div>
          </div>
          <ul class="ew-points centered">
            <li><i class="ri-flashlight-line"></i> Risposta prioritaria</li>
            <li><i class="ri-shield-check-line"></i> Tecnici certificati</li>
            <li><i class="ri-time-line"></i> Entro poche ore</li>
          </ul>
          <div class="ew-actions">
            <a href="tel:<?php echo PHONE_PRIMARY; ?>" class="btn btn-em-call btn-lg" rel="noopener">
              <i class="ri-phone-line me-1"></i> Chiama Subito
              <span class="phone-number">: <?php echo PHONE_PRIMARY; ?></span>
            </a>
            <a href="<?php echo whatsapp_link('🚨 EMERGENZA PREVENTIVO: richiesta prioritaria'); ?>" class="btn btn-em-wa btn-lg" id="wa-emergency" target="_blank" rel="noopener">
              <i class="ri-whatsapp-line me-1"></i> WhatsApp Urgente
            </a>
            <small class="ew-note">Fuori orario? Scrivi su WhatsApp, ti rispondiamo alla prima apertura.</small>
          </div>
        </section>
      </div>
    </div>
  </div>
</section>

<!-- =============================================== -->
<!-- ============ Sezione "Come Funziona" ============ -->
<!-- =============================================== -->
<section class="section section-process fancy-process">
  <div class="container">
    <!-- Intestazione della sezione -->
    <div class="section-header text-center">
      <h2 class="section-title">Come funziona</h2>
      <p class="section-subtitle">Dalla richiesta alla consegna, in 4 passi</p>
    </div>

    <!-- Lista dei passaggi (processo) -->
    <ol class="row g-4 process-steps align-items-stretch">
      <li class="col-md-6 col-lg-3">
        <div class="process-item" data-aos="fade-up">
          <div class="step-head">
            <span class="step-badge">1</span>
            <span class="step-icon"><i class="ri-edit-line" aria-hidden="true"></i></span>
          </div>
          <h3 class="step-title">Richiesta</h3>
          <p class="step-text">Compili il wizard o scrivi su WhatsApp.</p>
        </div>
      </li>

      <li class="col-md-6 col-lg-3">
        <div class="process-item" data-aos="fade-up" data-aos-delay="60">
          <div class="step-head">
            <span class="step-badge">2</span>
            <span class="step-icon"><i class="ri-search-eye-line" aria-hidden="true"></i></span>
          </div>
          <h3 class="step-title">Analisi</h3>
          <p class="step-text">Diagnosi rapida, verifichiamo tempi e fattibilità.</p>
        </div>
      </li>

      <li class="col-md-6 col-lg-3">
        <div class="process-item" data-aos="fade-up" data-aos-delay="120">
          <div class="step-head">
            <span class="step-badge">3</span>
            <span class="step-icon"><i class="ri-price-tag-3-line" aria-hidden="true"></i></span>
          </div>
          <h3 class="step-title">Preventivo</h3>
          <p class="step-text">Ricevi prezzo trasparente e tempistiche.</p>
        </div>
      </li>

      <li class="col-md-6 col-lg-3">
        <div class="process-item" data-aos="fade-up" data-aos-delay="180">
          <div class="step-head">
            <span class="step-badge">4</span>
            <span class="step-icon"><i class="ri-checkbox-circle-line" aria-hidden="true"></i></span>
          </div>
          <h3 class="step-title">Conferma</h3>
          <p class="step-text">Partiamo col lavoro e ti aggiorniamo.</p>
        </div>
      </li>
    </ol>

    <!-- Trust Badges (punti di fiducia) -->
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
      <h2 class="section-title">Domande Frequenti</h2>
    </div>
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <div class="accordion accordion-faq" id="faqAccordion">
          <div class="accordion-item">
            <h2 class="accordion-header"><button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">Il preventivo è gratuito?</button></h2>
            <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
              <div class="accordion-body">Sì, gratuito e senza impegno. Confermi solo se ti va bene.</div>
            </div>
          </div>
          <div class="accordion-item">
            <h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">Quando ricevo risposta?</button></h2>
            <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
              <div class="accordion-body">Entro 24 ore lavorative. In caso di emergenza puoi usare il canale prioritario.</div>
            </div>
          </div>
          <div class="accordion-item">
            <h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">IVA e manodopera sono incluse?</button></h2>
            <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
              <div class="accordion-body">Sì, il prezzo comunicato è finale salvo ricambi opzionali.</div>
            </div>
          </div>
          <div class="accordion-item">
            <h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">Posso modificare il preventivo?</button></h2>
            <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
              <div class="accordion-body">Certo: adeguiamo opzioni, tempi e specifiche fino a trovare la soluzione giusta.</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<?php
// JSON-LD
$ld_service = [
  '@context' => 'https://schema.org',
  '@type' => 'Service',
  'name' => 'Richiesta Preventivo IT',
  'provider' => ['@type' => 'LocalBusiness', 'name' => 'Key Soft Italia'],
  'areaServed' => 'IT',
  'priceRange' => '€25-€9500',
  'url' => url('preventivo.php'),
  'potentialAction' => ['@type' => 'QuoteAction', 'target' => url('preventivo.php')]
];
$ld_faq = [
  '@context' => 'https://schema.org',
  '@type' => 'FAQPage',
  'mainEntity' => [
    ['@type'=>'Question','name'=>'Il preventivo è gratuito?','acceptedAnswer'=>['@type'=>'Answer','text'=>'Sì, gratuito e senza impegno.']],
    ['@type'=>'Question','name'=>'Quando ricevo risposta?','acceptedAnswer'=>['@type'=>'Answer','text'=>'Entro 24 ore lavorative.']],
    ['@type'=>'Question','name'=>'IVA e manodopera sono incluse?','acceptedAnswer'=>['@type'=>'Answer','text'=>'Sì, prezzo finale salvo ricambi.']],
    ['@type'=>'Question','name'=>'Posso modificare il preventivo?','acceptedAnswer'=>['@type'=>'Answer','text'=>'Sì, lo adeguiamo alle tue esigenze.']],
  ]
];
?>
<script type="application/ld+json"><?php echo json_encode($ld_service, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?></script>
<script type="application/ld+json"><?php echo json_encode($ld_faq, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?></script>

<?php include 'includes/footer.php'; ?>

<!-- JS -->
<script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script defer src="<?php echo asset('js/main.js'); ?>"></script>
<script defer src="https://unpkg.com/aos@next/dist/aos.js"></script>
<script>document.addEventListener('DOMContentLoaded', function(){ if (window.AOS) AOS.init(); });</script>

<!-- Wizard IIFE -->
<script>
(function(){
  // ---- Endpoints
  const ENDPOINTS = {
    brands: "<?= url('assets/ajax/get_brands.php'); ?>",
    models: "<?= url('assets/ajax/get_models.php'); ?>",
    process: "<?= url('assets/process/process_quote.php') ?>",
    issues: "<?= url('assets/ajax/get_issues.php'); ?>"
  };
  const ALT_BRAND_VALUE = "__other_brand__";
  const ALT_MODEL_VALUE = "__other_model__";

  // ---- Elements
  const wizard   = document.getElementById('quoteWizard');
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

  const problemGrid   = document.getElementById('problem-grid');
  const problemDescr  = document.getElementById('problem_description');

  const estMin   = document.getElementById('est_min');
  const estMax   = document.getElementById('est_max');
  const payload  = document.getElementById('wizard_payload');

  const waBtn   = document.getElementById('btn_whatsapp');
  const mailBtn = document.getElementById('btn_submit');

  const formCard       = document.querySelector('.form-card');
  const wizardTitle    = document.getElementById('wizard_title');
  const wizardSubtitle = document.getElementById('wizard_subtitle');

  // Summary DOM
  const sumDevice   = document.getElementById('sum_device');
  const sumBrand    = document.getElementById('sum_brand');
  const sumModel    = document.getElementById('sum_model');
  const sumIssues   = document.getElementById('sum_issues');
  const sumDesc     = document.getElementById('sum_desc');
  const sumFromLbl  = document.getElementById('sum_from_label');
  const sumValue    = document.getElementById('sum_estimate_value');
  const restartBtn  = document.getElementById('btn_restart');

  const emSection = document.querySelector('.emergency-wide');
  const csrfEl = wizard.querySelector('input[name="csrf_token"], input[name="_csrf"], input[name="csrf"]');

  // ---- Data
  let current = 1;
  let selectedDevice = null;
  let selectedProblems = new Set();
  let lastSavedId = null; // quote id

  // Problemi fallback per device
  const FALLBACK_ISSUES = {
    smartphone: ['Non carica','Display rotto','Back cover','Non mi sentono (microfono)','Non sento (altoparlante)','Non si accende','Batteria','Fotocamera','Andato in acqua','Altro'],
    tablet:     ['Non carica','Display rotto','Batteria','Lento/ottimizzazione','Non si accende','Wi-Fi/Bluetooth','Altro'],
    computer:   ['Non si accende','Non si avvia (OS/boot)','Formattazione/Reinstallazione','Lento/ottimizzazione','Virus/Malware','Schermo rotto','Tastiera/Trackpad','Recupero dati','Rete/driver','Altro'],
    console:    ['Non legge dischi','Surriscaldamento/ventola','Errore aggiornamento','Porta HDMI danneggiata','Alimentazione','Controller non si connette','Memoria/archiviazione','Altro'],
    tv:         ['Schermo nero','Linee sul display','Nessun segnale HDMI','Audio assente','Wi-Fi/rete','Non si accende','Aggiornamento firmware','Altro'],
    altro:      ['Altro']
  };

  // ---- Utils
  
  function formatEstimate(min, max, showFrom=false){
    const m1 = (min != null) ? Math.round(min) : null;
    const m2 = (max != null) ? Math.round(max) : null;
    if (showFrom || !m2 || m2 <= m1) return `€ ${m1 ?? '—'}`;
    return `€ ${m1 ?? '—'}–${m2 ?? '—'}`;
  }
  function normalizeText(s){ return (s || '').toString().trim().replace(/\s+/g,' '); }
  function formatCurrency(n){ try { return new Intl.NumberFormat('it-IT',{style:'currency',currency:'EUR',maximumFractionDigits:0}).format(n); } catch { return `€ ${Math.round(+n||0)}`; } }
  function formatEstimateDisplay(est){
    const min = +est?.min || 0;
    const max = +est?.max || 0;
    if (!min && !max) return "€ —";
    if (!max || max < min) return `da ${formatCurrency(min)}`;
    if (min === max) return formatCurrency(min);
    return `${formatCurrency(min)} – ${formatCurrency(max)}`;
  }
  function waLinkFromText(text){
    const phone = "<?= preg_replace('/\D+/', '', (defined('PHONE_WHATSAPP') ? PHONE_WHATSAPP : PHONE_PRIMARY)); ?>";
    return `https://wa.me/${phone}?text=${encodeURIComponent(text)}`;
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
      loadIssues();
    });
  });

  // ---- Brands / Models (DB)
  async function loadBrandsForDevice(device){
    brandSel.disabled = true;
    brandSel.innerHTML = `<option value="">Carico marche…</option>`;
    brandOtherWrap.classList.add('d-none'); brandOther.value = '';
    resetModelUI();

    try{
      const url = `${ENDPOINTS.brands}?device=${encodeURIComponent(device)}`;
      const res = await fetch(url, {headers:{'Accept':'application/json'}});
      const data = await res.json();
      const list = Array.isArray(data?.brands) ? data.brands : [];
      const opts = [`<option value="">Seleziona marca…</option>`]
        .concat(list.map(b => `<option value="${b.id}" data-name="${b.name}">${b.name}</option>`))
        .concat([`<option value="${ALT_BRAND_VALUE}">Altro</option>`]);
      brandSel.innerHTML = opts.join('');
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

  async function loadModelsForBrand(brandId, brandName){
    resetModelUI();
    if (!brandId || brandId === ALT_BRAND_VALUE){
      return; // brand “Altro”: modello solo input libero
    }
    try{
      const url = `${ENDPOINTS.models}?brand_id=${encodeURIComponent(brandId)}`;
      const res = await fetch(url, {headers:{'Accept':'application/json'}});
      const data = await res.json();
      const list = Array.isArray(data?.models) ? data.models : [];
      if (!list.length) return;

      const options = [`<option value="">Seleziona modello…</option>`]
        .concat(list.map(m => `<option value="${m.name}" data-id="${m.id||''}">${m.name}</option>`))
        .concat([`<option value="${ALT_MODEL_VALUE}">Altro modello…</option>`]);
      modelSelect.innerHTML = options.join('');
      modelSelect.classList.remove('d-none');
      modelInput.classList.add('d-none');
    } catch(e){
      showToast({type:'danger', title:'Modelli', message:'Impossibile caricare i modelli.'});
    }
  }

  function buildProblemGridFromList(list){
    const labels = (Array.isArray(list) ? list : []).map(it => (typeof it === 'string' ? it : (it?.label ?? ''))).filter(Boolean);
    if (!labels.some(l => l.toLowerCase() === 'altro')) labels.push('Altro');

    selectedProblems.clear();
    problemGrid.innerHTML = labels.map(p => `
      <button type="button" class="problem-card" data-problem="${p.replace(/"/g,'&quot;')}" role="checkbox" aria-checked="false">
        <span class="problem-label">${p}</span>
      </button>
    `).join('');

    problemGrid.querySelectorAll('.problem-card').forEach(btn=>{
      btn.addEventListener('click', ()=>{
        const p = btn.dataset.problem;
        const isSel = selectedProblems.has(p);
        if (isSel) {
          selectedProblems.delete(p);
          btn.classList.remove('selected'); btn.setAttribute('aria-checked','false');
        } else {
          selectedProblems.add(p);
          btn.classList.add('selected'); btn.setAttribute('aria-checked','true');
          if (p === 'Altro') problemDescr.focus();
        }
      });
    });
  }

  async function loadIssues(){
    const qp = new URLSearchParams();
    qp.set('device', selectedDevice || '');
    if (brandSel.value && brandSel.value !== ALT_BRAND_VALUE) {
      qp.set('brand_id', brandSel.value);
    }
    if (!modelSelect.classList.contains('d-none')){
      const val = modelSelect.value;
      if (val && val !== ALT_MODEL_VALUE){
        const opt = modelSelect.options[modelSelect.selectedIndex];
        const mid = opt ? (opt.dataset.id || '') : '';
        if (mid) qp.set('model_id', mid);
        qp.set('model_name', val);
      }
    } else {
      const name = normalizeText(modelInput.value);
      if (name) qp.set('model_name', name);
    }

    try{
      problemGrid.innerHTML = `<div class="text-muted small">Carico problemi…</div>`;
      const res = await fetch(`${ENDPOINTS.issues}?${qp.toString()}`, {headers:{'Accept':'application/json'}});
      const data = await res.json();
      const fromDb = Array.isArray(data?.issues) ? data.issues : null;
      if (fromDb && fromDb.length){
        buildProblemGridFromList(fromDb);
      } else {
        buildProblemGridFromList(FALLBACK_ISSUES[selectedDevice] || FALLBACK_ISSUES['altro']);
      }
    } catch(e){
      buildProblemGridFromList(FALLBACK_ISSUES[selectedDevice] || FALLBACK_ISSUES['altro']);
      showToast({type:'danger', title:'Problemi', message:'Impossibile caricare i problemi dal server. Uso elenco di base.'});
    }
  }

  brandSel.addEventListener('change', () => {
    const val = brandSel.value;
    const opt = brandSel.options[brandSel.selectedIndex];
    const name = opt ? (opt.dataset.name || opt.textContent) : '';

    const isOther = (val === ALT_BRAND_VALUE);
    brandOtherWrap.classList.toggle('d-none', !isOther);
    if (!isOther) brandOther.value = '';

    loadModelsForBrand(val, name);
    loadIssues();
  });

  modelSelect.addEventListener('change', ()=>{
    const val = modelSelect.value;
    const isOtherModel = (val === ALT_MODEL_VALUE);
    modelOtherWrap.classList.toggle('d-none', !isOtherModel);
    if (!isOtherModel) modelOther.value = '';
    loadIssues();
  });

  modelInput.addEventListener('blur', ()=>{ if (normalizeText(modelInput.value)) loadIssues(); });

  // ---- Stepper
  function setStep(n){
    current = n;
    panes.forEach(p => p.classList.toggle('d-none', +p.dataset.step !== n));
    steps.forEach(s => s.classList.toggle('active', +s.dataset.step === n));

    const isSummary = (n === 4);
    if (formCard){
      formCard.classList.toggle('summary-mode', isSummary);
    }
    if (wizardTitle)    wizardTitle.style.display    = isSummary ? 'none' : '';
    if (wizardSubtitle) wizardSubtitle.style.display = isSummary ? 'none' : '';

    smoothScrollTo(document.querySelector('.quote-wizard'));
  }

  function validateStep(n){
    if (n===1){
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

      if (!modelInput.classList.contains('d-none') ){
        // modello via input: facoltativo
      } else {
        if (modelSelect.value === ALT_MODEL_VALUE && !normalizeText(modelOther.value)){
          showToast({type:'danger', title:'Modello', message:'Specifica il modello.'});
          modelOther.classList.add('is-invalid'); return false;
        }
        modelOther.classList.remove('is-invalid');
      }
      return true;
    }
    if (n===2){
      if (!selectedProblems.size){
        showToast({type:'danger', title:'Problemi', message:'Seleziona almeno un problema.'});
        return false;
      }
      if (selectedProblems.has('Altro') && !normalizeText(problemDescr.value)){
        showToast({type:'danger', title:'Descrizione', message:'Per "Altro" serve una breve descrizione.'});
        problemDescr.classList.add('is-invalid'); return false;
      }
      problemDescr.classList.remove('is-invalid');
      return true;
    }
    return true;
  }

  nextBtns.forEach(b=> b.addEventListener('click', ()=>{ if (!validateStep(current)) return; setStep(Math.min(4, current+1)); }));
  prevBtns.forEach(b=> b.addEventListener('click', ()=> setStep(Math.max(1, current-1))));
  goEmerg.forEach(b=> b.addEventListener('click', ()=>{ if (emSection) smoothScrollTo(emSection); }));

  // ---- Build payload for server/WA
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
  function getIssuesArray(){ return Array.from(selectedProblems); }

  function buildWaMessage(estimate){
    const lines = [
      '🧮 *Richiesta Preventivo*',
      `📱 *Dispositivo:* ${selectedDevice || 'n/d'} • *Marca:* ${getBrandDisplay() || 'n/d'} • *Modello:* ${getModelDisplay() || 'n/d'}`,
      `🛠️ *Problema:* ${getIssuesArray().join(', ') || 'n/d'}`,
      ...(normalizeText(problemDescr.value) ? [`📝 *Descrizione:* ${normalizeText(problemDescr.value)}`] : []),
      `💶 *Stima indicativa:* ${formatEstimateDisplay(estimate)}`,
      'Grazie!'
    ];
    return lines.join('\n');
  }

  // ---- Summary rendering
  function renderSummary(){
    sumDevice.textContent = (selectedDevice || '—');
    const brandTxt = getBrandDisplay();
    sumBrand.textContent  = brandTxt || '—';
    const modelTxt        = getModelDisplay();
    sumModel.textContent  = modelTxt || '—';

    sumIssues.textContent = (Array.from(selectedProblems).join(', ') || '—');
    sumDesc.textContent   = (normalizeText(problemDescr.value) || '—');

    const min = (estMin.value ? +estMin.value : null);
    const max = (estMax.value ? +estMax.value : null);

    const modelMissingOrOther = !modelTxt || modelTxt.toLowerCase() === 'altro' || modelTxt.toLowerCase() === 'other';
    const showFrom = modelMissingOrOther || !max || max <= (min ?? 0);

    sumFromLbl.hidden = !showFrom;
    sumValue.textContent = formatEstimate(min, max, showFrom);
  }

  // ---- Privacy modal gate
  function ensurePrivacyThen(onOk){
    const chkForm = wizard.querySelector('#privacy');
    if (chkForm && chkForm.checked) { onOk(); return; }
    const modalEl = document.getElementById('privacyModal');
    if (!modalEl || typeof bootstrap === 'undefined'){
      showToast({type:'danger', title:'Privacy', message:'Devi accettare la Privacy Policy per proseguire.'});
      return;
    }
    const bsModal = new bootstrap.Modal(modalEl, {backdrop:'static'});
    const chkModal = document.getElementById('privacyModalCheck');
    const acceptBtn = document.getElementById('privacyModalAccept');
    if (chkModal) chkModal.checked = false;
    function accept(){
      if (!chkModal || !chkModal.checked) return;
      if (chkForm) chkForm.checked = true;
      acceptBtn.removeEventListener('click', accept);
      bsModal.hide(); onOk();
    }
    acceptBtn.addEventListener('click', accept);
    bsModal.show();
  }

  // ---- SAVE to server (mode=save), then go to Step 4 and optionally open WhatsApp
  async function saveQuoteAndProceed(source){
    const req = [...wizard.querySelectorAll('[data-step="3"] [required]')];
    let ok = true;
    req.forEach(el=>{
      const valid = (el.type==='checkbox') ? el.checked : !!(el.value||'').trim();
      el.classList.toggle('is-invalid', !valid);
      if (!valid) ok=false;
    });
    if (!ok){
      showToast({type:'danger', title:'Campi mancanti', message:'Compila i dati richiesti.'});
      return;
    }

    const dataPayload = {
      device: selectedDevice,
      brand: getBrandDisplay(),
      model: getModelDisplay(),
      problems: getIssuesArray(),
      description: normalizeText(problemDescr.value)
    };
    payload.value = JSON.stringify(dataPayload);

    const fd = new FormData(wizard);
    fd.set('mode', 'save');
    fd.set('device', selectedDevice || '');
    if (brandSel.value && brandSel.value !== ALT_BRAND_VALUE) fd.set('brand_id', brandSel.value);
    fd.set('brand', getBrandDisplay());
    fd.set('model', getModelDisplay());
    getIssuesArray().forEach(p => fd.append('issues[]', p));
    fd.set('description', normalizeText(problemDescr.value));
    fd.set('source', source || 'form');

    const triggerBtn = (source === 'whatsapp') ? waBtn : mailBtn;
    const originalHTML = triggerBtn.innerHTML;
    triggerBtn.disabled = true;
    triggerBtn.innerHTML = `<i class="ri-loader-4-line ri-spin"></i> Invio…`;

    try{
      const res = await fetch(ENDPOINTS.process, {
        method: 'POST',
        headers: { 'Accept':'application/json','X-Requested-With':'XMLHttpRequest' },
        body: fd,
        credentials: 'same-origin'
      });
      const data = await res.json();
      if (!res.ok || !data.ok){
        showToast({type:'danger', title:'Invio', message: (data && data.message) || 'Errore durante il salvataggio.'});
        return;
      }

      lastSavedId = data.id || null;
      const estimate = data.estimate || {};
      estMin.value = Math.round(+estimate.min || 0);
      estMax.value = Math.round(+estimate.max || 0);

      // Mostra riepilogo
      renderSummary();
      setStep(4);

      if (source === 'whatsapp'){
        const href = waLinkFromText(buildWaMessage(estimate));
        // Nota: alcuni browser potrebbero bloccare pop-up se non direttamente da gesture.
        // In tal caso l'utente può cliccare di nuovo il bottone WA (ora inutile perché siamo allo step 4).
        window.open(href, '_blank', 'noopener');
        showToast({type:'info', title:'WhatsApp', message:'Apro la chat precompilata…', delay:2500});
      } else {
        showToast({type:'success', title:'Richiesta inviata', message:'Ti contatteremo entro 24 ore.'});
      }

    } catch(e){
      showToast({type:'danger', title:'Invio', message:'Errore di rete durante il salvataggio.'});
    } finally {
      triggerBtn.disabled = false;
      triggerBtn.innerHTML = originalHTML;
    }
  }

  // ---- Actions Step 3
  if (waBtn){
    waBtn.addEventListener('click', (e)=>{
      e.preventDefault();
      ensurePrivacyThen(()=> saveQuoteAndProceed('whatsapp'));
    });
  }
  if (mailBtn){
    mailBtn.addEventListener('click', (e)=>{
      e.preventDefault();
      ensurePrivacyThen(()=> saveQuoteAndProceed('email'));
    });
  }

  // ---- Ricomincia
  if (restartBtn){
    restartBtn.addEventListener('click', ()=>{
      wizard.reset();
      selectedDevice = null;
      selectedProblems.clear();
      problemGrid.innerHTML = '';
      brandSel.innerHTML = `<option value="">Seleziona il dispositivo prima…</option>`;
      brandOtherWrap.classList.add('d-none');
      if (brandOther) brandOther.value = '';
      resetModelUI();
      estMin.value = ''; estMax.value = '';
      if (waBtn) waBtn.removeAttribute('href');
      document.querySelectorAll('.device-card').forEach(b=>{ b.classList.remove('selected'); b.setAttribute('aria-pressed','false'); });
      setStep(1);
    });
  }

  // ---- Counters (sezione numeri)
  (function(){
    const section = document.querySelector('.section-proof'); if(!section) return;
    let started = false;
    function run(){
      if (started) return; started = true;
      section.querySelectorAll('.num').forEach(el=>{
        const raw = el.dataset.target;
        const target = parseFloat(raw);
        const isFloat = (raw+'').includes('.');
        const duration = 900;
        const start = performance.now();
        function tick(now){
          const p = Math.min(1, (now-start)/duration);
          const val = isFloat ? (target*p).toFixed(1) : Math.floor(target*p);
          el.textContent = val;
          if (p<1) requestAnimationFrame(tick); else el.textContent = raw;
        }
        requestAnimationFrame(tick);
      });
    }
    if ('IntersectionObserver' in window){
      const io = new IntersectionObserver(es=>{
        if (es.some(e=>e.isIntersecting)){ setTimeout(run, 150); io.disconnect(); }
      }, {threshold:.35});
      io.observe(section);
    } else {
      window.addEventListener('load', ()=> setTimeout(run, 300));
    }
  })();

  // Avvio
  setStep(1);
})();
</script>
</body>
</html>
