<?php
/**
 * Key Soft Italia - Servizio Consulenza IT
 * Pagina dettaglio servizi di consulenza informatica e sistemistica (Allineata a chi-siamo.php)
 * Carica i piani di assistenza dinamicamente dal database
 */

if (!defined('BASE_PATH')) {
    define('BASE_PATH', dirname(__DIR__) . '/');
}

require_once BASE_PATH . 'config/config.php';

// Carichiamo i pacchetti listino attivi
$packages = [];
try {
    $stmt = $pdo->query("SELECT * FROM it_packages WHERE status = 1 ORDER BY sort_order ASC, id ASC");
    $packages = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $packages = [];
}

// SEO Meta
$page_title = "Consulenza IT e Assistenza Sistemistica - Key Soft Italia";
$page_description = "Consulenza informatica professionale per aziende e professionisti a Ginosa. Configurazione server, reti aziendali, backup, sicurezza informatica e GDPR.";
$page_keywords = "consulenza it ginosa, assistenza sistemistica, sicurezza informatica, gestione server, reti lan wifi, gdpr compliance";

// Breadcrumbs
$breadcrumbs = [
    ['label' => 'Servizi', 'url' => '../servizi.php'],
    ['label' => 'Consulenza IT', 'url' => 'consulenza-it.php']
];
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <?php include '../includes/head.php'; ?>
    <!-- CSS di pagina -->
    <link rel="stylesheet" href="<?php echo asset_version('css/pages/consulenza-it.css'); ?>">
</head>
<body>
    
    <!-- Header -->
    <?php include '../includes/header.php'; ?>
    
    <!-- Hero Section -->
    <section class="hero hero-secondary text-center">
        <div class="hero-pattern"></div>
        <div class="container position-relative z-2" data-aos="fade-up">
            <div class="hero-icon mb-3" data-aos="zoom-in">
                <i class="ri-customer-service-2-line"></i>
            </div>
            <h1 class="hero-title text-white" data-aos="fade-up" data-aos-delay="100">
                Consulenza IT <span class="text-gradient">e Sistemistica</span>
            </h1>
            <p class="hero-subtitle" data-aos="fade-up" data-aos-delay="200">
                Ottimizziamo l'infrastruttura tecnologica della tua attività per garantire sicurezza, stabilità e velocità
            </p>
            <div class="hero-cta" data-aos="fade-up" data-aos-delay="300">
                <a href="#servizi" class="btn btn-primary btn-lg smooth-scroll" aria-label="Scopri i nostri servizi di consulenza IT">
                    <i class="ri-arrow-down-line me-1"></i> Scopri i Servizi
                </a>
            </div>
            <div class="hero-breadcrumb mt-4" data-aos="fade-up" data-aos-delay="400">
                <?php echo generate_breadcrumbs($breadcrumbs); ?>
            </div>
        </div>
    </section>
    
    <!-- Services Grid -->
    <section id="servizi" class="section-services">
        <div class="container">
            <div class="section-header text-center mb-5" data-aos="fade-up">
                <h2 class="section-title">Aree di <span class="text-gradient">Intervento IT</span></h2>
                <p class="section-subtitle">Supporto tecnico sistemistico qualificato e soluzioni cloud su misura</p>
            </div>
            
            <div class="row g-4">
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="ri-server-line"></i>
                        </div>
                        <h4>Gestione Server & Cloud</h4>
                        <p>Installazione, configurazione e monitoraggio continuo di server fisici e virtualizzati.</p>
                        <ul class="list-unstyled mt-3">
                            <li><i class="ri-checkbox-circle-line"></i> Windows / Linux Server</li>
                            <li><i class="ri-checkbox-circle-line"></i> Virtualizzazione Hyper-V / VMware</li>
                            <li><i class="ri-checkbox-circle-line"></i> Manutenzione proattiva</li>
                        </ul>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="ri-shield-check-line"></i>
                        </div>
                        <h4>Sicurezza Informatica</h4>
                        <p>Proteggiamo la rete ed i dati della tua azienda da attacchi informatici esterni e malware.</p>
                        <ul class="list-unstyled mt-3">
                            <li><i class="ri-checkbox-circle-line"></i> Firewall di rete Enterprise</li>
                            <li><i class="ri-checkbox-circle-line"></i> Antivirus centralizzati & Endpoint</li>
                            <li><i class="ri-checkbox-circle-line"></i> Vulnerability Assessment</li>
                        </ul>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="ri-wifi-line"></i>
                        </div>
                        <h4>Reti Aziendali LAN / WiFi</h4>
                        <p>Progettazione, cablaggio strutturato e installazione di infrastrutture di rete stabili.</p>
                        <ul class="list-unstyled mt-3">
                            <li><i class="ri-checkbox-circle-line"></i> Reti locali cablate e armadi rack</li>
                            <li><i class="ri-checkbox-circle-line"></i> WiFi aziendale con captive portal</li>
                            <li><i class="ri-checkbox-circle-line"></i> Tunnel VPN per smart working</li>
                        </ul>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="400">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="ri-database-2-line"></i>
                        </div>
                        <h4>Backup & Disaster Recovery</h4>
                        <p>Soluzioni e politiche di salvataggio dati per garantire la continuità aziendale in ogni scenario.</p>
                        <ul class="list-unstyled mt-3">
                            <li><i class="ri-checkbox-circle-line"></i> Backup automatici locali</li>
                            <li><i class="ri-checkbox-circle-line"></i> Cloud backup crittografato</li>
                            <li><i class="ri-checkbox-circle-line"></i> Piani di Disaster Recovery veloci</li>
                        </ul>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="500">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="ri-cloud-line"></i>
                        </div>
                        <h4>Migrazione in Cloud</h4>
                        <p>Supportiamo la transizione della tua produttività d'ufficio verso il Cloud in sicurezza.</p>
                        <ul class="list-unstyled mt-3">
                            <li><i class="ri-checkbox-circle-line"></i> Microsoft 365 e Teams</li>
                            <li><i class="ri-checkbox-circle-line"></i> Google Workspace</li>
                            <li><i class="ri-checkbox-circle-line"></i> Risorse AWS / Microsoft Azure</li>
                        </ul>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="600">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="ri-file-shield-2-line"></i>
                        </div>
                        <h4>GDPR & Compliance Dati</h4>
                        <p>Analisi dei sistemi per garantire la conformità al Regolamento Privacy Europeo.</p>
                        <ul class="list-unstyled mt-3">
                            <li><i class="ri-checkbox-circle-line"></i> Security Audit informatico</li>
                            <li><i class="ri-checkbox-circle-line"></i> Redazione policy di sicurezza</li>
                            <li><i class="ri-checkbox-circle-line"></i> Formazione del personale interno</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Consultation Types Section -->
    <section class="section-modalities">
        <div class="container position-relative z-1">
            <div class="section-header text-center mb-5" data-aos="fade-up">
                <h2 class="section-title">Modalità di <span class="text-gradient">Assistenza</span></h2>
                <p class="section-subtitle">Scegli la formula di intervento adatta ai flussi di lavoro della tua azienda</p>
            </div>
            
            <div class="row g-4">
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="type-card">
                        <i class="ri-building-line type-icon"></i>
                        <h4>On-Site (In Sede)</h4>
                        <p>Intervento dei nostri tecnici direttamente presso i tuoi uffici o negozi a Ginosa e dintorni.</p>
                    </div>
                </div>
                
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="type-card">
                        <i class="ri-global-line type-icon"></i>
                        <h4>Help Desk Remoto</h4>
                        <p>Assistenza telefonica ed in telecontrollo rapida per risolvere anomalie software in tempo reale.</p>
                    </div>
                </div>
                
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="type-card">
                        <i class="ri-calendar-check-line type-icon"></i>
                        <h4>Contratti di Assistenza SLA</h4>
                        <p>Pacchetti orari o canoni mensili con tempi di risposta e ripristino dei servizi informatici garantiti.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Work Process Section -->
    <section class="section-process">
        <div class="container">
            <div class="section-header text-center mb-5" data-aos="fade-up">
                <h2 class="section-title">Il Nostro <span class="text-gradient">Metodo</span></h2>
                <p class="section-subtitle">Processo metodologico per consolidare l'IT della tua azienda ed azzerare i fermi macchina</p>
            </div>
            
            <div class="row g-4">
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="100">
                    <div class="process-step">
                        <div class="process-number">1</div>
                        <h5>Analisi (Audit)</h5>
                        <p>Mappiamo l'infrastruttura IT esistente identificando falle di sicurezza e colli di bottiglia.</p>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="200">
                    <div class="process-step">
                        <div class="process-number">2</div>
                        <h5>Progettazione</h5>
                        <p>Disegniamo la soluzione ideale ottimizzando i costi ed evitando acquisti hardware superflui.</p>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="300">
                    <div class="process-step">
                        <div class="process-number">3</div>
                        <h5>Implementazione</h5>
                        <p>Installiamo e configuriamo i sistemi riducendo al minimo l'interruzione della tua attività.</p>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                    <div class="process-step">
                        <div class="process-number">4</div>
                        <h5>Manutenzione</h5>
                        <p>Garantiamo monitoraggio, aggiornamento dei sistemi e supporto sistemistico continuo.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section class="section-pricing">
        <div class="container">
            <div class="section-header text-center mb-5" data-aos="fade-up">
                <h2 class="section-title">Piani di <span class="text-gradient">Assistenza Gestita</span></h2>
                <p class="section-subtitle">Canoni chiari per mantenere la tua infrastruttura IT monitorata e protetta</p>
            </div>
            
            <div class="row g-4 justify-content-center">
                <?php if (empty($packages)): ?>
                    <div class="col-12 text-center py-5">
                        <div class="alert alert-info border-0 shadow-sm d-inline-block p-4 rounded-4" style="max-width: 500px;">
                            <i class="ri-information-line text-primary display-6 mb-3 d-block"></i>
                            <h5 class="fw-bold">Nessun piano di assistenza inserito</h5>
                            <p class="text-muted mb-0">Contattaci direttamente in negozio per richiedere un preventivo o un'offerta personalizzata su misura.</p>
                        </div>
                    </div>
                <?php else: ?>
                    <?php 
                    $delay = 0;
                    foreach ($packages as $pack): 
                        $delay += 100;
                        $is_featured = (int)$pack['is_featured'] === 1;
                        $features_arr = !empty($pack['features']) ? explode("\n", $pack['features']) : [];
                    ?>
                        <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="<?= $delay; ?>">
                            <div class="pricing-card <?= $is_featured ? 'featured' : ''; ?>">
                                <h3><?= htmlspecialchars($pack['title']); ?></h3>
                                <div class="price">
                                    <?php if ($pack['price'] !== null): ?>
                                        €<?= number_format($pack['price'], 0, ',', '.'); ?><small><?= htmlspecialchars($pack['price_detail']); ?></small>
                                    <?php else: ?>
                                        <?= htmlspecialchars($pack['price_detail'] ?: 'Su misura'); ?>
                                    <?php endif; ?>
                                </div>
                                <p class="text-muted"><?= htmlspecialchars($pack['subtitle']); ?></p>
                                <ul class="features-list">
                                    <?php foreach ($features_arr as $feat): 
                                        $feat = trim($feat);
                                        if (empty($feat)) continue;
                                    ?>
                                        <li>
                                            <i class="ri-checkbox-circle-line"></i>
                                            <span><?= htmlspecialchars($feat); ?></span>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                                <a href="<?php echo url('preventivo.php'); ?>?servizio=it" class="btn <?= $is_featured ? 'btn-primary' : 'btn-outline-primary'; ?> pricing-btn w-100">
                                    Scegli <?= htmlspecialchars($pack['title']); ?>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="section-cta-clean text-center">
        <div class="container" data-aos="fade-up">
            <h2 class="cta-title">Garantisci la Sicurezza della Tua Rete Aziendale</h2>
            <p class="cta-subtitle">Contattaci per richiedere un primo audit gratuito sui sistemi informatici ed evitare spiacevoli blocchi lavorativi</p>
            <div class="cta-buttons">
                <a href="<?php echo url('preventivo.php'); ?>?servizio=it" class="btn btn-primary btn-lg">
                    <i class="ri-file-list-3-line me-1"></i> Richiedi Consulenza
                </a>
                <a href="tel:<?php echo PHONE_PRIMARY; ?>" class="btn btn-outline-dark btn-lg">
                    <i class="ri-phone-line me-1"></i> Chiama Ora
                </a>
                <a href="<?php echo whatsapp_link('Salve, vorrei informazioni sui servizi di assistenza sistemistica e consulenza IT'); ?>" 
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