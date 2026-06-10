<?php
/**
 * Key Soft Italia - Servizio Telefonia Privati
 * Pagina dettaglio telefonia con offerte dinamiche caricate da DB ed invio lead
 */

if (!defined('BASE_PATH')) {
    define('BASE_PATH', dirname(__DIR__) . '/');
}

require_once BASE_PATH . 'config/config.php';

// Inizializza sessione se non attiva (per CSRF)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Carichiamo le promozioni attive
$promotions = [];
try {
    $stmt = $pdo->query("SELECT * FROM telephony_promotions WHERE status = 1 ORDER BY is_featured DESC, operator_name ASC, price ASC");
    $promotions = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $promotions = [];
}

// Trova il prezzo minimo delle offerte attive per il calcolo del risparmio per famiglie/privati
$min_price = 5.99; // default di sicurezza
if (!empty($promotions)) {
    $prices = array_column($promotions, 'price');
    $min_price = min($prices);
}

// SEO Meta
$page_title = "Telefonia Mobile e Fibra Casa - Key Soft Italia";
$page_description = "Scopri le migliori offerte telefoniche per privati a Ginosa. Attivazione SIM Kena, Lyca, Fastweb Mobile e Fibra Fastweb per la casa.";
$page_keywords = "telefonia privati ginosa, kena mobile, lycamobile, fastweb mobile, fibra fastweb casa, portabilità sim, offerte cellulari";

// Breadcrumbs
$breadcrumbs = [
    ['label' => 'Servizi', 'url' => '../servizi.php'],
    ['label' => 'Telefonia Privati', 'url' => 'telefonia.php']
];
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <?php include '../includes/head.php'; ?>
    <!-- CSS di pagina -->
    <link rel="stylesheet" href="<?php echo asset_version('css/pages/telefonia.css'); ?>">
</head>
<body>
    
    <!-- Header -->
    <?php include '../includes/header.php'; ?>
    
    <!-- Hero Section -->
    <section class="hero hero-secondary text-center">
        <div class="hero-pattern"></div>
        <div class="container position-relative z-2" data-aos="fade-up">
            <div class="hero-icon mb-3" data-aos="zoom-in">
                <i class="ri-phone-line"></i>
            </div>
            <h1 class="hero-title text-white" data-aos="fade-up" data-aos-delay="100">
                Telefonia <span class="text-gradient">Privati e Fibra Casa</span>
            </h1>
            <p class="hero-subtitle" data-aos="fade-up" data-aos-delay="200">
                Piani mobile e connessioni internet per la famiglia. Ti aiutiamo a scegliere la tariffa migliore e a risparmiare
            </p>
            <div class="hero-cta" data-aos="fade-up" data-aos-delay="300">
                <a href="#offerte" class="btn btn-primary btn-lg smooth-scroll" aria-label="Scopri le promozioni telefoniche attive">
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
            <div class="section-header text-center mb-5" data-aos="fade-up">
                <h2 class="section-title">Offerte e <span class="text-gradient">Promozioni Attive</span></h2>
                <p class="section-subtitle">Le migliori tariffe mobile e fibra selezionate per te questo mese</p>
            </div>
            
            <div class="row g-4 justify-content-center">
                <?php if (empty($promotions)): ?>
                    <div class="col-12 text-center py-5">
                        <div class="alert alert-info border-0 shadow-sm d-inline-block p-4 rounded-4" style="max-width: 500px;">
                            <i class="ri-information-line text-primary display-6 mb-3 d-block"></i>
                            <h5 class="fw-bold">Nessuna promozione attiva online</h5>
                            <p class="text-muted mb-0">Contattaci direttamente o passa in negozio per scoprire le offerte personalizzate del giorno di tutti i gestori.</p>
                        </div>
                    </div>
                <?php else: ?>
                    <?php 
                    $delay = 0;
                    foreach ($promotions as $promo): 
                        $delay += 100;
                        $is_featured = (int)$promo['is_featured'] === 1;
                        $features_arr = !empty($promo['features']) ? explode("\n", $promo['features']) : [];
                    ?>
                        <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="<?= $delay; ?>">
                            <div class="plan-card <?= $is_featured ? 'featured' : ''; ?>">
                                <div class="plan-header">
                                    <div class="operator-logo-wrap">
                                        <?php if (!empty($promo['logo_path']) && file_exists('../' . $promo['logo_path'])): ?>
                                            <img src="<?= url($promo['logo_path']); ?>" alt="<?= htmlspecialchars($promo['operator_name']); ?> Logo">
                                        <?php else: ?>
                                            <div class="operator-logo-fallback">
                                                <i class="ri-phone-line"></i>
                                                <span><?= htmlspecialchars($promo['operator_name']); ?></span>
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
                <h2 class="section-title">I Nostri <span class="text-gradient">Brand Partner</span></h2>
                <p class="section-subtitle">Siamo rivenditori autorizzati e partner ufficiali per la telefonia mobile e fissa</p>
            </div>
            
            <div class="row g-4 justify-content-center">
                <div class="col-lg-3 col-md-4 col-6" data-aos="zoom-in" data-aos-delay="100">
                    <div class="operator-card">
                        <div class="operator-logo">
                            <i class="ri-smartphone-line" style="font-size: 2.5rem; color: var(--ks-orange);"></i>
                        </div>
                        <h5>Kena Mobile</h5>
                        <p>Rete TIM 5G a tariffe imbattibili</p>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-4 col-6" data-aos="zoom-in" data-aos-delay="200">
                    <div class="operator-card">
                        <div class="operator-logo">
                            <i class="ri-global-line" style="font-size: 2.5rem; color: #7c4dff;"></i>
                        </div>
                        <h5>Lycamobile</h5>
                        <p>Rete Vodafone 5G e chiamate all'estero</p>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-4 col-6" data-aos="zoom-in" data-aos-delay="300">
                    <div class="operator-card">
                        <div class="operator-logo">
                            <i class="ri-phone-fill" style="font-size: 2.5rem; color: #003996;"></i>
                        </div>
                        <h5>Fastweb Mobile</h5>
                        <p>5G incluso e massima trasparenza</p>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-4 col-6" data-aos="zoom-in" data-aos-delay="400">
                    <div class="operator-card">
                        <div class="operator-logo">
                            <i class="ri-wifi-line" style="font-size: 2.5rem; color: var(--ks-green);"></i>
                        </div>
                        <h5>Fastweb Casa</h5>
                        <p>Fibra Ultra FTTH fino a 2.5 Gbps</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Features -->
    <section class="section-features">
        <div class="container">
            <div class="section-header text-center mb-5" data-aos="fade-up">
                <h2 class="section-title">Servizi <span class="text-gradient">Inclusi in Negozio</span></h2>
                <p class="section-subtitle">Gestiamo ogni aspetto per evitarti code, attese e problemi burocratici</p>
            </div>
            
            <div class="row g-4">
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="100">
                    <div class="service-feature">
                        <i class="ri-checkbox-multiple-line"></i>
                        <h4>Portabilità Facile (MNP)</h4>
                        <p>Passa a un nuovo operatore mantenendo il tuo numero. Gestiamo noi la pratica in pochi minuti.</p>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="200">
                    <div class="service-feature">
                        <i class="ri-smartphone-line"></i>
                        <h4>Configurazione SIM ed eSIM</h4>
                        <p>Inseriamo la SIM nel tuo cellulare, configuriamo internet (APN) e verifichiamo il funzionamento.</p>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="300">
                    <div class="service-feature">
                        <i class="ri-home-wifi-line"></i>
                        <h4>Fibra per la Famiglia</h4>
                        <p>Controlliamo gratis la copertura a casa tua e attiviamo la fibra più veloce al miglior prezzo.</p>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                    <div class="service-feature">
                        <i class="ri-customer-service-line"></i>
                        <h4>Assistenza Post-Vendita</h4>
                        <p>Ricariche telefoniche, sostituzione SIM smarrite, cambio piano o risoluzione di problemi di linea.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Savings Calculator -->
    <section id="calcolatore" class="section-calculator">
        <div class="container">
            <div class="savings-calculator" data-aos="fade-up">
                <div class="row align-items-center g-4">
                    <div class="col-lg-6">
                        <h3><i class="ri-calculator-line text-orange me-2"></i> Calcola e Prenota il Risparmio</h3>
                        <p class="lead text-muted">Seleziona una promozione e scopri quanto risparmi all'anno sulle tue SIM</p>
                        
                        <form class="mt-4" id="calcForm">
                            <!-- CSRF Security Token -->
                            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
                            <!-- Honeypot anti-spam -->
                            <input type="text" name="website" style="display:none !important" tabindex="-1" autocomplete="off">
                            
                            <div class="mb-3">
                                <label for="promoSelect" class="form-label fw-bold">1. Scegli la promozione di interesse *</label>
                                <select id="promoSelect" name="promotion_id" class="form-select" onchange="calculateSavings()" required>
                                    <option value="" disabled selected>Seleziona offerta...</option>
                                    <?php foreach ($promotions as $promo): ?>
                                        <option value="<?= $promo['id'] ?>" data-price="<?= $promo['price'] ?>">
                                            <?= htmlspecialchars($promo['operator_name'] . ' - ' . $promo['plan_name'] . ' (€' . number_format($promo['price'], 2, ',', '.') . $promo['price_detail'] . ')') ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label for="currentSpend" class="form-label fw-bold">2. Spesa mensile attuale per SIM (€) *</label>
                                <div class="input-group">
                                    <span class="input-group-text">€</span>
                                    <input type="number" step="0.01" class="form-control form-control-has-group" id="currentSpend" name="current_spend" placeholder="es. 15" oninput="calculateSavings()" required>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="numLines" class="form-label fw-bold">3. Numero di SIM in famiglia *</label>
                                <input type="number" class="form-control" id="numLines" name="num_lines" placeholder="es. 1" min="1" value="1" oninput="calculateSavings()" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="customerPhone" class="form-label fw-bold">4. Numero di cellulare per contatto *</label>
                                <input type="tel" class="form-control" id="customerPhone" name="phone" placeholder="es. 347 1234567" required>
                            </div>

                            <div class="form-check mb-4">
                                <input class="form-check-input" type="checkbox" id="privacyCheck" name="privacy" value="1" required>
                                <label class="form-check-label text-muted" for="privacyCheck" style="font-size: 0.85rem;">
                                    Accetto la <a href="../privacy.php" target="_blank">Privacy Policy</a> per essere ricontattato in negozio.
                                </label>
                            </div>
                            
                            <button type="submit" class="btn btn-primary w-100 py-3" id="btnSubmitCalc">
                                <i class="ri-send-plane-line me-1"></i> Calcola e Prenota Passaggio
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
                            <p class="text-white text-center mb-0" style="font-size: 0.95rem;">Abbiamo registrato la tua richiesta di passaggio. Ti contatteremo telefonicamente per preparare la SIM in negozio!</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Additional Services -->
    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-5" data-aos="fade-up">Servizi <span class="text-gradient">Aggiuntivi Telefonia</span></h2>
            
            <div class="row g-4">
                <div class="col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="d-flex">
                        <div class="flex-shrink-0">
                            <i class="ri-sim-card-line" style="font-size: 2.5rem; color: var(--ks-orange);"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="fw-bold">Servizio eSIM Immediato</h5>
                            <p class="text-muted">Attiva le offerte direttamente in formato eSIM per i dispositivi compatibili. Niente plastica e attivazione istantanea in negozio.</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="d-flex">
                        <div class="flex-shrink-0">
                            <i class="ri-global-line" style="font-size: 2.5rem; color: var(--ks-orange);"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="fw-bold">Tariffe e Giga per l'Estero</h5>
                            <p class="text-muted">Piani tariffari specifici Lycamobile con minuti internazionali verso il tuo paese d'origine e roaming UE compreso.</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6" data-aos="fade-up" data-aos-delay="300">
                    <div class="d-flex">
                        <div class="flex-shrink-0">
                            <i class="ri-route-line" style="font-size: 2.5rem; color: var(--ks-orange);"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="fw-bold">Ricariche Tutti gli Operatori</h5>
                            <p class="text-muted">Ricarica in tempo reale il tuo numero o quello di familiari per qualsiasi gestore nazionale ed estero principale.</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6" data-aos="fade-up" data-aos-delay="400">
                    <div class="d-flex">
                        <div class="flex-shrink-0">
                            <i class="ri-shield-check-line" style="font-size: 2.5rem; color: var(--ks-orange);"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="fw-bold">Verifica Copertura Fibra Casa</h5>
                            <p class="text-muted">Vieni a trovarci per una verifica copertura stradale esatta. Ti mostriamo la tecnologia disponibile (FTTH, FTTC, FWA) prima di attivare.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="section-cta-clean text-center">
        <div class="container" data-aos="fade-up">
            <h2 class="cta-title">Vuoi Cambiare Operatore o Attivare la Fibra?</h2>
            <p class="cta-subtitle">Vieni in negozio a Ginosa in via Diaz. Pensiamo noi a SIM, configurazione e verifica copertura!</p>
            <div class="cta-buttons">
                <a href="<?php echo url('contatti.php'); ?>" class="btn btn-primary btn-lg">
                    <i class="ri-map-pin-line me-1"></i> Vieni in Negozio
                </a>
                <a href="tel:<?php echo PHONE_PRIMARY; ?>" class="btn btn-outline-dark btn-lg">
                    <i class="ri-phone-line me-1"></i> Chiama Ora
                </a>
                <a href="<?php echo whatsapp_link('Salve, vorrei informazioni sulle offerte Kena, Lyca o Fastweb'); ?>" 
                   class="btn btn-success btn-lg" target="_blank" rel="noopener">
                    <i class="ri-whatsapp-line me-1"></i> Chiedi su WhatsApp
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php include '../includes/footer.php'; ?>

    <script>
        // Smooth scroll trigger when clicking "Seleziona e Calcola" from the promotion list
        document.querySelectorAll('.select-promo-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const promoId = this.dataset.id;
                const selectEl = document.getElementById('promoSelect');
                selectEl.value = promoId;
                calculateSavings();
                
                // Smooth scroll to calculator
                document.getElementById('calcolatore').scrollIntoView({ behavior: 'smooth' });
            });
        });

        function calculateSavings() {
            const selectEl = document.getElementById('promoSelect');
            const selectedOption = selectEl.options[selectEl.selectedIndex];
            
            const currentSpend = parseFloat(document.getElementById('currentSpend').value) || 0;
            const numLines = parseInt(document.getElementById('numLines').value) || 1;
            
            const resultBox = document.getElementById('savingsResult');
            const fallbackBox = document.getElementById('savingsFallback');
            const successBox = document.getElementById('successBox');
            const amountSpan = document.getElementById('savingsAmount');
            
            // If success box is currently visible, do not overwrite until recalculation
            if (!successBox.classList.contains('d-none')) {
                return;
            }

            if (!selectEl.value) {
                resultBox.style.display = 'none';
                fallbackBox.style.display = 'flex';
                return;
            }

            const promoPrice = parseFloat(selectedOption.dataset.price) || 0;
            
            // If spend is not set, just show 0 or keep fallback
            if (currentSpend <= 0) {
                resultBox.style.display = 'none';
                fallbackBox.style.display = 'flex';
                return;
            }

            const estimatedMonthly = numLines * promoPrice;
            const currentYearly = currentSpend * 12 * numLines;
            const newYearly = estimatedMonthly * 12;
            const savings = currentYearly - newYearly;
            
            if (savings > 0) {
                amountSpan.textContent = Math.round(savings);
                resultBox.style.display = 'flex';
                fallbackBox.style.display = 'none';
            } else {
                amountSpan.textContent = '0';
                resultBox.style.display = 'flex';
                fallbackBox.style.display = 'none';
            }
        }

        // Handle AJAX form submission
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

            fetch('../assets/process/process_telephony_request.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                btnSubmit.disabled = false;
                btnSubmit.innerHTML = '<i class="ri-send-plane-line me-1"></i> Calcola e Prenota Passaggio';

                if (data.ok) {
                    // Hide other boxes and show Success Box
                    document.getElementById('savingsResult').style.display = 'none';
                    document.getElementById('savingsFallback').style.display = 'none';
                    
                    const successBox = document.getElementById('successBox');
                    successBox.classList.remove('d-none');
                    
                    // Reset form fields
                    document.getElementById('calcForm').reset();
                } else {
                    alert(data.message || 'Errore durante l\'invio della richiesta.');
                }
            })
            .catch(err => {
                btnSubmit.disabled = false;
                btnSubmit.innerHTML = '<i class="ri-send-plane-line me-1"></i> Calcola e Prenota Passaggio';
                alert('Si è verificato un errore di rete.');
            });
        });
    </script>
</body>
</html>