<?php
include_once '../../config/config.php';

header('Content-Type: application/json');

$action = $_REQUEST['action'] ?? null;
$id = $_REQUEST['id'] ?? null;

try {
    switch ($action) {
        case 'get':
            $stmt = $pdo->prepare('SELECT * FROM brands WHERE id = ?');
            $stmt->execute([$id]);
            $brand = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($brand) {
                echo json_encode(['status' => 'success', 'brand' => $brand]);
            } else {
                throw new Exception('Marchio non trovato.');
            }
            break;

        case 'get_by_device':
            $device_id = $_GET['device_id'] ?? null;
            if (!$device_id) {
                throw new Exception('ID dispositivo non fornito.');
            }
            $stmt = $pdo->prepare('SELECT * FROM brands WHERE device_id = ? AND is_active = 1 ORDER BY name');
            $stmt->execute([$device_id]);
            $brands = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode(['status' => 'success', 'brands' => $brands]);
            break;

        case 'add':
            $name = $_POST['name'] ?? '';
            $device_id = $_POST['device_id'] ?? null;
            $is_active = isset($_POST['is_active']) && $_POST['is_active'] == '1' ? 1 : 0;
            if (empty($name) || empty($device_id)) {
                throw new Exception('Nome e dispositivo sono obbligatori.');
            }
            $stmt = $pdo->prepare('INSERT INTO brands (name, device_id, is_active) VALUES (?, ?, ?)');
            $stmt->execute([$name, $device_id, $is_active]);
            echo json_encode(['status' => 'success', 'message' => 'Marchio aggiunto con successo.']);
            break;

        case 'edit':
            $name = $_POST['name'] ?? '';
            $device_id = $_POST['device_id'] ?? null;
            $is_active = isset($_POST['is_active']) && $_POST['is_active'] == '1' ? 1 : 0;
            if (empty($name) || empty($device_id) || empty($id)) {
                throw new Exception('ID, nome e dispositivo sono obbligatori.');
            }
            $stmt = $pdo->prepare('UPDATE brands SET name = ?, device_id = ?, is_active = ? WHERE id = ?');
            $stmt->execute([$name, $device_id, $is_active, $id]);
            echo json_encode(['status' => 'success', 'message' => 'Marchio aggiornato con successo.']);
            break;

        case 'delete':
            if (empty($id)) {
                throw new Exception('ID del marchio non fornito.');
            }
            $stmt = $pdo->prepare('DELETE FROM brands WHERE id = ?');
            $stmt->execute([$id]);
            echo json_encode(['status' => 'success', 'message' => 'Marchio eliminato con successo.']);
            break;

        default:
            throw new Exception('Azione non valida.');
            break;
    }
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
