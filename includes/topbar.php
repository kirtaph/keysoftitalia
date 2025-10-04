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
                <span class="header-info-item" aria-label="Orari di apertura">
                    <i class="ri-time-line"></i>
                    <small>Lun–Sab 9:30–13:00 / 17.30-20.30 • Gio 9:30–13:00</small>
                </span>
      </div>
    </div>
  </div>
</div>
