<?php
/**
 * Key Soft Italia - Video Tutorial e Guide
 * Pagina con video tutorial, guide e recensioni
 */

// Define BASE_PATH if not defined
if (!defined('BASE_PATH')) {
    define('BASE_PATH', __DIR__ . '/');
}

require_once BASE_PATH . 'config/config.php';

// SEO Meta
$page_title = "Video Tutorial e Guide - Key Soft Italia | Impara con i Nostri Video";
$page_description = "Guarda i nostri video tutorial su riparazioni, manutenzione dispositivi e guide pratiche. Video recensioni prodotti e consigli degli esperti Key Soft Italia.";
$page_keywords = "video tutorial riparazioni, guide smartphone, video assistenza computer, tutorial tecnologia ginosa";

// Breadcrumbs
$breadcrumbs = [
    ['label' => 'Video', 'url' => 'video.php']
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
        'url' => url('video.php')
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
    <link rel="stylesheet" href="<?php echo asset('css/pages/video.css'); ?>">
    
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
                <h1 class="hero-title animate-fadeIn">Video e Tutorial</h1>
                <p class="hero-subtitle animate-fadeIn">
                    Guide pratiche, recensioni e consigli dai nostri esperti
                </p>
                <?php echo generate_breadcrumbs($breadcrumbs); ?>
            </div>
        </div>
    </section>
    
    <!-- Video Categories -->
    <section class="section section-categories">
        <div class="container">
            <div class="categories-filter">
                <button class="filter-btn active" data-filter="all">
                    <i class="ri-apps-line"></i> Tutti
                </button>
                <button class="filter-btn" data-filter="tutorial">
                    <i class="ri-book-open-line"></i> Tutorial
                </button>
                <button class="filter-btn" data-filter="riparazioni">
                    <i class="ri-tools-line"></i> Riparazioni
                </button>
                <button class="filter-btn" data-filter="recensioni">
                    <i class="ri-star-line"></i> Recensioni
                </button>
                <button class="filter-btn" data-filter="consigli">
                    <i class="ri-lightbulb-line"></i> Consigli
                </button>
                <button class="filter-btn" data-filter="novita">
                    <i class="ri-sparkles-line"></i> Novità
                </button>
            </div>
        </div>
    </section>
    
    <!-- Featured Video -->
    <section class="section section-featured">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Video in Evidenza</h2>
            </div>
            
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="featured-video-card">
                        <div class="video-wrapper">
                            <div class="video-placeholder">
                                <i class="ri-play-circle-line"></i>
                                <h3>Come Sostituire lo Schermo dell'iPhone</h3>
                                <p>Guida completa passo dopo passo</p>
                            </div>
                            <div class="video-badge">
                                <span class="badge bg-danger">NUOVO</span>
                            </div>
                        </div>
                        <div class="video-info">
                            <h3 class="video-title">Come Sostituire lo Schermo dell'iPhone - Tutorial Completo</h3>
                            <p class="video-description">
                                In questo video tutorial ti mostriamo come sostituire lo schermo di un iPhone 
                                in modo professionale. Scopri tutti i passaggi, gli strumenti necessari e i 
                                consigli per evitare errori comuni.
                            </p>
                            <div class="video-meta">
                                <span><i class="ri-calendar-line"></i> 2 giorni fa</span>
                                <span><i class="ri-eye-line"></i> 1.2K visualizzazioni</span>
                                <span><i class="ri-time-line"></i> 15:30</span>
                                <span class="text-warning"><i class="ri-star-fill"></i> Tutorial</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Video Grid -->
    <section class="section section-videos">
        <div class="container">
            <div class="row g-4" id="videoGrid">
                <!-- Tutorial Videos -->
                <div class="col-lg-4 col-md-6 video-item" data-category="tutorial">
                    <div class="video-card">
                        <div class="video-thumbnail">
                            <div class="video-placeholder-small">
                                <i class="ri-play-circle-line"></i>
                            </div>
                            <div class="video-duration">12:45</div>
                            <div class="video-category">Tutorial</div>
                        </div>
                        <div class="video-content">
                            <h4 class="video-title">Configurare Backup Automatico su Cloud</h4>
                            <p class="video-excerpt">
                                Proteggi i tuoi dati con il backup automatico. Guida completa per tutti i dispositivi.
                            </p>
                            <div class="video-footer">
                                <span><i class="ri-calendar-line"></i> 1 settimana fa</span>
                                <span><i class="ri-eye-line"></i> 850</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Riparazioni Videos -->
                <div class="col-lg-4 col-md-6 video-item" data-category="riparazioni">
                    <div class="video-card">
                        <div class="video-thumbnail">
                            <div class="video-placeholder-small">
                                <i class="ri-play-circle-line"></i>
                            </div>
                            <div class="video-duration">18:20</div>
                            <div class="video-category">Riparazioni</div>
                        </div>
                        <div class="video-content">
                            <h4 class="video-title">Riparazione Batteria Samsung Galaxy</h4>
                            <p class="video-excerpt">
                                Come sostituire la batteria di un Samsung Galaxy in sicurezza.
                            </p>
                            <div class="video-footer">
                                <span><i class="ri-calendar-line"></i> 2 settimane fa</span>
                                <span><i class="ri-eye-line"></i> 1.5K</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Recensioni Videos -->
                <div class="col-lg-4 col-md-6 video-item" data-category="recensioni">
                    <div class="video-card">
                        <div class="video-thumbnail">
                            <div class="video-placeholder-small">
                                <i class="ri-play-circle-line"></i>
                            </div>
                            <div class="video-duration">8:15</div>
                            <div class="video-category">Recensioni</div>
                        </div>
                        <div class="video-content">
                            <h4 class="video-title">iPhone 15 Ricondizionato - Vale la Pena?</h4>
                            <p class="video-excerpt">
                                Recensione completa iPhone 15 ricondizionato vs nuovo. Pro e contro.
                            </p>
                            <div class="video-footer">
                                <span><i class="ri-calendar-line"></i> 3 giorni fa</span>
                                <span><i class="ri-eye-line"></i> 2.1K</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Consigli Videos -->
                <div class="col-lg-4 col-md-6 video-item" data-category="consigli">
                    <div class="video-card">
                        <div class="video-thumbnail">
                            <div class="video-placeholder-small">
                                <i class="ri-play-circle-line"></i>
                            </div>
                            <div class="video-duration">10:30</div>
                            <div class="video-category">Consigli</div>
                        </div>
                        <div class="video-content">
                            <h4 class="video-title">10 Trucchi per Velocizzare il PC</h4>
                            <p class="video-excerpt">
                                Ottimizza le prestazioni del tuo computer con questi semplici trucchi.
                            </p>
                            <div class="video-footer">
                                <span><i class="ri-calendar-line"></i> 5 giorni fa</span>
                                <span><i class="ri-eye-line"></i> 980</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- More Tutorial -->
                <div class="col-lg-4 col-md-6 video-item" data-category="tutorial">
                    <div class="video-card">
                        <div class="video-thumbnail">
                            <div class="video-placeholder-small">
                                <i class="ri-play-circle-line"></i>
                            </div>
                            <div class="video-duration">15:00</div>
                            <div class="video-category">Tutorial</div>
                        </div>
                        <div class="video-content">
                            <h4 class="video-title">Installare Windows 11 da Zero</h4>
                            <p class="video-excerpt">
                                Guida completa all'installazione pulita di Windows 11 con tutti i driver.
                            </p>
                            <div class="video-footer">
                                <span><i class="ri-calendar-line"></i> 1 mese fa</span>
                                <span><i class="ri-eye-line"></i> 3.2K</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Novità Videos -->
                <div class="col-lg-4 col-md-6 video-item" data-category="novita">
                    <div class="video-card">
                        <div class="video-thumbnail">
                            <div class="video-placeholder-small">
                                <i class="ri-play-circle-line"></i>
                            </div>
                            <div class="video-duration">6:45</div>
                            <div class="video-category">Novità</div>
                        </div>
                        <div class="video-content">
                            <h4 class="video-title">Le Migliori App del 2024</h4>
                            <p class="video-excerpt">
                                Scopri le app più innovative e utili uscite quest'anno.
                            </p>
                            <div class="video-footer">
                                <span><i class="ri-calendar-line"></i> Ieri</span>
                                <span><i class="ri-eye-line"></i> 450</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- More Riparazioni -->
                <div class="col-lg-4 col-md-6 video-item" data-category="riparazioni">
                    <div class="video-card">
                        <div class="video-thumbnail">
                            <div class="video-placeholder-small">
                                <i class="ri-play-circle-line"></i>
                            </div>
                            <div class="video-duration">22:15</div>
                            <div class="video-category">Riparazioni</div>
                        </div>
                        <div class="video-content">
                            <h4 class="video-title">Riparazione MacBook Pro - Problemi Comuni</h4>
                            <p class="video-excerpt">
                                Come risolvere i problemi più frequenti dei MacBook Pro.
                            </p>
                            <div class="video-footer">
                                <span><i class="ri-calendar-line"></i> 2 settimane fa</span>
                                <span><i class="ri-eye-line"></i> 1.8K</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- More Consigli -->
                <div class="col-lg-4 col-md-6 video-item" data-category="consigli">
                    <div class="video-card">
                        <div class="video-thumbnail">
                            <div class="video-placeholder-small">
                                <i class="ri-play-circle-line"></i>
                            </div>
                            <div class="video-duration">9:00</div>
                            <div class="video-category">Consigli</div>
                        </div>
                        <div class="video-content">
                            <h4 class="video-title">Proteggere lo Smartphone dai Virus</h4>
                            <p class="video-excerpt">
                                Consigli pratici per mantenere sicuro il tuo dispositivo mobile.
                            </p>
                            <div class="video-footer">
                                <span><i class="ri-calendar-line"></i> 4 giorni fa</span>
                                <span><i class="ri-eye-line"></i> 670</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- More Recensioni -->
                <div class="col-lg-4 col-md-6 video-item" data-category="recensioni">
                    <div class="video-card">
                        <div class="video-thumbnail">
                            <div class="video-placeholder-small">
                                <i class="ri-play-circle-line"></i>
                            </div>
                            <div class="video-duration">11:30</div>
                            <div class="video-category">Recensioni</div>
                        </div>
                        <div class="video-content">
                            <h4 class="video-title">Migliori Accessori Tech sotto i 50€</h4>
                            <p class="video-excerpt">
                                I gadget tech più utili e convenienti del momento.
                            </p>
                            <div class="video-footer">
                                <span><i class="ri-calendar-line"></i> 1 settimana fa</span>
                                <span><i class="ri-eye-line"></i> 890</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Load More -->
            <div class="text-center mt-5">
                <button class="btn btn-outline-primary btn-lg">
                    <i class="ri-refresh-line"></i> Carica Altri Video
                </button>
            </div>
        </div>
    </section>
    
    <!-- YouTube Channel CTA -->
    <section class="section section-youtube-cta bg-gradient-red text-white">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <div class="youtube-content">
                        <div class="youtube-icon">
                            <i class="ri-youtube-fill"></i>
                        </div>
                        <div>
                            <h2 class="youtube-title">Seguici su YouTube</h2>
                            <p class="youtube-text">
                                Iscriviti al nostro canale per non perdere i nuovi video tutorial, 
                                recensioni e consigli dai nostri esperti.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <a href="#" class="btn btn-white btn-lg">
                        <i class="ri-youtube-line"></i> Iscriviti al Canale
                    </a>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Request Video Section -->
    <section class="section section-request">
        <div class="container">
            <div class="request-card">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <h3 class="request-title">
                            <i class="ri-questionnaire-line"></i> Non trovi quello che cerchi?
                        </h3>
                        <p class="request-text">
                            Suggerisci un argomento per il nostro prossimo video tutorial. 
                            Siamo sempre alla ricerca di nuovi contenuti utili per la nostra community!
                        </p>
                    </div>
                    <div class="col-lg-4 text-lg-end">
                        <button class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#requestModal">
                            <i class="ri-add-circle-line"></i> Richiedi un Video
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Request Modal -->
    <div class="modal fade" id="requestModal" tabindex="-1" aria-labelledby="requestModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="requestModalLabel">
                        <i class="ri-video-add-line"></i> Richiedi un Video Tutorial
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="videoRequestForm">
                        <div class="mb-3">
                            <label for="requestTopic" class="form-label">Argomento del Video *</label>
                            <input type="text" class="form-control" id="requestTopic" required
                                   placeholder="Es: Come pulire la ventola del notebook">
                        </div>
                        <div class="mb-3">
                            <label for="requestDescription" class="form-label">Descrizione (opzionale)</label>
                            <textarea class="form-control" id="requestDescription" rows="3"
                                      placeholder="Fornisci più dettagli su cosa vorresti vedere nel video..."></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="requestEmail" class="form-label">La tua Email *</label>
                            <input type="email" class="form-control" id="requestEmail" required
                                   placeholder="ti@tuaemail.com">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                    <button type="submit" form="videoRequestForm" class="btn btn-primary">
                        <i class="ri-send-plane-line"></i> Invia Richiesta
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo asset('js/main.js'); ?>"></script>
    
    <!-- Video Filter Script -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Video filtering
        const filterBtns = document.querySelectorAll('.filter-btn');
        const videoItems = document.querySelectorAll('.video-item');
        
        filterBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                // Remove active class from all buttons
                filterBtns.forEach(b => b.classList.remove('active'));
                // Add active class to clicked button
                btn.classList.add('active');
                
                const filter = btn.getAttribute('data-filter');
                
                videoItems.forEach(item => {
                    if (filter === 'all' || item.getAttribute('data-category') === filter) {
                        item.style.display = 'block';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        });
        
        // Video request form
        const requestForm = document.getElementById('videoRequestForm');
        if (requestForm) {
            requestForm.addEventListener('submit', (e) => {
                e.preventDefault();
                // Here you would normally send the form data
                alert('Grazie per il tuo suggerimento! Lo prenderemo in considerazione.');
                // Close modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('requestModal'));
                modal.hide();
                requestForm.reset();
            });
        }
    });
    </script>
    
    <!-- Set BASE_URL for JavaScript -->
    <script>
        window.KS_CONFIG = {
            baseUrl: '<?php echo BASE_URL; ?>',
            whatsappNumber: '<?php echo WHATSAPP_NUMBER; ?>'
        };
    </script>
</body>
</html>