<?php
// assets/process/process_telephony_request.php

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
$numLinesRaw = $trim('num_lines', '1');
$phone = $trim('phone');
$privacy = !empty($_POST['privacy']) ? 1 : 0;

$promoId = ctype_digit($promoIdRaw) ? (int)$promoIdRaw : null;
$currentSpend = $currentSpendRaw !== '' ? floatval($currentSpendRaw) : null;
$numLines = ctype_digit($numLinesRaw) ? (int)$numLinesRaw : 1;

// === Validazione ===
$errors = [];

if (!$promoId) {
    $errors['promotion_id'] = 'Seleziona una promozione.';
}
if ($currentSpend === null || $currentSpend <= 0) {
    $errors['current_spend'] = 'Spesa attuale non valida.';
}
if ($numLines <= 0) {
    $errors['num_lines'] = 'Il numero di linee deve essere almeno 1.';
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
    $stmt = $pdo->prepare("SELECT operator_name, plan_name, price FROM telephony_promotions WHERE id = ? AND status = 1");
    $stmt->execute([$promoId]);
    $promo = $stmt->fetch();

    if (!$promo) {
        http_response_code(400);
        echo json_encode(['ok' => false, 'message' => 'La promozione selezionata non è valida o non è più attiva.']);
        exit;
    }

    $promoPrice = floatval($promo['price']);
    $operatorName = $promo['operator_name'];
    $planName = $promo['plan_name'];

    // Calcolo risparmio annuale
    $currentYearly = $currentSpend * 12 * $numLines;
    $newYearly = $promoPrice * 12 * $numLines;
    $estimatedSavings = $currentYearly - $newYearly;

    // Inserimento nel database
    $stmt = $pdo->prepare("
        INSERT INTO telephony_requests (promotion_id, operator_name, plan_name, current_spend, num_lines, phone, estimated_savings, status, created_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, 'In attesa', NOW())
    ");
    $stmt->execute([$promoId, $operatorName, $planName, $currentSpend, $numLines, $phone, $estimatedSavings]);

    echo json_encode([
        'ok' => true,
        'message' => 'La tua richiesta è stata registrata con successo! Ti contatteremo a breve in negozio per finalizzare la pratica.'
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
