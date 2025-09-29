<?php
require_once '../config/config.php';
require_once '../assets/php/functions.php';

$page_title = "Social Media Marketing e Gestione - " . SITE_NAME;
$page_description = "Gestione professionale social media, campagne pubblicitarie, creazione contenuti e strategie di marketing digitale per far crescere il tuo brand.";
$current_page = 'servizi';
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <?php include '../includes/head.php'; ?>
    <style>
        .service-hero {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            padding: 100px 0;
            color: white;
            position: relative;
            overflow: hidden;
        }
        
        .service-hero::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: pulse 20s ease-in-out infinite;
        }
        
        @keyframes pulse {
            0%, 100% { transform: rotate(0deg) scale(1); }
            50% { transform: rotate(180deg) scale(1.1); }
        }
        
        .social-platform-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            height: 100%;
        }
        
        .social-platform-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }
        
        .social-icon {
            font-size: 3rem;
            margin-bottom: 20px;
        }
        
        .service-package {
            background: white;
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            height: 100%;
            transition: all 0.3s ease;
        }
        
        .service-package:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }
        
        .service-package.featured {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .service-package.featured .text-muted {
            color: rgba(255,255,255,0.8) !important;
        }
        
        .service-package.featured .btn-outline-primary {
            background: white;
            color: #667eea;
            border-color: white;
        }
        
        .price-tag {
            font-size: 2.5rem;
            font-weight: bold;
            color: #f5576c;
            margin: 20px 0;
        }
        
        .service-package.featured .price-tag {
            color: white;
        }
        
        .stats-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 60px 0;
        }
        
        .stat-card {
            text-align: center;
            padding: 30px;
        }
        
        .stat-number {
            font-size: 3rem;
            font-weight: bold;
            margin-bottom: 10px;
        }
        
        .portfolio-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin: 40px 0;
        }
        
        .portfolio-post {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        
        .portfolio-post:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }
        
        .portfolio-post img {
            width: 100%;
            height: 250px;
            object-fit: cover;
        }
        
        .portfolio-post-content {
            padding: 20px;
        }
        
        .service-list {
            list-style: none;
            padding: 0;
            margin: 30px 0;
        }
        
        .service-list li {
            padding: 12px 0;
            border-bottom: 1px solid #f0f0f0;
            display: flex;
            align-items: center;
        }
        
        .service-list li i {
            color: #4caf50;
            margin-right: 10px;
            font-size: 1.2rem;
        }
        
        .process-timeline {
            position: relative;
            padding: 40px 0;
        }
        
        .process-item {
            display: flex;
            margin-bottom: 40px;
            position: relative;
        }
        
        .process-item::after {
            content: '';
            position: absolute;
            left: 30px;
            top: 60px;
            width: 2px;
            height: calc(100% + 20px);
            background: #e2e8f0;
        }
        
        .process-item:last-child::after {
            display: none;
        }
        
        .process-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            flex-shrink: 0;
            z-index: 1;
        }
        
        .process-content {
            margin-left: 30px;
            flex: 1;
        }
        
        .cta-section {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
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
                            <li class="breadcrumb-item active" aria-current="page" style="color: white;">Social Media Marketing</li>
                        </ol>
                    </nav>
                    <h1 class="display-4 fw-bold mb-4">Social Media Marketing</h1>
                    <p class="lead mb-4">Fai crescere il tuo brand sui social media con strategie personalizzate e contenuti di qualità</p>
                    <div class="d-flex gap-4 flex-wrap">
                        <div>
                            <i class="ri-user-follow-line"></i> +Followers
                        </div>
                        <div>
                            <i class="ri-heart-line"></i> +Engagement
                        </div>
                        <div>
                            <i class="ri-line-chart-line"></i> +Conversioni
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Social Platforms -->
    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-5">Piattaforme Social Gestite</h2>
            
            <div class="row g-4">
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="social-platform-card">
                        <i class="ri-facebook-circle-fill social-icon" style="color: #1877f2;"></i>
                        <h5>Facebook</h5>
                        <small class="text-muted">2.9B utenti</small>
                    </div>
                </div>
                
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="social-platform-card">
                        <i class="ri-instagram-fill social-icon" style="color: #e4405f;"></i>
                        <h5>Instagram</h5>
                        <small class="text-muted">2B utenti</small>
                    </div>
                </div>
                
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="social-platform-card">
                        <i class="ri-linkedin-box-fill social-icon" style="color: #0077b5;"></i>
                        <h5>LinkedIn</h5>
                        <small class="text-muted">810M utenti</small>
                    </div>
                </div>
                
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="social-platform-card">
                        <i class="ri-tiktok-fill social-icon" style="color: #000000;"></i>
                        <h5>TikTok</h5>
                        <small class="text-muted">1B utenti</small>
                    </div>
                </div>
                
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="social-platform-card">
                        <i class="ri-youtube-fill social-icon" style="color: #ff0000;"></i>
                        <h5>YouTube</h5>
                        <small class="text-muted">2.5B utenti</small>
                    </div>
                </div>
                
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="social-platform-card">
                        <i class="ri-twitter-x-fill social-icon" style="color: #000000;"></i>
                        <h5>X (Twitter)</h5>
                        <small class="text-muted">450M utenti</small>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Service Packages -->
    <section class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-5">Pacchetti Social Media Management</h2>
            
            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="service-package">
                        <h3>Starter</h3>
                        <p class="text-muted">Perfetto per piccole attività</p>
                        <div class="price-tag">€299/mese</div>
                        
                        <ul class="service-list">
                            <li><i class="ri-check-line"></i> 2 Piattaforme social</li>
                            <li><i class="ri-check-line"></i> 12 Post al mese</li>
                            <li><i class="ri-check-line"></i> Creazione contenuti base</li>
                            <li><i class="ri-check-line"></i> Calendario editoriale</li>
                            <li><i class="ri-check-line"></i> Report mensile</li>
                            <li><i class="ri-close-line text-muted"></i> Advertising</li>
                            <li><i class="ri-close-line text-muted"></i> Influencer marketing</li>
                        </ul>
                        
                        <button class="btn btn-outline-primary w-100">Inizia ora</button>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <div class="service-package featured">
                        <h3>Professional</h3>
                        <p style="opacity: 0.9;">Il più scelto dalle aziende</p>
                        <div class="price-tag">€599/mese</div>
                        
                        <ul class="service-list">
                            <li><i class="ri-check-line"></i> 4 Piattaforme social</li>
                            <li><i class="ri-check-line"></i> 24 Post al mese</li>
                            <li><i class="ri-check-line"></i> Contenuti professionali</li>
                            <li><i class="ri-check-line"></i> Stories e Reels</li>
                            <li><i class="ri-check-line"></i> Community management</li>
                            <li><i class="ri-check-line"></i> Campagne advertising (budget escluso)</li>
                            <li><i class="ri-check-line"></i> Report settimanale</li>
                        </ul>
                        
                        <button class="btn btn-outline-primary w-100">Più venduto</button>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <div class="service-package">
                        <h3>Enterprise</h3>
                        <p class="text-muted">Soluzione completa</p>
                        <div class="price-tag">€1499/mese</div>
                        
                        <ul class="service-list">
                            <li><i class="ri-check-line"></i> Tutte le piattaforme</li>
                            <li><i class="ri-check-line"></i> Post illimitati</li>
                            <li><i class="ri-check-line"></i> Video professionali</li>
                            <li><i class="ri-check-line"></i> Shooting fotografici</li>
                            <li><i class="ri-check-line"></i> Influencer marketing</li>
                            <li><i class="ri-check-line"></i> Gestione crisi</li>
                            <li><i class="ri-check-line"></i> Account manager dedicato</li>
                        </ul>
                        
                        <button class="btn btn-outline-primary w-100">Contattaci</button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Statistics -->
    <section class="stats-section">
        <div class="container">
            <h2 class="text-center mb-5 text-white">I Nostri Risultati</h2>
            
            <div class="row">
                <div class="col-md-3 col-6">
                    <div class="stat-card">
                        <div class="stat-number">+250%</div>
                        <div>Crescita Media Followers</div>
                    </div>
                </div>
                
                <div class="col-md-3 col-6">
                    <div class="stat-card">
                        <div class="stat-number">+180%</div>
                        <div>Aumento Engagement</div>
                    </div>
                </div>
                
                <div class="col-md-3 col-6">
                    <div class="stat-card">
                        <div class="stat-number">50+</div>
                        <div>Clienti Attivi</div>
                    </div>
                </div>
                
                <div class="col-md-3 col-6">
                    <div class="stat-card">
                        <div class="stat-number">10M+</div>
                        <div>Impressioni Mensili</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Detail -->
    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-5">Cosa Include il Nostro Servizio</h2>
            
            <div class="row g-4">
                <div class="col-md-6 col-lg-4">
                    <div class="d-flex">
                        <div class="flex-shrink-0">
                            <i class="ri-calendar-line" style="font-size: 2rem; color: #f5576c;"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5>Strategia e Pianificazione</h5>
                            <p>Analisi del brand, definizione obiettivi e calendario editoriale personalizzato</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-4">
                    <div class="d-flex">
                        <div class="flex-shrink-0">
                            <i class="ri-brush-line" style="font-size: 2rem; color: #f5576c;"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5>Creazione Contenuti</h5>
                            <p>Grafiche, video, copy creativi e storytelling coinvolgente</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-4">
                    <div class="d-flex">
                        <div class="flex-shrink-0">
                            <i class="ri-megaphone-line" style="font-size: 2rem; color: #f5576c;"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5>Advertising</h5>
                            <p>Campagne pubblicitarie mirate per aumentare visibilità e conversioni</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-4">
                    <div class="d-flex">
                        <div class="flex-shrink-0">
                            <i class="ri-chat-3-line" style="font-size: 2rem; color: #f5576c;"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5>Community Management</h5>
                            <p>Gestione commenti, messaggi e interazione con la community</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-4">
                    <div class="d-flex">
                        <div class="flex-shrink-0">
                            <i class="ri-bar-chart-line" style="font-size: 2rem; color: #f5576c;"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5>Analytics e Report</h5>
                            <p>Monitoraggio KPI e report dettagliati sulle performance</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-4">
                    <div class="d-flex">
                        <div class="flex-shrink-0">
                            <i class="ri-user-star-line" style="font-size: 2rem; color: #f5576c;"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5>Influencer Marketing</h5>
                            <p>Collaborazioni con influencer per ampliare il reach</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Portfolio -->
    <section class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-5">Alcuni Nostri Lavori</h2>
            
            <div class="portfolio-grid">
                <div class="portfolio-post">
                    <img src="https://via.placeholder.com/400x400/f093fb/ffffff?text=Fashion+Brand" alt="Fashion Brand">
                    <div class="portfolio-post-content">
                        <h5>Fashion Brand</h5>
                        <p class="text-muted mb-0">Instagram: +15K followers in 3 mesi</p>
                    </div>
                </div>
                
                <div class="portfolio-post">
                    <img src="https://via.placeholder.com/400x400/f5576c/ffffff?text=Restaurant" alt="Restaurant">
                    <div class="portfolio-post-content">
                        <h5>Ristorante Gourmet</h5>
                        <p class="text-muted mb-0">Facebook: +300% prenotazioni</p>
                    </div>
                </div>
                
                <div class="portfolio-post">
                    <img src="https://via.placeholder.com/400x400/667eea/ffffff?text=Tech+Startup" alt="Tech Startup">
                    <div class="portfolio-post-content">
                        <h5>Tech Startup</h5>
                        <p class="text-muted mb-0">LinkedIn: 5K+ lead qualificati</p>
                    </div>
                </div>
                
                <div class="portfolio-post">
                    <img src="https://via.placeholder.com/400x400/764ba2/ffffff?text=Beauty+Salon" alt="Beauty Salon">
                    <div class="portfolio-post-content">
                        <h5>Beauty Salon</h5>
                        <p class="text-muted mb-0">TikTok: Video virale 1M views</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Process -->
    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-5">Come Lavoriamo</h2>
            
            <div class="process-timeline">
                <div class="process-item">
                    <div class="process-icon">
                        <i class="ri-search-eye-line"></i>
                    </div>
                    <div class="process-content">
                        <h4>1. Analisi e Audit</h4>
                        <p>Analizziamo la tua presenza online attuale, i competitor e definiamo gli obiettivi</p>
                    </div>
                </div>
                
                <div class="process-item">
                    <div class="process-icon">
                        <i class="ri-roadmap-line"></i>
                    </div>
                    <div class="process-content">
                        <h4>2. Strategia Personalizzata</h4>
                        <p>Creiamo una strategia su misura per il tuo brand e il tuo target</p>
                    </div>
                </div>
                
                <div class="process-item">
                    <div class="process-icon">
                        <i class="ri-magic-line"></i>
                    </div>
                    <div class="process-content">
                        <h4>3. Creazione e Pubblicazione</h4>
                        <p>Produciamo contenuti di qualità e li pubblichiamo secondo il calendario editoriale</p>
                    </div>
                </div>
                
                <div class="process-item">
                    <div class="process-icon">
                        <i class="ri-line-chart-line"></i>
                    </div>
                    <div class="process-content">
                        <h4>4. Monitoraggio e Ottimizzazione</h4>
                        <p>Monitoriamo le performance e ottimizziamo continuamente la strategia</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <h2 class="mb-4">Fai Crescere il Tuo Brand Sui Social</h2>
            <p class="lead mb-5">Richiedi una consulenza gratuita e scopri come possiamo aiutarti</p>
            <div class="d-flex gap-3 justify-content-center flex-wrap">
                <a href="<?php echo url('preventivo.php'); ?>" class="btn btn-light btn-lg">
                    <i class="ri-file-list-3-line"></i> Richiedi Preventivo
                </a>
                <a href="tel:<?php echo PHONE_PRIMARY; ?>" class="btn btn-outline-light btn-lg">
                    <i class="ri-phone-line"></i> Chiama Ora
                </a>
                <a href="<?php echo whatsapp_link('Salve, vorrei informazioni sui servizi social media marketing'); ?>" 
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