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

        $stmt = $pdo->prepare("SELECT q.*, d.name as device_name FROM quotes q JOIN devices d ON q.device_id = d.id WHERE q.id = ?");
        $stmt->execute([$id]);
        $quote = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($quote) {
            echo json_encode(['status' => 'success', 'quote' => $quote]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Preventivo non trovato']);
        }
    } elseif ($action === 'delete') {
        $id = $_POST['id'] ?? null;
        if (!$id) throw new Exception('ID mancante');

        $stmt = $pdo->prepare("DELETE FROM quotes WHERE id = ?");
        $stmt->execute([$id]);

        echo json_encode(['status' => 'success']);
    } else {
        throw new Exception('Azione non valida');
    }
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
