<?php
require_once '../config/config.php';
require_once '../assets/php/functions.php';

$page_title = "Servizi Telefonia Business - " . SITE_NAME;
$page_description = "Soluzioni di telefonia aziendale, centralini VoIP, linee business e assistenza operatori. Risparmia fino al 50% sui costi telefonici.";
$current_page = 'servizi';
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <?php include '../includes/head.php'; ?>
    <style>
        .service-hero {
            background: linear-gradient(135deg, #00c6ff 0%, #0072ff 100%);
            padding: 100px 0;
            color: white;
            position: relative;
            overflow: hidden;
        }
        
        .service-hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="%23ffffff10" d="M0,96L48,112C96,128,192,160,288,165.3C384,171,480,149,576,138.7C672,128,768,128,864,149.3C960,171,1056,213,1152,213.3C1248,213,1344,171,1392,149.3L1440,128L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>') no-repeat bottom;
            background-size: cover;
        }
        
        .plan-card {
            background: white;
            border-radius: 15px;
            padding: 40px 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            height: 100%;
            position: relative;
        }
        
        .plan-card.featured {
            transform: scale(1.05);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }
        
        .plan-card.featured::before {
            content: 'PIÙ VENDUTO';
            position: absolute;
            top: -15px;
            right: 20px;
            background: linear-gradient(135deg, #ff6b6b 0%, #ff8e8e 100%);
            color: white;
            padding: 5px 20px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        
        .plan-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }
        
        .plan-card.featured:hover {
            transform: scale(1.05) translateY(-10px);
        }
        
        .plan-price {
            font-size: 3rem;
            font-weight: bold;
            color: #0072ff;
            margin: 20px 0;
        }
        
        .plan-price small {
            font-size: 1rem;
            color: #666;
        }
        
        .plan-features {
            list-style: none;
            padding: 0;
            margin: 30px 0;
        }
        
        .plan-features li {
            padding: 10px 0;
            border-bottom: 1px solid #f0f0f0;
            display: flex;
            align-items: center;
        }
        
        .plan-features li i {
            color: #4caf50;
            margin-right: 10px;
        }
        
        .operator-card {
            background: white;
            border-radius: 10px;
            padding: 30px;
            text-align: center;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        
        .operator-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        }
        
        .operator-logo {
            height: 60px;
            margin-bottom: 20px;
        }
        
        .savings-calculator {
            background: linear-gradient(135deg, #f0f4ff 0%, #e8ecff 100%);
            padding: 40px;
            border-radius: 15px;
            margin: 40px 0;
        }
        
        .service-feature {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            height: 100%;
        }
        
        .service-feature i {
            font-size: 3rem;
            color: #0072ff;
            margin-bottom: 20px;
        }
        
        .cta-section {
            background: linear-gradient(135deg, #00c6ff 0%, #0072ff 100%);
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
                            <li class="breadcrumb-item active" aria-current="page" style="color: white;">Telefonia Business</li>
                        </ol>
                    </nav>
                    <h1 class="display-4 fw-bold mb-4">Telefonia Business</h1>
                    <p class="lead mb-4">Soluzioni complete di telefonia aziendale per ridurre i costi e migliorare la comunicazione</p>
                    <div class="d-flex gap-4 flex-wrap">
                        <div>
                            <i class="ri-percent-line"></i> Risparmio fino al 50%
                        </div>
                        <div>
                            <i class="ri-phone-line"></i> Centralini VoIP
                        </div>
                        <div>
                            <i class="ri-global-line"></i> Chiamate Illimitate
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Business Plans -->
    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-5">Piani Telefonia Business</h2>
            
            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="plan-card">
                        <h3 class="text-center">Basic Business</h3>
                        <div class="plan-price text-center">
                            €19<small>/mese</small>
                        </div>
                        <ul class="plan-features">
                            <li><i class="ri-check-line"></i> 1 Linea telefonica</li>
                            <li><i class="ri-check-line"></i> 500 minuti nazionali</li>
                            <li><i class="ri-check-line"></i> Numero fisso incluso</li>
                            <li><i class="ri-check-line"></i> Segreteria telefonica</li>
                            <li><i class="ri-check-line"></i> App mobile</li>
                            <li><i class="ri-close-line text-muted"></i> Centralino virtuale</li>
                        </ul>
                        <button class="btn btn-outline-primary w-100">Scopri di più</button>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <div class="plan-card featured">
                        <h3 class="text-center">Professional</h3>
                        <div class="plan-price text-center">
                            €39<small>/mese</small>
                        </div>
                        <ul class="plan-features">
                            <li><i class="ri-check-line"></i> 3 Linee telefoniche</li>
                            <li><i class="ri-check-line"></i> Minuti ILLIMITATI nazionali</li>
                            <li><i class="ri-check-line"></i> 500 minuti internazionali</li>
                            <li><i class="ri-check-line"></i> Centralino virtuale</li>
                            <li><i class="ri-check-line"></i> IVR personalizzato</li>
                            <li><i class="ri-check-line"></i> Registrazione chiamate</li>
                        </ul>
                        <button class="btn btn-primary w-100">Più venduto</button>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <div class="plan-card">
                        <h3 class="text-center">Enterprise</h3>
                        <div class="plan-price text-center">
                            €99<small>/mese</small>
                        </div>
                        <ul class="plan-features">
                            <li><i class="ri-check-line"></i> Linee ILLIMITATE</li>
                            <li><i class="ri-check-line"></i> Chiamate ILLIMITATE ovunque</li>
                            <li><i class="ri-check-line"></i> Centralino avanzato</li>
                            <li><i class="ri-check-line"></i> CRM integrato</li>
                            <li><i class="ri-check-line"></i> Videoconferenze HD</li>
                            <li><i class="ri-check-line"></i> Supporto dedicato 24/7</li>
                        </ul>
                        <button class="btn btn-outline-primary w-100">Contattaci</button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Operators -->
    <section class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-5">Partner e Operatori</h2>
            <p class="text-center lead mb-5">Collaboriamo con i principali operatori per offrirti le migliori tariffe</p>
            
            <div class="row g-4">
                <div class="col-md-3 col-6">
                    <div class="operator-card">
                        <div class="operator-logo">
                            <i class="ri-phone-fill" style="font-size: 3rem; color: #ff0000;"></i>
                        </div>
                        <h5>Vodafone Business</h5>
                        <p class="text-muted mb-0">Soluzioni enterprise</p>
                    </div>
                </div>
                
                <div class="col-md-3 col-6">
                    <div class="operator-card">
                        <div class="operator-logo">
                            <i class="ri-phone-fill" style="font-size: 3rem; color: #003996;"></i>
                        </div>
                        <h5>TIM Business</h5>
                        <p class="text-muted mb-0">Fibra e telefonia</p>
                    </div>
                </div>
                
                <div class="col-md-3 col-6">
                    <div class="operator-card">
                        <div class="operator-logo">
                            <i class="ri-phone-fill" style="font-size: 3rem; color: #ff7900;"></i>
                        </div>
                        <h5>WindTre Business</h5>
                        <p class="text-muted mb-0">Convergenza fisso-mobile</p>
                    </div>
                </div>
                
                <div class="col-md-3 col-6">
                    <div class="operator-card">
                        <div class="operator-logo">
                            <i class="ri-phone-fill" style="font-size: 3rem; color: #7c4dff;"></i>
                        </div>
                        <h5>Fastweb Business</h5>
                        <p class="text-muted mb-0">Ultra fibra e cloud</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Features -->
    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-5">Servizi Inclusi</h2>
            
            <div class="row g-4">
                <div class="col-md-6 col-lg-3">
                    <div class="service-feature text-center">
                        <i class="ri-customer-service-2-line"></i>
                        <h4>Centralino VoIP</h4>
                        <p>Centralino virtuale con IVR, code di attesa, musica personalizzata</p>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-3">
                    <div class="service-feature text-center">
                        <i class="ri-smartphone-line"></i>
                        <h4>App Mobile</h4>
                        <p>Gestisci le chiamate aziendali dal tuo smartphone ovunque tu sia</p>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-3">
                    <div class="service-feature text-center">
                        <i class="ri-video-chat-line"></i>
                        <h4>Videoconferenze</h4>
                        <p>Meeting online HD con condivisione schermo fino a 100 partecipanti</p>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-3">
                    <div class="service-feature text-center">
                        <i class="ri-bar-chart-box-line"></i>
                        <h4>Analytics</h4>
                        <p>Report dettagliati su chiamate, costi e performance del team</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Savings Calculator -->
    <section class="py-5">
        <div class="container">
            <div class="savings-calculator">
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <h3><i class="ri-calculator-line"></i> Calcola il Tuo Risparmio</h3>
                        <p class="lead">Scopri quanto puoi risparmiare passando alle nostre soluzioni business</p>
                        <form class="mt-4">
                            <div class="mb-3">
                                <label>Spesa telefonica mensile attuale</label>
                                <div class="input-group">
                                    <span class="input-group-text">€</span>
                                    <input type="number" class="form-control" id="currentSpend" placeholder="es. 200">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label>Numero di linee</label>
                                <input type="number" class="form-control" id="numLines" placeholder="es. 5">
                            </div>
                            <button type="button" class="btn btn-primary" onclick="calculateSavings()">
                                Calcola Risparmio
                            </button>
                        </form>
                    </div>
                    <div class="col-lg-6">
                        <div class="text-center" id="savingsResult" style="display: none;">
                            <h4>Risparmio Stimato</h4>
                            <div class="display-3 text-success">€<span id="savingsAmount">0</span></div>
                            <p>all'anno</p>
                            <small class="text-muted">*Stima basata sui nostri piani business</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Additional Services -->
    <section class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-5">Servizi Aggiuntivi</h2>
            
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="d-flex">
                        <div class="flex-shrink-0">
                            <i class="ri-wifi-line" style="font-size: 2rem; color: #0072ff;"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5>Connettività Internet</h5>
                            <p>Fibra ottica fino a 1 Gbps, ADSL, FWA per la tua azienda</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="d-flex">
                        <div class="flex-shrink-0">
                            <i class="ri-sim-card-line" style="font-size: 2rem; color: #0072ff;"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5>SIM Aziendali</h5>
                            <p>Piani mobile con minuti e giga illimitati per il tuo team</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="d-flex">
                        <div class="flex-shrink-0">
                            <i class="ri-phone-lock-line" style="font-size: 2rem; color: #0072ff;"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5>Numero Verde</h5>
                            <p>Attiva un numero verde 800 per i tuoi clienti</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="d-flex">
                        <div class="flex-shrink-0">
                            <i class="ri-mail-line" style="font-size: 2rem; color: #0072ff;"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5>Fax Virtuale</h5>
                            <p>Invia e ricevi fax via email senza apparecchi fisici</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <h2 class="mb-4">Passa al Business Phone System</h2>
            <p class="lead mb-5">Richiedi una consulenza gratuita e scopri la soluzione perfetta per la tua azienda</p>
            <div class="d-flex gap-3 justify-content-center flex-wrap">
                <a href="<?php echo url('preventivo.php'); ?>" class="btn btn-light btn-lg">
                    <i class="ri-file-list-3-line"></i> Richiedi Preventivo
                </a>
                <a href="tel:<?php echo PHONE_PRIMARY; ?>" class="btn btn-outline-light btn-lg">
                    <i class="ri-phone-line"></i> Chiama Ora
                </a>
                <a href="<?php echo whatsapp_link('Salve, vorrei informazioni sui piani telefonia business'); ?>" 
                   class="btn btn-success btn-lg" target="_blank">
                    <i class="ri-whatsapp-line"></i> WhatsApp
                </a>
            </div>
        </div>
    </section>

    <?php include '../includes/footer.php'; ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        function calculateSavings() {
            const currentSpend = parseFloat(document.getElementById('currentSpend').value) || 0;
            const numLines = parseInt(document.getElementById('numLines').value) || 1;
            
            // Calcolo risparmio stimato (40% di media)
            const estimatedMonthly = numLines * 39; // Piano Professional
            const currentYearly = currentSpend * 12;
            const newYearly = estimatedMonthly * 12;
            const savings = currentYearly - newYearly;
            
            if (savings > 0) {
                document.getElementById('savingsAmount').textContent = savings.toFixed(0);
                document.getElementById('savingsResult').style.display = 'block';
            } else {
                alert('Con i nostri piani avresti già un ottimo prezzo!');
            }
        }
    </script>
</body>
</html>