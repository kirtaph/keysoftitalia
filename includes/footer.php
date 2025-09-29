<?php
/**
 * Footer Component
 * Include questo file in tutte le pagine
 */

// Include config only if not already included
if (!defined('BASE_PATH')) {
    define('BASE_PATH', dirname(__DIR__) . '/');
    require_once BASE_PATH . 'config/config.php';
}
?>

<!-- Footer -->
<footer class="footer">
    <div class="container">
        <!-- Footer Content -->
        <div class="footer-content">
            <!-- Brand Column -->
            <div class="footer-brand">
                <a href="<?php echo url(); ?>" class="footer-logo">
                    <div class="footer-logo-icon">KS</div>
                    <div class="footer-logo-text">Key Soft Italia</div>
                </a>
                <p class="footer-description">
                    Il tuo partner tecnologico di fiducia a Ginosa. 
                    Dal 2008 offriamo soluzioni complete per privati e aziende: 
                    riparazioni, vendita, assistenza e sviluppo software.
                </p>
                <div class="footer-contacts">
                    <a href="<?php echo GOOGLE_MAPS_LINK; ?>" target="_blank" class="footer-contact-item">
                        <i class="ri-map-pin-line"></i>
                        <span><?php echo COMPANY_FULL_ADDRESS; ?></span>
                    </a>
                    <a href="tel:<?php echo str_replace(' ', '', COMPANY_PHONE); ?>" class="footer-contact-item">
                        <i class="ri-phone-line"></i>
                        <span><?php echo COMPANY_PHONE; ?></span>
                    </a>
                    <a href="<?php echo whatsapp_link('Ciao Key Soft Italia!'); ?>" target="_blank" class="footer-contact-item" data-whatsapp="footer">
                        <i class="ri-whatsapp-line"></i>
                        <span><?php echo COMPANY_WHATSAPP; ?></span>
                    </a>
                    <a href="mailto:<?php echo COMPANY_EMAIL; ?>" class="footer-contact-item">
                        <i class="ri-mail-line"></i>
                        <span><?php echo COMPANY_EMAIL; ?></span>
                    </a>
                </div>
            </div>
            
            <!-- Links Column 1 -->
            <div class="footer-column">
                <h4 class="footer-title">Link Utili</h4>
                <div class="footer-links">
                    <a href="<?php echo url('chi-siamo.php'); ?>" class="footer-link">Chi Siamo</a>
                    <a href="<?php echo url('servizi.php'); ?>" class="footer-link">I Nostri Servizi</a>
                    <a href="<?php echo url('ricondizionati.php'); ?>" class="footer-link">Dispositivi Ricondizionati</a>
                    <a href="<?php echo url('preventivo.php'); ?>" class="footer-link">Richiedi Preventivo</a>
                    <a href="<?php echo url('assistenza.php'); ?>" class="footer-link">Assistenza Tecnica</a>
                    <a href="<?php echo url('contatti.php'); ?>" class="footer-link">Contatti</a>
                </div>
            </div>
            
            <!-- Links Column 2 -->
            <div class="footer-column">
                <h4 class="footer-title">Seguici</h4>
                <div class="footer-links">
                    <a href="<?php echo FACEBOOK_URL; ?>" target="_blank" class="footer-link">
                        <i class="ri-facebook-fill"></i> Facebook
                    </a>
                    <a href="<?php echo INSTAGRAM_URL; ?>" target="_blank" class="footer-link">
                        <i class="ri-instagram-line"></i> Instagram
                    </a>
                    <a href="<?php echo YOUTUBE_URL; ?>" target="_blank" class="footer-link">
                        <i class="ri-youtube-fill"></i> YouTube
                    </a>
                    <a href="<?php echo LINKEDIN_URL; ?>" target="_blank" class="footer-link">
                        <i class="ri-linkedin-fill"></i> LinkedIn
                    </a>
                    <a href="<?php echo TIKTOK_URL; ?>" target="_blank" class="footer-link">
                        <i class="ri-tiktok-fill"></i> TikTok
                    </a>
                </div>
                
                <!-- Social Icons -->
                <div class="social-links">
                    <a href="<?php echo FACEBOOK_URL; ?>" target="_blank" class="social-link" aria-label="Facebook">
                        <i class="ri-facebook-fill"></i>
                    </a>
                    <a href="<?php echo INSTAGRAM_URL; ?>" target="_blank" class="social-link" aria-label="Instagram">
                        <i class="ri-instagram-line"></i>
                    </a>
                    <a href="<?php echo YOUTUBE_URL; ?>" target="_blank" class="social-link" aria-label="YouTube">
                        <i class="ri-youtube-fill"></i>
                    </a>
                    <a href="<?php echo LINKEDIN_URL; ?>" target="_blank" class="social-link" aria-label="LinkedIn">
                        <i class="ri-linkedin-fill"></i>
                    </a>
                </div>
            </div>
            
            <!-- Opening Hours Column -->
            <div class="footer-column">
                <h4 class="footer-title">Orari di Apertura</h4>
                
                <!-- Business Info Box -->
                <div class="business-info-box">
                    <div class="badge badge-dark">
                        <i class="ri-time-line"></i> Siamo Aperti
                    </div>
                    <div class="mt-3">
                        <strong>Lunedì - Venerdì:</strong> 9:00 - 19:00<br>
                        <strong>Sabato:</strong> 9:00 - 13:00<br>
                        <strong>Domenica:</strong> <span class="text-danger">Chiuso</span>
                    </div>
                    <div class="mt-3 text-sm">
                        <i class="ri-information-line text-warning"></i>
                        <small>Orario continuato nei giorni feriali</small>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Footer Bottom -->
        <div class="footer-bottom">
            <div class="footer-copyright">
                © <?php echo date('Y'); ?> Key Soft Italia. Tutti i diritti riservati. | P.IVA 01234567890
            </div>
            <div class="footer-bottom-links">
                <a href="<?php echo url('privacy.php'); ?>" class="footer-bottom-link">Privacy Policy</a>
                <a href="<?php echo url('cookie.php'); ?>" class="footer-bottom-link">Cookie Policy</a>
                <a href="<?php echo url('termini.php'); ?>" class="footer-bottom-link">Termini di Servizio</a>
            </div>
        </div>
    </div>
</footer>

<!-- Back to Top Button -->
<button id="backToTop" class="back-to-top" aria-label="Torna su">
    <i class="ri-arrow-up-line"></i>
</button>

<!-- Notification Container -->
<div id="notificationContainer"></div>

<!-- WhatsApp Floating Button -->
<a href="<?php echo whatsapp_link('Ciao Key Soft Italia, ho bisogno di assistenza!'); ?>" 
   target="_blank" 
   class="whatsapp-floating"
   data-whatsapp="floating"
   aria-label="Contattaci su WhatsApp">
    <i class="ri-whatsapp-line"></i>
    <span class="whatsapp-floating-text">Hai bisogno di aiuto?</span>
</a>

<!-- LocalBusiness Schema -->
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "LocalBusiness",
    "name": "Key Soft Italia",
    "description": "<?php echo SEO_DEFAULT_DESCRIPTION; ?>",
    "url": "<?php echo 'https://' . $_SERVER['HTTP_HOST']; ?>",
    "telephone": "<?php echo COMPANY_PHONE; ?>",
    "email": "<?php echo COMPANY_EMAIL; ?>",
    "address": {
        "@type": "PostalAddress",
        "streetAddress": "Via Diaz, 46",
        "addressLocality": "Ginosa",
        "addressRegion": "TA",
        "postalCode": "74013",
        "addressCountry": "IT"
    },
    "geo": {
        "@type": "GeoCoordinates",
        "latitude": "40.5833",
        "longitude": "16.7667"
    },
    "openingHoursSpecification": [
        {
            "@type": "OpeningHoursSpecification",
            "dayOfWeek": ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday"],
            "opens": "09:00",
            "closes": "19:00"
        },
        {
            "@type": "OpeningHoursSpecification",
            "dayOfWeek": "Saturday",
            "opens": "09:00",
            "closes": "13:00"
        }
    ],
    "sameAs": [
        "<?php echo FACEBOOK_URL; ?>",
        "<?php echo INSTAGRAM_URL; ?>",
        "<?php echo YOUTUBE_URL; ?>",
        "<?php echo LINKEDIN_URL; ?>"
    ],
    "priceRange": "€€"
}
</script>

<!-- Back to Top Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const backToTopBtn = document.getElementById('backToTop');
    
    // Show/hide button based on scroll position
    window.addEventListener('scroll', function() {
        if (window.scrollY > 300) {
            backToTopBtn.classList.add('visible');
        } else {
            backToTopBtn.classList.remove('visible');
        }
    });
    
    // Scroll to top on click
    backToTopBtn?.addEventListener('click', function() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
});
</script>

<style>
/* Back to Top Button */
.back-to-top {
    position: fixed;
    bottom: 30px;
    right: 30px;
    width: 48px;
    height: 48px;
    background-color: var(--ks-orange);
    color: white;
    border: none;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease;
    z-index: 1000;
    box-shadow: 0 4px 12px rgba(255, 107, 53, 0.3);
}

.back-to-top.visible {
    opacity: 1;
    visibility: visible;
}

.back-to-top:hover {
    background-color: var(--ks-orange-hover);
    transform: translateY(-3px);
}

/* WhatsApp Floating Button */
.whatsapp-floating {
    position: fixed;
    bottom: 90px;
    right: 30px;
    width: 60px;
    height: 60px;
    background-color: #25D366;
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    box-shadow: 0 4px 12px rgba(37, 211, 102, 0.3);
    z-index: 999;
    transition: all 0.3s ease;
}

.whatsapp-floating i {
    font-size: 28px;
}

.whatsapp-floating:hover {
    transform: scale(1.1);
    box-shadow: 0 6px 20px rgba(37, 211, 102, 0.4);
}

.whatsapp-floating-text {
    position: absolute;
    right: 70px;
    background: var(--ks-dark);
    color: white;
    padding: 8px 12px;
    border-radius: 8px;
    white-space: nowrap;
    font-size: 14px;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease;
}

.whatsapp-floating:hover .whatsapp-floating-text {
    opacity: 1;
    visibility: visible;
}

/* Notification Styles */
.notification {
    position: fixed;
    top: 100px;
    right: 20px;
    padding: 16px 24px;
    background: white;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    display: flex;
    align-items: center;
    gap: 12px;
    transform: translateX(400px);
    transition: transform 0.3s ease;
    z-index: 2000;
}

.notification.show {
    transform: translateX(0);
}

.notification-success {
    border-left: 4px solid var(--ks-green);
}

.notification-error {
    border-left: 4px solid var(--ks-red);
}

.notification i {
    font-size: 20px;
}

.notification-success i {
    color: var(--ks-green);
}

.notification-error i {
    color: var(--ks-red);
}

/* Business Info Box */
.business-info-box {
    margin-top: 20px;
    padding: 16px;
    background: rgba(255,255,255,0.05);
    border-radius: 12px;
    border: 1px solid rgba(255,255,255,0.1);
}

/* Responsive */
@media (max-width: 768px) {
    .back-to-top,
    .whatsapp-floating {
        right: 20px;
    }
    
    .whatsapp-floating {
        width: 50px;
        height: 50px;
    }
    
    .whatsapp-floating i {
        font-size: 24px;
    }
    
    .whatsapp-floating-text {
        display: none;
    }
}
</style>

<!-- Load Main JavaScript -->
<script src="<?php echo asset('js/main.js'); ?>"></script>