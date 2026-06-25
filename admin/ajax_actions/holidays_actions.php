<?php
require_once __DIR__ . '/init.php';

$action = $_REQUEST['action'] ?? null;
$id = $_REQUEST['id'] ?? null;

try {
    switch ($action) {
        case 'get':
            $stmt = $pdo->prepare('SELECT * FROM ks_store_holidays WHERE id = ?');
            $stmt->execute([$id]);
            $holiday = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($holiday) {
                jsonSuccess(['holiday' => $holiday]);
            } else {
                jsonError('Festività non trovata.');
            }
            break;

        case 'add':
            $name = $_POST['name'] ?? '';
            $rule_type = $_POST['rule_type'] ?? null;
            $month = $_POST['month'] ?? null;
            $day = $_POST['day'] ?? null;
            $offset_days = $_POST['offset_days'] ?? null;
            $is_closed = isset($_POST['is_closed']) && $_POST['is_closed'] == '1' ? 1 : 0;
            $active = isset($_POST['active']) && $_POST['active'] == '1' ? 1 : 0;
            $notice = $_POST['notice'] ?? null;

            if (empty($name) || empty($rule_type)) {
                jsonError('Nome e tipo regola sono obbligatori.');
            }
             if ($rule_type === 'fixed' && (empty($month) || empty($day))) {
                jsonError('Mese e giorno sono obbligatori per le festività fisse.');
            }
            if ($rule_type === 'easter' && $offset_days === null) {
                jsonError('Offset è obbligatorio per le festività legate a Pasqua.');
            }

            $stmt = $pdo->prepare('INSERT INTO ks_store_holidays (name, rule_type, month, day, offset_days, is_closed, active, notice) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
            $stmt->execute([$name, $rule_type, $month, $day, $offset_days, $is_closed, $active, $notice]);
            jsonSuccess(['message' => 'Festività aggiunta con successo.']);
            break;

        case 'edit':
            $name = $_POST['name'] ?? '';
            $rule_type = $_POST['rule_type'] ?? null;
            $month = $_POST['month'] ?? null;
            $day = $_POST['day'] ?? null;
            $offset_days = $_POST['offset_days'] ?? null;
            $is_closed = isset($_POST['is_closed']) && $_POST['is_closed'] == '1' ? 1 : 0;
            $active = isset($_POST['active']) && $_POST['active'] == '1' ? 1 : 0;
            $notice = $_POST['notice'] ?? null;

            if (empty($id) || empty($name) || empty($rule_type)) {
                jsonError('ID, nome e tipo regola sono obbligatori.');
            }
            if ($rule_type === 'fixed' && (empty($month) || empty($day))) {
                jsonError('Mese e giorno sono obbligatori per le festività fisse.');
            }
            if ($rule_type === 'easter' && $offset_days === null) {
                jsonError('Offset è obbligatorio per le festività legate a Pasqua.');
            }

            $stmt = $pdo->prepare('UPDATE ks_store_holidays SET name = ?, rule_type = ?, month = ?, day = ?, offset_days = ?, is_closed = ?, active = ?, notice = ? WHERE id = ?');
            $stmt->execute([$name, $rule_type, $month, $day, $offset_days, $is_closed, $active, $notice, $id]);
            jsonSuccess(['message' => 'Festività aggiornata con successo.']);
            break;

        case 'delete':
            if (empty($id)) {
                jsonError('ID non fornito.');
            }
            $stmt = $pdo->prepare('DELETE FROM ks_store_holidays WHERE id = ?');
            $stmt->execute([$id]);
            jsonSuccess(['message' => 'Festività eliminata con successo.']);
            break;

        default:
            jsonError('Azione non valida.');
            break;
    }
} catch (Throwable $e) {
    jsonError('Errore del server.', $e);
}
