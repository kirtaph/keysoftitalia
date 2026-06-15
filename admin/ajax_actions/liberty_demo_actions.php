<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo json_encode(['status' => 'error', 'message' => 'Non autorizzato']);
    exit;
}

require_once '../../config/config.php';

header('Content-Type: application/json');

$action = $_POST['action'] ?? $_GET['action'] ?? '';

try {
    switch ($action) {
        case 'toggle_status':
            $id = $_POST['id'] ?? null;
            if (!$id) throw new Exception('ID mancante');
            
            // Fetch current status
            $stmt = $pdo->prepare("SELECT status FROM liberty_demo_requests WHERE id = ?");
            $stmt->execute([$id]);
            $currentStatus = $stmt->fetchColumn();
            
            if ($currentStatus === false) {
                throw new Exception('Richiesta non trovata');
            }
            
            $newStatus = $currentStatus == 1 ? 0 : 1;
            
            $stmtUpdate = $pdo->prepare("UPDATE liberty_demo_requests SET status = ? WHERE id = ?");
            $stmtUpdate->execute([$newStatus, $id]);
            
            echo json_encode([
                'status' => 'success',
                'new_status' => $newStatus
            ]);
            break;

        case 'delete':
            $id = $_POST['id'] ?? null;
            if (!$id) throw new Exception('ID mancante');
            
            $stmt = $pdo->prepare("DELETE FROM liberty_demo_requests WHERE id = ?");
            $stmt->execute([$id]);
            
            echo json_encode(['status' => 'success']);
            break;

        default:
            throw new Exception('Azione non valida');
    }
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
