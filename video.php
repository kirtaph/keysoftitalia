<?php
/**
 * Key Soft Italia - Video Prodotti e Recensioni
 * Pagina con video presentazioni prodotti, recensioni e novità (Grafica coordinata con chi-siamo.php)
 */

// Define BASE_PATH if not defined
if (!defined('BASE_PATH')) {
    define('BASE_PATH', __DIR__ . '/');
}

require_once BASE_PATH . 'config/config.php';

// Fetch all active videos from database
$videos = [];
try {
    $stmt = $pdo->query("SELECT * FROM videos WHERE status = 1 ORDER BY created_at DESC");
    $videos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $videos = [];
}

// Extract featured video
$featured_video = null;
if (!empty($videos)) {
    foreach ($videos as $v) {
        if ($v['is_featured'] == 1) {
            $featured_video = $v;
            break;
        }
    }
    if (!$featured_video) {
        $featured_video = $videos[0];
    }
}

// SEO Meta
$page_title = "Video Prodotti e Recensioni - Key Soft Italia";
$page_description = "Guarda le nostre video presentazioni dei dispositivi tech e prodotti ricondizionati in negozio. Recensioni e novità da Key Soft Italia.";

// Breadcrumbs
$breadcrumbs = [
    ['label' => 'Video', 'url' => 'video.php']
];
?>
<!DOCTYPE html>
<html lang="it">
<head>
  <?php include 'includes/head.php'; ?>
  <!-- CSS di pagina -->
  <link rel="stylesheet" href="<?php echo asset_version('css/pages/video.css'); ?>">
</head>
<body>
    
    <!-- Header -->
    <?php include 'includes/header.php'; ?>
    
    <!-- HERO -->
    <section class="hero hero-secondary text-center">
      <div class="hero-pattern"></div>
      <div class="container position-relative z-2" data-aos="fade-up">
        <div class="hero-icon mb-3" data-aos="zoom-in">
          <i class="ri-video-line"></i>
        </div>
        <h1 class="hero-title text-white" data-aos="fade-up" data-aos-delay="100">
          I Nostri <span class="text-gradient">Video</span>
        </h1>
        <p class="hero-subtitle" data-aos="fade-up" data-aos-delay="200">
          Presentazioni prodotti, recensioni e novità tecnologiche dal nostro store
        </p>
        <div class="hero-breadcrumb mt-4" data-aos="fade-up" data-aos-delay="400">
          <?php echo generate_breadcrumbs($breadcrumbs); ?>
        </div>
      </div>
    </section>
    
    <!-- Video Categories -->
    <section class="section section-categories" data-aos="fade-up">
        <div class="container">
            <div class="categories-filter">
                <button class="filter-btn active" data-filter="all">
                    <i class="ri-apps-line"></i> Tutti
                </button>
                <button class="filter-btn" data-filter="prodotti">
                    <i class="ri-box-3-line"></i> Prodotti
                </button>
                <button class="filter-btn" data-filter="recensioni">
                    <i class="ri-star-line"></i> Recensioni
                </button>
                <button class="filter-btn" data-filter="novita">
                    <i class="ri-sparkles-line"></i> Novità
                </button>
                <button class="filter-btn" data-filter="consigli">
                    <i class="ri-lightbulb-line"></i> Consigli
                </button>
                <button class="filter-btn" data-filter="tutorial">
                    <i class="ri-book-open-line"></i> Tutorial
                </button>
            </div>
        </div>
    </section>
    
    <?php if ($featured_video): ?>
    <!-- Featured Video -->
    <section class="section section-featured">
        <div class="container">
            <div class="section-header text-center" data-aos="fade-up">
                <h2 class="section-title">In <span class="text-gradient">Evidenza</span></h2>
            </div>
            
            <div class="row justify-content-center mt-5">
                <div class="col-lg-10" data-aos="fade-up" data-aos-delay="100">
                    <div class="featured-video-card">
                        <div class="video-wrapper">
                            <?php if ($featured_video['cover_image']): ?>
                                <img src="<?php echo asset($featured_video['cover_image']); ?>" class="img-fluid w-100 h-100" style="position: absolute; top: 0; left: 0; object-fit: cover;" alt="<?php echo htmlspecialchars($featured_video['title']); ?>">
                            <?php endif; ?>
                            <div class="video-placeholder play-trigger" data-fb-url="<?php echo htmlspecialchars($featured_video['fb_video_url']); ?>" style="<?php echo $featured_video['cover_image'] ? 'background: rgba(0,0,0,0.35);' : ''; ?> cursor: pointer;">
                                <i class="ri-play-circle-line"></i>
                                <h3><?php echo htmlspecialchars($featured_video['title']); ?></h3>
                                <p>Clicca per guardare il video</p>
                            </div>
                            <div class="video-badge">
                                <span class="badge bg-danger">NUOVO</span>
                            </div>
                        </div>
                        <div class="video-info">
                            <h3 class="video-title"><?php echo htmlspecialchars($featured_video['title']); ?></h3>
                            <?php if ($featured_video['description']): ?>
                                <p class="video-description">
                                    <?php echo nl2br(htmlspecialchars($featured_video['description'])); ?>
                                </p>
                            <?php endif; ?>
                            <div class="video-meta">
                                <span><i class="ri-calendar-line"></i> <?php echo date('d/m/Y', strtotime($featured_video['created_at'])); ?></span>
                                <?php if ($featured_video['duration']): ?>
                                    <span><i class="ri-time-line"></i> <?php echo htmlspecialchars($featured_video['duration']); ?></span>
                                <?php endif; ?>
                                <span class="text-warning"><i class="ri-star-fill"></i> <?php 
                                    switch($featured_video['category']) {
                                        case 'prodotti': echo 'Presentazione Prodotti'; break;
                                        case 'recensioni': echo 'Recensione'; break;
                                        case 'novita': echo 'Novità'; break;
                                        case 'consigli': echo 'Consiglio'; break;
                                        case 'tutorial': echo 'Tutorial'; break;
                                        default: echo htmlspecialchars($featured_video['category']);
                                    }
                                ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>
    
    <!-- Video Grid -->
    <section class="section section-videos">
        <div class="container">
            <div class="section-header text-center" data-aos="fade-up">
                <h2 class="section-title">Tutti i <span class="text-gradient">Video</span></h2>
                <p class="section-subtitle">Esplora le presentazioni e le recensioni dei nostri dispositivi</p>
            </div>
            
            <div class="row g-4 mt-2" id="videoGrid">
                <?php if (empty($videos)): ?>
                    <div class="col-12 text-center py-5" data-aos="fade-up">
                        <div class="text-muted py-5">
                            <i class="ri-video-line display-1 d-block mb-3 text-secondary"></i>
                            <h4 class="fw-bold">Nessun video disponibile</h4>
                            <p>I video caricati dall'amministrazione compariranno qui.</p>
                        </div>
                    </div>
                <?php else: ?>
                    <?php 
                    $delay = 0;
                    foreach ($videos as $video): 
                        $delay = ($delay >= 300) ? 100 : $delay + 100;
                    ?>
                        <div class="col-lg-4 col-md-6 video-item" data-category="<?php echo htmlspecialchars($video['category']); ?>" data-aos="fade-up" data-aos-delay="<?php echo $delay; ?>">
                            <div class="video-card">
                                <div class="video-thumbnail">
                                    <?php if ($video['cover_image']): ?>
                                        <img src="<?php echo asset($video['cover_image']); ?>" class="img-fluid w-100 h-100" style="position: absolute; top: 0; left: 0; object-fit: cover;" alt="<?php echo htmlspecialchars($video['title']); ?>">
                                    <?php endif; ?>
                                    <div class="video-placeholder-small play-trigger" data-fb-url="<?php echo htmlspecialchars($video['fb_video_url']); ?>" style="<?php echo $video['cover_image'] ? 'background: rgba(0,0,0,0.3);' : ''; ?> cursor: pointer;">
                                        <i class="ri-play-circle-line"></i>
                                    </div>
                                    <?php if ($video['duration']): ?>
                                        <div class="video-duration"><?php echo htmlspecialchars($video['duration']); ?></div>
                                    <?php endif; ?>
                                    <div class="video-category"><?php 
                                        switch($video['category']) {
                                            case 'prodotti': echo 'Prodotti'; break;
                                            case 'recensioni': echo 'Recensione'; break;
                                            case 'novita': echo 'Novità'; break;
                                            case 'consigli': echo 'Consiglio'; break;
                                            case 'tutorial': echo 'Tutorial'; break;
                                            default: echo htmlspecialchars($video['category']);
                                        }
                                    ?></div>
                                </div>
                                <div class="video-content">
                                    <h4 class="video-title"><?php echo htmlspecialchars($video['title']); ?></h4>
                                    <?php if ($video['description']): ?>
                                        <p class="video-excerpt">
                                            <?php 
                                                $desc = strip_tags($video['description']);
                                                echo htmlspecialchars(mb_strimwidth($desc, 0, 110, '...'));
                                            ?>
                                        </p>
                                    <?php endif; ?>
                                    <div class="video-footer">
                                        <span><i class="ri-calendar-line"></i> <?php echo date('d/m/Y', strtotime($video['created_at'])); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>
    
    <!-- Facebook Page CTA -->
    <section class="section section-facebook-cta bg-gradient-facebook text-white">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8" data-aos="fade-right">
                    <div class="facebook-content">
                        <div class="facebook-icon">
                            <i class="ri-facebook-circle-fill"></i>
                        </div>
                        <div>
                            <h2 class="facebook-title">Seguici su Facebook</h2>
                            <p class="facebook-text">
                                Resta sintonizzato sulla nostra pagina ufficiale per guardare i nuovi video, 
                                le presentazioni dei prodotti in tempo reale e tutte le nostre ultime offerte.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 text-lg-end mt-4 mt-lg-0" data-aos="fade-left">
                    <a href="<?php echo SOCIAL_FACEBOOK; ?>" target="_blank" rel="noopener noreferrer" class="btn btn-white btn-lg">
                        <i class="ri-facebook-line"></i> Vai alla Pagina Facebook
                    </a>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Request Video Section -->
    <section class="section section-request">
        <div class="container" data-aos="fade-up">
            <div class="request-card">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <h3 class="request-title">
                            <i class="ri-questionnaire-line"></i> Vuoi vedere un prodotto in dettaglio?
                        </h3>
                        <p class="request-text">
                            Suggerisci un dispositivo o un argomento di cui vorresti vedere un video di presentazione.
                            Siamo pronti a mostrarti tutto ciò che desideri sapere!
                        </p>
                    </div>
                    <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
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
                        <i class="ri-video-add-line"></i> Richiedi un Video Prodotto
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="videoRequestForm">
                        <div class="mb-3">
                            <label for="requestTopic" class="form-label">Modello o Argomento del Video *</label>
                            <input type="text" class="form-control" id="requestTopic" required
                                   placeholder="Es: iPhone 15 Pro Max colore Titanio Naturale">
                        </div>
                        <div class="mb-3">
                            <label for="requestDescription" class="form-label">Dettagli della richiesta (opzionale)</label>
                            <textarea class="form-control" id="requestDescription" rows="3"
                                      placeholder="Cosa ti piacerebbe vedere in particolare? Prestazioni, estetica, fotocamera..."></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="requestEmail" class="form-label">La tua Email *</label>
                            <input type="email" class="form-control" id="requestEmail" required
                                   placeholder="tu@email.com">
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

    <!-- Video Player Modal -->
    <div class="modal fade" id="videoPlayerModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-lg modal-dialog-centered" id="videoPlayerDialog">
            <div class="modal-content border-0 bg-dark text-white shadow-lg">
                <div class="modal-header border-0 bg-dark text-white p-3 d-flex align-items-center justify-content-between">
                    <h5 class="modal-title text-truncate fw-bold" id="videoPlayerTitle">Riproduttore Video</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <div class="ratio ratio-16x9 bg-black" id="videoPlayerRatioContainer">
                        <iframe id="videoPlayerIframe" src="" allowfullscreen allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share" style="border:none; overflow:hidden; width:100%; height:100%;"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Set BASE_URL for JavaScript -->
    <script>
        window.KS_CONFIG = {
            baseUrl: '<?php echo BASE_URL; ?>',
            whatsappNumber: '<?php echo WHATSAPP_NUMBER; ?>'
        };
    </script>
    
    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>
    
    <!-- Video Logic & Filter Script -->
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
        
        // Video request form submit
        const requestForm = document.getElementById('videoRequestForm');
        if (requestForm) {
            requestForm.addEventListener('submit', (e) => {
                e.preventDefault();
                alert('Grazie per il tuo suggerimento! Lo prenderemo in considerazione per i prossimi video.');
                const modal = bootstrap.Modal.getInstance(document.getElementById('requestModal'));
                modal.hide();
                requestForm.reset();
            });
        }

        // Modal Video Player Handling
        const videoPlayerModalEl = document.getElementById('videoPlayerModal');
        const videoPlayerModal = new bootstrap.Modal(videoPlayerModalEl);
        const videoIframe = document.getElementById('videoPlayerIframe');
        const videoTitle = document.getElementById('videoPlayerTitle');
        const videoDialog = document.getElementById('videoPlayerDialog');
        const videoRatioContainer = document.getElementById('videoPlayerRatioContainer');
        
        document.addEventListener('click', function(e) {
            const trigger = e.target.closest('.play-trigger');
            if (trigger) {
                const fbUrl = trigger.getAttribute('data-fb-url') || '';
                let cardTitle = 'Riproduttore Video';
                
                // Find card title to display in modal header
                const featuredCard = trigger.closest('.featured-video-card');
                const regularCard = trigger.closest('.video-card');
                
                if (featuredCard) {
                    const titleEl = featuredCard.querySelector('.video-title');
                    if (titleEl) cardTitle = titleEl.textContent;
                } else if (regularCard) {
                    const titleEl = regularCard.querySelector('.video-title');
                    if (titleEl) cardTitle = titleEl.textContent;
                }
                
                videoTitle.textContent = cardTitle;
                
                // Parse if vertical (Reel)
                const isVertical = fbUrl.includes('/reel/') || fbUrl.includes('/reels/') || fbUrl.includes('/share/r/') || fbUrl.includes('reel=1');
                
                if (isVertical) {
                    videoDialog.classList.remove('modal-lg');
                    videoDialog.classList.add('modal-vertical');
                    videoRatioContainer.classList.remove('ratio-16x9');
                    videoRatioContainer.classList.add('ratio-9x16');
                } else {
                    videoDialog.classList.remove('modal-vertical');
                    videoDialog.classList.add('modal-lg');
                    videoRatioContainer.classList.remove('ratio-9x16');
                    videoRatioContainer.classList.add('ratio-16x9');
                }
                
                // Construct the Facebook embed URL
                const embedUrl = "https://www.facebook.com/plugins/video.php?href=" + encodeURIComponent(fbUrl) + "&show_text=0&autoplay=1" + (isVertical ? "" : "&width=560");
                
                videoIframe.src = embedUrl;
                videoPlayerModal.show();
            }
        });
        
        // Stop video playback when modal is closed
        videoPlayerModalEl.addEventListener('hidden.bs.modal', function () {
            videoIframe.src = '';
            videoTitle.textContent = 'Riproduttore Video';
        });
    });
    </script>
</body>
</html>