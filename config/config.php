<?php
/**
 * Key Soft Italia - Configuration File
 * Carica costanti globali + istanzia $pdo (PDO MySQL)
 */

// BASE_PATH: definito in functions.php; fallback qui se serve
if (!defined('BASE_PATH')) {
    define('BASE_PATH', rtrim(str_replace('\\', '/', dirname(__DIR__)), '/') . '/');
}

// Helpers (funzioni comuni, autoDetectBaseUrl, ecc.)
require_once BASE_PATH . 'assets/php/functions.php';

// Se vuoi forzare manualmente la sottocartella, decommenta questa riga:
// define('BASE_URL', 'https://example.com/keysoftitalia/');

// Altrimenti auto-detect
if (!defined('BASE_URL')) {
    autoDetectBaseUrl();
}

/* ==========================================================================
   DATI SITO / AZIENDA
   ========================================================================== */

if (!defined('SITE_NAME'))          define('SITE_NAME', 'Key Soft Italia');
if (!defined('SITE_TAGLINE'))       define('SITE_TAGLINE', "L'universo della Tecnologia");

if (!defined('COMPANY_NAME'))       define('COMPANY_NAME', 'Key Soft Italia');
if (!defined('COMPANY_PHONE'))      define('COMPANY_PHONE', '099 829 3794');
if (!defined('COMPANY_WHATSAPP'))   define('COMPANY_WHATSAPP', '348 310 9840');
if (!defined('COMPANY_EMAIL'))      define('COMPANY_EMAIL', 'info@keysoftitalia.it');
if (!defined('COMPANY_ADDRESS'))    define('COMPANY_ADDRESS', 'Via Diaz, 46');
if (!defined('COMPANY_CITY'))       define('COMPANY_CITY', 'Ginosa');
if (!defined('COMPANY_ZIP'))        define('COMPANY_ZIP', '74013');
if (!defined('COMPANY_PROVINCE'))   define('COMPANY_PROVINCE', 'TA');

if (!defined('COMPANY_FULL_ADDRESS')) {
    define('COMPANY_FULL_ADDRESS', COMPANY_ADDRESS . ' - ' . COMPANY_ZIP . ' ' . COMPANY_CITY . ' (' . COMPANY_PROVINCE . ')');
}
if (!defined('ADDRESS')) {
    define('ADDRESS', COMPANY_FULL_ADDRESS);
}

// Orari apertura
if (!defined('OPENING_HOURS')) {
    define('OPENING_HOURS', [
        'monday'    => ['open' => '09:00', 'close' => '19:00'],
        'tuesday'   => ['open' => '09:00', 'close' => '19:00'],
        'wednesday' => ['open' => '09:00', 'close' => '19:00'],
        'thursday'  => ['open' => '09:00', 'close' => '19:00'],
        'friday'    => ['open' => '09:00', 'close' => '19:00'],
        'saturday'  => ['open' => '09:00', 'close' => '13:00'],
        'sunday'    => 'chiuso'
    ]);
}

/* ==========================================================================
   GOOGLE ANALYTICS
   ========================================================================== */
if (!defined('GA_MEASUREMENT_ID')) {
    define('GA_MEASUREMENT_ID', 'G-1QG00HRZK8'); // TODO: cambia con il tuo se serve
}

/* ==========================================================================
   DEBUG / AMBIENTE
   ========================================================================== */
if (!defined('DEBUG_MODE')) define('DEBUG_MODE', true);

if (!defined('APP_ENV'))        define('APP_ENV', 'prod');
if (!defined('MAIL_TRANSPORT')) define('MAIL_TRANSPORT', 'smtp'); // usa SMTP in produzione

/* ==========================================================================
   SMTP
   ========================================================================== */
if (!defined('SMTP_HOST'))   define('SMTP_HOST',   'smtp.keysoftitalia.it'); // es: smtps.aruba.it
if (!defined('SMTP_PORT'))   define('SMTP_PORT',   587);                     // 587 (TLS) o 465 (SSL)
if (!defined('SMTP_SECURE')) define('SMTP_SECURE', 'tls');                   // 'tls' o 'ssl'
if (!defined('SMTP_AUTH'))   define('SMTP_AUTH',   true);
if (!defined('SMTP_USER'))   define('SMTP_USER',   'no-reply@keysoftitalia.it');
if (!defined('SMTP_PASS'))   define('SMTP_PASS',   '8CGYYJQr2024!');

if (!defined('EMAIL_ASSISTENZA')) define('EMAIL_ASSISTENZA', 'info@keysoftitalia.it');
if (!defined('EMAIL_FROM'))       define('EMAIL_FROM',       'no-reply@keysoftitalia.it');

if (!defined('PHONE_PRIMARY'))    define('PHONE_PRIMARY',   '099 829 3794');   // usato per tel:
if (!defined('PHONE_WHATSAPP'))   define('PHONE_WHATSAPP',  '393483109840');   // per wa.me

if (!defined('PHONE_SECONDARY'))  define('PHONE_SECONDARY', '393483109840');
if (!defined('EMAIL_INFO'))       define('EMAIL_INFO', 'info@keysoftitalia.it');

if (!defined('WHATSAPP_NUMBER'))  define('WHATSAPP_NUMBER', '393483109840');
if (!defined('WHATSAPP_API_URL')) define('WHATSAPP_API_URL', 'https://wa.me/');

if (!defined('GOOGLE_MAPS_EMBED')) define('GOOGLE_MAPS_EMBED', '');
if (!defined('GOOGLE_MAPS_LINK'))  define('GOOGLE_MAPS_LINK', 'https://maps.google.com/maps?q=Via+Diaz+46+Ginosa+TA');

/* ==========================================================================
   SEO
   ========================================================================== */
if (!defined('SITE_DESCRIPTION')) {
    define(
        'SITE_DESCRIPTION',
        'Key Soft Italia a Ginosa - Riparazioni in 24h, vendita dispositivi ricondizionati, assistenza informatica, sviluppo web. Il tuo partner tecnologico di fiducia dal 2008.'
    );
}
if (!defined('SEO_TITLE_SUFFIX'))          define('SEO_TITLE_SUFFIX', " | Key Soft Italia - L'universo della Tecnologia");
if (!defined('SEO_DEFAULT_DESCRIPTION'))   define('SEO_DEFAULT_DESCRIPTION', SITE_DESCRIPTION);
if (!defined('SEO_KEYWORDS')) {
    define('SEO_KEYWORDS', 'riparazioni smartphone, assistenza computer, ricondizionati, sviluppo web, ginosa, taranto, key soft italia');
}

/* ==========================================================================
   COMPANY DETAILS
   ========================================================================== */
if (!defined('COMPANY_VAT'))   define('COMPANY_VAT', 'IT12345678901');
if (!defined('VAT_NUMBER'))    define('VAT_NUMBER',  'IT12345678901');

/* ==========================================================================
   SOCIAL
   ========================================================================== */
if (!defined('SOCIAL_FACEBOOK')) define('SOCIAL_FACEBOOK', 'https://www.facebook.com/keysoftitalia');
if (!defined('SOCIAL_INSTAGRAM'))define('SOCIAL_INSTAGRAM','https://www.instagram.com/keysoftitalia');
if (!defined('SOCIAL_YOUTUBE'))  define('SOCIAL_YOUTUBE',  'https://www.youtube.com/keysoftitalia');
if (!defined('SOCIAL_LINKEDIN')) define('SOCIAL_LINKEDIN', 'https://www.linkedin.com/company/keysoftitalia');
if (!defined('SOCIAL_TIKTOK'))   define('SOCIAL_TIKTOK',   'https://www.tiktok.com/@keysoftitalia');

if (!defined('FACEBOOK_URL'))    define('FACEBOOK_URL',  SOCIAL_FACEBOOK);
if (!defined('INSTAGRAM_URL'))   define('INSTAGRAM_URL', SOCIAL_INSTAGRAM);
if (!defined('YOUTUBE_URL'))     define('YOUTUBE_URL',   SOCIAL_YOUTUBE);
if (!defined('LINKEDIN_URL'))    define('LINKEDIN_URL',  SOCIAL_LINKEDIN);
if (!defined('TIKTOK_URL'))      define('TIKTOK_URL',    SOCIAL_TIKTOK);

/* ==========================================================================
   TIMEZONE & ERROR REPORTING
   ========================================================================== */
date_default_timezone_set('Europe/Rome');

if (DEBUG_MODE) {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
} else {
    error_reporting(0);
    ini_set('display_errors', '0');
}

/* ==========================================================================
   SESSION (idempotente)
   ========================================================================== */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* ==========================================================================
   DATABASE CONFIG
   ========================================================================== */
if (!defined('DB_HOST'))     define('DB_HOST', 'localhost');
if (!defined('DB_PORT'))     define('DB_PORT', 3306);
if (!defined('DB_NAME'))     define('DB_NAME', 'ks_site_db');
if (!defined('DB_USER'))     define('DB_USER', 'keysoftfi_db');
if (!defined('DB_PASS'))     define('DB_PASS', 'az2zP389*'); // <-- metti la tua pwd reale
if (!defined('DB_CHARSET'))  define('DB_CHARSET', 'utf8mb4');

// Istanza PDO globale
$pdo = null;

try {
    // DSN stile host:porta + charset in init
    $dsn = 'mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;

    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // lancia eccezioni
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,        // fetch associativo di default
        PDO::ATTR_PERSISTENT         => false,                   // no connessione persistente per ora
    ]);

} catch (PDOException $e) {

    // Se siamo in debug vogliamo capire cosa succede al volo
    if (DEBUG_MODE) {
        die('Errore connessione DB: ' . $e->getMessage());
    } else {
        // In prod non sputiamo errori sensibili all'utente
        // Puoi loggare su file se vuoi:
        // error_log('DB connection error: '.$e->getMessage());
        die('Servizio momentaneamente non disponibile.');
    }
}

?>
