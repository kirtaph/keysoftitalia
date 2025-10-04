<?php
/**
 * Key Soft Italia - Servizi Riparazione
 * Pagina dettaglio servizi di riparazione
 */

// Define BASE_PATH if not defined
if (!defined('BASE_PATH')) {
    define('BASE_PATH', dirname(__DIR__) . '/');
}

require_once BASE_PATH . 'config/config.php';

// SEO Meta
$page_title = "Servizi Riparazione Computer e Smartphone - Key Soft Italia";
$page_description = "Riparazione professionale di computer, notebook, smartphone e tablet a Ginosa. Interventi rapidi e garantiti con componenti originali.";
$page_keywords = "riparazione computer ginosa, riparazione smartphone, assistenza notebook, riparazione tablet";

// Breadcrumbs
$breadcrumbs = [
    ['label' => 'Servizi', 'url' => '../servizi.php'],
    ['label' => 'Riparazioni', 'url' => 'riparazioni.php']
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
        'url' => url('servizi/riparazioni.php')
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
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?php echo asset('images/favicon.ico'); ?>">
    <style>
        .service-hero {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 100px 0;
            color: white;
            position: relative;
            overflow: hidden;
        }
        
        .service-hero::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: pulse 15s ease-in-out infinite;
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }
        
        .service-category {
            padding: 60px 0;
        }
        
        .service-category:nth-child(even) {
            background: #f8f9fa;
        }
        
        .service-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            height: 100%;
        }
        
        .service-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }
        
        .service-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
        }
        
        .service-icon i {
            font-size: 2rem;
            color: white;
        }
        
        .price-badge {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 5px 15px;
            border-radius: 25px;
            display: inline-block;
            font-weight: 600;
        }
        
        .warranty-banner {
            background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
            color: white;
            padding: 40px;
            border-radius: 15px;
            margin: 40px 0;
            text-align: center;
        }
        
        .process-timeline {
            position: relative;
            padding: 40px 0;
        }
        
        .process-step {
            display: flex;
            align-items: center;
            margin-bottom: 40px;
            position: relative;
        }
        
        .process-step::after {
            content: '';
            position: absolute;
            left: 40px;
            top: 80px;
            width: 2px;
            height: calc(100% + 20px);
            background: #e2e8f0;
        }
        
        .process-step:last-child::after {
            display: none;
        }
        
        .process-number {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2rem;
            font-weight: bold;
            flex-shrink: 0;
            z-index: 1;
        }
        
        .process-content {
            margin-left: 30px;
            flex: 1;
        }
        
        .cta-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 60px 0;
            text-align: center;
        }
    </style>
</head>
<body>
    
    <!-- Header -->
    <?php 
    // Temporarily change dir for include
    $original_dir = getcwd();
    chdir('..');
    include 'includes/header.php'; 
    chdir($original_dir);
    ?>

    <!-- Hero Section -->
    <section class="service-hero">
        <div class="container position-relative">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?php echo url(''); ?>" style="color: white;">Home</a></li>
                            <li class="breadcrumb-item"><a href="<?php echo url('servizi.php'); ?>" style="color: white;">Servizi</a></li>
                            <li class="breadcrumb-item active" aria-current="page" style="color: white;">Riparazioni</li>
                        </ol>
                    </nav>
                    <h1 class="display-4 fw-bold mb-4">Servizi di Riparazione</h1>
                    <p class="lead mb-4">Riparazioni professionali per tutti i dispositivi elettronici con garanzia e componenti originali</p>
                    <div class="d-flex gap-4 flex-wrap">
                        <div>
                            <i class="ri-shield-check-line"></i> Garanzia 12 Mesi
                        </div>
                        <div>
                            <i class="ri-time-line"></i> Riparazione Express
                        </div>
                        <div>
                            <i class="ri-tools-line"></i> Tecnici Certificati
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Computer & Notebook -->
    <section class="service-category">
        <div class="container">
            <h2 class="text-center mb-5">Riparazione Computer e Notebook</h2>
            
            <div class="row g-4">
                <div class="col-md-6 col-lg-4">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="ri-computer-line"></i>
                        </div>
                        <h4>Sostituzione Hardware</h4>
                        <p>RAM, hard disk, SSD, schede video, alimentatori, schede madri</p>
                        <div class="price-badge">Da €30</div>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-4">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="ri-bug-line"></i>
                        </div>
                        <h4>Rimozione Virus</h4>
                        <p>Pulizia completa da virus, malware, spyware e ottimizzazione sistema</p>
                        <div class="price-badge">€40</div>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-4">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="ri-install-line"></i>
                        </div>
                        <h4>Formattazione e Ripristino</h4>
                        <p>Installazione sistema operativo, driver, programmi essenziali</p>
                        <div class="price-badge">€50</div>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-4">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="ri-macbook-line"></i>
                        </div>
                        <h4>Riparazione Notebook</h4>
                        <p>Sostituzione schermo, tastiera, batteria, cerniere, ventole</p>
                        <div class="price-badge">Da €60</div>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-4">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="ri-database-2-line"></i>
                        </div>
                        <h4>Recupero Dati</h4>
                        <p>Recupero file da hard disk danneggiati o formattati accidentalmente</p>
                        <div class="price-badge">Da €100</div>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-4">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="ri-speed-line"></i>
                        </div>
                        <h4>Upgrade e Ottimizzazione</h4>
                        <p>Potenziamento hardware e ottimizzazione prestazioni</p>
                        <div class="price-badge">Da €35</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Smartphone & Tablet -->
    <section class="service-category">
        <div class="container">
            <h2 class="text-center mb-5">Riparazione Smartphone e Tablet</h2>
            
            <div class="row g-4">
                <div class="col-md-6 col-lg-4">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="ri-smartphone-line"></i>
                        </div>
                        <h4>Sostituzione Display</h4>
                        <p>Schermo rotto, touch non funzionante, problemi di visualizzazione</p>
                        <div class="price-badge">Da €80</div>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-4">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="ri-battery-charge-line"></i>
                        </div>
                        <h4>Sostituzione Batteria</h4>
                        <p>Batteria che non tiene la carica, surriscaldamento, spegnimenti</p>
                        <div class="price-badge">Da €40</div>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-4">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="ri-camera-lens-line"></i>
                        </div>
                        <h4>Riparazione Fotocamera</h4>
                        <p>Fotocamera anteriore/posteriore, vetro camera rotto</p>
                        <div class="price-badge">Da €50</div>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-4">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="ri-volume-up-line"></i>
                        </div>
                        <h4>Speaker e Microfono</h4>
                        <p>Audio assente, microfono non funzionante, vivavoce</p>
                        <div class="price-badge">Da €35</div>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-4">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="ri-usb-line"></i>
                        </div>
                        <h4>Porta di Ricarica</h4>
                        <p>Problemi di ricarica, connettore danneggiato</p>
                        <div class="price-badge">Da €45</div>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-4">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="ri-drop-line"></i>
                        </div>
                        <h4>Danni da Liquidi</h4>
                        <p>Pulizia e riparazione dispositivi bagnati</p>
                        <div class="price-badge">Da €70</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Warranty Banner -->
    <div class="container">
        <div class="warranty-banner">
            <h2 class="mb-3">
                <i class="ri-shield-check-line"></i> Garanzia su Tutte le Riparazioni
            </h2>
            <p class="lead mb-4">Ogni riparazione è coperta da garanzia di 12 mesi su ricambi e manodopera</p>
            <div class="row justify-content-center">
                <div class="col-md-3">
                    <h3>100%</h3>
                    <p>Componenti Originali</p>
                </div>
                <div class="col-md-3">
                    <h3>12 Mesi</h3>
                    <p>Garanzia Standard</p>
                </div>
                <div class="col-md-3">
                    <h3>24/48h</h3>
                    <p>Tempi di Riparazione</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Process Timeline -->
    <section class="service-category">
        <div class="container">
            <h2 class="text-center mb-5">Come Funziona il Servizio</h2>
            
            <div class="process-timeline">
                <div class="process-step">
                    <div class="process-number">1</div>
                    <div class="process-content">
                        <h4>Diagnosi Gratuita</h4>
                        <p>Analizziamo il problema e forniamo un preventivo dettagliato senza impegno</p>
                    </div>
                </div>
                
                <div class="process-step">
                    <div class="process-number">2</div>
                    <div class="process-content">
                        <h4>Accettazione Preventivo</h4>
                        <p>Dopo la tua approvazione, ordiniamo i ricambi necessari</p>
                    </div>
                </div>
                
                <div class="process-step">
                    <div class="process-number">3</div>
                    <div class="process-content">
                        <h4>Riparazione</h4>
                        <p>I nostri tecnici certificati eseguono la riparazione con cura</p>
                    </div>
                </div>
                
                <div class="process-step">
                    <div class="process-number">4</div>
                    <div class="process-content">
                        <h4>Test e Consegna</h4>
                        <p>Testiamo il dispositivo e lo consegniamo perfettamente funzionante</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <h2 class="mb-4">Hai Bisogno di una Riparazione?</h2>
            <p class="lead mb-5">Contattaci subito per un preventivo gratuito e senza impegno</p>
            <div class="d-flex gap-3 justify-content-center flex-wrap">
                <a href="<?php echo url('preventivo.php'); ?>" class="btn btn-light btn-lg">
                    <i class="ri-file-list-3-line"></i> Richiedi Preventivo
                </a>
                <a href="<?php echo url('assistenza.php'); ?>" class="btn btn-outline-light btn-lg">
                    <i class="ri-customer-service-2-line"></i> Assistenza Online
                </a>
                <a href="<?php echo whatsapp_link('Salve, avrei bisogno di una riparazione'); ?>" 
                   class="btn btn-success btn-lg" target="_blank">
                    <i class="ri-whatsapp-line"></i> WhatsApp
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php 
    // Temporarily change dir for include
    $original_dir = getcwd();
    chdir('..');
    include 'includes/footer.php';
    chdir($original_dir);
    ?>

    <!-- Bootstrap JS -->
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