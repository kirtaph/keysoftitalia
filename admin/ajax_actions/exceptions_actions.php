<?php
include_once '../../config/config.php';

header('Content-Type: application/json');

$action = $_REQUEST['action'] ?? null;
$id = $_REQUEST['id'] ?? null;

try {
    switch ($action) {
        case 'get':
            $stmt = $pdo->prepare('SELECT * FROM ks_store_hours_exceptions WHERE id = ?');
            $stmt->execute([$id]);
            $exception = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($exception) {
                echo json_encode(['status' => 'success', 'exception' => $exception]);
            } else {
                throw new Exception('Eccezione non trovata.');
            }
            break;

        case 'add':
            $date = $_POST['date'] ?? null;
            $seg = $_POST['seg'] ?? null;
            $open_time = $_POST['open_time'] ?? null;
            $close_time = $_POST['close_time'] ?? null;
            $is_closed = isset($_POST['is_closed']) && $_POST['is_closed'] == '1' ? 1 : 0;
            $notice = $_POST['notice'] ?? null;

            if (empty($date) || empty($seg)) {
                throw new Exception('Data e segmento sono obbligatori.');
            }

            if ($is_closed) {
                $open_time = null;
                $close_time = null;
            } elseif (empty($open_time) || empty($close_time)) {
                throw new Exception('Orario di apertura e chiusura sono obbligatori se non è chiuso tutto il giorno.');
            }

            $stmt = $pdo->prepare('INSERT INTO ks_store_hours_exceptions (date, seg, open_time, close_time, is_closed, notice) VALUES (?, ?, ?, ?, ?, ?)');
            $stmt->execute([$date, $seg, $open_time, $close_time, $is_closed, $notice]);
            echo json_encode(['status' => 'success', 'message' => 'Eccezione aggiunta con successo.']);
            break;

        case 'edit':
             $date = $_POST['date'] ?? null;
            $seg = $_POST['seg'] ?? null;
            $open_time = $_POST['open_time'] ?? null;
            $close_time = $_POST['close_time'] ?? null;
            $is_closed = isset($_POST['is_closed']) && $_POST['is_closed'] == '1' ? 1 : 0;
            $notice = $_POST['notice'] ?? null;

            if (empty($id) || empty($date) || empty($seg)) {
                throw new Exception('ID, data e segmento sono obbligatori.');
            }

            if ($is_closed) {
                $open_time = null;
                $close_time = null;
            } elseif (empty($open_time) || empty($close_time)) {
                throw new Exception('Orario di apertura e chiusura sono obbligatori se non è chiuso tutto il giorno.');
            }
            
            $stmt = $pdo->prepare('UPDATE ks_store_hours_exceptions SET date = ?, seg = ?, open_time = ?, close_time = ?, is_closed = ?, notice = ? WHERE id = ?');
            $stmt->execute([$date, $seg, $open_time, $close_time, $is_closed, $notice, $id]);
            echo json_encode(['status' => 'success', 'message' => 'Eccezione aggiornata con successo.']);
            break;

        case 'delete':
            if (empty($id)) {
                throw new Exception('ID non fornito.');
            }
            $stmt = $pdo->prepare('DELETE FROM ks_store_hours_exceptions WHERE id = ?');
            $stmt->execute([$id]);
            echo json_encode(['status' => 'success', 'message' => 'Eccezione eliminata con successo.']);
            break;

        default:
            throw new Exception('Azione non valida.');
            break;
    }
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
