<?php
// assets/process/process_utility_request.php

declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../config/config.php';

header('Content-Type: application/json; charset=utf-8');

// === CSRF CHECK ===
if (
  !isset($_POST['csrf_token'], $_SESSION['csrf_token']) ||
  !hash_equals((string)$_SESSION['csrf_token'], (string)$_POST['csrf_token'])
) {
  http_response_code(403);
  echo json_encode(['ok' => false, 'message' => 'Token CSRF non valido.']);
  exit;
}

// Honeypot antispam
if (!empty($_POST['website'] ?? '')) {
  echo json_encode(['ok' => true, 'message' => 'Richiesta ricevuta.']); // finta success per i bot
  exit;
}

// === Normalizzazione input ===
$trim = static fn($key, $default = '') => isset($_POST[$key]) ? trim((string)$_POST[$key]) : $default;

$promoIdRaw  = $trim('promotion_id');
$currentSpendRaw = $trim('current_spend');
$phone = $trim('phone');
$privacy = !empty($_POST['privacy']) ? 1 : 0;

$promoId = ctype_digit($promoIdRaw) ? (int)$promoIdRaw : null;
$currentSpend = $currentSpendRaw !== '' ? floatval($currentSpendRaw) : null;

// === Validazione ===
$errors = [];

if (!$promoId) {
    $errors['promotion_id'] = 'Seleziona una promozione.';
}
if ($currentSpend === null || $currentSpend <= 0) {
    $errors['current_spend'] = 'Spesa attuale non valida.';
}
if (empty($phone)) {
    $errors['phone'] = 'Il numero di telefono è obbligatorio.';
}
if (!$privacy) {
    $errors['privacy'] = 'Devi accettare la Privacy Policy.';
}

if (!empty($errors)) {
  http_response_code(422);
  echo json_encode([
    'ok'      => false,
    'message' => 'Verifica i campi evidenziati.',
    'errors'  => $errors
  ]);
  exit;
}

try {
    // Recupero info promozione dal DB per salvataggio snapshot
    $stmt = $pdo->prepare("
        SELECT COALESCE(p.name, up.operator_name) AS operator_name, up.plan_name, up.price, up.utility_type 
        FROM utility_promotions up
        LEFT JOIN utility_partners p ON up.partner_id = p.id
        WHERE up.id = ? AND up.status = 1
    ");
    $stmt->execute([$promoId]);
    $promo = $stmt->fetch();

    if (!$promo) {
        http_response_code(400);
        echo json_encode(['ok' => false, 'message' => 'La promozione selezionata non è valida o non è più attiva.']);
        exit;
    }

    $promoPrice = floatval($promo['price']);
    $operatorName = $promo['operator_name'] ?: 'Partner Energetico';
    $planName = $promo['plan_name'];
    $utilityType = $promo['utility_type'];

    // Calcolo risparmio annuale (spesa mensile attuale * 12 mesi - spesa promo mensile * 12 mesi)
    $currentYearly = $currentSpend * 12;
    $newYearly = $promoPrice * 12;
    $estimatedSavings = $currentYearly - $newYearly;

    // Inserimento nel database
    $stmt = $pdo->prepare("
        INSERT INTO utility_requests (promotion_id, operator_name, plan_name, utility_type, current_spend, phone, estimated_savings, status, created_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, 'In attesa', NOW())
    ");
    $stmt->execute([$promoId, $operatorName, $planName, $utilityType, $currentSpend, $phone, $estimatedSavings]);

    echo json_encode([
        'ok' => true,
        'message' => 'La tua richiesta è stata inviata con successo! Ti contatteremo al più presto per finalizzare il passaggio.'
    ]);
    exit;

} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'ok' => false,
        'message' => 'Si è verificato un errore durante il salvataggio della richiesta: ' . $e->getMessage()
    ]);
    exit;
}
?>
