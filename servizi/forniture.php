<?php
/**
 * Key Soft Italia - Servizio Forniture Domestiche (Luce & Gas)
 * Pagina promozionale con offerte dinamiche, calcolo risparmio ed invio lead
 */

if (!defined('BASE_PATH')) {
    define('BASE_PATH', dirname(__DIR__) . '/');
}

require_once BASE_PATH . 'config/config.php';

// Inizializza sessione se non attiva (per CSRF)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Carichiamo le promozioni attive e i partner energetici
$promotions = [];
$partners = [];
try {
    $stmt = $pdo->query("
        SELECT up.id, up.plan_name, up.utility_type, up.price, up.price_detail, up.features, up.is_featured,
               COALESCE(p.name, up.operator_name) AS operator_name,
               COALESCE(p.logo_path, up.logo_path) AS logo_path,
               p.icon_class AS partner_icon_class,
               p.icon_color AS partner_icon_color
        FROM utility_promotions up
        LEFT JOIN utility_partners p ON up.partner_id = p.id
        WHERE up.status = 1
        ORDER BY up.is_featured DESC, operator_name ASC, up.price ASC
    ");
    $promotions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmtPartners = $pdo->query("SELECT * FROM utility_partners WHERE status = 1 ORDER BY sort_order ASC, name ASC");
    $partners = $stmtPartners->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $promotions = [];
    $partners = [];
}

// SEO Meta
$page_title = "Forniture Domestiche Luce e Gas - Key Soft Italia";
$page_description = "Risparmia sulla bolletta di luce e gas a Ginosa. Confronta le offerte energetiche, calcola il risparmio ed attiva contratti a prezzi vantaggiosi.";
$page_keywords = "luce e gas ginosa, taranti energia, offerte enel ginosa, plenitude tariffe, risparmio bolletta energetica, contratti luce gas";

// Breadcrumbs
$breadcrumbs = [
    ['label' => 'Servizi', 'url' => '../servizi.php'],
    ['label' => 'Forniture Luce & Gas', 'url' => 'forniture.php']
];
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <?php include '../includes/head.php'; ?>
    <!-- CSS di pagina -->
    <link rel="stylesheet" href="<?php echo asset_version('css/pages/forniture.css'); ?>">
</head>
<body>
    
    <!-- Header -->
    <?php include '../includes/header.php'; ?>
    
    <!-- Hero Section -->
    <section class="hero hero-secondary text-center">
        <div class="hero-pattern"></div>
        <div class="container position-relative z-2" data-aos="fade-up">
            <div class="hero-icon mb-3" data-aos="zoom-in">
                <i class="ri-flashlight-line text-warning"></i>
            </div>
            <h1 class="hero-title text-white" data-aos="fade-up" data-aos-delay="100">
                Forniture Domestiche <span class="text-gradient">Luce e Gas</span>
            </h1>
            <p class="hero-subtitle" data-aos="fade-up" data-aos-delay="200">
                Trova la tariffa energetica più conveniente per la tua casa. Analizziamo le tue bollette e ti aiutiamo ad abbattere i costi di elettricità e riscaldamento.
            </p>
            <div class="hero-cta" data-aos="fade-up" data-aos-delay="300">
                <a href="#offerte" class="btn btn-primary btn-lg smooth-scroll" aria-label="Scopri le tariffe attive">
                    <i class="ri-arrow-down-line me-1"></i> Scopri le Offerte
                </a>
            </div>
            <div class="hero-breadcrumb mt-4" data-aos="fade-up" data-aos-delay="400">
                <?php echo generate_breadcrumbs($breadcrumbs); ?>
            </div>
        </div>
    </section>
    
    <!-- Dynamic Promotions Section -->
    <section id="offerte" class="section-plans">
        <div class="container">
            <div class="section-header text-center mb-4" data-aos="fade-up">
                <h2 class="section-title">Le Nostre <span class="text-gradient">Offerte Attive</span></h2>
                <p class="section-subtitle">Piani trasparenti per la luce e il gas selezionati per garantirti il massimo risparmio</p>
            </div>

            <!-- Filtro Categorie -->
            <div class="text-center" data-aos="fade-up">
                <div class="filter-btn-group">
                    <button class="btn-filter active" data-filter="all">Tutte le offerte</button>
                    <button class="btn-filter" data-filter="luce">Solo Luce</button>
                    <button class="btn-filter" data-filter="gas">Solo Gas</button>
                    <button class="btn-filter" data-filter="dual">Luce + Gas</button>
                </div>
            </div>
            
            <div class="row g-4 justify-content-center">
                <?php if (empty($promotions)): ?>
                    <div class="col-12 text-center py-5" data-aos="fade-up">
                        <div class="alert alert-info border-0 shadow-sm d-inline-block p-4 rounded-4" style="max-width: 500px;">
                            <i class="ri-information-line text-primary display-6 mb-3 d-block"></i>
                            <h5 class="fw-bold">Nessuna offerta inserita online</h5>
                            <p class="text-muted mb-0">Contattaci direttamente o passa in negozio a Ginosa portando una tua bolletta recente: effettueremo un'analisi gratuita per farti risparmiare.</p>
                        </div>
                    </div>
                <?php else: ?>
                    <?php 
                    $delay = 0;
                    foreach ($promotions as $promo): 
                        $delay += 100;
                        $is_featured = (int)$promo['is_featured'] === 1;
                        $features_arr = !empty($promo['features']) ? explode("\n", $promo['features']) : [];
                        $utility_type = $promo['utility_type'];
                    ?>
                        <div class="col-lg-3 col-md-6 plan-item-col" data-type="<?= $utility_type; ?>" data-aos="fade-up" data-aos-delay="<?= $delay; ?>">
                            <div class="plan-card <?= $is_featured ? 'featured' : ''; ?>">
                                <!-- Badge Utenza -->
                                <span class="utility-badge <?= $utility_type; ?>">
                                    <?php if ($utility_type === 'luce'): ?>
                                        <i class="ri-lightbulb-line me-1"></i> LUCE
                                    <?php elseif ($utility_type === 'gas'): ?>
                                        <i class="ri-fire-line me-1"></i> GAS
                                    <?php else: ?>
                                        <i class="ri-bolt-line me-1"></i> DUAL
                                    <?php endif; ?>
                                </span>

                                <div class="plan-header mt-3">
                                    <div class="operator-logo-wrap">
                                        <?php if (!empty($promo['logo_path']) && file_exists('../' . $promo['logo_path'])): ?>
                                            <img src="<?= url($promo['logo_path']); ?>" alt="<?= htmlspecialchars($promo['operator_name']); ?> Logo">
                                        <?php else: ?>
                                            <div class="operator-logo-fallback text-center">
                                                <i class="<?= $promo['partner_icon_class'] ?: 'ri-flashlight-line'; ?>" style="font-size: 1.5rem; color: <?= $promo['partner_icon_color'] ?: 'var(--ks-orange)'; ?>;"></i>
                                                <span class="d-block small fw-bold text-uppercase mt-1"><?= htmlspecialchars($promo['operator_name']); ?></span>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <h3 class="plan-title"><?= htmlspecialchars($promo['plan_name']); ?></h3>
                                </div>
                                <div class="plan-price">
                                    €<?= number_format($promo['price'], 2, ',', '.'); ?><small><?= htmlspecialchars($promo['price_detail']); ?></small>
                                </div>
                                <ul class="plan-features">
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
                                <a href="#calcolatore" class="btn <?= $is_featured ? 'btn-primary' : 'btn-outline-primary'; ?> plan-btn w-100 select-promo-btn" data-id="<?= $promo['id']; ?>">
                                    Seleziona e Calcola
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>
    
    <!-- Partner & Operators -->
    <section class="section-operators">
        <div class="container position-relative z-1">
            <div class="section-header text-center mb-5" data-aos="fade-up">
                <h2 class="section-title">Fornitori <span class="text-gradient">Partner Ufficiali</span></h2>
                <p class="section-subtitle">Collaboriamo con le migliori aziende sul mercato libero dell'energia</p>
            </div>
            
            <div class="row g-4 justify-content-center">
                <?php if (!empty($partners)): ?>
                    <?php 
                    $delay = 100;
                    foreach ($partners as $partner): 
                    ?>
                        <div class="col-lg-3 col-md-4 col-6" data-aos="zoom-in" data-aos-delay="<?php echo $delay; ?>">
                            <div class="operator-card">
                                <div class="operator-logo">
                                    <?php if ($partner['logo_path'] && file_exists('../' . $partner['logo_path'])): ?>
                                        <img src="<?php echo url($partner['logo_path']); ?>" alt="<?php echo htmlspecialchars($partner['name']); ?> Logo">
                                    <?php else: ?>
                                        <i class="<?php echo htmlspecialchars($partner['icon_class']); ?>" style="font-size: 2.5rem; color: <?php echo htmlspecialchars($partner['icon_color']); ?>;"></i>
                                    <?php endif; ?>
                                </div>
                                <h5><?php echo htmlspecialchars($partner['name']); ?></h5>
                                <?php if (!empty($partner['description'])): ?>
                                    <p><?php echo htmlspecialchars($partner['description']); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php 
                    $delay += 100;
                    endforeach; 
                    ?>
                <?php else: ?>
                    <div class="col-lg-3 col-md-4 col-6" data-aos="zoom-in" data-aos-delay="100">
                        <div class="operator-card">
                            <div class="operator-logo">
                                <i class="ri-flashlight-line" style="font-size: 2.5rem; color: var(--ks-orange);"></i>
                            </div>
                            <h5>Enel Energia</h5>
                            <p>Leader nazionale</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Savings Calculator Section -->
    <section id="calcolatore" class="section-calculator">
        <div class="container">
            <div class="savings-calculator" data-aos="fade-up">
                <div class="row align-items-center g-4">
                    <div class="col-lg-6">
                        <h3><i class="ri-calculator-line text-orange me-2"></i> Calcola e Prenota il Risparmio</h3>
                        <p class="lead text-muted">Seleziona una promozione energetica e scopri la stima di risparmio annuale sulla tua bolletta</p>
                        
                        <form class="mt-4" id="calcForm">
                            <!-- CSRF Security Token -->
                            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                            <!-- Honeypot anti-spam -->
                            <input type="text" name="website" style="display:none !important" tabindex="-1" autocomplete="off">
                            
                            <div class="mb-3">
                                <label for="promoSelect" class="form-label fw-bold">1. Scegli la promozione di interesse *</label>
                                <select id="promoSelect" name="promotion_id" class="form-select" onchange="calculateSavings()" required>
                                    <option value="" disabled selected>Seleziona offerta...</option>
                                    <?php foreach ($promotions as $promo): ?>
                                        <option value="<?= $promo['id']; ?>" data-price="<?= $promo['price']; ?>" data-type="<?= $promo['utility_type']; ?>">
                                            <?= htmlspecialchars($promo['operator_name'] . ' - ' . $promo['plan_name'] . ' (€' . number_format($promo['price'], 2, ',', '.') . $promo['price_detail'] . ')') ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label for="currentSpend" class="form-label fw-bold">2. Bolletta media mensile attuale (€) *</label>
                                <div class="input-group">
                                    <span class="input-group-text">€</span>
                                    <input type="number" step="0.01" class="form-control form-control-has-group" id="currentSpend" name="current_spend" placeholder="es. 120" oninput="calculateSavings()" required>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="customerPhone" class="form-label fw-bold">3. Numero di cellulare per contatto *</label>
                                <input type="tel" class="form-control" id="customerPhone" name="phone" placeholder="es. 347 1234567" required>
                            </div>

                            <div class="form-check mb-4">
                                <input class="form-check-input" type="checkbox" id="privacyCheck" name="privacy" value="1" required>
                                <label class="form-check-label text-muted" for="privacyCheck" style="font-size: 0.85rem;">
                                    Accetto la <a href="../privacy.php" target="_blank">Privacy Policy</a> per essere ricontattato in negozio.
                                </label>
                            </div>
                            
                            <button type="submit" class="btn btn-primary w-100 py-3" id="btnSubmitCalc">
                                <i class="ri-send-plane-line me-1"></i> Calcola e Richiedi Consulenza
                            </button>
                        </form>
                    </div>
                    
                    <div class="col-lg-6">
                        <div class="calculator-result-box" id="savingsResult" style="display: none;">
                            <h4 id="resultTitle">Risparmio Annuo Stimato</h4>
                            <div class="display-amount">€<span id="savingsAmount">0</span></div>
                            <p id="resultText">Pronto per essere bloccato in negozio!</p>
                            <small id="resultDisclaimer">* Stima indicativa basata sull'offerta selezionata.</small>
                        </div>
                        
                        <div class="calculator-result-box" id="savingsFallback" style="padding: var(--ks-spacing-10);">
                            <i class="ri-pulse-line display-5 text-orange mb-3"></i>
                            <h4 class="text-white">Scegli una Promo</h4>
                            <p class="text-white-50 text-center" style="font-size: 0.95rem;">Per avviare il calcolo e richiedere il passaggio, seleziona una delle offerte disponibili a sinistra.</p>
                        </div>
                        
                        <div class="calculator-result-box bg-success d-none" id="successBox" style="padding: var(--ks-spacing-10);">
                            <i class="ri-checkbox-circle-line display-4 text-white mb-3"></i>
                            <h4 class="text-white">Richiesta Inviata!</h4>
                            <p class="text-white text-center mb-0" style="font-size: 0.95rem;">Abbiamo registrato la tua richiesta di consulenza. Ti contatteremo telefonicamente per preparare il passaggio in negozio!</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Additional Services / Features -->
    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-5" data-aos="fade-up">Perché affidarsi a <span class="text-gradient">Key Soft Italia</span>?</h2>
            
            <div class="row g-4">
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="service-feature">
                        <i class="ri-shield-user-line"></i>
                        <h4>Consulenza in Negozio</h4>
                        <p>Niente call center esteri o attese telefoniche. Gestisci il tuo contratto luce o gas parlando direttamente con noi in via Diaz, 46 a Ginosa.</p>
                    </div>
                </div>
                
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="service-feature">
                        <i class="ri-search-eye-line"></i>
                        <h4>Analisi Bolletta Gratuita</h4>
                        <p>Porta con te l'ultima bolletta di elettricità o gas: verifichiamo la presenza di costi nascosti o tariffe fuori mercato senza alcun impegno.</p>
                    </div>
                </div>
                
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="service-feature">
                        <i class="ri-shuffle-line"></i>
                        <h4>Zero Interruzioni</h4>
                        <p>Il passaggio al nuovo fornitore è puramente amministrativo. Non ci saranno tagli di corrente, chiusure di gas o sostituzioni tecniche dei contatori.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="section-cta-clean text-center">
        <div class="container" data-aos="fade-up">
            <h2 class="cta-title">Vuoi Tagliare i Costi delle Bollette di Luce e Gas?</h2>
            <p class="cta-subtitle">Passa in negozio a Ginosa in via Diaz portando una bolletta recente. Analizzeremo insieme la tariffa migliore per te!</p>
            <div class="cta-buttons">
                <a href="<?php echo url('contatti.php'); ?>" class="btn btn-primary btn-lg">
                    <i class="ri-map-pin-line me-1"></i> Vieni in Negozio
                </a>
                <a href="tel:<?php echo PHONE_PRIMARY; ?>" class="btn btn-outline-dark btn-lg">
                    <i class="ri-phone-line me-1"></i> Chiama Ora
                </a>
                <a href="<?php echo whatsapp_link('Salve, vorrei una consulenza gratuita sulle mie bollette di luce e gas'); ?>" 
                   class="btn btn-success btn-lg" target="_blank" rel="noopener">
                    <i class="ri-whatsapp-line me-1"></i> Chiedi su WhatsApp
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php include '../includes/footer.php'; ?>

    <script>
        // Gestione filtri dinamici delle offerte energetiche
        document.querySelectorAll('.filter-btn-group .btn-filter').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.filter-btn-group .btn-filter').forEach(b => b.classList.remove('active'));
                this.classList.add('active');

                const filterValue = this.dataset.filter;
                const cards = document.querySelectorAll('.plan-item-col');

                cards.forEach(card => {
                    if (filterValue === 'all') {
                        card.style.display = 'block';
                    } else {
                        if (card.dataset.type === filterValue) {
                            card.style.display = 'block';
                        } else {
                            card.style.display = 'none';
                        }
                    }
                });
            });
        });

        // Esegui lo scroll morbido e pre-popola il calcolatore quando si sceglie una promozione
        document.querySelectorAll('.select-promo-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const promoId = this.dataset.id;
                const selectEl = document.getElementById('promoSelect');
                selectEl.value = promoId;
                calculateSavings();
                
                // Scroll morbido al calcolatore
                document.getElementById('calcolatore').scrollIntoView({ behavior: 'smooth' });
            });
        });

        function calculateSavings() {
            const selectEl = document.getElementById('promoSelect');
            const selectedOption = selectEl.options[selectEl.selectedIndex];
            
            const currentSpend = parseFloat(document.getElementById('currentSpend').value) || 0;
            
            const resultBox = document.getElementById('savingsResult');
            const savingsFallback = document.getElementById('savingsFallback');
            const successBox = document.getElementById('successBox');
            const amountSpan = document.getElementById('savingsAmount');
            
            if (!successBox.classList.contains('d-none')) {
                return; // non sovrascrivere se la richiesta è già andata a buon fine
            }

            if (!selectEl.value || currentSpend <= 0) {
                resultBox.style.display = 'none';
                savingsFallback.style.display = 'flex';
                return;
            }

            const promoPrice = parseFloat(selectedOption.dataset.price) || 0;
            
            // Risparmio annuale = (Bolletta mensile attuale - Costo promo mensile stimato) * 12
            const savings = (currentSpend - promoPrice) * 12;
            
            if (savings > 0) {
                amountSpan.textContent = Math.round(savings);
                resultBox.style.display = 'flex';
                savingsFallback.style.display = 'none';
            } else {
                amountSpan.textContent = '0';
                resultBox.style.display = 'flex';
                savingsFallback.style.display = 'none';
            }
        }

        // Gestione invio modulo tramite AJAX
        document.getElementById('calcForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const btnSubmit = document.getElementById('btnSubmitCalc');
            const phoneVal = document.getElementById('customerPhone').value.trim();
            const privacyCheck = document.getElementById('privacyCheck').checked;
            
            if (phoneVal === '') {
                alert('Inserisci il tuo numero di cellulare.');
                return;
            }
            if (!privacyCheck) {
                alert('Devi accettare la privacy policy.');
                return;
            }

            btnSubmit.disabled = true;
            btnSubmit.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Invio in corso...';

            const formData = new FormData(this);

            fetch('../assets/process/process_utility_request.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                btnSubmit.disabled = false;
                btnSubmit.innerHTML = '<i class="ri-send-plane-line me-1"></i> Richiesta Inviata';

                if (data.ok) {
                    // Nascondi calcoli e fallbacks, mostra messaggio di successo
                    document.getElementById('savingsResult').style.display = 'none';
                    document.getElementById('savingsFallback').style.display = 'none';
                    
                    const successBox = document.getElementById('successBox');
                    successBox.classList.remove('d-none');
                    
                    // Resetta campi
                    document.getElementById('calcForm').reset();
                } else {
                    alert(data.message || 'Errore durante l\'invio della richiesta.');
                }
            })
            .catch(err => {
                btnSubmit.disabled = false;
                btnSubmit.innerHTML = '<i class="ri-send-plane-line me-1"></i> Calcola e Richiedi Consulenza';
                alert('Si è verificato un errore di rete.');
            });
        });
    </script>
</body>
</html>
