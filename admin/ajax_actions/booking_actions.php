<?php
require_once __DIR__ . '/init.php';

$action = $_REQUEST['action'] ?? '';

try {
    $id = $_REQUEST['id'] ?? '';
    if (!$id) jsonError('ID mancante');

    if ($action === 'get') {
        $stmt = $pdo->prepare("SELECT id, created_at, device_type, brand_name, model_name,
            problem_summary, notes, preferred_date, preferred_time_slot, dropoff_type,
            customer_first_name, customer_last_name, customer_email, customer_phone,
            customer_company, status FROM repair_bookings WHERE id = ?");
        $stmt->execute([$id]);
        $booking = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($booking) {
            jsonSuccess(['booking' => $booking]);
        } else {
            jsonError('Prenotazione non trovata');
        }
    } elseif ($action === 'edit') {
        $status = $_POST['status'] ?? '';
        $notes = $_POST['notes'] ?? '';
        $stmt = $pdo->prepare("UPDATE repair_bookings SET status = ?, notes = ? WHERE id = ?");
        if ($stmt->execute([$status, $notes, $id])) {
            jsonSuccess(['message' => 'Prenotazione aggiornata']);
        } else {
            jsonError('Errore durante l\'aggiornamento');
        }
    } elseif ($action === 'delete') {
        $stmt = $pdo->prepare("DELETE FROM repair_bookings WHERE id = ?");
        if ($stmt->execute([$id])) {
            jsonSuccess(['message' => 'Prenotazione eliminata']);
        } else {
            jsonError('Errore durante l\'eliminazione');
        }
    } else {
        jsonError('Azione non valida');
    }
} catch (Throwable $e) {
    jsonError('Errore del server.', $e);
}
