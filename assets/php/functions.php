<?php
/**
 * Key Soft Italia - Helper Functions (hardened)
 * Funzioni globali per il sito
 */

use PHPMailer\PHPMailer\PHPMailer;

// === Percorso base del progetto (1 sola fonte di verità) ===
if (!defined('BASE_PATH')) {
    // /assets/php/functions.php -> risale di 2 livelli fino alla root progetto
    define('BASE_PATH', rtrim(str_replace('\\', '/', dirname(dirname(__DIR__))), '/') . '/');
}

// Ensure PHPMailer is loaded
if (file_exists(BASE_PATH . 'vendor/autoload.php')) {
    require_once BASE_PATH . 'vendor/autoload.php';
}

/* ---------------------------------------------------------
 |  URL helpers: BASE_URL robusto anche in sottocartella,
 |  dietro proxy (X-Forwarded-*) e con php -S
 * --------------------------------------------------------*/

/** Rileva schema (http/https) anche dietro proxy */
function _detect_scheme(): string {
    if (!empty($_SERVER['HTTP_X_FORWARDED_PROTO'])) {
        $proto = strtolower(explode(',', $_SERVER['HTTP_X_FORWARDED_PROTO'])[0]);
        return $proto === 'https' ? 'https' : 'http';
    }
    if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') return 'https';
    if (!empty($_SERVER['SERVER_PORT']) && (int)$_SERVER['SERVER_PORT'] === 443) return 'https';
    return 'http';
}

/** Host visibile (rispetta X-Forwarded-Host) */
function _detect_host(): string {
    if (!empty($_SERVER['HTTP_X_FORWARDED_HOST'])) return $_SERVER['HTTP_X_FORWARDED_HOST'];
    if (!empty($_SERVER['HTTP_HOST'])) return $_SERVER['HTTP_HOST'];
    if (!empty($_SERVER['SERVER_NAME'])) return $_SERVER['SERVER_NAME'];
    return 'localhost';
}

/** Path dell’app relativo alla document root (stabile, non dipende dalla sottocartella della pagina) */
function _detect_base_path(): string {
    $docRoot = $_SERVER['DOCUMENT_ROOT'] ?? '';
    $docRoot = $docRoot ? rtrim(str_replace('\\', '/', realpath($docRoot)), '/') : '';
    $appRoot = rtrim(str_replace('\\', '/', realpath(BASE_PATH)), '/');

    if ($docRoot && $appRoot && strpos($appRoot, $docRoot) === 0) {
        $rel = trim(substr($appRoot, strlen($docRoot)), '/');
        return $rel === '' ? '/' : ('/' . $rel . '/');
    }

    // Fallback: usa la dir dello script, ma normalizza alla prima cartella dell'app
    $scriptDir = dirname($_SERVER['SCRIPT_NAME'] ?? '/');
    $scriptDir = rtrim($scriptDir, '/') . '/';
    return $scriptDir ?: '/';
}

/** Definisce BASE_URL se mancante */
function autoDetectBaseUrl(): void {
    if (defined('BASE_URL')) return;
    $scheme = _detect_scheme() . '://';
    $host   = _detect_host();
    $path   = _detect_base_path(); // es.: "/", "/keysoftitalia/"
    define('BASE_URL', $scheme . $host . $path);
}

/** Join pulito tra segmenti URL evitando // doppi */
function _url_join(string $a, string $b): string {
    return rtrim($a, '/') . '/' . ltrim($b, '/');
}

/**
 * Genera URL assoluto con supporto BASE_URL
 * @param string $path  Percorso relativo o assoluto (se http/https lo ritorna com’è)
 * @return string
 */
function url(string $path = ''): string {
    autoDetectBaseUrl();
    if ($path === '' || $path === '/') return BASE_URL;
    if (preg_match('#^https?://#i', $path)) return $path; // già assoluto
    return _url_join(BASE_URL, $path);
}

/**
 * URL per asset statici (css, js, immagini)
 * @param string $path percorso relativo a /assets
 * @return string
 */
function asset(string $path): string {
    return url('assets/' . ltrim($path, '/'));
}

/**
 * URL pagina (alias semantico)
 * @param string $page es. 'chi-siamo.php'
 */
function page_url(string $page): string {
    return url($page);
}

/**
 * Verifica pagina corrente
 */
function is_current_page(string $page): bool {
    $current = basename($_SERVER['SCRIPT_NAME'] ?? '');
    return $current === $page || $current === ($page . '.php');
}

/**
 * Nome pagina corrente senza estensione
 */
function get_current_page(): string {
    return basename($_SERVER['SCRIPT_NAME'] ?? '', '.php');
}

/**
 * Include di un partial con variabili, dalla cartella /includes
 */
function include_partial(string $partial, array $variables = []): void {
    if ($variables) extract($variables, EXTR_SKIP);

    // sicurezza base contro traversal
    if (strpos($partial, '..') !== false) {
        log_error("Directory traversal rilevata in include_partial: {$partial}");
        return;
    }
    $partialPath = BASE_PATH . 'includes/' . ltrim($partial, '/');
    if (is_file($partialPath)) {
        include $partialPath;
    } else {
        error_log("Partial not found: " . $partialPath);
    }
}

/* ---------------------------------------------------------
 |  Utility business/UX
 * --------------------------------------------------------*/

function whatsapp_link(string $message = '', array $utmParams = []): string {
    $whatsappNumber = defined('WHATSAPP_NUMBER') ? WHATSAPP_NUMBER : '393483109840';
    $defaultUtm = ['utm_source' => 'site', 'utm_medium' => 'whatsapp', 'utm_campaign' => 'general'];
    $utm = array_merge($defaultUtm, $utmParams);
    $encodedMessage = urlencode($message);
    $utmString = http_build_query($utm);
    return "https://wa.me/{$whatsappNumber}?text={$encodedMessage}" . ($utmString ? "&{$utmString}" : '');
}

function format_price(float $price, bool $showSymbol = true): string {
    $formatted = number_format($price, 2, ',', '.');
    return $showSymbol ? '€ ' . $formatted : $formatted;
}

function format_phone(string $phone): string {
    $phone = preg_replace('/\D/', '', $phone);
    if (strlen($phone) === 10 && $phone[0] === '0') {
        return substr($phone, 0, 3) . ' ' . substr($phone, 3, 3) . ' ' . substr($phone, 6);
    } elseif (strlen($phone) === 10 && $phone[0] === '3') {
        return substr($phone, 0, 3) . ' ' . substr($phone, 3, 3) . ' ' . substr($phone, 6);
    }
    return $phone;
}

/** tel:+39… in formato E.164 basilare */
function phone_e164(string $phone, string $cc = '39'): string {
    $digits = preg_replace('/\D/', '', $phone);
    if (strpos($digits, $cc) === 0) return '+' . $digits;
    return '+' . $cc . $digits;
}

function sanitize_input($data) {
    if (is_array($data)) return array_map('sanitize_input', $data);
    $data = trim((string)$data);
    $data = stripslashes($data);
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

function is_valid_email(string $email): bool {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

function is_valid_phone(string $phone): bool {
    $digits = preg_replace('/\D/', '', $phone);
    return strlen($digits) >= 9 && strlen($digits) <= 13;
}



function generate_meta_tags(array $meta = []): string {
    $defaults = [
        'title' => "Key Soft Italia - L'universo della Tecnologia",
        'description' => 'Key Soft Italia a Ginosa - Riparazioni in 24h, vendita dispositivi ricondizionati, assistenza informatica, sviluppo web.',
        'keywords' => 'riparazioni smartphone, assistenza computer, ricondizionati, sviluppo web, key soft italia, ginosa, taranto',
        'image' => asset('images/og-image.jpg'),
        'url' => defined('BASE_URL') ? BASE_URL : url(),
    ];
    $m = array_merge($defaults, $meta);

    $h = fn($v) => htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');

    $out  = '<meta name="description" content="' . $h($m['description']) . '">' . "\n";
    $out .= '<meta name="keywords" content="' . $h($m['keywords']) . '">' . "\n";
    $out .= '<meta property="og:title" content="' . $h($m['title']) . '">' . "\n";
    $out .= '<meta property="og:description" content="' . $h($m['description']) . '">' . "\n";
    $out .= '<meta property="og:image" content="' . $h($m['image']) . '">' . "\n";
    $out .= '<meta property="og:url" content="' . $h($m['url']) . '">' . "\n";
    $out .= '<meta property="og:type" content="website">' . "\n";
    $out .= '<meta name="twitter:card" content="summary_large_image">' . "\n";
    $out .= '<meta name="twitter:title" content="' . $h($m['title']) . '">' . "\n";
    $out .= '<meta name="twitter:description" content="' . $h($m['description']) . '">' . "\n";
    $out .= '<meta name="twitter:image" content="' . $h($m['image']) . '">' . "\n";
    return $out;
}

// Carica Composer autoload se presente (meglio farlo in bootstrap generale)
if (defined('BASE_PATH') && file_exists(BASE_PATH . 'vendor/autoload.php')) {
  require_once BASE_PATH . 'vendor/autoload.php';
}

if (!function_exists('send_assistance_email')) {
  function send_assistance_email(array $data, array $opts = []): array {
    // --- pick helper
    $get = static function($k, $def='') use ($data){ return isset($data[$k]) ? trim((string)$data[$k]) : $def; };

    // --- fields
    $tipo   = strtoupper($get('assistance_type','DOMICILIO'));
    $name   = $get('name');
    $phone  = $get('phone');
    $email  = $get('email');
    $device = $get('device_type');
    $addr   = $get('address');
    $prob   = $get('problem_description');
    $urg    = $get('urgency','normale');
    $fascia = $get('time_preference','qualsiasi');

    // --- minimal validation
    if ($name==='' || $phone==='' || $device==='' || $prob==='') return ['ok'=>false,'error'=>'Campi obbligatori mancanti'];
    if ($email!=='' && !filter_var($email, FILTER_VALIDATE_EMAIL)) return ['ok'=>false,'error'=>'Email non valida'];

    // --- headers
    $to        = $opts['to']        ?? (defined('EMAIL_ASSISTENZA') ? EMAIL_ASSISTENZA : 'info@tuodominio.it');
    $from      = $opts['from']      ?? (defined('EMAIL_FROM') ? EMAIL_FROM : 'no-reply@' . ($_SERVER['SERVER_NAME'] ?? 'tuodominio.it'));
    $from_name = $opts['from_name'] ?? (defined('SITE_NAME') ? SITE_NAME : 'Key Soft Italia');
    $reply_to  = $opts['reply_to']  ?? ($email ?: $from);
    $subject   = $opts['subject']   ?? "Richiesta Assistenza ($tipo) - $name";

    // --- bodies
    $row = function($label,$value){
      if ($value==='') return '';
      return '<tr><td style="width:180px;background:#f6f7f9;border:1px solid #e5e7eb;font-weight:600;">'
           . htmlspecialchars($label).'</td><td style="border:1px solid #e5e7eb;">'
           . nl2br(htmlspecialchars($value)).'</td></tr>';
    };
    $html = '<html><body style="font-family:Inter,Arial,sans-serif;font-size:15px;color:#111">';
    $html.= '<h2 style="margin:0 0 8px">Richiesta Assistenza ('.htmlspecialchars($tipo).')</h2>';
    $html.= '<table cellpadding="6" cellspacing="0" style="border-collapse:collapse;width:100%">';
    $html.= $row('Nome',$name).$row('Telefono',$phone).$row('Email',$email).$row('Tipo',$tipo).$row('Dispositivo',$device);
    if ($tipo==='DOMICILIO') $html.= $row('Indirizzo',$addr);
    $html.= $row('Problema',$prob).$row('Urgenza',$urg).$row('Fascia',$fascia).'</table>';
    $html.= '<p style="color:#6b7280;font-size:12px;margin-top:10px;">IP: '.htmlspecialchars($_SERVER['REMOTE_ADDR'] ?? '').'</p>';
    $html.= '</body></html>';

    $plain = "Richiesta Assistenza ($tipo)\n"
           . "Nome: $name\nTelefono: $phone\n".($email ? "Email: $email\n" : '')
           . "Dispositivo: $device\n".(($tipo==='DOMICILIO' && $addr) ? "Indirizzo: $addr\n" : '')
           . "Problema:\n$prob\nUrgenza: $urg | Fascia: $fascia\n";

    // --- transport
    $transport = defined('MAIL_TRANSPORT') ? MAIL_TRANSPORT : 'smtp';

    // SMTP con PHPMailer
    if ($transport==='smtp' && class_exists('\PHPMailer\PHPMailer\PHPMailer')) {
      try {
        $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
        $mail->CharSet  = 'UTF-8';
        $mail->Encoding = 'base64';
        $mail->isSMTP();
        $mail->Host       = defined('SMTP_HOST') ? SMTP_HOST : '';
        $mail->SMTPAuth   = defined('SMTP_AUTH') ? (bool)SMTP_AUTH : true;
        $mail->Username   = defined('SMTP_USER') ? (string)SMTP_USER : '';
        $mail->Password   = defined('SMTP_PASS') ? (string)SMTP_PASS : '';
        $mail->SMTPSecure = (defined('SMTP_SECURE') && SMTP_SECURE) ? (string)SMTP_SECURE : \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = defined('SMTP_PORT') ? (int)SMTP_PORT : 587;
        $mail->Timeout    = 20;

        $mail->setFrom($from, $from_name);
        $mail->addAddress($to);
        if ($reply_to) $mail->addReplyTo($reply_to, $name ?: $from_name);

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $html;
        $mail->AltBody = $plain;

        $mail->send();
        return ['ok'=>true,'error'=>null];
      } catch (\Throwable $e) {
        // se SMTP fallisce, prova mail() come fallback
        error_log('PHPMailer SMTP error: '.$e->getMessage());
      }
    }

    // Fallback: mail() nativa
    $boundary = md5(uniqid('', true));
    $headers  = [];
    $headers[] = 'MIME-Version: 1.0';
    $headers[] = 'From: ' . sprintf('"%s" <%s>', mb_encode_mimeheader($from_name,'UTF-8'), $from);
    if ($reply_to) $headers[] = 'Reply-To: ' . $reply_to;
    $headers[] = 'Content-Type: multipart/alternative; boundary="'.$boundary.'"';
    $headers[] = 'X-Mailer: PHP/'.phpversion();

    $body  = "--$boundary\r\n";
    $body .= "Content-Type: text/plain; charset=UTF-8\r\nContent-Transfer-Encoding: 8bit\r\n\r\n$plain\r\n";
    $body .= "--$boundary\r\n";
    $body .= "Content-Type: text/html; charset=UTF-8\r\nContent-Transfer-Encoding: 8bit\r\n\r\n$html\r\n";
    $body .= "--$boundary--";

    $ok = @mail($to, '=?UTF-8?B?'.base64_encode($subject).'?=', $body, implode("\r\n",$headers));
    return ['ok'=>(bool)$ok, 'error'=>$ok ? null : 'mail() ha restituito false'];
  }
}

function get_excerpt(string $text, int $length = 150, string $suffix = '...'): string {
    $text = strip_tags($text);
    if (mb_strlen($text) <= $length) return $text;
    $excerpt = mb_substr($text, 0, $length);
    $lastSpace = mb_strrpos($excerpt, ' ');
    if ($lastSpace !== false) $excerpt = mb_substr($excerpt, 0, $lastSpace);
    return $excerpt . $suffix;
}

function generate_breadcrumbs(array $items = []): string {
    if (empty($items)) return '';
    $out  = '<nav aria-label="breadcrumb"><ol class="breadcrumb">';
    $out .= '<li class="breadcrumb-item"><a href="' . url() . '">Home</a></li>';
    foreach ($items as $i => $item) {
        $label = htmlspecialchars($item['label'] ?? '', ENT_QUOTES, 'UTF-8');
        $url   = isset($item['url']) ? url($item['url']) : '';
        $last  = ($i === count($items) - 1);
        if ($last || !$url) {
            $out .= '<li class="breadcrumb-item active" aria-current="page">' . $label . '</li>';
        } else {
            $out .= '<li class="breadcrumb-item"><a href="' . $url . '">' . $label . '</a></li>';
        }
    }
    $out .= '</ol></nav>';
    return $out;
}

function debug($data, bool $die = true): void {
    echo '<pre style="background:#222;color:#0f0;padding:10px;margin:10px;border-radius:5px;">';
    print_r($data);
    echo '</pre>';
    if ($die) die();
}

function log_error(string $message, string $file = '', $line = ''): void {
    $logFile = BASE_PATH . 'logs/error.log';
    $logDir  = dirname($logFile);
    if (!is_dir($logDir)) mkdir($logDir, 0755, true);
    $timestamp = date('Y-m-d H:i:s');
    $logMessage = "[{$timestamp}] {$message}" . ($file ? " in {$file}" : '') . ($line ? " on line {$line}" : '') . "\n";
    error_log($logMessage, 3, $logFile);
}

/* ---------------------------------------------------------
 |  CSRF
 * --------------------------------------------------------*/
function generate_csrf_token(): string {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}
function validate_csrf_token(string $token): bool {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}
function csrf_field(): string {
    return '<input type="hidden" name="csrf_token" value="' . generate_csrf_token() . '">';
}
function generate_csrf_field(): string { return csrf_field(); }

/* ---------------------------------------------------------
 |  HTTP helpers
 * --------------------------------------------------------*/
function redirect(string $url, int $statusCode = 302): void {
    header('Location: ' . $url, true, $statusCode);
    exit();
}
function is_ajax_request(): bool {
    return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
}
function json_response(array $data, int $statusCode = 200): void {
    http_response_code($statusCode);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit();
}

// === Session: attiva una volta sola, safe idempotente
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Definisci BASE_URL subito
autoDetectBaseUrl();

/* ===== Opening Hours (contatti.php) — usa config globale ================== */
$KS_TZ = new DateTimeZone(KS_TZ);

// -- DB helpers sicuri (usano $pdo se c'è; altrimenti fallback) --
if (!function_exists('ks_db')) {
  function ks_db(): ?PDO {
    global $pdo;
    return ($pdo instanceof PDO) ? $pdo : null;
  }
}
if (!function_exists('ks_table_exists')) {
  function ks_table_exists(string $t): bool {
    $db = ks_db(); if (!$db) return false;
    try { $db->query("SELECT 1 FROM `$t` LIMIT 1"); return true; }
    catch (Throwable $e) { return false; }
  }
}

// Orari settimanali dal DB (ks_store_hours_weekly)
if (!function_exists('ks_db_weekly_intervals')) {
  function ks_db_weekly_intervals(int $dow): array {
    $db = ks_db(); if (!$db || !ks_table_exists('ks_store_hours_weekly')) return [];
    $q = $db->prepare("SELECT open_time, close_time
                       FROM ks_store_hours_weekly
                       WHERE active=1 AND dow=:d
                       ORDER BY seg ASC");
    $q->execute([':d'=>$dow]);
    $out = [];
    foreach ($q->fetchAll(PDO::FETCH_ASSOC) as $r) {
      $out[] = [substr($r['open_time'],0,5), substr($r['close_time'],0,5)];
    }
    return $out;
  }
}

// Eccezioni puntuali (ks_store_hours_exceptions)
if (!function_exists('ks_db_date_exception')) {
  function ks_db_date_exception(string $ymd): array {
    $db = ks_db(); 
    if (!$db || !ks_table_exists('ks_store_hours_exceptions')) {
      return ['found'=>false];
    }
    try {
      $q = $db->prepare("
        SELECT seg, open_time, close_time, is_closed, notice
        FROM ks_store_hours_exceptions
        WHERE date = :d
        ORDER BY seg ASC
      ");
      $q->execute([':d' => $ymd]);
      $rows = $q->fetchAll(PDO::FETCH_ASSOC);
      if (!$rows) return ['found'=>false];

      $intervals = [];
      $hasIntervals = false;
      $allClosed = false;
      $notice = null;

      foreach ($rows as $r) {
        // prendi la prima notice non vuota
        if ($notice === null && isset($r['notice'])) {
          $n = trim((string)$r['notice']);
          if ($n !== '') $notice = $n;
        }

        // chiusura totale
        if ((int)$r['is_closed'] === 1 && $r['open_time'] === null && $r['close_time'] === null) {
          $allClosed = true;
          continue;
        }

        // intervalli validi
        if ($r['open_time'] !== null && $r['close_time'] !== null) {
          $hasIntervals = true;
          $intervals[] = [ substr($r['open_time'],0,5), substr($r['close_time'],0,5) ];
        }
      }

      if ($hasIntervals) return ['found'=>true, 'intervals'=>$intervals, 'notice'=>$notice, 'source'=>'exception'];
      if ($allClosed)    return ['found'=>true, 'intervals'=>[],        'notice'=>$notice, 'source'=>'exception'];
      // solo notice
      return ['found'=>true, 'intervals'=>null,   'notice'=>$notice, 'source'=>'exception'];

    } catch (Throwable $e) {
      return ['found'=>false];
    }
  }
}

/* EASTER date (per regole ricorrenti) */
if (!function_exists('ks_easter_date')) {
  function ks_easter_date(int $year, ?DateTimeZone $tz = null): DateTime {
    $tz = $tz ?: new DateTimeZone(defined('KS_TZ') ? KS_TZ : 'Europe/Rome');
    // algoritmo anonimo gregoriano
    $a=$year%19;$b=intdiv($year,100);$c=$year%100;$d=intdiv($b,4);$e=$b%4;$f=intdiv($b+8,25);
    $g=intdiv($b-$f+1,3);$h=(19*$a+$b-$d-$g+15)%30;$i=intdiv($c,4);$k=$c%4;$l=(32+2*$e+2*$i-$h-$k)%7;
    $m=intdiv($a+11*$h+22*$l,451);$month=intdiv($h+$l-7*$m+114,31);$day=(($h+$l-7*$m+114)%31)+1;
    return (new DateTime("$year-$month-$day 00:00:00", $tz));
  }
}

/* HOLIDAYS ricorrenti da DB */
if (!function_exists('ks_holiday_rule_for_date')) {
  function ks_holiday_rule_for_date(DateTime $d): ?array {
    $db = ks_db(); 
    if (!$db || !ks_table_exists('ks_store_holidays')) return null;

    $y = (int)$d->format('Y');
    $m = (int)$d->format('n');
    $day = (int)$d->format('j');

    try {
      // FISSE (mese/giorno)
      $q1 = $db->prepare("
        SELECT name, is_closed, notice
        FROM ks_store_holidays
        WHERE active=1 AND rule_type='fixed' AND month=:m AND day=:d
        LIMIT 1
      ");
      $q1->execute([':m'=>$m, ':d'=>$day]);
      if ($row = $q1->fetch(PDO::FETCH_ASSOC)) {
        $notice = trim((string)($row['notice'] ?? ''));
        if ($notice === '') $notice = (string)$row['name'];
        return ['closed'=>(bool)$row['is_closed'], 'notice'=>$notice];
      }

      // PASQUA (offset_days da Pasqua)
      $easter = ks_easter_date($y, $d->getTimezone()); // 00:00 del giorno di Pasqua
      $target = (clone $d)->setTime(0,0,0);
      $diffDays = (int)$easter->diff($target)->format('%r%a');

      $q2 = $db->query("
        SELECT offset_days, name, is_closed, notice
        FROM ks_store_holidays
        WHERE active=1 AND rule_type='easter'
      ");
      foreach ($q2->fetchAll(PDO::FETCH_ASSOC) as $r) {
        if ((int)$r['offset_days'] === $diffDays) {
          $notice = trim((string)($r['notice'] ?? ''));
          if ($notice === '') $notice = (string)$r['name'];
          return ['closed'=>(bool)$r['is_closed'], 'notice'=>$notice];
        }
      }
      return null;

    } catch (Throwable $e) {
      return null;
    }
  }
}

if (!function_exists('ks_date_for_iso_dow')) {
  function ks_date_for_iso_dow(DateTime $ref, int $dow): DateTime {
    $cur = (int)$ref->format('N');
    $diff = $dow - $cur;
    return (clone $ref)->modify(($diff >= 0 ? '+' : '') . $diff . ' day');
  }
}

if (!function_exists('ks_hours_notice_for_date')) {
  function ks_hours_notice_for_date(DateTime $date): ?string {
    // 1) Eccezione puntualizzata (DB)
    if (function_exists('ks_db_date_exception')) {
      $exc = ks_db_date_exception($date->format('Y-m-d'));
      if (!empty($exc['found']) && !empty($exc['notice'])) return $exc['notice'];
    }
    // 2) Festività ricorrenti (DB)
    if (function_exists('ks_holiday_rule_for_date')) {
      $hol = ks_holiday_rule_for_date($date);
      if ($hol && !empty($hol['notice'])) return $hol['notice'];
    }
    // 3) Fallback alla tua mappa array
    $map = ks_store_hours_notices();
    $k = $date->format('Y-m-d');
    return $map[$k] ?? null;
  }
}

/** Helpers base **/
function ks_dt_at(DateTime $base, string $hm): DateTime {
  [$h,$m] = array_map('intval', explode(':', $hm));
  $d = (clone $base); $d->setTime($h,$m,0);
  return $d;
}
function ks_day_label(int $N): string {
  static $d = [1=>'Lunedì',2=>'Martedì',3=>'Mercoledì',4=>'Giovedì',5=>'Venerdì',6=>'Sabato',7=>'Domenica'];
  return $d[$N] ?? '';
}
function ks_human_diff(DateTime $from, DateTime $to): string {
  $i = $from->diff($to);
  $parts = [];
  if ($i->d>0) $parts[] = $i->d.'g';
  if ($i->h>0) $parts[] = $i->h.'h';
  if ($i->i>0) $parts[] = $i->i.'m';
  return $parts ? implode(' ', $parts) : 'meno di 1m';
}

/** Orari per una data specifica (DB-first con fallback agli array di config) */
function ks_intervals_for_date(DateTime $date): array {
  // --- Eccezione puntuale da DB
  if (function_exists('ks_db_date_exception')) {
    $excDb = ks_db_date_exception($date->format('Y-m-d'));
    if ($excDb['found']) {
      if (is_array($excDb['intervals'])) return $excDb['intervals']; // override completo
      if ($excDb['intervals'] === [])    return [];                   // chiuso tutto il giorno
      // solo notice -> continua
    }
  }

  // 2) Weekly da DB (se presente)
  $weekly = ks_db_weekly_intervals((int)$date->format('N'));
  if (!empty($weekly)) return $weekly;

  // 3) Fallback: i TUOI array di config (come avevi già)
  $base = ks_store_hours_base();
  $excA = ks_store_hours_exceptions(); // eccezioni hardcoded (opzionali)
  $key  = $date->format('Y-m-d');
  if (array_key_exists($key, $excA)) return $excA[$key];
  $dow = (int)$date->format('N');
  return $base[$dow] ?? [];
}

/** Stato apertura adesso */
function ks_is_open_now(DateTime $now): array {
  $intervals = ks_intervals_for_date($now);
  foreach ($intervals as [$s,$e]) {
    $start = ks_dt_at($now, $s);
    $end   = ks_dt_at($now, $e);
    if ($now >= $start && $now < $end) {
      return ['open'=>true, 'start'=>$start, 'end'=>$end];
    }
  }
  return ['open'=>false, 'start'=>null, 'end'=>null];
}

/** Prossima apertura (oggi più prossimi 14 giorni) */
function ks_next_open_after(DateTime $now, int $horizonDays = 14): ?DateTime {
  // oggi: slot successivi
  foreach (ks_intervals_for_date($now) as [$s, $e]) {
    $start = ks_dt_at($now, $s);
    if ($start > $now) return $start;
  }
  // giorni successivi
  for ($i=1; $i<=max(1,$horizonDays); $i++) {
    $d = (clone $now)->modify("+$i day");
    $list = ks_intervals_for_date($d);
    if (!empty($list)) {
      return ks_dt_at($d, $list[0][0]);
    }
  }
  return null;
}

/** Formatta intervalli in stringa */
function ks_format_intervals(array $intervals): string {
  if (empty($intervals)) return '<span class="text-danger">Chiuso</span>';
  $fmt = array_map(fn($p)=> sprintf('%s - %s', $p[0], $p[1]), $intervals);
  return implode(' / ', $fmt);
}

/* Stato attuale + prossima variazione */
$__now     = new DateTime('now', $KS_TZ);
$__state   = ks_is_open_now($__now);
$__noticeMap = ks_store_hours_notices();
$__todayKey  = $__now->format('Y-m-d');
$__todayNotice = ks_hours_notice_for_date($__now);

$__chip_open   = $__state['open'];
$__chip_class  = $__chip_open ? 'oh-chip--open' : 'oh-chip--closed';
$__chip_icon   = $__chip_open ? 'ri-checkbox-circle-line' : 'ri-close-circle-line';
$__chip_label  = $__chip_open ? 'Aperti ora' : 'Chiusi ora';

if ($__state['open']) {
  $__note = 'Chiude tra ' . ks_human_diff($__now, $__state['end']) . ' (alle ' . $__state['end']->format('H:i') . ')';
} else {
  $nxt = ks_next_open_after($__now);
  $__note = $nxt ? 'Riapre ' . ($nxt->format('Ymd')===$__now->format('Ymd') ? 'alle ' : ks_day_label((int)$nxt->format('N')).' alle ')
             . $nxt->format('H:i') . ' (tra ' . ks_human_diff($__now,$nxt) . ')' : '';
}

/* Per la tabella: base settimanale, ma sostituisci “oggi” con l’eventuale eccezione */
$__base = ks_store_hours_base();
$__table = [];
for ($d=1; $d<=7; $d++) {
  $dateForRow = ks_date_for_iso_dow($__now, $d);        // data reale per quel day-of-week
  $__table[$d] = ks_intervals_for_date($dateForRow);    // <-- legge DB + fallback
}

function ks_hours_notice_for_date(DateTime $date): ?string {
  $exc = ks_db_date_exception($date->format('Y-m-d'));
  if (!empty($exc['found'])) {
    $n = isset($exc['notice']) ? trim((string)$exc['notice']) : '';
    if ($n !== '') return $n;
  }

  $hol = ks_holiday_rule_for_date($date);
  if ($hol) {
    $n = isset($hol['notice']) ? trim((string)$hol['notice']) : '';
    if ($n !== '') return $n;
  }

  // Fallback agli array
  $map = ks_store_hours_notices();
  $k = $date->format('Y-m-d');
  $n = isset($map[$k]) ? trim((string)$map[$k]) : '';
  return $n !== '' ? $n : null;
}

// Costruisce la tabella settimanale (DB-first grazie a ks_intervals_for_date)
if (!function_exists('ks_build_week_table')) {
  function ks_build_week_table(DateTime $ref): array {
    $out = [];
    for ($d=1; $d<=7; $d++) {
      $date = ks_date_for_iso_dow($ref, $d);
      $out[$d] = ks_intervals_for_date($date);
    }
    return $out;
  }
}