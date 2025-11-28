<?php
// assets/process/process_booking.php

declare(strict_types=1);

session_start();
require_once __DIR__ . '/../../config/config.php';

header('Content-Type: application/json; charset=utf-8');

// Helper per capire se è AJAX (dovresti averlo già in functions.php)
if (!function_exists('is_ajax_request')) {
  function is_ajax_request(): bool {
    return (
      isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
      strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest'
    );
  }
}

// === CSRF CHECK (stesso pattern che usi ovunque) ===
if (
  !isset($_POST['csrf_token'], $_SESSION['csrf_token']) ||
  !hash_equals((string)$_SESSION['csrf_token'], (string)$_POST['csrf_token'])
) {
  http_response_code(403);
  echo json_encode(['ok' => false, 'error' => 'csrf', 'message' => 'Token CSRF non valido.']);
  exit;
}

// Honeypot antispam
if (!empty($_POST['website'] ?? '')) {
  echo json_encode(['ok' => true, 'message' => 'OK']); // finta success
  exit;
}

// === Connessione PDO (se non già presente) ===
if (!isset($pdo) || !($pdo instanceof PDO)) {
  try {
    $pdo = new PDO($db_dsn, $db_user, $db_pass, [
      PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
  } catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'message' => 'Errore di connessione al database.']);
    exit;
  }
}

// === Normalizzazione input ===
$trim = static fn($key, $default = '') => isset($_POST[$key]) ? trim((string)$_POST[$key]) : $default;

// Device / brand / model dalla form del wizard
$deviceType  = $trim('device_type');
$brandIdRaw  = $trim('brand_id');
$brandName   = $trim('brand_name');
$modelIdRaw  = $trim('model_id');
$modelName   = $trim('model_name');
$problemSummary = $trim('problem_summary');
$notes          = $trim('notes');

// Slot prenotazione
$preferredDate      = $trim('preferred_date');
$preferredTimeSlot  = $trim('preferred_time_slot');
$dropoffType        = $trim('dropoff_type', 'in_store');

// Dati cliente
$firstName = $trim('firstName');
$lastName  = $trim('lastName');
$email     = $trim('email');
$phone     = $trim('phone');
$company   = $trim('company');

// Var meta
$channel   = $trim('channel', 'web');
$source    = $trim('source', 'form_prenotazione');
$privacy   = !empty($_POST['privacy']) ? 1 : 0;

// Cast ID se presenti
$brandId = ctype_digit($brandIdRaw) ? (int)$brandIdRaw : null;
$modelId = ctype_digit($modelIdRaw) ? (int)$modelIdRaw : null;
$deviceId = null; // quando avremo device_id dal tuo DB lo gestiamo qui

// === Validazione minima server-side ===
$errors = [];

if ($deviceType === '') {
  $errors['device_type'] = 'Seleziona un dispositivo.';
}
if ($brandName === '') {
  $errors['brand_name'] = 'La marca è obbligatoria.';
}
if ($modelName === '') {
  $errors['model_name'] = 'Il modello è obbligatorio.';
}

if ($preferredDate === '') {
  $errors['preferred_date'] = 'Seleziona una data.';
}
if ($preferredTimeSlot === '') {
  $errors['preferred_time_slot'] = 'Seleziona una fascia oraria.';
}
if (!in_array($dropoffType, ['in_store', 'pickup', 'on_site'], true)) {
  $errors['dropoff_type'] = 'Tipo di consegna non valido.';
}

if ($firstName === '') {
  $errors['firstName'] = 'Il nome è obbligatorio.';
}
if ($lastName === '') {
  $errors['lastName'] = 'Il cognome è obbligatorio.';
}
if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
  $errors['email'] = 'Email non valida.';
}
if ($phone === '') {
  $errors['phone'] = 'Il telefono è obbligatorio.';
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

// === Insert nel DB ===
try {
  $sql = "
    INSERT INTO repair_bookings (
      created_at,
      channel, source,
      device_type, device_id,
      brand_id, brand_name,
      model_id, model_name,
      problem_summary, notes,
      preferred_date, preferred_time_slot, dropoff_type,
      customer_first_name, customer_last_name, customer_email, customer_phone, customer_company,
      privacy_accepted, status
    ) VALUES (
      NOW(),
      :channel, :source,
      :device_type, :device_id,
      :brand_id, :brand_name,
      :model_id, :model_name,
      :problem_summary, :notes,
      :preferred_date, :preferred_time_slot, :dropoff_type,
      :first_name, :last_name, :email, :phone, :company,
      :privacy_accepted, :status
    )
  ";

  $stmt = $pdo->prepare($sql);
  $stmt->execute([
    ':channel'            => $channel ?: 'web',
    ':source'             => $source ?: 'form_prenotazione',
    ':device_type'        => $deviceType,
    ':device_id'          => $deviceId,
    ':brand_id'           => $brandId,
    ':brand_name'         => $brandName,
    ':model_id'           => $modelId,
    ':model_name'         => $modelName,
    ':problem_summary'    => $problemSummary !== '' ? $problemSummary : null,
    ':notes'              => $notes !== '' ? $notes : null,
    ':preferred_date'     => $preferredDate,
    ':preferred_time_slot'=> $preferredTimeSlot,
    ':dropoff_type'       => $dropoffType,
    ':first_name'         => $firstName,
    ':last_name'          => $lastName,
    ':email'              => $email,
    ':phone'              => $phone,
    ':company'            => $company !== '' ? $company : null,
    ':privacy_accepted'   => $privacy ? 1 : 0,
    ':status'             => 'pending',
  ]);

  $bookingId = (int)$pdo->lastInsertId();

  // === INVIO EMAIL (se la funzione esiste) ===
  $mailResult = null;
  if (function_exists('send_assistance_email')) {
      // Costruiamo descrizione per la mail
      $mailDesc = "Nuova Prenotazione Riparazione #{$bookingId}\n\n";
      $mailDesc .= "Dispositivo: " . ucfirst($deviceType) . " {$brandName} {$modelName}\n";
      $mailDesc .= "Data richiesta: {$preferredDate}\n";
      $mailDesc .= "Fascia oraria: {$preferredTimeSlot}\n";
      $mailDesc .= "Modalità consegna: {$dropoffType}\n\n";
      $mailDesc .= "Problema:\n{$problemSummary}\n";
      if ($notes !== '') {
          $mailDesc .= "\nNote:\n{$notes}\n";
      }

      $mailData = [
          'assistance_type'     => 'PRENOTAZIONE',
          'name'                => trim($firstName . ' ' . $lastName),
          'phone'               => $phone,
          'email'               => $email,
          'device_type'         => ucfirst($deviceType) . ' ' . $brandName . ' ' . $modelName,
          'address'             => '', // non serve
          'problem_description' => $mailDesc,
          'urgency'             => 'normale',
          'time_preference'     => $preferredTimeSlot,
      ];

      $mailOpts = [
          'subject'  => 'Prenotazione Riparazione - ' . trim($firstName . ' ' . $lastName),
          'reply_to' => $email ?: null,
      ];

      $mailResult = send_assistance_email($mailData, $mailOpts);
  }

  echo json_encode([
    'ok'        => true,
    'id'        => $bookingId,
    'message'   => 'Prenotazione registrata con successo.',
    'mail_sent' => $mailResult
  ]);
  exit;

} catch (Throwable $e) {
  // Logga l’errore se hai un logger
  // error_log($e->getMessage());
  http_response_code(500);
  echo json_encode([
    'ok'      => false,
    'message' => 'Errore durante il salvataggio della prenotazione.'
  ]);
  exit;
}
