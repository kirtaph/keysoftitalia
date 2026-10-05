<?php
/**
 * process_quote.php
 * mode=preview : calcola la stima ufficiale (usa price_rules + notes)
 * mode=save    : ricalcola la stima e salva in quotes
 */

declare(strict_types=1);

if (!defined('BASE_PATH')) {
  define('BASE_PATH', dirname(__DIR__, 2) . DIRECTORY_SEPARATOR);
}
require_once BASE_PATH . 'config/config.php';

header('Content-Type: application/json; charset=utf-8');

function respond(array $data, int $status=200){
  http_response_code($status);
  echo json_encode($data, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
  exit;
}

// ————————————————————————————————
// PDO dal tuo config (flessibile)
function get_pdo(): PDO {
  if (isset($GLOBALS['pdo']) && $GLOBALS['pdo'] instanceof PDO) return $GLOBALS['pdo'];
  if (function_exists('db')) {
    $d = db();
    if ($d instanceof PDO) return $d;
    if (is_object($d) && property_exists($d, 'pdo') && $d->{'pdo'} instanceof PDO) return $d->{'pdo'};
    if (is_array($d) && isset($d['pdo']) && $d['pdo'] instanceof PDO) return $d['pdo'];
  }
  if (defined('DB_HOST') && defined('DB_NAME') && defined('DB_USER')) {
    $dsn = 'mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8mb4';
    $pdo = new PDO($dsn, DB_USER, defined('DB_PASS')?DB_PASS:'', [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    return $pdo;
  }
  throw new RuntimeException('PDO non disponibile.');
}

// ————————————————————————————————
// Helpers DB

require_once BASE_PATH . 'src/QuoteEstimate.php';

// ————————————————————————————————
// Request

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  respond(['ok'=>false,'message'=>'Metodo non consentito'], 405);
}
foreach (['mode', 'wizard_payload', 'device', 'brand', 'brand_id', 'model', 'description',
    'firstName', 'lastName', 'email', 'phone', 'company', 'source'] as $field) {
  if (isset($_POST[$field]) && !is_scalar($_POST[$field])) {
    respond(['ok'=>false,'message'=>'Dati della richiesta non validi.'], 422);
  }
}

$mode = strtolower((string)($_POST['mode'] ?? 'preview'));
if (!in_array($mode, ['preview', 'save'], true)) {
  respond(['ok'=>false,'message'=>'Modalità non valida.'], 422);
}
if ($mode === 'save') {
  $token = $_POST['csrf_token'] ?? '';
  if (!is_string($token) || !validate_csrf_token($token)) {
    respond(['ok'=>false,'message'=>'Token CSRF non valido.'], 403);
  }
  if (!in_array($_POST['privacy'] ?? '', ['on', '1', 'true', 'yes'], true)) {
    respond(['ok'=>false,'message'=>'Devi accettare la Privacy Policy.'], 422);
  }
}

// payload “wizard_payload” (se presente)
$payload = [];
if (!empty($_POST['wizard_payload'])) {
  $tmp = json_decode((string)$_POST['wizard_payload'], true);
  if (is_array($tmp)) $payload = $tmp;
}
foreach (['device', 'brand', 'brand_id', 'model', 'description'] as $field) {
  if (isset($payload[$field]) && !is_scalar($payload[$field])) {
    respond(['ok'=>false,'message'=>'Dati del preventivo non validi.'], 422);
  }
}

$device = strtolower(trim($_POST['device'] ?? ($payload['device'] ?? '')));
$brandText = norm((string)($_POST['brand'] ?? ($payload['brand'] ?? '')));
$brandId   = isset($_POST['brand_id']) && $_POST['brand_id'] !== '' ? (int)$_POST['brand_id'] : (isset($payload['brand_id']) ? (int)$payload['brand_id'] : null);
$modelText = norm((string)($_POST['model'] ?? ($payload['model'] ?? '')));
$desc      = norm((string)($_POST['description'] ?? ($payload['description'] ?? '')));
$issues    = $_POST['issues'] ?? ($payload['problems'] ?? []);

if (is_string($issues)) {
  $tmp = json_decode($issues, true);
  $issues = is_array($tmp) ? $tmp : [$issues];
}
if (!is_array($issues)) $issues = [];
foreach ($issues as $issue) {
  if (!is_string($issue)) respond(['ok'=>false,'message'=>'Problematiche non valide.'], 422);
}
$issues = array_values(array_unique(array_filter(array_map('norm', $issues), static fn($issue) => $issue !== '')));

if ($device === '') respond(['ok'=>false,'message'=>'Device mancante'], 422);
if ($mode === 'preview' && empty($issues)) respond(['ok'=>false,'message'=>'Nessuna problematica selezionata'], 422);

try {
  $pdo = get_pdo();

  if ($mode === 'preview') {
    $est = compute_estimate($pdo, $device, $brandId, $brandText, $modelText, $issues);
    respond(['ok'=>true,'estimate'=>$est,'message'=>'Preview calcolata']);
  }

  // SAVE -------------------------------------------------
  // Dati anagrafici
  $first = norm((string)($_POST['firstName'] ?? ''));
  $last  = norm((string)($_POST['lastName'] ?? ''));
  $email = norm((string)($_POST['email'] ?? ''));
  $phone = norm((string)($_POST['phone'] ?? ''));
  $company = norm((string)($_POST['company'] ?? ''));

  if ($first==='' || $last==='' || $email==='' || $phone===''){
    respond(['ok'=>false,'message'=>'Compila tutti i campi obbligatori.'], 422);
  }
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    respond(['ok'=>false,'message'=>'Email non valida.'], 422);
  }

  $device_id = get_device_id_by_slug($pdo, $device);
  if (!$device_id) respond(['ok'=>false,'message'=>'Device non valido'], 422);

  // ricalcolo server-side (fonte di verità)
  $est = compute_estimate($pdo, $device, $brandId, $brandText, $modelText, $issues);
  $source = strtolower(trim($_POST['source'] ?? 'form'));
  // insert in quotes (schema reale)
  $sql = "INSERT INTO quotes
    (device_id, brand_text, model_text, problems_json, description, est_min, est_max, first_name, last_name, email, phone, company, ip_address, user_agent)
    VALUES (:device_id, :brand_text, :model_text, :problems_json, :description, :est_min, :est_max, :first_name, :last_name, :email, :phone, :company, INET6_ATON(:ip_address), :user_agent)";
  $st = $pdo->prepare($sql);
  $st->execute([
    ':device_id'    => $device_id,
    ':brand_text'   => $brandText !== '' ? $brandText : ($brandId ? '' : 'Altro'),
    ':model_text'   => $modelText !== '' ? $modelText : null,
    ':problems_json'=> json_encode(array_values($issues), JSON_UNESCAPED_UNICODE),
    ':description'  => $desc !== '' ? $desc : null,
    ':est_min'      => $est['min'] ?? null,
    ':est_max'      => $est['max'] ?? null,
    ':first_name'   => $first,
    ':last_name'    => $last,
    ':email'        => $email,
    ':phone'        => $phone,
    ':company'      => $company !== '' ? $company : null,
    ':ip_address'   => $_SERVER['REMOTE_ADDR'] ?? '',
    ':user_agent'   => $_SERVER['HTTP_USER_AGENT'] ?? ''
  ]);

  $id = (int)$pdo->lastInsertId();
// ---- EMAIL (facoltativa): invia solo se la sorgente è "email" e la funzione esiste
$mail_result = null;

if (
  isset($_POST['source']) && $_POST['source'] === 'email' &&
  function_exists('send_assistance_email')
){
  // format stima (usa badge se già presente, altrimenti ricrea)
  $badge = $est['badge'] ?? (
    (!empty($est['max']) && (float)$est['max'] > (float)$est['min'])
      ? ('€ ' . round((float)$est['min']) . '–' . round((float)$est['max']))
      : ('da € ' . round((float)$est['min']))
  );

  // problemi in riga
  $issues_str = '';
  if (!empty($issues) && is_array($issues)) {
    $issues_str = implode(', ', array_map(static function($s){ return trim((string)$s); }, $issues));
  }

  // descrizione da inviare a mail: problemi + nota + stima + id richiesta
  $mail_description = trim(
    ($desc ?: '') . "\n\n" .
    ($issues_str ? "Problemi: {$issues_str}\n" : '') .
    "Stima indicativa: {$badge}\n" .
    "ID richiesta: #{$id}\n"
  );

  // mappa campi verso la tua funzione esistente
  $mail_data = [
    'assistance_type'     => 'PREVENTIVO',
    'name'                => trim($first . ' ' . $last),
    'phone'               => $phone,
    'email'               => $email,
    'device_type'         => strtoupper($device) . ' • ' . ($brandText ?: 'n/d') . ( $modelText ? (' • ' . $modelText) : '' ),
    'address'             => '',
    'problem_description' => $mail_description,
    'urgency'             => 'normale',
    'time_preference'     => 'qualsiasi',
  ];

  // opzionali: destinatario/subject/reply-to personalizzati
  $mail_opts = [
    // se vuoi un indirizzo dedicato ai preventivi, definisci EMAIL_PREVENTIVI nel config
    // 'to'      => defined('EMAIL_PREVENTIVI') ? EMAIL_PREVENTIVI : null,
    'subject' => 'Richiesta Preventivo - ' . trim($first . ' ' . $last),
    'reply_to'=> $email ?: null,
  ];

  $mail_result = send_assistance_email($mail_data, $mail_opts);
}

// ...e nella risposta JSON aggiungi l’esito mail:
respond([
  'ok'       => true,
  'id'       => $id,
  'estimate' => $est,
  'mail'     => $mail_result, // es. {ok:true,error:null} oppure null se non inviata
  'message'  => 'Preventivo salvato'
], 200);

} catch (Throwable $e) {
  error_log('[Preventivo] '.$e->getMessage());
  respond(['ok'=>false,'message'=>'Errore durante il salvataggio del preventivo.'], 500);
}
