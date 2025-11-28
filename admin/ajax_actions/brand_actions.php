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

        $stmt = $pdo->prepare("SELECT * FROM brands WHERE id = ?");
        $stmt->execute([$id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            echo json_encode(['status' => 'success', 'data' => $data]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Brand non trovato']);
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
        
        echo json_encode(['status' => 'success', 'brands' => $brands]);

    } elseif ($action === 'add') {
        $device_id = $_POST['device_id'] ?? null;
        $name = $_POST['name'] ?? '';
        
        if (!$device_id || !$name) throw new Exception('Dati mancanti');

        $stmt = $pdo->prepare("INSERT INTO brands (device_id, name, is_active) VALUES (?, ?, 1)");
        $stmt->execute([$device_id, $name]);

        echo json_encode(['status' => 'success', 'message' => 'Brand aggiunto']);
    } elseif ($action === 'edit') {
        $id = $_POST['id'] ?? null;
        $device_id = $_POST['device_id'] ?? null;
        $name = $_POST['name'] ?? '';
        
        if (!$id || !$device_id || !$name) throw new Exception('Dati mancanti');

        $stmt = $pdo->prepare("UPDATE brands SET device_id = ?, name = ? WHERE id = ?");
        $stmt->execute([$device_id, $name, $id]);

        echo json_encode(['status' => 'success', 'message' => 'Brand aggiornato']);
    } elseif ($action === 'delete') {
        $id = $_POST['id'] ?? null;
        if (!$id) throw new Exception('ID mancante');

        $stmt = $pdo->prepare("DELETE FROM brands WHERE id = ?");
        $stmt->execute([$id]);

        echo json_encode(['status' => 'success', 'message' => 'Brand eliminato']);
    } else {
        throw new Exception('Azione non valida');
    }
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
