<?php
require_once __DIR__ . '/init.php';

$action = $_REQUEST['action'] ?? '';

try {
    $id = $_REQUEST['id'] ?? '';
    if (!$id) jsonError('ID mancante');

    if ($action === 'get') {
        $stmt = $pdo->prepare("SELECT * FROM used_device_quotes WHERE id = ?");
        $stmt->execute([$id]);
        $quote = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($quote) {
            jsonSuccess(['quote' => $quote]);
        } else {
            jsonError('Valutazione non trovata');
        }
    } elseif ($action === 'edit') {
        $status = $_POST['status'] ?? '';
        $expected_price = $_POST['expected_price'] ?? null;
        $notes = $_POST['notes'] ?? '';

        if ($expected_price === '') $expected_price = null;

        $stmt = $pdo->prepare("UPDATE used_device_quotes SET status = ?, expected_price = ?, notes = ? WHERE id = ?");
        if ($stmt->execute([$status, $expected_price, $notes, $id])) {
            jsonSuccess(['message' => 'Valutazione aggiornata']);
        } else {
            jsonError('Errore durante l\'aggiornamento');
        }
    } elseif ($action === 'delete') {
        $stmt = $pdo->prepare("DELETE FROM used_device_quotes WHERE id = ?");
        if ($stmt->execute([$id])) {
            jsonSuccess(['message' => 'Valutazione eliminata']);
        } else {
            jsonError('Errore durante l\'eliminazione');
        }
    } else {
        jsonError('Azione non valida');
    }
} catch (Throwable $e) {
    jsonError('Errore del server.', $e);
}
