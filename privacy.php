<?php
/**
 * Key Soft Italia - Privacy Policy, Cookie Policy & Termini di Servizio
 * Gestione centralizzata delle informative legali del sito
 */

// Define BASE_PATH if not defined
if (!defined('BASE_PATH')) {
    define('BASE_PATH', __DIR__ . '/');
}

require_once BASE_PATH . 'config/config.php';

// Determina quale tab mostrare (privacy, terms, cookies)
$active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'privacy';
if (!in_array($active_tab, ['privacy', 'terms', 'cookies'])) {
    $active_tab = 'privacy';
}

// Configura i metadati SEO e i breadcrumbs a seconda del tab attivo
if ($active_tab === 'terms') {
    $page_title = "Termini di Servizio - Key Soft Italia | Condizioni d'Uso";
    $page_description = "Termini e condizioni di servizio di Key Soft Italia. Leggi le regole d'uso del sito web e delle nostre prestazioni professionali.";
    $page_keywords = "termini di servizio, termini d'uso, condizioni di servizio, key soft italia";
    $breadcrumbs = [
        ['label' => 'Termini di Servizio', 'url' => 'privacy.php?tab=terms']
    ];
} elseif ($active_tab === 'cookies') {
    $page_title = "Cookie Policy - Key Soft Italia | Gestione Cookie";
    $page_description = "Informativa sui cookie di Key Soft Italia. Informazioni sull'uso di cookie e sul consenso per l'analisi del traffico.";
    $page_keywords = "cookie policy, informativa cookie, preferenze cookie, gdpr cookie, key soft italia";
    $breadcrumbs = [
        ['label' => 'Cookie Policy', 'url' => 'privacy.php?tab=cookies']
    ];
} else {
    $page_title = "Privacy Policy - Key Soft Italia | Informativa Privacy GDPR";
    $page_description = "Informativa sulla privacy di Key Soft Italia. Scopri come trattiamo e proteggiamo i tuoi dati personali in conformità al GDPR.";
    $page_keywords = "privacy policy, informativa privacy, gdpr, protezione dati, key soft italia";
    $breadcrumbs = [
        ['label' => 'Privacy Policy', 'url' => 'privacy.php?tab=privacy']
    ];
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <?php include 'includes/head.php'; ?>
    <link rel="stylesheet" href="<?php echo asset_version('css/pages/privacy.css'); ?>">
</head>
<body>
    
    <!-- Header -->
    <?php include 'includes/header.php'; ?>
    
    <?php
    $hero_icon = 'ri-shield-keyhole-line';
    $hero_title = 'Privacy <span class="text-gradient">Policy</span>';
    $hero_subtitle = 'Informativa sulla Privacy e Protezione dei Dati Personali';

    if ($active_tab === 'terms') {
        $hero_icon = 'ri-file-list-3-line';
        $hero_title = 'Termini di <span class="text-gradient">Servizio</span>';
        $hero_subtitle = 'Condizioni Generali di Utilizzo del Servizio';
    } elseif ($active_tab === 'cookies') {
        $hero_icon = 'ri-shield-user-line';
        $hero_title = 'Cookie <span class="text-gradient">Policy</span>';
        $hero_subtitle = 'Informativa sull\'uso dei Cookie e Preferenze di Consenso';
    }
    ?>
    <!-- HERO -->
    <section class="hero hero-secondary text-center">
      <div class="hero-pattern"></div>
      <div class="container position-relative z-2" data-aos="fade-up">
        <div class="hero-icon mb-3" data-aos="zoom-in">
          <i class="<?php echo $hero_icon; ?>"></i>
        </div>
        <h1 class="hero-title text-white" data-aos="fade-up" data-aos-delay="100">
          <?php echo $hero_title; ?>
        </h1>
        <p class="hero-subtitle" data-aos="fade-up" data-aos-delay="200">
          <?php echo $hero_subtitle; ?>
        </p>
        <div class="hero-breadcrumb mt-4" data-aos="fade-up" data-aos-delay="300">
          <?php echo generate_breadcrumbs($breadcrumbs); ?>
        </div>
      </div>
    </section>
    
    <!-- Privacy Content -->
    <section class="section section-privacy" data-aos="fade-up">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="privacy-card">
                        <div class="privacy-header">
                            <p class="text-muted">Ultimo aggiornamento: <?php echo date('d/m/Y'); ?></p>
                        </div>
                        
                        <div class="privacy-content">
                            <?php if ($active_tab === 'privacy'): ?>
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
                                    <a href="<?php echo url('privacy.php?tab=cookies'); ?>">Cookie Policy</a>.
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
                            <?php elseif ($active_tab === 'terms'): ?>
                            <!-- Terms of Service Content -->
                            <div class="privacy-section">
                                <h2>1. Oggetto del Servizio</h2>
                                <p>
                                    I presenti Termini di Servizio regolano l'accesso e l'utilizzo del sito web di Key Soft Italia 
                                    e dei servizi ad esso collegati, tra cui la richiesta di preventivi online, la prenotazione 
                                    di riparazioni e la consultazione di prodotti. L'utilizzo del sito implica l'accettazione 
                                    integrale delle presenti condizioni.
                                </p>
                            </div>

                            <div class="privacy-section">
                                <h2>2. Richieste di Preventivo e Diagnosi</h2>
                                <p>
                                    I prezzi calcolati attraverso lo strumento di preventivo automatico online sono puramente 
                                    indicativi e basati sulle informazioni fornite dall'utente. Essi non costituiscono una 
                                    quotazione contrattuale vincolante.
                                </p>
                                <p>
                                    Il costo definitivo della riparazione sarà comunicato al cliente solo a seguito di una diagnosi 
                                    fisica completa effettuata dai nostri tecnici presso il laboratorio di Key Soft Italia.
                                </p>
                            </div>

                            <div class="privacy-section">
                                <h2>3. Consegna dei Dispositivi e Sicurezza dei Dati</h2>
                                <p>
                                    È responsabilità esclusiva del cliente effettuare un backup completo di tutti i dati 
                                    presenti sul dispositivo (foto, contatti, messaggi, ecc.) prima di consegnarlo in riparazione.
                                </p>
                                <div class="info-box">
                                    <p>
                                        <strong>ATTENZIONE:</strong> Key Soft Italia non è in alcun modo responsabile per la 
                                        perdita, il danneggiamento o la compromissione di dati e file memorizzati sui dispositivi 
                                        consegnati per l'assistenza tecnica o la riparazione.
                                    </p>
                                </div>
                            </div>

                            <div class="privacy-section">
                                <h2>4. Garanzia sulle Riparazioni</h2>
                                <p>
                                    Key Soft Italia offre una garanzia di 3 mesi su tutte le riparazioni effettuate, a partire 
                                    dalla data di riconsegna del dispositivo. La garanzia si applica esclusivamente ai componenti 
                                    sostituiti e per il medesimo difetto riscontrato.
                                </p>
                                <p>
                                    La garanzia decade immediatamente in caso di manomissione da parte di terzi, danni accidentali, 
                                    cadute, urti o contatto con liquidi successivi alla riconsegna.
                                </p>
                            </div>

                            <div class="privacy-section">
                                <h2>5. Prodotti Ricondizionati</h2>
                                <p>
                                    I dispositivi ricondizionati venduti da Key Soft Italia sono coperti da una garanzia legale 
                                    di conformità di 12 mesi dalla data di acquisto, salvo diversa indicazione esplicita al momento 
                                    della vendita. La garanzia copre i difetti di conformità hardware e non i danni accidentali o l'usura 
                                    della batteria successiva all'acquisto.
                                </p>
                            </div>

                            <div class="privacy-section">
                                <h2>6. Limitazione di Responsabilità</h2>
                                <p>
                                    Key Soft Italia si impegna a fornire i propri servizi con la massima cura e professionalità. 
                                    Tuttavia, non potrà essere ritenuta responsabile per ritardi nell'approvvigionamento dei ricambi 
                                    da parte dei fornitori o per cause di forza maggiore che impediscano la riparazione nei tempi stimati.
                                </p>
                            </div>
                            <?php elseif ($active_tab === 'cookies'): ?>
                            <!-- Cookie Policy Content -->
                            <div class="privacy-section">
                                <h2>1. Cosa sono i Cookie</h2>
                                <p>
                                    I cookie sono piccoli file di testo che i siti visitati dagli utenti inviano ai loro terminali, 
                                    ove vengono memorizzati per essere poi ritrasmessi dagli stessi siti alla visita successiva. 
                                    I cookie sono usati per differenti finalità: esecuzione di autenticazioni informatiche, 
                                    monitoraggio di sessioni, memorizzazione di informazioni su specifiche configurazioni 
                                    riguardanti gli utenti che accedono al server, ecc.
                                </p>
                            </div>

                            <div class="privacy-section">
                                <h2>2. Cookie Utilizzati da questo Sito</h2>
                                <p>
                                    Questo sito web utilizza esclusivamente le seguenti categorie di cookie:
                                </p>
                                
                                <h3>2.1 Cookie Tecnici e Necessari</h3>
                                <p>
                                    Si tratta di cookie fondamentali per consentire la navigazione all'interno del sito e l'utilizzo 
                                    delle sue funzionalità, come l'accesso ad aree protette o la gestione della sessione di richiesta 
                                    preventivo. Senza questi cookie, alcuni servizi essenziali non potrebbero essere forniti. 
                                    Ai sensi della normativa vigente, per l'installazione di tali cookie non è richiesto il preventivo consenso.
                                </p>
                                
                                <h3>2.2 Cookie di Statistica e Analisi (Google Analytics 4)</h3>
                                <p>
                                    Utilizziamo Google Analytics 4 (GA4) per raccogliere informazioni in forma anonima e aggregata 
                                    sul numero degli utenti e su come questi visitano il sito. Il sistema è configurato per mascherare 
                                    l'indirizzo IP dell'utente e rispetta il Consent Mode v2 di Google.
                                </p>
                                <p>
                                    L'utente può prestare o negare il proprio consenso all'uso di questi cookie attraverso il banner 
                                    iniziale o modificando le proprie preferenze in qualsiasi momento.
                                </p>
                            </div>

                            <div class="privacy-section">
                                <h2>3. Gestione del Consenso e Preferenze Cookie</h2>
                                <p>
                                    Puoi configurare le tue preferenze relative ai cookie in qualsiasi momento cliccando sul link 
                                    <strong>"Preferenze cookie"</strong> situato nel footer di ogni pagina del sito. Questo riaprirà 
                                    il banner di scelta consentendoti di modificare o revocare il tuo consenso.
                                </p>
                                <div class="info-box text-center">
                                    <p>
                                        Puoi reimpostare o modificare le tue scelte sui cookie direttamente da qui:
                                    </p>
                                    <button id="btn-reopen-cookies-inline" class="btn btn-primary btn-sm mt-2">
                                        <i class="ri-settings-3-line"></i> Gestisci Consenso Cookie
                                    </button>
                                </div>
                            </div>

                            <script>
                            document.getElementById('btn-reopen-cookies-inline')?.addEventListener('click', function(e) {
                                e.preventDefault();
                                document.getElementById('open-consent-manager')?.click();
                            });
                            </script>
                            <?php endif; ?>
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
                                    <a href="<?php echo url('privacy.php?tab=privacy'); ?>" class="<?php echo $active_tab === 'privacy' ? 'fw-semibold text-primary' : ''; ?>">
                                        <i class="ri-arrow-right-s-line"></i> Privacy Policy
                                    </a>
                                </li>
                                <li class="mb-2">
                                    <a href="<?php echo url('privacy.php?tab=cookies'); ?>" class="<?php echo $active_tab === 'cookies' ? 'fw-semibold text-primary' : ''; ?>">
                                        <i class="ri-arrow-right-s-line"></i> Cookie Policy
                                    </a>
                                </li>
                                <li class="mb-2">
                                    <a href="<?php echo url('privacy.php?tab=terms'); ?>" class="<?php echo $active_tab === 'terms' ? 'fw-semibold text-primary' : ''; ?>">
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
                                Se hai domande, non esitare a contattarci.
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