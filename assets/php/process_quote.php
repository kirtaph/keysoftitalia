<?php
/**
 * Process Quote Form
 * Handles quote request submissions
 */

session_start();
require_once '../../config/config.php';
require_once 'functions.php';
require_once 'database.php';

// Check if request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . url('preventivo.php'));
    exit;
}

// Verify CSRF token
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    $_SESSION['error'] = 'Token di sicurezza non valido. Riprova.';
    header('Location: ' . url('preventivo.php'));
    exit;
}

// Validate required fields
$required_fields = ['name', 'email', 'phone', 'problem_description', 'privacy'];
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

// Check for errors
if (!empty($errors)) {
    $_SESSION['errors'] = $errors;
    $_SESSION['form_data'] = $_POST;
    header('Location: ' . url('preventivo.php'));
    exit;
}

// Sanitize input data
$name = sanitize_input($_POST['name']);
$email = sanitize_input($_POST['email']);
$phone = sanitize_input($_POST['phone']);
$device_model = sanitize_input($_POST['device_model'] ?? '');
$problem_description = sanitize_input($_POST['problem_description']);
$priority = sanitize_input($_POST['priority'] ?? 'normal');
$budget = sanitize_input($_POST['budget'] ?? '');
$newsletter = isset($_POST['newsletter']) ? 1 : 0;

// Calculator data
$calculated_price = floatval($_POST['calculated_price'] ?? 0);
$selected_device = sanitize_input($_POST['selected_device'] ?? '');
$selected_services = $_POST['selected_services'] ?? '[]';

// Generate quote number
$quote_number = 'PRV' . date('Ym') . str_pad(mt_rand(1, 999), 3, '0', STR_PAD_LEFT);

// Save to database
try {
    $db = Database::getInstance();
    $conn = $db->getConnection();
    
    // Insert quote request
    $stmt = $conn->prepare("
        INSERT INTO quote_requests 
        (quote_number, name, email, phone, device_model, problem_description, 
         priority, budget, calculated_price, selected_device, selected_services, 
         newsletter, status, created_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending', NOW())
    ");
    
    $stmt->bind_param("sssssssdsssi", 
        $quote_number, $name, $email, $phone, $device_model, 
        $problem_description, $priority, $budget, $calculated_price, 
        $selected_device, $selected_services, $newsletter
    );
    
    if (!$stmt->execute()) {
        throw new Exception("Errore nel salvataggio del preventivo.");
    }
    
    // Add to newsletter if requested
    if ($newsletter) {
        $stmt_newsletter = $conn->prepare("
            INSERT IGNORE INTO newsletter_subscribers (email, name, subscribed_at)
            VALUES (?, ?, NOW())
        ");
        $stmt_newsletter->bind_param("ss", $email, $name);
        $stmt_newsletter->execute();
    }
    
    // Decode services for email
    $services_array = json_decode($selected_services, true) ?? [];
    $services_list = '';
    if (!empty($services_array)) {
        foreach ($services_array as $service) {
            $services_list .= "<li>" . $service['name'] . " - €" . $service['price'] . "</li>";
        }
    }
    
    // Priority labels
    $priority_labels = [
        'normal' => 'Normale (3-5 giorni)',
        'urgent' => 'Urgente (24-48 ore)',
        'immediate' => 'Immediata (stesso giorno)'
    ];
    
    // Prepare email for admin
    $email_subject = "Nuova richiesta preventivo #$quote_number" . 
                    ($priority !== 'normal' ? ' - PRIORITÀ ' . strtoupper($priority) : '');
    
    $email_body = "
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px; text-align: center; }
            .content { background: #f8f9fa; padding: 20px; margin-top: 20px; }
            .field { margin-bottom: 15px; padding: 10px; background: white; border-radius: 5px; }
            .label { font-weight: bold; color: #333; }
            .value { color: #666; margin-top: 5px; }
            .price-box { background: #4a00e0; color: white; padding: 15px; border-radius: 10px; text-align: center; margin: 20px 0; }
            .price-box .amount { font-size: 2rem; font-weight: bold; }
            .footer { text-align: center; margin-top: 30px; color: #666; font-size: 12px; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h2>Nuova Richiesta Preventivo</h2>
                <h3>#$quote_number</h3>
            </div>
            <div class='content'>
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
    
    if (!empty($device_model)) {
        $email_body .= "
                <div class='field'>
                    <div class='label'>Marca e Modello:</div>
                    <div class='value'>$device_model</div>
                </div>";
    }
    
    $email_body .= "
                <div class='field'>
                    <div class='label'>Descrizione Problema:</div>
                    <div class='value'>" . nl2br($problem_description) . "</div>
                </div>
                <div class='field'>
                    <div class='label'>Priorità:</div>
                    <div class='value'><strong>" . ($priority_labels[$priority] ?? $priority) . "</strong></div>
                </div>";
    
    if (!empty($budget)) {
        $email_body .= "
                <div class='field'>
                    <div class='label'>Budget Indicativo:</div>
                    <div class='value'>€$budget</div>
                </div>";
    }
    
    if ($calculated_price > 0) {
        $email_body .= "
                <div class='price-box'>
                    <p>Preventivo Calcolato:</p>
                    <div class='amount'>€" . number_format($calculated_price, 2, ',', '.') . "</div>
                </div>";
        
        if (!empty($selected_device)) {
            $email_body .= "
                <div class='field'>
                    <div class='label'>Dispositivo Selezionato:</div>
                    <div class='value'>" . ucfirst($selected_device) . "</div>
                </div>";
        }
        
        if (!empty($services_list)) {
            $email_body .= "
                <div class='field'>
                    <div class='label'>Servizi Selezionati:</div>
                    <div class='value'><ul>$services_list</ul></div>
                </div>";
        }
    }
    
    $email_body .= "
                <div class='field'>
                    <div class='label'>Newsletter:</div>
                    <div class='value'>" . ($newsletter ? 'Iscritto' : 'Non iscritto') . "</div>
                </div>
            </div>
            <div class='footer'>
                <p>Preventivo richiesto il " . date('d/m/Y H:i') . "</p>
            </div>
        </div>
    </body>
    </html>
    ";
    
    // Send notification email
    send_email(EMAIL_INFO, $email_subject, $email_body);
    
    // Send confirmation to customer
    $customer_subject = "Preventivo ricevuto - " . SITE_NAME . " #$quote_number";
    $customer_body = "
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; text-align: center; }
            .content { padding: 30px; }
            .quote-box { background: #f8f9fa; padding: 20px; border-radius: 10px; text-align: center; margin: 20px 0; }
            .quote-number { font-size: 1.5rem; font-weight: bold; color: #4a00e0; }
            .price-estimate { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px; border-radius: 10px; text-align: center; margin: 20px 0; }
            .price-amount { font-size: 2.5rem; font-weight: bold; }
            .button { display: inline-block; padding: 12px 30px; background: #4a00e0; color: white; text-decoration: none; border-radius: 5px; margin-top: 20px; }
            .footer { background: #f8f9fa; padding: 20px; text-align: center; margin-top: 30px; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h1>Preventivo Ricevuto!</h1>
            </div>
            <div class='content'>
                <p>Ciao <strong>$name</strong>,</p>
                <p>Abbiamo ricevuto la tua richiesta di preventivo e ti ringraziamo per averci scelto.</p>
                
                <div class='quote-box'>
                    <p>Numero Preventivo:</p>
                    <div class='quote-number'>#$quote_number</div>
                </div>";
    
    if ($calculated_price > 0) {
        $customer_body .= "
                <div class='price-estimate'>
                    <p>Stima Preventivo:</p>
                    <div class='price-amount'>€" . number_format($calculated_price, 2, ',', '.') . "</div>
                    <p style='font-size: 0.9rem; opacity: 0.9;'>*Prezzo indicativo soggetto a conferma</p>
                </div>";
    }
    
    $customer_body .= "
                <h3>Cosa succede ora?</h3>
                <ol>
                    <li>I nostri tecnici analizzeranno la tua richiesta</li>
                    <li>Ti invieremo un preventivo dettagliato entro <strong>24 ore</strong></li>
                    <li>Il preventivo sarà valido per 30 giorni</li>
                    <li>Non ci sono obblighi di accettazione</li>
                </ol>
                
                <p><strong>Riepilogo della tua richiesta:</strong></p>
                <div style='background: #f8f9fa; padding: 15px; border-radius: 5px;'>
                    <p><strong>Problema:</strong><br>" . nl2br($problem_description) . "</p>
                    <p><strong>Priorità:</strong> " . ($priority_labels[$priority] ?? $priority) . "</p>
                </div>
                
                <p>Per qualsiasi domanda, non esitare a contattarci:</p>
                <ul>
                    <li>Telefono: <strong>" . PHONE_PRIMARY . "</strong></li>
                    <li>WhatsApp: <strong>" . WHATSAPP_NUMBER . "</strong></li>
                    <li>Email: <strong>" . EMAIL_INFO . "</strong></li>
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
    
    send_email($email, $customer_subject, $customer_body);
    
    // Set success message
    $_SESSION['success'] = "Preventivo richiesto con successo! Numero preventivo: #$quote_number";
    $_SESSION['quote_number'] = $quote_number;
    
    // Clear form data
    unset($_SESSION['form_data']);
    
    // Redirect back
    header('Location: ' . url('preventivo.php?success=1&quote=' . $quote_number));
    exit;
    
} catch (Exception $e) {
    error_log("Quote form error: " . $e->getMessage());
    $_SESSION['error'] = 'Si è verificato un errore. Riprova più tardi.';
    $_SESSION['form_data'] = $_POST;
    header('Location: ' . url('preventivo.php'));
    exit;
}
?>