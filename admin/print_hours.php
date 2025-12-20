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
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: #333; margin: 40px; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .header h1 { margin: 0; text-transform: uppercase; letter-spacing: 2px; }
        .header p { margin: 5px 0 0; color: #666; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background-color: #f8f9fa; font-weight: bold; text-transform: uppercase; font-size: 0.85em; }
        
        .date-col { width: 120px; font-weight: bold; }
        .type-col { width: 100px; }
        .status-badge { display: inline-block; padding: 3px 8px; border-radius: 4px; font-size: 0.8em; font-weight: bold; }
        .status-closed { background-color: #000; color: #fff; }
        .status-open { border: 1px solid #333; }
        
        .seg-info { font-size: 0.9em; margin: 2px 0; }
        .notice { font-style: italic; color: #555; margin-top: 5px; font-size: 0.9em; }
        
        @media print {
            .no-print { display: none; }
            body { margin: 20px; }
            button { display: none; }
        }
    </style>
</head>
<body>

<div class="header">
    <h1>Agenda Variazioni Orari</h1>
    <p><?php echo $months_it[$month] . " " . $year; ?></p>
</div>

<div class="no-print" style="text-align: right; margin-bottom: 20px;">
    <button onclick="window.print()" style="padding: 10px 20px; cursor: pointer; background: #333; color: #fff; border: none; border-radius: 4px;">Stampa ora</button>
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
                        <?php echo $d->format('d/m/Y'); ?><br>
                        <small style="font-weight: normal;"><?php echo $dayName; ?></small>
                    </td>
                    <td class="type-col">
                        <strong><?php echo $ev['type']; ?></strong><br>
                        <?php echo $ev['name']; ?>
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
                                    <div class="seg-info">
                                        <strong><?php echo $s['seg'] == 1 ? 'Mattina:' : 'Pomeriggio:'; ?></strong>
                                        <?php if ($s['is_closed']): ?>
                                            CHIUSO
                                        <?php else: ?>
                                            <?php echo date('H:i', strtotime($s['open_time'])); ?> - <?php echo date('H:i', strtotime($s['close_time'])); ?>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        <?php endif; ?>
                    </td>
                    <td class="notice">
                        <?php if ($ev['type'] === 'Eccezione'): ?>
                            <?php echo $ev['segments'][0]['notice'] ?? ''; ?>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>

<script>
    window.onload = function() {
        // Uncomment to auto-print
        // window.print();
    }
</script>

</body>
</html>
