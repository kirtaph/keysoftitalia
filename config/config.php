<?php
/**
 * Key Soft Italia - Configuration File
 */

// BASE_PATH: definito in functions.php; fallback qui se serve
if (!defined('BASE_PATH')) {
    define('BASE_PATH', rtrim(str_replace('\\', '/', dirname(__DIR__)), '/') . '/');
}

// Helpers
require_once BASE_PATH . 'assets/php/functions.php';

// Se vuoi forzare manualmente la sottocartella, decommenta questa riga:
// define('BASE_URL', 'https://example.com/keysoftitalia/');

// Altrimenti auto-detect
autoDetectBaseUrl();

// Site Configuration
define('SITE_NAME', 'Key Soft Italia');
define('SITE_TAGLINE', "L'universo della Tecnologia");

// Info Aziendali
define('COMPANY_NAME', 'Key Soft Italia');
define('COMPANY_PHONE', '099 829 3794');
define('COMPANY_WHATSAPP', '348 310 9840');
define('COMPANY_EMAIL', 'info@keysoftitalia.it');
define('COMPANY_ADDRESS', 'Via Diaz, 46');
define('COMPANY_CITY', 'Ginosa');
define('COMPANY_ZIP', '74013');
define('COMPANY_PROVINCE', 'TA');
define('COMPANY_FULL_ADDRESS', 'Via Diaz, 46 - 74013 Ginosa (TA)');
define('ADDRESS', COMPANY_FULL_ADDRESS);

// Orari
define('OPENING_HOURS', [
    'monday' => ['open' => '09:00', 'close' => '19:00'],
    'tuesday' => ['open' => '09:00', 'close' => '19:00'],
    'wednesday' => ['open' => '09:00', 'close' => '19:00'],
    'thursday' => ['open' => '09:00', 'close' => '19:00'],
    'friday' => ['open' => '09:00', 'close' => '19:00'],
    'saturday' => ['open' => '09:00', 'close' => '13:00'],
    'sunday' => 'chiuso'
]);

// DB (placeholder)
if (!defined('DB_HOST')) {
    define('DB_HOST', 'localhost');
    define('DB_USER', 'root');
    define('DB_PASS', '');
    define('DB_NAME', 'keysoftitalia');
    define('DB_PORT', 3306);
    define('DB_CHARSET', 'utf8mb4');
}

if (!defined('GA_MEASUREMENT_ID')) {
  define('GA_MEASUREMENT_ID', 'G-1QG00HRZK8'); // <-- metti il tuo
}

// Debug
if (!defined('DEBUG_MODE')) define('DEBUG_MODE', true);

// Produzione
define('APP_ENV', 'prod');
define('MAIL_TRANSPORT', 'smtp'); // usa SMTP in produzione

// SMTP del tuo hosting (esempi: cPanel/Aruba/Mailtrap prod)
define('SMTP_HOST',    'smtp.keysoftitalia.it'); // es: 'smtps.aruba.it' o quello del provider
define('SMTP_PORT',    587);                  // 587 (TLS) o 465 (SSL)
define('SMTP_SECURE',  'tls');                // 'tls' o 'ssl'
define('SMTP_AUTH',    true);
define('SMTP_USER',    'no-reply@keysoftitalia.it');
define('SMTP_PASS',    '8CGYYJQr2024!');

// Mittenti/destinazioni
define('EMAIL_ASSISTENZA', 'info@keysoftitalia.it');
define('EMAIL_FROM',       'no-reply@keysoftitalia.it');

// WhatsApp (in forma internazionale, solo cifre – es: +39 333 1234567 -> 393331234567)
define('PHONE_PRIMARY',   '099 829 3794'); // usato per tel:
define('PHONE_WHATSAPP',  '393483109840');   // per wa.me

// Contatti
define('PHONE_SECONDARY', '393483109840');
define('EMAIL_INFO', 'info@keysoftitalia.it');

// WhatsApp
define('WHATSAPP_NUMBER', '393483109840');
define('WHATSAPP_API_URL', 'https://wa.me/');

// Google Maps
define('GOOGLE_MAPS_EMBED', '');
define('GOOGLE_MAPS_LINK', 'https://maps.google.com/maps?q=Via+Diaz+46+Ginosa+TA');

// SEO
define('SITE_DESCRIPTION', 'Key Soft Italia a Ginosa - Riparazioni in 24h, vendita dispositivi ricondizionati, assistenza informatica, sviluppo web. Il tuo partner tecnologico di fiducia dal 2008.');
define('SEO_TITLE_SUFFIX', " | Key Soft Italia - L'universo della Tecnologia");
define('SEO_DEFAULT_DESCRIPTION', SITE_DESCRIPTION);
define('SEO_KEYWORDS', 'riparazioni smartphone, assistenza computer, ricondizionati, sviluppo web, ginosa, taranto, key soft italia');

// Company Details
define('COMPANY_VAT', 'IT12345678901');
define('VAT_NUMBER', 'IT12345678901');

// Social
define('SOCIAL_FACEBOOK', 'https://www.facebook.com/keysoftitalia');
define('SOCIAL_INSTAGRAM', 'https://www.instagram.com/keysoftitalia');
define('SOCIAL_YOUTUBE', 'https://www.youtube.com/keysoftitalia');
define('SOCIAL_LINKEDIN', 'https://www.linkedin.com/company/keysoftitalia');
define('SOCIAL_TIKTOK', 'https://www.tiktok.com/@keysoftitalia');

define('FACEBOOK_URL', SOCIAL_FACEBOOK);
define('INSTAGRAM_URL', SOCIAL_INSTAGRAM);
define('YOUTUBE_URL', SOCIAL_YOUTUBE);
define('LINKEDIN_URL', SOCIAL_LINKEDIN);
define('TIKTOK_URL', SOCIAL_TIKTOK);

// Timezone + error reporting
date_default_timezone_set('Europe/Rome');

if (DEBUG_MODE) {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
} else {
    error_reporting(0);
    ini_set('display_errors', '0');
}

// Session: già gestita in functions.php, ma idempotente
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
