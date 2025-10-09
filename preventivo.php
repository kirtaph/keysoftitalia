<?php
/**
 * Key Soft Italia - Preventivo (Wizard 3 step)
 * Step 1: Dispositivo/Marca/Modello
 * Step 2: Problemi (multi-select) + Descrizione
 * Step 3: Stima + Dati + Invio (AJAX) – con modale Privacy
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
      'keywords' => $page_keywords,
      'url' => url('preventivo.php')
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
    <p class="hero-subtitle" data-aos="fade-up" data-aos-delay="100">Stima indicativa e invio in 3 step</p>
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
        <div class="ks-step" data-step="2"><span class="ks-step-num">2</span><span class="ks-step-label">Problema</span></div>
        <div class="ks-step" data-step="3"><span class="ks-step-num">3</span><span class="ks-step-label">Stima & Dati</span></div>
      </div>

      <h2 id="titolo-wizard" class="form-title">Richiedi Preventivo</h2>
      <p class="form-subtitle">Completa i 3 step: <strong>stima</strong> + <strong>invio</strong> in pochi secondi.</p>

      <form id="quoteWizard" method="POST" action="<?php echo url('process_quote.php'); ?>" novalidate>
        <?php echo generate_csrf_field(); ?>
        <input type="text" name="website" class="d-none" tabindex="-1" autocomplete="off"><!-- honeypot -->

        <!-- Hidden serialize (per server) -->
        <input type="hidden" name="estimate_min" id="est_min">
        <input type="hidden" name="estimate_max" id="est_max">
        <input type="hidden" name="wizard_payload" id="wizard_payload">

        <!-- STEP 1 -->
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
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label">Marca *</label>
                <select class="form-select" id="brand" name="brand" required>
                  <option value="">Seleziona il dispositivo prima…</option>
                </select>
              </div>
              <div class="col-md-6" id="brand-other-wrap" class="d-none">
                <label class="form-label">Altra marca</label>
                <input type="text" class="form-control" id="brand_other" name="brand_other" placeholder="Scrivi la marca">
              </div>
              <div class="col-12">
                <label class="form-label">Modello</label>
                <input type="text" class="form-control" id="model" name="model" placeholder="Es. iPhone 12, Lenovo IdeaPad 3…">
              </div>
            </div>
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

        <!-- STEP 2 -->
        <fieldset class="ks-step-pane d-none" data-step="2">
          <div class="step-block">
            <h3 class="block-title"><i class="ri-tools-line"></i> Seleziona i problemi (uno o più)</h3>
            <div id="problem-grid" class="problem-grid" role="group" aria-label="Problemi selezionabili"><!-- via JS --></div>
            <small class="text-muted d-block mt-1">Puoi selezionarne più di uno. Nessun segno di spunta: si evidenziano le card scelte.</small>
          </div>

          <div class="step-block">
            <h3 class="block-title"><i class="ri-edit-2-line"></i> Descrizione</h3>
            <textarea class="form-control" id="problem_description" name="problem_description" rows="5" required placeholder="Descrivi il problema: quando si presenta? messaggi di errore? cosa hai già provato…"></textarea>
            <div class="form-text">Più dettagli = stima e diagnosi più precise.</div>
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

        <!-- STEP 3 -->
        <fieldset class="ks-step-pane d-none" data-step="3">
          <div class="step-block">
            <h3 class="block-title"><i class="ri-money-euro-circle-line"></i> Stima Indicativa</h3>
            <div class="estimate-box" aria-live="polite">
              <div class="estimate-badge"><span id="est_badge">€ —</span></div>
              <div class="estimate-note">IVA inclusa, salvo diagnosi. Il prezzo finale viene confermato dal tecnico.</div>
            </div>
          </div>

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
              <a class="btn btn-wa" id="btn_whatsapp" target="_blank" rel="noopener">
                <i class="ri-whatsapp-line"></i> <span class="btn-text">WhatsApp</span>
              </a>
              <button type="submit" class="btn btn-mail" id="btn_submit">
                <i class="ri-mail-send-line"></i> <span class="btn-text">Invia</span>
              </button>
            </div>
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

<?php
// Proof metrics
$quote_metrics = [
  ['icon'=>'ri-file-list-2-line','label'=>'Preventivi/anno','value'=>1200,'suffix'=>'+'],
  ['icon'=>'ri-timer-flash-line','label'=>'Tempo medio risposta','value'=>6,'suffix'=>'h'],
  ['icon'=>'ri-hand-coin-line','label'=>'Tasso accettazione','value'=>78,'suffix'=>'%'],
  ['icon'=>'ri-star-smile-line','label'=>'Valutazione media','value'=>4.9,'suffix'=>'/5'],
];
?>
<section class="section section-proof bg-gradient-orange">
  <div class="container position-relative">
    <div class="section-header text-center">
      <h2 class="section-title text-white">I nostri numeri</h2>
      <p class="section-subtitle text-white-80">Indicativi su base annuale</p>
    </div>
    <div class="row g-4 justify-content-center equalize">
      <?php foreach($quote_metrics as $m): ?>
        <div class="col-6 col-md-4 col-lg-2">
          <div class="proof-card">
            <div class="proof-icon" aria-hidden="true"><i class="<?php echo $m['icon']; ?>"></i></div>
            <div class="proof-value">
              <span class="num" data-target="<?php echo htmlspecialchars($m['value']); ?>"><?php echo htmlspecialchars($m['value']); ?></span><span class="suffix"><?php echo htmlspecialchars($m['suffix']); ?></span>
            </div>
            <div class="proof-label"><?php echo htmlspecialchars($m['label']); ?></div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- PROCESS -->
<section class="section section-process">
  <div class="container">
    <div class="section-header text-center">
      <h2 class="section-title">Come funziona</h2>
      <p class="section-subtitle">Dalla richiesta alla consegna, in 4 passi</p>
    </div>
    <div class="row g-4 process-grid">
      <div class="col-md-6 col-lg-3"><div class="process-card" data-aos="fade-up"><div class="process-icon"><i class="ri-edit-line"></i></div><h3>1) Richiesta</h3><p>Compili il wizard o scrivi su WhatsApp.</p></div></div>
      <div class="col-md-6 col-lg-3"><div class="process-card" data-aos="fade-up" data-aos-delay="60"><div class="process-icon"><i class="ri-search-eye-line"></i></div><h3>2) Analisi</h3><p>Diagnosi rapida, verifichiamo tempi e fattibilità.</p></div></div>
      <div class="col-md-6 col-lg-3"><div class="process-card" data-aos="fade-up" data-aos-delay="120"><div class="process-icon"><i class="ri-price-tag-3-line"></i></div><h3>3) Preventivo</h3><p>Ricevi prezzo trasparente e tempistiche.</p></div></div>
      <div class="col-md-6 col-lg-3"><div class="process-card" data-aos="fade-up" data-aos-delay="180"><div class="process-icon"><i class="ri-checkbox-circle-line"></i></div><h3>4) Conferma</h3><p>Partiamo col lavoro e ti aggiorniamo.</p></div></div>
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
  // ---- Elements
  const wizard   = document.getElementById('quoteWizard');
  const panes    = [...wizard.querySelectorAll('.ks-step-pane')];
  const steps    = [...document.querySelectorAll('.ks-stepper .ks-step')];
  const nextBtns = wizard.querySelectorAll('.ks-next');
  const prevBtns = wizard.querySelectorAll('.ks-prev');
  const goEmerg  = wizard.querySelectorAll('.ks-goto-emergency');

  const brandSel   = document.getElementById('brand');
  const brandOther = document.getElementById('brand_other');
  const brandOtherWrap = document.getElementById('brand-other-wrap');
  const modelInput = document.getElementById('model');

  const problemGrid   = document.getElementById('problem-grid');
  const problemDescr  = document.getElementById('problem_description');

  const estMin   = document.getElementById('est_min');
  const estMax   = document.getElementById('est_max');
  const estBadge = document.getElementById('est_badge');
  const payload  = document.getElementById('wizard_payload');

  const waBtn  = document.getElementById('btn_whatsapp');
  const submit = document.getElementById('btn_submit');

  const emSection = document.querySelector('.emergency-wide');

  // ---- Data
  let current = 1;
  let selectedDevice = null;
  let selectedProblems = new Set();

  const BRANDS = {
    smartphone: ['Apple','Samsung','Xiaomi','Huawei','Oppo','OnePlus','Google','Altro'],
    tablet:     ['Apple','Samsung','Lenovo','Huawei','Xiaomi','Amazon (Fire)','Altro'],
    computer:   ['Apple','HP','Dell','Lenovo','Acer','ASUS','MSI','Altro'],
    console:    ['Sony PlayStation','Microsoft Xbox','Nintendo Switch','Altro'],
    tv:         ['Samsung','LG','Sony','Philips','Hisense','TCL','Altro'],
    altro:      ['Altro']
  };

  const PROBLEMS = {
    smartphone: ['Non carica','Display rotto','Back cover','Non mi sentono (microfono)','Non sento (altoparlante)','Non si accende','Batteria scarica/si spegne','Fotocamera guasta','Wi-Fi/Bluetooth','Altro'],
    tablet:     ['Non carica','Display rotto','Batteria','Lento/ottimizzazione','Non si accende','Wi-Fi/Bluetooth','Altro'],
    computer:   ['Non si accende','Non si avvia (OS/boot)','Formattazione/Reinstallazione','Lento/ottimizzazione','Virus/Malware','Schermo rotto','Tastiera/Trackpad','Recupero dati','Rete/driver','Altro'],
    console:    ['Non legge dischi','Surriscaldamento/ventola','Errore aggiornamento','Porta HDMI danneggiata','Alimentazione','Controller non si connette','Memoria/archiviazione','Altro'],
    tv:         ['Schermo nero','Linee sul display','Nessun segnale HDMI','Audio assente','Wi-Fi/rete','Non si accende','Aggiornamento firmware','Altro'],
    altro:      ['Altro']
  };

  const BASE_RANGE = {
    smartphone: [39,129],
    tablet:     [49,149],
    computer:   [35,180],
    console:    [45,160],
    tv:         [60,220],
    altro:      [40,160]
  };
  const WEIGHTS = { low: 0.1, mid: 0.25, high: 0.45 };
  const PROBLEM_WEIGHT = {
    smartphone: {
      'Display rotto':'high','Back cover':'mid','Non carica':'mid','Batteria scarica/si spegne':'mid','Fotocamera guasta':'mid',
      'Non mi sentono (microfono)':'low','Non sento (altoparlante)':'low','Wi-Fi/Bluetooth':'low','Non si accende':'high','Altro':'mid'
    },
    tablet:     { 'Display rotto':'high','Batteria':'mid','Non carica':'mid','Lento/ottimizzazione':'low','Non si accende':'high','Wi-Fi/Bluetooth':'low','Altro':'mid' },
    computer:   { 'Formattazione/Reinstallazione':'low','Lento/ottimizzazione':'low','Virus/Malware':'mid','Non si avvia (OS/boot)':'mid','Rete/driver':'low','Schermo rotto':'high','Tastiera/Trackpad':'mid','Recupero dati':'high','Non si accende':'high','Altro':'mid' },
    console:    { 'Porta HDMI danneggiata':'high','Surriscaldamento/ventola':'mid','Errore aggiornamento':'low','Non legge dischi':'mid','Alimentazione':'high','Controller non si connette':'low','Memoria/archiviazione':'low','Altro':'mid' },
    tv:         { 'Schermo nero':'high','Linee sul display':'high','Nessun segnale HDMI':'low','Audio assente':'low','Wi-Fi/rete':'low','Non si accende':'high','Aggiornamento firmware':'low','Altro':'mid' },
    altro:      { 'Altro':'mid' }
  };

  // ---- Utils
  function showToast({title='', message='', type='info', delay=4500} = {}){
    let cont = document.getElementById('ks-toast-container');
    if (!cont) {
      cont = document.createElement('div');
      cont.id = 'ks-toast-container';
      cont.className = 'toast-container position-fixed p-3';
      cont.style.right = '1rem'; cont.style.bottom = '1rem'; cont.style.zIndex = '1080';
      cont.setAttribute('role','region'); cont.setAttribute('aria-live','polite');
      document.body.appendChild(cont);
    }
    const color = type === 'success' ? 'border-success' : (type === 'danger' ? 'border-danger' : 'border-info');
    const el = document.createElement('div');
    el.className = `toast align-items-center shadow ${color}`;
    el.innerHTML = `
      <div class="toast-header">
        <strong class="me-auto">${type==='success'?'✅':(type==='danger'?'❌':'ℹ️')} ${title}</strong>
        <button type="button" class="btn-close ms-2 mb-1" data-bs-dismiss="toast" aria-label="Chiudi"></button>
      </div>
      <div class="toast-body">${message}</div>`;
    cont.appendChild(el);
    const t = new bootstrap.Toast(el, {delay, autohide:true}); t.show();
    el.addEventListener('hidden.bs.toast', ()=> el.remove());
  }
  function smoothScrollTo(el){
    if (!el) return;
    const hdr = document.querySelector('.site-header, header.sticky-top, header.navbar, .main-header');
    const off = hdr ? (hdr.getBoundingClientRect().height + 16) : 96;
    const y = el.getBoundingClientRect().top + window.pageYOffset - off;
    window.scrollTo({top:y, behavior:'smooth'});
  }
  function eur(v){ return Math.round(v/5)*5; }
  function clamp(min,max){ min = Math.max(0,min); return [eur(min), eur(Math.max(min+5, max))]; }
  function normalizeText(s){ return (s || '').toString().trim().replace(/\s+/g,' '); }
  function waLinkFromText(text){
    const phone = "<?= preg_replace('/\D+/', '', (defined('PHONE_WHATSAPP') ? PHONE_WHATSAPP : PHONE_PRIMARY)); ?>";
    return `https://wa.me/${phone}?text=${encodeURIComponent(text)}`;
  }

  // Privacy gate (usa la modale già presente nel footer)
  function ensurePrivacyThen(formEl, onOk){
    const chkForm = formEl.querySelector('#privacy');
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
      fillBrand(selectedDevice);
      buildProblems(selectedDevice);
    });
  });

  function fillBrand(device){
    const list = (BRANDS[device] || BRANDS['altro']);
    brandSel.innerHTML = `<option value="">Seleziona marca…</option>` + list.map(b=>`<option value="${b}">${b}</option>`).join('');
    brandOtherWrap.classList.add('d-none');
    brandOther.value = '';
  }
  brandSel.addEventListener('change', () => {
    const val = brandSel.value;
    brandOtherWrap.classList.toggle('d-none', val !== 'Altro');
    if (val !== 'Altro') brandOther.value = '';
  });

  // ---- Problems grid (multi) — senza icona di check, solo evidenza card
  function buildProblems(device){
    const items = PROBLEMS[device] || PROBLEMS['altro'];
    selectedProblems.clear();
    problemGrid.innerHTML = items.map(p => `
      <button type="button" class="problem-card" data-problem="${p.replace(/"/g,'&quot;')}" role="checkbox" aria-checked="false">
        <span class="problem-label">${p}</span>
      </button>
    `).join('');
    problemGrid.querySelectorAll('.problem-card').forEach(btn=>{
      btn.addEventListener('click', ()=>{
        const p = btn.dataset.problem;
        const isSel = selectedProblems.has(p);
        if (isSel) { selectedProblems.delete(p); btn.classList.remove('selected'); btn.setAttribute('aria-checked','false'); }
        else { selectedProblems.add(p); btn.classList.add('selected'); btn.setAttribute('aria-checked','true'); }
        if (p === 'Altro' && !isSel) problemDescr.focus();
      });
    });
  }

  // ---- Stepper
  function setStep(n){
    current = n;
    panes.forEach(p => p.classList.toggle('d-none', +p.dataset.step !== n));
    steps.forEach(s => s.classList.toggle('active', +s.dataset.step === n));
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
      if (brandSel.value==='Altro' && !normalizeText(brandOther.value)){
        showToast({type:'danger', title:'Altra marca', message:'Specifica la marca.'});
        brandOther.classList.add('is-invalid'); return false;
      }
      brandOther.classList.remove('is-invalid');
      return true;
    }
    if (n===2){
      if (!selectedProblems.size){
        showToast({type:'danger', title:'Problema', message:'Seleziona almeno un problema.'});
        return false;
      }
      if (!normalizeText(problemDescr.value)){
        showToast({type:'danger', title:'Descrizione', message:'Aggiungi una breve descrizione.'});
        problemDescr.classList.add('is-invalid'); return false;
      }
      problemDescr.classList.remove('is-invalid');
      const est = computeEstimate();
      estBadge.textContent = `€ ${est.min}–${est.max}`;
      estMin.value = est.min; estMax.value = est.max;
      return true;
    }
    return true;
  }
  nextBtns.forEach(b=> b.addEventListener('click', ()=>{ if (!validateStep(current)) return; setStep(Math.min(3, current+1)); }));
  prevBtns.forEach(b=> b.addEventListener('click', ()=> setStep(Math.max(1, current-1))));
  goEmerg.forEach(b=> b.addEventListener('click', ()=>{ if (emSection) smoothScrollTo(emSection); }));

  // ---- Estimator
  function computeEstimate(){
    const dev = selectedDevice || 'altro';
    let [minB, maxB] = BASE_RANGE[dev] || BASE_RANGE['altro'];
    let weightAcc = 0, count = 0;
    selectedProblems.forEach(p=>{
      const level = (PROBLEM_WEIGHT[dev] || {})[p] || 'mid';
      weightAcc += WEIGHTS[level] || WEIGHTS.mid;
      count++;
    });
    const avg = count ? (weightAcc / count) : WEIGHTS.mid;
    const span = maxB - minB;
    const shift = span * avg;
    let min = minB + (span * 0.10);
    let max = minB + shift + (span * 0.50);
    const mm = clamp(min, max);
    return {min: mm[0], max: mm[1]};
  }

  // ---- WA message (gated by Privacy)
  function buildWaMessage(){
    const brand = brandSel.value === 'Altro' ? normalizeText(brandOther.value) : brandSel.value;
    const model = normalizeText(modelInput.value);
    const list  = Array.from(selectedProblems).join(', ');
    const desc  = normalizeText(problemDescr.value);
    const minV  = estMin.value || '—';
    const maxV  = estMax.value || '—';
    const lines = [
      '🧮 *Richiesta Preventivo*',
      `📱 *Dispositivo:* ${selectedDevice || 'n/d'} • *Marca:* ${brand || 'n/d'} • *Modello:* ${model || 'n/d'}`,
      `🛠️ *Problema:* ${list || 'n/d'}`,
      `📝 *Descrizione:* ${desc || 'n/d'}`,
      `💶 *Stima indicativa:* € ${minV}–${maxV}`,
      'Grazie!'
    ];
    return lines.join('\n');
  }
  if (waBtn){
    waBtn.addEventListener('click', (e)=>{
      e.preventDefault();
      ensurePrivacyThen(wizard, ()=>{
        if (!estMin.value || !estMax.value){ const e = computeEstimate(); estMin.value=e.min; estMax.value=e.max; }
        const href = waLinkFromText(buildWaMessage());
        waBtn.setAttribute('href', href);
        showToast({type:'info', title:'WhatsApp', message:'Apro la chat precompilata…', delay:2500});
        window.open(href, '_blank', 'noopener');
      });
    });
  }

  // ---- Submit AJAX (gated by Privacy modal)
  wizard.addEventListener('submit', (ev)=>{
    ev.preventDefault();
    ensurePrivacyThen(wizard, async ()=>{
      // validazione step 3
      const req = [...wizard.querySelectorAll('[data-step="3"] [required]')];
      let ok = true;
      req.forEach(el=>{
        const valid = (el.type==='checkbox') ? el.checked : !!(el.value||'').trim();
        el.classList.toggle('is-invalid', !valid);
        if (!valid) ok=false;
      });
      if (!ok){ showToast({type:'danger', title:'Campi mancanti', message:'Compila i dati richiesti.'}); return; }

      const est = (estMin.value && estMax.value) ? {min:+estMin.value, max:+estMax.value} : computeEstimate();
      estMin.value = est.min; estMax.value = est.max;

      const dataPayload = {
        device: selectedDevice,
        brand: brandSel.value === 'Altro' ? normalizeText(brandOther.value) : brandSel.value,
        model: normalizeText(modelInput.value),
        problems: Array.from(selectedProblems),
        description: normalizeText(problemDescr.value),
        estimate: est
      };
      payload.value = JSON.stringify(dataPayload);

      const original = submit.innerHTML;
      submit.disabled = true; submit.innerHTML = `<i class="ri-loader-4-line ri-spin"></i> Invio…`;

      try{
        const fd  = new FormData(wizard);
        const res = await fetch(wizard.action, { method: 'POST', headers: { 'Accept':'application/json','X-Requested-With':'XMLHttpRequest' }, body: fd, credentials:'same-origin' });
        const isJson = (res.headers.get('Content-Type')||'').includes('application/json');
        const data = isJson ? await res.json() : {ok:false, message:'Risposta non valida dal server.'};

        if (res.ok && data.ok){
          showToast({type:'success', title:'Richiesta inviata', message: data.message || 'Ti contatteremo entro 24 ore.'});
          wizard.reset();
          deviceCards.forEach(b=>{ b.classList.remove('selected'); b.setAttribute('aria-pressed','false'); });
          selectedDevice = null; selectedProblems.clear(); problemGrid.innerHTML='';
          brandSel.innerHTML = `<option value="">Seleziona il dispositivo prima…</option>`;
          brandOtherWrap.classList.add('d-none'); brandOther.value = ''; modelInput.value='';
          estBadge.textContent = '€ —'; estMin.value=''; estMax.value='';
          setStep(1);
        } else {
          showToast({type:'danger', title:'Invio non riuscito', message: (data && data.message) || 'Riprova o usa WhatsApp.'});
        }
      } catch {
        showToast({type:'danger', title:'Errore di rete', message:'Controlla la connessione e riprova.'});
      } finally {
        submit.disabled = false; submit.innerHTML = original;
      }
    });
  });

  // Counters
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
