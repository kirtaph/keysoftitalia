<?php
/**
 * Key Soft Italia - Servizio Vendita
 * Pagina dettaglio servizi di vendita hardware e accessori
 */

// Define BASE_PATH if not defined
if (!defined('BASE_PATH')) {
    define('BASE_PATH', dirname(__DIR__) . '/');
}

require_once BASE_PATH . 'config/config.php';

// SEO Meta
$page_title = "Vendita Computer e Accessori - Key Soft Italia | Prodotti Ricondizionati Garantiti";
$page_description = "Vendita computer, notebook, smartphone ricondizionati e accessori a Ginosa. Prodotti garantiti con risparmio fino al 40%. Consulenza e assistenza post-vendita.";
$page_keywords = "vendita computer ginosa, notebook ricondizionati, smartphone usati garantiti, accessori informatica";

// Breadcrumbs
$breadcrumbs = [
    ['label' => 'Servizi', 'url' => '../servizi.php'],
    ['label' => 'Vendita', 'url' => 'vendita.php']
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
        'url' => url('servizi/vendita.php')
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
                    <i class="ri-shopping-cart-line"></i>
                </div>
                <h1 class="hero-title animate-fadeIn">Vendita Hardware e Ricondizionati</h1>
                <p class="hero-subtitle animate-fadeIn">
                    Prodotti di qualità garantita con risparmio fino al 40%
                </p>
                <?php echo generate_breadcrumbs($breadcrumbs); ?>
            </div>
        </div>
    </section>
    
    <!-- Product Categories -->
    <section class="section section-categories">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Le Nostre Categorie</h2>
                <p class="section-subtitle">
                    Ampia selezione di prodotti nuovi e ricondizionati
                </p>
            </div>
            
            <div class="row g-4">
                <div class="col-lg-3 col-md-6">
                    <div class="category-card">
                        <div class="category-icon">
                            <i class="ri-computer-line"></i>
                        </div>
                        <h3 class="category-title">Computer Desktop</h3>
                        <p class="category-description">PC fissi per casa e ufficio</p>
                        <ul class="category-list">
                            <li>PC Gaming</li>
                            <li>Workstation</li>
                            <li>PC Office</li>
                            <li>All-in-One</li>
                        </ul>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <div class="category-card">
                        <div class="category-icon">
                            <i class="ri-macbook-line"></i>
                        </div>
                        <h3 class="category-title">Notebook</h3>
                        <p class="category-description">Portatili per ogni esigenza</p>
                        <ul class="category-list">
                            <li>Ultrabook</li>
                            <li>Gaming Laptop</li>
                            <li>Business</li>
                            <li>Chromebook</li>
                        </ul>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <div class="category-card">
                        <div class="category-icon">
                            <i class="ri-smartphone-line"></i>
                        </div>
                        <h3 class="category-title">Smartphone & Tablet</h3>
                        <p class="category-description">Dispositivi mobili ricondizionati</p>
                        <ul class="category-list">
                            <li>iPhone</li>
                            <li>Samsung Galaxy</li>
                            <li>iPad</li>
                            <li>Android Tablet</li>
                        </ul>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <div class="category-card">
                        <div class="category-icon">
                            <i class="ri-headphone-line"></i>
                        </div>
                        <h3 class="category-title">Accessori</h3>
                        <p class="category-description">Tutto per il tuo setup</p>
                        <ul class="category-list">
                            <li>Monitor</li>
                            <li>Tastiere & Mouse</li>
                            <li>Cuffie & Speaker</li>
                            <li>Storage</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Featured Products -->
    <section class="section section-featured bg-light">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Prodotti in Evidenza</h2>
                <p class="section-subtitle">
                    Le migliori offerte del momento
                </p>
            </div>
            
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="product-card">
                        <div class="product-badge">-30%</div>
                        <div class="product-image">
                            <i class="ri-macbook-line"></i>
                        </div>
                        <div class="product-content">
                            <h4 class="product-title">MacBook Air M1</h4>
                            <p class="product-specs">8GB RAM, 256GB SSD, Space Gray</p>
                            <div class="product-condition">
                                <span class="badge bg-success">Ricondizionato Grade A</span>
                            </div>
                            <div class="product-price">
                                <span class="price-old">€1.299</span>
                                <span class="price-current">€899</span>
                            </div>
                            <div class="product-warranty">
                                <i class="ri-shield-check-line"></i> Garanzia 12 mesi
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="product-card">
                        <div class="product-badge">-25%</div>
                        <div class="product-image">
                            <i class="ri-smartphone-line"></i>
                        </div>
                        <div class="product-content">
                            <h4 class="product-title">iPhone 13 Pro</h4>
                            <p class="product-specs">128GB, Graphite, Dual SIM</p>
                            <div class="product-condition">
                                <span class="badge bg-success">Ricondizionato Grade A</span>
                            </div>
                            <div class="product-price">
                                <span class="price-old">€999</span>
                                <span class="price-current">€749</span>
                            </div>
                            <div class="product-warranty">
                                <i class="ri-shield-check-line"></i> Garanzia 12 mesi
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="product-card">
                        <div class="product-badge">Nuovo</div>
                        <div class="product-image">
                            <i class="ri-computer-line"></i>
                        </div>
                        <div class="product-content">
                            <h4 class="product-title">Gaming PC Custom</h4>
                            <p class="product-specs">RTX 4060, i5-13400F, 16GB RAM</p>
                            <div class="product-condition">
                                <span class="badge bg-primary">Nuovo</span>
                            </div>
                            <div class="product-price">
                                <span class="price-current">€1.199</span>
                            </div>
                            <div class="product-warranty">
                                <i class="ri-shield-check-line"></i> Garanzia 24 mesi
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="text-center mt-5">
                <a href="<?php echo url('ricondizionati.php'); ?>" class="btn btn-primary btn-lg">
                    <i class="ri-shopping-bag-line"></i> Vedi Tutti i Prodotti
                </a>
            </div>
        </div>
    </section>
    
    <!-- Why Choose Us -->
    <section class="section section-benefits">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Perché Scegliere i Nostri Prodotti</h2>
                <p class="section-subtitle">
                    Qualità, convenienza e assistenza garantite
                </p>
            </div>
            
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="benefit-card">
                        <div class="benefit-icon">
                            <i class="ri-verified-badge-line"></i>
                        </div>
                        <h3 class="benefit-title">Qualità Certificata</h3>
                        <p class="benefit-text">
                            Tutti i prodotti ricondizionati sono testati e certificati 
                            secondo rigorosi standard di qualità
                        </p>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="benefit-card">
                        <div class="benefit-icon">
                            <i class="ri-percent-line"></i>
                        </div>
                        <h3 class="benefit-title">Risparmio Garantito</h3>
                        <p class="benefit-text">
                            Risparmia fino al 40% rispetto al nuovo con la stessa 
                            affidabilità e garanzia
                        </p>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="benefit-card">
                        <div class="benefit-icon">
                            <i class="ri-shield-star-line"></i>
                        </div>
                        <h3 class="benefit-title">Garanzia Estesa</h3>
                        <p class="benefit-text">
                            12-24 mesi di garanzia su tutti i prodotti con possibilità 
                            di estensione fino a 36 mesi
                        </p>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="benefit-card">
                        <div class="benefit-icon">
                            <i class="ri-customer-service-2-line"></i>
                        </div>
                        <h3 class="benefit-title">Assistenza Dedicata</h3>
                        <p class="benefit-text">
                            Supporto tecnico pre e post vendita con tecnici 
                            qualificati sempre a disposizione
                        </p>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="benefit-card">
                        <div class="benefit-icon">
                            <i class="ri-truck-line"></i>
                        </div>
                        <h3 class="benefit-title">Consegna Rapida</h3>
                        <p class="benefit-text">
                            Consegna in 24-48 ore in tutta la provincia. 
                            Ritiro in negozio immediato
                        </p>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="benefit-card">
                        <div class="benefit-icon">
                            <i class="ri-loop-left-line"></i>
                        </div>
                        <h3 class="benefit-title">Reso Facile</h3>
                        <p class="benefit-text">
                            14 giorni per il reso senza domande. 
                            Soddisfatti o rimborsati al 100%
                        </p>
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
                    <h2 class="cta-title">Cerchi un Prodotto Specifico?</h2>
                    <p class="cta-text">
                        Contattaci per verificare disponibilità e prezzi. 
                        Possiamo procurare qualsiasi prodotto su richiesta!
                    </p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <a href="<?php echo whatsapp_link('Salve, vorrei informazioni sulla disponibilità di un prodotto'); ?>" 
                       class="btn btn-white btn-lg">
                        <i class="ri-whatsapp-line"></i> Contatta su WhatsApp
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