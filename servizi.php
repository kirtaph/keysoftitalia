<?php
/**
 * Key Soft Italia - Servizi
 * Pagina indice dei servizi
 */

// Define BASE_PATH if not defined
if (!defined('BASE_PATH')) {
    define('BASE_PATH', __DIR__ . '/');
}

require_once BASE_PATH . 'config/config.php';

// SEO Meta
$page_title = "Servizi - Key Soft Italia | Riparazioni, Vendita, Assistenza, Sviluppo";
$page_description = "Scopri tutti i servizi di Key Soft Italia: riparazioni express, vendita dispositivi, assistenza informatica, sviluppo web, consulenza IT e telefonia.";
$page_keywords = "servizi informatici ginosa, riparazioni smartphone, assistenza pc, sviluppo web taranto";

// Breadcrumbs
$breadcrumbs = [
    ['label' => 'Servizi', 'url' => 'servizi.php']
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
        'url' => url('servizi.php')
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
    <link rel="stylesheet" href="<?php echo asset('css/pages/servizi.css'); ?>">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?php echo asset('images/favicon.ico'); ?>">
</head>
<body>
    
    <!-- Header -->
    <?php include 'includes/header.php'; ?>
    
    <!-- Hero Section -->
    <section class="hero hero-services">
        <div class="container">
            <div class="hero-content text-center">
                <div class="hero-badge animate-fadeIn">
                    <i class="ri-service-line"></i>
                    Soluzioni complete
                </div>
                <h1 class="hero-title animate-slideInLeft">
                    I Nostri <span class="text-orange">Servizi</span>
                </h1>
                <p class="hero-subtitle animate-slideInRight">
                    Dal supporto tecnico allo sviluppo software, offriamo soluzioni complete per ogni esigenza tecnologica
                </p>
                <div class="hero-actions animate-fadeIn">
                    <a href="<?php echo url('preventivo.php'); ?>" class="btn btn-primary btn-lg">
                        <i class="ri-file-list-3-line"></i> Richiedi Preventivo Gratuito
                    </a>
                    <a href="#services-grid" class="btn btn-secondary btn-lg">
                        <i class="ri-arrow-down-line"></i> Scopri i servizi
                    </a>
                </div>
            </div>
        </div>
        <div class="hero-pattern"></div>
    </section>
    
    <!-- Services Grid Section -->
    <section id="services-grid" class="section section-services-grid">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Tutti i nostri servizi</h2>
                <p class="section-subtitle">
                    Scegli il servizio di cui hai bisogno e scopri come possiamo aiutarti
                </p>
            </div>
            
            <!-- Riparazioni & Assistenza -->
            <div class="service-block mb-5">
                <div class="service-block-header">
                    <div class="service-block-icon">
                        <i class="ri-tools-line"></i>
                    </div>
                    <div>
                        <h3 class="service-block-title">Riparazioni & Assistenza</h3>
                        <p class="service-block-subtitle">Riparazione professionale di tutti i dispositivi elettronici</p>
                    </div>
                    <a href="<?php echo url('servizi/riparazioni.php'); ?>" class="btn btn-outline-primary">
                        Scopri di più <i class="ri-arrow-right-line"></i>
                    </a>
                </div>
                <div class="service-block-content">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="service-feature">
                                <i class="ri-check-line text-green"></i>
                                <span>Smartphone e Tablet di tutti i marchi</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="service-feature">
                                <i class="ri-check-line text-green"></i>
                                <span>Computer Desktop e Laptop</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="service-feature">
                                <i class="ri-check-line text-green"></i>
                                <span>Console Gaming (PlayStation, Xbox, Switch)</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="service-feature">
                                <i class="ri-check-line text-green"></i>
                                <span>Smart TV e Monitor</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="service-feature">
                                <i class="ri-check-line text-green"></i>
                                <span>Riparazioni board-level e microsoldering</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="service-feature">
                                <i class="ri-check-line text-green"></i>
                                <span>Servizio express in 24h</span>
                            </div>
                        </div>
                    </div>
                    <div class="service-block-badges mt-4">
                        <span class="badge bg-orange">Diagnosi Gratuita</span>
                        <span class="badge bg-green">Garanzia 12 Mesi</span>
                        <span class="badge bg-blue">Ricambi Originali</span>
                    </div>
                </div>
            </div>
            
            <!-- Vendita al Dettaglio -->
            <div class="service-block mb-5">
                <div class="service-block-header">
                    <div class="service-block-icon bg-green">
                        <i class="ri-shopping-bag-line"></i>
                    </div>
                    <div>
                        <h3 class="service-block-title">Vendita al Dettaglio</h3>
                        <p class="service-block-subtitle">Dispositivi nuovi e ricondizionati con garanzia</p>
                    </div>
                    <a href="<?php echo url('servizi/vendita.php'); ?>" class="btn btn-outline-green">
                        Scopri di più <i class="ri-arrow-right-line"></i>
                    </a>
                </div>
                <div class="service-block-content">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="service-feature">
                                <i class="ri-check-line text-green"></i>
                                <span>Smartphone e Tablet ricondizionati certificati</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="service-feature">
                                <i class="ri-check-line text-green"></i>
                                <span>Computer e Laptop nuovi e usati garantiti</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="service-feature">
                                <i class="ri-check-line text-green"></i>
                                <span>Accessori originali e compatibili</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="service-feature">
                                <i class="ri-check-line text-green"></i>
                                <span>Console gaming e videogiochi</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="service-feature">
                                <i class="ri-check-line text-green"></i>
                                <span>Permuta del tuo usato</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="service-feature">
                                <i class="ri-check-line text-green"></i>
                                <span>Configurazione e setup inclusi</span>
                            </div>
                        </div>
                    </div>
                    <div class="service-block-badges mt-4">
                        <span class="badge bg-green">Prezzi Competitivi</span>
                        <span class="badge bg-orange">Permuta Usato</span>
                        <span class="badge bg-blue">Finanziamenti</span>
                    </div>
                </div>
            </div>
            
            <!-- MyShape Protection -->
            <div class="service-block mb-5">
                <div class="service-block-header">
                    <div class="service-block-icon bg-purple">
                        <i class="ri-shield-check-line"></i>
                    </div>
                    <div>
                        <h3 class="service-block-title">MyShape Protection</h3>
                        <p class="service-block-subtitle">Piani di protezione completa per i tuoi dispositivi</p>
                    </div>
                    <a href="<?php echo url('servizi/myshape.php'); ?>" class="btn btn-outline-purple">
                        Scopri di più <i class="ri-arrow-right-line"></i>
                    </a>
                </div>
                <div class="service-block-content">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="service-feature">
                                <i class="ri-check-line text-green"></i>
                                <span>Copertura danni accidentali</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="service-feature">
                                <i class="ri-check-line text-green"></i>
                                <span>Furto e smarrimento</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="service-feature">
                                <i class="ri-check-line text-green"></i>
                                <span>Estensione garanzia fino a 3 anni</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="service-feature">
                                <i class="ri-check-line text-green"></i>
                                <span>Assistenza priority 24/7</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="service-feature">
                                <i class="ri-check-line text-green"></i>
                                <span>Dispositivo sostitutivo immediato</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="service-feature">
                                <i class="ri-check-line text-green"></i>
                                <span>Backup cloud automatico</span>
                            </div>
                        </div>
                    </div>
                    <div class="service-block-badges mt-4">
                        <span class="badge bg-purple">Protezione Totale</span>
                        <span class="badge bg-orange">Zero Franchigia</span>
                        <span class="badge bg-green">Attivazione Immediata</span>
                    </div>
                </div>
            </div>
            
            <!-- Consulenza IT & Reti -->
            <div class="service-block mb-5">
                <div class="service-block-header">
                    <div class="service-block-icon bg-blue">
                        <i class="ri-server-line"></i>
                    </div>
                    <div>
                        <h3 class="service-block-title">Consulenza IT & Reti</h3>
                        <p class="service-block-subtitle">Soluzioni professionali per aziende e professionisti</p>
                    </div>
                    <a href="<?php echo url('servizi/consulenza-it.php'); ?>" class="btn btn-outline-blue">
                        Scopri di più <i class="ri-arrow-right-line"></i>
                    </a>
                </div>
                <div class="service-block-content">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="service-feature">
                                <i class="ri-check-line text-green"></i>
                                <span>Progettazione reti aziendali</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="service-feature">
                                <i class="ri-check-line text-green"></i>
                                <span>Sistemi di videosorveglianza EZVIZ</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="service-feature">
                                <i class="ri-check-line text-green"></i>
                                <span>Sicurezza informatica e firewall</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="service-feature">
                                <i class="ri-check-line text-green"></i>
                                <span>Backup e disaster recovery</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="service-feature">
                                <i class="ri-check-line text-green"></i>
                                <span>Virtualizzazione e cloud</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="service-feature">
                                <i class="ri-check-line text-green"></i>
                                <span>Assistenza sistemistica on-site</span>
                            </div>
                        </div>
                    </div>
                    <div class="service-block-badges mt-4">
                        <span class="badge bg-blue">Consulenza Gratuita</span>
                        <span class="badge bg-orange">Supporto 24/7</span>
                        <span class="badge bg-green">Contratti Personalizzati</span>
                    </div>
                </div>
            </div>
            
            <!-- Telefonia & Servizi Casa -->
            <div class="service-block mb-5">
                <div class="service-block-header">
                    <div class="service-block-icon bg-red">
                        <i class="ri-sim-card-line"></i>
                    </div>
                    <div>
                        <h3 class="service-block-title">Telefonia & Servizi Casa</h3>
                        <p class="service-block-subtitle">Le migliori offerte per mobile, internet, luce e gas</p>
                    </div>
                    <a href="<?php echo url('servizi/telefonia.php'); ?>" class="btn btn-outline-red">
                        Scopri di più <i class="ri-arrow-right-line"></i>
                    </a>
                </div>
                <div class="service-block-content">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="service-feature">
                                <i class="ri-check-line text-green"></i>
                                <span>Attivazione SIM e portabilità numero</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="service-feature">
                                <i class="ri-check-line text-green"></i>
                                <span>Offerte internet fibra e ADSL</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="service-feature">
                                <i class="ri-check-line text-green"></i>
                                <span>Luce e gas con i migliori fornitori</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="service-feature">
                                <i class="ri-check-line text-green"></i>
                                <span>Piani business per aziende</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="service-feature">
                                <i class="ri-check-line text-green"></i>
                                <span>Confronto offerte e risparmio garantito</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="service-feature">
                                <i class="ri-check-line text-green"></i>
                                <span>Assistenza cambio operatore</span>
                            </div>
                        </div>
                    </div>
                    <div class="service-block-badges mt-4">
                        <span class="badge bg-red">Tutti gli Operatori</span>
                        <span class="badge bg-green">Zero Costi Attivazione</span>
                        <span class="badge bg-orange">Cashback Disponibile</span>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Why Choose Section -->
    <section class="section section-why bg-light">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Perché scegliere i nostri servizi</h2>
                <p class="section-subtitle">
                    I vantaggi che fanno la differenza
                </p>
            </div>
            
            <div class="row g-4">
                <div class="col-md-6 col-lg-4">
                    <div class="why-card">
                        <div class="why-icon">
                            <i class="ri-user-star-line"></i>
                        </div>
                        <h4 class="why-title">Esperienza Pluriennale</h4>
                        <p class="why-text">
                            Oltre 15 anni di esperienza nel settore tecnologico con migliaia di clienti soddisfatti
                        </p>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-4">
                    <div class="why-card">
                        <div class="why-icon">
                            <i class="ri-team-line"></i>
                        </div>
                        <h4 class="why-title">Tecnici Certificati</h4>
                        <p class="why-text">
                            Team di professionisti certificati e costantemente aggiornati sulle ultime tecnologie
                        </p>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-4">
                    <div class="why-card">
                        <div class="why-icon">
                            <i class="ri-timer-flash-line"></i>
                        </div>
                        <h4 class="why-title">Tempi Rapidi</h4>
                        <p class="why-text">
                            Interventi veloci con servizio express in 24h per le riparazioni più comuni
                        </p>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-4">
                    <div class="why-card">
                        <div class="why-icon">
                            <i class="ri-shield-star-line"></i>
                        </div>
                        <h4 class="why-title">Garanzia Totale</h4>
                        <p class="why-text">
                            12 mesi di garanzia su tutti gli interventi e possibilità di estensione
                        </p>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-4">
                    <div class="why-card">
                        <div class="why-icon">
                            <i class="ri-price-tag-3-line"></i>
                        </div>
                        <h4 class="why-title">Prezzi Trasparenti</h4>
                        <p class="why-text">
                            Preventivi chiari e dettagliati senza costi nascosti o sorprese
                        </p>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-4">
                    <div class="why-card">
                        <div class="why-icon">
                            <i class="ri-customer-service-2-line"></i>
                        </div>
                        <h4 class="why-title">Assistenza Completa</h4>
                        <p class="why-text">
                            Supporto continuo prima, durante e dopo ogni intervento
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Process Section -->
    <section class="section section-process">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Come funziona</h2>
                <p class="section-subtitle">
                    Il nostro processo di lavoro in 4 semplici step
                </p>
            </div>
            
            <div class="process-timeline">
                <div class="row g-4">
                    <div class="col-md-6 col-lg-3">
                        <div class="process-step">
                            <div class="process-number">1</div>
                            <div class="process-icon">
                                <i class="ri-phone-line"></i>
                            </div>
                            <h4 class="process-title">Contatto</h4>
                            <p class="process-text">
                                Contattaci telefonicamente, via WhatsApp o vieni in negozio
                            </p>
                        </div>
                    </div>
                    
                    <div class="col-md-6 col-lg-3">
                        <div class="process-step">
                            <div class="process-number">2</div>
                            <div class="process-icon">
                                <i class="ri-search-eye-line"></i>
                            </div>
                            <h4 class="process-title">Diagnosi</h4>
                            <p class="process-text">
                                Analizziamo il problema e forniamo un preventivo gratuito
                            </p>
                        </div>
                    </div>
                    
                    <div class="col-md-6 col-lg-3">
                        <div class="process-step">
                            <div class="process-number">3</div>
                            <div class="process-icon">
                                <i class="ri-tools-line"></i>
                            </div>
                            <h4 class="process-title">Intervento</h4>
                            <p class="process-text">
                                Eseguiamo l'intervento con professionalità e rapidità
                            </p>
                        </div>
                    </div>
                    
                    <div class="col-md-6 col-lg-3">
                        <div class="process-step">
                            <div class="process-number">4</div>
                            <div class="process-icon">
                                <i class="ri-shield-check-line"></i>
                            </div>
                            <h4 class="process-title">Garanzia</h4>
                            <p class="process-text">
                                Ti garantiamo il lavoro svolto per 12 mesi completi
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- CTA Section -->
    <section class="section section-cta bg-gradient-orange text-white">
        <div class="container">
            <div class="text-center">
                <h2 class="cta-title">Hai bisogno di assistenza?</h2>
                <p class="cta-subtitle mb-5">
                    Contattaci ora per un preventivo gratuito e senza impegno
                </p>
                <div class="cta-buttons">
                    <a href="<?php echo url('preventivo.php'); ?>" class="btn btn-white btn-lg me-3">
                        <i class="ri-file-list-3-line"></i> Richiedi Preventivo
                    </a>
                    <a href="<?php echo url('contatti.php'); ?>" class="btn btn-outline-white btn-lg">
                        <i class="ri-phone-line"></i> Contattaci Ora
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
    
    <!-- Set BASE_URL for JavaScript -->
    <script>
        window.KS_CONFIG = {
            baseUrl: '<?php echo BASE_URL; ?>',
            whatsappNumber: '<?php echo WHATSAPP_NUMBER; ?>'
        };
    </script>
</body>
</html>