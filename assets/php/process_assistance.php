<?php
/**
 * Process Assistance Form
 * Handles assistance request submissions
 */

session_start();
require_once '../../config/config.php';
require_once 'functions.php';
require_once 'database.php';

// Check if request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . url('assistenza.php'));
    exit;
}

// Verify CSRF token
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    $_SESSION['error'] = 'Token di sicurezza non valido. Riprova.';
    header('Location: ' . url('assistenza.php'));
    exit;
}

// Validate required fields
$required_fields = ['name', 'email', 'phone', 'device_type', 'problem_description', 'privacy'];
$errors = [];

// Check assistance type
$assistance_type = $_POST['assistance_type'] ?? 'domicilio';
if ($assistance_type === 'domicilio' && empty($_POST['address'])) {
    $errors[] = "L'indirizzo è obbligatorio per l'assistenza a domicilio.";
}

foreach ($required_fields as $field) {
    if (empty($_POST[$field])) {
        $errors[] = "Il campo " . ucfirst($field) . " è obbligatorio.";
    }
}

// Validate email
if (!empty($_POST['email']) && !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    $errors[] = "L'indirizzo email non è valido.";
}

// Check for errors
if (!empty($errors)) {
    $_SESSION['errors'] = $errors;
    $_SESSION['form_data'] = $_POST;
    header('Location: ' . url('assistenza.php'));
    exit;
}

// Sanitize input data
$name = sanitize_input($_POST['name']);
$email = sanitize_input($_POST['email']);
$phone = sanitize_input($_POST['phone']);
$device_type = sanitize_input($_POST['device_type']);
$problem_description = sanitize_input($_POST['problem_description']);
$assistance_type = sanitize_input($_POST['assistance_type']);
$address = sanitize_input($_POST['address'] ?? '');
$urgency = sanitize_input($_POST['urgency'] ?? 'normale');
$time_preference = sanitize_input($_POST['time_preference'] ?? 'qualsiasi');

// Generate ticket number
$ticket_number = 'ASS' . date('Y') . str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);

// Save to database
try {
    $db = Database::getInstance();
    $conn = $db->getConnection();
    
    // Insert assistance request
    $stmt = $conn->prepare("
        INSERT INTO assistance_requests 
        (ticket_number, name, email, phone, device_type, problem_description, 
         assistance_type, address, urgency, time_preference, status, created_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending', NOW())
    ");
    
    $stmt->bind_param("ssssssssss", 
        $ticket_number, $name, $email, $phone, $device_type, 
        $problem_description, $assistance_type, $address, $urgency, $time_preference
    );
    
    if (!$stmt->execute()) {
        throw new Exception("Errore nel salvataggio della richiesta.");
    }
    
    // Prepare email content for admin
    $urgency_label = [
        'normale' => 'Normale (2-3 giorni)',
        'urgente' => 'Urgente (24 ore)',
        'immediata' => 'Immediata (stesso giorno)'
    ];
    
    $email_subject = "Nuova richiesta assistenza - Ticket #$ticket_number";
    $email_body = "
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background: #ff6b6b; color: white; padding: 20px; text-align: center; }
            .urgent { background: #ff4444; }
            .content { background: #f8f9fa; padding: 20px; margin-top: 20px; }
            .field { margin-bottom: 15px; padding: 10px; background: white; border-radius: 5px; }
            .label { font-weight: bold; color: #333; margin-bottom: 5px; }
            .value { color: #666; }
            .footer { text-align: center; margin-top: 30px; color: #666; font-size: 12px; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header " . ($urgency === 'immediata' ? 'urgent' : '') . "'>
                <h2>Nuova Richiesta Assistenza</h2>
                <h3>Ticket #$ticket_number</h3>
            </div>
            <div class='content'>
                <div class='field'>
                    <div class='label'>Tipo Assistenza:</div>
                    <div class='value'><strong>" . ucfirst($assistance_type) . "</strong></div>
                </div>
                <div class='field'>
                    <div class='label'>Urgenza:</div>
                    <div class='value'><strong style='color: " . ($urgency === 'immediata' ? '#ff0000' : '#333') . "'>" 
                    . ($urgency_label[$urgency] ?? $urgency) . "</strong></div>
                </div>
                <div class='field'>
                    <div class='label'>Cliente:</div>
                    <div class='value'>$name</div>
                </div>
                <div class='field'>
                    <div class='label'>Email:</div>
                    <div class='value'>$email</div>
                </div>
                <div class='field'>
                    <div class='label'>Telefono:</div>
                    <div class='value'><strong>$phone</strong></div>
                </div>";
    
    if ($assistance_type === 'domicilio' && !empty($address)) {
        $email_body .= "
                <div class='field'>
                    <div class='label'>Indirizzo:</div>
                    <div class='value'>$address</div>
                </div>";
    }
    
    $email_body .= "
                <div class='field'>
                    <div class='label'>Dispositivo:</div>
                    <div class='value'>" . ucfirst($device_type) . "</div>
                </div>
                <div class='field'>
                    <div class='label'>Descrizione Problema:</div>
                    <div class='value'>" . nl2br($problem_description) . "</div>
                </div>
                <div class='field'>
                    <div class='label'>Fascia Oraria Preferita:</div>
                    <div class='value'>$time_preference</div>
                </div>
            </div>
            <div class='footer'>
                <p>Richiesta ricevuta il " . date('d/m/Y H:i') . "</p>
            </div>
        </div>
    </body>
    </html>
    ";
    
    // Send notification email
    send_email(EMAIL_INFO, $email_subject, $email_body);
    
    // Send confirmation to customer
    $customer_subject = "Richiesta assistenza ricevuta - Ticket #$ticket_number";
    $customer_body = "
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; text-align: center; }
            .content { padding: 30px; }
            .ticket-box { background: #f8f9fa; padding: 20px; border-radius: 10px; text-align: center; margin: 20px 0; }
            .ticket-number { font-size: 2rem; font-weight: bold; color: #4a00e0; }
            .button { display: inline-block; padding: 12px 30px; background: #4a00e0; color: white; text-decoration: none; border-radius: 5px; margin-top: 20px; }
            .footer { background: #f8f9fa; padding: 20px; text-align: center; margin-top: 30px; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h1>Richiesta Assistenza Ricevuta!</h1>
            </div>
            <div class='content'>
                <p>Ciao <strong>$name</strong>,</p>
                <p>Abbiamo ricevuto la tua richiesta di assistenza tecnica.</p>
                
                <div class='ticket-box'>
                    <p>Il tuo numero di ticket è:</p>
                    <div class='ticket-number'>#$ticket_number</div>
                    <p style='color: #666; font-size: 14px;'>Conserva questo numero per riferimento</p>
                </div>
                
                <h3>Riepilogo della richiesta:</h3>
                <ul>
                    <li><strong>Tipo assistenza:</strong> " . ucfirst($assistance_type) . "</li>
                    <li><strong>Dispositivo:</strong> " . ucfirst($device_type) . "</li>
                    <li><strong>Urgenza:</strong> " . ($urgency_label[$urgency] ?? $urgency) . "</li>
                </ul>
                
                <p><strong>Prossimi passi:</strong></p>
                <ol>
                    <li>Un nostro tecnico analizzerà la tua richiesta</li>
                    <li>Ti contatteremo entro " . ($urgency === 'immediata' ? '2 ore' : ($urgency === 'urgente' ? '24 ore' : '48 ore')) . "</li>
                    <li>Concorderemo insieme data e ora dell'intervento</li>
                </ol>
                
                <p>Per urgenze puoi contattarci:</p>
                <ul>
                    <li>Telefono: <strong>" . PHONE_PRIMARY . "</strong></li>
                    <li>WhatsApp: <strong>" . WHATSAPP_NUMBER . "</strong></li>
                </ul>
                
                <center>
                    <a href='" . url('') . "' class='button'>Visita il Nostro Sito</a>
                </center>
            </div>
            <div class='footer'>
                <p style='color: #666; font-size: 12px;'>
                    " . SITE_NAME . " - " . COMPANY_ADDRESS . "<br>
                    Tel: " . PHONE_PRIMARY . " - Email: " . EMAIL_INFO . "
                </p>
            </div>
        </div>
    </body>
    </html>
    ";
    
    send_email($email, $customer_subject, $customer_body);
    
    // Set success message
    $_SESSION['success'] = "Richiesta di assistenza inviata con successo! Ticket: #$ticket_number";
    $_SESSION['ticket_number'] = $ticket_number;
    
    // Clear form data
    unset($_SESSION['form_data']);
    
    // Redirect back
    header('Location: ' . url('assistenza.php?success=1&ticket=' . $ticket_number));
    exit;
    
} catch (Exception $e) {
    error_log("Assistance form error: " . $e->getMessage());
    $_SESSION['error'] = 'Si è verificato un errore. Riprova più tardi.';
    $_SESSION['form_data'] = $_POST;
    header('Location: ' . url('assistenza.php'));
    exit;
}
?>