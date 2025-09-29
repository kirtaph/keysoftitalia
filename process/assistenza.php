<?php
/**
 * Key Soft Italia - Process Assistance Request
 * Gestisce l'invio del form richiesta assistenza
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
    header('Location: ' . url('assistenza.php'));
    exit;
}

// Validate CSRF token
if (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
    FlashMessage::error('Sessione scaduta. Riprova.');
    header('Location: ' . url('assistenza.php'));
    exit;
}

// Collect and sanitize form data
$data = [
    'name' => sanitize_input($_POST['firstName'] ?? ''),
    'surname' => sanitize_input($_POST['lastName'] ?? ''),
    'email' => sanitize_input($_POST['email'] ?? ''),
    'phone' => sanitize_input($_POST['phone'] ?? ''),
    'company' => sanitize_input($_POST['company'] ?? ''),
    'device_type' => sanitize_input($_POST['deviceType'] ?? ''),
    'device_brand' => sanitize_input($_POST['deviceBrand'] ?? ''),
    'device_model' => sanitize_input($_POST['deviceModel'] ?? ''),
    'problem_type' => sanitize_input($_POST['problemType'] ?? ''),
    'problem_description' => sanitize_input($_POST['problemDescription'] ?? ''),
    'urgency' => sanitize_input($_POST['urgency'] ?? 'normal'),
    'pickup_requested' => isset($_POST['pickup']) ? 1 : 0,
    'backup_needed' => isset($_POST['backup']) ? 1 : 0
];

// Add tracking data
$data['ip_address'] = $_SERVER['REMOTE_ADDR'] ?? null;
$data['status'] = 'pending';

// Generate ticket number
$data['ticket_number'] = generateTicketNumber();

// Validation
$errors = [];

if (empty($data['name'])) {
    $errors[] = 'Il nome è obbligatorio';
}

if (empty($data['surname'])) {
    $errors[] = 'Il cognome è obbligatorio';
}

if (empty($data['email'])) {
    $errors[] = 'L\'email è obbligatoria';
} elseif (!is_valid_email($data['email'])) {
    $errors[] = 'Inserisci un\'email valida';
}

if (empty($data['phone'])) {
    $errors[] = 'Il telefono è obbligatorio';
} elseif (!is_valid_phone($data['phone'])) {
    $errors[] = 'Inserisci un numero di telefono valido';
}

if (empty($data['device_type'])) {
    $errors[] = 'Seleziona il tipo di dispositivo';
}

if (empty($data['problem_description'])) {
    $errors[] = 'Descrivi il problema riscontrato';
}

if (!isset($_POST['privacy']) || !$_POST['privacy']) {
    $errors[] = 'Devi accettare la privacy policy';
}

// Check for errors
if (!empty($errors)) {
    foreach ($errors as $error) {
        FlashMessage::error($error);
    }
    
    $_SESSION['form_data'] = $_POST;
    header('Location: ' . url('assistenza.php'));
    exit;
}

try {
    // Initialize database
    $db = new Database();
    
    // Insert assistance request
    $requestId = $db->insert('assistance_requests', $data);
    
    if ($requestId) {
        // Send confirmation email to customer
        sendConfirmationEmail($data);
        
        // Send notification to staff
        sendStaffNotification($data);
        
        // Clear form data
        unset($_SESSION['form_data']);
        
        // Success message with ticket number
        FlashMessage::success(
            "Richiesta di assistenza inviata con successo!<br>" .
            "Il tuo numero ticket è: <strong>{$data['ticket_number']}</strong><br>" .
            "Riceverai una email di conferma a breve."
        );
        
        // Store ticket number in session for display
        $_SESSION['last_ticket'] = $data['ticket_number'];
        
        // Log activity
        logActivity('assistance_request_created', $requestId, $data);
        
    } else {
        throw new Exception('Errore nel salvataggio della richiesta');
    }
    
} catch (Exception $e) {
    error_log('Assistance form error: ' . $e->getMessage());
    
    FlashMessage::error('Si è verificato un errore nell\'invio della richiesta. Riprova più tardi.');
    $_SESSION['form_data'] = $_POST;
}

// Redirect back
header('Location: ' . url('assistenza.php'));
exit;

/**
 * Generate unique ticket number
 */
function generateTicketNumber() {
    $prefix = 'TK';
    $year = date('Y');
    $month = date('m');
    
    // Get last ticket number for this month
    try {
        $db = new Database();
        $lastTicket = $db->query("
            SELECT ticket_number 
            FROM assistance_requests 
            WHERE ticket_number LIKE :pattern 
            ORDER BY id DESC 
            LIMIT 1
        ")
        ->bind(':pattern', $prefix . $year . $month . '%')
        ->single();
        
        if ($lastTicket) {
            // Extract number and increment
            $lastNumber = intval(substr($lastTicket['ticket_number'], -4));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
    } catch (Exception $e) {
        $newNumber = 1;
    }
    
    // Format: TK202401001
    return $prefix . $year . $month . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
}

/**
 * Send confirmation email to customer
 */
function sendConfirmationEmail($data) {
    $to = $data['email'];
    $subject = "Richiesta Assistenza #{$data['ticket_number']} - Key Soft Italia";
    
    $urgencyText = [
        'low' => 'Bassa',
        'normal' => 'Normale',
        'high' => 'Alta',
        'critical' => 'Critica'
    ][$data['urgency']] ?? 'Normale';
    
    $message = "
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; }
            .container { max-width: 600px; margin: 0 auto; }
            .header { background: #ff6b00; color: white; padding: 30px; text-align: center; }
            .ticket-box { background: #f9f9f9; padding: 20px; margin: 20px 0; border-left: 4px solid #ff6b00; }
            .content { padding: 30px; }
            .details { background: white; padding: 20px; border: 1px solid #ddd; }
            .footer { padding: 20px; text-align: center; color: #666; font-size: 12px; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h1>Richiesta Assistenza Ricevuta</h1>
                <p>Ti confermiamo la ricezione della tua richiesta</p>
            </div>
            
            <div class='ticket-box'>
                <h2 style='margin: 0;'>Numero Ticket: {$data['ticket_number']}</h2>
                <p style='margin: 5px 0;'>Conserva questo numero per riferimento</p>
            </div>
            
            <div class='content'>
                <h3>Dettagli della Richiesta:</h3>
                <div class='details'>
                    <p><strong>Dispositivo:</strong> {$data['device_type']} - {$data['device_brand']} {$data['device_model']}</p>
                    <p><strong>Tipo Problema:</strong> {$data['problem_type']}</p>
                    <p><strong>Descrizione:</strong><br>" . nl2br($data['problem_description']) . "</p>
                    <p><strong>Urgenza:</strong> {$urgencyText}</p>
                    <p><strong>Ritiro a domicilio:</strong> " . ($data['pickup_requested'] ? 'Sì' : 'No') . "</p>
                    <p><strong>Backup richiesto:</strong> " . ($data['backup_needed'] ? 'Sì' : 'No') . "</p>
                </div>
                
                <h3>Prossimi Passi:</h3>
                <ol>
                    <li>Un nostro tecnico esaminerà la tua richiesta</li>
                    <li>Ti contatteremo entro 24 ore lavorative</li>
                    <li>Concorderemo insieme le modalità di intervento</li>
                </ol>
                
                <p><strong>Tempi di risposta stimati:</strong></p>
                <ul>
                    <li>Urgenza Critica: entro 4 ore</li>
                    <li>Urgenza Alta: entro 12 ore</li>
                    <li>Urgenza Normale: entro 24 ore</li>
                </ul>
                
                <p>Per informazioni urgenti puoi contattarci:</p>
                <ul>
                    <li>Telefono: " . PHONE_PRIMARY . "</li>
                    <li>WhatsApp: " . PHONE_SECONDARY . "</li>
                    <li>Email: " . EMAIL_SUPPORT . "</li>
                </ul>
            </div>
            
            <div class='footer'>
                <p>Key Soft Italia - L'universo della Tecnologia<br>
                Via Diaz, 46 - 74013 Ginosa (TA)<br>
                www.keysoftitalia.it</p>
            </div>
        </div>
    </body>
    </html>
    ";
    
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    $headers .= "From: " . EMAIL_SUPPORT . "\r\n";
    
    @mail($to, $subject, $message, $headers);
}

/**
 * Send notification to staff
 */
function sendStaffNotification($data) {
    $to = EMAIL_SUPPORT;
    $subject = "[URGENZA: " . strtoupper($data['urgency']) . "] Nuova Richiesta Assistenza #{$data['ticket_number']}";
    
    $message = "
    <html>
    <head>
        <style>
            .urgent { background: #ff0000; color: white; padding: 10px; }
            .high { background: #ff6b00; color: white; padding: 10px; }
            .normal { background: #28a745; color: white; padding: 10px; }
        </style>
    </head>
    <body>
        <h2 class='{$data['urgency']}'>Nuova Richiesta Assistenza - Urgenza: " . strtoupper($data['urgency']) . "</h2>
        
        <h3>Ticket: {$data['ticket_number']}</h3>
        
        <h4>Cliente:</h4>
        <p>
            {$data['name']} {$data['surname']}<br>
            Email: {$data['email']}<br>
            Telefono: {$data['phone']}<br>
            Azienda: {$data['company']}
        </p>
        
        <h4>Dispositivo:</h4>
        <p>
            Tipo: {$data['device_type']}<br>
            Marca: {$data['device_brand']}<br>
            Modello: {$data['device_model']}
        </p>
        
        <h4>Problema:</h4>
        <p>
            Tipo: {$data['problem_type']}<br>
            Descrizione: " . nl2br($data['problem_description']) . "
        </p>
        
        <h4>Servizi Aggiuntivi:</h4>
        <p>
            Ritiro a domicilio: " . ($data['pickup_requested'] ? 'SÌ' : 'NO') . "<br>
            Backup richiesto: " . ($data['backup_needed'] ? 'SÌ' : 'NO') . "
        </p>
        
        <hr>
        <p><a href='" . BASE_URL . "admin/assistenza/view.php?ticket={$data['ticket_number']}'>Visualizza nel pannello admin</a></p>
    </body>
    </html>
    ";
    
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    $headers .= "From: " . EMAIL_NOREPLY . "\r\n";
    $headers .= "Reply-To: {$data['email']}\r\n";
    
    // Set priority header for urgent requests
    if ($data['urgency'] === 'critical' || $data['urgency'] === 'high') {
        $headers .= "X-Priority: 1\r\n";
        $headers .= "Importance: High\r\n";
    }
    
    @mail($to, $subject, $message, $headers);
}

/**
 * Log activity
 */
function logActivity($action, $entityId, $details) {
    try {
        $db = new Database();
        $db->insert('activity_log', [
            'action' => $action,
            'entity_type' => 'assistance_request',
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