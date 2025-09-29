<?php
/**
 * Key Soft Italia - Homepage
 * Pagina principale del sito
 */

// Define BASE_PATH if not defined
if (!defined('BASE_PATH')) {
    define('BASE_PATH', __DIR__ . '/');
}

require_once BASE_PATH . 'config/config.php';

// SEO Meta
$page_title = "Key Soft Italia - L'universo della Tecnologia | Riparazioni, Vendita e Assistenza a Ginosa";
$page_description = "Key Soft Italia a Ginosa - Riparazioni in 24h, vendita dispositivi ricondizionati, assistenza informatica, sviluppo web. Il tuo partner tecnologico di fiducia dal 2008.";
$page_keywords = "riparazioni smartphone ginosa, assistenza computer taranto, ricondizionati, sviluppo web, key soft italia";
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <meta name="description" content="<?php echo $page_description; ?>">
    <meta name="keywords" content="<?php echo $page_keywords; ?>">
    
    <!-- Open Graph -->
    <meta property="og:title" content="<?php echo $page_title; ?>">
    <meta property="og:description" content="<?php echo $page_description; ?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>">
    <meta property="og:image" content="<?php echo url('assets/images/og-image.jpg'); ?>">
    
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
    <link rel="stylesheet" href="<?php echo asset('css/pages/home.css'); ?>">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?php echo asset('images/favicon.ico'); ?>">
</head>
<body>
    
    <!-- Header -->
    <?php include 'includes/header.php'; ?>
    
    <!-- Hero Section -->
    <section class="hero hero-home">
        <div class="container">
            <div class="row align-items-center min-vh-70">
                <div class="col-lg-6">
                    <div class="hero-content">
                        <!-- Badge -->
                        <div class="hero-badge animate-fadeIn">
                            <i class="ri-time-line"></i>
                            <span>Riparazione in 24h*</span>
                        </div>
                        
                        <!-- Title -->
                        <h1 class="hero-title animate-slideInLeft">
                            Siamo il tuo partner <span class="highlight">tecnologico</span> di fiducia a Ginosa
                        </h1>
                        
                        <!-- Description -->
                        <p class="hero-description animate-slideInLeft">
                            Riparazioni espresse, vendita di dispositivi ricondizionati, 
                            assistenza professionale e sviluppo software. 
                            Soluzioni complete per privati e aziende.
                        </p>
                        
                        <!-- CTA Buttons -->
                        <div class="hero-actions animate-fadeIn">
                            <a href="<?php echo url('servizi.php'); ?>" class="btn btn-primary btn-lg">
                                <i class="ri-service-line"></i> Scopri i servizi
                            </a>
                            <a href="<?php echo url('assistenza.php'); ?>" class="btn btn-secondary btn-lg">
                                <i class="ri-customer-service-line"></i> Richiedi assistenza
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-6">
                    <!-- Service Cards Grid -->
                    <div class="hero-features">
                        <div class="row g-3">
                            <div class="col-6">
                                <div class="feature-card animate-fadeIn">
                                    <i class="ri-exchange-line"></i>
                                    <h4>Passaggio dati incluso</h4>
                                    <p>Trasferimento completo dei tuoi dati</p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="feature-card animate-fadeIn">
                                    <i class="ri-user-settings-line"></i>
                                    <h4>Account & credenziali</h4>
                                    <p>Configurazione account e app</p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="feature-card animate-fadeIn">
                                    <i class="ri-shield-check-line"></i>
                                    <h4>Garanzia senza pensieri</h4>
                                    <p>12 mesi di garanzia inclusa</p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="feature-card animate-fadeIn">
                                    <i class="ri-smartphone-line"></i>
                                    <h4>Cambio dispositivo immediato</h4>
                                    <p>Dispositivo sostitutivo disponibile</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Background Pattern -->
        <div class="hero-pattern"></div>
    </section>
    
    <!-- Start Repair Section -->
    <section class="section section-repair">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Inizia la tua riparazione</h2>
                <p class="section-subtitle">
                    Scegli il dispositivo che necessita assistenza. Riparazioni express con garanzia 12 mesi.
                </p>
            </div>
            
            <div class="row g-4">
                <!-- PC & Mac -->
                <div class="col-lg-3 col-md-6">
                    <div class="device-card">
                        <div class="device-icon">
                            <i class="ri-computer-line"></i>
                        </div>
                        <h3 class="device-title">PC & Mac</h3>
                        <ul class="device-list">
                            <li>Desktop Windows</li>
                            <li>iMac & Mac Mini</li>
                            <li>All-in-One</li>
                            <li>Workstation</li>
                        </ul>
                        <a href="<?php echo url('servizi/riparazioni.php#pc-mac'); ?>" class="btn btn-outline-primary btn-sm w-100">
                            Scopri di più
                        </a>
                    </div>
                </div>
                
                <!-- Laptop/MacBook -->
                <div class="col-lg-3 col-md-6">
                    <div class="device-card">
                        <div class="device-icon">
                            <i class="ri-macbook-line"></i>
                        </div>
                        <h3 class="device-title">Laptop/MacBook</h3>
                        <ul class="device-list">
                            <li>Notebook Windows</li>
                            <li>MacBook Pro/Air</li>
                            <li>Ultrabook</li>
                            <li>Gaming Laptop</li>
                        </ul>
                        <a href="<?php echo url('servizi/riparazioni.php#laptop'); ?>" class="btn btn-outline-primary btn-sm w-100">
                            Scopri di più
                        </a>
                    </div>
                </div>
                
                <!-- Smartphone -->
                <div class="col-lg-3 col-md-6">
                    <div class="device-card">
                        <div class="device-icon">
                            <i class="ri-smartphone-line"></i>
                        </div>
                        <h3 class="device-title">Smartphone</h3>
                        <ul class="device-list">
                            <li>iPhone</li>
                            <li>Samsung Galaxy</li>
                            <li>Xiaomi/Redmi</li>
                            <li>Altri Android</li>
                        </ul>
                        <a href="<?php echo url('servizi/riparazioni.php#smartphone'); ?>" class="btn btn-outline-primary btn-sm w-100">
                            Scopri di più
                        </a>
                    </div>
                </div>
                
                <!-- Tablet -->
                <div class="col-lg-3 col-md-6">
                    <div class="device-card">
                        <div class="device-icon">
                            <i class="ri-tablet-line"></i>
                        </div>
                        <h3 class="device-title">Tablet</h3>
                        <ul class="device-list">
                            <li>iPad Pro/Air/Mini</li>
                            <li>Samsung Tab</li>
                            <li>Microsoft Surface</li>
                            <li>Altri tablet</li>
                        </ul>
                        <a href="<?php echo url('servizi/riparazioni.php#tablet'); ?>" class="btn btn-outline-primary btn-sm w-100">
                            Scopri di più
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Double Panel CTA Section -->
    <section class="section section-cta-panels">
        <div class="container">
            <div class="row g-4">
                <!-- Richiedi Preventivo -->
                <div class="col-lg-6">
                    <div class="cta-panel cta-panel-orange">
                        <div class="cta-panel-content">
                            <div class="cta-panel-icon">
                                <i class="ri-file-list-3-line"></i>
                            </div>
                            <h3 class="cta-panel-title">Richiedi Preventivo</h3>
                            <p class="cta-panel-description">
                                Ottieni un preventivo gratuito e senza impegno. 
                                Risposta garantita entro 24 ore.
                            </p>
                            <a href="<?php echo url('preventivo.php'); ?>" class="btn btn-white">
                                Richiedi ora <i class="ri-arrow-right-line"></i>
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Vendi il tuo usato -->
                <div class="col-lg-6">
                    <div class="cta-panel cta-panel-green">
                        <div class="cta-panel-content">
                            <div class="cta-panel-icon">
                                <i class="ri-recycle-line"></i>
                            </div>
                            <h3 class="cta-panel-title">Vendi il tuo usato</h3>
                            <p class="cta-panel-description">
                                Valutazione immediata del tuo dispositivo usato. 
                                Pagamento istantaneo e ritiro gratuito.
                            </p>
                            <a href="<?php echo url('servizi/vendita.php#permuta'); ?>" class="btn btn-white">
                                Valuta ora <i class="ri-arrow-right-line"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Why Choose Us Section -->
    <section class="section section-why-us">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Perché scegliere Key Soft Italia</h2>
                <p class="section-subtitle">
                    I vantaggi che fanno la differenza nel nostro servizio
                </p>
            </div>
            
            <div class="row g-4">
                <div class="col-lg-3 col-md-6">
                    <div class="advantage-card">
                        <div class="advantage-icon">
                            <i class="ri-search-eye-line"></i>
                        </div>
                        <h4 class="advantage-title">Diagnosi Gratuita</h4>
                        <p class="advantage-text">
                            Analizziamo il problema senza costi. 
                            Paghi solo se decidi di riparare.
                        </p>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <div class="advantage-card">
                        <div class="advantage-icon">
                            <i class="ri-price-tag-3-line"></i>
                        </div>
                        <h4 class="advantage-title">Prezzo Giusto</h4>
                        <p class="advantage-text">
                            Prezzi trasparenti e competitivi. 
                            Preventivo dettagliato sempre gratuito.
                        </p>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <div class="advantage-card">
                        <div class="advantage-icon">
                            <i class="ri-timer-flash-line"></i>
                        </div>
                        <h4 class="advantage-title">Riparazione Rapida</h4>
                        <p class="advantage-text">
                            Servizio express in 24h per le riparazioni più comuni. 
                            Nessuna attesa.
                        </p>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <div class="advantage-card">
                        <div class="advantage-icon">
                            <i class="ri-shield-star-line"></i>
                        </div>
                        <h4 class="advantage-title">Garanzia 12 Mesi</h4>
                        <p class="advantage-text">
                            Tutte le riparazioni sono garantite 12 mesi. 
                            La tua tranquillità è importante.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- What We Do Section -->
    <section class="section section-services bg-light">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <div class="section-header text-start">
                        <h2 class="section-title">Cosa facciamo</h2>
                        <p class="section-subtitle">
                            Soluzioni tecnologiche complete per ogni esigenza
                        </p>
                    </div>
                    
                    <div class="services-grid">
                        <div class="service-item">
                            <i class="ri-tools-line"></i>
                            <div>
                                <h4>Riparazioni & Assistenza</h4>
                                <p>Riparazione professionale di smartphone, tablet, PC e console gaming</p>
                            </div>
                        </div>
                        
                        <div class="service-item">
                            <i class="ri-shopping-bag-line"></i>
                            <div>
                                <h4>Vendita al Dettaglio</h4>
                                <p>Dispositivi nuovi e ricondizionati con garanzia, accessori originali</p>
                            </div>
                        </div>
                        
                        <div class="service-item">
                            <i class="ri-shield-check-line"></i>
                            <div>
                                <h4>MyShape Protection</h4>
                                <p>Piani di protezione e assistenza estesa per i tuoi dispositivi</p>
                            </div>
                        </div>
                        
                        <div class="service-item">
                            <i class="ri-global-line"></i>
                            <div>
                                <h4>Sviluppo Web & App</h4>
                                <p>Creazione siti web, e-commerce e applicazioni mobile su misura</p>
                            </div>
                        </div>
                        
                        <div class="service-item">
                            <i class="ri-server-line"></i>
                            <div>
                                <h4>Consulenza IT & Reti</h4>
                                <p>Progettazione reti aziendali, sistemi di sicurezza e videosorveglianza</p>
                            </div>
                        </div>
                        
                        <div class="service-item">
                            <i class="ri-sim-card-line"></i>
                            <div>
                                <h4>Telefonia & Servizi Casa</h4>
                                <p>Attivazione SIM, offerte internet casa, luce e gas con i migliori operatori</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-5">
                        <a href="<?php echo url('servizi.php'); ?>" class="btn btn-primary">
                            Scopri tutti i servizi <i class="ri-arrow-right-line"></i>
                        </a>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <div class="experience-box">
                        <div class="experience-number">15+</div>
                        <div class="experience-text">anni di esperienza</div>
                        <div class="experience-stats">
                            <div class="stat-item">
                                <div class="stat-number">5000+</div>
                                <div class="stat-label">Clienti soddisfatti</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-number">24h</div>
                                <div class="stat-label">Tempo medio riparazione</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- CTA Section -->
    <section class="section section-final-cta">
        <div class="container">
            <div class="cta-box">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <h3 class="cta-title">Hai bisogno di assistenza immediata?</h3>
                        <p class="cta-subtitle">
                            I nostri tecnici sono pronti ad aiutarti. Contattaci ora per un supporto rapido e professionale.
                        </p>
                    </div>
                    <div class="col-lg-4 text-lg-end">
                        <a href="<?php echo whatsapp_link('Ciao Key Soft Italia, ho bisogno di assistenza immediata!', ['utm_campaign' => 'home-cta']); ?>" 
                           target="_blank" 
                           class="btn btn-white btn-lg"
                           data-whatsapp="home-cta">
                            <i class="ri-whatsapp-line"></i> Contattaci su WhatsApp
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo asset('js/main.js'); ?>"></script>
    
    <!-- Set BASE_URL for JavaScript -->
    <script>
        window.KS_CONFIG = {
            baseUrl: '<?php echo BASE_URL; ?>',
            whatsappNumber: '<?php echo WHATSAPP_NUMBER; ?>'
        };
    </script>
</body>
</html>