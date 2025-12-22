<?php
include_once '../config/config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    die('Accesso negato.');
}

$month = isset($_GET['month']) ? (int)$_GET['month'] : (int)date('m');
$year = isset($_GET['year']) ? (int)$_GET['year'] : (int)date('Y');

$start = date("$year-$month-01");
$end = date("$year-$month-t");

// 1. Fetch Exceptions
$stmt = $pdo->prepare("SELECT * FROM ks_store_hours_exceptions WHERE date BETWEEN ? AND ? ORDER BY date ASC, seg ASC");
$stmt->execute([$start, $end]);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

$grouped = [];
foreach ($rows as $row) {
    $grouped[$row['date']][] = $row;
}

// 2. Fetch Holidays
$stmt = $pdo->query("SELECT * FROM ks_store_holidays WHERE active = 1");
$holidays = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 3. Merge Exceptions and Holidays into a single keyed array to prevent duplicates
$merged = [];

// First pass: Add exceptions (they take precedence for times)
foreach ($grouped as $date => $segments) {
    $merged[$date] = [
        'date' => $date,
        'type' => 'Eccezione',
        'segments' => $segments,
        'name' => $segments[0]['notice'] ?? 'Variazione Orario'
    ];
}

// Second pass: Add/Update with Holidays
foreach ($holidays as $h) {
    for ($y = $year; $y <= $year; $y++) {
        $date = null;
        if ($h['rule_type'] === 'fixed') {
            $date = sprintf('%04d-%02d-%02d', $y, $h['month'], $h['day']);
        } elseif ($h['rule_type'] === 'easter') {
            $easterDate = date('Y-m-d', easter_date($y));
            $date = date('Y-m-d', strtotime("$easterDate " . ($h['offset_days'] >= 0 ? '+' : '') . $h['offset_days'] . " days"));
        }

        if ($date && $date >= $start && $date <= $end) {
            if (!isset($merged[$date])) {
                $merged[$date] = [
                    'date' => $date,
                    'type' => 'Festività',
                    'name' => $h['name'],
                    'is_closed' => $h['is_closed'],
                    'segments' => [] 
                ];
            } else {
                // If it's already an exception, try to use the holiday name if the notice is generic
                if ($merged[$date]['type'] === 'Eccezione' && (empty($merged[$date]['name']) || $merged[$date]['name'] === 'Variazione Orario')) {
                    $merged[$date]['name'] = $h['name'];
                }
            }
        }
    }
}

// Convert to indexed list and sort
$events = array_values($merged);
usort($events, function($a, $b) {
    return strcmp($a['date'], $b['date']);
});

$months_it = [1=>'Gennaio', 2=>'Febbraio', 3=>'Marzo', 4=>'Aprile', 5=>'Maggio', 6=>'Giugno', 7=>'Luglio', 8=>'Agosto', 9=>'Settembre', 10=>'Ottobre', 11=>'Novembre', 12=>'Dicembre'];
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Stampa Agenda - <?php echo $months_it[$month] . " " . $year; ?></title>
    <style>
        :root {
            --primary-color: #0d6efd;
            --secondary-color: #6c757d;
            --dark-color: #212529;
            --border-color: #dee2e6;
            --bg-light: #f8f9fa;
        }

        body { 
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; 
            color: var(--dark-color); 
            line-height: 1.5;
            margin: 0;
            padding: 40px;
        }

        .print-container {
            max-width: 1000px;
            margin: 0 auto;
        }

        /* Professional Header */
        .print-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 2px solid var(--primary-color);
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .brand-info h1 {
            margin: 0;
            font-size: 24px;
            color: var(--primary-color);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .brand-info p {
            margin: 5px 0 0;
            font-size: 13px;
            color: var(--secondary-color);
        }

        .document-title {
            text-align: right;
        }

        .document-title h2 {
            margin: 0;
            font-size: 18px;
            text-transform: uppercase;
            color: var(--dark-color);
        }

        .document-title p {
            margin: 5px 0 0;
            font-weight: bold;
            color: var(--primary-color);
        }

        /* Controls */
        .print-actions {
            text-align: right;
            margin-bottom: 20px;
        }

        .btn-print {
            padding: 10px 25px;
            cursor: pointer;
            background: var(--primary-color);
            color: #fff;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            transition: opacity 0.2s;
        }

        .btn-print:hover { opacity: 0.9; }

        /* Table Styling */
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 20px;
            background: white;
        }

        th { 
            background-color: var(--bg-light); 
            font-weight: 700; 
            text-transform: uppercase; 
            font-size: 11px; 
            color: var(--secondary-color);
            border-bottom: 2px solid var(--border-color);
            padding: 15px 12px;
            text-align: left;
        }

        td { 
            border-bottom: 1px solid var(--border-color); 
            padding: 15px 12px; 
            vertical-align: top;
        }

        .date-col { width: 140px; }
        .date-main { font-weight: 700; font-size: 15px; display: block; }
        .date-sub { font-size: 12px; color: var(--secondary-color); text-transform: capitalize; }

        .type-col { width: 160px; }
        .type-tag { 
            font-size: 10px; 
            text-transform: uppercase; 
            font-weight: 800; 
            color: var(--primary-color);
            display: block;
            margin-bottom: 4px;
        }
        .event-name { font-weight: 600; font-size: 14px; }

        .status-badge { 
            display: inline-block; 
            padding: 4px 10px; 
            border-radius: 4px; 
            font-size: 11px; 
            font-weight: 700; 
            text-transform: uppercase;
        }
        .status-closed { background-color: #000; color: #fff; }
        .status-open { background-color: #e7f1ff; color: var(--primary-color); border: 1px solid #b6d4fe; }

        .seg-row { display: flex; font-size: 13px; margin: 4px 0; }
        .seg-label { width: 85px; font-weight: 600; color: var(--secondary-color); }
        .seg-time { font-weight: 700; }

        .notice-text { 
            font-style: italic; 
            color: #555; 
            font-size: 13px; 
            max-width: 300px;
        }

        /* Print optimization */
        @media print {
            .no-print { display: none !important; }
            body { padding: 0; margin: 1cm; }
            .print-container { max-width: 100%; }
            table { page-break-inside: auto; }
            tr { page-break-inside: avoid; page-break-after: auto; }
            .status-closed { background-color: #000 !important; color: #fff !important; -webkit-print-color-adjust: exact; }
            .status-open { background-color: #e7f1ff !important; color: var(--primary-color) !important; border: 1px solid #b6d4fe !important; -webkit-print-color-adjust: exact; }
            .print-header { border-bottom-color: var(--primary-color) !important; -webkit-print-color-adjust: exact; }
        }
    </style>
</head>
<body>

<div class="print-container">
    <div class="print-header">
        <div class="brand-info">
            <h1><?php echo SITE_NAME; ?></h1>
            <p><?php echo COMPANY_FULL_ADDRESS; ?></p>
            <p>Tel: <?php echo COMPANY_PHONE; ?> | Email: <?php echo COMPANY_EMAIL; ?></p>
        </div>
        <div class="document-title">
            <h2>Agenda Variazioni Orari</h2>
            <p><?php echo $months_it[$month] . " " . $year; ?></p>
        </div>
    </div>

    <div class="print-actions no-print">
        <button onclick="window.print()" class="btn-print">STAMPA RAPPORTO</button>
    </div>

    <table>
        <thead>
            <tr>
                <th>Giorno</th>
                <th>Tipo / Nome</th>
                <th>Stato / Orari</th>
                <th>Note</th>
            </tr>
        </thead>
        <tbody>
        <?php if (empty($events)): ?>
            <tr>
                <td colspan="4" style="text-align: center; padding: 30px;">Nessuna variazione registrata per questo mese.</td>
            </tr>
        <?php else: ?>
            <?php foreach ($events as $ev): 
                $d = new DateTime($ev['date']);
                $days_it = ['Domenica', 'Lunedì', 'Martedì', 'Mercoledì', 'Giovedì', 'Venerdì', 'Sabato'];
                $dayName = $days_it[$d->format('w')];
            ?>
                <tr>
                    <td class="date-col">
                        <span class="date-main"><?php echo $d->format('d/m/Y'); ?></span>
                        <span class="date-sub"><?php echo $dayName; ?></span>
                    </td>
                    <td class="type-col">
                        <span class="type-tag"><?php echo $ev['type']; ?></span>
                        <span class="event-name"><?php echo $ev['name']; ?></span>
                    </td>
                    <td>
                        <?php if ($ev['type'] === 'Festività'): ?>
                            <?php if ($ev['is_closed']): ?>
                                <span class="status-badge status-closed">CHIUSO</span>
                            <?php else: ?>
                                <span class="status-badge status-open">APERTO (Orario Standard)</span>
                            <?php endif; ?>
                        <?php else: ?>
                            <?php 
                            $isAllClosed = true;
                            foreach ($ev['segments'] as $s) {
                                if (!$s['is_closed']) $isAllClosed = false;
                            }
                            ?>
                            
                            <?php if ($isAllClosed): ?>
                                <span class="status-badge status-closed">CHIUSO</span>
                            <?php else: ?>
                                <?php foreach ($ev['segments'] as $s): ?>
                                    <div class="seg-row">
                                        <div class="seg-label"><?php echo $s['seg'] == 1 ? 'Mattina:' : 'Pomeriggio:'; ?></div>
                                        <div class="seg-time">
                                            <?php if ($s['is_closed']): ?>
                                                CHIUSO
                                            <?php else: ?>
                                                <?php echo date('H:i', strtotime($s['open_time'])); ?> - <?php echo date('H:i', strtotime($s['close_time'])); ?>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div class="notice-text">
                            <?php if ($ev['type'] === 'Eccezione'): ?>
                                <?php echo $ev['segments'][0]['notice'] ?? ''; ?>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>

    <div class="print-footer" style="margin-top: 50px; text-align: center; font-size: 11px; color: #999; border-top: 1px solid #eee; padding-top: 20px;">
        <p>Key Soft Italia - Via Diaz, 46 - 74013 Ginosa (TA) - Tel: 099 829 3794</p>
        <p>&copy; <?php echo date('Y'); ?> Key Soft Italia. Tutti i diritti riservati. Grazie per la collaborazione!</p>
    </div>
</div>

<script>
    window.onload = function() {
        // Uncomment to auto-print
        // window.print();
    }
</script>

</body>
</html>
