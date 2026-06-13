<?php
/**
 * Key Soft Italia - LibertyCommerce & Comanda Facile
 * Pagina di dettaglio per le soluzioni software gestionali retail e ristorazione
 */

if (!defined('BASE_PATH')) {
    define('BASE_PATH', dirname(__DIR__) . '/');
}

require_once BASE_PATH . 'config/config.php';

// Inizializza sessione se non attiva (per CSRF)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// SEO Meta
$page_title = "LibertyCommerce e Comanda Facile - Key Soft Italia";
$page_description = "I migliori software gestionali per negozi, aziende e ristoranti a Ginosa. Rivenditore autorizzato LibertyCommerce e Comanda Facile: installazione e supporto.";
$page_keywords = "libertycommerce ginosa, comanda facile ristorazione, software gestionale negozi, fatturazione elettronica, registratore di cassa telematico, comande tablet";

// Breadcrumbs
$breadcrumbs = [
    ['label' => 'Servizi', 'url' => '../servizi.php'],
    ['label' => 'Liberty Commerce', 'url' => 'liberty-commerce.php']
];
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <?php include '../includes/head.php'; ?>
    <!-- CSS di pagina -->
    <link rel="stylesheet" href="<?php echo asset_version('css/pages/liberty-commerce.css'); ?>">
</head>
<body>
    
    <!-- Header -->
    <?php include '../includes/header.php'; ?>
    
    <!-- Hero Section -->
    <section class="hero hero-secondary text-center">
        <div class="hero-pattern"></div>
        <div class="container position-relative z-2" data-aos="fade-up">
            <div class="hero-icon mb-3" data-aos="zoom-in">
                <i class="ri-computer-line text-info"></i>
            </div>
            <h1 class="hero-title text-white" data-aos="fade-up" data-aos-delay="100">
                LibertyCommerce & <span class="text-gradient">Comanda Facile</span>
            </h1>
            <p class="hero-subtitle" data-aos="fade-up" data-aos-delay="200">
                Semplifica la fatturazione, il magazzino e la presa degli ordini. Le soluzioni software professionali per negozi, aziende e attività di ristorazione.
            </p>
            <div class="hero-cta" data-aos="fade-up" data-aos-delay="300">
                <a href="#soluzioni" class="btn btn-primary btn-lg smooth-scroll" aria-label="Scopri i software gestionali">
                    <i class="ri-arrow-down-line me-1"></i> Scopri le Soluzioni
                </a>
            </div>
            <div class="hero-breadcrumb mt-4" data-aos="fade-up" data-aos-delay="400">
                <?php echo generate_breadcrumbs($breadcrumbs); ?>
            </div>
        </div>
    </section>
    
    <!-- Software Solutions Grid -->
    <section id="soluzioni" class="section-features-split">
        <div class="container">
            <div class="section-header text-center mb-5" data-aos="fade-up">
                <h2 class="section-title">Software Gestionali su <span class="text-gradient">Misura del tuo Business</span></h2>
                <p class="section-subtitle">Piattaforme stabili, veloci e costantemente aggiornate secondo le normative fiscali italiane</p>
            </div>
            
            <div class="row g-4">
                <!-- LibertyCommerce Card -->
                <div class="col-lg-6" data-aos="fade-right">
                    <div class="service-card featured">
                        <div class="service-icon">
                            <i class="ri-store-2-line"></i>
                        </div>
                        <h3>
                            LibertyCommerce
                            <small>Negozi, Artigiani, Grossisti e PMI</small>
                        </h3>
                        <p class="text-muted">Il programma gestionale completo per Windows ideale per gestire le vendite al banco, il magazzino e gli adempimenti fiscali della tua attività commerciale.</p>
                        
                        <div class="price-tag">Versione LITE, ESSENTIAL e BUSINESS</div>
                        
                        <ul class="software-features">
                            <li>
                                <i class="ri-checkbox-circle-line"></i>
                                <span><strong>Fatturazione Elettronica integrata:</strong> Invio e ricezione diretta di fatture (B2B, B2C e Pubblica Amministrazione) in pochi clic.</span>
                            </li>
                            <li>
                                <i class="ri-checkbox-circle-line"></i>
                                <span><strong>Gestione Magazzino & Giacenze:</strong> Carico/scarico articoli, gestione lotti di produzione, date di scadenza, codici a barre e inventari.</span>
                            </li>
                            <li>
                                <i class="ri-checkbox-circle-line"></i>
                                <span><strong>Interfaccia Cassa Touch Screen:</strong> Interfaccia TPV intuitiva collegabile ai registratori telematici per scontrini elettronici veloci.</span>
                            </li>
                            <li>
                                <i class="ri-checkbox-circle-line"></i>
                                <span><strong>Sincronizzazione E-commerce:</strong> Integrazione nativa bidirezionale con Prestashop, WooCommerce e marketplace come Amazon e eBay.</span>
                            </li>
                            <li>
                                <i class="ri-checkbox-circle-line"></i>
                                <span><strong>Scadenzari e Prima Nota:</strong> Gestione dei flussi finanziari dei clienti e dei fornitori con reportistica contabile semplificata.</span>
                            </li>
                        </ul>
                    </div>
                </div>
                
                <!-- Comanda Facile Card -->
                <div class="col-lg-6" data-aos="fade-left">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="ri-restaurant-line"></i>
                        </div>
                        <h3>
                            Comanda Facile
                            <small>Ristoranti, Pizzerie, Bar e Pub</small>
                        </h3>
                        <p class="text-muted">Il modulo verticale integrato con LibertyCommerce che automatizza la presa degli ordini ai tavoli, la gestione dei centri di stampa e il monitor in cucina.</p>
                        
                        <div class="price-tag">App Mobile inclusa (iOS e Android)</div>
                        
                        <ul class="software-features">
                            <li>
                                <i class="ri-checkbox-circle-line"></i>
                                <span><strong>Comande via Smartphone e Tablet:</strong> I camerieri utilizzano dispositivi mobili standard per inviare le ordinazioni direttamente ai reparti.</span>
                            </li>
                            <li>
                                <i class="ri-checkbox-circle-line"></i>
                                <span><strong>Schermi in Cucina (Kitchen Monitor):</strong> Monitor interattivi installati in cucina o al bar per visualizzare e smaltire i piatti in preparazione.</span>
                            </li>
                            <li>
                                <i class="ri-checkbox-circle-line"></i>
                                <span><strong>Mappa dei Tavoli Interattiva:</strong> Layout grafico personalizzabile del locale con indicazione visiva dello stato (tavolo libero, occupato, conto).</span>
                            </li>
                            <li>
                                <i class="ri-checkbox-circle-line"></i>
                                <span><strong>Gestione Asporto e Domicilio (Delivery):</strong> Modulo dedicato per gestire gli orari delle consegne e i fattorini in modo ordinato.</span>
                            </li>
                            <li>
                                <i class="ri-checkbox-circle-line"></i>
                                <span><strong>Stampanti di Produzione multiple:</strong> Invio automatico e selettivo degli ordini a stampanti termiche (fino a 5 centri diversi).</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Partner & Reseller Section -->
    <section class="section-reseller-info">
        <div class="container position-relative z-1 text-center">
            <div class="row justify-content-center">
                <div class="col-lg-8" data-aos="fade-up">
                    <div class="badge bg-warning-light text-warning fw-bold px-3 py-2 rounded-full mb-3" style="background-color: var(--ks-orange-light); color: var(--ks-orange);">
                        <i class="ri-medal-line me-1"></i> RIVENDITORE AUTORIZZATO GINOSA
                    </div>
                    <h2 class="fw-bold mb-4">Siamo il tuo Partner Tecnologico per l'Installazione e il Supporto</h2>
                    <p class="lead text-muted mb-0">Non vendiamo solo il software: ti seguiamo in ogni fase della digitalizzazione del tuo locale o negozio, garantendoti continuità operativa ed assistenza sul posto.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Support Services Grid -->
    <section class="section-services-grid">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-3" data-aos="fade-up" data-aos-delay="100">
                    <div class="support-feature">
                        <i class="ri-settings-3-line"></i>
                        <h4>Installazione Hardware</h4>
                        <p>Configuriamo computer server, cavi di rete, stampanti termiche per la comanda, tablet per camerieri e la cassa telematica.</p>
                    </div>
                </div>
                
                <div class="col-md-3" data-aos="fade-up" data-aos-delay="200">
                    <div class="support-feature">
                        <i class="ri-group-line"></i>
                        <h4>Formazione Personale</h4>
                        <p>Formiamo te e il tuo staff sull'uso corretto di cassa, invio comande, gestione del menù, conti separati e fatturazione.</p>
                    </div>
                </div>
                
                <div class="col-md-3" data-aos="fade-up" data-aos-delay="300">
                    <div class="support-feature">
                        <i class="ri-customer-service-2-line"></i>
                        <h4>Assistenza Tecnica</h4>
                        <p>Forniamo supporto telefonico, teleassistenza o interventi rapidi direttamente nel tuo locale per risolvere qualsiasi blocco.</p>
                    </div>
                </div>
                
                <div class="col-md-3" data-aos="fade-up" data-aos-delay="400">
                    <div class="support-feature">
                        <i class="ri-scales-line"></i>
                        <h4>Adeguamento Fiscale</h4>
                        <p>Manteniamo il sistema aggiornato con le direttive dell'Agenzia delle Entrate (scontrini elettronici e invio corrispettivi).</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="section-cta-clean text-center">
        <div class="container" data-aos="fade-up">
            <h2 class="cta-title">Hai Bisogno di un Preventivo o di una Demo?</h2>
            <p class="cta-subtitle">Vieni a trovarci in via Diaz, 46 a Ginosa. Ti mostreremo una demo del software LibertyCommerce o Comanda Facile.</p>
            <div class="cta-buttons">
                <a href="<?php echo url('contatti.php'); ?>" class="btn btn-primary btn-lg">
                    <i class="ri-map-pin-line me-1"></i> Vieni in Negozio
                </a>
                <a href="tel:<?php echo PHONE_PRIMARY; ?>" class="btn btn-outline-dark btn-lg">
                    <i class="ri-phone-line me-1"></i> Chiama Ora
                </a>
                <a href="<?php echo whatsapp_link('Salve, vorrei informazioni sui gestionali LibertyCommerce o Comanda Facile per la mia attività'); ?>" 
                   class="btn btn-success btn-lg" target="_blank" rel="noopener">
                    <i class="ri-whatsapp-line me-1"></i> Chiedi su WhatsApp
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php include '../includes/footer.php'; ?>

</body>
</html>
