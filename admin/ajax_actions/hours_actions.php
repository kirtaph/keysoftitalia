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
            
            $stmt = $pdo->prepare("SELECT * FROM ks_store_hours_exceptions WHERE date BETWEEN ? AND ?");
            $stmt->execute([$start, $end]);
            $exceptions = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Format for FullCalendar
            $events = [];
            foreach ($exceptions as $ex) {
                $color = $ex['is_closed'] ? '#dc3545' : '#198754'; // Red if closed, Green if open
                $title = $ex['is_closed'] ? 'CHIUSO' : 'APERTO';
                if ($ex['notice']) $title .= ' - ' . $ex['notice'];
                
                $events[] = [
                    'id' => $ex['id'],
                    'title' => $title,
                    'start' => $ex['date'],
                    'allDay' => true,
                    'backgroundColor' => $color,
                    'borderColor' => $color,
                    'extendedProps' => $ex
                ];
            }
            
            echo json_encode($events); // FullCalendar expects direct array
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
