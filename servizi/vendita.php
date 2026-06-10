<?php
/**
 * Key Soft Italia - Servizio Vendita
 * Pagina dettaglio servizi di vendita hardware e accessori (Allineata a chi-siamo.php)
 */

// Define BASE_PATH if not defined
if (!defined('BASE_PATH')) {
    define('BASE_PATH', dirname(__DIR__) . '/');
}

require_once BASE_PATH . 'config/config.php';

// Fetch featured products from database
$featured_products = [];
try {
    $sql = "
    SELECT
      p.id, p.sku,
      p.list_price,             -- listino
      p.price_eur,              -- prezzo
      p.short_desc, p.full_desc,
      p.grade, p.storage_gb, p.color,
      p.is_available, p.is_featured,
      b.name AS brand,
      m.name AS model,
      d.name AS device,
      COALESCE(
        MAX(CASE WHEN pi.is_cover = 1 THEN pi.path END),
        MAX(pi.path)
      ) AS image_path
    FROM products p
    JOIN models  m ON p.model_id = m.id
    JOIN brands  b ON m.brand_id = b.id
    JOIN devices d ON b.device_id = d.id
    LEFT JOIN product_images pi ON pi.product_id = p.id
    WHERE p.is_featured = 1 AND p.is_available = 1
    GROUP BY p.id
    ORDER BY p.created_at DESC
    LIMIT 3
    ";
    $stmt = $pdo->query($sql);
    $featured_products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $featured_products = [];
}

// SEO Meta
$page_title = "Vendita Computer e Accessori - Key Soft Italia | Prodotti Ricondizionati Garantiti";
$page_description = "Vendita computer, notebook, smartphone ricondizionati e accessori a Ginosa. Prodotti garantiti Key-Renew con risparmio fino al 40%. Consulenza e assistenza.";
$page_keywords = "vendita computer ginosa, notebook ricondizionati, smartphone usati garantiti, accessori informatica, key-renew";

// Breadcrumbs
$breadcrumbs = [
    ['label' => 'Servizi', 'url' => '../servizi.php'],
    ['label' => 'Vendita', 'url' => 'vendita.php']
];
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <?php include '../includes/head.php'; ?>
    <!-- CSS di pagina -->
    <link rel="stylesheet" href="<?php echo asset_version('css/pages/vendita.css'); ?>">
</head>
<body>
    
    <!-- Header -->
    <?php include '../includes/header.php'; ?>
    
    <!-- Hero Section -->
    <section class="hero hero-secondary text-center">
        <div class="hero-pattern"></div>
        <div class="container position-relative z-2" data-aos="fade-up">
            <div class="hero-icon mb-3" data-aos="zoom-in">
                <i class="ri-shopping-cart-line"></i>
            </div>
            <h1 class="hero-title text-white" data-aos="fade-up" data-aos-delay="100">
                Vendita <span class="text-gradient">Hardware e Ricondizionati</span>
            </h1>
            <p class="hero-subtitle" data-aos="fade-up" data-aos-delay="200">
                Dispositivi selezionati nuovi e usati certificati <strong>Key-Renew</strong> con risparmio fino al 40%
            </p>
            <div class="hero-cta" data-aos="fade-up" data-aos-delay="300">
                <a href="#categorie" class="btn btn-primary btn-lg smooth-scroll" aria-label="Scopri le nostre categorie di prodotti">
                    <i class="ri-arrow-down-line me-1"></i> Scopri i Prodotti
                </a>
            </div>
            <div class="hero-breadcrumb mt-4" data-aos="fade-up" data-aos-delay="400">
                <?php echo generate_breadcrumbs($breadcrumbs); ?>
            </div>
        </div>
    </section>
    
    <!-- Product Categories -->
    <section id="categorie" class="service-category">
        <div class="container">
            <div class="section-header text-center mb-5" data-aos="fade-up">
                <h2 class="section-title">Le Nostre <span class="text-gradient">Categorie</span></h2>
                <p class="section-subtitle">Ampia selezione di prodotti nuovi e ricondizionati con setup iniziale incluso</p>
            </div>
            
            <div class="row g-4 mt-2">
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="100">
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
                
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
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
                
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="300">
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
                
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="400">
                    <div class="category-card">
                        <div class="category-icon">
                            <i class="ri-headphone-line"></i>
                        </div>
                        <h3 class="category-title">Accessori</h3>
                        <p class="category-description">Tutto per il tuo setup</p>
                        <ul class="category-list">
                            <li>Monitor PC</li>
                            <li>Tastiere & Mouse</li>
                            <li>Cuffie & Speaker</li>
                            <li>Dischi Storage</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Featured Products -->
    <section id="prodotti-evidenza" class="section section-values bg-light">
        <div class="container position-relative z-2">
            <div class="section-header text-center mb-5" data-aos="fade-up">
                <h2 class="section-title">Prodotti in <span class="text-gradient">Evidenza</span></h2>
                <p class="section-subtitle">Le migliori offerte su dispositivi ricondizionati certificati Key-Renew e nuovi</p>
            </div>
            
            <div class="row g-4 mt-2">
                <?php if (empty($featured_products)): ?>
                    <div class="col-12 text-center py-5">
                        <p class="text-muted">Nessun prodotto in evidenza disponibile al momento nel database.</p>
                    </div>
                <?php else: ?>
                    <?php 
                    $delay = 0;
                    foreach ($featured_products as $product): 
                        $delay += 100;
                        $title = trim(($product['brand'] ?? '') . ' ' . ($product['model'] ?? ''));
                        
                        // Calculate discount if list price is set
                        $discount = 0;
                        if (!empty($product['list_price']) && !empty($product['price_eur']) && (float)$product['list_price'] > (float)$product['price_eur']) {
                            $discount = round((( (float)$product['list_price'] - (float)$product['price_eur'] ) / (float)$product['list_price']) * 100);
                        }
                        
                        // Construct cover image url
                        $img_src = !empty($product['image_path']) ? url($product['image_path']) : asset('img/recond/placeholder.jpg');
                        
                        // Condition logic
                        $is_refurbished = !empty($product['grade']);
                        $condition_text = $is_refurbished ? "Ricondizionato Grado " . $product['grade'] : "Nuovo";
                        $condition_badge_class = $is_refurbished ? "badge-green" : "badge-blue";
                        
                        // Badge markup
                        if ($discount > 0) {
                            $badge_html = '<div class="product-badge bg-orange">-' . $discount . '%</div>';
                        } elseif (!$is_refurbished) {
                            $badge_html = '<div class="product-badge bg-blue">Nuovo</div>';
                        } else {
                            $badge_html = '<div class="product-badge bg-dark">Key-Renew</div>';
                        }
                    ?>
                        <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="<?= $delay; ?>">
                            <div class="product-card">
                                <?= $badge_html; ?>
                                <div class="product-image">
                                    <img src="<?= $img_src; ?>" alt="<?= htmlspecialchars($title); ?>" loading="lazy">
                                </div>
                                <div class="product-content">
                                    <h4 class="product-title"><?= htmlspecialchars($title); ?></h4>
                                    <p class="product-specs">
                                        <?= !empty($product['storage_gb']) ? $product['storage_gb'] . 'GB' : ''; ?>
                                        <?= (!empty($product['storage_gb']) && !empty($product['color'])) ? ' • ' : ''; ?>
                                        <?= !empty($product['color']) ? htmlspecialchars($product['color']) : ''; ?>
                                    </p>
                                    <div class="product-condition">
                                        <span class="badge <?= $condition_badge_class; ?>"><?= $condition_text; ?></span>
                                    </div>
                                    <div class="product-price">
                                        <?php if ($discount > 0): ?>
                                            <span class="price-old">€<?= number_format((float)$product['list_price'], 0, ',', '.'); ?></span>
                                        <?php endif; ?>
                                        <span class="price-current">€<?= number_format((float)$product['price_eur'], 0, ',', '.'); ?></span>
                                    </div>
                                    <div class="product-warranty">
                                        <i class="ri-shield-check-line"></i> Garanzia <?= $is_refurbished ? '12' : '24'; ?> mesi
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            
            <div class="text-center mt-5" data-aos="fade-up">
                <a href="<?php echo url('prodotti.php'); ?>" class="btn btn-primary btn-lg">
                    <i class="ri-shopping-bag-line"></i> Vedi Tutti i Prodotti
                </a>
            </div>
        </div>
    </section>
    
    <!-- Why Choose Us -->
    <section id="benefici" class="service-category">
        <div class="container">
            <div class="section-header text-center mb-5" data-aos="fade-up">
                <h2 class="section-title">Perché Scegliere i <span class="text-gradient">Nostri Prodotti</span></h2>
                <p class="section-subtitle">Qualità garantita, convenienza e l'assistenza diretta del nostro punto vendita</p>
            </div>
            
            <div class="row g-4 mt-2">
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="benefit-card">
                        <div class="benefit-icon">
                            <i class="ri-verified-badge-line"></i>
                        </div>
                        <h3 class="benefit-title">Qualità Certificata</h3>
                        <p class="benefit-text">
                            Ogni dispositivo ricondizionato Key-Renew viene sottoposto a oltre 30 test hardware e software in laboratorio.
                        </p>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="benefit-card">
                        <div class="benefit-icon">
                            <i class="ri-percent-line"></i>
                        </div>
                        <h3 class="benefit-title">Risparmio Garantito</h3>
                        <p class="benefit-text">
                            Risparmia fino al 40% rispetto al prezzo di listino del nuovo, senza compromessi sull'efficienza del dispositivo.
                        </p>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
                    <div class="benefit-card">
                        <div class="benefit-icon">
                            <i class="ri-shield-star-line"></i>
                        </div>
                        <h3 class="benefit-title">Garanzia di 12 Mesi</h3>
                        <p class="benefit-text">
                            Tutti i nostri prodotti ricondizionati Key-Renew sono protetti da 12 mesi di garanzia scritta contro ogni difetto.
                        </p>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="benefit-card">
                        <div class="benefit-icon">
                            <i class="ri-customer-service-2-line"></i>
                        </div>
                        <h3 class="benefit-title">Assistenza in Negozio</h3>
                        <p class="benefit-text">
                            Nessun call center remoto: ricevi supporto pre e post vendita direttamente dal nostro team tecnico a Ginosa.
                        </p>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="benefit-card">
                        <div class="benefit-icon">
                            <i class="ri-truck-line"></i>
                        </div>
                        <h3 class="benefit-title">Consegna Rapida</h3>
                        <p class="benefit-text">
                            Ritiro immediato dei prodotti pronti in negozio o spedizione veloce in tutta la provincia di Taranto e Matera.
                        </p>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
                    <div class="benefit-card">
                        <div class="benefit-icon">
                            <i class="ri-exchange-line"></i>
                        </div>
                        <h3 class="benefit-title">Valutazione & Permuta</h3>
                        <p class="benefit-text">
                            Portaci il tuo usato: effettuiamo una valutazione immediata in negozio da scalare sull'acquisto del tuo prossimo dispositivo.
                        </p>
                        <a href="<?php echo url('valuta-usato.php'); ?>" class="btn btn-sm btn-outline-orange mt-3">
                            Valuta Usato <i class="ri-arrow-right-line"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- CTA Section (Aligned with section-cta-clean) -->
    <section class="section section-cta-clean text-center" data-aos="fade-up" id="section-cta">
        <div class="container">
            <h2 class="cta-title">Cerchi un Prodotto Specifico?</h2>
            <p class="cta-subtitle">Contattaci per verificare la disponibilità o richiedi un preventivo gratuito per qualsiasi configurazione</p>
            <div class="cta-buttons">
                <a href="<?php echo url('preventivo.php'); ?>" class="btn btn-primary btn-lg" aria-label="Richiedi un preventivo gratuito">
                    <i class="ri-file-list-3-line me-2"></i> Richiedi Preventivo
                </a>
                <a href="<?php echo whatsapp_link('Salve, vorrei informazioni sulla disponibilità di un prodotto'); ?>" 
                   class="btn btn-success btn-lg" target="_blank" rel="noopener noreferrer" aria-label="Contattaci su WhatsApp">
                    <i class="ri-whatsapp-line me-2"></i> Contattaci su WhatsApp
                </a>
            </div>
        </div>
    </section>
    
    <!-- Set BASE_URL for JavaScript -->
    <script>
        window.KS_CONFIG = {
            baseUrl: '<?php echo BASE_URL; ?>',
            whatsappNumber: '<?php echo WHATSAPP_NUMBER; ?>'
        };
    </script>

    <!-- Footer -->
    <?php include '../includes/footer.php'; ?>
</body>
</html>