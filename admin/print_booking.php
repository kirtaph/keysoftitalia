<?php
require_once '../config/config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$id = $_GET['id'] ?? null;
if (!$id) {
    die('ID Prenotazione mancante');
}

$stmt = $pdo->prepare("SELECT * FROM repair_bookings WHERE id = ?");
$stmt->execute([$id]);
$booking = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$booking) {
    die('Prenotazione non trovata');
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scheda Riparazione #<?php echo $booking['id']; ?></title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace; /* Monospace for ticket feel */
            width: 80mm; /* Standard receipt width or small sheet */
            margin: 0 auto;
            padding: 10px;
            color: #000;
        }
        .header {
            text-align: center;
            border-bottom: 2px dashed #000;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }
        .logo {
            font-weight: bold;
            font-size: 1.2em;
        }
        .info-row {
            margin-bottom: 5px;
            display: flex;
            justify-content: space-between;
        }
        .label {
            font-weight: bold;
        }
        .section {
            border-bottom: 1px dashed #000;
            padding: 10px 0;
        }
        .barcode {
            text-align: center;
            margin: 20px 0;
            font-size: 2em;
            letter-spacing: 5px;
        }
        .footer {
            text-align: center;
            font-size: 0.8em;
            margin-top: 20px;
        }
        @media print {
            body { margin: 0; }
            button { display: none; }
        }
    </style>
</head>
<body>
    <button onclick="window.print()" style="width:100%; padding: 10px; margin-bottom: 20px; cursor: pointer;">STAMPA SCHEDA</button>

    <div class="header">
        <div class="logo">KEY SOFT ITALIA</div>
        <div>Scheda Riparazione</div>
        <div><?php echo date('d/m/Y H:i'); ?></div>
    </div>

    <div class="barcode">
        *<?php echo str_pad($booking['id'], 6, '0', STR_PAD_LEFT); ?>*
    </div>

    <div class="section">
        <div class="info-row">
            <span class="label">ID:</span>
            <span>#<?php echo $booking['id']; ?></span>
        </div>
        <div class="info-row">
            <span class="label">Data:</span>
            <span><?php echo date('d/m/Y', strtotime($booking['created_at'])); ?></span>
        </div>
    </div>

    <div class="section">
        <div class="label" style="margin-bottom: 5px;">CLIENTE:</div>
        <div><?php echo htmlspecialchars($booking['customer_first_name'] . ' ' . $booking['customer_last_name']); ?></div>
        <div><?php echo htmlspecialchars($booking['customer_phone']); ?></div>
        <div><?php echo htmlspecialchars($booking['customer_email']); ?></div>
    </div>

    <div class="section">
        <div class="label" style="margin-bottom: 5px;">DISPOSITIVO:</div>
        <div><?php echo htmlspecialchars($booking['device_type']); ?></div>
        <div><?php echo htmlspecialchars($booking['brand_name'] . ' ' . $booking['model_name']); ?></div>
    </div>

    <div class="section">
        <div class="label" style="margin-bottom: 5px;">PROBLEMA:</div>
        <div><?php echo htmlspecialchars($booking['problem_summary']); ?></div>
    </div>

    <div class="section">
        <div class="info-row">
            <span class="label">Backup:</span>
            <span><?php echo $booking['backup_done'] ? 'SI' : 'NO'; ?></span>
        </div>
        <div class="info-row">
            <span class="label">Test Iniziali:</span>
            <span><?php echo $booking['tests_ok'] ? 'OK' : 'NO'; ?></span>
        </div>
    </div>

    <div class="section">
        <div class="label" style="margin-bottom: 5px;">NOTE:</div>
        <div style="min-height: 50px; border: 1px solid #ccc;"></div>
    </div>

    <div class="footer">
        Grazie per averci scelto!<br>
        www.keysoftitalia.it
    </div>

    <script>
        // Auto-print on load
        // window.onload = function() { window.print(); }
    </script>
</body>
</html>
