<?php
require_once '../../config/config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' || isset($_GET['action'])) {
    $action = $_REQUEST['action'] ?? '';
    $id = $_REQUEST['id'] ?? '';

    if (!$id) {
        echo json_encode(['status' => 'error', 'message' => 'ID mancante']);
        exit;
    }

    try {
        if ($action === 'get') {
            $stmt = $pdo->prepare("SELECT * FROM repair_bookings WHERE id = ?");
            $stmt->execute([$id]);
            $booking = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($booking) {
                echo json_encode(['status' => 'success', 'booking' => $booking]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Prenotazione non trovata']);
            }
        } elseif ($action === 'edit') {
            $status = $_POST['status'] ?? '';
            $notes = $_POST['notes'] ?? '';

            $stmt = $pdo->prepare("UPDATE repair_bookings SET status = ?, notes = ? WHERE id = ?");
            if ($stmt->execute([$status, $notes, $id])) {
                echo json_encode(['status' => 'success', 'message' => 'Prenotazione aggiornata']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Errore durante l\'aggiornamento']);
            }
        } elseif ($action === 'delete') {
            $stmt = $pdo->prepare("DELETE FROM repair_bookings WHERE id = ?");
            if ($stmt->execute([$id])) {
                echo json_encode(['status' => 'success', 'message' => 'Prenotazione eliminata']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Errore durante l\'eliminazione']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Azione non valida']);
        }
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Errore Database: ' . $e->getMessage()]);
    }
}
