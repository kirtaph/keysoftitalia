<?php
declare(strict_types=1);
session_start();

require_once __DIR__ . '/../config/config.php'; // $pdo, costanti, helpers
require_once __DIR__ . '/../assets/php/functions.php'; // $pdo, costanti, helpers

header('Content-Type: application/json; charset=UTF-8');


$respond = function (bool $ok, string $msg, array $extra = [], int $code = 200) {
  http_response_code($code);
  echo json_encode(array_merge(['success' => $ok, 'message' => $msg], $extra));
  exit;
};

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  $respond(false, 'Metodo non consentito.', [], 405);
}

// --- CSRF
if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
  if (is_ajax_request()) {
    http_response_code(403);
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode(['ok'=>false, 'error'=>'csrf']);
    exit;
  }
  header('Location: ' . url('errore.php?code=csrf')); exit;
}

/* Honeypot: aggiungi nel form <input type="text" name="website" class="d-none" autocomplete="off"> */
if (!empty($_POST['website'] ?? '')) {
  // Risposta "ok" silenziosa per i bot
  $respond(true, 'Grazie! Ti ricontatteremo a breve.');
}

/* Anti-flood base: max 5 invii/10 minuti per sessione */
$_SESSION['contact_flood'] = array_filter($_SESSION['contact_flood'] ?? [], fn($t) => $t > time() - 600);
if (count($_SESSION['contact_flood']) >= 5) {
  $respond(false, 'Hai inviato troppi messaggi. Riprova più tardi.', [], 429);
}

/* Helpers */
$clean = static function ($v, int $max = 500) {
  $v = trim((string)$v);
  $v = preg_replace('/\s+/u', ' ', $v);
  return mb_substr($v, 0, $max);
};
$phoneClean = static function ($p) {
  $p = preg_replace('/[^\d+\s]/', '', (string)$p);
  return mb_substr(trim($p), 0, 30);
};
$emailValid = static fn($e) => (bool)filter_var($e, FILTER_VALIDATE_EMAIL);

/* Input */
$name    = $clean($_POST['name']    ?? '', 80);
$surname = $clean($_POST['surname'] ?? '', 80);
$email   = $clean($_POST['email']   ?? '', 190);
$phone   = $phoneClean($_POST['phone'] ?? '');
$subject = strtolower($clean($_POST['subject'] ?? 'altro', 30));
$message = $clean($_POST['message'] ?? '', 4000);
$privacy = isset($_POST['privacy']) && in_array($_POST['privacy'], ['on','1','true','yes'], true);

/* Validazione minima */
$errors = [];
if (mb_strlen($name) < 2)        $errors['name']    = 'Nome troppo corto.';
if (mb_strlen($surname) < 2)     $errors['surname'] = 'Cognome troppo corto.';
if (!$emailValid($email))        $errors['email']   = 'Email non valida.';
if (mb_strlen($message) < 10)    $errors['message'] = 'Messaggio troppo breve.';
if (!$privacy)                   $errors['privacy'] = 'Devi accettare la privacy policy.';
if ($errors) {
  $respond(false, 'Controlla i campi evidenziati.', ['errors' => $errors], 422);
}

/* Mappa subject -> tipo */
$subjectMap = [
  'riparazione' => 'RIPARAZIONE',
  'preventivo'  => 'PREVENTIVO',
  'assistenza'  => 'ASSISTENZA TECNICA',
  'vendita'     => 'INFORMAZIONI VENDITA',
  'altro'       => 'ALTRO',
];
$tipo = $subjectMap[$subject] ?? 'ALTRO';

/* Payload per send_assistance_email (usa i campi della tua funzione) */
$payload = [
  // campi attesi dalla tua funzione
  'assistance_type'   => $tipo,                           // es. DOMICILIO/LAB/… qui usiamo la categoria richiesta
  'name'              => trim($name . ' ' . $surname),
  'phone'             => $phone,
  'email'             => $email,
  'device_type'       => 'N/D',                           // non presente nel form contatti
  'address'           => '',                              // non presente nel form contatti
  'problem_description'=> $message,                       // testo del messaggio
  'urgency'           => 'normale',
  'time_preference'   => 'qualsiasi',

  // extra utili
  'subject'           => $tipo,
  'source'            => 'Form Contatti Sito',
];

/* Opzioni aggiuntive (se la tua funzione le supporta) */
$tz  = defined('KS_TZ') ? KS_TZ : 'Europe/Rome';
$opts = [
  'meta'    => [
    'ip'        => $_SERVER['REMOTE_ADDR'] ?? '',
    'ua'        => $_SERVER['HTTP_USER_AGENT'] ?? '',
    'referer'   => $_SERVER['HTTP_REFERER'] ?? '',
    'submitted' => (new DateTime('now', new DateTimeZone($tz)))->format('Y-m-d H:i:s'),
  ],
  // 'subject' => "[Contatti] {$tipo} – {$name} {$surname}", // se la tua funzione accetta override del subject
];

try {
  if (!function_exists('send_assistance_email')) {
    throw new RuntimeException('Funzione send_assistance_email non disponibile.');
  }

  $res = send_assistance_email($payload, $opts);

  // Normalizziamo il risultato in (success,message)
  $ok  = is_array($res) ? ($res['success'] ?? $res['ok'] ?? false) : (bool)$res;
  $msg = is_array($res) ? ($res['message'] ?? ($ok ? 'Messaggio inviato con successo!' : 'Errore durante l’invio.')) 
                        : ($ok ? 'Messaggio inviato con successo!' : 'Errore durante l’invio.');

  $_SESSION['contact_flood'][] = time();

  if ($ok) {
    $respond(true, $msg);
  }
  $respond(false, $msg, [], 200);

} catch (Throwable $e) {
  // error_log('send_assistance_email failed: '.$e->getMessage());
  $respond(false, 'Si è verificato un problema con l’invio. Riprova tra poco o contattaci telefonicamente.', [], 500);
}