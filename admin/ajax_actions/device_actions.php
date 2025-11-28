<?php
include_once '../../config/config.php';

header('Content-Type: application/json');

$action = $_REQUEST['action'] ?? null;
$id = $_REQUEST['id'] ?? null;

try {
    switch ($action) {
        case 'get':
            $stmt = $pdo->prepare('SELECT * FROM devices WHERE id = ?');
            $stmt->execute([$id]);
            $device = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($device) {
                echo json_encode(['status' => 'success', 'data' => $device]);
            } else {
                throw new Exception('Dispositivo non trovato.');
            }
            break;

        case 'add':
            $name = $_POST['name'] ?? '';
            $slug = $_POST['slug'] ?? '';
            $sort_order = $_POST['sort_order'] ?? 0;
            if (empty($name) || empty($slug)) {
                throw new Exception('Nome e slug sono obbligatori.');
            }
            $stmt = $pdo->prepare('INSERT INTO devices (name, slug, sort_order) VALUES (?, ?, ?)');
            $stmt->execute([$name, $slug, $sort_order]);
            echo json_encode(['status' => 'success', 'message' => 'Dispositivo aggiunto con successo.']);
            break;

        case 'edit':
            $name = $_POST['name'] ?? '';
            $slug = $_POST['slug'] ?? '';
            $sort_order = $_POST['sort_order'] ?? 0;
            if (empty($name) || empty($slug) || empty($id)) {
                throw new Exception('ID, nome e slug sono obbligatori.');
            }
            $stmt = $pdo->prepare('UPDATE devices SET name = ?, slug = ?, sort_order = ? WHERE id = ?');
            $stmt->execute([$name, $slug, $sort_order, $id]);
            echo json_encode(['status' => 'success', 'message' => 'Dispositivo aggiornato con successo.']);
            break;

        case 'delete':
            if (empty($id)) {
                throw new Exception('ID del dispositivo non fornito.');
            }
            $stmt = $pdo->prepare('DELETE FROM devices WHERE id = ?');
            $stmt->execute([$id]);
            echo json_encode(['status' => 'success', 'message' => 'Dispositivo eliminato con successo.']);
            break;

        default:
            throw new Exception('Azione non valida.');
            break;
    }
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
