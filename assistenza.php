<?php
/**
 * Key Soft Italia - Assistenza Tecnica
 * Pagina richiesta assistenza tecnica
 */

// Define BASE_PATH if not defined
if (!defined('BASE_PATH')) {
    define('BASE_PATH', __DIR__ . '/');
}

require_once BASE_PATH . 'config/config.php';

// SEO Meta
$page_title = "Assistenza Tecnica - Key Soft Italia | Supporto a Domicilio e Remoto";
$page_description = "Richiedi assistenza tecnica professionale. Supporto a domicilio e remoto per privati e aziende. Intervento rapido garantito.";
$page_keywords = "assistenza tecnica, supporto informatico, assistenza remota, riparazione computer a domicilio";

// Breadcrumbs
$breadcrumbs = [
    ['label' => 'Assistenza', 'url' => 'assistenza.php']
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
        'url' => url('assistenza.php')
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
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?php echo asset('images/favicon.ico'); ?>">
    
    <style>
        .assistance-hero {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 80px 0;
            color: white;
            position: relative;
            overflow: hidden;
        }
        
        .assistance-hero::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: pulse 15s ease-in-out infinite;
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }
        
        .assistance-type-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            cursor: pointer;
            transition: all 0.3s ease;
            border: 3px solid transparent;
        }
        
        .assistance-type-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }
        
        .assistance-type-card.selected {
            border-color: #4a00e0;
            background: linear-gradient(135deg, #f0f4ff 0%, #e8ecff 100%);
        }
        
        .assistance-type-card i {
            font-size: 3.5rem;
            color: #4a00e0;
            margin-bottom: 20px;
        }
        
        .form-section {
            background: #f8f9fa;
            padding: 60px 0;
        }
        
        .form-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            padding: 40px;
        }
        
        .emergency-box {
            background: linear-gradient(135deg, #ff6b6b 0%, #ff8e8e 100%);
            color: white;
            padding: 30px;
            border-radius: 15px;
            margin-bottom: 30px;
        }
        
        .emergency-box h3 {
            margin-bottom: 15px;
            font-weight: 700;
        }
        
        .emergency-box .btn {
            background: white;
            color: #ff6b6b;
            font-weight: 600;
            padding: 12px 30px;
        }
        
        .emergency-box .btn:hover {
            transform: scale(1.05);
        }
        
        .faq-section {
            padding: 60px 0;
            background: white;
        }
        
        .accordion-button:not(.collapsed) {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .accordion-button:focus {
            box-shadow: none;
            border-color: rgba(102, 126, 234, 0.25);
        }
        
        .stats-box {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px;
            border-radius: 15px;
            margin-top: 30px;
        }
        
        .stat-item {
            text-align: center;
            padding: 20px;
        }
        
        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 10px;
        }
        
        .stat-label {
            font-size: 1rem;
            opacity: 0.9;
        }
        
        @media (max-width: 768px) {
            .assistance-type-card {
                margin-bottom: 20px;
            }
            
            .stat-number {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    
    <!-- Header -->
    <?php include 'includes/header.php'; ?>

    <!-- Hero Section -->
    <section class="assistance-hero">
        <div class="container position-relative">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h1 class="display-4 fw-bold mb-4">Assistenza Tecnica Professionale</h1>
                    <p class="lead mb-4">Supporto tecnico qualificato a domicilio o da remoto. Risolviamo ogni problema informatico con rapidità e professionalità.</p>
                    <div class="d-flex gap-4 flex-wrap">
                        <div>
                            <i class="ri-time-line"></i> Intervento Rapido
                        </div>
                        <div>
                            <i class="ri-shield-check-line"></i> Tecnici Certificati
                        </div>
                        <div>
                            <i class="ri-money-euro-circle-line"></i> Prezzi Trasparenti
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Assistance Type Selection -->
    <div class="container" style="margin-top: -50px; margin-bottom: 50px;">
        <div class="row g-4">
            <div class="col-md-6">
                <div class="assistance-type-card" id="domicilio-card" onclick="selectAssistanceType('domicilio')">
                    <i class="ri-home-smile-line"></i>
                    <h3>Assistenza a Domicilio</h3>
                    <p class="mb-0">Un tecnico verrà direttamente a casa o in ufficio per risolvere il problema</p>
                    <div class="mt-3">
                        <span class="badge bg-success">Più Richiesto</span>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="assistance-type-card" id="remota-card" onclick="selectAssistanceType('remota')">
                    <i class="ri-global-line"></i>
                    <h3>Assistenza Remota</h3>
                    <p class="mb-0">Ci colleghiamo al tuo computer da remoto per risolvere il problema</p>
                    <div class="mt-3">
                        <span class="badge bg-primary">Soluzione Veloce</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Section -->
    <section class="form-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="form-container">
                        <h2 class="mb-4">Richiedi Assistenza</h2>
                        <form id="assistanceForm" method="POST" action="<?php echo url('assets/php/process_assistance.php'); ?>">
                            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
                            <input type="hidden" name="assistance_type" id="assistance_type" value="domicilio">
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Nome e Cognome *</label>
                                    <input type="text" class="form-control" name="name" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Email *</label>
                                    <input type="email" class="form-control" name="email" required>
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Telefono *</label>
                                    <input type="tel" class="form-control" name="phone" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Tipo Dispositivo *</label>
                                    <select class="form-select" name="device_type" required>
                                        <option value="">Seleziona...</option>
                                        <option value="computer">Computer Desktop</option>
                                        <option value="notebook">Notebook/Laptop</option>
                                        <option value="smartphone">Smartphone</option>
                                        <option value="tablet">Tablet</option>
                                        <option value="stampante">Stampante</option>
                                        <option value="rete">Rete/Router</option>
                                        <option value="altro">Altro</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="mb-3" id="address-field">
                                <label class="form-label">Indirizzo (per assistenza a domicilio) *</label>
                                <input type="text" class="form-control" name="address" id="address" placeholder="Via, numero civico, città">
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Descrizione del Problema *</label>
                                <textarea class="form-control" name="problem_description" rows="5" required 
                                    placeholder="Descrivi dettagliatamente il problema che stai riscontrando..."></textarea>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Urgenza</label>
                                    <select class="form-select" name="urgency">
                                        <option value="normale">Normale (2-3 giorni)</option>
                                        <option value="urgente">Urgente (24 ore)</option>
                                        <option value="immediata">Immediata (stesso giorno)</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Fascia Oraria Preferita</label>
                                    <select class="form-select" name="time_preference">
                                        <option value="mattina">Mattina (9:00-13:00)</option>
                                        <option value="pomeriggio">Pomeriggio (14:00-18:00)</option>
                                        <option value="sera">Sera (18:00-20:00)</option>
                                        <option value="qualsiasi">Qualsiasi orario</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="privacy" name="privacy" required>
                                <label class="form-check-label" for="privacy">
                                    Acconsento al trattamento dei dati personali secondo la <a href="<?php echo url('privacy.php'); ?>">Privacy Policy</a> *
                                </label>
                            </div>
                            
                            <button type="submit" class="btn btn-primary btn-lg w-100">
                                <i class="ri-send-plane-line"></i> Invia Richiesta Assistenza
                            </button>
                        </form>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <!-- Emergency Box -->
                    <div class="emergency-box">
                        <h3><i class="ri-alarm-warning-line"></i> Emergenza?</h3>
                        <p>Per interventi urgenti chiama subito!</p>
                        <h2 class="mb-3"><?php echo PHONE_PRIMARY; ?></h2>
                        <a href="tel:<?php echo PHONE_PRIMARY; ?>" class="btn btn-white">
                            <i class="ri-phone-line"></i> Chiama Ora
                        </a>
                    </div>
                    
                    <!-- Stats Box -->
                    <div class="stats-box">
                        <div class="row">
                            <div class="col-6">
                                <div class="stat-item">
                                    <div class="stat-number">2h</div>
                                    <div class="stat-label">Tempo Medio Intervento</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="stat-item">
                                    <div class="stat-number">98%</div>
                                    <div class="stat-label">Problemi Risolti</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="stat-item">
                                    <div class="stat-number">500+</div>
                                    <div class="stat-label">Clienti Assistiti</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="stat-item">
                                    <div class="stat-number">4.9</div>
                                    <div class="stat-label">Valutazione Media</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- WhatsApp Contact -->
                    <div class="text-center mt-4">
                        <a href="<?php echo whatsapp_link('Salve, avrei bisogno di assistenza tecnica'); ?>" 
                           class="btn btn-success btn-lg w-100" target="_blank">
                            <i class="ri-whatsapp-line"></i> Contattaci su WhatsApp
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="faq-section">
        <div class="container">
            <h2 class="text-center mb-5">Domande Frequenti sull'Assistenza</h2>
            
            <div class="accordion" id="faqAccordion">
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                            Quanto costa l'assistenza a domicilio?
                        </button>
                    </h2>
                    <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Il costo dell'assistenza a domicilio parte da €30 per la chiamata più €20/ora di lavoro. 
                            Il preventivo viene sempre comunicato prima dell'intervento e non ci sono costi nascosti.
                        </div>
                    </div>
                </div>
                
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                            Come funziona l'assistenza remota?
                        </button>
                    </h2>
                    <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Utilizziamo software sicuri come TeamViewer o AnyDesk. Ti forniremo un codice da inserire 
                            che ci permetterà di collegarci al tuo computer. Potrai vedere tutto quello che facciamo 
                            e interrompere la connessione in qualsiasi momento.
                        </div>
                    </div>
                </div>
                
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                            In quanto tempo intervenite?
                        </button>
                    </h2>
                    <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Per interventi urgenti garantiamo l'assistenza entro 24 ore. Per richieste normali 
                            interveniamo entro 2-3 giorni lavorativi. L'assistenza remota può essere fornita anche 
                            immediatamente se c'è disponibilità.
                        </div>
                    </div>
                </div>
                
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                            Che tipo di problemi potete risolvere?
                        </button>
                    </h2>
                    <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Risolviamo qualsiasi problema informatico: virus, lentezza del sistema, problemi software, 
                            errori di sistema, configurazione periferiche, recupero dati, installazione programmi, 
                            problemi di rete e molto altro.
                        </div>
                    </div>
                </div>
                
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq5">
                            Offrite garanzia sul lavoro svolto?
                        </button>
                    </h2>
                    <div id="faq5" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Sì, tutti i nostri interventi sono garantiti per 30 giorni. Se il problema si ripresenta 
                            entro questo periodo, interveniamo gratuitamente per risolverlo definitivamente.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Main JS -->
    <script src="<?php echo asset('js/main.js'); ?>"></script>
    
    <script>
        let selectedType = 'domicilio';
        
        function selectAssistanceType(type) {
            selectedType = type;
            
            // Update cards visual state
            document.querySelectorAll('.assistance-type-card').forEach(card => {
                card.classList.remove('selected');
            });
            document.getElementById(type + '-card').classList.add('selected');
            
            // Update hidden input
            document.getElementById('assistance_type').value = type;
            
            // Show/hide address field
            const addressField = document.getElementById('address-field');
            const addressInput = document.getElementById('address');
            
            if (type === 'domicilio') {
                addressField.style.display = 'block';
                addressInput.setAttribute('required', '');
            } else {
                addressField.style.display = 'none';
                addressInput.removeAttribute('required');
            }
        }
        
        // Initialize with domicilio selected
        selectAssistanceType('domicilio');
        
        // Form submission
        document.getElementById('assistanceForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Show loading state
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="ri-loader-4-line ri-spin"></i> Invio in corso...';
            
            // Simulate form submission
            setTimeout(() => {
                alert('Richiesta di assistenza inviata con successo! Ti contatteremo al più presto.');
                this.reset();
                selectAssistanceType('domicilio');
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }, 2000);
        });
    </script>
</body>
</html>