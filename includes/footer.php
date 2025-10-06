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
          <a href="<?php echo url('servizi/consulenza-it.php'); ?>" class="footer-link"><i class="ri-arrow-right-s-line"></i> Consulenza IT</a>
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

        <div class="opening-hours-box">
          <h4 class="footer-title text-orange">Orari di Apertura</h4>
          <div class="opening-hour-item"><span>Lun-Sab</span><span><strong>9:00-13:00 / 17:00-20.30</strong></span></div>
          <div class="opening-hour-item"><span>Giovedì</span><span><strong>9:00-13:00</strong></span></div>
          <div class="opening-hour-item"><span>Domenica</span><span class="text-red"><strong>Chiuso</strong></span></div>
        </div>

        <div class="footer-map-box">
          <i class="ri-map-pin-line" aria-hidden="true"></i>
          <div class="footer-map-title">Mappa del negozio</div>
          <!-- Embed reale di Google Maps (sostituisci src con il tuo generato) -->
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
        </div>
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
</script>
<script src="<?php echo asset('js/main.js'); ?>"></script>