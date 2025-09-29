<?php
/**
 * Key Soft Italia - Privacy Policy
 * Informativa sulla privacy e trattamento dei dati personali
 */

// Define BASE_PATH if not defined
if (!defined('BASE_PATH')) {
    define('BASE_PATH', __DIR__ . '/');
}

require_once BASE_PATH . 'config/config.php';

// SEO Meta
$page_title = "Privacy Policy - Key Soft Italia | Informativa Privacy GDPR";
$page_description = "Informativa sulla privacy di Key Soft Italia. Scopri come trattiamo e proteggiamo i tuoi dati personali in conformità al GDPR.";
$page_keywords = "privacy policy, informativa privacy, gdpr, protezione dati, key soft italia";

// Breadcrumbs
$breadcrumbs = [
    ['label' => 'Privacy Policy', 'url' => 'privacy.php']
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
        'url' => url('privacy.php')
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
    <link rel="stylesheet" href="<?php echo asset('css/pages/privacy.css'); ?>">
    
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
                <h1 class="hero-title animate-fadeIn">Privacy Policy</h1>
                <p class="hero-subtitle animate-fadeIn">
                    Informativa sulla Privacy e Protezione dei Dati
                </p>
                <?php echo generate_breadcrumbs($breadcrumbs); ?>
            </div>
        </div>
    </section>
    
    <!-- Privacy Content -->
    <section class="section section-privacy">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="privacy-card">
                        <div class="privacy-header">
                            <p class="text-muted">Ultimo aggiornamento: <?php echo date('d/m/Y'); ?></p>
                        </div>
                        
                        <div class="privacy-content">
                            <!-- Introduction -->
                            <div class="privacy-section">
                                <h2>1. Introduzione</h2>
                                <p>
                                    La presente Privacy Policy descrive le modalità di gestione di questo sito web 
                                    in riferimento al trattamento dei dati personali degli utenti che lo consultano. 
                                    Si tratta di un'informativa resa ai sensi dell'art. 13 del Regolamento UE 2016/679 
                                    (General Data Protection Regulation - GDPR) a coloro che si collegano al sito web 
                                    di Key Soft Italia e usufruiscono dei relativi servizi.
                                </p>
                                <p>
                                    La protezione dei tuoi dati personali è molto importante per noi. 
                                    Ci impegniamo a proteggere la tua privacy e a gestire i tuoi dati in modo 
                                    aperto, trasparente e sicuro.
                                </p>
                            </div>
                            
                            <!-- Data Controller -->
                            <div class="privacy-section">
                                <h2>2. Titolare del Trattamento</h2>
                                <p>
                                    Il Titolare del trattamento dei dati personali è:
                                </p>
                                <div class="info-box">
                                    <p>
                                        <strong><?php echo COMPANY_NAME; ?></strong><br>
                                        <?php echo ADDRESS; ?><br>
                                        P.IVA: <?php echo VAT_NUMBER; ?><br>
                                        Email: <a href="mailto:<?php echo EMAIL_INFO; ?>"><?php echo EMAIL_INFO; ?></a><br>
                                        Telefono: <a href="tel:<?php echo PHONE_PRIMARY; ?>"><?php echo PHONE_PRIMARY; ?></a>
                                    </p>
                                </div>
                            </div>
                            
                            <!-- Types of Data Collected -->
                            <div class="privacy-section">
                                <h2>3. Tipologie di Dati Raccolti</h2>
                                <p>
                                    Fra i dati personali raccolti da questo sito web, in modo autonomo o tramite terze parti, 
                                    ci sono:
                                </p>
                                
                                <h3>3.1 Dati di Navigazione</h3>
                                <p>
                                    I sistemi informatici e le procedure software preposte al funzionamento di questo 
                                    sito web acquisiscono, nel corso del loro normale esercizio, alcuni dati personali 
                                    la cui trasmissione è implicita nell'uso dei protocolli di comunicazione di Internet.
                                </p>
                                <ul>
                                    <li>Indirizzo IP</li>
                                    <li>Tipo di browser utilizzato</li>
                                    <li>Sistema operativo</li>
                                    <li>Data e orario di visita</li>
                                    <li>Pagine visitate</li>
                                    <li>Tempo di permanenza sul sito</li>
                                </ul>
                                
                                <h3>3.2 Dati Forniti Volontariamente</h3>
                                <p>
                                    L'invio facoltativo, esplicito e volontario di dati attraverso i moduli di contatto 
                                    o di richiesta preventivo presenti su questo sito comporta la successiva acquisizione 
                                    dei dati forniti dal mittente, necessari per rispondere alle richieste.
                                </p>
                                <ul>
                                    <li>Nome e Cognome</li>
                                    <li>Indirizzo email</li>
                                    <li>Numero di telefono</li>
                                    <li>Azienda (se applicabile)</li>
                                    <li>Messaggio/Richiesta</li>
                                    <li>Informazioni sul dispositivo da riparare</li>
                                </ul>
                                
                                <h3>3.3 Cookie e Tecnologie Simili</h3>
                                <p>
                                    Il sito utilizza cookie tecnici e, previo consenso dell'utente, cookie di profilazione 
                                    e di terze parti. Per maggiori informazioni consulta la nostra 
                                    <a href="<?php echo url('cookie-policy.php'); ?>">Cookie Policy</a>.
                                </p>
                            </div>
                            
                            <!-- Purpose of Processing -->
                            <div class="privacy-section">
                                <h2>4. Finalità del Trattamento</h2>
                                <p>
                                    I tuoi dati personali sono trattati per le seguenti finalità:
                                </p>
                                
                                <h3>4.1 Servizi Richiesti</h3>
                                <ul>
                                    <li>Rispondere alle tue richieste di informazioni</li>
                                    <li>Elaborare preventivi personalizzati</li>
                                    <li>Gestire richieste di assistenza tecnica</li>
                                    <li>Fornire i servizi di riparazione richiesti</li>
                                    <li>Gestire ordini di prodotti ricondizionati</li>
                                </ul>
                                
                                <h3>4.2 Comunicazioni</h3>
                                <ul>
                                    <li>Inviare comunicazioni relative ai servizi richiesti</li>
                                    <li>Fornire assistenza clienti</li>
                                    <li>Inviare aggiornamenti sullo stato delle riparazioni</li>
                                    <li>Con il tuo consenso, inviare newsletter e offerte promozionali</li>
                                </ul>
                                
                                <h3>4.3 Obblighi di Legge</h3>
                                <ul>
                                    <li>Adempiere agli obblighi previsti dalla legge</li>
                                    <li>Gestire la contabilità e la fatturazione</li>
                                    <li>Rispondere a richieste delle autorità competenti</li>
                                </ul>
                            </div>
                            
                            <!-- Legal Basis -->
                            <div class="privacy-section">
                                <h2>5. Base Giuridica del Trattamento</h2>
                                <p>
                                    Il trattamento dei tuoi dati personali si basa su:
                                </p>
                                <ul>
                                    <li><strong>Consenso:</strong> per l'invio di newsletter e comunicazioni marketing</li>
                                    <li><strong>Contratto:</strong> per l'erogazione dei servizi richiesti</li>
                                    <li><strong>Obbligo legale:</strong> per adempimenti fiscali e amministrativi</li>
                                    <li><strong>Legittimo interesse:</strong> per migliorare i nostri servizi e prevenire frodi</li>
                                </ul>
                            </div>
                            
                            <!-- Data Retention -->
                            <div class="privacy-section">
                                <h2>6. Periodo di Conservazione</h2>
                                <p>
                                    I dati personali saranno conservati per il periodo necessario al conseguimento 
                                    delle finalità per le quali sono stati raccolti:
                                </p>
                                <ul>
                                    <li><strong>Dati contrattuali:</strong> 10 anni dalla conclusione del contratto 
                                        (per obblighi fiscali)</li>
                                    <li><strong>Dati di marketing:</strong> fino alla revoca del consenso o 
                                        massimo 24 mesi dall'ultimo contatto</li>
                                    <li><strong>Dati di navigazione:</strong> massimo 12 mesi</li>
                                    <li><strong>Cookie:</strong> secondo quanto specificato nella Cookie Policy</li>
                                </ul>
                            </div>
                            
                            <!-- Data Sharing -->
                            <div class="privacy-section">
                                <h2>7. Comunicazione e Diffusione dei Dati</h2>
                                <p>
                                    I tuoi dati personali potranno essere comunicati a:
                                </p>
                                <ul>
                                    <li>Personale autorizzato di Key Soft Italia</li>
                                    <li>Fornitori di servizi IT e hosting</li>
                                    <li>Consulenti e commercialisti per adempimenti fiscali</li>
                                    <li>Corrieri per spedizioni (solo dati necessari alla consegna)</li>
                                    <li>Autorità competenti quando richiesto dalla legge</li>
                                </ul>
                                <p>
                                    I tuoi dati non saranno oggetto di diffusione né saranno venduti a terze parti.
                                </p>
                            </div>
                            
                            <!-- User Rights -->
                            <div class="privacy-section">
                                <h2>8. I Tuoi Diritti</h2>
                                <p>
                                    In qualità di interessato, hai i seguenti diritti previsti dal GDPR:
                                </p>
                                
                                <div class="rights-grid">
                                    <div class="right-card">
                                        <h4><i class="ri-eye-line"></i> Diritto di Accesso</h4>
                                        <p>Ottenere conferma del trattamento e accedere ai tuoi dati</p>
                                    </div>
                                    
                                    <div class="right-card">
                                        <h4><i class="ri-edit-line"></i> Diritto di Rettifica</h4>
                                        <p>Correggere dati inesatti o incompleti</p>
                                    </div>
                                    
                                    <div class="right-card">
                                        <h4><i class="ri-delete-bin-line"></i> Diritto alla Cancellazione</h4>
                                        <p>Richiedere la cancellazione dei tuoi dati ("diritto all'oblio")</p>
                                    </div>
                                    
                                    <div class="right-card">
                                        <h4><i class="ri-pause-circle-line"></i> Diritto di Limitazione</h4>
                                        <p>Limitare il trattamento dei tuoi dati</p>
                                    </div>
                                    
                                    <div class="right-card">
                                        <h4><i class="ri-download-line"></i> Diritto alla Portabilità</h4>
                                        <p>Ricevere i tuoi dati in formato strutturato</p>
                                    </div>
                                    
                                    <div class="right-card">
                                        <h4><i class="ri-hand-heart-line"></i> Diritto di Opposizione</h4>
                                        <p>Opporti al trattamento per motivi legittimi</p>
                                    </div>
                                </div>
                                
                                <p class="mt-4">
                                    Per esercitare i tuoi diritti, puoi contattarci all'indirizzo email: 
                                    <a href="mailto:privacy@keysoftitalia.it">privacy@keysoftitalia.it</a>
                                </p>
                                
                                <p>
                                    Hai inoltre il diritto di proporre reclamo all'Autorità Garante per la 
                                    Protezione dei Dati Personali se ritieni che il trattamento violi il GDPR.
                                </p>
                            </div>
                            
                            <!-- Data Security -->
                            <div class="privacy-section">
                                <h2>9. Sicurezza dei Dati</h2>
                                <p>
                                    Adottiamo misure di sicurezza appropriate per proteggere i tuoi dati personali 
                                    contro accessi non autorizzati, alterazione, divulgazione o distruzione. 
                                    Queste misure includono:
                                </p>
                                <ul>
                                    <li>Crittografia SSL/TLS per la trasmissione dei dati</li>
                                    <li>Accesso limitato ai dati solo al personale autorizzato</li>
                                    <li>Backup regolari e sistemi di disaster recovery</li>
                                    <li>Aggiornamenti di sicurezza regolari</li>
                                    <li>Formazione del personale sulla protezione dei dati</li>
                                </ul>
                            </div>
                            
                            <!-- International Transfers -->
                            <div class="privacy-section">
                                <h2>10. Trasferimenti Internazionali</h2>
                                <p>
                                    I tuoi dati personali sono conservati su server situati all'interno 
                                    dell'Unione Europea. Nel caso in cui sia necessario trasferire dati 
                                    al di fuori dell'UE, garantiremo che siano in atto adeguate misure 
                                    di protezione in conformità con il GDPR.
                                </p>
                            </div>
                            
                            <!-- Minors -->
                            <div class="privacy-section">
                                <h2>11. Minori</h2>
                                <p>
                                    I nostri servizi non sono destinati a persone di età inferiore ai 16 anni. 
                                    Non raccogliamo consapevolmente dati personali da minori di 16 anni. 
                                    Se sei un genitore o tutore e sei a conoscenza che tuo figlio ci ha 
                                    fornito dati personali, ti preghiamo di contattarci.
                                </p>
                            </div>
                            
                            <!-- Updates -->
                            <div class="privacy-section">
                                <h2>12. Modifiche alla Privacy Policy</h2>
                                <p>
                                    Ci riserviamo il diritto di modificare questa Privacy Policy in qualsiasi momento. 
                                    Le modifiche saranno pubblicate su questa pagina con l'indicazione della data 
                                    di aggiornamento. Ti invitiamo a consultare regolarmente questa pagina per 
                                    rimanere informato su come proteggiamo i tuoi dati.
                                </p>
                            </div>
                            
                            <!-- Contact -->
                            <div class="privacy-section">
                                <h2>13. Contatti</h2>
                                <p>
                                    Per qualsiasi domanda o richiesta relativa a questa Privacy Policy o 
                                    al trattamento dei tuoi dati personali, puoi contattarci:
                                </p>
                                <div class="contact-info">
                                    <p>
                                        <i class="ri-mail-line"></i> Email: 
                                        <a href="mailto:privacy@keysoftitalia.it">privacy@keysoftitalia.it</a>
                                    </p>
                                    <p>
                                        <i class="ri-phone-line"></i> Telefono: 
                                        <a href="tel:<?php echo PHONE_PRIMARY; ?>"><?php echo PHONE_PRIMARY; ?></a>
                                    </p>
                                    <p>
                                        <i class="ri-map-pin-line"></i> Indirizzo: <?php echo ADDRESS; ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Sidebar -->
                <div class="col-lg-4">
                    <div class="sidebar-sticky">
                        <div class="sidebar-card">
                            <h4 class="sidebar-title">
                                <i class="ri-shield-check-line"></i> GDPR Compliance
                            </h4>
                            <p>
                                La nostra azienda è pienamente conforme al Regolamento Generale 
                                sulla Protezione dei Dati (GDPR) dell'Unione Europea.
                            </p>
                        </div>
                        
                        <div class="sidebar-card">
                            <h4 class="sidebar-title">
                                <i class="ri-links-line"></i> Link Utili
                            </h4>
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <a href="<?php echo url('cookie-policy.php'); ?>">
                                        <i class="ri-arrow-right-s-line"></i> Cookie Policy
                                    </a>
                                </li>
                                <li class="mb-2">
                                    <a href="<?php echo url('termini-servizio.php'); ?>">
                                        <i class="ri-arrow-right-s-line"></i> Termini di Servizio
                                    </a>
                                </li>
                                <li class="mb-2">
                                    <a href="https://www.garanteprivacy.it" target="_blank" rel="noopener">
                                        <i class="ri-arrow-right-s-line"></i> Garante Privacy
                                    </a>
                                </li>
                            </ul>
                        </div>
                        
                        <div class="sidebar-card bg-primary text-white">
                            <h4 class="sidebar-title">
                                <i class="ri-question-line"></i> Hai domande?
                            </h4>
                            <p>
                                Se hai domande sulla nostra Privacy Policy, non esitare a contattarci.
                            </p>
                            <a href="mailto:privacy@keysoftitalia.it" class="btn btn-light btn-sm">
                                <i class="ri-mail-line"></i> Contatta il DPO
                            </a>
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
    
    <!-- Set BASE_URL for JavaScript -->
    <script>
        window.KS_CONFIG = {
            baseUrl: '<?php echo BASE_URL; ?>',
            whatsappNumber: '<?php echo WHATSAPP_NUMBER; ?>'
        };
    </script>
</body>
</html>