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
