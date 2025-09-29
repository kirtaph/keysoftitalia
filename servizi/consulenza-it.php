<?php
require_once '../config/config.php';
require_once '../assets/php/functions.php';

$page_title = "Consulenza IT e Sistemistica - " . SITE_NAME;
$page_description = "Consulenza informatica professionale per aziende. Gestione reti, sicurezza informatica, backup e disaster recovery.";
$current_page = 'servizi';
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <?php include '../includes/head.php'; ?>
    <style>
        .service-hero {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 100px 0 50px;
            color: white;
            position: relative;
        }
        
        .breadcrumb {
            background: transparent;
            padding: 0;
            margin-bottom: 20px;
        }
        
        .breadcrumb a {
            color: rgba(255,255,255,0.8);
            text-decoration: none;
        }
        
        .breadcrumb a:hover {
            color: white;
        }
        
        .breadcrumb-item.active {
            color: white;
        }
        
        .service-card {
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            height: 100%;
        }
        
        .service-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }
        
        .service-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
        }
        
        .service-icon i {
            font-size: 2rem;
            color: white;
        }
        
        .consultation-types {
            background: #f8f9fa;
            padding: 60px 0;
        }
        
        .type-card {
            background: white;
            padding: 40px;
            border-radius: 10px;
            text-align: center;
            height: 100%;
            transition: all 0.3s ease;
        }
        
        .type-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.15);
        }
        
        .type-icon {
            font-size: 3rem;
            color: #667eea;
            margin-bottom: 20px;
        }
        
        .process-section {
            padding: 60px 0;
        }
        
        .process-step {
            text-align: center;
            padding: 30px;
        }
        
        .process-number {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            font-weight: bold;
            margin: 0 auto 20px;
        }
        
        .pricing-section {
            background: #f8f9fa;
            padding: 60px 0;
        }
        
        .pricing-card {
            background: white;
            border-radius: 10px;
            padding: 40px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            height: 100%;
            transition: all 0.3s ease;
        }
        
        .pricing-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.15);
        }
        
        .pricing-card.featured {
            border: 2px solid #667eea;
            transform: scale(1.05);
        }
        
        .price {
            font-size: 2.5rem;
            font-weight: bold;
            color: #667eea;
            margin: 20px 0;
        }
        
        .price small {
            font-size: 1rem;
            color: #666;
        }
        
        .features-list {
            list-style: none;
            padding: 0;
            margin: 30px 0;
        }
        
        .features-list li {
            padding: 10px 0;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .features-list li i {
            color: #4caf50;
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <!-- Hero Section -->
    <section class="service-hero">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo url(''); ?>">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo url('servizi.php'); ?>">Servizi</a></li>
                    <li class="breadcrumb-item active">Consulenza IT</li>
                </ol>
            </nav>
            <h1 class="display-4 fw-bold mb-4">Consulenza IT Professionale</h1>
            <p class="lead">Ottimizza l'infrastruttura IT della tua azienda con i nostri esperti</p>
        </div>
    </section>

    <!-- Services Grid -->
    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-5">I Nostri Servizi di Consulenza</h2>
            
            <div class="row g-4">
                <div class="col-md-6 col-lg-4">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="ri-server-line"></i>
                        </div>
                        <h4>Gestione Server</h4>
                        <p>Configurazione, manutenzione e ottimizzazione server fisici e virtuali</p>
                        <ul class="list-unstyled mt-3">
                            <li><i class="ri-check-line text-success"></i> Windows Server</li>
                            <li><i class="ri-check-line text-success"></i> Linux Server</li>
                            <li><i class="ri-check-line text-success"></i> Virtualizzazione</li>
                        </ul>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-4">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="ri-shield-check-line"></i>
                        </div>
                        <h4>Sicurezza Informatica</h4>
                        <p>Protezione completa della tua infrastruttura IT da minacce e attacchi</p>
                        <ul class="list-unstyled mt-3">
                            <li><i class="ri-check-line text-success"></i> Firewall</li>
                            <li><i class="ri-check-line text-success"></i> Antivirus Enterprise</li>
                            <li><i class="ri-check-line text-success"></i> Penetration Test</li>
                        </ul>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-4">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="ri-wifi-line"></i>
                        </div>
                        <h4>Reti Aziendali</h4>
                        <p>Progettazione e gestione reti LAN/WAN performanti e sicure</p>
                        <ul class="list-unstyled mt-3">
                            <li><i class="ri-check-line text-success"></i> Cablaggio strutturato</li>
                            <li><i class="ri-check-line text-success"></i> WiFi aziendale</li>
                            <li><i class="ri-check-line text-success"></i> VPN</li>
                        </ul>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-4">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="ri-database-2-line"></i>
                        </div>
                        <h4>Backup & Recovery</h4>
                        <p>Strategie di backup e disaster recovery per proteggere i tuoi dati</p>
                        <ul class="list-unstyled mt-3">
                            <li><i class="ri-check-line text-success"></i> Backup automatizzati</li>
                            <li><i class="ri-check-line text-success"></i> Cloud backup</li>
                            <li><i class="ri-check-line text-success"></i> Piano di continuità</li>
                        </ul>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-4">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="ri-cloud-line"></i>
                        </div>
                        <h4>Migrazione Cloud</h4>
                        <p>Accompagniamo la tua azienda nel passaggio al cloud</p>
                        <ul class="list-unstyled mt-3">
                            <li><i class="ri-check-line text-success"></i> Microsoft 365</li>
                            <li><i class="ri-check-line text-success"></i> Google Workspace</li>
                            <li><i class="ri-check-line text-success"></i> AWS/Azure</li>
                        </ul>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-4">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="ri-file-shield-2-line"></i>
                        </div>
                        <h4>GDPR Compliance</h4>
                        <p>Adeguamento normativo e protezione dati personali</p>
                        <ul class="list-unstyled mt-3">
                            <li><i class="ri-check-line text-success"></i> Audit GDPR</li>
                            <li><i class="ri-check-line text-success"></i> Policy privacy</li>
                            <li><i class="ri-check-line text-success"></i> Formazione staff</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Consultation Types -->
    <section class="consultation-types">
        <div class="container">
            <h2 class="text-center mb-5">Modalità di Consulenza</h2>
            
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="type-card">
                        <i class="ri-building-line type-icon"></i>
                        <h4>On-Site</h4>
                        <p>Intervento diretto presso la tua sede per analisi e implementazioni</p>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="type-card">
                        <i class="ri-global-line type-icon"></i>
                        <h4>Remoto</h4>
                        <p>Assistenza e consulenza da remoto per risolvere problemi rapidamente</p>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="type-card">
                        <i class="ri-calendar-check-line type-icon"></i>
                        <h4>Contratto</h4>
                        <p>Assistenza continuativa con SLA garantito e supporto dedicato</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Process -->
    <section class="process-section">
        <div class="container">
            <h2 class="text-center mb-5">Come Lavoriamo</h2>
            
            <div class="row">
                <div class="col-md-3">
                    <div class="process-step">
                        <div class="process-number">1</div>
                        <h5>Analisi</h5>
                        <p>Valutiamo l'infrastruttura IT esistente e identifichiamo criticità</p>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="process-step">
                        <div class="process-number">2</div>
                        <h5>Progettazione</h5>
                        <p>Elaboriamo soluzioni personalizzate per le tue esigenze</p>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="process-step">
                        <div class="process-number">3</div>
                        <h5>Implementazione</h5>
                        <p>Mettiamo in pratica le soluzioni concordate</p>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="process-step">
                        <div class="process-number">4</div>
                        <h5>Supporto</h5>
                        <p>Forniamo assistenza continuativa e manutenzione</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing -->
    <section class="pricing-section">
        <div class="container">
            <h2 class="text-center mb-5">Piani di Assistenza</h2>
            
            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="pricing-card">
                        <h3>Base</h3>
                        <div class="price">
                            €99<small>/mese</small>
                        </div>
                        <p class="text-muted">Per piccole aziende</p>
                        <ul class="features-list">
                            <li><i class="ri-check-line"></i> Fino a 5 postazioni</li>
                            <li><i class="ri-check-line"></i> Assistenza remota</li>
                            <li><i class="ri-check-line"></i> Tempo risposta 24h</li>
                            <li><i class="ri-check-line"></i> Report mensile</li>
                        </ul>
                        <button class="btn btn-outline-primary w-100">Scegli Base</button>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <div class="pricing-card featured">
                        <h3>Professional</h3>
                        <div class="price">
                            €299<small>/mese</small>
                        </div>
                        <p class="text-muted">Soluzione completa</p>
                        <ul class="features-list">
                            <li><i class="ri-check-line"></i> Fino a 20 postazioni</li>
                            <li><i class="ri-check-line"></i> Assistenza on-site</li>
                            <li><i class="ri-check-line"></i> Tempo risposta 8h</li>
                            <li><i class="ri-check-line"></i> Monitoraggio proattivo</li>
                            <li><i class="ri-check-line"></i> Backup automatico</li>
                        </ul>
                        <button class="btn btn-primary w-100">Più venduto</button>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <div class="pricing-card">
                        <h3>Enterprise</h3>
                        <div class="price">
                            Su misura
                        </div>
                        <p class="text-muted">Grandi aziende</p>
                        <ul class="features-list">
                            <li><i class="ri-check-line"></i> Postazioni illimitate</li>
                            <li><i class="ri-check-line"></i> Team dedicato</li>
                            <li><i class="ri-check-line"></i> SLA personalizzato</li>
                            <li><i class="ri-check-line"></i> Supporto 24/7</li>
                            <li><i class="ri-check-line"></i> Disaster recovery</li>
                        </ul>
                        <button class="btn btn-outline-primary w-100">Contattaci</button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="py-5 text-center bg-light">
        <div class="container">
            <h2 class="mb-4">Ottimizza la Tua Infrastruttura IT</h2>
            <p class="lead mb-4">Richiedi una consulenza gratuita per valutare le tue esigenze</p>
            <div class="d-flex gap-3 justify-content-center flex-wrap">
                <a href="<?php echo url('preventivo.php'); ?>" class="btn btn-primary btn-lg">
                    <i class="ri-file-list-3-line"></i> Richiedi Consulenza
                </a>
                <a href="<?php echo whatsapp_link('Salve, vorrei informazioni sui servizi di consulenza IT'); ?>" 
                   class="btn btn-success btn-lg" target="_blank">
                    <i class="ri-whatsapp-line"></i> WhatsApp
                </a>
            </div>
        </div>
    </section>

    <?php include '../includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>