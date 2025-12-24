<?php
/**
 * Key Soft Italia - Track Order
 * Pagina per il tracciamento degli ordini clienti
 */

require_once 'config/config.php';

// Parametri in ingresso
$order_id = isset($_GET['order']) ? trim($_GET['order']) : null;
$track_code = isset($_GET['code']) ? trim($_GET['code']) : null;

$order_data = null;
$error_message = null;

if ($order_id && $track_code) {
    try {
        $api_url = "https://keyos.keysoftitalia.it/order_track.php?order=" . urlencode($order_id) . "&code=" . urlencode($track_code);
        
        $response = null;
        
        // Tentativo con cURL (più robusto)
        if (function_exists('curl_init')) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $api_url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Spesso necessario su server locali/condivisi
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_USERAGENT, 'KeySoftItalia-Site-Bot/1.0');
            $response = curl_exec($ch);
            curl_close($ch);
        }
        
        // Fallback su file_get_contents se cURL fallisce o non è disponibile
        if ($response === false || $response === null) {
            $opts = [
                "http" => [
                    "method" => "GET",
                    "header" => "User-Agent: KeySoftItalia-Site-Bot/1.0\r\n",
                    "ignore_errors" => true,
                    "timeout" => 10
                ],
                "ssl" => [
                    "verify_peer" => false,
                    "verify_peer_name" => false,
                ]
            ];
            $context = stream_context_create($opts);
            $response = @file_get_contents($api_url, false, $context);
        }
        
        if ($response === false || empty($response)) {
            $error_message = "Impossibile collegarsi al sistema di tracciamento. Riprova più tardi.";
        } else {
            $data = json_decode($response, true);
            
            if (isset($data['success']) && $data['success'] && isset($data['data'])) {
                $order_data = $data['data'];
            } else {
                $error_message = "Ordine non trovato o codice non valido. Verifica i dati inseriti.";
                // Log per debug interno
                if (defined('DEBUG_MODE') && DEBUG_MODE) {
                    error_log("Order Tracking API response error: " . $response);
                }
            }
        }
    } catch (Throwable $e) {
        $error_message = "Si è verificato un errore durante il recupero dei dati.";
        if (defined('DEBUG_MODE') && DEBUG_MODE) {
            error_log('Track order error: ' . $e->getMessage());
        }
    }
}

// SEO Meta
$page_title = "Traccia il tuo Ordine" . (defined('SEO_TITLE_SUFFIX') ? SEO_TITLE_SUFFIX : " | Key Soft Italia");
$page_description = "Segui lo stato di avanzamento del tuo ordine su Key Soft Italia.";
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <?php include 'includes/head.php'; ?>
    <title><?php echo $page_title; ?></title>
    <meta name="description" content="<?php echo $page_description; ?>">
    <link rel="stylesheet" href="<?php echo asset('css/pages/track-order.css'); ?>">
</head>
<body data-aos-easing="ease-in-out" data-aos-duration="800" data-aos-once="true">

    <?php include 'includes/header.php'; ?>

    <main>
        <!-- HERO SECONDARY (Site Standard) -->
        <section class="hero hero-secondary text-center">
            <div class="hero-pattern"></div>
            <div class="container position-relative z-2" data-aos="fade-up">
                <div class="hero-icon mb-3" data-aos="zoom-in">
                    <i class="ri-radar-line"></i>
                </div>
                <h1 class="hero-title text-white">
                    Traccia il tuo <span class="text-gradient">Ordine</span>
                </h1>
                <p class="hero-subtitle">
                    Inserisci i tuoi dati per conoscere lo stato di avanzamento della tua riparazione o del tuo acquisto in tempo reale.
                </p>
                <div class="hero-breadcrumb mt-4">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb justify-content-center">
                            <li class="breadcrumb-item"><a href="<?php echo url(); ?>">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Traccia Ordine</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </section>

        <!-- CONTENT -->
        <section class="section-track-content">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-10">
                        
                        <!-- SEARCH FORM (Only if no order found or error) -->
                        <?php if (!$order_data): ?>
                            <div class="order-card mb-5" data-aos="fade-up">
                                <div class="order-card-body p-4 p-md-5">
                                    <?php if ($error_message): ?>
                                        <div class="alert alert-danger d-flex align-items-center mb-4 border-0 shadow-sm rounded-4" role="alert">
                                            <i class="ri-error-warning-line fs-4 me-3"></i>
                                            <div><?php echo $error_message; ?></div>
                                        </div>
                                    <?php endif; ?>

                                    <div class="text-center mb-5">
                                        <h3 class="fw-bold section-title">Cerca il tuo ordine</h3>
                                        <p class="text-muted">Recupera i dati dal modulo che ti abbiamo consegnato</p>
                                    </div>

                                    <form action="track_order.php" method="GET" class="row g-4 justify-content-center">
                                        <div class="col-md-5">
                                            <label class="form-label fw-bold small text-uppercase text-muted">Numero Ordine</label>
                                            <div class="input-group input-group-lg">
                                                <span class="input-group-text bg-light border-0"><i class="ri-hashtag text-accent"></i></span>
                                                <input type="text" name="order" class="form-control bg-light border-0 shadow-none" placeholder="Es. 18" value="<?php echo htmlspecialchars($order_id ?? ''); ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <label class="form-label fw-bold small text-uppercase text-muted">Codice di Tracciamento</label>
                                            <div class="input-group input-group-lg">
                                                <span class="input-group-text bg-light border-0"><i class="ri-fingerprint-line text-accent"></i></span>
                                                <input type="text" name="code" class="form-control bg-light border-0 shadow-none" placeholder="Codice segreto" value="<?php echo htmlspecialchars($track_code ?? ''); ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-md-10 mt-5">
                                            <button type="submit" class="btn btn-primary w-100 btn-track py-3 fs-5 shadow-sm">
                                                <i class="ri-search-eye-line"></i> Traccia Ordine Ora
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- ORDER DETAILS (If order found) -->
                        <?php if ($order_data): ?>
                            <div class="order-card" data-aos="fade-up">
                                <div class="order-card-header">
                                    <div class="row align-items-center">
                                        <div class="col-md-7">
                                            <span class="badge badge-accent mb-2 px-3 py-2 rounded-pill fw-bold">ORDINE #<?php echo $order_data['id']; ?></span>
                                            <h2 class="fw-bold mb-0 text-dark"><?php echo htmlspecialchars($order_data['descrizione']); ?></h2>
                                        </div>
                                        <div class="col-md-5 text-md-end mt-3 mt-md-0">
                                            <div class="detail-label">Stato Attuale</div>
                                            <span class="status-badge status-badge-<?php echo str_replace(' ', '-', strtolower($order_data['stato']['nome'])); ?>">
                                                <?php echo htmlspecialchars($order_data['stato']['nome']); ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="order-card-body">
                                    <!-- TIMELINE -->
                                    <div class="status-timeline">
                                        <?php 
                                        $steps = [
                                            'Nuovo' => ['icon' => 'ri-file-list-3-line', 'label' => 'Ricevuto'],
                                            'In Lavorazione' => ['icon' => 'ri-settings-4-line', 'label' => 'In Corso'],
                                            'In Attesa' => ['icon' => 'ri-pause-circle-line', 'label' => 'In Sospeso'],
                                            'Pronto' => ['icon' => 'ri-checkbox-circle-line', 'label' => 'Completato'],
                                            'Ritirato' => ['icon' => 'ri-hand-coin-line', 'label' => 'Consegnato']
                                        ];
                                        
                                        $current_status = $order_data['stato']['nome'];
                                        $found_current = false;
                                        foreach ($steps as $name => $step): 
                                            $is_completed = !$found_current;
                                            $is_active = ($name === $current_status);
                                            if ($is_active) $found_current = true;
                                            
                                            $class = $is_active ? 'active' : ($is_completed ? 'completed' : '');
                                        ?>
                                            <div class="timeline-step <?php echo $class; ?>">
                                                <div class="step-icon">
                                                    <i class="<?php echo $step['icon']; ?>"></i>
                                                </div>
                                                <div class="step-label"><?php echo $step['label']; ?></div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>

                                    <div class="row g-5">
                                        <!-- LEFT COL: INFO -->
                                        <div class="col-lg-7">
                                            <h5 class="fw-bold mb-4 d-flex align-items-center">
                                                <i class="ri-user-settings-line text-accent me-2 fs-4"></i> Informazioni Ordine
                                            </h5>
                                            
                                            <div class="row g-4">
                                                <div class="col-sm-6">
                                                    <div class="detail-box">
                                                        <div class="detail-label">Cliente</div>
                                                        <div class="detail-value text-accent"><?php echo htmlspecialchars($order_data['cliente']); ?></div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="detail-box">
                                                        <div class="detail-label">Reparto</div>
                                                        <div class="detail-value">
                                                            <span class="text-<?php echo $order_data['reparto']['colore']; ?>">
                                                                <i class="ri-checkbox-blank-circle-fill fs-xs me-1"></i>
                                                                <?php echo htmlspecialchars($order_data['reparto']['nome']); ?>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="detail-box">
                                                        <div class="detail-label">Data Inserimento</div>
                                                        <div class="detail-value"><?php echo date('d/m/Y', strtotime($order_data['data_ordine'])); ?></div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="detail-box">
                                                        <div class="detail-label">Ultimo Update</div>
                                                        <div class="detail-value"><?php echo date('d/m/Y H:i', strtotime($order_data['ultimo_aggiornamento'])); ?></div>
                                                    </div>
                                                </div>
                                                
                                                <?php if (!empty($order_data['data_prevista'])): ?>
                                                    <div class="col-12">
                                                        <div class="delivery-expected-box text-center">
                                                            <div class="detail-label text-accent">Data Prevista Consegna</div>
                                                            <div class="detail-value text-accent h3 fw-800 mb-0 mt-2">
                                                                <i class="ri-calendar-event-line me-2"></i>
                                                                <?php echo date('d/m/Y', strtotime($order_data['data_prevista'])); ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>

                                        <!-- RIGHT COL: PRICING & CTA -->
                                        <div class="col-lg-5">
                                            <div class="price-card mb-4 shadow-sm">
                                                <h5 class="fw-bold mb-4 d-flex align-items-center">
                                                    <i class="ri-bank-card-line text-accent me-2 fs-4"></i> Riepilogo Economico
                                                </h5>
                                                
                                                <div class="price-row">
                                                    <span class="text-muted">Importo Totale</span>
                                                    <span class="fw-bold"><?php echo format_price($order_data['prezzo_totale']); ?></span>
                                                </div>
                                                <div class="price-row text-success">
                                                    <span class="text-muted">Acconto Pagato</span>
                                                    <span class="fw-bold">- <?php echo format_price($order_data['totale_pagato']); ?></span>
                                                </div>
                                                <div class="price-row total <?php echo ($order_data['saldo'] > 0) ? 'text-danger' : 'text-success'; ?>">
                                                    <span>Saldo Residuo</span>
                                                    <span><?php echo format_price($order_data['saldo']); ?></span>
                                                </div>

                                                <div class="action-buttons mt-4">
                                                    <a href="<?php echo whatsapp_link("Ciao! Vorrei un aggiornamento sull'ordine #" . $order_data['id'] . " (" . $order_data['descrizione'] . ")"); ?>" 
                                                       class="btn btn-success btn-track" target="_blank">
                                                        <i class="ri-whatsapp-line"></i> Assistenza WhatsApp
                                                    </a>
                                                    <button onclick="window.print()" class="btn btn-outline-secondary btn-track">
                                                        <i class="ri-printer-line"></i> Stampa Scheda
                                                    </button>
                                                </div>
                                            </div>

                                            <?php if ($order_data['stato']['nome'] === 'Pronto'): ?>
                                                <div class="alert alert-success border-0 shadow-sm rounded-4 p-4 d-flex align-items-center" data-aos="shake">
                                                    <div class="bg-white rounded-circle p-2 me-3 shadow-sm">
                                                        <i class="ri-notification-3-fill text-success fs-4"></i>
                                                    </div>
                                                    <div>
                                                        <h6 class="fw-bold mb-1">Il tuo dispositivo è pronto!</h6>
                                                        <p class="small mb-0">Vienici a trovare in sede per il ritiro.</p>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4 text-center">
                                <a href="track_order.php" class="btn btn-link text-muted text-decoration-none">
                                    <i class="ri-arrow-left-line me-1"></i> Controlla un altro ordine
                                </a>
                            </div>
                        <?php endif; ?>

                        <div class="mt-5 text-center" data-aos="fade-up">
                            <div class="p-4 bg-light rounded-4 d-inline-block border border-white shadow-sm">
                                <p class="text-muted mb-0">
                                    Problemi con il tracciamento? 
                                    <a href="<?php echo url('contatti.php'); ?>" class="text-accent fw-bold text-decoration-none ms-1">
                                        Contattaci <i class="ri-external-link-line ms-1 small"></i>
                                    </a>
                                </p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </section>
    </main>

    <?php include 'includes/footer.php'; ?>
    
    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof AOS !== 'undefined') {
                AOS.init({
                    duration: 800,
                    once: true,
                    easing: 'ease-in-out'
                });
            }
        });
    </script>
</body>
</html>
