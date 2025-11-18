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

<footer class="footer">
  <div class="container">
    <div class="footer-content">
      
      <!-- Brand Column -->
      <div class="footer-brand">
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
            <i class="ri-map-pin-line icon-orange"></i>
            <span><?php echo COMPANY_FULL_ADDRESS; ?></span>
          </a>
          <a href="tel:<?php echo preg_replace('/\s+/', '', COMPANY_PHONE); ?>" class="footer-contact-item">
            <i class="ri-phone-line icon-orange"></i>
            <span><?php echo COMPANY_PHONE; ?></span>
          </a>
          <a href="mailto:<?php echo COMPANY_EMAIL; ?>" class="footer-contact-item">
            <i class="ri-mail-line icon-orange"></i>
            <span><?php echo COMPANY_EMAIL; ?></span>
          </a>
          <a href="<?php echo whatsapp_link('Ciao Key Soft Italia!'); ?>" target="_blank" rel="noopener" class="footer-contact-item" data-whatsapp="footer">
            <i class="ri-whatsapp-line icon-whatsapp"></i>
            <span>WhatsApp: <?php echo COMPANY_WHATSAPP; ?></span>
          </a>
        </div>
      </div>

      <!-- Useful Links -->
      <div class="footer-column footer-links">
        <h4 class="footer-title footer-links">Link Utili</h4>
        <div class="footer-links">
          <a href="<?php echo url('servizi.php'); ?>" class="footer-link"><i class="ri-arrow-right-s-line"></i> Tutti i Servizi</a>
          <a href="<?php echo url('servizi/riparazioni.php'); ?>" class="footer-link"><i class="ri-arrow-right-s-line"></i> Riparazioni & Assistenza</a>
          <a href="<?php echo url('ricondizionati.php'); ?>" class="footer-link"><i class="ri-arrow-right-s-line"></i> Dispositivi Ricondizionati</a>
          <a href="<?php echo url('volantini.php'); ?>" class="footer-link"><i class="ri-arrow-right-s-line"></i> I Nostri Volantini</a>
          <a href="<?php echo url('preventivo.php'); ?>" class="footer-link"><i class="ri-arrow-right-s-line"></i> Richiedi Preventivo</a>
          <a href="<?php echo url('usato.php'); ?>" class="footer-link"><i class="ri-arrow-right-s-line"></i> Vendi il tuo Usato</a>
          <a href="<?php echo url('assistenza.php'); ?>" class="footer-link"><i class="ri-arrow-right-s-line"></i> Richiedi Assistenza</a>
          <a href="<?php echo url('faq.php'); ?>" class="footer-link"><i class="ri-arrow-right-s-line"></i> Domande Frequenti</a>
        </div>
      </div>

      <!-- Right Column: Social + Orari + Mappa -->
      <div class="footer-column">
        <h4 class="footer-title">Seguici</h4>
        <div class="social-links">
          <a href="<?php echo FACEBOOK_URL; ?>" target="_blank" rel="noopener" class="social-link social-facebook" aria-label="Facebook"><i class="ri-facebook-fill"></i></a>
          <a href="<?php echo INSTAGRAM_URL; ?>" target="_blank" rel="noopener" class="social-link social-instagram" aria-label="Instagram"><i class="ri-instagram-line"></i></a>
          <a href="<?php echo YOUTUBE_URL; ?>" target="_blank" rel="noopener" class="social-link social-youtube" aria-label="YouTube"><i class="ri-youtube-fill"></i></a>
          <a href="<?php echo TIKTOK_URL; ?>" target="_blank" rel="noopener" class="social-link social-tiktok" aria-label="TikTok"><i class="ri-tiktok-fill"></i></a>
        </div>
<br>
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

    // Avviso speciale (da config)
    $todayKey = $now->format('Y-m-d');
    $todayNotice = null;

if (function_exists('ks_hours_notice_for_date')) {
  $todayNotice = ks_hours_notice_for_date($now);
} else {
  // fallback slim se non hai la funzione aggregata
  if (function_exists('ks_db_date_exception')) {
    $exc = ks_db_date_exception($now->format('Y-m-d'));
    if (!empty($exc['found']) && !empty($exc['notice'])) {
      $todayNotice = trim((string)$exc['notice']);
    }
  }
  if (!$todayNotice && function_exists('ks_holiday_rule_for_date')) {
    $hol = ks_holiday_rule_for_date($now);
    if ($hol && !empty($hol['notice'])) {
      $todayNotice = trim((string)$hol['notice']);
    }
  }
  if (!$todayNotice) {
    $map = ks_store_hours_notices();
    $todayNotice = $map[$now->format('Y-m-d')] ?? null;
  }
}

    // Raggruppa giorni con identici intervalli (base settimanale)
    function fo_group_by_intervals(array $base): array {
      $groups = [];
      for ($d=1; $d<=7; $d++) {
        $ints = $base[$d] ?? [];
        $key  = json_encode($ints);
        if (!isset($groups[$key])) $groups[$key] = ['days'=>[], 'intervals'=>$ints];
        $groups[$key]['days'][] = $d;
      }
      return array_values($groups);
    }
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

    $base   = ks_store_hours_base();            // schema standard
    $groups = fo_group_by_intervals($base);     // righe compatte
    $todayN = (int)$now->format('N');           // per evidenziare oggi
  ?>

  <div class="fo-status mb-2">
    <span class="<?= $chipCls; ?>"><i class="<?= $chipIco; ?>" aria-hidden="true"></i> <?= $chipTxt; ?></span>
    <small class="fo-note"><?= $note; ?></small>
    <?php if (!empty($todayNotice)): ?>
      <small class="fo-special" title="<?= htmlspecialchars($todayNotice, ENT_QUOTES, 'UTF-8'); ?>">
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

        <!-- <div class="footer-map-box">
          <i class="ri-map-pin-line" aria-hidden="true"></i>
          <div class="footer-map-title">Mappa del negozio</div>

          <iframe 
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d7189.563886109397!2d16.752903976408206!3d40.57454714601821!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x134770f123f4ba59%3A0x8e9307ff05e9cee0!2sKey%20Soft%20Italia!5e1!3m2!1sit!2sit!4v1759516864357!5m2!1sit!2sit"
            width="100%" 
            height="200" 
            style="border:0;" 
            allowfullscreen="" 
            loading="lazy" 
            referrerpolicy="no-referrer-when-downgrade"
            title="Mappa di Key Soft Italia a Ginosa (TA)"
            aria-label="Mappa interattiva di Key Soft Italia a Ginosa (TA)">
          </iframe>
          <a href="<?php echo GOOGLE_MAPS_LINK; ?>" target="_blank" rel="noopener" class="footer-map-link">Visualizza su Google Maps</a>
        </div> -->
      </div>
    </div>

    <!-- Bottom -->
    <div class="footer-bottom">
      <div class="footer-copyright">
        © <?php echo date('Y'); ?> Key Soft Italia. Tutti i diritti riservati.
      </div>
      <div class="footer-bottom-links">
        <a href="<?php echo url('privacy.php'); ?>" class="footer-bottom-link">Privacy Policy</a>
        <a href="<?php echo url('termini.php'); ?>" class="footer-bottom-link">Termini di Servizio</a>
        <a href="<?php echo url('cookie.php'); ?>" class="footer-bottom-link">Cookie Policy</a>
        <a href="<?php echo url('admin'); ?>" class="footer-bottom-link">Amministrazione</a>
      </div>
    </div>
  </div>
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

<!-- Cookie Banner (Bootstrap 5) -->
<div id="cookie-banner"
     class="position-fixed bottom-0 start-50 translate-middle-x z-3"
     style="display:none; max-width: 920px; width: calc(100% - 1.5rem);">
  <div class="card shadow-lg border-0" style="border-radius: 16px;">
    <div class="card-body p-3 p-md-4">
      <div class="d-flex flex-column flex-md-row align-items-md-center gap-3">
        <div class="flex-grow-1">
          <strong class="d-block mb-1">Questo sito usa solo statistiche anonime (GA4 con Consent Mode)</strong>
          <small class="text-muted">
            Per migliorare i contenuti usiamo Google Analytics in modalità rispettosa della privacy.
            Puoi accettare o rifiutare le statistiche anonime. Nessuna pubblicità personalizzata.
            <a href="<?php echo PRIVACY_URL; ?>" class="link-primary">Leggi la Privacy Policy</a>.
          </small>
        </div>
        <div class="d-flex align-items-center gap-2 ms-md-3">
          <button id="btn-cookie-decline" type="button" class="btn btn-outline-secondary btn-sm">
            Rifiuta
          </button>
          <button id="btn-cookie-accept" type="button" class="btn btn-primary btn-sm">
            Accetta
          </button>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Link per riaprire le preferenze (mettilo nel tuo footer dove vuoi) -->
<div class="text-center mt-2">
  <a href="#" id="open-consent-manager" class="small text-muted">Preferenze cookie</a>
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
<script src="<?php echo asset('js/main.js'); ?>"></script>