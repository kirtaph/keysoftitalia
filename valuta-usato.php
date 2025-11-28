<?php
/**
 * Key Soft Italia - Valuta il tuo Usato (Wizard 4 step)
 */

if (!defined('BASE_PATH')) {
  define('BASE_PATH', __DIR__ . '/');
}
require_once BASE_PATH . 'config/config.php';

$page_title       = "Valuta il tuo Usato - Key Soft Italia";
$page_description = "Smartphone, tablet, notebook o console: dicci in che condizioni è il tuo dispositivo e ti ricontattiamo con una proposta di valutazione personalizzata.";
$page_keywords    = "valutazione usato smartphone, valutazione tablet, valutazione notebook, valutazione console";

$breadcrumbs = [
  ['label' => 'Valuta il tuo Usato', 'url' => 'valuta-usato.php']
];
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
      'url'         => url('valuta-usato.php')
  ]); ?>
  <link rel="stylesheet" href="<?php echo asset_version('css/pages/valuta-usato.css'); ?>">
</head>
<body data-aos-easing="ease-in-out" data-aos-duration="800" data-aos-once="true">
<?php include 'includes/header.php'; ?>

<main id="main-content">

  <!-- HERO -->
  <section class="hero hero-secondary text-center">
    <div class="hero-pattern"></div>
    <div class="container position-relative z-2">
      <div class="hero-icon mb-3" data-aos="zoom-in">
        <i class="ri-recycle-line"></i>
      </div>
      <h1 class="hero-title text-white" data-aos="fade-up">
        Valuta il tuo <span class="text-gradient">Usato</span>
      </h1>
      <p class="hero-subtitle" data-aos="fade-up" data-aos-delay="100">
        Smartphone, tablet, notebook e console: ottieni una proposta di valutazione in pochi step.
      </p>
      <div class="hero-breadcrumb mt-3" data-aos="fade-up" data-aos-delay="150">
        <?= generate_breadcrumbs($breadcrumbs); ?>
      </div>
    </div>
  </section>

  <!-- FORM WIZARD -->
  <section class="section quote-wizard" aria-labelledby="titolo-wizard">
    <div class="container">
      <div class="form-card" data-aos="fade-up">
        <!-- Stepper -->
        <div class="ks-stepper" aria-hidden="true">
          <div class="ks-step active" data-step="1"><span class="ks-step-num">1</span><span class="ks-step-label">Dispositivo</span></div>
          <div class="ks-step" data-step="2"><span class="ks-step-num">2</span><span class="ks-step-label">Stato</span></div>
          <div class="ks-step" data-step="3"><span class="ks-step-num">3</span><span class="ks-step-label">Dati</span></div>
          <div class="ks-step" data-step="4"><span class="ks-step-num">4</span><span class="ks-step-label">Riepilogo</span></div>
        </div>

        <h2 id="wizard_title" class="form-title">Richiedi Valutazione</h2>
        <p id="wizard_subtitle" class="form-subtitle">Completa i passaggi per ricevere una proposta.</p>

        <form id="usedQuoteWizard" method="POST" action="#" novalidate>
            <?php echo generate_csrf_field(); ?>
            <input type="hidden" name="contact_channel" id="contact_channel" value="form">
            <input type="hidden" name="quote_id" id="quote_id" value="">
            <input type="hidden" name="wizard_payload" id="wizard_payload" value="">

            <!-- ================= STEP 1 ================= -->
            <fieldset class="ks-step-pane" data-step="1">
              <div class="step-block">
                <h3 class="block-title"><i class="ri-smartphone-line"></i> Scegli il tipo di dispositivo</h3>
                <div class="device-grid" role="group" aria-label="Tipo di dispositivo">
                  <button type="button" class="device-card" data-device="smartphone" aria-pressed="false">
                    <i class="ri-smartphone-line"></i><span>Smartphone</span>
                  </button>
                  <button type="button" class="device-card" data-device="tablet" aria-pressed="false">
                    <i class="ri-tablet-line"></i><span>Tablet</span>
                  </button>
                  <button type="button" class="device-card" data-device="computer" aria-pressed="false">
                    <i class="ri-macbook-line"></i><span>Notebook / PC</span>
                  </button>
                  <button type="button" class="device-card" data-device="console" aria-pressed="false">
                    <i class="ri-gamepad-line"></i><span>Console</span>
                  </button>
                </div>
                <input type="hidden" name="device" id="device" value="">
              </div>

              <div class="step-block">
                <h3 class="block-title"><i class="ri-price-tag-3-line"></i> Marca &amp; Modello</h3>
                <div class="row g-3 align-items-end">
                  <!-- Brand (da DB) -->
                  <div class="col-md-4">
                    <label class="form-label">Marca *</label>
                    <select class="form-select" id="brand" name="brand" required disabled>
                      <option value="">Seleziona il dispositivo prima…</option>
                    </select>
                    <input type="hidden" name="brand_id" id="brand_id" value="">
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
                    <input type="text" class="form-control" id="model" name="model" placeholder="Es. iPhone 12, IdeaPad 3…">
                    <input type="hidden" name="model_id" id="model_id" value="">
                  </div>

                  <!-- Modello “Altro modello…” -->
                  <div class="col-md-4 d-none" id="model-other-wrap">
                    <label class="form-label">Specifica modello</label>
                    <input type="text" class="form-control" id="model_other" name="model_other" placeholder="Scrivi il modello">
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

            <!-- ================= STEP 2 ================= -->
            <fieldset class="ks-step-pane d-none" data-step="2">
              <div class="step-block">
                <h3 class="block-title"><i class="ri-heart-pulse-line"></i> Stato generale</h3>
                <!-- Condition Cards Grid -->
                <div class="condition-grid" id="condition-grid" role="group" aria-label="Stato del dispositivo">
                  <button type="button" class="condition-card" data-value="ottimo">
                    <i class="ri-star-smile-line"></i>
                    <div class="cond-text">
                      <strong>Ottimo</strong>
                      <span>Pari al nuovo</span>
                    </div>
                  </button>
                  <button type="button" class="condition-card" data-value="buono">
                    <i class="ri-thumb-up-line"></i>
                    <div class="cond-text">
                      <strong>Buono</strong>
                      <span>Normali segni d'uso</span>
                    </div>
                  </button>
                  <button type="button" class="condition-card" data-value="usurato">
                    <i class="ri-indeterminate-circle-line"></i>
                    <div class="cond-text">
                      <strong>Usurato</strong>
                      <span>Graffi evidenti / Urti</span>
                    </div>
                  </button>
                  <button type="button" class="condition-card" data-value="danneggiato">
                    <i class="ri-error-warning-line"></i>
                    <div class="cond-text">
                      <strong>Danneggiato</strong>
                      <span>Difetti importanti</span>
                    </div>
                  </button>
                </div>
                <input type="hidden" name="device_condition" id="device_condition" value="">
              </div>

              <div class="step-block">
                <h3 class="block-title"><i class="ri-tools-line"></i> Problemi presenti</h3>
                <div id="defect-grid" class="problem-grid" role="group" aria-label="Problemi selezionabili">
                  <button type="button" class="problem-card" data-label="Schermo rotto"><i class="ri-smartphone-line"></i><span>Schermo rotto</span></button>
                  <button type="button" class="problem-card" data-label="Batteria esausta"><i class="ri-battery-low-line"></i><span>Batteria esausta</span></button>
                  <button type="button" class="problem-card" data-label="Fotocamera difettosa"><i class="ri-camera-line"></i><span>Fotocamera difettosa</span></button>
                  <button type="button" class="problem-card" data-label="Microfono / audio difettoso"><i class="ri-mic-line"></i><span>Microfono / audio difettoso</span></button>
                  <button type="button" class="problem-card" data-label="Tasti non funzionanti"><i class="ri-keyboard-line"></i><span>Tasti non funzionanti</span></button>
                  <button type="button" class="problem-card" data-label="Spegnimenti improvvisi"><i class="ri-alert-line"></i><span>Spegnimenti improvvisi</span></button>
                  <button type="button" class="problem-card" data-label="Altro"><i class="ri-more-line"></i><span>Altro</span></button>
                </div>
                <textarea class="form-control mt-2" id="defect_other" name="defect_other" rows="2" placeholder="Descrivi altri problemi (obbligatorio solo se selezioni Altro)"></textarea>
              </div>

              <div class="step-block">
                <h3 class="block-title"><i class="ri-gift-line"></i> Accessori inclusi</h3>
                <!-- Accessory Cards Grid -->
                <div class="accessory-grid" id="accessory-grid" role="group" aria-label="Accessori inclusi">
                  <button type="button" class="accessory-card" data-value="Scatola"><i class="ri-archive-line"></i><span>Scatola</span></button>
                  <button type="button" class="accessory-card" data-value="Caricatore"><i class="ri-battery-charge-line"></i><span>Caricatore</span></button>
                  <button type="button" class="accessory-card" data-value="Cavo"><i class="ri-usb-line"></i><span>Cavo</span></button>
                  <button type="button" class="accessory-card" data-value="Custodia"><i class="ri-shield-line"></i><span>Custodia</span></button>
                  <button type="button" class="accessory-card" data-value="Nessuno"><i class="ri-prohibited-line"></i><span>Nessuno</span></button>
                </div>
              </div>

              <div class="step-block">
                <h3 class="block-title"><i class="ri-money-euro-box-line"></i> Valore richiesto e note</h3>
                <div class="row g-3">
                  <div class="col-md-4">
                    <label class="form-label">Valore richiesto <span class="text-muted">(opzionale)</span></label>
                    <div class="input-group">
                      <span class="input-group-text">€</span>
                      <input type="number" min="0" step="10" class="form-control" id="expected_price" name="expected_price" placeholder="Es. 200">
                    </div>
                  </div>
                  <div class="col-md-8">
                    <label class="form-label">Note aggiuntive</label>
                    <textarea class="form-control" id="notes" name="notes" rows="2" placeholder="Es. presenza scontrino, stato estetico…"></textarea>
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
                  <button type="button" class="btn btn-prev ks-prev">
                    <i class="ri-arrow-left-line"></i> <span class="btn-text">Indietro</span>
                  </button>
                  <button type="button" class="btn btn-next ks-next">
                    <i class="ri-arrow-right-line"></i> <span class="btn-text">Avanti</span>
                  </button>
                </div>
              </div>
            </fieldset>

            <!-- ================= STEP 3 (DATI + INVIO) ================= -->
            <fieldset class="ks-step-pane d-none" data-step="3">
              <div class="step-block">
                <h3 class="block-title"><i class="ri-user-line"></i> I tuoi dati</h3>
                <div class="row g-3">
                  <div class="col-md-6"><label class="form-label">Nome *</label><input type="text" class="form-control" name="firstName" required></div>
                  <div class="col-md-6"><label class="form-label">Cognome *</label><input type="text" class="form-control" name="lastName" required></div>
                  <div class="col-md-6"><label class="form-label">Email *</label><input type="email" class="form-control" name="email" required></div>
                  <div class="col-md-6"><label class="form-label">Telefono *</label><input type="tel" class="form-control" name="phone" required inputmode="tel" placeholder="3xx xxx xxxx"></div>
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
                  <button type="button" class="btn btn-wa" id="btn_whatsapp" data-channel="whatsapp">
                    <i class="ri-whatsapp-line"></i> <span class="btn-text">WhatsApp</span>
                  </button>
                  <button type="button" class="btn btn-mail" id="btn_submit" data-channel="form">
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
                  <ul class="summary-list">
                    <li><strong>Dispositivo:</strong> <span id="sum_device">—</span></li>
                    <li><strong>Marca:</strong> <span id="sum_brand">—</span></li>
                    <li><strong>Modello:</strong> <span id="sum_model">—</span></li>
                    <li><strong>Stato:</strong> <span id="sum_condition">—</span></li>
                    <li><strong>Problemi:</strong> <span id="sum_defects">—</span></li>
                    <li><strong>Accessori:</strong> <span id="sum_accessories">—</span></li>
                    <li><strong>Valore richiesto:</strong> <span id="sum_expected">—</span></li>
                    <li><strong>Note:</strong> <span id="sum_notes">—</span></li>
                  </ul>
                  <div class="summary-note mt-3">
                    ✅ Richiesta registrata. Ti contatteremo al più presto per la valutazione.
                  </div>
                </div>
              </div>

              <div class="ks-actions">
                <div class="ks-left">
                  <button type="button" class="btn btn-light" id="btn_restart">
                    <i class="ri-refresh-line"></i> <span class="btn-text">Ricomincia</span>
                  </button>
                </div>
                <div class="ks-right"></div>
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
              <a href="<?php echo whatsapp_link('🚨 EMERGENZA VALUTAZIONE USATO: richiesta prioritaria'); ?>" class="btn btn-em-wa btn-lg" id="wa-emergency" target="_blank" rel="noopener">
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
        <h2 class="section-title">Come funziona</h2>
        <p class="section-subtitle">Dalla richiesta di valutazione al pagamento, in 4 passi</p>
      </div>
      <ol class="row g-4 process-steps align-items-stretch">
        <li class="col-md-6 col-lg-3">
          <div class="process-item" data-aos="fade-up">
            <div class="step-head">
              <span class="step-badge">1</span>
              <span class="step-icon"><i class="ri-edit-line" aria-hidden="true"></i></span>
            </div>
            <h3 class="step-title">Richiesta online</h3>
            <p class="step-text">Compili il wizard o ci scrivi su WhatsApp con i dati del dispositivo.</p>
          </div>
        </li>
        <li class="col-md-6 col-lg-3">
          <div class="process-item" data-aos="fade-up" data-aos-delay="60">
            <div class="step-head">
              <span class="step-badge">2</span>
              <span class="step-icon"><i class="ri-search-eye-line" aria-hidden="true"></i></span>
            </div>
            <h3 class="step-title">Prima valutazione</h3>
            <p class="step-text">Facciamo una stima indicativa sulla base delle informazioni inviate.</p>
          </div>
        </li>
        <li class="col-md-6 col-lg-3">
          <div class="process-item" data-aos="fade-up" data-aos-delay="120">
            <div class="step-head">
              <span class="step-badge">3</span>
              <span class="step-icon"><i class="ri-store-2-line" aria-hidden="true"></i></span>
            </div>
            <h3 class="step-title">Verifica in negozio</h3>
            <p class="step-text">Porti il dispositivo: il tecnico verifica condizioni e accessori.</p>
          </div>
        </li>
        <li class="col-md-6 col-lg-3">
          <div class="process-item" data-aos="fade-up" data-aos-delay="180">
            <div class="step-head">
              <span class="step-badge">4</span>
              <span class="step-icon"><i class="ri-money-euro-circle-line" aria-hidden="true"></i></span>
            </div>
            <h3 class="step-title">Proposta & pagamento</h3>
            <p class="step-text">Confermi la proposta di ritiro e procediamo con il pagamento concordato.</p>
          </div>
        </li>
      </ol>
      <div class="trust-badges" data-aos="fade-up" data-aos-delay="240" aria-label="Punti di fiducia">
        <div class="tb-item"><i class="ri-shield-check-line" aria-hidden="true"></i><span>Valutazione trasparente</span></div>
        <div class="tb-item"><i class="ri-file-list-3-line" aria-hidden="true"></i><span>Ricevuta/fattura</span></div>
        <div class="tb-item"><i class="ri-time-line" aria-hidden="true"></i><span>Risposta rapida</span></div>
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
          <div class="accordion accordion-faq" id="faqAccordionUsed">
            <div class="accordion-item">
              <h2 class="accordion-header"><button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#u_faq1">La valutazione è gratuita?</button></h2>
              <div id="u_faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordionUsed"><div class="accordion-body">Sì, la valutazione è gratuita e senza impegno. Decidi tu se accettare o meno la proposta.</div></div>
            </div>
            <div class="accordion-item">
              <h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#u_faq2">Quando ricevo una risposta?</button></h2>
              <div id="u_faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordionUsed"><div class="accordion-body">Ti rispondiamo entro 24 ore lavorative. Nei casi urgenti puoi usare il canale emergenza.</div></div>
            </div>
            <div class="accordion-item">
              <h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#u_faq3">Il prezzo che proponete è definitivo?</button></h2>
              <div id="u_faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordionUsed"><div class="accordion-body">Il prezzo viene confermato solo dopo la verifica fisica del dispositivo e degli accessori.</div></div>
            </div>
            <div class="accordion-item">
              <h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#u_faq4">Cosa devo portare con me?</button></h2>
              <div id="u_faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordionUsed"><div class="accordion-body">Dispositivo, eventuali accessori (scatola, caricatore, cavo, custodia) e, se disponibile, scontrino o fattura.</div></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>

<?php include __DIR__ . '/includes/footer.php'; ?>

<!-- JS WIZARD -->
<script>
document.addEventListener('DOMContentLoaded', function () {
  const wizard = document.getElementById('usedQuoteWizard');
  if (!wizard) return;

  const stepButtons = document.querySelectorAll('.ks-stepper .ks-step');
  const panes = wizard.querySelectorAll('.ks-step-pane');
  const emSection = document.querySelector('.emergency-wide');
  const formCard = document.querySelector('.form-card');
  const wizardTitle = document.getElementById('wizard_title');
  const wizardSubtitle = document.getElementById('wizard_subtitle');

  const deviceCards = wizard.querySelectorAll('.device-card');
  const deviceInput = document.getElementById('device');

  const brandSelect = document.getElementById('brand');
  const brandIdInput = document.getElementById('brand_id');
  const brandOtherWrap = document.getElementById('brand-other-wrap');
  const brandOtherInput = document.getElementById('brand_other');

  const modelSelect = document.getElementById('model_select');
  const modelInput = document.getElementById('model');
  const modelIdInput = document.getElementById('model_id');
  const modelOtherWrap = document.getElementById('model-other-wrap');
  const modelOtherInput = document.getElementById('model_other');

  const conditionGrid = document.getElementById('condition-grid');
  const conditionInput = document.getElementById('device_condition');

  const defectGrid = document.getElementById('defect_grid') || document.getElementById('defect-grid');
  const defectOther = document.getElementById('defect_other');

  const accessoryGrid = document.getElementById('accessory-grid');
  const expectedPriceInput = document.getElementById('expected_price');
  const notesInput = document.getElementById('notes');
  const contactChannelInput = document.getElementById('contact_channel');

  // summary
  const sumDevice = document.getElementById('sum_device');
  const sumBrand = document.getElementById('sum_brand');
  const sumModel = document.getElementById('sum_model');
  const sumCondition = document.getElementById('sum_condition');
  const sumDefects = document.getElementById('sum_defects');
  const sumAccessories = document.getElementById('sum_accessories');
  const sumExpected = document.getElementById('sum_expected');
  const sumNotes = document.getElementById('sum_notes');

  const BTN_CLASS_ACTIVE = 'active';
  const BRAND_OTHER_VALUE = '__other__';
  const MODEL_OTHER_VALUE = '__other__';

  let currentStep = 1;
  let selectedDevice = null;
  let selectedDefects = new Set();
  let selectedAccessories = new Set();
  let lastSavedId = null;

  function normalizeText(s) {
    return (s || '').toString().trim().replace(/\s+/g, ' ');
  }

  function waLinkFromText(text) {
    const phone = "<?= preg_replace('/\D+/', '', (defined('PHONE_WHATSAPP') ? PHONE_WHATSAPP : PHONE_PRIMARY)); ?>";
    return `https://wa.me/${phone}?text=${encodeURIComponent(text)}`;
  }

  function setStep(step) {
    currentStep = step;

    panes.forEach(p => {
      const s = Number(p.dataset.step || 0);
      p.classList.toggle('d-none', s !== step);
    });

    stepButtons.forEach(btn => {
      const s = Number(btn.dataset.step || 0);
      btn.classList.toggle(BTN_CLASS_ACTIVE, s === step);
      btn.disabled = s > step;
      if (s === step) {
        btn.setAttribute('aria-current', 'step');
      } else {
        btn.removeAttribute('aria-current');
      }
    });

    const isSummary = (step === 4);
    if (formCard) formCard.classList.toggle('summary-mode', isSummary);
    if (wizardTitle) wizardTitle.style.display = isSummary ? 'none' : '';
    if (wizardSubtitle) wizardSubtitle.style.display = isSummary ? 'none' : '';

    if (step === 4) renderSummary();

    // SCROLL TO TOP OF WIZARD
    const wizardContainer = document.querySelector('.quote-wizard');
    if (wizardContainer) {
        wizardContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
  }

  function getSelectedDeviceLabel() {
    const card = wizard.querySelector('.device-card.active');
    return card ? card.textContent.trim() : '';
  }

  function getBrandDisplay() {
    const val = brandSelect.value || '';
    if (!val) return '';
    if (val === BRAND_OTHER_VALUE) {
      return normalizeText(brandOtherInput.value) || 'Altro';
    }
    const opt = brandSelect.options[brandSelect.selectedIndex];
    return opt ? normalizeText(opt.textContent) : '';
  }

  function getModelDisplay() {
    // Se la select è visibile, usiamo quella
    if (!modelSelect.classList.contains('d-none')) {
      const val = modelSelect.value || '';
      if (val === MODEL_OTHER_VALUE) {
        return normalizeText(modelOtherInput.value) || '';
      }
      const opt = modelSelect.options[modelSelect.selectedIndex];
      return opt ? normalizeText(opt.textContent) : '';
    }
    // Altrimenti input diretto
    return normalizeText(modelInput.value);
  }

  function getConditionLabel() {
    const val = conditionInput.value;
    if (!val) return '';
    // Mappa valori a etichette leggibili
    const map = {
        'ottimo': 'Ottimo',
        'buono': 'Buono',
        'usurato': 'Usurato',
        'danneggiato': 'Danneggiato'
    };
    return map[val] || val;
  }

  function getSelectedDefectsArray() {
    const arr = Array.from(selectedDefects);
    const hasAltro = arr.includes('Altro');
    const extra = normalizeText(defectOther.value);
    if (hasAltro && extra) {
      return arr.filter(d => d !== 'Altro').concat([`Altro: ${extra}`]);
    }
    return arr;
  }

  function getSelectedAccessoriesArray() {
    const arr = Array.from(selectedAccessories);
    if (arr.includes('Nessuno') && arr.length > 1) {
      return arr.filter(a => a !== 'Nessuno');
    }
    return arr;
  }

  function renderSummary() {
    sumDevice.textContent = getSelectedDeviceLabel() || '—';
    sumBrand.textContent = getBrandDisplay() || '—';
    sumModel.textContent = getModelDisplay() || '—';
    sumCondition.textContent = getConditionLabel() || '—';

    const defects = getSelectedDefectsArray();
    sumDefects.textContent = defects.length ? defects.join(', ') : '—';

    const accessories = getSelectedAccessoriesArray();
    sumAccessories.textContent = accessories.length ? accessories.join(', ') : '—';

    const valExp = expectedPriceInput.value ? `€ ${expectedPriceInput.value}` : '—';
    sumExpected.textContent = valExp;

    const notesVal = normalizeText(notesInput.value);
    sumNotes.textContent = notesVal || '—';
  }

  function validateStep(step) {
    if (step === 1) {
      if (!selectedDevice) {
        showToast({type:'danger', title:'Dispositivo', message:'Seleziona il tipo di dispositivo.'});
        return false;
      }
      if (!brandSelect.value) {
        showToast({type:'danger', title:'Marca', message:'Seleziona la marca del dispositivo.'});
        return false;
      }
      if (brandSelect.value === BRAND_OTHER_VALUE && !normalizeText(brandOtherInput.value)) {
        showToast({type:'danger', title:'Marca', message:'Specifica la marca del dispositivo.'});
        return false;
      }
      if (!modelSelect.classList.contains('d-none')) {
         if (modelSelect.value === MODEL_OTHER_VALUE && !normalizeText(modelOtherInput.value)) {
            showToast({type:'danger', title:'Modello', message:'Specifica il modello del dispositivo.'});
            return false;
         }
      }
    }

    if (step === 2) {
      if (!conditionInput.value) {
        showToast({type:'danger', title:'Stato', message:'Seleziona lo stato del dispositivo.'});
        return false;
      }
      if (selectedDefects.has('Altro') && !normalizeText(defectOther.value)) {
        showToast({type:'danger', title:'Problemi', message:'Hai selezionato “Altro”: descrivi i problemi.'});
        return false;
      }
    }

    if (step === 3) {
      const first = normalizeText(wizard.elements['firstName'].value);
      const last = normalizeText(wizard.elements['lastName'].value);
      const email = normalizeText(wizard.elements['email'].value);
      const phone = normalizeText(wizard.elements['phone'].value);
      const privacy = wizard.elements['privacy'].checked;

      if (!first || !last || !email || !phone) {
        showToast({type:'danger', title:'Dati mancanti', message:'Compila tutti i campi obbligatori.'});
        return false;
      }
      if (!privacy) {
        showToast({type:'danger', title:'Privacy', message:'Devi accettare la Privacy Policy.'});
        return false;
      }
    }

    return true;
  }

  // ---- Device selection
  deviceCards.forEach(card => {
    card.addEventListener('click', () => {
      const device = card.dataset.device || '';
      if (!device) return;

      selectedDevice = device;
      deviceInput.value = device;

      deviceCards.forEach(c => {
        const isActive = (c === card);
        c.classList.toggle('active', isActive);
        c.setAttribute('aria-pressed', isActive ? 'true' : 'false');
      });

      loadBrandsForDevice(device);
    });
  });

  // ---- Brand/Model dynamic
  async function loadBrandsForDevice(device) {
    brandSelect.disabled = true;
    brandSelect.innerHTML = '<option value="">Caricamento marche…</option>';
    brandOtherWrap.classList.add('d-none');
    brandOtherInput.value = '';
    brandIdInput.value = '';

    // Reset model UI
    modelSelect.classList.add('d-none');
    modelSelect.innerHTML = '';
    modelInput.classList.remove('d-none'); // Default input visible
    modelInput.value = '';
    modelIdInput.value = '';
    modelOtherWrap.classList.add('d-none');
    modelOtherInput.value = '';

    if (!device) {
      brandSelect.innerHTML = '<option value="">Seleziona il dispositivo prima…</option>';
      return;
    }

    try {
      const res = await fetch('<?= url('assets/ajax/get_brands.php'); ?>?device=' + encodeURIComponent(device), {
        headers: { 'Accept': 'application/json' }
      });
      const data = await res.json();

      if (!data || !Array.isArray(data.brands)) {
        throw new Error('Risposta non valida');
      }

      brandSelect.innerHTML = '<option value="">Seleziona la marca…</option>';

      data.brands.forEach(b => {
        const opt = document.createElement('option');
        opt.value = String(b.id);
        opt.textContent = b.name;
        brandSelect.appendChild(opt);
      });

      const optOther = document.createElement('option');
      optOther.value = BRAND_OTHER_VALUE;
      optOther.textContent = 'Altro…';
      brandSelect.appendChild(optOther);

      brandSelect.disabled = false;
    } catch (err) {
      console.error(err);
      brandSelect.innerHTML = '<option value="">Errore nel caricamento marche</option>';
      showToast({type:'danger', title:'Errore', message:'Errore durante il caricamento delle marche'});
    }
  }

  brandSelect.addEventListener('change', () => {
    const val = brandSelect.value;
    const isOther = (val === BRAND_OTHER_VALUE);

    brandOtherWrap.classList.toggle('d-none', !isOther);
    if (!isOther) brandOtherInput.value = '';

    if (val && !isOther) {
      brandIdInput.value = val;
    } else {
      brandIdInput.value = '';
    }

    loadModelsForSelection();
  });

  async function loadModelsForSelection() {
    const device = selectedDevice;
    const brandId = brandSelect.value && brandSelect.value !== BRAND_OTHER_VALUE ? brandSelect.value : '';

    // Reset model UI
    modelSelect.classList.add('d-none');
    modelSelect.innerHTML = '';
    modelInput.classList.remove('d-none'); // Default to input
    modelInput.value = '';
    modelIdInput.value = '';
    modelOtherWrap.classList.add('d-none');
    modelOtherInput.value = '';

    if (!device || !brandId) {
      return;
    }

    try {
      const url = '<?= url('assets/ajax/get_models.php'); ?>'
        + '?device=' + encodeURIComponent(device)
        + '&brand_id=' + encodeURIComponent(brandId);

      const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
      const data = await res.json();

      // Se ci sono modelli, mostriamo la select e nascondiamo l'input
      if (data && Array.isArray(data.models) && data.models.length > 0) {
        modelInput.classList.add('d-none');
        modelSelect.classList.remove('d-none');

        const placeholder = document.createElement('option');
        placeholder.value = '';
        placeholder.textContent = 'Seleziona un modello…';
        modelSelect.appendChild(placeholder);

        data.models.forEach(m => {
          const opt = document.createElement('option');
          opt.value = m.name;
          if (m.id) opt.dataset.id = m.id;
          opt.textContent = m.name;
          modelSelect.appendChild(opt);
        });

        const optOther = document.createElement('option');
        optOther.value = MODEL_OTHER_VALUE;
        optOther.textContent = 'Altro modello…';
        modelSelect.appendChild(optOther);
      }
      // Altrimenti rimane l'input text (già visibile)

    } catch (err) {
      console.error(err);
      // In caso di errore, fallback su input text
      modelInput.classList.remove('d-none');
      modelSelect.classList.add('d-none');
    }
  }

  modelSelect.addEventListener('change', () => {
    const val = modelSelect.value;
    const isOther = (val === MODEL_OTHER_VALUE);

    modelOtherWrap.classList.toggle('d-none', !isOther);

    if (isOther) {
      modelInput.value = '';
      modelIdInput.value = '';
    } else if (val) {
      modelInput.value = val;
      const opt = modelSelect.options[modelSelect.selectedIndex];
      modelIdInput.value = opt && opt.dataset.id ? opt.dataset.id : '';
    } else {
      modelInput.value = '';
      modelIdInput.value = '';
    }
  });

  // ---- Condition selection (Cards)
  if (conditionGrid) {
    const condButtons = conditionGrid.querySelectorAll('.condition-card');
    condButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            const val = btn.dataset.value;
            conditionInput.value = val;
            
            condButtons.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
        });
    });
  }

  // ---- Defects selection
  if (defectGrid) {
    const defectButtons = defectGrid.querySelectorAll('.problem-card');

    defectButtons.forEach(btn => {
      btn.addEventListener('click', () => {
        const label = btn.dataset.label || btn.textContent.trim();
        if (!label) return;

        const isActive = btn.classList.toggle('active');
        if (isActive) {
          selectedDefects.add(label);
        } else {
          selectedDefects.delete(label);
        }

        if (label === 'Altro' && !isActive) {
          defectOther.value = '';
        }
      });
    });
  }

  // ---- Accessories selection (Cards)
  if (accessoryGrid) {
    const accButtons = accessoryGrid.querySelectorAll('.accessory-card');
    
    accButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            const val = btn.dataset.value;
            if (!val) return;

            // Toggle logic
            if (val === 'Nessuno') {
                if (selectedAccessories.has('Nessuno')) {
                    selectedAccessories.delete('Nessuno');
                    btn.classList.remove('active');
                } else {
                    selectedAccessories.clear();
                    selectedAccessories.add('Nessuno');
                    // Reset UI
                    accButtons.forEach(b => b.classList.remove('active'));
                    btn.classList.add('active');
                }
            } else {
                // Se seleziono altro, rimuovo Nessuno
                if (selectedAccessories.has('Nessuno')) {
                    selectedAccessories.delete('Nessuno');
                    const noneBtn = accessoryGrid.querySelector('[data-value="Nessuno"]');
                    if(noneBtn) noneBtn.classList.remove('active');
                }

                if (selectedAccessories.has(val)) {
                    selectedAccessories.delete(val);
                    btn.classList.remove('active');
                } else {
                    selectedAccessories.add(val);
                    btn.classList.add('active');
                }
            }
        });
    });
  }

  // ---- Navigation
  wizard.querySelectorAll('.ks-next').forEach(btn => {
    btn.addEventListener('click', () => {
      if (!validateStep(currentStep)) return;
      if (currentStep < 3) setStep(currentStep + 1);
    });
  });

  wizard.querySelectorAll('.ks-prev').forEach(btn => {
    btn.addEventListener('click', () => {
      if (currentStep > 1) setStep(currentStep - 1);
    });
  });

  stepButtons.forEach(btn => {
    btn.addEventListener('click', () => {
      const target = Number(btn.dataset.step || 1);
      // Permetti di tornare indietro o andare avanti solo se validato (ma qui semplifichiamo: solo indietro o step corrente)
      if (target < currentStep) {
        setStep(target);
      }
    });
  });

  // ---- Emergency scroll
  wizard.querySelectorAll('.ks-goto-emergency').forEach(btn => {
    btn.addEventListener('click', () => {
      if (!emSection) return;
      emSection.scrollIntoView({ behavior: 'smooth', block: 'center' });
    });
  });

  // ---- Submit handlers (form / whatsapp)
  const waBtn = document.getElementById('btn_whatsapp');
  const mailBtn = document.getElementById('btn_submit');
  const restartBtn = document.getElementById('btn_restart');

  async function handleSubmission(channel) {
    if (!validateStep(1) || !validateStep(2) || !validateStep(3)) return;

    contactChannelInput.value = channel;
    await saveUsedQuote(channel);
  }

  if (waBtn) waBtn.addEventListener('click', (e) => { e.preventDefault(); handleSubmission('whatsapp'); });
  if (mailBtn) mailBtn.addEventListener('click', (e) => { e.preventDefault(); handleSubmission('form'); });

  if (restartBtn) {
    restartBtn.addEventListener('click', () => {
        window.location.reload();
    });
  }

  async function saveUsedQuote(channel) {
    const fd = new FormData(wizard);

    getSelectedDefectsArray().forEach(d => fd.append('defects[]', d));
    getSelectedAccessoriesArray().forEach(a => fd.append('accessories[]', a));

    const payload = {
      device_type: selectedDevice,
      device_label: getSelectedDeviceLabel(),
      brand: getBrandDisplay(),
      model: getModelDisplay(),
      condition: getConditionLabel(),
      defects: getSelectedDefectsArray(),
      accessories: getSelectedAccessoriesArray(),
      expected_price: expectedPriceInput.value || null,
      notes: normalizeText(notesInput.value || ''),
      customer: {
        firstName: normalizeText(wizard.elements['firstName'].value),
        lastName: normalizeText(wizard.elements['lastName'].value),
        email: normalizeText(wizard.elements['email'].value),
        phone: normalizeText(wizard.elements['phone'].value)
      }
    };

    document.getElementById('wizard_payload').value = JSON.stringify(payload);
    fd.set('wizard_payload', JSON.stringify(payload));

    const btn = (channel === 'whatsapp') ? waBtn : mailBtn;
    const originalText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<i class="ri-loader-4-line ri-spin"></i> Attendere...';

    try {
      const res = await fetch('<?= url('assets/process/process_used_quote.php'); ?>', {
        method: 'POST',
        headers: {
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest'
        },
        body: fd
      });

      const data = await res.json().catch(() => null);

      if (!res.ok) {
        if (data && data.error === 'csrf') {
          showToast({type:'danger', title:'Sessione scaduta', message:'Ricarica la pagina e riprova.'});
        } else {
          showToast({type:'danger', title:'Errore', message:'Si è verificato un errore durante il salvataggio.'});
        }
        return;
      }

      if (!data || !data.ok) {
        showToast({type:'danger', title:'Errore', message: data && data.message ? data.message : 'Impossibile registrare la richiesta.'});
        return;
      }

      lastSavedId = data.id || null;
      document.getElementById('quote_id').value = lastSavedId || '';

      // Successo: vai allo step 4
      setStep(4);

      if (channel === 'whatsapp') {
        const msg = data.wa_message || buildWaMessage(payload, lastSavedId);
        const url = waLinkFromText(msg);
        window.open(url, '_blank');
        showToast({type:'success', title:'WhatsApp', message:'Apro la chat precompilata…'});
      } else {
        showToast({type:'success', title:'Inviato', message:'Richiesta registrata correttamente.'});
      }
    } catch (err) {
      console.error(err);
      showToast({type:'danger', title:'Errore', message:'Errore di rete durante il salvataggio.'});
    } finally {
        btn.disabled = false;
        btn.innerHTML = originalText;
    }
  }

  function buildWaMessage(payload, id) {
    const lines = [];
    lines.push('💬 *Nuova richiesta valutazione usato*');
    if (id) lines.push(`ID richiesta: #${id}`);
    lines.push('');
    lines.push(`• Dispositivo: ${payload.device_label || '—'}`);
    lines.push(`• Marca: ${payload.brand || '—'}`);
    lines.push(`• Modello: ${payload.model || '—'}`);
    lines.push(`• Stato: ${payload.condition || '—'}`);

    if (payload.defects && payload.defects.length) {
      lines.push(`• Problemi: ${payload.defects.join(', ')}`);
    }
    if (payload.accessories && payload.accessories.length) {
      lines.push(`• Accessori: ${payload.accessories.join(', ')}`);
    }
    if (payload.expected_price) {
      lines.push(`• Valore richiesto: € ${payload.expected_price}`);
    }

    if (payload.notes) {
      lines.push('');
      lines.push(`Note: ${payload.notes}`);
    }

    lines.push('');

    if (payload.customer) {
      const fullName = `${payload.customer.firstName || ''} ${payload.customer.lastName || ''}`.trim();
      lines.push(`📞 Cliente: ${fullName || '—'}`);
      lines.push(`Email: ${payload.customer.email || '—'}`);
      lines.push(`Telefono: ${payload.customer.phone || '—'}`);
    }

    return lines.join('\n');
  }

  // init
  setStep(1);
});
</script>

</body>
</html>
