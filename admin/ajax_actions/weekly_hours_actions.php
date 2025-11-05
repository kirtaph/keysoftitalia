<?php
include_once '../../config/config.php';

header('Content-Type: application/json');

$action = $_REQUEST['action'] ?? null;
$id = $_REQUEST['id'] ?? null;

try {
    switch ($action) {
        case 'get':
            $stmt = $pdo->prepare('SELECT * FROM ks_store_hours_weekly WHERE id = ?');
            $stmt->execute([$id]);
            $weekly_hour = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($weekly_hour) {
                echo json_encode(['status' => 'success', 'weekly_hour' => $weekly_hour]);
            } else {
                throw new Exception('Orario non trovato.');
            }
            break;

        case 'add':
            $dow = $_POST['dow'] ?? null;
            $seg = $_POST['seg'] ?? null;
            $open_time = $_POST['open_time'] ?? null;
            $close_time = $_POST['close_time'] ?? null;
            $active = isset($_POST['active']) && $_POST['active'] == '1' ? 1 : 0;
            if (empty($dow) || empty($seg) || empty($open_time) || empty($close_time)) {
                throw new Exception('Tutti i campi sono obbligatori.');
            }
            $stmt = $pdo->prepare('INSERT INTO ks_store_hours_weekly (dow, seg, open_time, close_time, active) VALUES (?, ?, ?, ?, ?)');
            $stmt->execute([$dow, $seg, $open_time, $close_time, $active]);
            echo json_encode(['status' => 'success', 'message' => 'Orario aggiunto con successo.']);
            break;

        case 'edit':
            $dow = $_POST['dow'] ?? null;
            $seg = $_POST['seg'] ?? null;
            $open_time = $_POST['open_time'] ?? null;
            $close_time = $_POST['close_time'] ?? null;
            $active = isset($_POST['active']) && $_POST['active'] == '1' ? 1 : 0;
            if (empty($dow) || empty($seg) || empty($open_time) || empty($close_time) || empty($id)) {
                throw new Exception('ID e tutti i campi sono obbligatori.');
            }
            $stmt = $pdo->prepare('UPDATE ks_store_hours_weekly SET dow = ?, seg = ?, open_time = ?, close_time = ?, active = ? WHERE id = ?');
            $stmt->execute([$dow, $seg, $open_time, $close_time, $active, $id]);
            echo json_encode(['status' => 'success', 'message' => 'Orario aggiornato con successo.']);
            break;

        case 'delete':
            if (empty($id)) {
                throw new Exception('ID non fornito.');
            }
            $stmt = $pdo->prepare('DELETE FROM ks_store_hours_weekly WHERE id = ?');
            $stmt->execute([$id]);
            echo json_encode(['status' => 'success', 'message' => 'Orario eliminato con successo.']);
            break;

        default:
            throw new Exception('Azione non valida.');
            break;
    }
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
