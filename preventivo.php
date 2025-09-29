<?php
/**
 * Key Soft Italia - Richiesta Preventivo
 * Form per richiedere un preventivo personalizzato
 */

// Define BASE_PATH if not defined
if (!defined('BASE_PATH')) {
    define('BASE_PATH', __DIR__ . '/');
}

require_once BASE_PATH . 'config/config.php';

// SEO Meta
$page_title = "Richiedi Preventivo - Key Soft Italia | Preventivi Gratuiti e Senza Impegno";
$page_description = "Richiedi un preventivo gratuito per riparazioni, vendita hardware, sviluppo software e assistenza informatica a Ginosa. Risposta entro 24 ore.";
$page_keywords = "preventivo riparazione computer, preventivo assistenza informatica, preventivo sviluppo software ginosa";

// Breadcrumbs
$breadcrumbs = [
    ['label' => 'Preventivo', 'url' => 'preventivo.php']
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
        'url' => url('preventivo.php')
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
    <link rel="stylesheet" href="<?php echo asset('css/pages/preventivo.css'); ?>">
    
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
                <h1 class="hero-title animate-fadeIn">Richiedi Preventivo</h1>
                <p class="hero-subtitle animate-fadeIn">
                    Preventivi gratuiti e senza impegno in 24 ore
                </p>
                <?php echo generate_breadcrumbs($breadcrumbs); ?>
            </div>
        </div>
    </section>
    
    <!-- Form Section -->
    <section class="section section-form">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="form-card">
                        <div class="form-header">
                            <h2 class="form-title">Compila il modulo per il tuo preventivo</h2>
                            <p class="form-subtitle">
                                Fornisci tutti i dettagli possibili per ricevere un preventivo accurato. 
                                Ti risponderemo entro 24 ore lavorative.
                            </p>
                        </div>
                        
                        <form id="quoteForm" method="POST" action="<?php echo url('process/preventivo.php'); ?>" class="needs-validation" novalidate>
                            <?php echo generate_csrf_field(); ?>
                            
                            <!-- Contact Information -->
                            <div class="form-section">
                                <h4 class="form-section-title">
                                    <i class="ri-user-line"></i> Informazioni di Contatto
                                </h4>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="firstName" class="form-label">Nome *</label>
                                        <input type="text" class="form-control" id="firstName" name="firstName" required>
                                        <div class="invalid-feedback">
                                            Inserisci il tuo nome
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="lastName" class="form-label">Cognome *</label>
                                        <input type="text" class="form-control" id="lastName" name="lastName" required>
                                        <div class="invalid-feedback">
                                            Inserisci il tuo cognome
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="email" class="form-label">Email *</label>
                                        <input type="email" class="form-control" id="email" name="email" required>
                                        <div class="invalid-feedback">
                                            Inserisci un'email valida
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="phone" class="form-label">Telefono *</label>
                                        <input type="tel" class="form-control" id="phone" name="phone" required>
                                        <div class="invalid-feedback">
                                            Inserisci il tuo numero di telefono
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="company" class="form-label">Azienda</label>
                                    <input type="text" class="form-control" id="company" name="company" 
                                           placeholder="Nome dell'azienda (opzionale)">
                                </div>
                            </div>
                            
                            <!-- Service Details -->
                            <div class="form-section">
                                <h4 class="form-section-title">
                                    <i class="ri-settings-3-line"></i> Dettagli del Servizio
                                </h4>
                                
                                <div class="mb-3">
                                    <label for="serviceType" class="form-label">Tipo di Servizio *</label>
                                    <select class="form-select" id="serviceType" name="serviceType" required>
                                        <option value="">Seleziona un servizio...</option>
                                        <option value="riparazione-smartphone">Riparazione Smartphone</option>
                                        <option value="riparazione-tablet">Riparazione Tablet</option>
                                        <option value="riparazione-computer">Riparazione Computer/Notebook</option>
                                        <option value="riparazione-console">Riparazione Console Gaming</option>
                                        <option value="vendita-ricondizionati">Vendita Prodotti Ricondizionati</option>
                                        <option value="vendita-accessori">Vendita Accessori</option>
                                        <option value="assistenza-software">Assistenza Software</option>
                                        <option value="recupero-dati">Recupero Dati</option>
                                        <option value="sviluppo-software">Sviluppo Software</option>
                                        <option value="sviluppo-web">Sviluppo Sito Web</option>
                                        <option value="sviluppo-app">Sviluppo App Mobile</option>
                                        <option value="consulenza-it">Consulenza IT</option>
                                        <option value="altro">Altro</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        Seleziona il tipo di servizio richiesto
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="urgency" class="form-label">Urgenza</label>
                                    <select class="form-select" id="urgency" name="urgency">
                                        <option value="normale">Normale (3-5 giorni)</option>
                                        <option value="urgente">Urgente (24-48 ore)</option>
                                        <option value="molto-urgente">Molto Urgente (entro 24 ore)</option>
                                    </select>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="budget" class="form-label">Budget Indicativo</label>
                                    <select class="form-select" id="budget" name="budget">
                                        <option value="">Non specificato</option>
                                        <option value="0-100">Fino a 100€</option>
                                        <option value="100-300">100€ - 300€</option>
                                        <option value="300-500">300€ - 500€</option>
                                        <option value="500-1000">500€ - 1000€</option>
                                        <option value="1000+">Oltre 1000€</option>
                                    </select>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="description" class="form-label">Descrizione Dettagliata *</label>
                                    <textarea class="form-control" id="description" name="description" rows="6" required
                                              placeholder="Descrivi nel dettaglio il servizio richiesto, il problema da risolvere o il progetto da realizzare..."></textarea>
                                    <div class="invalid-feedback">
                                        Fornisci una descrizione del servizio richiesto
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="deviceInfo" class="form-label">Informazioni Dispositivo</label>
                                    <input type="text" class="form-control" id="deviceInfo" name="deviceInfo" 
                                           placeholder="Marca, modello, anno (se applicabile)">
                                </div>
                            </div>
                            
                            <!-- Additional Options -->
                            <div class="form-section">
                                <h4 class="form-section-title">
                                    <i class="ri-add-circle-line"></i> Opzioni Aggiuntive
                                </h4>
                                
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" id="pickup" name="pickup" value="1">
                                    <label class="form-check-label" for="pickup">
                                        Richiedo ritiro a domicilio
                                    </label>
                                </div>
                                
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" id="warranty" name="warranty" value="1">
                                    <label class="form-check-label" for="warranty">
                                        Sono interessato all'estensione di garanzia
                                    </label>
                                </div>
                                
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="newsletter" name="newsletter" value="1">
                                    <label class="form-check-label" for="newsletter">
                                        Desidero ricevere offerte e novità via email
                                    </label>
                                </div>
                            </div>
                            
                            <!-- Privacy -->
                            <div class="form-section">
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="privacy" name="privacy" required>
                                    <label class="form-check-label" for="privacy">
                                        Ho letto e accetto la <a href="<?php echo url('privacy.php'); ?>" target="_blank">Privacy Policy</a> *
                                    </label>
                                    <div class="invalid-feedback">
                                        Devi accettare la Privacy Policy
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Submit -->
                            <div class="form-submit">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="ri-send-plane-line"></i> Richiedi Preventivo
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Sidebar -->
                <div class="col-lg-4">
                    <div class="sidebar-card">
                        <h4 class="sidebar-title">
                            <i class="ri-time-line"></i> Tempi di Risposta
                        </h4>
                        <p>Riceverai il tuo preventivo personalizzato entro:</p>
                        <ul class="list-unstyled">
                            <li><i class="ri-check-line text-success"></i> 24 ore per richieste standard</li>
                            <li><i class="ri-check-line text-success"></i> 12 ore per richieste urgenti</li>
                            <li><i class="ri-check-line text-success"></i> 4 ore per emergenze</li>
                        </ul>
                    </div>
                    
                    <div class="sidebar-card">
                        <h4 class="sidebar-title">
                            <i class="ri-shield-check-line"></i> I Nostri Vantaggi
                        </h4>
                        <ul class="list-unstyled">
                            <li><i class="ri-checkbox-circle-line text-primary"></i> Preventivi sempre gratuiti</li>
                            <li><i class="ri-checkbox-circle-line text-primary"></i> Nessun impegno di acquisto</li>
                            <li><i class="ri-checkbox-circle-line text-primary"></i> Prezzi trasparenti</li>
                            <li><i class="ri-checkbox-circle-line text-primary"></i> Garanzia su tutti i lavori</li>
                            <li><i class="ri-checkbox-circle-line text-primary"></i> Pagamenti flessibili</li>
                        </ul>
                    </div>
                    
                    <div class="sidebar-card bg-primary text-white">
                        <h4 class="sidebar-title">
                            <i class="ri-customer-service-2-line"></i> Hai bisogno di aiuto?
                        </h4>
                        <p>Il nostro team è disponibile per rispondere alle tue domande</p>
                        <div class="d-grid gap-2">
                            <a href="tel:<?php echo PHONE_PRIMARY; ?>" class="btn btn-light">
                                <i class="ri-phone-line"></i> <?php echo PHONE_PRIMARY; ?>
                            </a>
                            <a href="<?php echo whatsapp_link('Salve, ho bisogno di informazioni per un preventivo'); ?>" 
                               class="btn btn-success">
                                <i class="ri-whatsapp-line"></i> WhatsApp
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- FAQ Section -->
    <section class="section section-faq bg-light">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Domande Frequenti sui Preventivi</h2>
            </div>
            
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="accordion" id="faqAccordion">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" 
                                        data-bs-target="#faq1" aria-expanded="true">
                                    Il preventivo è davvero gratuito?
                                </button>
                            </h2>
                            <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Sì, tutti i nostri preventivi sono completamente gratuiti e senza alcun impegno di acquisto. 
                                    Puoi richiedere quanti preventivi desideri senza costi aggiuntivi.
                                </div>
                            </div>
                        </div>
                        
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                                        data-bs-target="#faq2">
                                    Quanto tempo ci vuole per ricevere un preventivo?
                                </button>
                            </h2>
                            <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Generalmente inviamo i preventivi entro 24 ore lavorative. 
                                    Per richieste urgenti, garantiamo una risposta entro 12 ore.
                                </div>
                            </div>
                        </div>
                        
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                                        data-bs-target="#faq3">
                                    Il preventivo include IVA e manodopera?
                                </button>
                            </h2>
                            <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Sì, tutti i nostri preventivi includono IVA e costo della manodopera. 
                                    Il prezzo che vedi è il prezzo finale, senza sorprese.
                                </div>
                            </div>
                        </div>
                        
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                                        data-bs-target="#faq4">
                                    Posso modificare il preventivo dopo averlo ricevuto?
                                </button>
                            </h2>
                            <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Certamente! I preventivi sono personalizzabili. 
                                    Puoi richiedere modifiche o aggiustamenti contattandoci direttamente.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo asset('js/main.js'); ?>"></script>
    
    <!-- Form Validation -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Form validation
        const forms = document.querySelectorAll('.needs-validation');
        
        Array.from(forms).forEach(form => {
            form.addEventListener('submit', event => {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                
                form.classList.add('was-validated');
            }, false);
        });
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