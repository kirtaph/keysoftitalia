<?php
/**
 * Key Soft Italia - Helper Functions
 * Funzioni globali per il sito
 */

// Prevent direct access
if (!defined('BASE_PATH')) {
    define('BASE_PATH', dirname(dirname(__DIR__)) . '/');
}

/**
 * Automatically detect and set BASE_URL if not defined
 */
function autoDetectBaseUrl() {
    if (!defined('BASE_URL')) {
        $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || 
                    $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        
        $host = $_SERVER['HTTP_HOST'];
        
        // Get the current script's directory relative to document root
        $scriptPath = dirname($_SERVER['SCRIPT_NAME']);
        
        // Ensure trailing slash
        $scriptPath = rtrim($scriptPath, '/') . '/';
        
        // If we're in the root, just use /
        if ($scriptPath == '//') {
            $scriptPath = '/';
        }
        
        define('BASE_URL', $protocol . $host . $scriptPath);
    }
}

/**
 * Generate URL with base path support
 * @param string $path Relative path to resource
 * @return string Complete URL
 */
function url($path = '') {
    // Auto-detect BASE_URL if not set
    autoDetectBaseUrl();
    
    // Remove leading slash from path
    $path = ltrim($path, '/');
    
    // Return complete URL
    return BASE_URL . $path;
}

/**
 * Generate asset URL (css, js, images)
 * @param string $path Asset path relative to assets folder
 * @return string Complete asset URL
 */
function asset($path) {
    return url('assets/' . ltrim($path, '/'));
}

/**
 * Generate page URL
 * @param string $page Page name (e.g., 'chi-siamo.php')
 * @return string Complete page URL
 */
function page_url($page) {
    return url($page);
}

/**
 * Check if current page
 * @param string $page Page name to check
 * @return bool
 */
function is_current_page($page) {
    $currentPage = basename($_SERVER['SCRIPT_NAME']);
    return $currentPage === $page || $currentPage === $page . '.php';
}

/**
 * Get current page name without extension
 * @return string
 */
function get_current_page() {
    return basename($_SERVER['SCRIPT_NAME'], '.php');
}

/**
 * Include partial with variables
 * @param string $partial Partial file path
 * @param array $variables Variables to pass to partial
 */
function include_partial($partial, $variables = []) {
    // Extract variables to current scope
    if (!empty($variables)) {
        extract($variables);
    }
    
    $partialPath = BASE_PATH . 'includes/' . $partial;
    if (file_exists($partialPath)) {
        include $partialPath;
    } else {
        error_log("Partial not found: " . $partialPath);
    }
}

/**
 * Generate WhatsApp link with message
 * @param string $message Message text
 * @param array $utmParams UTM parameters
 * @return string WhatsApp URL
 */
function whatsapp_link($message = '', $utmParams = []) {
    $whatsappNumber = defined('WHATSAPP_NUMBER') ? WHATSAPP_NUMBER : '393483109840';
    
    $defaultUtm = [
        'utm_source' => 'site',
        'utm_medium' => 'whatsapp',
        'utm_campaign' => 'general'
    ];
    
    $utm = array_merge($defaultUtm, $utmParams);
    $utmString = http_build_query($utm);
    
    $encodedMessage = urlencode($message);
    return "https://wa.me/{$whatsappNumber}?text={$encodedMessage}&{$utmString}";
}

/**
 * Format price in EUR
 * @param float $price Price value
 * @param bool $showSymbol Show currency symbol
 * @return string Formatted price
 */
function format_price($price, $showSymbol = true) {
    $formatted = number_format($price, 2, ',', '.');
    return $showSymbol ? '€ ' . $formatted : $formatted;
}

/**
 * Format phone number for display
 * @param string $phone Phone number
 * @return string Formatted phone number
 */
function format_phone($phone) {
    // Remove all non-numeric characters
    $phone = preg_replace('/\D/', '', $phone);
    
    // Italian phone formatting
    if (strlen($phone) == 10 && substr($phone, 0, 1) == '0') {
        // Landline: 099 829 3794
        return substr($phone, 0, 3) . ' ' . substr($phone, 3, 3) . ' ' . substr($phone, 6);
    } elseif (strlen($phone) == 10 && substr($phone, 0, 1) == '3') {
        // Mobile: 348 310 9840
        return substr($phone, 0, 3) . ' ' . substr($phone, 3, 3) . ' ' . substr($phone, 6);
    }
    
    return $phone;
}

/**
 * Sanitize input data
 * @param mixed $data Input data
 * @return mixed Sanitized data
 */
function sanitize_input($data) {
    if (is_array($data)) {
        return array_map('sanitize_input', $data);
    }
    
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

/**
 * Validate email
 * @param string $email Email address
 * @return bool
 */
function is_valid_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Validate Italian phone number
 * @param string $phone Phone number
 * @return bool
 */
function is_valid_phone($phone) {
    $phone = preg_replace('/\D/', '', $phone);
    return strlen($phone) >= 9 && strlen($phone) <= 13;
}

/**
 * Generate SEO meta tags
 * @param array $meta Meta information
 * @return string HTML meta tags
 */
function generate_meta_tags($meta = []) {
    $defaults = [
        'title' => 'Key Soft Italia - L\'universo della Tecnologia',
        'description' => 'Key Soft Italia a Ginosa - Riparazioni in 24h, vendita dispositivi ricondizionati, assistenza informatica, sviluppo web.',
        'keywords' => 'riparazioni smartphone, assistenza computer, ricondizionati, sviluppo web, key soft italia, ginosa',
        'image' => asset('images/og-image.jpg'),
        'url' => BASE_URL
    ];
    
    $meta = array_merge($defaults, $meta);
    
    $output = '';
    $output .= '<meta name="description" content="' . htmlspecialchars($meta['description']) . '">' . "\n";
    $output .= '<meta name="keywords" content="' . htmlspecialchars($meta['keywords']) . '">' . "\n";
    
    // Open Graph
    $output .= '<meta property="og:title" content="' . htmlspecialchars($meta['title']) . '">' . "\n";
    $output .= '<meta property="og:description" content="' . htmlspecialchars($meta['description']) . '">' . "\n";
    $output .= '<meta property="og:image" content="' . htmlspecialchars($meta['image']) . '">' . "\n";
    $output .= '<meta property="og:url" content="' . htmlspecialchars($meta['url']) . '">' . "\n";
    $output .= '<meta property="og:type" content="website">' . "\n";
    
    // Twitter Card
    $output .= '<meta name="twitter:card" content="summary_large_image">' . "\n";
    $output .= '<meta name="twitter:title" content="' . htmlspecialchars($meta['title']) . '">' . "\n";
    $output .= '<meta name="twitter:description" content="' . htmlspecialchars($meta['description']) . '">' . "\n";
    $output .= '<meta name="twitter:image" content="' . htmlspecialchars($meta['image']) . '">' . "\n";
    
    return $output;
}

/**
 * Get excerpt from text
 * @param string $text Full text
 * @param int $length Max length
 * @param string $suffix Suffix to add
 * @return string Excerpt
 */
function get_excerpt($text, $length = 150, $suffix = '...') {
    $text = strip_tags($text);
    if (mb_strlen($text) <= $length) {
        return $text;
    }
    
    $excerpt = mb_substr($text, 0, $length);
    $lastSpace = mb_strrpos($excerpt, ' ');
    
    if ($lastSpace !== false) {
        $excerpt = mb_substr($excerpt, 0, $lastSpace);
    }
    
    return $excerpt . $suffix;
}

/**
 * Generate breadcrumbs
 * @param array $items Breadcrumb items
 * @return string HTML breadcrumbs
 */
function generate_breadcrumbs($items = []) {
    if (empty($items)) {
        return '';
    }
    
    $output = '<nav aria-label="breadcrumb">';
    $output .= '<ol class="breadcrumb">';
    
    $output .= '<li class="breadcrumb-item"><a href="' . url() . '">Home</a></li>';
    
    foreach ($items as $index => $item) {
        $isLast = ($index === count($items) - 1);
        
        if ($isLast) {
            $output .= '<li class="breadcrumb-item active" aria-current="page">' . htmlspecialchars($item['label']) . '</li>';
        } else {
            $output .= '<li class="breadcrumb-item"><a href="' . url($item['url']) . '">' . htmlspecialchars($item['label']) . '</a></li>';
        }
    }
    
    $output .= '</ol>';
    $output .= '</nav>';
    
    return $output;
}

/**
 * Debug function (disable in production)
 * @param mixed $data Data to debug
 * @param bool $die Stop execution
 */
function debug($data, $die = true) {
    echo '<pre style="background: #222; color: #0f0; padding: 10px; margin: 10px; border-radius: 5px;">';
    print_r($data);
    echo '</pre>';
    
    if ($die) {
        die();
    }
}

/**
 * Log error to file
 * @param string $message Error message
 * @param string $file File where error occurred
 * @param int $line Line number
 */
function log_error($message, $file = '', $line = '') {
    $logFile = BASE_PATH . 'logs/error.log';
    $logDir = dirname($logFile);
    
    // Create logs directory if not exists
    if (!is_dir($logDir)) {
        mkdir($logDir, 0755, true);
    }
    
    $timestamp = date('Y-m-d H:i:s');
    $logMessage = "[{$timestamp}] {$message}";
    
    if ($file) {
        $logMessage .= " in {$file}";
    }
    
    if ($line) {
        $logMessage .= " on line {$line}";
    }
    
    $logMessage .= "\n";
    
    error_log($logMessage, 3, $logFile);
}

/**
 * CSRF Token Generation and Validation
 */
function generate_csrf_token() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function validate_csrf_token($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

function csrf_field() {
    return '<input type="hidden" name="csrf_token" value="' . generate_csrf_token() . '">';
}

// Alias for backward compatibility
function generate_csrf_field() {
    return csrf_field();
}

/**
 * Redirect to URL
 * @param string $url URL to redirect to
 * @param int $statusCode HTTP status code
 */
function redirect($url, $statusCode = 302) {
    header('Location: ' . $url, true, $statusCode);
    exit();
}

/**
 * Check if request is AJAX
 * @return bool
 */
function is_ajax_request() {
    return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
           strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}

/**
 * Send JSON response
 * @param array $data Response data
 * @param int $statusCode HTTP status code
 */
function json_response($data, $statusCode = 200) {
    http_response_code($statusCode);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit();
}

// Initialize session if not started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Auto-detect BASE_URL on load
autoDetectBaseUrl();
?>