<?php
/**
 * Header Component
 * Include questo file in tutte le pagine
 */

// Include config only if not already included
if (!defined('BASE_PATH')) {
    define('BASE_PATH', dirname(__DIR__) . '/');
    require_once BASE_PATH . 'config/config.php';
}

// Determina la pagina corrente per l'active state
$current_page = get_current_page();
?>

<!-- Header Top Bar -->
<div class="header-top">
    <div class="container">
        <div class="header-top-content">
            <div class="header-contacts">
                <a href="tel:<?php echo str_replace(' ', '', COMPANY_PHONE); ?>" class="header-contact-item">
                    <i class="ri-phone-line"></i>
                    <span><?php echo COMPANY_PHONE; ?></span>
                </a>
                <a href="<?php echo whatsapp_link('Ciao Key Soft Italia, ho bisogno di assistenza'); ?>" 
                   class="header-contact-item" 
                   target="_blank"
                   data-whatsapp="header">
                    <i class="ri-whatsapp-line"></i>
                    <span>WhatsApp</span>
                </a>
                <a href="mailto:<?php echo COMPANY_EMAIL; ?>" class="header-contact-item">
                    <i class="ri-mail-line"></i>
                    <span><?php echo COMPANY_EMAIL; ?></span>
                </a>
            </div>
            <div class="header-info">
                <span class="header-info-item">
                    <i class="ri-time-line"></i>
                    Lun-Ven 9:00-19:00 • Sab 9:00-13:00
                </span>
            </div>
        </div>
    </div>
</div>

<!-- Header Main -->
<header class="header">
    <div class="header-main">
        <div class="container">
            <div class="header-main-content">
                <!-- Logo -->
                <a href="<?php echo url(); ?>" class="logo">
                    <div class="logo-icon">KS</div>
                    <div class="logo-text">
                        <div class="logo-title">Key Soft Italia</div>
                        <div class="logo-subtitle">L'universo della Tecnologia</div>
                    </div>
                </a>
                
                <!-- Navigation -->
                <nav class="nav-main">
                    <ul class="nav-menu">
                        <li class="nav-item">
                            <a href="<?php echo url('chi-siamo.php'); ?>" 
                               class="nav-link <?php echo $current_page == 'chi-siamo' ? 'active' : ''; ?>">
                                Chi Siamo
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo url('servizi.php'); ?>" 
                               class="nav-link <?php echo $current_page == 'servizi' ? 'active' : ''; ?>">
                                Servizi
                                <i class="ri-arrow-down-s-line"></i>
                            </a>
                            <div class="nav-dropdown">
                                <a href="<?php echo url('servizi/riparazioni.php'); ?>" class="nav-dropdown-item">
                                    <i class="ri-tools-line"></i> Riparazioni & Assistenza
                                </a>
                                <a href="<?php echo url('servizi/vendita.php'); ?>" class="nav-dropdown-item">
                                    <i class="ri-shopping-bag-line"></i> Vendita al Dettaglio
                                </a>
                                <a href="<?php echo url('servizi/telefonia.php'); ?>" class="nav-dropdown-item">
                                    <i class="ri-sim-card-line"></i> Telefonia & Servizi Casa
                                </a>
                                <a href="<?php echo url('servizi/sviluppo-web.php'); ?>" class="nav-dropdown-item">
                                    <i class="ri-code-line"></i> Sviluppo Web & App
                                </a>
                                <a href="<?php echo url('servizi/consulenza-it.php'); ?>" class="nav-dropdown-item">
                                    <i class="ri-shield-check-line"></i> Consulenza IT & Reti
                                </a>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo url('ricondizionati.php'); ?>" 
                               class="nav-link <?php echo $current_page == 'ricondizionati' ? 'active' : ''; ?>">
                                Ricondizionati
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo url('video.php'); ?>" 
                               class="nav-link <?php echo $current_page == 'video' ? 'active' : ''; ?>">
                                Video
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo url('contatti.php'); ?>" 
                               class="nav-link <?php echo $current_page == 'contatti' ? 'active' : ''; ?>">
                                Contatti
                            </a>
                        </li>
                    </ul>
                    
                    <!-- CTA Buttons -->
                    <div class="nav-actions">
                        <a href="<?php echo url('assistenza.php'); ?>" class="nav-cta nav-cta-assistenza">
                            <i class="ri-customer-service-line"></i> Assistenza
                        </a>
                        <a href="<?php echo url('preventivo.php'); ?>" class="nav-cta nav-cta-preventivo">
                            <i class="ri-file-list-3-line"></i> Preventivo
                        </a>
                    </div>
                </nav>
                
                <!-- Mobile Menu Toggle -->
                <button class="menu-toggle" aria-label="Menu">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
            </div>
        </div>
    </div>
</header>

<!-- Mobile Offcanvas Menu -->
<div class="offcanvas-backdrop"></div>
<div class="offcanvas">
    <div class="offcanvas-header">
        <div class="logo">
            <div class="logo-icon">KS</div>
            <div class="logo-text">
                <div class="logo-title">Key Soft Italia</div>
            </div>
        </div>
        <button class="offcanvas-close" aria-label="Chiudi menu">
            <i class="ri-close-line"></i>
        </button>
    </div>
    <div class="offcanvas-body">
        <ul class="mobile-nav">
            <li class="mobile-nav-item">
                <a href="<?php echo url(''); ?>" class="mobile-nav-link">Home</a>
            </li>
            <li class="mobile-nav-item">
                <a href="<?php echo url('chi-siamo.php'); ?>" class="mobile-nav-link">Chi Siamo</a>
            </li>
            <li class="mobile-nav-item">
                <a href="<?php echo url('servizi.php'); ?>" class="mobile-nav-link">Servizi</a>
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
                <a href="<?php echo url('ricondizionati.php'); ?>" class="mobile-nav-link">Ricondizionati</a>
            </li>
            <li class="mobile-nav-item">
                <a href="<?php echo url('video.php'); ?>" class="mobile-nav-link">Video</a>
            </li>
            <li class="mobile-nav-item">
                <a href="<?php echo url('contatti.php'); ?>" class="mobile-nav-link">Contatti</a>
            </li>
        </ul>
        <div class="mobile-nav-actions">
            <a href="<?php echo url('assistenza.php'); ?>" class="mobile-nav-cta mobile-nav-cta-assistenza">
                <i class="ri-customer-service-line"></i> Assistenza
            </a>
            <a href="<?php echo url('preventivo.php'); ?>" class="mobile-nav-cta mobile-nav-cta-preventivo">
                <i class="ri-file-list-3-line"></i> Preventivo
            </a>
        </div>
    </div>
    <div class="offcanvas-body">
        <!-- Mobile CTA Buttons (sempre in alto) -->
        <div class="offcanvas-actions">
            <a href="<?php echo url('assistenza.php'); ?>" class="btn btn-primary btn-block">
                <i class="ri-customer-service-line"></i> Assistenza
            </a>
            <a href="<?php echo url('preventivo.php'); ?>" class="btn btn-primary btn-block">
                <i class="ri-file-list-3-line"></i> Preventivo
            </a>
        </div>
        
        <!-- Mobile Navigation -->
        <nav class="offcanvas-nav">
            <a href="<?php echo url(); ?>" class="offcanvas-nav-item <?php echo $current_page == 'index' ? 'active' : ''; ?>">
                <i class="ri-home-line"></i> Home
            </a>
            <a href="<?php echo url('chi-siamo.php'); ?>" class="offcanvas-nav-item <?php echo $current_page == 'chi-siamo' ? 'active' : ''; ?>">
                <i class="ri-team-line"></i> Chi Siamo
            </a>
            <a href="<?php echo url('servizi.php'); ?>" class="offcanvas-nav-item <?php echo $current_page == 'servizi' ? 'active' : ''; ?>">
                <i class="ri-service-line"></i> Servizi
            </a>
            <a href="<?php echo url('ricondizionati.php'); ?>" class="offcanvas-nav-item <?php echo $current_page == 'ricondizionati' ? 'active' : ''; ?>">
                <i class="ri-smartphone-line"></i> Ricondizionati
            </a>
            <a href="<?php echo url('video.php'); ?>" class="offcanvas-nav-item <?php echo $current_page == 'video' ? 'active' : ''; ?>">
                <i class="ri-play-circle-line"></i> Video
            </a>
            <a href="<?php echo url('contatti.php'); ?>" class="offcanvas-nav-item <?php echo $current_page == 'contatti' ? 'active' : ''; ?>">
                <i class="ri-map-pin-line"></i> Contatti
            </a>
        </nav>
        
        <!-- Mobile Contact Info -->
        <div class="offcanvas-contacts">
            <a href="tel:<?php echo str_replace(' ', '', COMPANY_PHONE); ?>" class="offcanvas-contact-item">
                <i class="ri-phone-line"></i> <?php echo COMPANY_PHONE; ?>
            </a>
            <a href="<?php echo whatsapp_link('Ciao Key Soft Italia!'); ?>" class="offcanvas-contact-item" target="_blank">
                <i class="ri-whatsapp-line"></i> WhatsApp
            </a>
            <a href="mailto:<?php echo COMPANY_EMAIL; ?>" class="offcanvas-contact-item">
                <i class="ri-mail-line"></i> Email
            </a>
        </div>
    </div>
</div>