<?php
include_once '../../config/config.php';

header('Content-Type: application/json');

$action = $_REQUEST['action'] ?? null;
$id = $_REQUEST['id'] ?? null;

try {
    switch ($action) {
        case 'get':
            $stmt = $pdo->prepare('SELECT m.*, b.device_id FROM models m JOIN brands b ON m.brand_id = b.id WHERE m.id = ?');
            $stmt->execute([$id]);
            $model = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($model) {
                echo json_encode(['status' => 'success', 'model' => $model]);
            } else {
                throw new Exception('Modello non trovato.');
            }
            break;

        case 'get_by_brand':
            $brand_id = $_GET['brand_id'] ?? null;
            if (!$brand_id) {
                throw new Exception('ID marchio non fornito.');
            }
            $stmt = $pdo->prepare('SELECT * FROM models WHERE brand_id = ? AND is_active = 1 ORDER BY name');
            $stmt->execute([$brand_id]);
            $models = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode(['status' => 'success', 'models' => $models]);
            break;

        case 'add':
            $name = $_POST['name'] ?? '';
            $brand_id = $_POST['brand_id'] ?? null;
            $year = !empty($_POST['year']) ? $_POST['year'] : null;
            $is_active = isset($_POST['is_active']) && $_POST['is_active'] == '1' ? 1 : 0;
            if (empty($name) || empty($brand_id)) {
                throw new Exception('Nome e marchio sono obbligatori.');
            }
            $stmt = $pdo->prepare('INSERT INTO models (name, brand_id, year, is_active) VALUES (?, ?, ?, ?)');
            $stmt->execute([$name, $brand_id, $year, $is_active]);
            echo json_encode(['status' => 'success', 'message' => 'Modello aggiunto con successo.']);
            break;

        case 'edit':
            $name = $_POST['name'] ?? '';
            $brand_id = $_POST['brand_id'] ?? null;
            $year = !empty($_POST['year']) ? $_POST['year'] : null;
            $is_active = isset($_POST['is_active']) && $_POST['is_active'] == '1' ? 1 : 0;
            if (empty($name) || empty($brand_id) || empty($id)) {
                throw new Exception('ID, nome e marchio sono obbligatori.');
            }
            $stmt = $pdo->prepare('UPDATE models SET name = ?, brand_id = ?, year = ?, is_active = ? WHERE id = ?');
            $stmt->execute([$name, $brand_id, $year, $is_active, $id]);
            echo json_encode(['status' => 'success', 'message' => 'Modello aggiornato con successo.']);
            break;

        case 'delete':
            if (empty($id)) {
                throw new Exception('ID del modello non fornito.');
            }
            $stmt = $pdo->prepare('DELETE FROM models WHERE id = ?');
            $stmt->execute([$id]);
            echo json_encode(['status' => 'success', 'message' => 'Modello eliminato con successo.']);
            break;

        default:
            throw new Exception('Azione non valida.');
            break;
    }
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
