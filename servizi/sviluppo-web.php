<?php
/**
 * Key Soft Italia - Servizio Sviluppo Web
 * Pagina dettaglio sviluppo siti web e gestionali con listino e showcase dinamici da DB
 */

if (!defined('BASE_PATH')) {
    define('BASE_PATH', dirname(__DIR__) . '/');
}

require_once BASE_PATH . 'config/config.php';

// Carichiamo i pacchetti listino attivi
$packages = [];
try {
    $stmt = $pdo->query("SELECT * FROM web_packages WHERE status = 1 ORDER BY sort_order ASC, id ASC");
    $packages = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $packages = [];
}

// Carichiamo i progetti portfolio/showcase attivi
$showcases = [];
try {
    $stmt = $pdo->query("SELECT * FROM web_showcase WHERE status = 1 ORDER BY sort_order ASC, id ASC");
    $showcases = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $showcases = [];
}

// SEO Meta
$page_title = "Creazione Siti Web e E-commerce - Key Soft Italia";
$page_description = "Sviluppo siti internet, landing page e negozi e-commerce a Ginosa. Soluzioni professionali responsive ed ottimizzate SEO per privati e aziende.";
$page_keywords = "sviluppo siti web ginosa, creazione e-commerce, landing page professionali, portafoglio siti internet, prezzi siti web";

// Breadcrumbs
$breadcrumbs = [
    ['label' => 'Servizi', 'url' => '../servizi.php'],
    ['label' => 'Sviluppo Web', 'url' => 'sviluppo-web.php']
];
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <?php include '../includes/head.php'; ?>
    <!-- CSS di pagina -->
    <link rel="stylesheet" href="<?php echo asset_version('css/pages/sviluppo-web.css'); ?>">
</head>
<body>
    
    <!-- Header -->
    <?php include '../includes/header.php'; ?>
    
    <!-- Hero Section -->
    <section class="hero hero-secondary text-center">
        <div class="hero-pattern"></div>
        <div class="container position-relative z-2" data-aos="fade-up">
            <div class="hero-icon mb-3" data-aos="zoom-in">
                <i class="ri-code-s-slash-line"></i>
            </div>
            <h1 class="hero-title text-white" data-aos="fade-up" data-aos-delay="100">
                Sviluppo <span class="text-gradient">Siti Web e Applicazioni</span>
            </h1>
            <p class="hero-subtitle" data-aos="fade-up" data-aos-delay="200">
                Creiamo soluzioni web professionali, e-commerce e gestionali personalizzati per espandere il tuo business
            </p>
            <div class="hero-cta" data-aos="fade-up" data-aos-delay="300">
                <a href="#listino" class="btn btn-primary btn-lg smooth-scroll" aria-label="Scopri i pacchetti di sviluppo web">
                    <i class="ri-arrow-down-line me-1"></i> Scopri i Pacchetti
                </a>
            </div>
            <div class="hero-breadcrumb mt-4" data-aos="fade-up" data-aos-delay="400">
                <?php echo generate_breadcrumbs($breadcrumbs); ?>
            </div>
        </div>
    </section>
    
    <!-- Packages List Section -->
    <section id="listino" class="section-packages">
        <div class="container">
            <div class="section-header text-center mb-5" data-aos="fade-up">
                <h2 class="section-title">Pacchetti <span class="text-gradient">Sviluppo Web</span></h2>
                <p class="section-subtitle">Soluzioni chiare a prezzi trasparenti per soddisfare le esigenze di qualsiasi progetto</p>
            </div>
            
            <div class="row g-4 justify-content-center">
                <?php if (empty($packages)): ?>
                    <div class="col-12 text-center py-5">
                        <div class="alert alert-info border-0 shadow-sm d-inline-block p-4 rounded-4" style="max-width: 500px;">
                            <i class="ri-information-line text-primary display-6 mb-3 d-block"></i>
                            <h5 class="fw-bold">Nessun piano inserito nel listino</h5>
                            <p class="text-muted mb-0">Contattaci direttamente in negozio per richiedere un preventivo o un'offerta personalizzata su misura.</p>
                        </div>
                    </div>
                <?php else: ?>
                    <?php 
                    $delay = 0;
                    foreach ($packages as $pack): 
                        $delay += 100;
                        $is_featured = (int)$pack['is_featured'] === 1;
                        $features_arr = !empty($pack['features']) ? explode("\n", $pack['features']) : [];
                    ?>
                        <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="<?= $delay; ?>">
                            <div class="package-card <?= $is_featured ? 'featured' : ''; ?>">
                                <div class="text-center">
                                    <h3 class="package-title"><?= htmlspecialchars($pack['title']); ?></h3>
                                    <p class="package-subtitle"><?= htmlspecialchars($pack['subtitle']); ?></p>
                                </div>
                                <div class="package-price">
                                    <?php if ($pack['price'] !== null): ?>
                                        €<?= number_format($pack['price'], 0, ',', '.'); ?><small><?= htmlspecialchars($pack['price_detail']); ?></small>
                                    <?php else: ?>
                                        <?= htmlspecialchars($pack['price_detail'] ?: 'Su misura'); ?>
                                    <?php endif; ?>
                                </div>
                                <ul class="package-features">
                                    <?php foreach ($features_arr as $feat): 
                                        $feat = trim($feat);
                                        if (empty($feat)) continue;
                                    ?>
                                        <li>
                                            <i class="ri-checkbox-circle-line text-success"></i>
                                            <span><?= htmlspecialchars($feat); ?></span>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                                <a href="<?php echo url('preventivo.php'); ?>?servizio=web" class="btn <?= $is_featured ? 'btn-primary' : 'btn-outline-primary'; ?> package-btn w-100">
                                    Richiedi Progetto
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Tech Stack Section -->
    <section class="section-tech">
        <div class="container">
            <h2 class="text-center mb-2" data-aos="fade-up">Tecnologie & Framework</h2>
            <p class="text-center lead text-muted" data-aos="fade-up" data-aos-delay="100">Utilizziamo le tecnologie più avanzate per garantire stabilità, velocità e sicurezza</p>
            
            <div class="tech-stack" data-aos="fade-up" data-aos-delay="200">
                <div class="tech-badge">
                    <i class="ri-html5-fill" style="color: #e34c26;"></i>
                    <span>HTML5</span>
                </div>
                <div class="tech-badge">
                    <i class="ri-css3-fill" style="color: #1572b6;"></i>
                    <span>CSS3</span>
                </div>
                <div class="tech-badge">
                    <i class="ri-javascript-fill" style="color: #f7df1e;"></i>
                    <span>JavaScript</span>
                </div>
                <div class="tech-badge">
                    <i class="ri-reactjs-line" style="color: #61dafb;"></i>
                    <span>React JS</span>
                </div>
                <div class="tech-badge">
                    <i class="ri-bootstrap-fill" style="color: #7952b3;"></i>
                    <span>Bootstrap</span>
                </div>
                <div class="tech-badge">
                    <i class="ri-wordpress-fill" style="color: #21759b;"></i>
                    <span>WordPress</span>
                </div>
                <div class="tech-badge">
                    <i class="ri-shopping-cart-line" style="color: #96588a;"></i>
                    <span>WooCommerce</span>
                </div>
                <div class="tech-badge">
                    <i class="ri-database-2-line" style="color: #336791;"></i>
                    <span>MySQL DB</span>
                </div>
                <div class="tech-badge">
                    <i class="ri-server-line" style="color: #777bb4;"></i>
                    <span>PHP Engine</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Showcase Section -->
    <section class="section-portfolio">
        <div class="container">
            <div class="section-header text-center mb-5" data-aos="fade-up">
                <h2 class="section-title">Ultimi <span class="text-gradient">Progetti Realizzati</span></h2>
                <p class="section-subtitle">Sfoglia alcuni dei lavori che abbiamo completato con successo per i nostri clienti</p>
            </div>
            
            <div class="row g-4 justify-content-center">
                <?php if (empty($showcases)): ?>
                    <div class="col-12 text-center py-5">
                        <p class="text-muted">Nessun progetto caricato nel portfolio.</p>
                    </div>
                <?php else: ?>
                    <?php 
                    $delay = 0;
                    foreach ($showcases as $show): 
                        $delay += 100;
                    ?>
                        <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="<?= $delay; ?>">
                            <div class="portfolio-item">
                                <?php if (!empty($show['image_path']) && file_exists('../' . $show['image_path'])): ?>
                                    <img src="<?= url($show['image_path']); ?>" alt="<?= htmlspecialchars($show['title']); ?>" class="portfolio-image">
                                <?php else: ?>
                                    <div class="portfolio-fallback-img">
                                        <i class="ri-macbook-line"></i>
                                        <h5 class="text-white"><?= htmlspecialchars($show['title']); ?></h5>
                                    </div>
                                <?php endif; ?>
                                <div class="portfolio-overlay">
                                    <h4><?= htmlspecialchars($show['title']); ?></h4>
                                    <p><?= htmlspecialchars($show['description']); ?></p>
                                    <div class="tech-tags">
                                        <i class="ri-tools-fill me-1"></i> <?= htmlspecialchars($show['technologies']); ?>
                                    </div>
                                    <?php if (!empty($show['project_url'])): ?>
                                        <a href="<?= htmlspecialchars($show['project_url']); ?>" target="_blank" rel="noopener" class="btn btn-sm btn-outline-light mt-3 py-1 px-3" style="font-size: 0.8rem;">
                                            Visita il Sito <i class="ri-external-link-line ms-1"></i>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Development Process Section -->
    <section class="section-process">
        <div class="container position-relative z-1">
            <div class="section-header text-center mb-5" data-aos="fade-up">
                <h2 class="section-title">Il Nostro <span class="text-gradient">Metodo di Lavoro</span></h2>
                <p class="section-subtitle">Quattro step per trasformare la tua idea in una piattaforma online di successo</p>
            </div>
            
            <div class="row g-4">
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="100">
                    <div class="process-card">
                        <div class="process-number">1</div>
                        <h4>Analisi</h4>
                        <p>Studio degli obiettivi e analisi del target di riferimento.</p>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="200">
                    <div class="process-card">
                        <div class="process-number">2</div>
                        <h4>Design UI/UX</h4>
                        <p>Creazione del layout visivo e dell'esperienza utente responsiva.</p>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="300">
                    <div class="process-card">
                        <div class="process-number">3</div>
                        <h4>Sviluppo & Test</h4>
                        <p>Scrittura del codice, ottimizzazione SEO e test di performance.</p>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                    <div class="process-card">
                        <div class="process-number">4</div>
                        <h4>Lancio & Assistenza</h4>
                        <p>Messa online del sito, formazione gestionale e supporto continuo.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-5" data-aos="fade-up">Caratteristiche dei Nostri Siti</h2>
            
            <div class="row g-4">
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="d-flex">
                        <div class="flex-shrink-0">
                            <i class="ri-smartphone-line" style="font-size: 2.2rem; color: var(--ks-orange);"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="fw-bold">100% Responsive</h5>
                            <p class="text-muted">Progettati per adattarsi in modo fluido a smartphone, tablet e computer.</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="d-flex">
                        <div class="flex-shrink-0">
                            <i class="ri-search-line" style="font-size: 2.2rem; color: var(--ks-orange);"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="fw-bold">SEO Ottimizzato</h5>
                            <p class="text-muted">Codice pulito e struttura studiata per massimizzare la visibilità sui motori di ricerca.</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="d-flex">
                        <div class="flex-shrink-0">
                            <i class="ri-speed-line" style="font-size: 2.2rem; color: var(--ks-orange);"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="fw-bold">Velocità di Caricamento</h5>
                            <p class="text-muted">Compressione degli asset e ottimizzazioni server per caricamenti istantanei.</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="400">
                    <div class="d-flex">
                        <div class="flex-shrink-0">
                            <i class="ri-lock-2-line" style="font-size: 2.2rem; color: var(--ks-orange);"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="fw-bold">Sicurezza & SSL</h5>
                            <p class="text-muted">Installazione di certificati HTTPS e sistemi anti-intrusione aggiornati.</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="500">
                    <div class="d-flex">
                        <div class="flex-shrink-0">
                            <i class="ri-edit-2-line" style="font-size: 2.2rem; color: var(--ks-orange);"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="fw-bold">Pannello Autonomo (CMS)</h5>
                            <p class="text-muted">Inserisci e modifica testi, foto e cataloghi in modo semplice e senza scrivere codice.</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="600">
                    <div class="d-flex">
                        <div class="flex-shrink-0">
                            <i class="ri-bar-chart-box-line" style="font-size: 2.2rem; color: var(--ks-orange);"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="fw-bold">Analytics Integrato</h5>
                            <p class="text-muted">Pannello statistiche per misurare visite, pagine visualizzate e conversioni utenti.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="section-cta-clean text-center">
        <div class="container" data-aos="fade-up">
            <h2 class="cta-title">Hai un Progetto da Realizzare Online?</h2>
            <p class="cta-subtitle">Siamo pronti ad ascoltare la tua idea e a studiare la soluzione web più redditizia per la tua impresa</p>
            <div class="cta-buttons">
                <a href="<?php echo url('preventivo.php'); ?>?servizio=web" class="btn btn-primary btn-lg">
                    <i class="ri-file-list-3-line me-1"></i> Richiedi Preventivo
                </a>
                <a href="<?php echo url('contatti.php'); ?>" class="btn btn-outline-dark btn-lg">
                    <i class="ri-mail-line me-1"></i> Contattaci
                </a>
                <a href="<?php echo whatsapp_link('Salve, vorrei informazioni per lo sviluppo di un sito web o e-commerce'); ?>" 
                   class="btn btn-success btn-lg" target="_blank" rel="noopener">
                    <i class="ri-whatsapp-line me-1"></i> WhatsApp
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php include '../includes/footer.php'; ?>
</body>
</html>