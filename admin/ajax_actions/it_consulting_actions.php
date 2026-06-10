<?php
include_once '../../config/config.php';

header('Content-Type: application/json');

// Security Check: Ensure user is logged in
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

$action = $_REQUEST['action'] ?? null;

try {
    switch ($action) {
        case 'list':
            $stmt = $pdo->query("SELECT * FROM it_packages ORDER BY sort_order ASC, title ASC");
            $packages = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode(['status' => 'success', 'packages' => $packages]);
            break;

        case 'get':
            $id = $_GET['id'] ?? null;
            if (!$id) throw new Exception('ID pacchetto non fornito.');
            
            $stmt = $pdo->prepare('SELECT * FROM it_packages WHERE id = ?');
            $stmt->execute([$id]);
            $package = $stmt->fetch(PDO::FETCH_ASSOC);
            
            echo json_encode(['status' => 'success', 'package' => $package]);
            break;

        case 'save':
            $id = $_POST['id'] ?? null;
            $title = trim($_POST['title'] ?? '');
            $price_raw = trim($_POST['price'] ?? '');
            $price_detail = trim($_POST['price_detail'] ?? '/mese');
            $subtitle = trim($_POST['subtitle'] ?? '');
            $features = trim($_POST['features'] ?? '');
            $status = isset($_POST['status']) ? (int)$_POST['status'] : 1;
            $is_featured = isset($_POST['is_featured']) ? 1 : 0;
            $sort_order = isset($_POST['sort_order']) ? (int)$_POST['sort_order'] : 0;

            if (empty($title)) throw new Exception('Il titolo del pacchetto è obbligatorio.');
            
            $price = ($price_raw !== '') ? floatval($price_raw) : null;

            if (empty($id)) { // Add
                $stmt = $pdo->prepare(
                    'INSERT INTO it_packages (title, price, price_detail, subtitle, features, is_featured, status, sort_order)
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?)'
                );
                $stmt->execute([$title, $price, $price_detail, $subtitle, $features, $is_featured, $status, $sort_order]);
            } else { // Edit
                $stmt = $pdo->prepare(
                    'UPDATE it_packages SET title=?, price=?, price_detail=?, subtitle=?, features=?, is_featured=?, status=?, sort_order=? WHERE id = ?'
                );
                $stmt->execute([$title, $price, $price_detail, $subtitle, $features, $is_featured, $status, $sort_order, $id]);
            }
            
            echo json_encode(['status' => 'success', 'message' => 'Pacchetto salvato con successo.']);
            break;

        case 'delete':
            $id = $_POST['id'] ?? null;
            if (!$id) throw new Exception('ID pacchetto non fornito.');

            $stmt = $pdo->prepare('DELETE FROM it_packages WHERE id = ?');
            $stmt->execute([$id]);

            echo json_encode(['status' => 'success', 'message' => 'Pacchetto eliminato con successo.']);
            break;

        default:
            throw new Exception('Azione non valida.');
            break;
    }
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
