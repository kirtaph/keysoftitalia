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
            $stmt = $pdo->prepare("SELECT * FROM used_device_quotes WHERE id = ?");
            $stmt->execute([$id]);
            $quote = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($quote) {
                echo json_encode(['status' => 'success', 'quote' => $quote]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Valutazione non trovata']);
            }
        } elseif ($action === 'edit') {
            $status = $_POST['status'] ?? '';
            $expected_price = $_POST['expected_price'] ?? null;
            $notes = $_POST['notes'] ?? '';

            // Gestione prezzo vuoto
            if ($expected_price === '') $expected_price = null;

            $stmt = $pdo->prepare("UPDATE used_device_quotes SET status = ?, expected_price = ?, notes = ? WHERE id = ?");
            if ($stmt->execute([$status, $expected_price, $notes, $id])) {
                echo json_encode(['status' => 'success', 'message' => 'Valutazione aggiornata']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Errore durante l\'aggiornamento']);
            }
        } elseif ($action === 'delete') {
            $stmt = $pdo->prepare("DELETE FROM used_device_quotes WHERE id = ?");
            if ($stmt->execute([$id])) {
                echo json_encode(['status' => 'success', 'message' => 'Valutazione eliminata']);
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
