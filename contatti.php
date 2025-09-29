<?php
/**
 * Key Soft Italia - Contatti
 * Pagina contatti con form, mappa e informazioni
 */

// Define BASE_PATH if not defined
if (!defined('BASE_PATH')) {
    define('BASE_PATH', __DIR__ . '/');
}

require_once BASE_PATH . 'config/config.php';

// SEO Meta
$page_title = "Contatti - Key Soft Italia | Ginosa, Via Diaz 46";
$page_description = "Contatta Key Soft Italia a Ginosa per riparazioni, assistenza e vendita. Via Diaz 46, Tel: 099 829 3794. WhatsApp, email o vieni in negozio.";
$page_keywords = "contatti key soft italia, negozio informatica ginosa, assistenza computer taranto";

// Breadcrumbs
$breadcrumbs = [
    ['label' => 'Contatti', 'url' => 'contatti.php']
];
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    
    <?php echo generate_meta_tags([
        'title' => $page_title,
        'description' => $page_description,
        'keywords' => $page_keywords,
        'url' => url('contatti.php')
    ]); ?>
    
    <!-- Preconnect -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo asset('css/variables.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset('css/main.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset('css/components.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset('css/pages/contatti.css'); ?>">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?php echo asset('images/favicon.ico'); ?>">
</head>
<body>
    
    <!-- Header -->
    <?php include 'includes/header.php'; ?>
    
    <!-- Hero Section -->
    <section class="hero hero-contatti">
        <div class="container">
            <div class="hero-content text-center">
                <h1 class="hero-title text-white">Contattaci</h1>
                <p class="hero-subtitle">
                    Siamo qui per aiutarti. Scegli il modo più comodo per contattarci.
                </p>
                <?php echo generate_breadcrumbs($breadcrumbs); ?>
            </div>
        </div>
    </section>
    
    <!-- Contact Info Cards -->
    <section class="section section-contact-cards">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-3 col-md-6">
                    <div class="contact-card">
                        <div class="contact-card-icon">
                            <i class="ri-map-pin-line"></i>
                        </div>
                        <h4 class="contact-card-title">Indirizzo</h4>
                        <p class="contact-card-text">
                            Via Diaz, 46<br>
                            74013 Ginosa (TA)
                        </p>
                        <a href="<?php echo GOOGLE_MAPS_LINK; ?>" 
                           target="_blank" 
                           class="contact-card-link">
                            Indicazioni stradali <i class="ri-arrow-right-line"></i>
                        </a>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <div class="contact-card">
                        <div class="contact-card-icon">
                            <i class="ri-phone-line"></i>
                        </div>
                        <h4 class="contact-card-title">Telefono</h4>
                        <p class="contact-card-text">
                            <a href="tel:<?php echo str_replace(' ', '', COMPANY_PHONE); ?>">
                                <?php echo COMPANY_PHONE; ?>
                            </a><br>
                            Lun-Ven: 9:00-19:00
                        </p>
                        <a href="tel:<?php echo str_replace(' ', '', COMPANY_PHONE); ?>" 
                           class="contact-card-link">
                            Chiama ora <i class="ri-arrow-right-line"></i>
                        </a>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <div class="contact-card">
                        <div class="contact-card-icon">
                            <i class="ri-whatsapp-line"></i>
                        </div>
                        <h4 class="contact-card-title">WhatsApp</h4>
                        <p class="contact-card-text">
                            <?php echo COMPANY_WHATSAPP; ?><br>
                            Risposta immediata
                        </p>
                        <a href="<?php echo whatsapp_link('Ciao Key Soft Italia, ho bisogno di informazioni'); ?>" 
                           target="_blank"
                           class="contact-card-link">
                            Scrivici su WhatsApp <i class="ri-arrow-right-line"></i>
                        </a>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <div class="contact-card">
                        <div class="contact-card-icon">
                            <i class="ri-mail-line"></i>
                        </div>
                        <h4 class="contact-card-title">Email</h4>
                        <p class="contact-card-text">
                            <a href="mailto:<?php echo COMPANY_EMAIL; ?>">
                                <?php echo COMPANY_EMAIL; ?>
                            </a><br>
                            24/7 Support
                        </p>
                        <a href="mailto:<?php echo COMPANY_EMAIL; ?>" 
                           class="contact-card-link">
                            Invia email <i class="ri-arrow-right-line"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Contact Form & Map Section -->
    <section class="section section-contact-main">
        <div class="container">
            <div class="row g-5">
                <!-- Contact Form -->
                <div class="col-lg-6">
                    <div class="contact-form-wrapper">
                        <h2 class="section-title">Invia un messaggio</h2>
                        <p class="section-subtitle mb-4">
                            Compila il form e ti risponderemo entro 24 ore
                        </p>
                        
                        <form id="contactForm" class="contact-form" data-ajax="true">
                            <?php echo csrf_field(); ?>
                            
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Nome *</label>
                                        <input type="text" 
                                               class="form-control" 
                                               name="name" 
                                               required>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Cognome *</label>
                                        <input type="text" 
                                               class="form-control" 
                                               name="surname" 
                                               required>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Email *</label>
                                        <input type="email" 
                                               class="form-control" 
                                               name="email" 
                                               required>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Telefono</label>
                                        <input type="tel" 
                                               class="form-control" 
                                               name="phone"
                                               placeholder="000 000 0000">
                                    </div>
                                </div>
                                
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="form-label">Oggetto *</label>
                                        <select class="form-select" name="subject" required>
                                            <option value="">Seleziona un argomento</option>
                                            <option value="riparazione">Richiesta Riparazione</option>
                                            <option value="preventivo">Richiesta Preventivo</option>
                                            <option value="assistenza">Assistenza Tecnica</option>
                                            <option value="vendita">Informazioni Vendita</option>
                                            <option value="altro">Altro</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="form-label">Messaggio *</label>
                                        <textarea class="form-control" 
                                                  name="message" 
                                                  rows="5" 
                                                  required
                                                  placeholder="Descrivi la tua richiesta..."></textarea>
                                    </div>
                                </div>
                                
                                <div class="col-12">
                                    <div class="form-check">
                                        <input class="form-check-input" 
                                               type="checkbox" 
                                               id="privacy" 
                                               name="privacy" 
                                               required>
                                        <label class="form-check-label" for="privacy">
                                            Accetto la <a href="<?php echo url('privacy.php'); ?>" target="_blank">privacy policy</a> *
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary btn-lg w-100">
                                        <i class="ri-send-plane-line"></i> Invia Messaggio
                                    </button>
                                </div>
                            </div>
                            
                            <div class="alert alert-success mt-3 d-none" id="successMessage">
                                <i class="ri-check-line"></i> Messaggio inviato con successo! Ti risponderemo presto.
                            </div>
                            
                            <div class="alert alert-danger mt-3 d-none" id="errorMessage">
                                <i class="ri-error-warning-line"></i> Si è verificato un errore. Riprova più tardi.
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Map & Info -->
                <div class="col-lg-6">
                    <div class="map-wrapper">
                        <h2 class="section-title">Dove siamo</h2>
                        <p class="section-subtitle mb-4">
                            Vieni a trovarci nel nostro negozio a Ginosa
                        </p>
                        
                        <!-- Map Container -->
                        <div class="map-container">
                            <!-- Placeholder for Google Maps -->
                            <div class="map-placeholder">
                                <i class="ri-map-pin-2-line"></i>
                                <h4>Key Soft Italia</h4>
                                <p>Via Diaz, 46 - 74013 Ginosa (TA)</p>
                                <a href="<?php echo GOOGLE_MAPS_LINK; ?>" 
                                   target="_blank" 
                                   class="btn btn-primary">
                                    <i class="ri-map-line"></i> Visualizza su Google Maps
                                </a>
                            </div>
                            <!-- In produzione qui andrà l'iframe di Google Maps -->
                        </div>
                        
                        <!-- Opening Hours -->
                        <div class="opening-hours-box mt-4">
                            <h4 class="mb-3">
                                <i class="ri-time-line"></i> Orari di Apertura
                            </h4>
                            <div class="opening-hours-list">
                                <div class="opening-hour-row">
                                    <span>Lunedì - Venerdì</span>
                                    <strong>9:00 - 19:00</strong>
                                </div>
                                <div class="opening-hour-row">
                                    <span>Sabato</span>
                                    <strong>9:00 - 13:00</strong>
                                </div>
                                <div class="opening-hour-row">
                                    <span>Domenica</span>
                                    <strong class="text-danger">Chiuso</strong>
                                </div>
                            </div>
                            <div class="alert alert-info mt-3">
                                <i class="ri-information-line"></i> 
                                Siamo aperti anche durante la pausa pranzo per garantirti 
                                assistenza continuativa.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- FAQ Section -->
    <section class="section section-faq bg-light">
        <div class="container">
            <div class="section-header text-center">
                <h2 class="section-title">Domande Frequenti</h2>
                <p class="section-subtitle">
                    Le risposte alle domande più comuni
                </p>
            </div>
            
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="accordion" id="faqAccordion">
                        <div class="accordion-item">
                            <h3 class="accordion-header">
                                <button class="accordion-button" 
                                        type="button" 
                                        data-bs-toggle="collapse" 
                                        data-bs-target="#faq1">
                                    <i class="ri-question-line me-2"></i>
                                    Quali sono i vostri orari di apertura?
                                </button>
                            </h3>
                            <div id="faq1" 
                                 class="accordion-collapse collapse show" 
                                 data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Siamo aperti dal lunedì al venerdì dalle 9:00 alle 19:00 
                                    (orario continuato) e il sabato dalle 9:00 alle 13:00. 
                                    La domenica siamo chiusi.
                                </div>
                            </div>
                        </div>
                        
                        <div class="accordion-item">
                            <h3 class="accordion-header">
                                <button class="accordion-button collapsed" 
                                        type="button" 
                                        data-bs-toggle="collapse" 
                                        data-bs-target="#faq2">
                                    <i class="ri-question-line me-2"></i>
                                    Offrite servizio di ritiro e consegna?
                                </button>
                            </h3>
                            <div id="faq2" 
                                 class="accordion-collapse collapse" 
                                 data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Sì, offriamo servizio di ritiro e consegna a domicilio 
                                    per Ginosa e comuni limitrofi. Il servizio ha un costo 
                                    aggiuntivo che varia in base alla distanza.
                                </div>
                            </div>
                        </div>
                        
                        <div class="accordion-item">
                            <h3 class="accordion-header">
                                <button class="accordion-button collapsed" 
                                        type="button" 
                                        data-bs-toggle="collapse" 
                                        data-bs-target="#faq3">
                                    <i class="ri-question-line me-2"></i>
                                    Quanto tempo serve per una riparazione?
                                </button>
                            </h3>
                            <div id="faq3" 
                                 class="accordion-collapse collapse" 
                                 data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    I tempi variano in base al tipo di intervento. 
                                    Per le riparazioni più comuni garantiamo un servizio 
                                    express in 24-48 ore. Per interventi più complessi 
                                    potrebbero essere necessari 3-5 giorni lavorativi.
                                </div>
                            </div>
                        </div>
                        
                        <div class="accordion-item">
                            <h3 class="accordion-header">
                                <button class="accordion-button collapsed" 
                                        type="button" 
                                        data-bs-toggle="collapse" 
                                        data-bs-target="#faq4">
                                    <i class="ri-question-line me-2"></i>
                                    Posso avere un preventivo telefonico?
                                </button>
                            </h3>
                            <div id="faq4" 
                                 class="accordion-collapse collapse" 
                                 data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Possiamo fornire una stima indicativa telefonica, 
                                    ma per un preventivo preciso è necessaria una diagnosi 
                                    del dispositivo. La diagnosi è sempre gratuita e senza impegno.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- CTA Section -->
    <section class="section section-cta">
        <div class="container">
            <div class="cta-box text-center">
                <h2 class="cta-title">Preferisci contattarci direttamente?</h2>
                <p class="cta-subtitle mb-4">
                    Scegli il metodo che preferisci per contattarci immediatamente
                </p>
                <div class="cta-buttons">
                    <a href="<?php echo whatsapp_link('Ciao Key Soft Italia!'); ?>" 
                       target="_blank"
                       class="btn btn-success btn-lg">
                        <i class="ri-whatsapp-line"></i> WhatsApp
                    </a>
                    <a href="tel:<?php echo str_replace(' ', '', COMPANY_PHONE); ?>" 
                       class="btn btn-primary btn-lg">
                        <i class="ri-phone-line"></i> Chiama Ora
                    </a>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo asset('js/main.js'); ?>"></script>
    
    <!-- Page Specific Scripts -->
    <script>
        // Set BASE_URL for JavaScript
        window.KS_CONFIG = {
            baseUrl: '<?php echo BASE_URL; ?>',
            whatsappNumber: '<?php echo WHATSAPP_NUMBER; ?>'
        };
        
        // Initialize contact form
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('contactForm');
            if (form) {
                new FormHandler('#contactForm');
            }
        });
    </script>
</body>
</html>