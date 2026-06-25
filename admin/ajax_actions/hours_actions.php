<?php
require_once __DIR__ . '/init.php';

$action = $_REQUEST['action'] ?? null;

try {
    switch ($action) {
        case 'get_weekly':
            $stmt = $pdo->query("SELECT * FROM ks_store_hours_weekly ORDER BY dow ASC, seg ASC");
            $hours = $stmt->fetchAll(PDO::FETCH_ASSOC);
            jsonSuccess(['data' => $hours]);
            break;

        case 'save_weekly':
            $hours = $_POST['hours'] ?? [];
            if (empty($hours)) jsonError('Nessun dato ricevuto.');

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
                jsonSuccess(['message' => 'Orari settimanali aggiornati.']);
            } catch (Exception $e) {
                $pdo->rollBack();
                throw $e;
            }
            break;

        case 'get_exceptions':
            $start = $_GET['start'] ?? date('Y-m-01');
            $end = $_GET['end'] ?? date('Y-m-t');
            
            $stmt = $pdo->prepare("SELECT * FROM ks_store_hours_exceptions WHERE date BETWEEN ? AND ? ORDER BY date ASC, seg ASC");
            $stmt->execute([$start, $end]);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $grouped = [];
            foreach ($rows as $row) {
                $grouped[$row['date']][] = $row;
            }

            $events = [];
            
            foreach ($grouped as $date => $segments) {
                $titles = [];
                $isAllClosed = true;
                foreach ($segments as $s) {
                    if (!$s['is_closed']) {
                        $isAllClosed = false;
                        $titles[] = ($s['seg'] == 1 ? 'Mattina' : 'Pomeriggio') . ': ' . date('H:i', strtotime($s['open_time'])) . '-' . date('H:i', strtotime($s['close_time']));
                    }
                }
                
                $title = $isAllClosed ? 'CHIUSO' : implode(' / ', $titles);
                if (!empty($segments[0]['notice'])) {
                    $title = $segments[0]['notice'] . ($isAllClosed ? '' : ' (' . $title . ')');
                }
                
                $color = $isAllClosed ? '#dc3545' : '#198754';
                
                $events[] = [
                    'id' => 'ex_' . $date,
                    'title' => $title,
                    'start' => $date,
                    'allDay' => true,
                    'backgroundColor' => $color,
                    'borderColor' => $color,
                    'extendedProps' => [
                        'type' => 'exception',
                        'date' => $date,
                        'segments' => $segments,
                        'notice' => $segments[0]['notice']
                    ]
                ];
            }

            $stmt = $pdo->query("SELECT * FROM ks_store_holidays WHERE active = 1");
            $holidays = $stmt->fetchAll(PDO::FETCH_ASSOC);

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

                    if ($date && $date >= $start && $date <= $end) {
                        $color = '#6f42c1';
                        $title = $h['name'];
                        if ($h['is_closed']) $title .= ' (Chiuso)';
                        
                        $events[] = [
                            'id' => 'hol_' . $h['id'] . '_' . $year,
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
            $date = $_POST['date'];
            $notice = $_POST['notice'] ?? null;
            $segments = $_POST['segments'] ?? [];

            $pdo->beginTransaction();
            try {
                $stmt = $pdo->prepare("DELETE FROM ks_store_hours_exceptions WHERE date = ?");
                $stmt->execute([$date]);

                $stmt = $pdo->prepare("INSERT INTO ks_store_hours_exceptions (date, seg, is_closed, open_time, close_time, notice) VALUES (?, ?, ?, ?, ?, ?)");
                
                foreach ([1, 2] as $segNum) {
                    $s = $segments[$segNum] ?? null;
                    $active = (isset($s['active']) && $s['active'] == 1);
                    $is_closed = $active ? 0 : 1;
                    
                    $open_time = ($active && !empty($s['open_time'])) ? $s['open_time'] : null;
                    $close_time = ($active && !empty($s['close_time'])) ? $s['close_time'] : null;
                    
                    $stmt->execute([$date, $segNum, $is_closed, $open_time, $close_time, $notice]);
                }

                $pdo->commit();
                jsonSuccess(['message' => 'Eccezioni salvate correttamente.']);
            } catch (Exception $e) {
                $pdo->rollBack();
                throw $e;
            }
            break;

        case 'delete_exception':
            $date = $_POST['date'] ?? null;
            if (!$date) jsonError('Data mancante.');
            
            $stmt = $pdo->prepare("DELETE FROM ks_store_hours_exceptions WHERE date = ?");
            $stmt->execute([$date]);
            jsonSuccess(['message' => 'Eccezioni rimosse.']);
            break;

        case 'get_holidays':
            $stmt = $pdo->query("SELECT * FROM ks_store_holidays ORDER BY month ASC, day ASC");
            $holidays = $stmt->fetchAll(PDO::FETCH_ASSOC);
            jsonSuccess(['data' => $holidays]);
            break;

        case 'save_holiday':
            $id = $_POST['id'] ?? null;
            $name = $_POST['name'];
            $rule_type = $_POST['rule_type'];
            $is_closed = $_POST['is_closed'] ?? 1;
            $active = $_POST['active'] ?? 1;
            
            $month = $_POST['month'] ?? null;
            $day = $_POST['day'] ?? null;
            $offset_days = $_POST['offset_days'] ?? 0;

            if ($id) {
                $stmt = $pdo->prepare("UPDATE ks_store_holidays SET name=?, rule_type=?, month=?, day=?, offset_days=?, is_closed=?, active=? WHERE id=?");
                $stmt->execute([$name, $rule_type, $month, $day, $offset_days, $is_closed, $active, $id]);
            } else {
                $stmt = $pdo->prepare("INSERT INTO ks_store_holidays (name, rule_type, month, day, offset_days, is_closed, active) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$name, $rule_type, $month, $day, $offset_days, $is_closed, $active]);
            }
            jsonSuccess(['message' => 'Festività salvata.']);
            break;

        case 'delete_holiday':
            $id = $_POST['id'] ?? null;
            if (!$id) jsonError('ID mancante.');
            
            $stmt = $pdo->prepare("DELETE FROM ks_store_holidays WHERE id=?");
            $stmt->execute([$id]);
            jsonSuccess(['message' => 'Festività rimossa.']);
            break;

        default:
            jsonError('Azione non valida.');
    }
} catch (Throwable $e) {
    jsonError('Errore del server.', $e);
}
