<?php
require_once __DIR__ . '/init.php';

$action = $_REQUEST['action'] ?? null;
$id = $_REQUEST['id'] ?? null;

try {
    switch ($action) {
        case 'get':
            $stmt = $pdo->prepare('SELECT * FROM ks_store_hours_weekly WHERE id = ?');
            $stmt->execute([$id]);
            $weekly_hour = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($weekly_hour) {
                jsonSuccess(['weekly_hour' => $weekly_hour]);
            } else {
                jsonError('Orario non trovato.');
            }
            break;

        case 'add':
            $dow = $_POST['dow'] ?? null;
            $seg = $_POST['seg'] ?? null;
            $open_time = $_POST['open_time'] ?? null;
            $close_time = $_POST['close_time'] ?? null;
            $active = isset($_POST['active']) && $_POST['active'] == '1' ? 1 : 0;
            if (empty($dow) || empty($seg) || empty($open_time) || empty($close_time)) {
                jsonError('Tutti i campi sono obbligatori.');
            }
            $stmt = $pdo->prepare('INSERT INTO ks_store_hours_weekly (dow, seg, open_time, close_time, active) VALUES (?, ?, ?, ?, ?)');
            $stmt->execute([$dow, $seg, $open_time, $close_time, $active]);
            jsonSuccess(['message' => 'Orario aggiunto con successo.']);
            break;

        case 'edit':
            $dow = $_POST['dow'] ?? null;
            $seg = $_POST['seg'] ?? null;
            $open_time = $_POST['open_time'] ?? null;
            $close_time = $_POST['close_time'] ?? null;
            $active = isset($_POST['active']) && $_POST['active'] == '1' ? 1 : 0;
            if (empty($dow) || empty($seg) || empty($open_time) || empty($close_time) || empty($id)) {
                jsonError('ID e tutti i campi sono obbligatori.');
            }
            $stmt = $pdo->prepare('UPDATE ks_store_hours_weekly SET dow = ?, seg = ?, open_time = ?, close_time = ?, active = ? WHERE id = ?');
            $stmt->execute([$dow, $seg, $open_time, $close_time, $active, $id]);
            jsonSuccess(['message' => 'Orario aggiornato con successo.']);
            break;

        case 'delete':
            if (empty($id)) {
                jsonError('ID non fornito.');
            }
            $stmt = $pdo->prepare('DELETE FROM ks_store_hours_weekly WHERE id = ?');
            $stmt->execute([$id]);
            jsonSuccess(['message' => 'Orario eliminato con successo.']);
            break;

        default:
            jsonError('Azione non valida.');
            break;
    }
} catch (Throwable $e) {
    jsonError('Errore del server.', $e);
}
