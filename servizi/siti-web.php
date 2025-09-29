<?php
require_once '../config/config.php';
require_once '../assets/php/functions.php';

$page_title = "Sviluppo Siti Web e E-commerce - " . SITE_NAME;
$page_description = "Creazione siti web professionali, e-commerce, gestionali web e app mobile. Design moderno e SEO ottimizzato.";
$current_page = 'servizi';
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <?php include '../includes/head.php'; ?>
    <style>
        .service-hero {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 100px 0;
            color: white;
            position: relative;
            overflow: hidden;
        }
        
        .service-hero::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="%23ffffff10" d="M0,32L48,53.3C96,75,192,117,288,128C384,139,480,117,576,90.7C672,64,768,32,864,48C960,64,1056,128,1152,144C1248,160,1344,128,1392,112L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>') no-repeat bottom;
            background-size: cover;
            opacity: 0.5;
        }
        
        .portfolio-item {
            position: relative;
            overflow: hidden;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .portfolio-item:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        }
        
        .portfolio-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(to bottom, transparent 0%, rgba(0,0,0,0.8) 100%);
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 30px;
            color: white;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .portfolio-item:hover .portfolio-overlay {
            opacity: 1;
        }
        
        .portfolio-image {
            width: 100%;
            height: 300px;
            object-fit: cover;
            transition: transform 0.3s ease;
        }
        
        .portfolio-item:hover .portfolio-image {
            transform: scale(1.1);
        }
        
        .package-card {
            background: white;
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            height: 100%;
            position: relative;
            transition: all 0.3s ease;
        }
        
        .package-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }
        
        .package-card.popular {
            border: 2px solid #4a00e0;
        }
        
        .package-card.popular::before {
            content: 'CONSIGLIATO';
            position: absolute;
            top: -12px;
            left: 50%;
            transform: translateX(-50%);
            background: #4a00e0;
            color: white;
            padding: 5px 20px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        
        .package-price {
            font-size: 2.5rem;
            font-weight: bold;
            color: #4a00e0;
            margin: 20px 0;
        }
        
        .feature-list {
            list-style: none;
            padding: 0;
            margin: 30px 0;
        }
        
        .feature-list li {
            padding: 10px 0;
            border-bottom: 1px solid #f0f0f0;
            display: flex;
            align-items: center;
        }
        
        .feature-list li i {
            margin-right: 10px;
        }
        
        .feature-list li i.ri-check-line {
            color: #4caf50;
        }
        
        .feature-list li i.ri-close-line {
            color: #ccc;
        }
        
        .tech-stack {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            justify-content: center;
            margin: 40px 0;
        }
        
        .tech-badge {
            padding: 10px 20px;
            background: #f8f9fa;
            border-radius: 25px;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s ease;
        }
        
        .tech-badge:hover {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            transform: translateY(-5px);
        }
        
        .tech-badge i {
            font-size: 1.5rem;
        }
        
        .process-section {
            background: #f8f9fa;
            padding: 60px 0;
        }
        
        .process-card {
            background: white;
            padding: 30px;
            border-radius: 10px;
            text-align: center;
            height: 100%;
            position: relative;
            transition: all 0.3s ease;
        }
        
        .process-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.15);
        }
        
        .process-number {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            font-weight: bold;
            margin: 0 auto 20px;
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
    <?php include '../includes/header.php'; ?>

    <!-- Hero Section -->
    <section class="service-hero">
        <div class="container position-relative">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?php echo url(''); ?>" style="color: white;">Home</a></li>
                            <li class="breadcrumb-item"><a href="<?php echo url('servizi.php'); ?>" style="color: white;">Servizi</a></li>
                            <li class="breadcrumb-item active" aria-current="page" style="color: white;">Sviluppo Web</li>
                        </ol>
                    </nav>
                    <h1 class="display-4 fw-bold mb-4">Sviluppo Siti Web</h1>
                    <p class="lead mb-4">Creiamo siti web professionali, e-commerce e applicazioni web su misura per far crescere il tuo business online</p>
                    <div class="d-flex gap-4 flex-wrap">
                        <div>
                            <i class="ri-smartphone-line"></i> Design Responsive
                        </div>
                        <div>
                            <i class="ri-search-line"></i> SEO Ottimizzato
                        </div>
                        <div>
                            <i class="ri-speed-line"></i> Performance Elevate
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Packages -->
    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-5">Pacchetti Sviluppo Web</h2>
            
            <div class="row g-4">
                <div class="col-lg-3">
                    <div class="package-card">
                        <h3 class="text-center">Landing Page</h3>
                        <div class="package-price text-center">
                            €499
                        </div>
                        <p class="text-center text-muted">Perfetta per campagne marketing</p>
                        <ul class="feature-list">
                            <li><i class="ri-check-line"></i> Design personalizzato</li>
                            <li><i class="ri-check-line"></i> 1 pagina ottimizzata</li>
                            <li><i class="ri-check-line"></i> Form contatti</li>
                            <li><i class="ri-check-line"></i> Mobile responsive</li>
                            <li><i class="ri-check-line"></i> SEO base</li>
                            <li><i class="ri-close-line"></i> Area riservata</li>
                            <li><i class="ri-close-line"></i> E-commerce</li>
                        </ul>
                        <button class="btn btn-outline-primary w-100">Scopri di più</button>
                    </div>
                </div>
                
                <div class="col-lg-3">
                    <div class="package-card popular">
                        <h3 class="text-center">Sito Aziendale</h3>
                        <div class="package-price text-center">
                            €1.499
                        </div>
                        <p class="text-center text-muted">Ideale per PMI e professionisti</p>
                        <ul class="feature-list">
                            <li><i class="ri-check-line"></i> Design su misura</li>
                            <li><i class="ri-check-line"></i> Fino a 10 pagine</li>
                            <li><i class="ri-check-line"></i> Blog integrato</li>
                            <li><i class="ri-check-line"></i> Gallery prodotti</li>
                            <li><i class="ri-check-line"></i> SEO avanzato</li>
                            <li><i class="ri-check-line"></i> Google Analytics</li>
                            <li><i class="ri-close-line"></i> E-commerce</li>
                        </ul>
                        <button class="btn btn-primary w-100">Più venduto</button>
                    </div>
                </div>
                
                <div class="col-lg-3">
                    <div class="package-card">
                        <h3 class="text-center">E-commerce</h3>
                        <div class="package-price text-center">
                            €2.999
                        </div>
                        <p class="text-center text-muted">Vendi online con successo</p>
                        <ul class="feature-list">
                            <li><i class="ri-check-line"></i> Shop completo</li>
                            <li><i class="ri-check-line"></i> Pagamenti online</li>
                            <li><i class="ri-check-line"></i> Gestione magazzino</li>
                            <li><i class="ri-check-line"></i> Coupon e sconti</li>
                            <li><i class="ri-check-line"></i> Multi-lingua</li>
                            <li><i class="ri-check-line"></i> Spedizioni automatiche</li>
                            <li><i class="ri-check-line"></i> App mobile</li>
                        </ul>
                        <button class="btn btn-outline-primary w-100">Richiedi info</button>
                    </div>
                </div>
                
                <div class="col-lg-3">
                    <div class="package-card">
                        <h3 class="text-center">Custom</h3>
                        <div class="package-price text-center">
                            Su misura
                        </div>
                        <p class="text-center text-muted">Soluzioni enterprise</p>
                        <ul class="feature-list">
                            <li><i class="ri-check-line"></i> Analisi requisiti</li>
                            <li><i class="ri-check-line"></i> Sviluppo custom</li>
                            <li><i class="ri-check-line"></i> Integrazioni API</li>
                            <li><i class="ri-check-line"></i> Web application</li>
                            <li><i class="ri-check-line"></i> Database dedicato</li>
                            <li><i class="ri-check-line"></i> Scalabilità garantita</li>
                            <li><i class="ri-check-line"></i> Supporto dedicato</li>
                        </ul>
                        <button class="btn btn-outline-primary w-100">Contattaci</button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Technology Stack -->
    <section class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-4">Tecnologie Utilizzate</h2>
            <p class="text-center lead mb-5">Utilizziamo le migliori tecnologie per garantire performance e sicurezza</p>
            
            <div class="tech-stack">
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
                    <span>React</span>
                </div>
                <div class="tech-badge">
                    <i class="ri-vuejs-line" style="color: #4fc08d;"></i>
                    <span>Vue.js</span>
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
                    <span>MySQL</span>
                </div>
                <div class="tech-badge">
                    <i class="ri-server-line" style="color: #777bb4;"></i>
                    <span>PHP</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Portfolio -->
    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-5">Ultimi Progetti Realizzati</h2>
            
            <div class="row g-4">
                <div class="col-md-6 col-lg-4">
                    <div class="portfolio-item">
                        <img src="https://via.placeholder.com/400x300/667eea/ffffff?text=E-commerce" alt="E-commerce" class="portfolio-image">
                        <div class="portfolio-overlay">
                            <h4>Fashion Store</h4>
                            <p>E-commerce moda con 5000+ prodotti</p>
                            <small>WooCommerce, Payment Gateway</small>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-4">
                    <div class="portfolio-item">
                        <img src="https://via.placeholder.com/400x300/764ba2/ffffff?text=Corporate" alt="Corporate" class="portfolio-image">
                        <div class="portfolio-overlay">
                            <h4>Studio Legale</h4>
                            <p>Sito istituzionale con area clienti</p>
                            <small>WordPress, Custom Theme</small>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-4">
                    <div class="portfolio-item">
                        <img src="https://via.placeholder.com/400x300/00c6ff/ffffff?text=Restaurant" alt="Restaurant" class="portfolio-image">
                        <div class="portfolio-overlay">
                            <h4>Ristorante Gourmet</h4>
                            <p>Prenotazioni online e menu digitale</p>
                            <small>React, Node.js, MongoDB</small>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-4">
                    <div class="portfolio-item">
                        <img src="https://via.placeholder.com/400x300/0072ff/ffffff?text=Real+Estate" alt="Real Estate" class="portfolio-image">
                        <div class="portfolio-overlay">
                            <h4>Agenzia Immobiliare</h4>
                            <p>Portale annunci con ricerca avanzata</p>
                            <small>Laravel, Vue.js</small>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-4">
                    <div class="portfolio-item">
                        <img src="https://via.placeholder.com/400x300/ff6b6b/ffffff?text=Fitness" alt="Fitness" class="portfolio-image">
                        <div class="portfolio-overlay">
                            <h4>Palestra Fitness</h4>
                            <p>Booking corsi e abbonamenti online</p>
                            <small>Custom CMS, Stripe</small>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-4">
                    <div class="portfolio-item">
                        <img src="https://via.placeholder.com/400x300/4caf50/ffffff?text=Medical" alt="Medical" class="portfolio-image">
                        <div class="portfolio-overlay">
                            <h4>Clinica Medica</h4>
                            <p>Prenotazioni visite e telemedicina</p>
                            <small>HIPAA Compliant, WebRTC</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Process -->
    <section class="process-section">
        <div class="container">
            <h2 class="text-center mb-5">Il Nostro Processo di Sviluppo</h2>
            
            <div class="row g-4">
                <div class="col-md-6 col-lg-3">
                    <div class="process-card">
                        <div class="process-number">1</div>
                        <h4>Analisi</h4>
                        <p>Studio del progetto, obiettivi e target di riferimento</p>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-3">
                    <div class="process-card">
                        <div class="process-number">2</div>
                        <h4>Design</h4>
                        <p>Progettazione UI/UX e prototipo interattivo</p>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-3">
                    <div class="process-card">
                        <div class="process-number">3</div>
                        <h4>Sviluppo</h4>
                        <p>Codifica, test e ottimizzazione performance</p>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-3">
                    <div class="process-card">
                        <div class="process-number">4</div>
                        <h4>Lancio</h4>
                        <p>Deploy, formazione e supporto post-lancio</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features -->
    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-5">Caratteristiche dei Nostri Siti</h2>
            
            <div class="row g-4">
                <div class="col-md-6 col-lg-4">
                    <div class="d-flex">
                        <div class="flex-shrink-0">
                            <i class="ri-smartphone-line" style="font-size: 2rem; color: #4a00e0;"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5>100% Responsive</h5>
                            <p>Perfetti su ogni dispositivo: desktop, tablet e smartphone</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-4">
                    <div class="d-flex">
                        <div class="flex-shrink-0">
                            <i class="ri-search-line" style="font-size: 2rem; color: #4a00e0;"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5>SEO Ottimizzato</h5>
                            <p>Struttura e contenuti ottimizzati per i motori di ricerca</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-4">
                    <div class="d-flex">
                        <div class="flex-shrink-0">
                            <i class="ri-speed-line" style="font-size: 2rem; color: #4a00e0;"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5>Velocità Massima</h5>
                            <p>Tempi di caricamento ultra-rapidi per migliore UX</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-4">
                    <div class="d-flex">
                        <div class="flex-shrink-0">
                            <i class="ri-shield-check-line" style="font-size: 2rem; color: #4a00e0;"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5>Sicurezza SSL</h5>
                            <p>Certificato SSL gratuito e protezione dati garantita</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-4">
                    <div class="d-flex">
                        <div class="flex-shrink-0">
                            <i class="ri-edit-2-line" style="font-size: 2rem; color: #4a00e0;"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5>CMS Facile</h5>
                            <p>Gestisci autonomamente contenuti e immagini</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-4">
                    <div class="d-flex">
                        <div class="flex-shrink-0">
                            <i class="ri-bar-chart-box-line" style="font-size: 2rem; color: #4a00e0;"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5>Analytics</h5>
                            <p>Monitoraggio visitatori e conversioni con Google Analytics</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <h2 class="mb-4">Inizia il Tuo Progetto Web</h2>
            <p class="lead mb-5">Richiedi un preventivo gratuito e senza impegno per il tuo nuovo sito web</p>
            <div class="d-flex gap-3 justify-content-center flex-wrap">
                <a href="<?php echo url('preventivo.php'); ?>" class="btn btn-light btn-lg">
                    <i class="ri-file-list-3-line"></i> Richiedi Preventivo
                </a>
                <a href="<?php echo url('contatti.php'); ?>" class="btn btn-outline-light btn-lg">
                    <i class="ri-mail-line"></i> Contattaci
                </a>
                <a href="<?php echo whatsapp_link('Salve, vorrei informazioni per lo sviluppo di un sito web'); ?>" 
                   class="btn btn-success btn-lg" target="_blank">
                    <i class="ri-whatsapp-line"></i> WhatsApp
                </a>
            </div>
        </div>
    </section>

    <?php include '../includes/footer.php'; ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>