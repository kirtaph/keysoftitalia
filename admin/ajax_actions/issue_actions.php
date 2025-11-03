<?php
include_once '../../config/config.php';

header('Content-Type: application/json');

$action = $_REQUEST['action'] ?? null;
$id = $_REQUEST['id'] ?? null;

try {
    switch ($action) {
        case 'get':
            $stmt = $pdo->prepare('SELECT * FROM issues WHERE id = ?');
            $stmt->execute([$id]);
            $issue = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($issue) {
                echo json_encode(['status' => 'success', 'issue' => $issue]);
            } else {
                throw new Exception('Problema non trovato.');
            }
            break;

        case 'get_by_device':
            $device_id = $_GET['device_id'] ?? null;
            if (!$device_id) {
                throw new Exception('ID dispositivo non fornito.');
            }
            $stmt = $pdo->prepare('SELECT * FROM issues WHERE device_id = ? AND is_active = 1 ORDER BY sort_order');
            $stmt->execute([$device_id]);
            $issues = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode(['status' => 'success', 'issues' => $issues]);
            break;

        case 'add':
            $device_id = $_POST['device_id'] ?? null;
            $label = $_POST['label'] ?? '';
            $severity = $_POST['severity'] ?? 'low';
            $sort_order = $_POST['sort_order'] ?? 0;
            $is_active = isset($_POST['is_active']) && $_POST['is_active'] == '1' ? 1 : 0;
            if (empty($label) || empty($device_id)) {
                throw new Exception('Label e dispositivo sono obbligatori.');
            }
            $stmt = $pdo->prepare('INSERT INTO issues (device_id, label, severity, sort_order, is_active) VALUES (?, ?, ?, ?, ?)');
            $stmt->execute([$device_id, $label, $severity, $sort_order, $is_active]);
            echo json_encode(['status' => 'success', 'message' => 'Problema aggiunto con successo.']);
            break;

        case 'edit':
            $device_id = $_POST['device_id'] ?? null;
            $label = $_POST['label'] ?? '';
            $severity = $_POST['severity'] ?? 'low';
            $sort_order = $_POST['sort_order'] ?? 0;
            $is_active = isset($_POST['is_active']) && $_POST['is_active'] == '1' ? 1 : 0;
            if (empty($label) || empty($device_id) || empty($id)) {
                throw new Exception('ID, label e dispositivo sono obbligatori.');
            }
            $stmt = $pdo->prepare('UPDATE issues SET device_id = ?, label = ?, severity = ?, sort_order = ?, is_active = ? WHERE id = ?');
            $stmt->execute([$device_id, $label, $severity, $sort_order, $is_active, $id]);
            echo json_encode(['status' => 'success', 'message' => 'Problema aggiornato con successo.']);
            break;

        case 'delete':
            if (empty($id)) {
                throw new Exception('ID del problema non fornito.');
            }
            $stmt = $pdo->prepare('DELETE FROM issues WHERE id = ?');
            $stmt->execute([$id]);
            echo json_encode(['status' => 'success', 'message' => 'Problema eliminato con successo.']);
            break;

        default:
            throw new Exception('Azione non valida.');
            break;
    }
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
