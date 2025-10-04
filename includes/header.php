<?php
/**
 * Header Component
 */
if (!defined('BASE_PATH')) {
  define('BASE_PATH', dirname(__DIR__) . '/');
  require_once BASE_PATH . 'config/config.php';
}
$current_page = get_current_page();
?>

<?php include_partial('topbar.php'); ?>

<header class="header">
  <div class="header-main">
    <div class="container">
      <div class="header-main-content">
      <!-- Logo -->
      <a href="<?php echo url(); ?>" class="logo">
        <div class="logo-mark">
          <img src="<?php echo asset('img/logo.png'); ?>" alt="Key Soft Italia" class="logo-img" width="48" height="48" decoding="async">
        </div>
        <div class="logo-text">
          <div class="logo-title">Key Soft Italia</div>
          <div class="logo-subtitle">L'universo della Tecnologia</div>
        </div>
      </a>

        <!-- Nav desktop -->
        <nav class="nav-main" aria-label="Navigazione principale">
          <ul class="nav-menu">
            <li class="nav-item">
              <a href="<?php echo url('chi-siamo.php'); ?>" class="nav-link <?php echo $current_page == 'chi-siamo' ? 'active' : ''; ?>">
                <i class="ri-team-line"></i> Chi Siamo
              </a>
            </li>

            <li class="nav-item">
              <a href="<?php echo url('servizi.php'); ?>" class="nav-link <?php echo $current_page == 'servizi' ? 'active' : ''; ?>">
                <i class="ri-service-line"></i> Servizi <i class="ri-arrow-down-s-line"></i>
              </a>
              <div class="nav-dropdown" role="menu">
                <a href="<?php echo url('servizi/riparazioni.php'); ?>" class="nav-dropdown-item"><i class="ri-tools-line"></i> Riparazioni & Assistenza</a>
                <a href="<?php echo url('servizi/vendita.php'); ?>" class="nav-dropdown-item"><i class="ri-shopping-bag-line"></i> Vendita al Dettaglio</a>
                <a href="<?php echo url('servizi/telefonia.php'); ?>" class="nav-dropdown-item"><i class="ri-sim-card-line"></i> Telefonia & Servizi Casa</a>
                <a href="<?php echo url('servizi/sviluppo-web.php'); ?>" class="nav-dropdown-item"><i class="ri-code-line"></i> Sviluppo Web & App</a>
                <a href="<?php echo url('servizi/consulenza-it.php'); ?>" class="nav-dropdown-item"><i class="ri-shield-check-line"></i> Consulenza IT & Reti</a>
              </div>
            </li>

            <li class="nav-item"><a href="<?php echo url('ricondizionati.php'); ?>" class="nav-link <?php echo $current_page == 'ricondizionati' ? 'active' : ''; ?>"><i class="ri-smartphone-line"></i> Ricondizionati</a></li>
            <li class="nav-item"><a href="<?php echo url('video.php'); ?>" class="nav-link <?php echo $current_page == 'video' ? 'active' : ''; ?>"><i class="ri-play-circle-line"></i> Video</a></li>
            <li class="nav-item"><a href="<?php echo url('contatti.php'); ?>" class="nav-link <?php echo $current_page == 'contatti' ? 'active' : ''; ?>"><i class="ri-map-pin-line"></i> Contatti</a></li>
          </ul>

          <!-- CTA -->
          <div class="nav-actions">
            <a href="<?php echo url('assistenza.php'); ?>" class="nav-cta nav-cta-assistenza"><i class="ri-customer-service-line"></i> Assistenza</a>
            <a href="<?php echo url('preventivo.php'); ?>" class="nav-cta nav-cta-preventivo"><i class="ri-file-list-3-line"></i> Preventivo</a>
          </div>
        </nav>

        <!-- Toggle mobile -->
<button class="menu-toggle" aria-label="Apri menu" aria-expanded="false" aria-controls="ksOffcanvasNav">
  <span></span><span></span><span></span>
</button>
      </div>
    </div>
  </div>
</header>

<!-- Offcanvas mobile -->
<div class="ks-offcanvas-backdrop" id="ksOffcanvasBackdrop"></div>
<div class="ks-offcanvas" id="ksOffcanvasNav" role="dialog" aria-modal="true" aria-hidden="true">

  <!-- Header -->
  <div class="offcanvas-header">
    <div class="logo">
      <div class="logo-mark">
        <img src="<?php echo asset('img/logo.png'); ?>" alt="Key Soft Italia" class="logo-img" width="40" height="40">
      </div>
      <div class="logo-text">
        <div class="logo-title">Key Soft Italia</div>
      </div>
    </div>
    <button class="offcanvas-close" aria-label="Chiudi menu">
      <i class="ri-close-line"></i>
    </button>
  </div>

  <!-- Body -->
  <div class="offcanvas-body">
    <ul class="mobile-nav">
      <li class="mobile-nav-item">
        <a href="<?php echo url(''); ?>" class="mobile-nav-link">
          <i class="ri-home-4-line"></i> Home
        </a>
      </li>

      <li class="mobile-nav-item">
        <a href="<?php echo url('chi-siamo.php'); ?>" class="mobile-nav-link">
          <i class="ri-user-3-line"></i> Chi Siamo
        </a>
      </li>

      <li class="mobile-nav-item">
        <a href="<?php echo url('servizi.php'); ?>" class="mobile-nav-link">
          <i class="ri-grid-line"></i> Servizi
        </a>
        <div class="mobile-nav-dropdown">
          <a href="<?php echo url('servizi/riparazioni.php'); ?>" class="mobile-nav-dropdown-item">
            <i class="ri-tools-line"></i> Riparazioni
          </a>
          <a href="<?php echo url('servizi/vendita.php'); ?>" class="mobile-nav-dropdown-item">
            <i class="ri-shopping-bag-line"></i> Vendita
          </a>
          <a href="<?php echo url('servizi/telefonia.php'); ?>" class="mobile-nav-dropdown-item">
            <i class="ri-sim-card-line"></i> Telefonia
          </a>
          <a href="<?php echo url('servizi/sviluppo-web.php'); ?>" class="mobile-nav-dropdown-item">
            <i class="ri-code-line"></i> Sviluppo Web
          </a>
          <a href="<?php echo url('servizi/consulenza-it.php'); ?>" class="mobile-nav-dropdown-item">
            <i class="ri-shield-check-line"></i> Consulenza IT
          </a>
        </div>
      </li>

      <li class="mobile-nav-item">
        <a href="<?php echo url('ricondizionati.php'); ?>" class="mobile-nav-link">
          <i class="ri-smartphone-line"></i> Ricondizionati
        </a>
      </li>

      <li class="mobile-nav-item">
        <a href="<?php echo url('video.php'); ?>" class="mobile-nav-link">
          <i class="ri-video-line"></i> Video
        </a>
      </li>

      <li class="mobile-nav-item">
        <a href="<?php echo url('contatti.php'); ?>" class="mobile-nav-link">
          <i class="ri-contacts-book-2-line"></i> Contatti
        </a>
      </li>
    </ul>

    <!-- CTA principali -->
    <div class="mobile-nav-actions">
      <a href="<?php echo url('assistenza.php'); ?>" class="mobile-nav-cta mobile-nav-cta-assistenza">
        <i class="ri-customer-service-line"></i> Assistenza
      </a>
      <a href="<?php echo url('preventivo.php'); ?>" class="mobile-nav-cta mobile-nav-cta-preventivo">
        <i class="ri-file-list-3-line"></i> Preventivo
      </a>
    </div>

    <!-- Contatti -->
    <div class="offcanvas-contacts" style="margin-top: var(--ks-spacing-6); display:flex; flex-direction:row; justify-content:center; gap:15px;">
      <a href="tel:<?php echo preg_replace('/\s+/', '', COMPANY_PHONE); ?>" class="contact-btn" aria-label="Chiama">
        <i class="ri-phone-line"></i>
      </a>
      <a href="<?php echo whatsapp_link('Ciao Key Soft Italia!'); ?>" class="contact-btn" target="_blank" rel="noopener" aria-label="WhatsApp">
        <i class="ri-whatsapp-line"></i>
      </a>
      <a href="mailto:<?php echo COMPANY_EMAIL; ?>" class="contact-btn" aria-label="Email">
        <i class="ri-mail-line"></i>
      </a>
    </div>
  </div>
</div>


<script>
(function(){
  const toggle   = document.querySelector('.menu-toggle');
  const panel    = document.getElementById('ksOffcanvasNav');
  const backdrop = document.getElementById('ksOffcanvasBackdrop');
  const closeBtn = panel ? panel.querySelector('.offcanvas-close') : null;

  function openMenu(){
    if (!panel) return;
    panel.classList.add('active');
    backdrop.classList.add('active');
    toggle.classList.add('active');
    toggle.setAttribute('aria-expanded','true');
    panel.setAttribute('aria-hidden','false');
    document.body.style.overflow = 'hidden';
  }

  function closeMenu(){
    if (!panel) return;
    panel.classList.remove('active');
    backdrop.classList.remove('active');
    toggle.classList.remove('active');
    toggle.setAttribute('aria-expanded','false');
    panel.setAttribute('aria-hidden','true');
    document.body.style.overflow = '';
  }

  function toggleMenu(){
    if (panel.classList.contains('active')) { closeMenu(); } else { openMenu(); }
  }

  toggle && toggle.addEventListener('click', toggleMenu);
  closeBtn && closeBtn.addEventListener('click', closeMenu);
  backdrop && backdrop.addEventListener('click', closeMenu);
  document.addEventListener('keydown', (e)=>{ if(e.key==='Escape') closeMenu(); });

  // Chiudi se si torna in desktop
  window.addEventListener('resize', ()=>{ if (window.innerWidth >= 992) closeMenu(); });
})();
</script>
