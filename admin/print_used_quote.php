<?php
require_once '../config/config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$id = $_GET['id'] ?? null;
if (!$id) {
    die('ID Valutazione mancante');
}

$stmt = $pdo->prepare("SELECT * FROM used_device_quotes WHERE id = ?");
$stmt->execute([$id]);
$quote = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$quote) {
    die('Valutazione non trovata');
}

$defects = json_decode($quote['defects'] ?? '[]', true);
$accessories = json_decode($quote['accessories'] ?? '[]', true);

// Allow overriding price via URL for "what-if" scenarios
$displayPrice = isset($_GET['price']) && $_GET['price'] !== '' ? floatval($_GET['price']) : $quote['expected_price'];
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Valutazione #<?php echo $quote['id']; ?></title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            color: #333;
            line-height: 1.6;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #28a745;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #28a745;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .meta-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            font-size: 0.9em;
            color: #666;
        }
        .section-title {
            font-size: 18px;
            font-weight: bold;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
            margin-bottom: 15px;
            margin-top: 30px;
            color: #28a745;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        .info-item {
            margin-bottom: 10px;
        }
        .label {
            font-weight: bold;
            display: block;
            font-size: 0.85em;
            color: #888;
            text-transform: uppercase;
        }
        .value {
            font-size: 1.1em;
        }
        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 0.9em;
            margin-right: 5px;
        }
        .badge-danger { background: #fee; color: #c00; border: 1px solid #fcc; }
        .badge-success { background: #efe; color: #080; border: 1px solid #cfc; }
        
        .price-box {
            background: #f8f9fa;
            border: 2px solid #e9ecef;
            padding: 20px;
            text-align: center;
            margin-top: 40px;
            border-radius: 8px;
        }
        .price-label {
            font-size: 1.2em;
            color: #666;
        }
        .price-value {
            font-size: 2.5em;
            font-weight: bold;
            color: #28a745;
            margin: 10px 0;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 0.8em;
            color: #999;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }
        .btn-print {
            display: block;
            width: 100%;
            padding: 15px;
            background: #28a745;
            color: white;
            border: none;
            font-size: 16px;
            cursor: pointer;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .btn-print:hover {
            background: #218838;
        }
        @media print {
            .btn-print { display: none; }
            body { padding: 0; }
        }
    </style>
</head>
<body>
    <button class="btn-print" onclick="window.print()">STAMPA VALUTAZIONE</button>

    <div class="header">
        <div class="logo">KEY SOFT ITALIA</div>
        <div>Scheda Valutazione Usato</div>
    </div>

    <div class="meta-info">
        <div>
            <strong>Data:</strong> <?php echo date('d/m/Y', strtotime($quote['created_at'])); ?>
        </div>
        <div>
            <strong>ID Valutazione:</strong> #<?php echo str_pad($quote['id'], 6, '0', STR_PAD_LEFT); ?>
        </div>
    </div>

    <div class="info-grid">
        <div>
            <div class="section-title">Cliente</div>
            <div class="info-item">
                <span class="label">Nome Completo</span>
                <span class="value"><?php echo htmlspecialchars($quote['customer_first_name'] . ' ' . $quote['customer_last_name']); ?></span>
            </div>
            <div class="info-item">
                <span class="label">Email</span>
                <span class="value"><?php echo htmlspecialchars($quote['customer_email']); ?></span>
            </div>
            <div class="info-item">
                <span class="label">Telefono</span>
                <span class="value"><?php echo htmlspecialchars($quote['customer_phone']); ?></span>
            </div>
        </div>

        <div>
            <div class="section-title">Dispositivo</div>
            <div class="info-item">
                <span class="label">Tipo</span>
                <span class="value"><?php echo htmlspecialchars($quote['device_type']); ?></span>
            </div>
            <div class="info-item">
                <span class="label">Marca e Modello</span>
                <span class="value"><?php echo htmlspecialchars($quote['brand_name'] . ' ' . $quote['model_name']); ?></span>
            </div>
            <div class="info-item">
                <span class="label">Condizione Estetica</span>
                <span class="value"><?php echo htmlspecialchars($quote['device_condition']); ?></span>
            </div>
        </div>
    </div>

    <div class="section-title">Dettagli Tecnici</div>
    <div class="info-item">
        <span class="label">Difetti Segnalati</span>
        <div class="value">
            <?php 
            if ($defects) {
                foreach ($defects as $d) {
                    echo '<span class="badge badge-danger">' . htmlspecialchars($d) . '</span>';
                }
            } else {
                echo 'Nessun difetto segnalato';
            }
            ?>
        </div>
    </div>
    <div class="info-item" style="margin-top: 15px;">
        <span class="label">Accessori Inclusi</span>
        <div class="value">
            <?php 
            if ($accessories) {
                foreach ($accessories as $a) {
                    echo '<span class="badge badge-success">' . htmlspecialchars($a) . '</span>';
                }
            } else {
                echo 'Nessun accessorio';
            }
            ?>
        </div>
    </div>

    <?php if (!empty($quote['notes'])): ?>
    <div class="section-title">Note Interne</div>
    <p><?php echo nl2br(htmlspecialchars($quote['notes'])); ?></p>
    <?php endif; ?>

    <div class="price-box">
        <div class="price-label">Valutazione Offerta</div>
        <div class="price-value">
            <?php
            if ($displayPrice > 0) {
                echo '€ ' . number_format($displayPrice, 2, ',', '.');
            } else {
                echo 'Da valutare';
            }
            ?>
        </div>
        <p style="font-size: 0.9em; color: #666;">
            *L'offerta è valida per 7 giorni e soggetta a verifica tecnica del dispositivo.
        </p>
    </div>

    <div class="footer">
        <p>Key Soft Italia - Via Example 123, Roma - Tel: 06 12345678 - info@keysoftitalia.it</p>
        <p>Documento generato il <?php echo date('d/m/Y H:i'); ?></p>
    </div>
</body>
</html>
