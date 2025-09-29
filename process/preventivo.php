<?php
/**
 * Key Soft Italia - Process Quote Request
 * Gestisce l'invio del form richiesta preventivo
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
    header('Location: ' . url('preventivo.php'));
    exit;
}

// Validate CSRF token
if (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
    FlashMessage::error('Sessione scaduta. Riprova.');
    header('Location: ' . url('preventivo.php'));
    exit;
}

// Collect and sanitize form data
$data = [
    'first_name' => sanitize_input($_POST['firstName'] ?? ''),
    'last_name' => sanitize_input($_POST['lastName'] ?? ''),
    'email' => sanitize_input($_POST['email'] ?? ''),
    'phone' => sanitize_input($_POST['phone'] ?? ''),
    'company' => sanitize_input($_POST['company'] ?? ''),
    'service_type' => sanitize_input($_POST['serviceType'] ?? ''),
    'urgency' => sanitize_input($_POST['urgency'] ?? 'normal'),
    'budget' => sanitize_input($_POST['budget'] ?? ''),
    'description' => sanitize_input($_POST['description'] ?? ''),
    'device_info' => sanitize_input($_POST['deviceInfo'] ?? ''),
    'pickup_requested' => isset($_POST['pickup']) ? 1 : 0,
    'warranty_extension' => isset($_POST['warranty']) ? 1 : 0,
    'newsletter' => isset($_POST['newsletter']) ? 1 : 0
];

// Add tracking data
$data['ip_address'] = $_SERVER['REMOTE_ADDR'] ?? null;
$data['status'] = 'pending';

// Generate quote number
$data['quote_number'] = generateQuoteNumber();

// Calculate validity date (30 days from now)
$validityDays = 30; // Could be from settings
$data['quote_valid_until'] = date('Y-m-d', strtotime("+{$validityDays} days"));

// Validation
$errors = [];

if (empty($data['first_name'])) {
    $errors[] = 'Il nome è obbligatorio';
}

if (empty($data['last_name'])) {
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

if (empty($data['service_type'])) {
    $errors[] = 'Seleziona il tipo di servizio';
}

if (empty($data['description'])) {
    $errors[] = 'Descrivi il servizio richiesto';
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
    header('Location: ' . url('preventivo.php'));
    exit;
}

try {
    // Initialize database
    $db = new Database();
    
    // Insert quote request
    $quoteId = $db->insert('quotes', $data);
    
    if ($quoteId) {
        // Subscribe to newsletter if requested
        if ($data['newsletter'] && !empty($data['email'])) {
            $existingSubscriber = $db->select(
                'newsletter_subscribers',
                'id',
                'email = :email',
                ['email' => $data['email']]
            );
            
            if (empty($existingSubscriber)) {
                $db->insert('newsletter_subscribers', [
                    'email' => $data['email'],
                    'name' => $data['first_name'] . ' ' . $data['last_name'],
                    'status' => 'active',
                    'token' => bin2hex(random_bytes(32)),
                    'ip_address' => $data['ip_address']
                ]);
            }
        }
        
        // Send confirmation email to customer
        sendQuoteConfirmation($data);
        
        // Send notification to sales team
        sendSalesNotification($data);
        
        // Clear form data
        unset($_SESSION['form_data']);
        
        // Success message
        FlashMessage::success(
            "Richiesta di preventivo inviata con successo!<br>" .
            "Il tuo numero preventivo è: <strong>{$data['quote_number']}</strong><br>" .
            "Ti invieremo il preventivo dettagliato entro 24 ore lavorative."
        );
        
        // Store quote number in session
        $_SESSION['last_quote'] = $data['quote_number'];
        
        // Log activity
        logActivity('quote_request_created', $quoteId, $data);
        
    } else {
        throw new Exception('Errore nel salvataggio della richiesta');
    }
    
} catch (Exception $e) {
    error_log('Quote form error: ' . $e->getMessage());
    
    FlashMessage::error('Si è verificato un errore nell\'invio della richiesta. Riprova più tardi.');
    $_SESSION['form_data'] = $_POST;
}

// Redirect back
header('Location: ' . url('preventivo.php'));
exit;

/**
 * Generate unique quote number
 */
function generateQuoteNumber() {
    $prefix = 'QT';
    $year = date('Y');
    
    try {
        $db = new Database();
        $lastQuote = $db->query("
            SELECT quote_number 
            FROM quotes 
            WHERE quote_number LIKE :pattern 
            ORDER BY id DESC 
            LIMIT 1
        ")
        ->bind(':pattern', $prefix . $year . '%')
        ->single();
        
        if ($lastQuote) {
            $lastNumber = intval(substr($lastQuote['quote_number'], -5));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
    } catch (Exception $e) {
        $newNumber = 1;
    }
    
    // Format: QT202400001
    return $prefix . $year . str_pad($newNumber, 5, '0', STR_PAD_LEFT);
}

/**
 * Send confirmation email to customer
 */
function sendQuoteConfirmation($data) {
    $to = $data['email'];
    $subject = "Richiesta Preventivo #{$data['quote_number']} - Key Soft Italia";
    
    $serviceTypes = [
        'riparazione-smartphone' => 'Riparazione Smartphone',
        'riparazione-tablet' => 'Riparazione Tablet',
        'riparazione-computer' => 'Riparazione Computer/Notebook',
        'riparazione-console' => 'Riparazione Console Gaming',
        'vendita-ricondizionati' => 'Vendita Prodotti Ricondizionati',
        'vendita-accessori' => 'Vendita Accessori',
        'assistenza-software' => 'Assistenza Software',
        'recupero-dati' => 'Recupero Dati',
        'sviluppo-software' => 'Sviluppo Software',
        'sviluppo-web' => 'Sviluppo Sito Web',
        'sviluppo-app' => 'Sviluppo App Mobile',
        'consulenza-it' => 'Consulenza IT',
        'altro' => 'Altro'
    ];
    
    $serviceTypeText = $serviceTypes[$data['service_type']] ?? $data['service_type'];
    
    $urgencyText = [
        'normale' => 'Normale (3-5 giorni)',
        'urgente' => 'Urgente (24-48 ore)',
        'molto-urgente' => 'Molto Urgente (entro 24 ore)'
    ][$data['urgency']] ?? 'Normale';
    
    $message = "
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; }
            .container { max-width: 600px; margin: 0 auto; }
            .header { background: linear-gradient(135deg, #ff6b00, #ff8800); color: white; padding: 30px; text-align: center; }
            .quote-box { background: #f9f9f9; padding: 20px; margin: 20px 0; border-left: 4px solid #ff6b00; }
            .content { padding: 30px; }
            .details { background: white; padding: 20px; border: 1px solid #ddd; border-radius: 5px; }
            .footer { padding: 20px; text-align: center; color: #666; font-size: 12px; background: #f5f5f5; }
            .button { display: inline-block; padding: 12px 30px; background: #ff6b00; color: white; text-decoration: none; border-radius: 5px; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h1>Richiesta Preventivo Ricevuta</h1>
                <p>Ti prepareremo un preventivo personalizzato</p>
            </div>
            
            <div class='quote-box'>
                <h2 style='margin: 0;'>Preventivo N°: {$data['quote_number']}</h2>
                <p style='margin: 5px 0;'>Validità: 30 giorni dalla data di emissione</p>
            </div>
            
            <div class='content'>
                <p>Ciao {$data['first_name']},</p>
                <p>Abbiamo ricevuto la tua richiesta di preventivo e la stiamo elaborando con la massima attenzione.</p>
                
                <h3>Riepilogo della Richiesta:</h3>
                <div class='details'>
                    <p><strong>Servizio Richiesto:</strong> {$serviceTypeText}</p>
                    <p><strong>Urgenza:</strong> {$urgencyText}</p>
                    <p><strong>Budget Indicativo:</strong> {$data['budget']}</p>
                    <p><strong>Descrizione:</strong><br>" . nl2br($data['description']) . "</p>
                    " . (!empty($data['device_info']) ? "<p><strong>Info Dispositivo:</strong> {$data['device_info']}</p>" : "") . "
                    <p><strong>Servizi Aggiuntivi:</strong></p>
                    <ul>
                        <li>Ritiro a domicilio: " . ($data['pickup_requested'] ? 'Sì' : 'No') . "</li>
                        <li>Estensione garanzia: " . ($data['warranty_extension'] ? 'Sì' : 'No') . "</li>
                    </ul>
                </div>
                
                <h3>Tempi di Risposta:</h3>
                <p>Riceverai il preventivo dettagliato entro:</p>
                <ul>
                    <li><strong>Richieste normali:</strong> 24 ore lavorative</li>
                    <li><strong>Richieste urgenti:</strong> 12 ore lavorative</li>
                    <li><strong>Richieste molto urgenti:</strong> 4 ore lavorative</li>
                </ul>
                
                <p>Il preventivo includerà:</p>
                <ul>
                    <li>Descrizione dettagliata dei servizi</li>
                    <li>Costi trasparenti e senza sorprese</li>
                    <li>Tempi di realizzazione stimati</li>
                    <li>Condizioni di garanzia</li>
                    <li>Modalità di pagamento</li>
                </ul>
                
                <p style='text-align: center; margin-top: 30px;'>
                    <a href='" . BASE_URL . "' class='button'>Visita il nostro sito</a>
                </p>
                
                <p>Per informazioni immediate puoi contattarci:</p>
                <ul>
                    <li>Telefono: " . PHONE_PRIMARY . "</li>
                    <li>WhatsApp: " . PHONE_SECONDARY . "</li>
                    <li>Email: " . EMAIL_INFO . "</li>
                </ul>
            </div>
            
            <div class='footer'>
                <p><strong>Key Soft Italia</strong><br>
                L'universo della Tecnologia<br>
                Via Diaz, 46 - 74013 Ginosa (TA)<br>
                P.IVA: " . COMPANY_VAT . "</p>
                <p>Questa email è stata generata automaticamente.<br>
                Per rispondere, utilizza i contatti sopra indicati.</p>
            </div>
        </div>
    </body>
    </html>
    ";
    
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    $headers .= "From: " . EMAIL_INFO . "\r\n";
    
    @mail($to, $subject, $message, $headers);
}

/**
 * Send notification to sales team
 */
function sendSalesNotification($data) {
    $to = EMAIL_INFO;
    $urgencyFlag = $data['urgency'] === 'molto-urgente' ? '[URGENTE] ' : '';
    $subject = $urgencyFlag . "Nuova Richiesta Preventivo #{$data['quote_number']}";
    
    $message = "
    <html>
    <body style='font-family: Arial, sans-serif;'>
        <h2 style='color: #ff6b00;'>Nuova Richiesta Preventivo</h2>
        
        <table style='width: 100%; border-collapse: collapse;'>
            <tr>
                <td style='padding: 10px; background: #f5f5f5; font-weight: bold;'>N° Preventivo:</td>
                <td style='padding: 10px;'>{$data['quote_number']}</td>
            </tr>
            <tr>
                <td style='padding: 10px; background: #f5f5f5; font-weight: bold;'>Cliente:</td>
                <td style='padding: 10px;'>{$data['first_name']} {$data['last_name']}</td>
            </tr>
            <tr>
                <td style='padding: 10px; background: #f5f5f5; font-weight: bold;'>Email:</td>
                <td style='padding: 10px;'><a href='mailto:{$data['email']}'>{$data['email']}</a></td>
            </tr>
            <tr>
                <td style='padding: 10px; background: #f5f5f5; font-weight: bold;'>Telefono:</td>
                <td style='padding: 10px;'><a href='tel:{$data['phone']}'>{$data['phone']}</a></td>
            </tr>
            <tr>
                <td style='padding: 10px; background: #f5f5f5; font-weight: bold;'>Azienda:</td>
                <td style='padding: 10px;'>{$data['company']}</td>
            </tr>
            <tr>
                <td style='padding: 10px; background: #f5f5f5; font-weight: bold;'>Servizio:</td>
                <td style='padding: 10px;'>{$data['service_type']}</td>
            </tr>
            <tr>
                <td style='padding: 10px; background: #f5f5f5; font-weight: bold;'>Urgenza:</td>
                <td style='padding: 10px;'><strong style='color: " . 
                    ($data['urgency'] === 'molto-urgente' ? '#ff0000' : 
                    ($data['urgency'] === 'urgente' ? '#ff6b00' : '#28a745')) . 
                ";'>" . strtoupper($data['urgency']) . "</strong></td>
            </tr>
            <tr>
                <td style='padding: 10px; background: #f5f5f5; font-weight: bold;'>Budget:</td>
                <td style='padding: 10px;'>{$data['budget']}</td>
            </tr>
            <tr>
                <td style='padding: 10px; background: #f5f5f5; font-weight: bold; vertical-align: top;'>Descrizione:</td>
                <td style='padding: 10px;'>" . nl2br($data['description']) . "</td>
            </tr>
            <tr>
                <td style='padding: 10px; background: #f5f5f5; font-weight: bold;'>Info Dispositivo:</td>
                <td style='padding: 10px;'>{$data['device_info']}</td>
            </tr>
            <tr>
                <td style='padding: 10px; background: #f5f5f5; font-weight: bold;'>Opzioni:</td>
                <td style='padding: 10px;'>
                    Ritiro: " . ($data['pickup_requested'] ? 'SÌ' : 'NO') . "<br>
                    Garanzia Estesa: " . ($data['warranty_extension'] ? 'SÌ' : 'NO') . "<br>
                    Newsletter: " . ($data['newsletter'] ? 'SÌ' : 'NO') . "
                </td>
            </tr>
        </table>
        
        <p style='margin-top: 20px; padding: 15px; background: #fff3cd; border: 1px solid #ffc107;'>
            <strong>⏰ Tempo di risposta richiesto:</strong> " . 
            ($data['urgency'] === 'molto-urgente' ? 'ENTRO 4 ORE' : 
            ($data['urgency'] === 'urgente' ? 'Entro 12 ore' : 'Entro 24 ore')) . "
        </p>
        
        <p><a href='" . BASE_URL . "admin/preventivi/view.php?id={$data['quote_number']}' 
              style='display: inline-block; padding: 10px 20px; background: #ff6b00; color: white; text-decoration: none; border-radius: 5px;'>
              Gestisci Preventivo nel Pannello Admin
        </a></p>
    </body>
    </html>
    ";
    
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    $headers .= "From: " . EMAIL_NOREPLY . "\r\n";
    $headers .= "Reply-To: {$data['email']}\r\n";
    
    // High priority for urgent quotes
    if ($data['urgency'] === 'molto-urgente' || $data['urgency'] === 'urgente') {
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
            'entity_type' => 'quote',
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