<?php
require_once __DIR__ . '/init.php';

$action = $_REQUEST['action'] ?? null;
$id = $_REQUEST['id'] ?? null;

try {
    switch ($action) {
        case 'get':
            $stmt = $pdo->prepare('SELECT * FROM ks_store_hours_exceptions WHERE id = ?');
            $stmt->execute([$id]);
            $exception = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($exception) {
                jsonSuccess(['exception' => $exception]);
            } else {
                jsonError('Eccezione non trovata.');
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
                jsonError('Data e segmento sono obbligatori.');
            }

            if ($is_closed) {
                $open_time = null;
                $close_time = null;
            } elseif (empty($open_time) || empty($close_time)) {
                jsonError('Orario di apertura e chiusura sono obbligatori se non è chiuso tutto il giorno.');
            }

            $stmt = $pdo->prepare('INSERT INTO ks_store_hours_exceptions (date, seg, open_time, close_time, is_closed, notice) VALUES (?, ?, ?, ?, ?, ?)');
            $stmt->execute([$date, $seg, $open_time, $close_time, $is_closed, $notice]);
            jsonSuccess(['message' => 'Eccezione aggiunta con successo.']);
            break;

        case 'edit':
             $date = $_POST['date'] ?? null;
            $seg = $_POST['seg'] ?? null;
            $open_time = $_POST['open_time'] ?? null;
            $close_time = $_POST['close_time'] ?? null;
            $is_closed = isset($_POST['is_closed']) && $_POST['is_closed'] == '1' ? 1 : 0;
            $notice = $_POST['notice'] ?? null;

            if (empty($id) || empty($date) || empty($seg)) {
                jsonError('ID, data e segmento sono obbligatori.');
            }

            if ($is_closed) {
                $open_time = null;
                $close_time = null;
            } elseif (empty($open_time) || empty($close_time)) {
                jsonError('Orario di apertura e chiusura sono obbligatori se non è chiuso tutto il giorno.');
            }
            
            $stmt = $pdo->prepare('UPDATE ks_store_hours_exceptions SET date = ?, seg = ?, open_time = ?, close_time = ?, is_closed = ?, notice = ? WHERE id = ?');
            $stmt->execute([$date, $seg, $open_time, $close_time, $is_closed, $notice, $id]);
            jsonSuccess(['message' => 'Eccezione aggiornata con successo.']);
            break;

        case 'delete':
            if (empty($id)) {
                jsonError('ID non fornito.');
            }
            $stmt = $pdo->prepare('DELETE FROM ks_store_hours_exceptions WHERE id = ?');
            $stmt->execute([$id]);
            jsonSuccess(['message' => 'Eccezione eliminata con successo.']);
            break;

        default:
            jsonError('Azione non valida.');
            break;
    }
} catch (Throwable $e) {
    jsonError('Errore del server.', $e);
}
