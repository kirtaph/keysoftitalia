<?php
/**
 * Footer Component
 * Include questo file in tutte le pagine
 */

if (!defined('BASE_PATH')) {
    define('BASE_PATH', dirname(__DIR__) . '/');
    require_once BASE_PATH . 'config/config.php';
}
?>

<footer class="footer ks-nm-footer">
  <div class="container">
    <div class="row ks-nm-footer-row g-4 mb-5">
    <!-- Brand Column -->
    <div class="col-lg-4">
      <div class="footer-card footer-brand-card">
        <a href="<?php echo url(); ?>" class="footer-logo" aria-label="Homepage Key Soft Italia">
          <img src="<?php echo asset('img/logo.png'); ?>" alt="Key Soft Italia" class="footer-logo-img" width="40" height="40" decoding="async">
          <div class="footer-logo-text">
            <strong>Key Soft Italia</strong>
            <span class="footer-logo-subititle-text">L'universo della Tecnologia</span>
          </div>
        </a>

        <p class="footer-description">
          Il tuo partner tecnologico di fiducia a Ginosa.<br>
          Vendita, riparazioni, consulenza IT e molto altro.<br>
          Soluzioni complete per privati e aziende.
        </p>

        <div class="footer-contacts">
          <a href="<?php echo GOOGLE_MAPS_LINK; ?>" target="_blank" rel="noopener" class="footer-contact-item">
            <i class="ri-map-pin-line"></i>
            <span><?php echo COMPANY_FULL_ADDRESS; ?></span>
          </a>
          <a href="tel:<?php echo preg_replace('/\s+/', '', COMPANY_PHONE); ?>" class="footer-contact-item">
            <i class="ri-phone-line"></i>
            <span><?php echo COMPANY_PHONE; ?></span>
          </a>
          <a href="mailto:<?php echo COMPANY_EMAIL; ?>" class="footer-contact-item">
            <i class="ri-mail-line"></i>
            <span><?php echo COMPANY_EMAIL; ?></span>
          </a>
          <a href="<?php echo whatsapp_link('Ciao Key Soft Italia!'); ?>" target="_blank" rel="noopener" class="footer-contact-item" data-whatsapp="footer">
            <i class="ri-whatsapp-line"></i>
            <span>WhatsApp: <?php echo COMPANY_WHATSAPP; ?></span>
          </a>
        </div>
      </div>
    </div>
    <!-- Useful Links -->
    <div class="col-lg-4 col-md-6">
      <div class="footer-card">
        <h4 class="footer-title">Link Utili</h4>
        <div class="footer-links-grid">
          <a href="<?php echo url('servizi.php'); ?>" class="footer-link"><i class="ri-arrow-right-s-line"></i> Tutti i Servizi</a>
          <a href="<?php echo url('servizi/riparazioni.php'); ?>" class="footer-link"><i class="ri-arrow-right-s-line"></i> Riparazioni & Assistenza</a>
          <a href="<?php echo url('prodotti.php'); ?>" class="footer-link"><i class="ri-arrow-right-s-line"></i> Dispositivi Ricondizionati</a>
          <a href="<?php echo url('volantini.php'); ?>" class="footer-link"><i class="ri-arrow-right-s-line"></i> I Nostri Volantini</a>
          <a href="<?php echo url('preventivo.php'); ?>" class="footer-link"><i class="ri-arrow-right-s-line"></i> Richiedi Preventivo</a>
          <a href="<?php echo url('valuta-usato.php'); ?>" class="footer-link"><i class="ri-arrow-right-s-line"></i> Vendi il tuo Usato</a>
          <a href="<?php echo url('assistenza.php'); ?>" class="footer-link"><i class="ri-arrow-right-s-line"></i> Richiedi Assistenza</a>
          <a href="<?php echo url('contatti.php#faq'); ?>" class="footer-link"><i class="ri-arrow-right-s-line"></i> Domande Frequenti</a>
        </div>
      </div>
    </div>
    <!-- Right Column: Social + Orari -->
    <div class="col-lg-4 col-md-6">
      <div class="footer-card footer-info-card">
        <h4 class="footer-title">Seguici</h4>
        <div class="social-links ks-nm-social">
          <a href="<?php echo FACEBOOK_URL; ?>" target="_blank" rel="noopener" class="social-link nm-social-link" data-bs-toggle="tooltip" title="Seguici su Facebook" aria-label="Facebook"><i class="ri-facebook-fill"></i></a>
          <a href="<?php echo INSTAGRAM_URL; ?>" target="_blank" rel="noopener" class="social-link nm-social-link" data-bs-toggle="tooltip" title="Seguici su Instagram" aria-label="Instagram"><i class="ri-instagram-line"></i></a>
          <a href="<?php echo YOUTUBE_URL; ?>" target="_blank" rel="noopener" class="social-link nm-social-link" data-bs-toggle="tooltip" title="Iscriviti al canale YouTube" aria-label="YouTube"><i class="ri-youtube-fill"></i></a>
          <a href="<?php echo TIKTOK_URL; ?>" target="_blank" rel="noopener" class="social-link nm-social-link" data-bs-toggle="tooltip" title="Seguici su TikTok" aria-label="TikTok"><i class="ri-tiktok-fill"></i></a>
        </div>
        <div class="opening-hours-section">
        <div class="footer-hours fo-box">
  <h4 class="footer-title d-flex align-items-center gap-2 mb-2">
    <i class="ri-time-line" aria-hidden="true"></i> Orari di Apertura
  </h4>

  <?php
    $tz   = new DateTimeZone(KS_TZ);
    $now  = new DateTime('now', $tz);

    $state   = ks_is_open_now($now);
    $open    = $state['open'];
    $chipCls = $open ? 'fo-chip fo-chip--open' : 'fo-chip fo-chip--closed';
    $chipIco = $open ? 'ri-checkbox-circle-line' : 'ri-close-circle-line';
    $chipTxt = $open ? 'Aperti' : 'Chiusi';

    if ($open) {
      $note = 'Chiude alle '.$state['end']->format('H:i').' (tra '.ks_human_diff($now, $state['end']).')';
    } else {
      $nxt  = ks_next_open_after($now);
      $note = $nxt
        ? 'Riapre '.($nxt->format('Ymd')===$now->format('Ymd') ? 'alle '.$nxt->format('H:i') : ks_day_label((int)$nxt->format('N')).' alle '.$nxt->format('H:i'))
          .' (tra '.ks_human_diff($now, $nxt).')'
        : 'Chiuso';
    }

    // Avviso speciale (centralizzato)
    $todayNotice = ks_hours_notice_for_date($now);

    // Raggruppa giorni con identici intervalli (settimana corrente, incluse eccezioni)
    if (!function_exists('fo_group_by_intervals')) {
        function fo_group_by_intervals(array $calculatedWeek): array {
          $groups = [];
          for ($d=1; $d<=7; $d++) {
            $ints = $calculatedWeek[$d] ?? [];
            $key  = json_encode($ints);
            if (!isset($groups[$key])) $groups[$key] = ['days'=>[], 'intervals'=>$ints];
            $groups[$key]['days'][] = $d;
          }
          return array_values($groups);
        }
    }
    if (!function_exists('fo_days_compact')) {
        function fo_days_compact(array $days): string {
          $abbr = [1=>'Lun',2=>'Mar',3=>'Mer',4=>'Gio',5=>'Ven',6=>'Sab',7=>'Dom'];
          sort($days);
          $ranges = [];
          $s = $p = null;
          foreach ($days as $d) {
            if ($s===null){ $s=$p=$d; continue; }
            if ($d===$p+1){ $p=$d; continue; }
            $ranges[] = [$s,$p]; $s=$p=$d;
          }
          $ranges[] = [$s,$p];
          return implode(', ', array_map(fn($r) => $r[0]===$r[1] ? $abbr[$r[0]] : $abbr[$r[0]].'–'.$abbr[$r[1]], $ranges));
        }
    }

    // Calcola intervalli reali per la settimana corrente
    $currentWeek = [];
    for ($d=1; $d<=7; $d++) {
        $date = ks_date_for_iso_dow($now, $d);
        $currentWeek[$d] = ks_intervals_for_date($date);
    }

    $groups = fo_group_by_intervals($currentWeek);
    $todayN = (int)$now->format('N');           // per evidenziare oggi

    // Info compatta tooltip
    $todayIntervals = $currentWeek[$todayN] ?? [];
    $todayShort = empty($todayIntervals) ? 'Oggi: chiuso' : 'Oggi: '.ks_format_intervals($todayIntervals);
  ?>

    <div class="fo-status mb-2">
      <span class="<?= $chipCls; ?>" data-bs-toggle="tooltip" title="<?= htmlspecialchars(strip_tags($todayShort), ENT_QUOTES, 'UTF-8'); ?>">
        <i class="<?= $chipIco; ?>" aria-hidden="true"></i> <?= $chipTxt; ?>
      </span>
      <small class="fo-note"><?= $note; ?></small>
      <?php if (!empty($todayNotice)): ?>
        <small class="fo-special" data-bs-toggle="tooltip" title="<?= htmlspecialchars($todayNotice, ENT_QUOTES, 'UTF-8'); ?>">
          <i class="ri-megaphone-line" aria-hidden="true"></i>
        </small>
      <?php endif; ?>
    </div>

  <div class="fo-list">
    <?php foreach ($groups as $g):
      $label = fo_days_compact($g['days']);
      $isTodayGroup = in_array($todayN, $g['days'], true);
      $cls = $isTodayGroup ? 'opening-hour-item fo-item is-today' : 'opening-hour-item fo-item';
      $time = empty($g['intervals']) ? '<span class="text-red"><strong>Chiuso</strong></span>' : ks_format_intervals($g['intervals']);
    ?>
      <div class="<?= $cls; ?>">
        <span><?= $label; ?></span>
        <span><strong><?= $time; ?></strong></span>
      </div>
    <?php endforeach; ?>
  </div>
</div>

      </div>
    </div>
  </div>
    </div> <!-- Close Row -->
    
    <div class="footer-bottom">
      <div class="footer-copyright">
        © <?php echo date('Y'); ?> Key Soft Italia. Tutti i diritti riservati.
      </div>
      <div class="footer-bottom-links">
        <a href="<?php echo url('privacy.php?tab=privacy'); ?>" class="footer-bottom-link">Privacy Policy</a>
        <a href="<?php echo url('privacy.php?tab=terms'); ?>" class="footer-bottom-link">Termini di Servizio</a>
        <a href="<?php echo url('privacy.php?tab=cookies'); ?>" class="footer-bottom-link">Cookie Policy</a>
        <a href="#" id="open-consent-manager" class="footer-bottom-link">Preferenze cookie</a>
        <a href="<?php echo url('admin'); ?>" class="footer-bottom-link">Amministrazione</a>
      </div>
    </div>
  </div> <!-- Close Container -->
</footer>
<!-- Sticky WhatsApp Button -->
<a href="<?php echo whatsapp_link('Ciao Key Soft Italia, ho bisogno di assistenza!'); ?>"
   target="_blank"
   class="whatsapp-sticky"
   aria-label="Contattaci su WhatsApp">
  <i class="ri-whatsapp-line"></i>
  <span class="whatsapp-tooltip">Hai bisogno di aiuto?</span>
</a>

<!-- Back to Top Button -->
<button id="backToTop" class="back-to-top" aria-label="Torna su">
  <i class="ri-arrow-up-line"></i>
</button>

<!-- Privacy Modal (Bootstrap) -->
<div class="modal fade" id="privacyModal" tabindex="-1" aria-labelledby="privacyModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 rounded-3">
      <div class="modal-header border-0">
        <h5 class="modal-title" id="privacyModalLabel">
          <i class="ri-shield-check-line me-1"></i> Consenso Privacy
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Chiudi"></button>
      </div>
      <div class="modal-body">
        Per inviare la richiesta è necessario accettare la <a href="<?= url('privacy.php'); ?>" target="_blank" rel="noopener">Privacy Policy</a>.
        <div class="form-check mt-3">
          <input class="form-check-input" type="checkbox" id="privacyModalCheck">
          <label class="form-check-label" for="privacyModalCheck">
            Ho letto e accetto la Privacy Policy.
          </label>
        </div>
      </div>
      <div class="modal-footer border-0">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annulla</button>
        <button type="button" class="btn btn-primary" id="privacyModalAccept">
          Accetta e continua
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Toasts -->
<div id="ks-toast-container" class="toast-container position-fixed p-3" aria-live="polite" aria-atomic="true"></div>

<?php
  // opzionale: path della privacy
  if (!defined('PRIVACY_URL')) {
    define('PRIVACY_URL', url('privacy.php')); // cambia se diverso
  }
?>

<!-- Cookie Banner (Bootstrap 5 - Premium Dark Glassmorphism) -->
<div id="cookie-banner"
     class="position-fixed bottom-0 start-50 translate-middle-x z-3"
     style="display:none; max-width: 920px; width: calc(100% - 1.5rem); margin-bottom: 1.5rem;">
  <div class="card shadow-lg border-0" style="border-radius: 16px; background: rgba(30, 35, 48, 0.95); backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.08) !important;">
    <div class="card-body p-3 p-md-4 text-white">
      <div class="d-flex flex-column flex-md-row align-items-md-center gap-3">
        <div class="flex-grow-1">
          <strong class="d-block mb-1 text-white" style="font-family: var(--ks-font-heading); font-weight: 600;">
            <i class="ri-shield-user-line me-2 text-warning" style="vertical-align: middle; font-size: 1.1rem;"></i>Questo sito usa solo statistiche anonime (GA4 con Consent Mode)
          </strong>
          <small style="color: rgba(255, 255, 255, 0.7); font-size: 13px;">
            Per migliorare i contenuti usiamo Google Analytics in modalità rispettosa della privacy.
            Puoi accettare o rifiutare le statistiche anonime. Nessuna pubblicità personalizzata.
            <a href="<?php echo PRIVACY_URL; ?>" class="text-warning text-decoration-none fw-semibold">Leggi la Privacy Policy</a>.
          </small>
        </div>
        <div class="d-flex align-items-center gap-2 ms-md-3">
          <button id="btn-cookie-decline" type="button" class="btn btn-outline-light btn-sm px-3" style="border-radius: 8px; font-size: 13px; font-weight: 500;">
            Rifiuta
          </button>
          <button id="btn-cookie-accept" type="button" class="btn btn-sm px-3 text-white" style="background: var(--ks-orange); border: none; border-radius: 8px; font-size: 13px; font-weight: 600; transition: all 0.2s;">
            Accetta
          </button>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
// === Cookie/Consent Banner – Key Soft Italia ===
// Requisiti: gtag + Consent Mode v2 con default denied già presente nel <head>

(function(){
  const STORAGE_KEY = 'ksoft_consent_v2';
  const RETENTION_DAYS = 180; // 6 mesi
  const bannerEl  = document.getElementById('cookie-banner');
  const acceptBtn = document.getElementById('btn-cookie-accept');
  const declineBtn= document.getElementById('btn-cookie-decline');
  const reopenLink= document.getElementById('open-consent-manager');

  // Utility
  function now(){ return Date.now(); }
  function days(n){ return n * 24 * 60 * 60 * 1000; }

  function readConsent(){
    try {
      const raw = localStorage.getItem(STORAGE_KEY);
      return raw ? JSON.parse(raw) : null;
    } catch(e){ return null; }
  }

  function persistConsent(state){
    const payload = { state, ts: now() };
    try { localStorage.setItem(STORAGE_KEY, JSON.stringify(payload)); } catch(e){}
  }

  // Aggiorna Google Consent Mode (gtag)
  function updateConsentTo(state){
    // state: 'granted' | 'denied'
    // Manteniamo SEMPRE negato l'advertising (niente pubblicità personalizzata)
    try {
      if (typeof gtag === 'function') {
        gtag('consent', 'update', {
          ad_storage: 'denied',
          ad_user_data: 'denied',
          ad_personalization: 'denied',
          analytics_storage: state,
          functionality_storage: 'granted',
          security_storage: 'granted'
        });
      }
    } catch(e){}
  }

  function showBanner(){ bannerEl.style.display = 'block'; }
  function hideBanner(){ bannerEl.style.display = 'none'; }

  function accept(){
    updateConsentTo('granted');
    persistConsent('granted');
    hideBanner();
  }

  function decline(){
    updateConsentTo('denied');
    persistConsent('denied');
    hideBanner();
  }

  // Riapertura manuale
  function reopen(){
    showBanner();
  }

  // Init su DOM pronto
  document.addEventListener('DOMContentLoaded', function(){
    // Posizionamento: centrato in basso, con margini
    bannerEl.style.left = '50%';
    bannerEl.style.bottom = '0.75rem';

    const saved = readConsent();
    if (!saved) {
      showBanner();
    } else {
      const expired = (now() - (saved.ts || 0)) > days(RETENTION_DAYS);
      if (expired) {
        showBanner();
      } else {
        // Rispetta la scelta precedente
        updateConsentTo(saved.state === 'granted' ? 'granted' : 'denied');
      }
    }
  });

  // Bind bottoni
  acceptBtn?.addEventListener('click', accept);
  declineBtn?.addEventListener('click', decline);
  reopenLink?.addEventListener('click', function(e){ e.preventDefault(); reopen(); });

  // Espone un metodo globale opzionale
  window.showConsentManager = reopen;
})();
</script>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const backToTop = document.getElementById('backToTop');

    window.addEventListener('scroll', function() {
      if (window.scrollY > 300) {
        backToTop.classList.add('visible');
      } else {
        backToTop.classList.remove('visible');
      }
    });

    backToTop.addEventListener('click', function() {
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });
  });

  // ===== Toast Utility =====
function showToast({title='', message='', type='info', delay=4500} = {}){
  const cont = document.getElementById('ks-toast-container');
  if (!cont) return;

  const id = 'toast-'+Date.now();
  const colorClass = type === 'success' ? 'border-success'
                    : type === 'danger'  ? 'border-danger'
                    : 'border-info';

  const el = document.createElement('div');
  el.className = `toast align-items-center shadow ${colorClass}`;
  el.id = id;
  el.role = 'alert'; el.ariaLive = 'assertive'; el.ariaAtomic = 'true';
  el.innerHTML = `
    <div class="toast-header">
      <span class="toast-title me-auto">
        ${type==='success' ? '✅' : (type==='danger' ? '❌' : 'ℹ️')} ${title}
      </span>
      <small class="text-muted">ora</small>
      <button type="button" class="btn-close ms-2 mb-1" data-bs-dismiss="toast" aria-label="Chiudi"></button>
    </div>
    <div class="toast-body">${message}</div>
  `;
  cont.appendChild(el);

  const bsToast = new bootstrap.Toast(el, { delay, autohide: true });
  bsToast.show();

  // rimuovi dal DOM quando chiuso
  el.addEventListener('hidden.bs.toast', ()=> el.remove());
}

    function getHeaderOffset() {
        const hdr = document.querySelector('.site-header, header.sticky-top, header.navbar, .main-header');
        return hdr ? (hdr.getBoundingClientRect().height + 16) : 96;
    }

    function smoothScrollTo(el) {
        if (!el) return;
        const y = el.getBoundingClientRect().top + window.pageYOffset - getHeaderOffset();
        window.scrollTo({ top: y, behavior: 'smooth' });
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo asset_version('js/main.js'); ?>"></script>
