<?php
require_once __DIR__ . '/init.php';

$action = $_REQUEST['action'] ?? '';

try {
    if ($action === 'get') {
        $id = $_GET['id'] ?? null;
        if (!$id) jsonError('ID mancante');

        $stmt = $pdo->prepare("SELECT * FROM brands WHERE id = ?");
        $stmt->execute([$id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            jsonSuccess(['data' => $data]);
        } else {
            jsonError('Brand non trovato');
        }
    } elseif ($action === 'list') {
        $device_id = $_GET['device_id'] ?? null;
        $sql = "SELECT * FROM brands";
        $params = [];
        if ($device_id) {
            $sql .= " WHERE device_id = ?";
            $params[] = $device_id;
        }
        $sql .= " ORDER BY name ASC";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $brands = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        jsonSuccess(['brands' => $brands]);
    } elseif ($action === 'add') {
        $device_id = $_POST['device_id'] ?? null;
        $name = $_POST['name'] ?? '';
        
        if (!$device_id || !$name) jsonError('Dati mancanti');

        $stmt = $pdo->prepare("INSERT INTO brands (device_id, name, is_active) VALUES (?, ?, 1)");
        $stmt->execute([$device_id, $name]);

        jsonSuccess(['message' => 'Brand aggiunto']);
    } elseif ($action === 'edit') {
        $id = $_POST['id'] ?? null;
        $device_id = $_POST['device_id'] ?? null;
        $name = $_POST['name'] ?? '';
        
        if (!$id || !$device_id || !$name) jsonError('Dati mancanti');

        $stmt = $pdo->prepare("UPDATE brands SET device_id = ?, name = ? WHERE id = ?");
        $stmt->execute([$device_id, $name, $id]);

        jsonSuccess(['message' => 'Brand aggiornato']);
    } elseif ($action === 'delete') {
        $id = $_POST['id'] ?? null;
        if (!$id) jsonError('ID mancante');

        $stmt = $pdo->prepare("DELETE FROM brands WHERE id = ?");
        $stmt->execute([$id]);

        jsonSuccess(['message' => 'Brand eliminato']);
    } else {
        jsonError('Azione non valida');
    }
} catch (Throwable $e) {
    jsonError('Errore del server.', $e);
}
