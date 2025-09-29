<?php
/**
 * Key Soft Italia - Dispositivi Ricondizionati
 * Catalogo prodotti ricondizionati con filtri
 */

// Define BASE_PATH if not defined
if (!defined('BASE_PATH')) {
    define('BASE_PATH', __DIR__ . '/');
}

require_once BASE_PATH . 'config/config.php';

// SEO Meta
$page_title = "Dispositivi Ricondizionati - Key Soft Italia | Smartphone, Tablet, Laptop Garantiti";
$page_description = "Acquista dispositivi ricondizionati certificati con garanzia 12 mesi. iPhone, Samsung, iPad e laptop a prezzi convenienti. Spedizione gratuita da Key Soft Italia.";
$page_keywords = "ricondizionati ginosa, iphone ricondizionato, samsung usato garantito, ipad ricondizionato, laptop usati";

// Breadcrumbs
$breadcrumbs = [
    ['label' => 'Ricondizionati', 'url' => 'ricondizionati.php']
];

// Sample products data (in produzione questi dati verranno dal database)
$products = [
    [
        'id' => 1,
        'title' => 'iPhone 13 Pro 128GB',
        'brand' => 'Apple',
        'category' => 'smartphone',
        'condition' => 'A+',
        'price' => 799,
        'price_old' => 999,
        'warranty' => 12,
        'badge' => 'Best Seller',
        'image' => 'https://via.placeholder.com/300x300/f8f9fa/6c757d?text=iPhone+13+Pro',
        'highlights' => ['Display Super Retina XDR', 'Tripla fotocamera 12MP', 'Chip A15 Bionic', 'Batteria nuova']
    ],
    [
        'id' => 2,
        'title' => 'Samsung Galaxy S22 Ultra 256GB',
        'brand' => 'Samsung',
        'category' => 'smartphone',
        'condition' => 'A',
        'price' => 699,
        'price_old' => 899,
        'warranty' => 12,
        'badge' => 'Offerta',
        'image' => 'https://via.placeholder.com/300x300/f8f9fa/6c757d?text=Galaxy+S22',
        'highlights' => ['Display Dynamic AMOLED 2X', 'S Pen inclusa', '108MP Camera', '5G Ready']
    ],
    [
        'id' => 3,
        'title' => 'iPad Air 5 64GB Wi-Fi',
        'brand' => 'Apple',
        'category' => 'tablet',
        'condition' => 'A+',
        'price' => 549,
        'price_old' => 699,
        'warranty' => 12,
        'badge' => 'Nuovo Arrivo',
        'image' => 'https://via.placeholder.com/300x300/f8f9fa/6c757d?text=iPad+Air+5',
        'highlights' => ['Display Liquid Retina 10.9"', 'Chip M1', 'Touch ID', 'Apple Pencil compatibile']
    ],
    [
        'id' => 4,
        'title' => 'MacBook Air M1 256GB',
        'brand' => 'Apple',
        'category' => 'laptop',
        'condition' => 'A',
        'price' => 899,
        'price_old' => 1149,
        'warranty' => 12,
        'badge' => null,
        'image' => 'https://via.placeholder.com/300x300/f8f9fa/6c757d?text=MacBook+Air',
        'highlights' => ['Chip Apple M1', 'Display Retina 13.3"', '18 ore di autonomia', 'SSD veloce']
    ],
    [
        'id' => 5,
        'title' => 'Samsung Galaxy Tab S8 128GB',
        'brand' => 'Samsung',
        'category' => 'tablet',
        'condition' => 'B',
        'price' => 449,
        'price_old' => 599,
        'warranty' => 12,
        'badge' => 'Super Prezzo',
        'image' => 'https://via.placeholder.com/300x300/f8f9fa/6c757d?text=Tab+S8',
        'highlights' => ['Display 11" 120Hz', 'S Pen inclusa', 'Snapdragon 8 Gen 1', 'DeX Mode']
    ],
    [
        'id' => 6,
        'title' => 'iPhone 12 64GB',
        'brand' => 'Apple',
        'category' => 'smartphone',
        'condition' => 'A',
        'price' => 499,
        'price_old' => 649,
        'warranty' => 12,
        'badge' => null,
        'image' => 'https://via.placeholder.com/300x300/f8f9fa/6c757d?text=iPhone+12',
        'highlights' => ['Display OLED 6.1"', 'Doppia fotocamera', '5G', 'Ceramic Shield']
    ]
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
        'url' => url('ricondizionati.php')
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
    <link rel="stylesheet" href="<?php echo asset('css/pages/ricondizionati.css'); ?>">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?php echo asset('images/favicon.ico'); ?>">
</head>
<body>
    
    <!-- Header -->
    <?php include 'includes/header.php'; ?>
    
    <!-- Hero Section -->
    <section class="hero hero-ricondizionati">
        <div class="container">
            <div class="hero-content text-center">
                <h1 class="hero-title text-white">Dispositivi Ricondizionati</h1>
                <p class="hero-subtitle">
                    Risparmia fino al 40% su dispositivi certificati e garantiti 12 mesi
                </p>
                <div class="hero-features">
                    <div class="hero-feature">
                        <i class="ri-shield-check-line"></i>
                        <span>Garanzia 12 mesi</span>
                    </div>
                    <div class="hero-feature">
                        <i class="ri-truck-line"></i>
                        <span>Spedizione Gratuita</span>
                    </div>
                    <div class="hero-feature">
                        <i class="ri-exchange-line"></i>
                        <span>Reso Facile</span>
                    </div>
                    <div class="hero-feature">
                        <i class="ri-secure-payment-line"></i>
                        <span>Pagamento Sicuro</span>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Filters Section -->
    <section class="section-filters">
        <div class="container">
            <div class="filters-wrapper">
                <div class="filters-header">
                    <h5 class="filters-title">
                        <i class="ri-filter-3-line"></i> Filtra Prodotti
                    </h5>
                    <button class="btn btn-sm btn-outline-secondary d-md-none" id="toggleFilters">
                        <i class="ri-equalizer-line"></i> Mostra Filtri
                    </button>
                </div>
                
                <div class="filters-content" id="filtersContent">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <div class="filter-group">
                                <label class="filter-label">Categoria</label>
                                <select class="form-select" id="filterCategory">
                                    <option value="">Tutte le categorie</option>
                                    <option value="smartphone">Smartphone</option>
                                    <option value="tablet">Tablet</option>
                                    <option value="laptop">Laptop</option>
                                    <option value="desktop">Desktop</option>
                                    <option value="smartwatch">Smartwatch</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="filter-group">
                                <label class="filter-label">Marca</label>
                                <select class="form-select" id="filterBrand">
                                    <option value="">Tutte le marche</option>
                                    <option value="apple">Apple</option>
                                    <option value="samsung">Samsung</option>
                                    <option value="xiaomi">Xiaomi</option>
                                    <option value="huawei">Huawei</option>
                                    <option value="microsoft">Microsoft</option>
                                    <option value="lenovo">Lenovo</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="filter-group">
                                <label class="filter-label">Prezzo</label>
                                <select class="form-select" id="filterPrice">
                                    <option value="">Tutti i prezzi</option>
                                    <option value="0-300">Fino a €300</option>
                                    <option value="300-500">€300 - €500</option>
                                    <option value="500-800">€500 - €800</option>
                                    <option value="800+">Oltre €800</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="filter-group">
                                <label class="filter-label">Condizioni</label>
                                <select class="form-select" id="filterCondition">
                                    <option value="">Tutte le condizioni</option>
                                    <option value="A+">A+ Come Nuovo</option>
                                    <option value="A">A - Ottimo</option>
                                    <option value="B">B - Buono</option>
                                    <option value="C">C - Discreto</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="filters-actions mt-3">
                        <button class="btn btn-primary" id="applyFilters">
                            <i class="ri-check-line"></i> Applica Filtri
                        </button>
                        <button class="btn btn-outline-secondary" id="resetFilters">
                            <i class="ri-refresh-line"></i> Reset
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Products Section -->
    <section class="section section-products">
        <div class="container">
            <!-- Results Header -->
            <div class="results-header">
                <div class="results-count">
                    <span class="text-muted">Trovati</span>
                    <strong><?php echo count($products); ?> prodotti</strong>
                </div>
                <div class="results-sort">
                    <label class="me-2">Ordina per:</label>
                    <select class="form-select form-select-sm" style="width: auto;">
                        <option value="featured">In evidenza</option>
                        <option value="price-asc">Prezzo crescente</option>
                        <option value="price-desc">Prezzo decrescente</option>
                        <option value="newest">Più recenti</option>
                    </select>
                </div>
            </div>
            
            <!-- Products Grid -->
            <div class="products-grid">
                <div class="row g-4">
                    <?php foreach ($products as $product): ?>
                    <div class="col-lg-4 col-md-6">
                        <div class="product-card">
                            <?php if ($product['badge']): ?>
                            <div class="product-badge"><?php echo $product['badge']; ?></div>
                            <?php endif; ?>
                            
                            <div class="product-image">
                                <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['title']; ?>" loading="lazy">
                                <div class="product-condition">
                                    <span class="condition-badge condition-<?php echo strtolower(str_replace('+', 'plus', $product['condition'])); ?>">
                                        Grado <?php echo $product['condition']; ?>
                                    </span>
                                </div>
                            </div>
                            
                            <div class="product-content">
                                <div class="product-brand"><?php echo $product['brand']; ?></div>
                                <h3 class="product-title"><?php echo $product['title']; ?></h3>
                                
                                <div class="product-highlights">
                                    <?php foreach (array_slice($product['highlights'], 0, 2) as $highlight): ?>
                                    <div class="highlight-item">
                                        <i class="ri-check-line"></i>
                                        <span><?php echo $highlight; ?></span>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                                
                                <div class="product-price">
                                    <span class="price-current">€<?php echo number_format($product['price'], 0, ',', '.'); ?></span>
                                    <?php if ($product['price_old']): ?>
                                    <span class="price-old">€<?php echo number_format($product['price_old'], 0, ',', '.'); ?></span>
                                    <span class="price-discount">
                                        -<?php echo round((1 - $product['price'] / $product['price_old']) * 100); ?>%
                                    </span>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="product-warranty">
                                    <i class="ri-shield-check-line"></i>
                                    <span>Garanzia <?php echo $product['warranty']; ?> mesi</span>
                                </div>
                                
                                <div class="product-actions">
                                    <button class="btn btn-outline-primary btn-sm" 
                                            onclick="openWhatsApp('<?php echo $product['title']; ?>', <?php echo $product['id']; ?>)">
                                        <i class="ri-information-line"></i> Dettagli
                                    </button>
                                    <button class="btn btn-primary btn-sm"
                                            onclick="requestQuote('<?php echo $product['title']; ?>', <?php echo $product['price']; ?>)">
                                        <i class="ri-shopping-cart-line"></i> Acquista ora
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <!-- Load More -->
            <div class="text-center mt-5">
                <button class="btn btn-outline-primary btn-lg">
                    <i class="ri-add-line"></i> Carica altri prodotti
                </button>
            </div>
        </div>
    </section>
    
    <!-- Info Section -->
    <section class="section section-info bg-light">
        <div class="container">
            <div class="row g-4">
                <!-- Garanzia Box -->
                <div class="col-lg-4">
                    <div class="info-box">
                        <div class="info-icon">
                            <i class="ri-shield-check-line"></i>
                        </div>
                        <h4 class="info-title">Garanzia 12 Mesi</h4>
                        <p class="info-text">
                            Tutti i nostri dispositivi ricondizionati sono coperti da 
                            una garanzia completa di 12 mesi. In caso di problemi, 
                            interveniamo rapidamente con riparazione o sostituzione.
                        </p>
                        <a href="#" class="info-link">Scopri di più <i class="ri-arrow-right-line"></i></a>
                    </div>
                </div>
                
                <!-- Perché Ricondizionato -->
                <div class="col-lg-4">
                    <div class="info-box">
                        <div class="info-icon">
                            <i class="ri-recycle-line"></i>
                        </div>
                        <h4 class="info-title">Perché Scegliere il Ricondizionato</h4>
                        <p class="info-text">
                            Risparmia fino al 40% rispetto al nuovo, contribuisci 
                            alla sostenibilità ambientale e ottieni un dispositivo 
                            perfettamente funzionante e garantito.
                        </p>
                        <a href="#" class="info-link">Scopri i vantaggi <i class="ri-arrow-right-line"></i></a>
                    </div>
                </div>
                
                <!-- Come Funziona -->
                <div class="col-lg-4">
                    <div class="info-box">
                        <div class="info-icon">
                            <i class="ri-settings-3-line"></i>
                        </div>
                        <h4 class="info-title">Il Nostro Processo</h4>
                        <p class="info-text">
                            Ogni dispositivo viene accuratamente testato, 
                            ricondizionato con ricambi originali se necessario, 
                            igienizzato e certificato prima della vendita.
                        </p>
                        <a href="#" class="info-link">Come lavoriamo <i class="ri-arrow-right-line"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Newsletter Section -->
    <section class="section section-newsletter">
        <div class="container">
            <div class="newsletter-box">
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <h3 class="newsletter-title">
                            <i class="ri-mail-send-line"></i> 
                            Non perdere le nostre offerte!
                        </h3>
                        <p class="newsletter-subtitle">
                            Iscriviti alla newsletter e ricevi uno sconto del 10% sul primo acquisto
                        </p>
                    </div>
                    <div class="col-lg-6">
                        <form class="newsletter-form" id="newsletterForm">
                            <div class="input-group">
                                <input type="email" 
                                       class="form-control" 
                                       placeholder="Inserisci la tua email"
                                       required>
                                <button class="btn btn-primary" type="submit">
                                    Iscriviti <i class="ri-arrow-right-line"></i>
                                </button>
                            </div>
                            <div class="form-text mt-2">
                                <i class="ri-lock-line"></i> 
                                I tuoi dati sono al sicuro. Leggi la nostra 
                                <a href="<?php echo url('privacy.php'); ?>">privacy policy</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Contact Section -->
    <section class="section section-contact-cta">
        <div class="container">
            <div class="text-center">
                <h2 class="mb-4">Hai domande sui nostri ricondizionati?</h2>
                <p class="lead mb-5">
                    Siamo qui per aiutarti a scegliere il dispositivo perfetto per le tue esigenze
                </p>
                <div class="contact-options">
                    <a href="<?php echo whatsapp_link('Ciao, vorrei informazioni sui dispositivi ricondizionati'); ?>" 
                       target="_blank"
                       class="contact-option">
                        <i class="ri-whatsapp-line"></i>
                        <span>WhatsApp</span>
                        <small>Risposta immediata</small>
                    </a>
                    <a href="<?php echo url('contatti.php'); ?>" class="contact-option">
                        <i class="ri-store-2-line"></i>
                        <span>Vieni in negozio</span>
                        <small>Via Diaz 46, Ginosa</small>
                    </a>
                    <a href="tel:<?php echo str_replace(' ', '', COMPANY_PHONE); ?>" class="contact-option">
                        <i class="ri-phone-line"></i>
                        <span>Chiamaci</span>
                        <small><?php echo COMPANY_PHONE; ?></small>
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
        
        // WhatsApp function for product details
        function openWhatsApp(productTitle, productId) {
            const message = `Ciao Key Soft Italia, sono interessato a ${productTitle} (ID: ${productId}). Ho visto la pagina Ricondizionati. Potete darmi maggiori info su prezzo, garanzia e disponibilità? Grazie!`;
            const url = Utils.whatsappLink(message, {
                utm_campaign: 'ricondizionati-details',
                utm_content: productId
            });
            window.open(url, '_blank');
        }
        
        // Request quote function
        function requestQuote(productTitle, price) {
            // In produzione questo aprirà il form preventivo precompilato
            // Per ora apre WhatsApp
            const message = `Ciao Key Soft Italia, vorrei acquistare ${productTitle} al prezzo di €${price}. Come posso procedere?`;
            const url = Utils.whatsappLink(message, {
                utm_campaign: 'ricondizionati-purchase'
            });
            window.open(url, '_blank');
        }
        
        // Toggle filters on mobile
        document.getElementById('toggleFilters')?.addEventListener('click', function() {
            const filtersContent = document.getElementById('filtersContent');
            filtersContent.classList.toggle('show');
            this.innerHTML = filtersContent.classList.contains('show') 
                ? '<i class="ri-close-line"></i> Nascondi Filtri'
                : '<i class="ri-equalizer-line"></i> Mostra Filtri';
        });
        
        // Apply filters
        document.getElementById('applyFilters')?.addEventListener('click', function() {
            // In produzione questo filtrerà i prodotti via AJAX
            console.log('Applying filters...');
            Utils.showNotification('Filtri applicati', 'success');
        });
        
        // Reset filters
        document.getElementById('resetFilters')?.addEventListener('click', function() {
            document.getElementById('filterCategory').value = '';
            document.getElementById('filterBrand').value = '';
            document.getElementById('filterPrice').value = '';
            document.getElementById('filterCondition').value = '';
            Utils.showNotification('Filtri resettati', 'success');
        });
        
        // Newsletter form
        document.getElementById('newsletterForm')?.addEventListener('submit', function(e) {
            e.preventDefault();
            // In produzione questo invierà l'email al backend
            Utils.showNotification('Iscrizione completata! Riceverai presto le nostre offerte.', 'success');
            this.reset();
        });
    </script>
</body>
</html>