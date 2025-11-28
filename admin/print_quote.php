<?php
require_once '../config/config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$id = $_GET['id'] ?? null;
if (!$id) {
    die('ID Preventivo mancante');
}

$stmt = $pdo->prepare("SELECT q.*, d.name as device_name FROM quotes q JOIN devices d ON q.device_id = d.id WHERE q.id = ?");
$stmt->execute([$id]);
$quote = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$quote) {
    die('Preventivo non trovato');
}

$problems = json_decode($quote['problems_json'], true);
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preventivo #<?php echo $quote['id']; ?></title>
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
            border-bottom: 2px solid #007bff;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #007bff;
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
            color: #007bff;
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
            background: #007bff;
            color: white;
            border: none;
            font-size: 16px;
            cursor: pointer;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .btn-print:hover {
            background: #0056b3;
        }
        @media print {
            .btn-print { display: none; }
            body { padding: 0; }
        }
    </style>
</head>
<body>
    <button class="btn-print" onclick="window.print()">STAMPA PREVENTIVO</button>

    <div class="header">
        <div class="logo">KEY SOFT ITALIA</div>
        <div>Preventivo di Riparazione</div>
    </div>

    <div class="meta-info">
        <div>
            <strong>Data:</strong> <?php echo date('d/m/Y', strtotime($quote['created_at'])); ?>
        </div>
        <div>
            <strong>ID Preventivo:</strong> #<?php echo str_pad($quote['id'], 6, '0', STR_PAD_LEFT); ?>
        </div>
    </div>

    <div class="info-grid">
        <div>
            <div class="section-title">Cliente</div>
            <div class="info-item">
                <span class="label">Nome Completo</span>
                <span class="value"><?php echo htmlspecialchars($quote['first_name'] . ' ' . $quote['last_name']); ?></span>
            </div>
            <div class="info-item">
                <span class="label">Email</span>
                <span class="value"><?php echo htmlspecialchars($quote['email']); ?></span>
            </div>
            <div class="info-item">
                <span class="label">Telefono</span>
                <span class="value"><?php echo htmlspecialchars($quote['phone']); ?></span>
            </div>
            <?php if (!empty($quote['company'])): ?>
            <div class="info-item">
                <span class="label">Azienda</span>
                <span class="value"><?php echo htmlspecialchars($quote['company']); ?></span>
            </div>
            <?php endif; ?>
        </div>

        <div>
            <div class="section-title">Dispositivo</div>
            <div class="info-item">
                <span class="label">Tipo</span>
                <span class="value"><?php echo htmlspecialchars($quote['device_name']); ?></span>
            </div>
            <div class="info-item">
                <span class="label">Marca e Modello</span>
                <span class="value"><?php echo htmlspecialchars($quote['brand_text'] . ' ' . $quote['model_text']); ?></span>
            </div>
            <div class="info-item">
                <span class="label">Problemi Segnalati</span>
                <div class="value">
                    <?php 
                    if ($problems) {
                        foreach ($problems as $p) {
                            echo '• ' . htmlspecialchars($p) . '<br>';
                        }
                    } else {
                        echo '-';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <?php if (!empty($quote['description'])): ?>
    <div class="section-title">Descrizione del Problema</div>
    <p><?php echo nl2br(htmlspecialchars($quote['description'])); ?></p>
    <?php endif; ?>

    <div class="price-box">
        <div class="price-label">Stima Costi di Riparazione</div>
        <div class="price-value">
            <?php
            $min = $_GET['min'] ?? $quote['est_min'];
            $max = $_GET['max'] ?? $quote['est_max'];

            if ($min && $max) {
                echo '€ ' . number_format((float)$min, 2, ',', '.') . ' - ' . number_format((float)$max, 2, ',', '.');
            } elseif ($min) {
                echo '€ ' . number_format((float)$min, 2, ',', '.');
            } else {
                echo 'Da valutare in sede';
            }
            ?>
        </div>
        <p style="font-size: 0.9em; color: #666;">
            *Il prezzo finale potrebbe subire variazioni dopo l'analisi tecnica del dispositivo.
        </p>
    </div>

    <div class="footer">
        <p>Key Soft Italia - Via Example 123, Roma - Tel: 06 12345678 - info@keysoftitalia.it</p>
        <p>Grazie per averci contattato!</p>
    </div>
</body>
</html>
