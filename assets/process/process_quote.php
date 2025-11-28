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

function norm(string $s): string { return trim(preg_replace('/\s+/u',' ', $s)); }

function get_device_id_by_slug(PDO $pdo, string $slug): ?int {
  $st = $pdo->prepare("SELECT id FROM devices WHERE slug = ? LIMIT 1");
  $st->execute([$slug]);
  $id = $st->fetchColumn();
  return $id ? (int)$id : null;
}

function get_brand_id(PDO $pdo, int $device_id, ?int $brand_id, ?string $brand_text): ?int {
  if ($brand_id) return $brand_id;
  $name = norm((string)$brand_text);
  if ($name === '' || mb_strtolower($name,'UTF-8') === 'altro') return null;
  // cerco per name (case-insensitive) entro device_id
  $st = $pdo->prepare("SELECT id FROM brands WHERE device_id = ? AND (name = ? OR LOWER(name) = LOWER(?)) LIMIT 1");
  $st->execute([$device_id, $name, $name]);
  $id = $st->fetchColumn();
  return $id ? (int)$id : null;
}

function get_model_id(PDO $pdo, ?int $brand_id, ?string $model_text): ?int {
  if (!$brand_id) return null;
  $name = norm((string)$model_text);
  if ($name === '') return null;
  $st = $pdo->prepare("SELECT id FROM models WHERE brand_id = ? AND (name = ? OR LOWER(name) = LOWER(?)) LIMIT 1");
  $st->execute([$brand_id, $name, $name]);
  $id = $st->fetchColumn();
  return $id ? (int)$id : null;
}

function get_issue_id(PDO $pdo, int $device_id, string $label): ?int {
  $name = norm($label);
  $st = $pdo->prepare("SELECT id FROM issues WHERE device_id = ? AND (label = ? OR LOWER(label)=LOWER(?)) LIMIT 1");
  $st->execute([$device_id, $name, $name]);
  $id = $st->fetchColumn();
  return $id ? (int)$id : null;
}

/**
 * Ritorna la regola più specifica disponibile.
 * matched: device+brand+model+issue | device+brand+issue | device+issue
 */
function find_rule(PDO $pdo, int $device_id, ?int $brand_id, ?int $model_id, int $issue_id): ?array {
  // 1) device+brand+model+issue
  if ($brand_id && $model_id){
    $st = $pdo->prepare("SELECT min_price,max_price,notes FROM price_rules WHERE is_active=1 AND device_id=? AND brand_id=? AND model_id=? AND issue_id=? LIMIT 1");
    $st->execute([$device_id,$brand_id,$model_id,$issue_id]);
    if ($r = $st->fetch()) return $r + ['matched'=>'device+brand+model+issue'];
  }
  // 2) device+brand+issue
  if ($brand_id){
    $st = $pdo->prepare("SELECT min_price,max_price,notes FROM price_rules WHERE is_active=1 AND device_id=? AND brand_id=? AND model_id IS NULL AND issue_id=? LIMIT 1");
    $st->execute([$device_id,$brand_id,$issue_id]);
    if ($r = $st->fetch()) return $r + ['matched'=>'device+brand+issue'];
  }
  // 3) device+issue
  $st = $pdo->prepare("SELECT min_price,max_price,notes FROM price_rules WHERE is_active=1 AND device_id=? AND brand_id IS NULL AND model_id IS NULL AND issue_id=? LIMIT 1");
  $st->execute([$device_id,$issue_id]);
  if ($r = $st->fetch()) return $r + ['matched'=>'device+issue'];

  return null;
}

/**
 * Classifica la riga (fisso/range/da) in base a valori + notes
 */
function classify_rule(?string $min, ?string $max, ?string $notes): string {
  $minv = is_null($min) ? null : (float)$min;
  $maxv = is_null($max) ? null : (float)$max;
  $n = mb_strtolower((string)$notes, 'UTF-8');

  if ($maxv === null || $maxv == 0 || strpos($n, 'a partire') !== false || preg_match('/(^|\W)da($|\W)/u', $n)) {
    return 'from';
  }
  if ($minv !== null && $maxv !== null) {
    if (abs($minv - $maxv) < 0.005 || strpos($n,'fisso') !== false) return 'fixed';
    return 'range';
  }
  return 'unknown';
}

/**
 * Calcola la stima totale sulle issues selezionate con somma smart:
 * - se c'è almeno un "da": totale = "da € X" (niente max)
 * - altrimenti range/fisso con somma min/max
 */
function compute_estimate(PDO $pdo, string $device_slug, ?int $brand_id_in, string $brand_text, string $model_text, array $issues): array {
  $device_id = get_device_id_by_slug($pdo, $device_slug);
  if (!$device_id) {
    return ['type'=>'unknown','min'=>0,'max'=>null,'badge'=>'da € 0','breakdown'=>[],'currency'=>'EUR'];
  }

  $brand_id = get_brand_id($pdo, $device_id, $brand_id_in, $brand_text);
  $model_id = get_model_id($pdo, $brand_id, $model_text);

  $sum_fixed = 0.0;
  $sum_range_min = 0.0;
  $sum_range_max = 0.0;
  $sum_from_min  = 0.0;
  $has_from = false;
  $breakdown = [];

  foreach ($issues as $label) {
    $label = norm((string)$label);
    if ($label === '') continue;

    $issue_id = get_issue_id($pdo, $device_id, $label);
    if (!$issue_id){
      $breakdown[] = ['issue'=>$label,'kind'=>'unknown','min'=>null,'max'=>null,'matched'=>null,'notes'=>null];
      continue;
    }

    $rule = find_rule($pdo, $device_id, $brand_id, $model_id, $issue_id);
    if (!$rule){
      $breakdown[] = ['issue'=>$label,'kind'=>'unknown','min'=>null,'max'=>null,'matched'=>null,'notes'=>null];
      continue;
    }

    $min = isset($rule['min_price']) ? (float)$rule['min_price'] : null;
    $max = isset($rule['max_price']) ? (float)$rule['max_price'] : null;
    $kind = classify_rule($rule['min_price'] ?? null, $rule['max_price'] ?? null, $rule['notes'] ?? null);

    if ($kind === 'fixed') {
      $sum_fixed += $min;
    } elseif ($kind === 'range') {
      $sum_range_min += $min;
      $sum_range_max += ($max ?? $min);
    } elseif ($kind === 'from') {
      $sum_from_min += ($min ?? 0);
      $has_from = true;
    }

    $breakdown[] = [
      'issue'   => $label,
      'kind'    => $kind,
      'min'     => $min,
      'max'     => $max,
      'matched' => $rule['matched'],
      'notes'   => $rule['notes'] ?? null
    ];
  }

  $total_min = round($sum_fixed + $sum_range_min + $sum_from_min);
  $total_max = $has_from ? null : round($sum_fixed + $sum_range_max);

  if ($has_from) {
    $type  = 'from';
    $badge = 'da € '.$total_min;
  } else {
    if ($total_max === null || $total_min === $total_max) {
      $type = 'fixed';
      $badge = '€ '.$total_min;
      $total_max = $total_min;
    } else {
      $type = 'range';
      $badge = '€ '.$total_min.'–'.$total_max;
    }
  }

  return [
    'type'      => $type,
    'min'       => $total_min,
    'max'       => $total_max,
    'badge'     => $badge,
    'currency'  => 'EUR',
    'breakdown' => $breakdown
  ];
}

// ————————————————————————————————
// Request

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  respond(['ok'=>false,'message'=>'Metodo non consentito'], 405);
}

$mode = strtolower((string)($_POST['mode'] ?? 'preview'));

// payload “wizard_payload” (se presente)
$payload = [];
if (!empty($_POST['wizard_payload'])) {
  $tmp = json_decode((string)$_POST['wizard_payload'], true);
  if (is_array($tmp)) $payload = $tmp;
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
  respond(['ok'=>false,'message'=>'Errore server: '.$e->getMessage()], 500);
}
