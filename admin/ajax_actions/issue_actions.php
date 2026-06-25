<?php
require_once __DIR__ . '/init.php';

$action = $_REQUEST['action'] ?? '';

try {
    if ($action === 'get') {
        $id = $_GET['id'] ?? null;
        if (!$id) jsonError('ID mancante');

        $stmt = $pdo->prepare("SELECT * FROM issues WHERE id = ?");
        $stmt->execute([$id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            jsonSuccess(['data' => $data]);
        } else {
            jsonError('Problema non trovato');
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
        
        jsonSuccess(['issues' => $issues]);

    } elseif ($action === 'add') {
        $device_id = $_POST['device_id'] ?? null;
        $label = $_POST['label'] ?? '';
        $severity = $_POST['severity'] ?? 'mid';
        
        if (!$device_id || !$label) jsonError('Dati mancanti');

        $stmt = $pdo->prepare("INSERT INTO issues (device_id, label, severity, is_active) VALUES (?, ?, ?, 1)");
        $stmt->execute([$device_id, $label, $severity]);

        jsonSuccess(['message' => 'Problema aggiunto']);
    } elseif ($action === 'edit') {
        $id = $_POST['id'] ?? null;
        $device_id = $_POST['device_id'] ?? null;
        $label = $_POST['label'] ?? '';
        $severity = $_POST['severity'] ?? 'mid';
        
        if (!$id || !$device_id || !$label) jsonError('Dati mancanti');

        $stmt = $pdo->prepare("UPDATE issues SET device_id = ?, label = ?, severity = ? WHERE id = ?");
        $stmt->execute([$device_id, $label, $severity, $id]);

        jsonSuccess(['message' => 'Problema aggiornato']);
    } elseif ($action === 'delete') {
        $id = $_POST['id'] ?? null;
        if (!$id) jsonError('ID mancante');

        $stmt = $pdo->prepare("DELETE FROM issues WHERE id = ?");
        $stmt->execute([$id]);

        jsonSuccess(['message' => 'Problema eliminato']);
    } else {
        jsonError('Azione non valida');
    }
} catch (Throwable $e) {
    jsonError('Errore del server.', $e);
}
