<?php
/**
 * Key Soft Italia - Assistenza Tecnica
 * Richiesta assistenza a domicilio o remota
 */

if (!defined('BASE_PATH')) {
  define('BASE_PATH', __DIR__ . '/');
}
require_once BASE_PATH . 'config/config.php';

$page_title       = "Assistenza Tecnica - Key Soft Italia | Supporto a Domicilio e Remoto";
$page_description = "Assistenza informatica professionale a domicilio e da remoto. Risolviamo i tuoi problemi tecnologici rapidamente e con trasparenza.";
$page_keywords    = "assistenza tecnica, supporto informatico, assistenza remota, tecnico a domicilio, riparazione computer";

$breadcrumbs = [
  ['label' => 'Assistenza', 'url' => 'assistenza.php']
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
      'url' => url('assistenza.php')
  ]); ?>

  <link rel="stylesheet" href="<?php echo asset('css/pages/assistenza.css'); ?>">
</head>
<body data-aos-easing="ease-in-out" data-aos-duration="800" data-aos-once="true">
<?php include 'includes/header.php'; ?>

<!-- HERO SECONDARY (riuso componente) -->
<section class="hero hero-secondary text-center">
  <div class="hero-pattern"></div>
  <div class="container position-relative z-2" data-aos="fade-up">
    <div class="hero-icon mb-3" data-aos="zoom-in">
      <i class="ri-customer-service-2-line"></i>
    </div>
    <h1 class="hero-title text-white" data-aos="fade-up" data-aos-delay="100">
      Richiedi <span class="text-gradient">Assistenza</span>
    </h1>
    <p class="hero-subtitle" data-aos="fade-up" data-aos-delay="200">
      Assistenza professionale a domicilio e da remoto. Risolviamo in fretta, con trasparenza.
    </p>
    <div class="hero-cta" data-aos="fade-up" data-aos-delay="300">
      <a href="#tipo-assistenza" class="btn btn-primary btn-lg smooth-scroll" aria-label="Scegli il tipo di assistenza">
        <i class="ri-arrow-down-line me-1"></i> Scegli il tipo di assistenza
      </a>
    </div>
    <div class="hero-breadcrumb mt-4" data-aos="fade-up" data-aos-delay="400">
      <?= generate_breadcrumbs($breadcrumbs); ?>
    </div>
  </div>
</section>

<!-- TYPE SELECTOR -->
<section id="tipo-assistenza" class="assistance-select">
  <div class="container">
    <h2 class="section-title text-center mb-2" data-aos="fade-up">Scegli il Tipo di Assistenza</h2>
    <p class="section-subtitle text-center mb-4" data-aos="fade-up" data-aos-delay="100">
      Seleziona la modalità più adatta alle tue esigenze
    </p>

    <div class="row g-4 justify-content-center">
      <div class="col-md-6" data-aos="fade-up" data-aos-delay="150">
        <button type="button" class="assistance-type-card" id="domicilio-card" data-type="domicilio" aria-pressed="false">
          <div class="card-icon"><i class="ri-home-smile-line"></i></div>
          <h3 class="card-title">Assistenza a Domicilio</h3>
          <ul class="card-list">
            <li><i class="ri-check-line"></i> Tecnico specializzato a casa tua</li>
            <li><i class="ri-check-line"></i> Risoluzione problemi software</li>
            <li><i class="ri-check-line"></i> Installazioni e configurazioni</li>
            <li><i class="ri-check-line"></i> Consulenza e ottimizzazione rete</li>
          </ul>
          <div class="card-foot"><span class="badge badge-soft">A partire da <strong>€35</strong> + trasferta</span></div>
        </button>
      </div>

      <div class="col-md-6" data-aos="fade-up" data-aos-delay="200">
        <button type="button" class="assistance-type-card" id="remota-card" data-type="remota" aria-pressed="false">
          <div class="card-icon indigo"><i class="ri-global-line"></i></div>
          <h3 class="card-title">Assistenza Remota</h3>
          <ul class="card-list">
            <li><i class="ri-check-line"></i> Intervento immediato</li>
            <li><i class="ri-check-line"></i> Nessuna trasferta</li>
            <li><i class="ri-check-line"></i> Software sicuri (TeamViewer/AnyDesk)</li>
            <li><i class="ri-check-line"></i> Pagamento rapido</li>
          </ul>
          <div class="card-foot"><span class="badge badge-soft">A partire da <strong>€25</strong></span></div>
        </button>
      </div>
    </div>

<!-- FORM MULTISTEP (inizialmente nascosto, centrato) -->
<section class="form-section d-none" aria-labelledby="titolo-form" >

    <div class="row justify-content-center" id="titolo-form">
      <div class="col-lg-12">
        <div class="form-container" data-aos="fade-up">
          <!-- Stepper -->
<div class="ks-stepper" aria-hidden="true" > 
  <div class="ks-step active" data-step="1">
    <span class="ks-step-num">1</span> <span class="ks-step-label">Contatti</span>
  </div>
  <div class="ks-step" data-step="2">
    <span class="ks-step-num">2</span> <span class="ks-step-label">Dettagli</span>
  </div>
  <div class="ks-step" data-step="3">
    <span class="ks-step-num">3</span> <span class="ks-step-label">Preferenze</span>
  </div>
</div>

          <h2 class="mb-2">Richiedi Assistenza</h2>
          <p class="text-muted mb-4">Compila i campi: puoi inviare via <strong>Email</strong> o <strong>WhatsApp</strong>.</p>

          <form id="assistanceForm" method="POST" action="<?php echo url('assets/php/process_assistance.php'); ?>" novalidate>
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
            <input type="hidden" name="assistance_type" id="assistance_type" value="">
            <!-- honeypot -->
            <input type="text" name="website" class="d-none" tabindex="-1" autocomplete="off">

            <!-- STEP 1: Contatti -->
            <fieldset class="ks-step-pane" data-step="1">
              <div class="row g-3">
                <div class="col-md-6">
                  <label class="form-label">Nome e Cognome *</label>
                  <input type="text" class="form-control" name="name" required>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Telefono *</label>
                  <input type="tel" class="form-control" name="phone" required inputmode="tel" placeholder="3xx xxx xxxx">
                </div>
                <div class="col-12">
                  <label class="form-label">Email <span class="text-muted">(facoltativa)</span></label>
                  <input type="email" class="form-control" name="email" placeholder="es. nome@mail.it">
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

            <!-- STEP 2: Dettagli -->
            <fieldset class="ks-step-pane d-none" data-step="2">
              <div class="row g-3">
                <div class="col-md-6">
                  <label class="form-label">Tipo Dispositivo *</label>
                  <select class="form-select" name="device_type" required>
                    <option value="">Seleziona…</option>
                    <option value="computer">Computer Desktop</option>
                    <option value="notebook">Notebook/Laptop</option>
                    <option value="smartphone">Smartphone</option>
                    <option value="tablet">Tablet</option>
                    <option value="stampante">Stampante</option>
                    <option value="rete">Rete/Router</option>
                    <option value="altro">Altro</option>
                  </select>
                </div>
                <div class="col-md-6" id="address-field" class="d-none">
                  <label class="form-label">Indirizzo (solo domicilio) *</label>
                  <input type="text" class="form-control" name="address" id="address" placeholder="Via, numero, città">
                </div>
                <div class="col-12">
                  <label class="form-label">Descrizione del Problema *</label>
                  <textarea class="form-control" name="problem_description" rows="5" required placeholder="Descrivi il problema…"></textarea>
                </div>
              </div>

              <!-- Info Remota -->
              <div class="remote-steps d-none mt-3" id="remote-steps">
                <div class="remote-steps-inner">
                  <strong><i class="ri-information-line me-1"></i> Assistenza Remota — Come funziona</strong>
                  <ol class="mt-2 mb-0">
                    <li>Confermiamo disponibilità</li>
                    <li>Ti inviamo link/ID TeamViewer/AnyDesk</li>
                    <li>Risolviamo insieme e inviamo report</li>
                  </ol>
                </div>
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

            <!-- STEP 3: Preferenze + Privacy -->
            <fieldset class="ks-step-pane d-none" data-step="3">
              <div class="row g-3">
                <div class="col-md-6">
                  <label class="form-label">Urgenza</label>
                  <select class="form-select" name="urgency">
                    <option value="normale">Normale (2–3 giorni)</option>
                    <option value="urgente">Urgente (24 ore)</option>
                    <option value="immediata">Immediata (stesso giorno)</option>
                  </select>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Fascia Oraria Preferita</label>
                  <select class="form-select" name="time_preference">
                    <option value="mattina">Mattina (9:00–13:00)</option>
                    <option value="pomeriggio">Pomeriggio (14:00–18:00)</option>
                    <option value="sera">Sera (18:00–20:00)</option>
                    <option value="qualsiasi">Qualsiasi orario</option>
                  </select>
                </div>
              </div>

              <div class="form-check my-3">
                <input class="form-check-input" type="checkbox" id="privacy" name="privacy" required>
                <label class="form-check-label" for="privacy">
                  Acconsento al trattamento dei dati secondo la <a href="<?php echo url('privacy.php'); ?>">Privacy Policy</a> *
                </label>
              </div>

<div class="ks-actions">
  <div class="ks-left">

        <button type="button" class="btn btn-prev ks-prev">
      <i class="ri-arrow-left-line"></i> <span class="btn-text">Indietro</span>
    </button>
  </div>
  <div class="ks-right">
    <a class="btn btn-wa btn-lg" target="_blank" id="send-whatsapp" rel="noopener">
      <i class="ri-whatsapp-line"></i> <span class="btn-text">WhatsApp</span>
    </a>
    <button type="submit" class="btn btn-mail btn-lg" id="send-email">
      <i class="ri-mail-send-line"></i> <span class="btn-text">Mail</span>
    </button>
  </div>
</div>
            </fieldset>
          </form>
        </div>
      </div>
    </div>
</section>

<!-- EMERGENCY FULL-WIDTH -->
<div class="row mt-4" data-aos="fade-up" data-aos-delay="250">
  <div class="col-12">
    <section class="emergency-wide em-centered" role="region" aria-labelledby="emergenza-title">
      <div class="ew-header">
        <div class="ew-beacon" aria-hidden="true">
          <span class="pulse"></span>
          <i class="ri-alarm-warning-line"></i>
        </div>
        <div>
          <h3 id="emergenza-title" class="mb-1">Emergenza Informatica?</h3>
          <p class="mb-0">Se un problema blocca la tua attività o lo studio, attiva la <strong>corsia prioritaria</strong>.</p>
        </div>
      </div>

      <ul class="ew-points centered">
        <li><i class="ri-flashlight-line"></i> Intervento prioritario</li>
        <li><i class="ri-shield-check-line"></i> Tecnici certificati & diagnosi mirata</li>
        <li><i class="ri-time-line"></i> Risposta rapida negli orari di apertura</li>
      </ul>

      <div class="ew-actions">
        <a href="tel:<?php echo PHONE_PRIMARY; ?>" class="btn btn-em-call btn-lg">
          <i class="ri-phone-line me-1"></i> Chiama Subito<span class="phone-number">: <?php echo PHONE_PRIMARY; ?>
        </a>
        <a href="<?php echo whatsapp_link('Emergenza informatica: ho bisogno di assistenza urgente'); ?>" class="btn btn-em-wa btn-lg" id="wa-emergency" target="_blank" rel="noopener">
          <i class="ri-whatsapp-line me-1"></i> WhatsApp Urgente
        </a>
        <small class="ew-note">Fuori orario: scrivi su WhatsApp, ti ricontattiamo alla prima apertura.</small>
      </div>
    </section>
  </div>
</div>


  </div>
</section>


<?php
$assist_metrics = [
  ['icon'=>'ri-tools-line','label'=>'Interventi/anno','value'=>800,'suffix'=>'+'],
  ['icon'=>'ri-remote-control-2-line','label'=>'Sessioni remote','value'=>300,'suffix'=>'+'],
  ['icon'=>'ri-home-2-line','label'=>'Uscite a domicilio','value'=>450,'suffix'=>'+'],
  ['icon'=>'ri-user-smile-line','label'=>'Clienti soddisfatti','value'=>98,'suffix'=>'%'],
  ['icon'=>'ri-time-line','label'=>'Tempo medio','value'=>2,'suffix'=>'h'],
  ['icon'=>'ri-star-smile-line','label'=>'Valutazione media','value'=>4.9,'suffix'=>'/5'],
];
?>
<section class="section section-proof bg-gradient-orange">
  <div class="container position-relative">
    <div class="section-header text-center">
      <h2 class="section-title text-white">Alcuni dei nostri numeri</h2>
      <p class="section-subtitle text-white-80">Numeri indicativi su base annuale</p>
    </div>
    <div class="row g-4 justify-content-center equalize">
      <?php foreach($assist_metrics as $m): ?>
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
    <p class="why-note mt-3 text-center text-white-70"></p>
  </div>
</section>

<!-- FAQ -->
<section class="faq-section">
  <div class="container">
    <h2 class="text-center mb-5">Domande Frequenti sull'Assistenza</h2>

    <div class="accordion accordion-faq" id="faqAccordion">
      <div class="accordion-item">
        <h2 class="accordion-header">
          <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
            Quanto costa l'assistenza a domicilio?
          </button>
        </h2>
        <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
          <div class="accordion-body">
            L’assistenza a domicilio parte da <strong>€35</strong> + <strong>trasferta</strong> per Ginosa e zone limitrofe.
            Il costo finale dipende dalla complessità dell’intervento. Il preventivo viene sempre comunicato prima di iniziare.
          </div>
        </div>
      </div>

      <div class="accordion-item">
        <h2 class="accordion-header">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
            L’assistenza remota è sicura?
          </button>
        </h2>
        <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
          <div class="accordion-body">
            Sì. Utilizziamo software professionali (TeamViewer/AnyDesk). Segui tutto ciò che facciamo e puoi
            interrompere la connessione in qualunque momento.
          </div>
        </div>
      </div>

      <div class="accordion-item">
        <h2 class="accordion-header">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
            In quanto tempo intervenite?
          </button>
        </h2>
        <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
          <div class="accordion-body">
            Remota: spesso <strong>immediata</strong> in base alla disponibilità. Domicilio: appuntamento entro <strong>24–48h</strong>.
          </div>
        </div>
      </div>

      <div class="accordion-item">
        <h2 class="accordion-header">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
            Che tipo di problemi risolvete?
          </button>
        </h2>
        <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
          <div class="accordion-body">
            Problemi software e sistema, rimozione virus, backup e recupero dati, installazione programmi e periferiche,
            rete Wi-Fi/Router, ottimizzazioni e altro ancora.
          </div>
        </div>
      </div>

      <div class="accordion-item">
        <h2 class="accordion-header">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq5">
            Offrite garanzia sul lavoro svolto?
          </button>
        </h2>
        <div id="faq5" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
          <div class="accordion-body">
            Sì, gli interventi sono <strong>garantiti 30 giorni</strong>. Se il problema si ripresenta nello stesso periodo,
            interveniamo per risolverlo definitivamente.
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<?php
// JSON-LD per i due servizi
$ld = [
  '@context' => 'https://schema.org',
  '@type' => 'ItemList',
  'name' => 'Assistenza tecnica Key Soft Italia',
  'itemListElement' => [
    [
      '@type' => 'ListItem',
      'position' => 1,
      'item' => [
        '@type' => 'Service',
        'name' => 'Assistenza a Domicilio',
        'serviceType' => 'On-site IT support',
        'url' => url('assistenza.php#tipo-assistenza')
      ]
    ],
    [
      '@type' => 'ListItem',
      'position' => 2,
      'item' => [
        '@type' => 'Service',
        'name' => 'Assistenza Remota',
        'serviceType' => 'Remote IT support',
        'url' => url('assistenza.php#tipo-assistenza')
      ]
    ]
  ]
];
?>
<script type="application/ld+json">
<?php echo json_encode($ld, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT); ?>
</script>

<?php include 'includes/footer.php'; ?>

<!-- JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo asset('js/main.js'); ?>"></script>
<script src="https://unpkg.com/aos@next/dist/aos.js"></script>
<script>AOS.init();</script>

<script>
// ===== Multistep form + validazioni + canali invio =====
(function(){
  const formSection = document.querySelector('.form-section');
  const form = document.getElementById('assistanceForm');
  const stepPanes = [...form.querySelectorAll('.ks-step-pane')];
  const steps = [...document.querySelectorAll('.ks-stepper .ks-step')];
  const nextBtns = form.querySelectorAll('.ks-next');
  const prevBtns = form.querySelectorAll('.ks-prev');
  const goEmergencyBtns = form.querySelectorAll('.ks-goto-emergency');

  const hiddenType  = document.getElementById('assistance_type');
  const addressWrap = document.getElementById('address-field');
  const addressInput= document.getElementById('address');
  const remoteSteps = document.getElementById('remote-steps');

  const sendEmailBtn = document.getElementById('send-email');
  const sendWaBtn    = document.getElementById('send-whatsapp');

  const emSection = document.querySelector('.emergency-wide, .emergency-wide.em-centered');

  let current = 1;

  // --- Smooth scroll con offset per header sticky ---
function getHeaderOffset() {
  const hdr = document.querySelector('.site-header, header.sticky-top, header.navbar, .main-header');
  return hdr ? (hdr.getBoundingClientRect().height + 16) : 96; // fallback ~96px
}
function smoothScrollTo(el) {
  if (!el) return;
  const y = el.getBoundingClientRect().top + window.pageYOffset - getHeaderOffset();
  window.scrollTo({ top: y, behavior: 'smooth' });
}

  function showFormIfHidden(){
    if (formSection && formSection.classList.contains('d-none')) {
      formSection.classList.remove('d-none');
      if (window.AOS) setTimeout(()=>AOS.refresh(),0);
    }
  }
  // gestione tipo (da selettore cards esterno)
  function applyType(type){
    hiddenType.value = type || '';
    const isHome = type === 'domicilio';
    addressWrap.classList.toggle('d-none', !isHome);
    if (isHome) addressInput.setAttribute('required',''); else addressInput.removeAttribute('required');
    remoteSteps.classList.toggle('d-none', isHome);
  }

  // click sulle card: **mostra form** e **resetta** tutto ogni volta che cambi tipo
  let lastType = null;
  document.querySelectorAll('.assistance-type-card').forEach(btn=>{
    btn.addEventListener('click', ()=>{
      const type = btn.dataset.type;
      document.querySelectorAll('.assistance-type-card').forEach(b=>{
        b.classList.toggle('selected', b===btn);
        b.setAttribute('aria-pressed', b===btn ? 'true' : 'false');
      });

      // se il tipo è diverso dal precedente, resetta il form
      if (type !== lastType){
        showFormIfHidden();
        applyType(type);
        resetFormFields();
        lastType = type;
      } else {
        // se è lo stesso, solo scorri al form
        showFormIfHidden();
      }
      smoothScrollTo(document.getElementById('titolo-form'));
    });
  });

  // deep-link ?type=remota/dom
  const qs = new URLSearchParams(location.search);
  const initType = (qs.get('type') === 'remota') ? 'remota' : (qs.get('type') === 'domicilio' ? 'domicilio' : null);
  if (initType){
    const targetBtn = document.querySelector(`.assistance-type-card[data-type="${initType}"]`);
    if (targetBtn) targetBtn.click();
  }

// stepper helpers (FIX)
function setStep(n){
  current = n; // <— aggiorna lo stato corrente
  stepPanes.forEach(p => p.classList.toggle('d-none', +p.dataset.step !== n));
  steps.forEach(s => s.classList.toggle('active', +s.dataset.step === n));
}

function validateStep(n){
  const pane = stepPanes[n-1];
  const required = [...pane.querySelectorAll('[required]')];

  let allOk = true;
  required.forEach(el => {
    // validazione per singolo campo (no "ok" globale)
    const valid = (el.type === 'checkbox') ? el.checked : !!el.value.trim();
    el.classList.toggle('is-invalid', !valid);
    if (!valid) allOk = false;
  });
  return allOk;
}

  nextBtns.forEach(b=> b.addEventListener('click', ()=>{
    if (!validateStep(current)) return;
    setStep(Math.min(3, current+1));
    smoothScrollTo(document.getElementById('titolo-form'));
  }));
  prevBtns.forEach(b=> b.addEventListener('click', ()=>{
    setStep(Math.max(1, current-1));
    smoothScrollTo(document.getElementById('titolo-form'));
  }));

  goEmergencyBtns.forEach(b=> b.addEventListener('click', ()=>{
    if (emSection) smoothScrollTo(emSection);
  }));

  function resetFormFields(){
    if (!form) return;
    form.reset();
    // rimuovi stati di validazione
    form.querySelectorAll('.is-invalid').forEach(el=>el.classList.remove('is-invalid'));
    // reimposta step 1
    setStep(1);
    // reimposta UI condizionali
    applyType(hiddenType.value || ''); // mantiene il tipo corrente ma resetta i campi condizionali
  }

  function ensurePrivacyThen(formEl, onOk){
  const modalEl = document.getElementById('privacyModal');
  const bsModal = new bootstrap.Modal(modalEl, {backdrop:'static'});
  const chkForm = formEl.querySelector('#privacy');
  if (chkForm && chkForm.checked) { onOk(); return; }

  // reset checkbox del modal
  const chk = document.getElementById('privacyModalCheck');
  chk.checked = false;

  const acceptBtn = document.getElementById('privacyModalAccept');
  function handleAccept(){
    if (!chk.checked) return;
    // spunta la checkbox reale nel form
    if (chkForm) chkForm.checked = true;
    acceptBtn.removeEventListener('click', handleAccept);
    bsModal.hide();
    onOk();
  }
  acceptBtn.addEventListener('click', handleAccept);
  bsModal.show();
}

// ====== Builder messaggi WhatsApp (formattati e sanificati) ======
function normalizeText(s){ return (s || '').toString().trim().replace(/\s+/g,' '); }

// --- Builder messaggio standard (usa solo emoji full-Unicode, niente font-icone) ---
function buildWaMessage(data){
  const tipoUC = (data.tipo || 'DOMICILIO').toString().toUpperCase();
  const lines = [
    `🆘 *Richiesta Assistenza (${tipoUC})*`,
    `👤 *Nome:* ${normalizeText(data.name)}`,
    `📞 *Telefono:* ${normalizeText(data.phone)}`,
  ];
  const email = normalizeText(data.email);
  if (email) lines.push(`📧 *Email:* ${email}`);
  lines.push(`💻 *Dispositivo:* ${normalizeText(data.device)}`);
  if (tipoUC === 'DOMICILIO' && normalizeText(data.address)) {
    lines.push(`🏠 *Indirizzo:* ${normalizeText(data.address)}`);
  }
  lines.push(`❗ *Problema:* ${normalizeText(data.prob)}`);
  lines.push(`⏱️ *Urgenza:* ${normalizeText(data.urgency)}  •  🕘 *Fascia:* ${normalizeText(data.timepref)}`);
  return lines.join('\n');
}

// --- Builder messaggio Emergenza ---
function buildEmergencyMessage(){
  return [
    '🚨 *EMERGENZA INFORMATICA*',
    'Ho bisogno di assistenza *urgente*.',
    'Preferisco *prima disponibilità utile* (remota o in loco).',
    'Grazie!'
  ].join('\n');
}

// --- Costruisci link WhatsApp SEMPRE con encodeURIComponent (UTF-8) ---
function waLinkFromText(text){
  // Se hai un helper PHP, puoi ignorarlo e forzare qui:
  // Imposta qui il numero con prefisso internazionale, es. +39XXXXXXXXXX
  const phone = "<?= preg_replace('/\D+/', '', (defined('PHONE_WHATSAPP') ? PHONE_SECONDARY : PHONE_PRIMARY)); ?>";
  const base = `https://wa.me/${phone}`;
  return `${base}?text=${encodeURIComponent(text)}`;
}

(function(){
  const form = document.getElementById('assistanceForm');
  const hiddenType  = document.getElementById('assistance_type');
  const sendWaBtn   = document.getElementById('send-whatsapp');
  const sendEmailBtn= document.getElementById('send-email');
  const emergencyBtn= document.getElementById('wa-emergency');

  function collectFormData(){
    return {
      tipo: (hiddenType && hiddenType.value) || 'domicilio',
      name: form.name?.value || '',
      phone: form.phone?.value || '',
      email: form.email?.value || '',
      device: form.device_type?.value || '',
      address: form.address?.value || '',
      prob: form.problem_description?.value || '',
      urgency: form.urgency?.value || 'normale',
      timepref: form.time_preference?.value || 'qualsiasi'
    };
  }

  // Invia via WhatsApp (step 3, bottone sinistro)
  if (sendWaBtn){
    sendWaBtn.addEventListener('click', (e)=>{
      e.preventDefault();
      ensurePrivacyThen(form, ()=>{
        // (opzionale) valida step 1 e 2 se vuoi essere rigido
        const d = collectFormData();
        const msg = buildWaMessage(d);
        const href = waLinkFromText(msg);
        // apri in nuova scheda
        window.open(href, '_blank', 'noopener');
      });
    });
  }

  // Submit via Email (POST)
  if (sendEmailBtn){
    form.addEventListener('submit', (e)=>{
      e.preventDefault();
      ensurePrivacyThen(form, ()=>{
        // lascia che il form proceda
        form.submit();
      });
    });
  }

  // WhatsApp Urgenza (box emergenza)
  if (emergencyBtn){
    emergencyBtn.addEventListener('click', (e)=>{
      // niente privacy obbligatoria qui (è contatto diretto), ma puoi riutilizzarla se vuoi
      const msg = buildEmergencyMessage();
      const href = waLinkFromText(msg);
      emergencyBtn.setAttribute('href', href);
      // lasciamo aprire il link normalmente
    });
  }
})();

  // Init primo step
  setStep(1);
})();
</script>

<script>
// Contatori "proof" al primo ingresso in viewport
(function(){
  const section = document.querySelector('.section-proof');
  if(!section) return;
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
</script>

</body>
</html>
