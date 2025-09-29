<?php
/**
 * Key Soft Italia - Configuration File
 * 
 * Configurazione generale del sito
 * @author Key Soft Italia Development Team
 * @version 1.0.0
 */

// Define BASE_PATH for file includes (only if not already defined)
if (!defined('BASE_PATH')) {
    define('BASE_PATH', dirname(__DIR__) . '/');
}

// Include helper functions
require_once BASE_PATH . 'assets/php/functions.php';

// Auto-detect BASE_URL (commentare e definire manualmente se necessario)
// define('BASE_URL', '/keysoftitalia/'); // Decommentare per forzare una specifica sottocartella
autoDetectBaseUrl();

// Site Configuration
define('SITE_NAME', 'Key Soft Italia');
define('SITE_TAGLINE', "L'universo della Tecnologia");

// Informazioni Aziendali
define('COMPANY_NAME', 'Key Soft Italia');
define('COMPANY_PHONE', '099 829 3794');
define('COMPANY_WHATSAPP', '348 310 9840');
define('COMPANY_EMAIL', 'info@keysoftitalia.it');
define('COMPANY_ADDRESS', 'Via Diaz, 46');
define('COMPANY_CITY', 'Ginosa');
define('COMPANY_ZIP', '74013');
define('COMPANY_PROVINCE', 'TA');
define('COMPANY_FULL_ADDRESS', 'Via Diaz, 46 - 74013 Ginosa (TA)');

// Address alias for backward compatibility
define('ADDRESS', COMPANY_FULL_ADDRESS);

// Orari di Apertura
define('OPENING_HOURS', [
    'monday' => ['open' => '09:00', 'close' => '19:00'],
    'tuesday' => ['open' => '09:00', 'close' => '19:00'],
    'wednesday' => ['open' => '09:00', 'close' => '19:00'],
    'thursday' => ['open' => '09:00', 'close' => '19:00'],
    'friday' => ['open' => '09:00', 'close' => '19:00'],
    'saturday' => ['open' => '09:00', 'close' => '13:00'],
    'sunday' => 'chiuso'
]);

// Database Configuration (check if not already defined to avoid duplicates)
if (!defined('DB_HOST')) {
    define('DB_HOST', 'localhost');
    define('DB_USER', 'root');
    define('DB_PASS', '');
    define('DB_NAME', 'keysoftitalia');
    define('DB_PORT', 3306);
    define('DB_CHARSET', 'utf8mb4');
}

// Debug mode
if (!defined('DEBUG_MODE')) {
    define('DEBUG_MODE', true);
}

// Configurazione Email (SMTP)
define('SMTP_HOST', 'smtp.gmail.com'); // Modificare con il proprio SMTP
define('SMTP_PORT', 587);
define('SMTP_USERNAME', ''); // Inserire username SMTP
define('SMTP_PASSWORD', ''); // Inserire password SMTP
define('SMTP_ENCRYPTION', 'tls');
define('SMTP_FROM_EMAIL', COMPANY_EMAIL);
define('SMTP_FROM_NAME', COMPANY_NAME);

// Contact Configuration
define('PHONE_PRIMARY', '099 829 3794');
define('PHONE_SECONDARY', '348 310 9840');
define('EMAIL_INFO', 'info@keysoftitalia.it');
define('EMAIL_SUPPORT', 'supporto@keysoftitalia.it');
define('EMAIL_NOREPLY', 'noreply@keysoftitalia.it');

// WhatsApp Configuration
define('WHATSAPP_NUMBER', '393483109840'); // Numero senza + e spazi
define('WHATSAPP_API_URL', 'https://wa.me/');

// Google Maps
define('GOOGLE_MAPS_EMBED', ''); // Inserire embed code Google Maps
define('GOOGLE_MAPS_LINK', 'https://maps.google.com/maps?q=Via+Diaz+46+Ginosa+TA');

// SEO Settings
define('SITE_DESCRIPTION', 'Key Soft Italia a Ginosa - Riparazioni in 24h, vendita dispositivi ricondizionati, assistenza informatica, sviluppo web. Il tuo partner tecnologico di fiducia dal 2008.');
define('SEO_TITLE_SUFFIX', ' | Key Soft Italia - L\'universo della Tecnologia');
define('SEO_DEFAULT_DESCRIPTION', 'Key Soft Italia a Ginosa - Riparazioni in 24h, vendita dispositivi ricondizionati, assistenza informatica, sviluppo web. Il tuo partner tecnologico di fiducia dal 2008.');
define('SEO_KEYWORDS', 'riparazioni smartphone, assistenza computer, ricondizionati, sviluppo web, ginosa, taranto, key soft italia');

// Company Details
define('COMPANY_VAT', 'IT12345678901'); // Inserire P.IVA reale
define('VAT_NUMBER', 'IT12345678901'); // Alias for compatibility

// Social Media
define('SOCIAL_FACEBOOK', 'https://www.facebook.com/keysoftitalia');
define('SOCIAL_INSTAGRAM', 'https://www.instagram.com/keysoftitalia');
define('SOCIAL_YOUTUBE', 'https://www.youtube.com/keysoftitalia');
define('SOCIAL_LINKEDIN', 'https://www.linkedin.com/company/keysoftitalia');
define('SOCIAL_TIKTOK', 'https://www.tiktok.com/@keysoftitalia');

// Legacy aliases for compatibility
define('FACEBOOK_URL', SOCIAL_FACEBOOK);
define('INSTAGRAM_URL', SOCIAL_INSTAGRAM);
define('YOUTUBE_URL', SOCIAL_YOUTUBE);
define('LINKEDIN_URL', SOCIAL_LINKEDIN);
define('TIKTOK_URL', SOCIAL_TIKTOK);

// Le funzioni url() e whatsapp_link() sono ora in assets/php/functions.php

// Timezone
date_default_timezone_set('Europe/Rome');

// Error Reporting (disabilitare in produzione)
if (DEBUG_MODE) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Session Start
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>