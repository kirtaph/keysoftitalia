<?php
/**
 * Key Soft Italia - Chi Siamo
 * Pagina informazioni aziendali
 */

// Define BASE_PATH if not defined
if (!defined('BASE_PATH')) {
    define('BASE_PATH', __DIR__ . '/');
}

require_once BASE_PATH . 'config/config.php';

// SEO Meta
$page_title = "Chi Siamo - Key Soft Italia | La Nostra Storia dal 2008";
$page_description = "Scopri la storia di Key Soft Italia, leader a Ginosa per riparazioni tecnologiche, vendita ricondizionati e assistenza informatica dal 2008.";
$page_keywords = "key soft italia storia, chi siamo, team tecnico ginosa, esperienza informatica";

// Breadcrumbs
$breadcrumbs = [
    ['label' => 'Chi Siamo', 'url' => 'chi-siamo.php']
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
        'url' => url('chi-siamo.php')
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
    <link rel="stylesheet" href="<?php echo asset('css/pages/chi-siamo.css'); ?>">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?php echo asset('images/favicon.ico'); ?>">
</head>
<body>
    
    <!-- Header -->
    <?php include 'includes/header.php'; ?>
    
    <!-- Hero Section -->
    <section class="hero hero-secondary">
        <div class="container">
            <div class="hero-content text-center">
                <h1 class="hero-title animate-fadeIn">Chi Siamo</h1>
                <p class="hero-subtitle animate-fadeIn">
                    Il tuo partner tecnologico di fiducia dal 2008
                </p>
                <?php echo generate_breadcrumbs($breadcrumbs); ?>
            </div>
        </div>
    </section>
    
    <!-- Our Story Section -->
    <section class="section section-story">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="section-header text-start">
                        <h2 class="section-title">La Nostra Storia</h2>
                        <p class="lead mb-4">
                            Key Soft Italia nasce nel 2008 con l'obiettivo di diventare 
                            il punto di riferimento tecnologico per privati e aziende a Ginosa e provincia.
                        </p>
                    </div>
                    <div class="story-content">
                        <p>
                            Da oltre <strong>15 anni</strong> siamo al fianco dei nostri clienti, 
                            offrendo soluzioni complete per ogni esigenza tecnologica. 
                            Quello che è iniziato come un piccolo laboratorio di riparazioni 
                            è oggi un'azienda strutturata con competenze che spaziano dalla 
                            riparazione di dispositivi elettronici allo sviluppo software.
                        </p>
                        <p>
                            La nostra crescita è stata guidata dalla <strong>passione per la tecnologia</strong> 
                            e dall'impegno costante nel fornire un servizio di qualità superiore. 
                            Ogni membro del nostro team condivide questi valori, 
                            garantendo professionalità e attenzione al cliente in ogni intervento.
                        </p>
                        <p>
                            Oggi Key Soft Italia è sinonimo di <strong>affidabilità, competenza e innovazione</strong>, 
                            con oltre 5000 clienti soddisfatti e un team di professionisti 
                            costantemente aggiornati sulle ultime tecnologie.
                        </p>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="story-image-wrapper">
                        <div class="story-image">
                            <div class="story-image-placeholder">
                                <i class="ri-store-2-line"></i>
                                <h3>Key Soft Italia Story</h3>
                                <span class="year-badge">2008</span>
                            </div>
                        </div>
                        <div class="story-stats">
                            <div class="stat-card">
                                <div class="stat-number">15+</div>
                                <div class="stat-label">Anni di Esperienza</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-number">5000+</div>
                                <div class="stat-label">Clienti Soddisfatti</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Our Values Section -->
    <section class="section section-values bg-light">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">I Nostri Valori</h2>
                <p class="section-subtitle">
                    I principi che guidano il nostro lavoro ogni giorno
                </p>
            </div>
            
            <div class="row g-4">
                <div class="col-lg-3 col-md-6">
                    <div class="value-card">
                        <div class="value-icon">
                            <i class="ri-heart-line"></i>
                        </div>
                        <h4 class="value-title">Passione per la Tecnologia</h4>
                        <p class="value-text">
                            La tecnologia è la nostra passione. 
                            Ci manteniamo sempre aggiornati per offrire le migliori soluzioni.
                        </p>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <div class="value-card">
                        <div class="value-icon">
                            <i class="ri-user-star-line"></i>
                        </div>
                        <h4 class="value-title">Orientati al Cliente</h4>
                        <p class="value-text">
                            Il cliente è al centro di tutto. 
                            Ascoltiamo le esigenze e proponiamo soluzioni personalizzate.
                        </p>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <div class="value-card">
                        <div class="value-icon">
                            <i class="ri-shield-check-line"></i>
                        </div>
                        <h4 class="value-title">Qualità Garantita</h4>
                        <p class="value-text">
                            Utilizziamo solo ricambi di qualità e garantiamo 
                            ogni nostro intervento per 12 mesi.
                        </p>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <div class="value-card">
                        <div class="value-icon">
                            <i class="ri-speed-line"></i>
                        </div>
                        <h4 class="value-title">Efficienza e Rapidità</h4>
                        <p class="value-text">
                            Tempi di intervento rapidi senza compromettere la qualità. 
                            Riparazioni express in 24h.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Team Section -->
    <section class="section section-team">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Il Nostro Team</h2>
                <p class="section-subtitle">
                    Professionisti qualificati al tuo servizio
                </p>
            </div>
            
            <div class="row g-4 justify-content-center">
                <div class="col-lg-4 col-md-6">
                    <div class="team-card">
                        <div class="team-avatar">
                            <i class="ri-user-line"></i>
                            <span class="team-badge">MR</span>
                        </div>
                        <h4 class="team-name">Marco Rossi</h4>
                        <p class="team-role">Fondatore & CEO</p>
                        <p class="team-bio">
                            Esperto di tecnologia con oltre 20 anni di esperienza. 
                            Guida l'azienda con passione e visione innovativa.
                        </p>
                        <div class="team-skills">
                            <span class="skill-tag">Management</span>
                            <span class="skill-tag">Strategy</span>
                            <span class="skill-tag">Innovation</span>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="team-card">
                        <div class="team-avatar">
                            <i class="ri-user-line"></i>
                            <span class="team-badge">LB</span>
                        </div>
                        <h4 class="team-name">Luca Bianchi</h4>
                        <p class="team-role">Responsabile Tecnico</p>
                        <p class="team-bio">
                            Specialista in riparazioni board-level e microsoldering. 
                            Certificato Apple e Samsung.
                        </p>
                        <div class="team-skills">
                            <span class="skill-tag">Hardware</span>
                            <span class="skill-tag">Microsoldering</span>
                            <span class="skill-tag">Diagnostica</span>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="team-card">
                        <div class="team-avatar">
                            <i class="ri-user-line"></i>
                            <span class="team-badge">GV</span>
                        </div>
                        <h4 class="team-name">Giuseppe Verdi</h4>
                        <p class="team-role">Sviluppatore Senior</p>
                        <p class="team-bio">
                            Full-stack developer con competenze in web e mobile. 
                            Esperto in soluzioni personalizzate.
                        </p>
                        <div class="team-skills">
                            <span class="skill-tag">Web Dev</span>
                            <span class="skill-tag">Mobile App</span>
                            <span class="skill-tag">Database</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Mission Section -->
    <section class="section section-mission bg-gradient-orange text-white">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h2 class="mission-title">La Nostra Missione</h2>
                    <p class="mission-text">
                        Rendere la tecnologia accessibile e affidabile per tutti, 
                        offrendo servizi di riparazione, vendita e assistenza di altissima qualità. 
                        Vogliamo essere il partner tecnologico di riferimento per la comunità di Ginosa e oltre, 
                        costruendo relazioni durature basate su fiducia e professionalità.
                    </p>
                </div>
                <div class="col-lg-4">
                    <div class="mission-stats">
                        <div class="row g-3">
                            <div class="col-6">
                                <div class="mission-stat">
                                    <div class="stat-value">5000+</div>
                                    <div class="stat-label">Clienti Soddisfatti</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mission-stat">
                                    <div class="stat-value">15+</div>
                                    <div class="stat-label">Anni di Esperienza</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mission-stat">
                                    <div class="stat-value">24h</div>
                                    <div class="stat-label">Tempo Medio Riparazione</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mission-stat">
                                    <div class="stat-value">100%</div>
                                    <div class="stat-label">Garanzia sui Lavori</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- CTA Section -->
    <section class="section section-cta">
        <div class="container">
            <div class="cta-wrapper text-center">
                <h2 class="cta-title">Scopri cosa possiamo fare per te</h2>
                <p class="cta-subtitle">
                    Contattaci oggi stesso per una consulenza gratuita e senza impegno
                </p>
                <div class="cta-buttons">
                    <a href="<?php echo url('preventivo.php'); ?>" class="btn btn-primary btn-lg">
                        <i class="ri-file-list-3-line"></i> Richiedi Preventivo
                    </a>
                    <a href="<?php echo url('contatti.php'); ?>" class="btn btn-outline-primary btn-lg">
                        <i class="ri-phone-line"></i> Contattaci
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