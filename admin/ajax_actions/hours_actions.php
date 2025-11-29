<?php
include_once '../../config/config.php';

header('Content-Type: application/json');

$action = $_REQUEST['action'] ?? null;

try {
    switch ($action) {
        // --- WEEKLY HOURS ---
        case 'get_weekly':
            $stmt = $pdo->query("SELECT * FROM ks_store_hours_weekly ORDER BY dow ASC, seg ASC");
            $hours = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode(['status' => 'success', 'data' => $hours]);
            break;

        case 'save_weekly':
            $hours = $_POST['hours'] ?? [];
            if (empty($hours)) throw new Exception('Nessun dato ricevuto.');

            $pdo->beginTransaction();
            try {
                $stmt = $pdo->prepare("UPDATE ks_store_hours_weekly SET open_time = ?, close_time = ?, active = ? WHERE id = ?");
                foreach ($hours as $h) {
                    $stmt->execute([
                        $h['open_time'],
                        $h['close_time'],
                        $h['active'],
                        $h['id']
                    ]);
                }
                $pdo->commit();
                echo json_encode(['status' => 'success', 'message' => 'Orari settimanali aggiornati.']);
            } catch (Exception $e) {
                $pdo->rollBack();
                throw $e;
            }
            break;

        // --- EXCEPTIONS (CALENDAR) ---
        case 'get_exceptions':
            $start = $_GET['start'] ?? date('Y-m-01');
            $end = $_GET['end'] ?? date('Y-m-t');
            
            // 1. Fetch Exceptions
            $stmt = $pdo->prepare("SELECT * FROM ks_store_hours_exceptions WHERE date BETWEEN ? AND ?");
            $stmt->execute([$start, $end]);
            $exceptions = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $events = [];
            
            // Process Exceptions
            foreach ($exceptions as $ex) {
                $color = $ex['is_closed'] ? '#dc3545' : '#198754'; // Red/Green
                $title = $ex['is_closed'] ? 'CHIUSO' : 'APERTO';
                if ($ex['notice']) $title .= ' - ' . $ex['notice'];
                
                $events[] = [
                    'id' => $ex['id'],
                    'title' => $title,
                    'start' => $ex['date'],
                    'allDay' => true,
                    'backgroundColor' => $color,
                    'borderColor' => $color,
                    'extendedProps' => array_merge($ex, ['type' => 'exception'])
                ];
            }

            // 2. Fetch Holidays
            $stmt = $pdo->query("SELECT * FROM ks_store_holidays WHERE active = 1");
            $holidays = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Calculate Holidays for the requested range (years)
            $startYear = date('Y', strtotime($start));
            $endYear = date('Y', strtotime($end));

            for ($year = $startYear; $year <= $endYear; $year++) {
                foreach ($holidays as $h) {
                    $date = null;
                    if ($h['rule_type'] === 'fixed') {
                        $date = sprintf('%04d-%02d-%02d', $year, $h['month'], $h['day']);
                    } elseif ($h['rule_type'] === 'easter') {
                        $easterDate = date('Y-m-d', easter_date($year));
                        $date = date('Y-m-d', strtotime("$easterDate " . ($h['offset_days'] >= 0 ? '+' : '') . $h['offset_days'] . " days"));
                    }

                    // Check if date is within range
                    if ($date && $date >= $start && $date <= $end) {
                        $color = '#6f42c1'; // Purple for holidays
                        $title = $h['name'];
                        if ($h['is_closed']) $title .= ' (Chiuso)';
                        
                        $events[] = [
                            'id' => 'hol_' . $h['id'] . '_' . $year, // Unique ID for calendar
                            'title' => $title,
                            'start' => $date,
                            'allDay' => true,
                            'backgroundColor' => $color,
                            'borderColor' => $color,
                            'extendedProps' => array_merge($h, ['type' => 'holiday', 'real_id' => $h['id']])
                        ];
                    }
                }
            }
            
            echo json_encode($events);
            break;

        case 'save_exception':
            $id = $_POST['id'] ?? null;
            $date = $_POST['date'];
            $is_closed = $_POST['is_closed'] ?? 0;
            $open_time = $_POST['open_time'] ?? null;
            $close_time = $_POST['close_time'] ?? null;
            $notice = $_POST['notice'] ?? null;

            if ($id) {
                $stmt = $pdo->prepare("UPDATE ks_store_hours_exceptions SET date=?, is_closed=?, open_time=?, close_time=?, notice=? WHERE id=?");
                $stmt->execute([$date, $is_closed, $open_time, $close_time, $notice, $id]);
            } else {
                $stmt = $pdo->prepare("INSERT INTO ks_store_hours_exceptions (date, is_closed, open_time, close_time, notice) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$date, $is_closed, $open_time, $close_time, $notice]);
            }
            echo json_encode(['status' => 'success', 'message' => 'Eccezione salvata.']);
            break;

        case 'delete_exception':
            $id = $_POST['id'] ?? null;
            if (!$id) throw new Exception('ID mancante.');
            
            $stmt = $pdo->prepare("DELETE FROM ks_store_hours_exceptions WHERE id=?");
            $stmt->execute([$id]);
            echo json_encode(['status' => 'success', 'message' => 'Eccezione rimossa.']);
            break;

        // --- HOLIDAYS ---
        case 'get_holidays':
            $stmt = $pdo->query("SELECT * FROM ks_store_holidays ORDER BY month ASC, day ASC");
            $holidays = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode(['status' => 'success', 'data' => $holidays]);
            break;

        case 'save_holiday':
            $id = $_POST['id'] ?? null;
            $name = $_POST['name'];
            $rule_type = $_POST['rule_type']; // 'fixed' or 'easter'
            $is_closed = $_POST['is_closed'] ?? 1;
            $active = $_POST['active'] ?? 1;
            
            // Fixed date params
            $month = $_POST['month'] ?? null;
            $day = $_POST['day'] ?? null;
            
            // Easter params
            $offset_days = $_POST['offset_days'] ?? 0;

            if ($id) {
                $stmt = $pdo->prepare("UPDATE ks_store_holidays SET name=?, rule_type=?, month=?, day=?, offset_days=?, is_closed=?, active=? WHERE id=?");
                $stmt->execute([$name, $rule_type, $month, $day, $offset_days, $is_closed, $active, $id]);
            } else {
                $stmt = $pdo->prepare("INSERT INTO ks_store_holidays (name, rule_type, month, day, offset_days, is_closed, active) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$name, $rule_type, $month, $day, $offset_days, $is_closed, $active]);
            }
            echo json_encode(['status' => 'success', 'message' => 'Festività salvata.']);
            break;

        case 'delete_holiday':
            $id = $_POST['id'] ?? null;
            if (!$id) throw new Exception('ID mancante.');
            
            $stmt = $pdo->prepare("DELETE FROM ks_store_holidays WHERE id=?");
            $stmt->execute([$id]);
            echo json_encode(['status' => 'success', 'message' => 'Festività rimossa.']);
            break;

        default:
            throw new Exception('Azione non valida.');
    }
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
