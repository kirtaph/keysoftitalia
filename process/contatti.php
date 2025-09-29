<?php
/**
 * Key Soft Italia - Process Contact Form
 * Gestisce l'invio del form contatti
 */

session_start();

// Define BASE_PATH
define('BASE_PATH', dirname(__DIR__) . '/');

// Include required files
require_once BASE_PATH . 'config/config.php';
require_once BASE_PATH . 'includes/Database.php';
require_once BASE_PATH . 'includes/FlashMessage.php';

// Only accept POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . url('contatti.php'));
    exit;
}

// Validate CSRF token
if (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
    FlashMessage::error('Sessione scaduta. Riprova.');
    header('Location: ' . url('contatti.php'));
    exit;
}

// Collect and sanitize form data
$data = [
    'name' => sanitize_input($_POST['name'] ?? ''),
    'surname' => sanitize_input($_POST['surname'] ?? ''),
    'email' => sanitize_input($_POST['email'] ?? ''),
    'phone' => sanitize_input($_POST['phone'] ?? ''),
    'company' => sanitize_input($_POST['company'] ?? ''),
    'subject' => sanitize_input($_POST['subject'] ?? ''),
    'message' => sanitize_input($_POST['message'] ?? ''),
    'newsletter_accepted' => isset($_POST['newsletter']) ? 1 : 0,
    'privacy_accepted' => isset($_POST['privacy']) ? 1 : 0
];

// Add tracking data
$data['source'] = 'website';
$data['utm_source'] = $_SESSION['utm_source'] ?? null;
$data['utm_medium'] = $_SESSION['utm_medium'] ?? null;
$data['utm_campaign'] = $_SESSION['utm_campaign'] ?? null;
$data['ip_address'] = $_SERVER['REMOTE_ADDR'] ?? null;
$data['user_agent'] = $_SERVER['HTTP_USER_AGENT'] ?? null;

// Validation
$errors = [];

// Required fields
if (empty($data['name'])) {
    $errors[] = 'Il nome è obbligatorio';
}

if (empty($data['email'])) {
    $errors[] = 'L\'email è obbligatoria';
} elseif (!is_valid_email($data['email'])) {
    $errors[] = 'Inserisci un\'email valida';
}

if (empty($data['message'])) {
    $errors[] = 'Il messaggio è obbligatorio';
}

if (!$data['privacy_accepted']) {
    $errors[] = 'Devi accettare la privacy policy';
}

// Check for errors
if (!empty($errors)) {
    foreach ($errors as $error) {
        FlashMessage::error($error);
    }
    
    // Store form data in session to repopulate
    $_SESSION['form_data'] = $_POST;
    
    header('Location: ' . url('contatti.php'));
    exit;
}

try {
    // Initialize database
    $db = new Database();
    
    // Insert contact into database
    $contactId = $db->insert('contacts', $data);
    
    if ($contactId) {
        // Subscribe to newsletter if requested
        if ($data['newsletter_accepted'] && !empty($data['email'])) {
            // Check if email already exists
            $existingSubscriber = $db->select(
                'newsletter_subscribers',
                'id',
                'email = :email',
                ['email' => $data['email']]
            );
            
            if (empty($existingSubscriber)) {
                $db->insert('newsletter_subscribers', [
                    'email' => $data['email'],
                    'name' => $data['name'] . ' ' . $data['surname'],
                    'status' => 'active',
                    'token' => bin2hex(random_bytes(32)),
                    'ip_address' => $data['ip_address']
                ]);
            }
        }
        
        // Send email notification (if SMTP is configured)
        if (defined('SMTP_HOST') && !empty(SMTP_HOST)) {
            sendEmailNotification($data);
        }
        
        // Send auto-reply to user
        sendAutoReply($data);
        
        // Clear form data from session
        unset($_SESSION['form_data']);
        
        // Success message
        FlashMessage::success('Messaggio inviato con successo! Ti risponderemo al più presto.');
        
        // Log activity
        logActivity('contact_form_submitted', $contactId, $data);
        
    } else {
        throw new Exception('Errore nel salvataggio del messaggio');
    }
    
} catch (Exception $e) {
    // Log error
    error_log('Contact form error: ' . $e->getMessage());
    
    // User-friendly error message
    FlashMessage::error('Si è verificato un errore nell\'invio del messaggio. Riprova più tardi.');
    
    // Store form data to repopulate
    $_SESSION['form_data'] = $_POST;
}

// Redirect back to contact page
header('Location: ' . url('contatti.php'));
exit;

/**
 * Send email notification to admin
 */
function sendEmailNotification($data) {
    $to = EMAIL_INFO;
    $subject = "[Nuovo Contatto] " . ($data['subject'] ?: 'Richiesta informazioni');
    
    $message = "
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background: #ff6b00; color: white; padding: 20px; text-align: center; }
            .content { padding: 20px; background: #f5f5f5; }
            .field { margin-bottom: 15px; }
            .label { font-weight: bold; color: #333; }
            .value { color: #666; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h2>Nuovo Messaggio dal Sito Web</h2>
            </div>
            <div class='content'>
                <div class='field'>
                    <span class='label'>Nome:</span> 
                    <span class='value'>{$data['name']} {$data['surname']}</span>
                </div>
                <div class='field'>
                    <span class='label'>Email:</span> 
                    <span class='value'>{$data['email']}</span>
                </div>
                <div class='field'>
                    <span class='label'>Telefono:</span> 
                    <span class='value'>{$data['phone']}</span>
                </div>
                <div class='field'>
                    <span class='label'>Azienda:</span> 
                    <span class='value'>{$data['company']}</span>
                </div>
                <div class='field'>
                    <span class='label'>Oggetto:</span> 
                    <span class='value'>{$data['subject']}</span>
                </div>
                <div class='field'>
                    <span class='label'>Messaggio:</span><br>
                    <span class='value'>" . nl2br($data['message']) . "</span>
                </div>
                <div class='field'>
                    <span class='label'>Newsletter:</span> 
                    <span class='value'>" . ($data['newsletter_accepted'] ? 'Sì' : 'No') . "</span>
                </div>
                <hr>
                <small>
                    IP: {$data['ip_address']}<br>
                    Data: " . date('d/m/Y H:i:s') . "
                </small>
            </div>
        </div>
    </body>
    </html>
    ";
    
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    $headers .= "From: " . EMAIL_NOREPLY . "\r\n";
    $headers .= "Reply-To: {$data['email']}\r\n";
    
    @mail($to, $subject, $message, $headers);
}

/**
 * Send auto-reply to user
 */
function sendAutoReply($data) {
    $to = $data['email'];
    $subject = "Grazie per averci contattato - Key Soft Italia";
    
    $message = "
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background: #ff6b00; color: white; padding: 30px; text-align: center; }
            .content { padding: 30px; background: #f9f9f9; }
            .footer { padding: 20px; text-align: center; color: #666; font-size: 12px; }
            .button { display: inline-block; padding: 12px 30px; background: #ff6b00; color: white; text-decoration: none; border-radius: 5px; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h1>Key Soft Italia</h1>
                <p>L'universo della Tecnologia</p>
            </div>
            <div class='content'>
                <h2>Ciao {$data['name']},</h2>
                <p>Grazie per averci contattato! Abbiamo ricevuto il tuo messaggio e ti risponderemo al più presto possibile.</p>
                
                <p><strong>Riepilogo del tuo messaggio:</strong></p>
                <div style='background: white; padding: 15px; border-left: 4px solid #ff6b00;'>
                    " . nl2br($data['message']) . "
                </div>
                
                <p>Nel frattempo, puoi:</p>
                <ul>
                    <li>Visitare il nostro sito: <a href='" . BASE_URL . "'>www.keysoftitalia.it</a></li>
                    <li>Chiamarci al: " . PHONE_PRIMARY . "</li>
                    <li>Scriverci su WhatsApp: " . PHONE_SECONDARY . "</li>
                </ul>
                
                <p style='text-align: center; margin-top: 30px;'>
                    <a href='" . BASE_URL . "' class='button'>Visita il Sito</a>
                </p>
            </div>
            <div class='footer'>
                <p>Key Soft Italia<br>
                Via Diaz, 46 - 74013 Ginosa (TA)<br>
                Tel: " . PHONE_PRIMARY . " | Email: " . EMAIL_INFO . "</p>
                <p>Questa è un'email automatica, ti preghiamo di non rispondere.</p>
            </div>
        </div>
    </body>
    </html>
    ";
    
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    $headers .= "From: " . EMAIL_NOREPLY . "\r\n";
    
    @mail($to, $subject, $message, $headers);
}

/**
 * Log activity to database
 */
function logActivity($action, $entityId, $details) {
    try {
        $db = new Database();
        $db->insert('activity_log', [
            'action' => $action,
            'entity_type' => 'contact',
            'entity_id' => $entityId,
            'details' => json_encode($details),
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? null,
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? null
        ]);
    } catch (Exception $e) {
        error_log('Failed to log activity: ' . $e->getMessage());
    }
}
?>