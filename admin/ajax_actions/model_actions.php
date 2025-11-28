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

        $stmt = $pdo->prepare("SELECT * FROM models WHERE id = ?");
        $stmt->execute([$id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            echo json_encode(['status' => 'success', 'data' => $data]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Modello non trovato']);
        }
    } elseif ($action === 'list') {
        $brand_id = $_GET['brand_id'] ?? null;
        $sql = "SELECT * FROM models";
        $params = [];
        if ($brand_id) {
            $sql .= " WHERE brand_id = ?";
            $params[] = $brand_id;
        }
        $sql .= " ORDER BY name ASC";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $models = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode(['status' => 'success', 'models' => $models]);

    } elseif ($action === 'add') {
        $brand_id = $_POST['brand_id'] ?? null;
        $name = $_POST['name'] ?? '';
        
        if (!$brand_id || !$name) throw new Exception('Dati mancanti');

        $stmt = $pdo->prepare("INSERT INTO models (brand_id, name, is_active) VALUES (?, ?, 1)");
        $stmt->execute([$brand_id, $name]);

        echo json_encode(['status' => 'success', 'message' => 'Modello aggiunto']);
    } elseif ($action === 'edit') {
        $id = $_POST['id'] ?? null;
        $brand_id = $_POST['brand_id'] ?? null;
        $name = $_POST['name'] ?? '';
        
        if (!$id || !$brand_id || !$name) throw new Exception('Dati mancanti');

        $stmt = $pdo->prepare("UPDATE models SET brand_id = ?, name = ? WHERE id = ?");
        $stmt->execute([$brand_id, $name, $id]);

        echo json_encode(['status' => 'success', 'message' => 'Modello aggiornato']);
    } elseif ($action === 'delete') {
        $id = $_POST['id'] ?? null;
        if (!$id) throw new Exception('ID mancante');

        $stmt = $pdo->prepare("DELETE FROM models WHERE id = ?");
        $stmt->execute([$id]);

        echo json_encode(['status' => 'success', 'message' => 'Modello eliminato']);
    } else {
        throw new Exception('Azione non valida');
    }
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
