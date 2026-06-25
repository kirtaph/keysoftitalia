<?php
require_once __DIR__ . '/init.php';

$action = $_POST['action'] ?? $_GET['action'] ?? '';

try {
    switch ($action) {
        case 'toggle_status':
            $id = $_POST['id'] ?? null;
            if (!$id) jsonError('ID mancante');
            
            $stmt = $pdo->prepare("SELECT status FROM liberty_demo_requests WHERE id = ?");
            $stmt->execute([$id]);
            $currentStatus = $stmt->fetchColumn();
            
            if ($currentStatus === false) {
                jsonError('Richiesta non trovata');
            }
            
            $newStatus = $currentStatus == 1 ? 0 : 1;
            
            $stmtUpdate = $pdo->prepare("UPDATE liberty_demo_requests SET status = ? WHERE id = ?");
            $stmtUpdate->execute([$newStatus, $id]);
            
            jsonSuccess(['new_status' => $newStatus]);
            break;

        case 'delete':
            $id = $_POST['id'] ?? null;
            if (!$id) jsonError('ID mancante');
            
            $stmt = $pdo->prepare("DELETE FROM liberty_demo_requests WHERE id = ?");
            $stmt->execute([$id]);
            
            jsonSuccess([]);
            break;

        default:
            jsonError('Azione non valida');
    }
} catch (Throwable $e) {
    jsonError('Errore del server.', $e);
}
