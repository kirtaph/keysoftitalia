<?php
require_once '../../config/config.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Non autorizzato']);
    exit;
}

$action = $_REQUEST['action'] ?? '';

try {
    if ($action === 'get') {
        $id = $_GET['id'] ?? null;
        if (!$id) throw new Exception('ID mancante');

        $stmt = $pdo->prepare("SELECT * FROM issues WHERE id = ?");
        $stmt->execute([$id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            echo json_encode(['status' => 'success', 'data' => $data]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Problema non trovato']);
        }
    } elseif ($action === 'list') {
        $device_id = $_GET['device_id'] ?? null;
        $sql = "SELECT * FROM issues";
        $params = [];
        if ($device_id) {
            $sql .= " WHERE device_id = ?";
            $params[] = $device_id;
        }
        $sql .= " ORDER BY sort_order ASC, label ASC";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $issues = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode(['status' => 'success', 'issues' => $issues]);

    } elseif ($action === 'add') {
        $device_id = $_POST['device_id'] ?? null;
        $label = $_POST['label'] ?? '';
        $severity = $_POST['severity'] ?? 'mid';
        
        if (!$device_id || !$label) throw new Exception('Dati mancanti');

        $stmt = $pdo->prepare("INSERT INTO issues (device_id, label, severity, is_active) VALUES (?, ?, ?, 1)");
        $stmt->execute([$device_id, $label, $severity]);

        echo json_encode(['status' => 'success', 'message' => 'Problema aggiunto']);
    } elseif ($action === 'edit') {
        $id = $_POST['id'] ?? null;
        $device_id = $_POST['device_id'] ?? null;
        $label = $_POST['label'] ?? '';
        $severity = $_POST['severity'] ?? 'mid';
        
        if (!$id || !$device_id || !$label) throw new Exception('Dati mancanti');

        $stmt = $pdo->prepare("UPDATE issues SET device_id = ?, label = ?, severity = ? WHERE id = ?");
        $stmt->execute([$device_id, $label, $severity, $id]);

        echo json_encode(['status' => 'success', 'message' => 'Problema aggiornato']);
    } elseif ($action === 'delete') {
        $id = $_POST['id'] ?? null;
        if (!$id) throw new Exception('ID mancante');

        $stmt = $pdo->prepare("DELETE FROM issues WHERE id = ?");
        $stmt->execute([$id]);

        echo json_encode(['status' => 'success', 'message' => 'Problema eliminato']);
    } else {
        throw new Exception('Azione non valida');
    }
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
