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
$page_title = "Vendita Computer, Elettronica ed Elettrodomestici - Key Soft Italia";
$page_description = "Vendita computer, notebook, smartphone ricondizionati nei nostri laboratori e prodotti nuovi a Ginosa. Ampia scelta di TV, Hi-Fi, elettrodomestici, videosorveglianza e telefonia fissa.";
$page_keywords = "vendita computer ginosa, notebook ricondizionati, smartphone usati garantiti, accessori informatica, vendita elettrodomestici, smart tv ginosa, videosorveglianza";

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
                Vendita <span class="text-gradient">Hardware e Elettronica</span>
            </h1>
            <p class="hero-subtitle" data-aos="fade-up" data-aos-delay="200">
                Dispositivi nuovi e ricondizionati certificati nei nostri laboratori con risparmio fino al 40%
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
                <p class="section-subtitle">Un'ampia selezione di prodotti nuovi ed usati garantiti con il supporto diretto dei nostri tecnici</p>
            </div>
            
            <div class="row g-4 mt-2">
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="category-card">
                        <div class="category-icon">
                            <i class="ri-computer-line"></i>
                        </div>
                        <h3 class="category-title">Informatica & Notebook</h3>
                        <p class="category-description">PC Desktop e portatili per ogni esigenza</p>
                        <ul class="category-list">
                            <li>PC fissi assemblati per Office e Gaming</li>
                            <li>Notebook e Workstation ricondizionati</li>
                            <li>Componenti hardware e Monitor PC</li>
                            <li>Tastiere, Mouse ed Accessori informatici</li>
                        </ul>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="category-card">
                        <div class="category-icon">
                            <i class="ri-smartphone-line"></i>
                        </div>
                        <h3 class="category-title">Telefonia & Protezione</h3>
                        <p class="category-description">Dispositivi mobili e protezione display professionale</p>
                        <ul class="category-list">
                            <li>Smartphone nuovi e ricondizionati in laboratorio</li>
                            <li>Tablet Android e iPad garantiti 12 mesi</li>
                            <li><strong>MyShape Protection:</strong> Pellicole display anti-shock e autorigeneranti tagliate su misura al plotter per qualsiasi modello</li>
                            <li>Cover, accessori per ricarica e telefonia fissa</li>
                        </ul>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
                    <div class="category-card">
                        <div class="category-icon">
                            <i class="ri-tv-2-line"></i>
                        </div>
                        <h3 class="category-title">TV, Audio & Hi-Fi</h3>
                        <p class="category-description">Intrattenimento multimediale per la casa</p>
                        <ul class="category-list">
                            <li>Smart TV Led e Oled di ultima generazione</li>
                            <li>Sistemi Hi-Fi e amplificatori audio</li>
                            <li>Cuffie, auricolari bluetooth e speaker wireless</li>
                            <li>Soundbar, decoder e accessori audio-video</li>
                        </ul>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="400">
                    <div class="category-card">
                        <div class="category-icon">
                            <i class="ri-fridge-line"></i>
                        </div>
                        <h3 class="category-title">Elettrodomestici & Bellezza</h3>
                        <p class="category-description">Per la cura della casa e della persona</p>
                        <ul class="category-list">
                            <li>Piccoli e grandi elettrodomestici per la cucina</li>
                            <li>Rasoi elettrici, regolabarba e cura capelli</li>
                            <li>Asciugacapelli e piastre professionali</li>
                            <li>Prodotti tecnologici per la bellezza e il benessere</li>
                        </ul>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="500">
                    <div class="category-card">
                        <div class="category-icon">
                            <i class="ri-eye-line"></i>
                        </div>
                        <h3 class="category-title">Videosorveglianza</h3>
                        <p class="category-description">Sicurezza avanzata per ambienti privati e commerciali</p>
                        <ul class="category-list">
                            <li>Telecamere IP ad alta definizione (nuove/ricondizionate)</li>
                            <li>Sistemi NVR/DVR di registrazione continua</li>
                            <li>Kit di sorveglianza gestibili da smartphone</li>
                            <li>Accessori e alimentatori per impianti di sicurezza</li>
                        </ul>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="600">
                    <div class="category-card">
                        <div class="category-icon">
                            <i class="ri-database-2-line"></i>
                        </div>
                        <h3 class="category-title">Storage & Networking</h3>
                        <p class="category-description">Soluzioni per la connettività e l'archiviazione</p>
                        <ul class="category-list">
                            <li>Hard Disk esterni ed unità SSD ad alta velocità</li>
                            <li>Modem Router Wi-Fi e ripetitori di segnale</li>
                            <li>Switch di rete, cavi LAN e adattatori internet</li>
                            <li>Chiavette USB e schede di memoria SD</li>
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
                <p class="section-subtitle">Le migliori offerte su dispositivi ricondizionati nei nostri laboratori e prodotti nuovi</p>
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
                            $badge_html = '<div class="product-badge bg-dark">Ricondizionato</div>';
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

    <!-- MyShape Protection Section -->
    <section id="myshape" class="section bg-white border-top border-bottom position-relative overflow-hidden">
        <div class="container position-relative z-2">
            <div class="row align-items-center g-5">
                <div class="col-lg-6" data-aos="fade-right">
                    <div class="pe-lg-4">
                        <span class="badge bg-orange mb-3">Servizio Esclusivo</span>
                        <h2 class="section-title mb-4">Protezione su Misura con <span class="text-gradient">MyShape Protection</span></h2>
                        <p class="lead mb-4 text-dark fw-semibold">Dì addio ai vetri temperati che si scheggiano o si sollevano continuamente.</p>
                        <p class="text-secondary mb-4">
                            Presso il nostro negozio a Ginosa disponiamo del sistema plotter professionale <strong>MyShape Protection</strong>. Tagliamo e applichiamo all'istante pellicole protettive ultra-resistenti di livello militare, adattabili a qualsiasi modello di smartphone, tablet, smartwatch e persino console portatili.
                        </p>
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="ri-shield-flash-line text-orange fa-lg"></i>
                                    <span class="text-dark fw-bold small">Protezione Anti-Shock</span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="ri-magic-line text-orange fa-lg"></i>
                                    <span class="text-dark fw-bold small">Film Autorigenerante</span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="ri-scan-2-line text-orange fa-lg"></i>
                                    <span class="text-dark fw-bold small">Taglio al Millimetro</span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="ri-contrast-drop-line text-orange fa-lg"></i>
                                    <span class="text-dark fw-bold small">Finitura Lucida o Opaca</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6" data-aos="fade-left">
                    <div class="card bg-light border-0 p-4 p-md-5 rounded-4 shadow-sm">
                        <h4 class="fw-bold mb-3"><i class="ri-settings-4-line text-orange me-2"></i>Come Funziona il Servizio?</h4>
                        <ol class="list-unstyled mb-0">
                            <li class="mb-3 d-flex gap-3">
                                <div class="badge bg-dark rounded-circle d-flex align-items-center justify-content-center" style="width: 28px; height: 28px; flex-shrink: 0;">1</div>
                                <div>
                                    <h5 class="fw-bold mb-1" style="font-size: 15px;">Selezione del Modello</h5>
                                    <p class="text-muted small mb-0">Troviamo il file di taglio esatto per il tuo dispositivo nel database MyShape (oltre 30.000 modelli).</p>
                                </div>
                            </li>
                            <li class="mb-3 d-flex gap-3">
                                <div class="badge bg-dark rounded-circle d-flex align-items-center justify-content-center" style="width: 28px; height: 28px; flex-shrink: 0;">2</div>
                                <div>
                                    <h5 class="fw-bold mb-1" style="font-size: 15px;">Taglio On-Demand</h5>
                                    <p class="text-muted small mb-0">Il plotter professionale incide la pellicola in poliuretano termoplastico (TPU) al millimetro in pochi secondi.</p>
                                </div>
                            </li>
                            <li class="d-flex gap-3">
                                <div class="badge bg-dark rounded-circle d-flex align-items-center justify-content-center" style="width: 28px; height: 28px; flex-shrink: 0;">3</div>
                                <div>
                                    <h5 class="fw-bold mb-1" style="font-size: 15px;">Applicazione Professionale</h5>
                                    <p class="text-muted small mb-0">I nostri tecnici puliscono lo schermo ed applicano la pellicola a regola d'arte, garantendo l'assenza di bolle o imperfezioni.</p>
                                </div>
                            </li>
                        </ol>
                    </div>
                </div>
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
                            Ogni dispositivo ricondizionato nei nostri laboratori viene sottoposto a oltre 30 severi test hardware e software.
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
                            Risparmia fino al 40% rispetto al prezzo di listino del nuovo, senza compromessi sulle performance del dispositivo.
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
                            Tutti i nostri prodotti ricondizionati in laboratorio sono protetti da 12 mesi di garanzia scritta contro ogni difetto.
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