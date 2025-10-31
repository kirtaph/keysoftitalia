<?php
/**
 * Process Contact Form
 * Handles contact form submissions
 */

session_start();
require_once '../../config/config.php';
require_once 'functions.php';
require_once 'database.php';

// Check if request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . url('contatti.php'));
    exit;
}

// Verify CSRF token
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    $_SESSION['error'] = 'Token di sicurezza non valido. Riprova.';
    header('Location: ' . url('contatti.php'));
    exit;
}

// Validate required fields
$required_fields = ['name', 'email', 'phone', 'subject', 'message', 'privacy'];
$errors = [];

foreach ($required_fields as $field) {
    if (empty($_POST[$field])) {
        $errors[] = "Il campo " . ucfirst($field) . " è obbligatorio.";
    }
}

// Validate email
if (!empty($_POST['email']) && !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    $errors[] = "L'indirizzo email non è valido.";
}

// Validate phone
if (!empty($_POST['phone']) && !preg_match('/^[0-9\s\+\-\(\)]+$/', $_POST['phone'])) {
    $errors[] = "Il numero di telefono non è valido.";
}

// Check for errors
if (!empty($errors)) {
    $_SESSION['errors'] = $errors;
    $_SESSION['form_data'] = $_POST;
    header('Location: ' . url('contatti.php'));
    exit;
}

// Sanitize input data
$name = sanitize_input($_POST['name']);
$email = sanitize_input($_POST['email']);
$phone = sanitize_input($_POST['phone']);
$subject = sanitize_input($_POST['subject']);
$message = sanitize_input($_POST['message']);
$newsletter = isset($_POST['newsletter']) ? 1 : 0;

// Save to database
try {
    $db = Database::getInstance();
    $conn = $db->getConnection();
    
    // Insert contact request
    $stmt = $conn->prepare("
        INSERT INTO contact_requests (name, email, phone, subject, message, newsletter, created_at)
        VALUES (?, ?, ?, ?, ?, ?, NOW())
    ");
    
    $stmt->bind_param("sssssi", $name, $email, $phone, $subject, $message, $newsletter);
    
    if (!$stmt->execute()) {
        throw new Exception("Errore nel salvataggio della richiesta.");
    }
    
    $contact_id = $conn->insert_id;
    
    // Add to newsletter if requested
    if ($newsletter) {
        $stmt_newsletter = $conn->prepare("
            INSERT IGNORE INTO newsletter_subscribers (email, name, subscribed_at)
            VALUES (?, ?, NOW())
        ");
        $stmt_newsletter->bind_param("ss", $email, $name);
        $stmt_newsletter->execute();
    }
    
    // Prepare email content
    $email_subject = "Nuova richiesta di contatto: " . $subject;
    $email_body = "
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px; text-align: center; }
            .content { background: #f8f9fa; padding: 20px; margin-top: 20px; }
            .field { margin-bottom: 15px; }
            .label { font-weight: bold; color: #333; }
            .value { color: #666; margin-top: 5px; }
            .footer { text-align: center; margin-top: 30px; color: #666; font-size: 12px; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h2>Nuova Richiesta di Contatto</h2>
            </div>
            <div class='content'>
                <div class='field'>
                    <div class='label'>Nome:</div>
                    <div class='value'>$name</div>
                </div>
                <div class='field'>
                    <div class='label'>Email:</div>
                    <div class='value'>$email</div>
                </div>
                <div class='field'>
                    <div class='label'>Telefono:</div>
                    <div class='value'>$phone</div>
                </div>
                <div class='field'>
                    <div class='label'>Oggetto:</div>
                    <div class='value'>$subject</div>
                </div>
                <div class='field'>
                    <div class='label'>Messaggio:</div>
                    <div class='value'>" . nl2br($message) . "</div>
                </div>
                <div class='field'>
                    <div class='label'>Newsletter:</div>
                    <div class='value'>" . ($newsletter ? 'Sì' : 'No') . "</div>
                </div>
            </div>
            <div class='footer'>
                <p>Richiesta ricevuta il " . date('d/m/Y H:i') . "</p>
                <p>ID Richiesta: #$contact_id</p>
            </div>
        </div>
    </body>
    </html>
    ";
    
    // Send email notification
    if (send_email(EMAIL_INFO, $email_subject, $email_body)) {
        // Send auto-reply to customer
        $auto_reply_subject = "Abbiamo ricevuto la tua richiesta - " . SITE_NAME;
        $auto_reply_body = "
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; text-align: center; }
                .content { padding: 30px; }
                .button { display: inline-block; padding: 12px 30px; background: #4a00e0; color: white; text-decoration: none; border-radius: 5px; margin-top: 20px; }
                .footer { background: #f8f9fa; padding: 20px; text-align: center; margin-top: 30px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>Grazie per averci contattato!</h1>
                </div>
                <div class='content'>
                    <p>Ciao <strong>$name</strong>,</p>
                    <p>Abbiamo ricevuto la tua richiesta e ti risponderemo al più presto possibile, generalmente entro 24 ore lavorative.</p>
                    <p><strong>Riepilogo della tua richiesta:</strong></p>
                    <div style='background: #f8f9fa; padding: 15px; border-radius: 5px; margin: 20px 0;'>
                        <p><strong>Oggetto:</strong> $subject</p>
                        <p><strong>Messaggio:</strong><br>" . nl2br($message) . "</p>
                    </div>
                    <p>Nel frattempo, puoi contattarci per urgenze:</p>
                    <ul>
                        <li>Telefono: <strong>" . PHONE_PRIMARY . "</strong></li>
                        <li>WhatsApp: <strong>" . WHATSAPP_NUMBER . "</strong></li>
                    </ul>
                    <center>
                        <a href='" . url('servizi.php') . "' class='button'>Scopri i Nostri Servizi</a>
                    </center>
                </div>
                <div class='footer'>
                    <p>Seguici sui social:</p>
                    <p>
                        <a href='" . SOCIAL_FACEBOOK . "'>Facebook</a> | 
                        <a href='" . SOCIAL_INSTAGRAM . "'>Instagram</a>
                    </p>
                    <p style='color: #666; font-size: 12px; margin-top: 20px;'>
                        " . SITE_NAME . " - " . COMPANY_ADDRESS . "<br>
                        P.IVA: " . COMPANY_VAT . "
                    </p>
                </div>
            </div>
        </body>
        </html>
        ";
        
        send_email($email, $auto_reply_subject, $auto_reply_body);
    }
    
    // Set success message
    $_SESSION['success'] = 'Grazie per averci contattato! Ti risponderemo al più presto.';
    
    // Clear form data
    unset($_SESSION['form_data']);
    
    // Redirect back
    header('Location: ' . url('contatti.php?success=1'));
    exit;
    
} catch (Exception $e) {
    error_log("Contact form error: " . $e->getMessage());
    $_SESSION['error'] = 'Si è verificato un errore. Riprova più tardi.';
    $_SESSION['form_data'] = $_POST;
    header('Location: ' . url('contatti.php'));
    exit;
}
?>