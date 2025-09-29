<?php
/**
 * Key Soft Italia - Sviluppo Software
 * Pagina dettaglio servizi di sviluppo software e web
 */

// Define BASE_PATH if not defined
if (!defined('BASE_PATH')) {
    define('BASE_PATH', dirname(__DIR__) . '/');
}

require_once BASE_PATH . 'config/config.php';

// SEO Meta
$page_title = "Sviluppo Software e Web - Key Soft Italia | Soluzioni Digitali Personalizzate";
$page_description = "Sviluppo software personalizzato, siti web, e-commerce e app mobile a Ginosa. Soluzioni digitali su misura per la tua azienda.";
$page_keywords = "sviluppo software ginosa, creazione siti web, e-commerce, app mobile, web development taranto";

// Breadcrumbs
$breadcrumbs = [
    ['label' => 'Servizi', 'url' => '../servizi.php'],
    ['label' => 'Sviluppo Software', 'url' => 'sviluppo.php']
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
        'url' => url('servizi/sviluppo.php')
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
    <link rel="stylesheet" href="<?php echo asset('css/pages/servizi-detail.css'); ?>">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?php echo asset('images/favicon.ico'); ?>">
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
    <section class="hero hero-secondary hero-service">
        <div class="container">
            <div class="hero-content text-center">
                <div class="hero-icon">
                    <i class="ri-code-s-slash-line"></i>
                </div>
                <h1 class="hero-title animate-fadeIn">Sviluppo Software</h1>
                <p class="hero-subtitle animate-fadeIn">
                    Soluzioni digitali personalizzate per far crescere il tuo business
                </p>
                <?php echo generate_breadcrumbs($breadcrumbs); ?>
            </div>
        </div>
    </section>
    
    <!-- Services Overview -->
    <section class="section section-services">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">I Nostri Servizi di Sviluppo</h2>
                <p class="section-subtitle">
                    Dalla progettazione alla realizzazione, creiamo soluzioni su misura
                </p>
            </div>
            
            <div class="row g-4">
                <div class="col-lg-6">
                    <div class="service-detail-card">
                        <div class="service-icon">
                            <i class="ri-global-line"></i>
                        </div>
                        <div class="service-content">
                            <h3>Sviluppo Siti Web</h3>
                            <p>Siti web professionali, responsive e ottimizzati SEO per la tua presenza online</p>
                            <ul class="service-features">
                                <li>Design personalizzato e responsive</li>
                                <li>Ottimizzazione SEO completa</li>
                                <li>CMS facile da gestire</li>
                                <li>Integrazione social media</li>
                                <li>Analytics e monitoraggio</li>
                            </ul>
                            <div class="service-price">A partire da €799</div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-6">
                    <div class="service-detail-card">
                        <div class="service-icon">
                            <i class="ri-shopping-cart-2-line"></i>
                        </div>
                        <div class="service-content">
                            <h3>E-Commerce</h3>
                            <p>Negozi online completi con gestione prodotti, pagamenti e spedizioni</p>
                            <ul class="service-features">
                                <li>Catalogo prodotti illimitato</li>
                                <li>Pagamenti sicuri (PayPal, Stripe, etc.)</li>
                                <li>Gestione magazzino</li>
                                <li>Calcolo spedizioni automatico</li>
                                <li>Dashboard vendite</li>
                            </ul>
                            <div class="service-price">A partire da €1.499</div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-6">
                    <div class="service-detail-card">
                        <div class="service-icon">
                            <i class="ri-smartphone-line"></i>
                        </div>
                        <div class="service-content">
                            <h3>App Mobile</h3>
                            <p>Applicazioni native e ibride per iOS e Android</p>
                            <ul class="service-features">
                                <li>App native iOS e Android</li>
                                <li>App ibride cross-platform</li>
                                <li>Integrazione con backend</li>
                                <li>Push notifications</li>
                                <li>Pubblicazione su store</li>
                            </ul>
                            <div class="service-price">A partire da €2.999</div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-6">
                    <div class="service-detail-card">
                        <div class="service-icon">
                            <i class="ri-settings-3-line"></i>
                        </div>
                        <div class="service-content">
                            <h3>Software Gestionale</h3>
                            <p>Soluzioni personalizzate per la gestione aziendale</p>
                            <ul class="service-features">
                                <li>Gestione clienti e fornitori</li>
                                <li>Fatturazione elettronica</li>
                                <li>Gestione magazzino</li>
                                <li>Report e statistiche</li>
                                <li>Multi-utente con permessi</li>
                            </ul>
                            <div class="service-price">Preventivo personalizzato</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Technologies -->
    <section class="section section-technologies bg-light">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Tecnologie Utilizzate</h2>
                <p class="section-subtitle">
                    Lavoriamo con le tecnologie più moderne e affidabili
                </p>
            </div>
            
            <div class="technologies-grid">
                <div class="tech-item">
                    <div class="tech-icon">
                        <i class="ri-html5-fill"></i>
                    </div>
                    <span>HTML5</span>
                </div>
                <div class="tech-item">
                    <div class="tech-icon">
                        <i class="ri-css3-fill"></i>
                    </div>
                    <span>CSS3</span>
                </div>
                <div class="tech-item">
                    <div class="tech-icon">
                        <i class="ri-javascript-fill"></i>
                    </div>
                    <span>JavaScript</span>
                </div>
                <div class="tech-item">
                    <div class="tech-icon">
                        <i class="ri-reactjs-line"></i>
                    </div>
                    <span>React</span>
                </div>
                <div class="tech-item">
                    <div class="tech-icon">
                        <i class="ri-vuejs-line"></i>
                    </div>
                    <span>Vue.js</span>
                </div>
                <div class="tech-item">
                    <div class="tech-icon">
                        <i class="ri-github-fill"></i>
                    </div>
                    <span>PHP</span>
                </div>
                <div class="tech-item">
                    <div class="tech-icon">
                        <i class="ri-database-2-line"></i>
                    </div>
                    <span>MySQL</span>
                </div>
                <div class="tech-item">
                    <div class="tech-icon">
                        <i class="ri-wordpress-fill"></i>
                    </div>
                    <span>WordPress</span>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Process -->
    <section class="section section-process">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Il Nostro Processo</h2>
                <p class="section-subtitle">
                    Dalla prima idea al prodotto finito, seguiamo ogni fase con attenzione
                </p>
            </div>
            
            <div class="process-timeline">
                <div class="process-step">
                    <div class="process-number">1</div>
                    <div class="process-content">
                        <h3>Analisi e Consulenza</h3>
                        <p>Analizziamo le tue esigenze e definiamo obiettivi e requisiti del progetto</p>
                    </div>
                </div>
                
                <div class="process-step">
                    <div class="process-number">2</div>
                    <div class="process-content">
                        <h3>Progettazione</h3>
                        <p>Creiamo wireframe, mockup e architettura del sistema</p>
                    </div>
                </div>
                
                <div class="process-step">
                    <div class="process-number">3</div>
                    <div class="process-content">
                        <h3>Sviluppo</h3>
                        <p>Sviluppiamo il software seguendo le best practice e gli standard di qualità</p>
                    </div>
                </div>
                
                <div class="process-step">
                    <div class="process-number">4</div>
                    <div class="process-content">
                        <h3>Testing</h3>
                        <p>Test approfonditi per garantire funzionalità, sicurezza e performance</p>
                    </div>
                </div>
                
                <div class="process-step">
                    <div class="process-number">5</div>
                    <div class="process-content">
                        <h3>Deployment</h3>
                        <p>Rilascio del prodotto e formazione del personale</p>
                    </div>
                </div>
                
                <div class="process-step">
                    <div class="process-number">6</div>
                    <div class="process-content">
                        <h3>Manutenzione</h3>
                        <p>Supporto continuo, aggiornamenti e miglioramenti</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Portfolio Preview -->
    <section class="section section-portfolio bg-light">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">I Nostri Lavori Recenti</h2>
                <p class="section-subtitle">
                    Alcuni esempi di progetti realizzati per i nostri clienti
                </p>
            </div>
            
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="portfolio-card">
                        <div class="portfolio-image">
                            <div class="portfolio-placeholder">
                                <i class="ri-store-2-line"></i>
                            </div>
                        </div>
                        <div class="portfolio-content">
                            <h4>E-Commerce Fashion</h4>
                            <p>Negozio online per boutique di moda con oltre 500 prodotti</p>
                            <div class="portfolio-tags">
                                <span class="tag">WooCommerce</span>
                                <span class="tag">Responsive</span>
                                <span class="tag">SEO</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="portfolio-card">
                        <div class="portfolio-image">
                            <div class="portfolio-placeholder">
                                <i class="ri-restaurant-line"></i>
                            </div>
                        </div>
                        <div class="portfolio-content">
                            <h4>App Ristorante</h4>
                            <p>App mobile per ordinazioni e prenotazioni tavoli</p>
                            <div class="portfolio-tags">
                                <span class="tag">React Native</span>
                                <span class="tag">iOS/Android</span>
                                <span class="tag">API</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="portfolio-card">
                        <div class="portfolio-image">
                            <div class="portfolio-placeholder">
                                <i class="ri-building-2-line"></i>
                            </div>
                        </div>
                        <div class="portfolio-content">
                            <h4>Gestionale Azienda</h4>
                            <p>Software personalizzato per gestione commesse e fatturazione</p>
                            <div class="portfolio-tags">
                                <span class="tag">PHP</span>
                                <span class="tag">MySQL</span>
                                <span class="tag">Dashboard</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- CTA Section -->
    <section class="section section-cta bg-gradient-primary text-white">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h2 class="cta-title">Hai un Progetto in Mente?</h2>
                    <p class="cta-text">
                        Parliamone insieme! Offriamo consulenza gratuita per valutare 
                        la fattibilità e i costi del tuo progetto.
                    </p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <a href="<?php echo url('preventivo.php'); ?>" class="btn btn-white btn-lg">
                        <i class="ri-file-list-3-line"></i> Richiedi Preventivo
                    </a>
                </div>
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