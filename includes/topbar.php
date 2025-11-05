<?php
// Top bar contatti/orari
?>
<div class="header-top">
  <div class="container">
    <div class="header-top-content">
      <div class="header-contacts">
                <a href="tel:<?php echo str_replace(' ', '', COMPANY_PHONE); ?>" class="header-contact-item" aria-label="Chiama <?php echo COMPANY_PHONE; ?>">
                    <i class="ri-phone-line"></i>
                    <span><?php echo COMPANY_PHONE; ?></span>
                </a>
                <a href="<?php echo whatsapp_link('Ciao Key Soft Italia, ho bisogno di assistenza'); ?>"
                   class="header-contact-item"
                   target="_blank"
                   rel="noopener"
                   aria-label="Scrivici su WhatsApp"
                   data-whatsapp="header">
                    <i class="ri-whatsapp-line"></i>
                    <span>WhatsApp</span>
                </a>
                <a href="mailto:<?php echo COMPANY_EMAIL; ?>" class="header-contact-item" aria-label="Scrivi a <?php echo COMPANY_EMAIL; ?>">
                    <i class="ri-mail-line"></i>
                    <span><?php echo COMPANY_EMAIL; ?></span>
                </a>
      </div>
<div class="header-info">
  <span class="header-info-item top-hours" aria-label="Stato orari e prossima chiusura/apertura">
    
    <?php
      // Usa helpers globali già caricati da functions.php
      $tz   = new DateTimeZone(KS_TZ);
      $now  = new DateTime('now', $tz);

      $state   = ks_is_open_now($now);
      $open    = $state['open'];
      $chipCls = $open ? 'top-chip top-chip--open' : 'top-chip top-chip--closed';
      $chipIco = $open ? 'ri-checkbox-circle-line' : 'ri-close-circle-line';
      $chipTxt = $open ? 'Aperti' : 'Chiusi';

      if ($open) {
        $note = 'Chiude alle '.$state['end']->format('H:i').' (tra '.ks_human_diff($now, $state['end']).')';
      } else {
        $nxt  = ks_next_open_after($now);
        $note = $nxt
          ? 'Riapre '.($nxt->format('Ymd') === $now->format('Ymd')
              ? 'alle '.$nxt->format('H:i')
              : ks_day_label((int)$nxt->format('N')).' alle '.$nxt->format('H:i'))
            .' (tra '.ks_human_diff($now, $nxt).')'
          : 'Chiuso';
      }

      // Info compatta "Oggi: 09:00-13:00 / 17:00-20:30" come tooltip
      $todayIntervals = ks_intervals_for_date($now);
      $todayShort = empty($todayIntervals) ? 'Oggi: chiuso' : 'Oggi: '.ks_format_intervals($todayIntervals);

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
    ?>

    <span class="<?= $chipCls; ?>">
      <i class="<?= $chipIco; ?>" aria-hidden="true"></i> <?= $chipTxt; ?>
    </span>

    <small class="top-hours-note" title="<?= htmlspecialchars(strip_tags($todayShort), ENT_QUOTES, 'UTF-8'); ?>">
      <?= $note; ?>
    </small>

    <?php if (!empty($todayNotice)): ?>
      <span class="top-hours-special" title="<?= htmlspecialchars($todayNotice, ENT_QUOTES, 'UTF-8'); ?>">
        <i class="ri-megaphone-line" aria-hidden="true"></i>
      </span>
    <?php endif; ?>
  </span>
</div>
    </div>
  </div>
</div>
