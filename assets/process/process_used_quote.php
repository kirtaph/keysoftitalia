<?php
/**
 * Processa richiesta "Valuta il tuo Usato"
 * Salva in used_device_quotes e ritorna JSON.
 */

if (!defined('BASE_PATH')) {
    define('BASE_PATH', rtrim(str_replace('\\', '/', dirname(__DIR__, 2)), '/') . '/');
}

require_once BASE_PATH . 'config/config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json; charset=utf-8');

/**
 * Ritorna JSON e termina
 */
if (!function_exists('respond')) {
  function respond(array $payload, int $status = 200): void {
      http_response_code($status);
      echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
      exit;
  }
}

/**
 * Normalizza testo
 */
if (!function_exists('norm')) {
  function norm(?string $s): string {
      $s = (string)($s ?? '');
      $s = trim(preg_replace('/\s+/', ' ', $s));
      return $s;
  }
}

/**
 * PDO helper
 */
if (!function_exists('get_pdo')) {
  function get_pdo(): PDO {
      global $pdo, $db_dsn, $db_user, $db_pass;
      if ($pdo instanceof PDO) {
          return $pdo;
      }
      $pdo = new PDO($db_dsn, $db_user, $db_pass, [
          PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
          PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
      ]);
      return $pdo;
  }
}

/**
 * Ricava device_id da slug device_type (smartphone, tablet, ecc.)
 * NB: stessa logica usata in process_quote.php
 */
if (!function_exists('get_device_id_by_slug')) {
  function get_device_id_by_slug(PDO $pdo, string $slug): ?int {
      $slug = strtolower(trim($slug));
      if ($slug === '') return null;

      $stmt = $pdo->prepare("SELECT id FROM devices WHERE slug = :slug LIMIT 1");
      $stmt->execute([':slug' => $slug]);
      $row = $stmt->fetch();
      return $row ? (int)$row['id'] : null;
  }
}

/**
 * Rileva AJAX
 */
if (!function_exists('is_ajax_request')) {
  function is_ajax_request(): bool {
      return (
          !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
          strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest'
      );
  }
}

/**
 * CSRF (snippet standard di Patrizio)
 */
if (empty($_POST['csrf_token']) || empty($_SESSION['csrf_token']) ||
    !hash_equals((string)$_SESSION['csrf_token'], (string)$_POST['csrf_token'])) {

    if (is_ajax_request()) {
        http_response_code(403);
        echo json_encode(['ok' => false, 'error' => 'csrf'], JSON_UNESCAPED_UNICODE);
        exit;
    }

    header('Location: ' . url('errore.php?code=csrf'));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    respond(['ok' => false, 'message' => 'Metodo non consentito.'], 405);
}

/**
 * Honeypot antispam opzionale
 */
if (!empty($_POST['website'] ?? '')) {
    respond(['ok' => false, 'message' => 'Spam rilevato.'], 400);
}

try {
    $pdo = get_pdo();

    // --- input base
    $deviceType = strtolower(norm($_POST['device'] ?? $_POST['device_type'] ?? ''));
    if ($deviceType === '') {
        respond(['ok' => false, 'message' => 'Tipo di dispositivo mancante.'], 422);
    }

    $brandId   = isset($_POST['brand_id']) && $_POST['brand_id'] !== '' ? (int)$_POST['brand_id'] : null;
    $brandSel  = norm($_POST['brand'] ?? '');
    $brandOther = norm($_POST['brand_other'] ?? '');
    
    // Se abbiamo un brandId, recuperiamo il nome dal DB per sicurezza
    if ($brandId) {
        $stmtB = $pdo->prepare("SELECT name FROM brands WHERE id = ?");
        $stmtB->execute([$brandId]);
        $bRow = $stmtB->fetch();
        if ($bRow) {
            $brandName = $bRow['name'];
        } else {
            $brandName = $brandSel; // Fallback
        }
    } else {
        $brandName = $brandOther !== '' ? $brandOther : $brandSel;
    }

    $modelId = isset($_POST['model_id']) && $_POST['model_id'] !== '' ? (int)$_POST['model_id'] : null;
    $model   = norm($_POST['model'] ?? '');
    $modelOther = norm($_POST['model_other'] ?? '');

    // Se abbiamo un modelId, recuperiamo il nome dal DB
    if ($modelId) {
        $stmtM = $pdo->prepare("SELECT name FROM models WHERE id = ?");
        $stmtM->execute([$modelId]);
        $mRow = $stmtM->fetch();
        if ($mRow) {
            $modelName = $mRow['name'];
        } else {
            $modelName = $model; // Fallback
        }
    } else {
        $modelName = $modelOther !== '' ? $modelOther : $model;
    }

    $condition = strtolower(norm($_POST['device_condition'] ?? ''));
    $allowedConditions = ['ottimo','buono','usurato','danneggiato'];
    if (!in_array($condition, $allowedConditions, true)) {
        respond(['ok' => false, 'message' => 'Stato del dispositivo non valido.'], 422);
    }

    // Difetti
    $defects = $_POST['defects'] ?? [];
    if (is_string($defects)) {
        $decoded = json_decode($defects, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            $defects = $decoded;
        } else {
            $defects = [];
        }
    }
    if (!is_array($defects)) {
        $defects = [];
    }
    $defects = array_values(array_filter(array_map('norm', $defects)));

    // Accessori
    $accessories = $_POST['accessories'] ?? [];
    if (is_string($accessories)) {
        $decoded = json_decode($accessories, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            $accessories = $decoded;
        } else {
            $accessories = [];
        }
    }
    if (!is_array($accessories)) {
        $accessories = [];
    }
    $accessories = array_values(array_filter(array_map('norm', $accessories)));

    // Valore richiesto
    $expectedRaw = str_replace(',', '.', trim((string)($_POST['expected_price'] ?? '')));
    $expectedPrice = null;
    if ($expectedRaw !== '' && is_numeric($expectedRaw)) {
        $expectedPrice = (float)$expectedRaw;
    }

    $notes = norm($_POST['notes'] ?? '');

    // Dati cliente
    $firstName = norm($_POST['firstName'] ?? $_POST['customer_first_name'] ?? '');
    $lastName  = norm($_POST['lastName'] ?? $_POST['customer_last_name'] ?? '');
    $email     = norm($_POST['email'] ?? $_POST['customer_email'] ?? '');
    $phone     = norm($_POST['phone'] ?? $_POST['customer_phone'] ?? '');

    if ($firstName === '' || $lastName === '' || $email === '' || $phone === '') {
        respond(['ok' => false, 'message' => 'Compila tutti i campi obbligatori dei dati di contatto.'], 422);
    }

    $privacyAccepted = !empty($_POST['privacy']) || !empty($_POST['privacy_accepted']);
    if (!$privacyAccepted) {
        respond(['ok' => false, 'message' => 'Devi accettare la Privacy Policy per inviare la richiesta.'], 422);
    }

    $contactChannel = strtolower(norm($_POST['contact_channel'] ?? 'form'));
    if ($contactChannel === '') {
        $contactChannel = 'form';
    }

    // Device id opzionale (nessun errore se non trovato)
    $deviceId = get_device_id_by_slug($pdo, $deviceType);

    // IP / UA
    $ip = $_SERVER['REMOTE_ADDR'] ?? null;
    $ua = substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 255);

    // Insert
    $sql = "INSERT INTO used_device_quotes
              (device_type, device_id,
               brand_id, brand_name, model_id, model_name,
               device_condition, defects, accessories,
               expected_price, notes,
               customer_first_name, customer_last_name, customer_email, customer_phone,
               contact_channel, privacy_accepted, status,
               ip_address, user_agent)
            VALUES
              (:device_type, :device_id,
               :brand_id, :brand_name, :model_id, :model_name,
               :device_condition, :defects, :accessories,
               :expected_price, :notes,
               :first_name, :last_name, :email, :phone,
               :contact_channel, :privacy_accepted, 'pending',
               " . ($ip ? "INET6_ATON(:ip_address)" : "NULL") . ", :user_agent)";

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':device_type', $deviceType);
    $stmt->bindValue(':device_id', $deviceId, $deviceId === null ? PDO::PARAM_NULL : PDO::PARAM_INT);

    $stmt->bindValue(':brand_id', $brandId, $brandId === null ? PDO::PARAM_NULL : PDO::PARAM_INT);
    $stmt->bindValue(':brand_name', $brandName !== '' ? $brandName : null, $brandName !== '' ? PDO::PARAM_STR : PDO::PARAM_NULL);

    $stmt->bindValue(':model_id', $modelId, $modelId === null ? PDO::PARAM_NULL : PDO::PARAM_INT);
    $stmt->bindValue(':model_name', $modelName !== '' ? $modelName : null, $modelName !== '' ? PDO::PARAM_STR : PDO::PARAM_NULL);

    $stmt->bindValue(':device_condition', $condition);

    $stmt->bindValue(':defects', $defects ? json_encode($defects, JSON_UNESCAPED_UNICODE) : null, $defects ? PDO::PARAM_STR : PDO::PARAM_NULL);
    $stmt->bindValue(':accessories', $accessories ? json_encode($accessories, JSON_UNESCAPED_UNICODE) : null, $accessories ? PDO::PARAM_STR : PDO::PARAM_NULL);

    if ($expectedPrice !== null) {
        $stmt->bindValue(':expected_price', $expectedPrice);
    } else {
        $stmt->bindValue(':expected_price', null, PDO::PARAM_NULL);
    }

    $stmt->bindValue(':notes', $notes !== '' ? $notes : null, $notes !== '' ? PDO::PARAM_STR : PDO::PARAM_NULL);

    $stmt->bindValue(':first_name', $firstName);
    $stmt->bindValue(':last_name',  $lastName);
    $stmt->bindValue(':email',      $email);
    $stmt->bindValue(':phone',      $phone);

    $stmt->bindValue(':contact_channel', $contactChannel);
    $stmt->bindValue(':privacy_accepted', $privacyAccepted ? 1 : 0, PDO::PARAM_INT);

    if ($ip) {
        $stmt->bindValue(':ip_address', $ip);
    }
    $stmt->bindValue(':user_agent', $ua);

    $stmt->execute();
    $newId = (int)$pdo->lastInsertId();

    // Messaggio WhatsApp precompilato (per il tecnico)
    $payload = [
        'device_label' => ucfirst($deviceType),
        'brand'        => $brandName,
        'model'        => $modelName,
        'condition'    => $condition,
        'defects'      => $defects,
        'accessories'  => $accessories,
        'expected'     => $expectedPrice,
        'notes'        => $notes,
        'customer'     => [
          'firstName' => $firstName,
          'lastName'  => $lastName,
          'email'     => $email,
          'phone'     => $phone,
        ]
    ];

    $lines = [];
    $lines[] = '💬 *Nuova richiesta valutazione usato*';
    $lines[] = 'ID richiesta: #' . $newId;
    $lines[] = '';
    $lines[] = '• Dispositivo: ' . ($payload['device_label'] ?: '—');
    $lines[] = '• Marca: ' . ($payload['brand'] ?: '—');
    $lines[] = '• Modello: ' . ($payload['model'] ?: '—');
    $lines[] = '• Stato: ' . ($payload['condition'] ?: '—');

    if (!empty($defects)) {
        $lines[] = '• Problemi: ' . implode(', ', $defects);
    }
    if (!empty($accessories)) {
        $lines[] = '• Accessori: ' . implode(', ', $accessories);
    }
    if ($expectedPrice !== null) {
        $lines[] = '• Valore richiesto: € ' . number_format($expectedPrice, 2, ',', '.');
    }
    if ($notes !== '') {
        $lines[] = '';
        $lines[] = 'Note: ' . $notes;
    }
    $lines[] = '';
    $lines[] = '📞 Cliente: ' . trim($firstName . ' ' . $lastName);
    $lines[] = 'Email: ' . $email;
    $lines[] = 'Telefono: ' . $phone;

    $waMessage = implode("\n", $lines);

    // --- INVIO EMAIL (se canale = form) ---
    $mailResult = null;
    if ($contactChannel === 'form' && function_exists('send_assistance_email')) {
        // Costruiamo descrizione per la mail (simile a WA ma plain text per il campo 'problem_description')
        $mailDesc = "Richiesta Valutazione Usato #{$newId}\n\n";
        $mailDesc .= "Dispositivo: " . ($payload['device_label'] ?: '—') . "\n";
        $mailDesc .= "Marca: " . ($payload['brand'] ?: '—') . "\n";
        $mailDesc .= "Modello: " . ($payload['model'] ?: '—') . "\n";
        $mailDesc .= "Stato: " . ($payload['condition'] ?: '—') . "\n";
        
        if (!empty($defects)) {
            $mailDesc .= "Problemi: " . implode(', ', $defects) . "\n";
        }
        if (!empty($accessories)) {
            $mailDesc .= "Accessori: " . implode(', ', $accessories) . "\n";
        }
        if ($expectedPrice !== null) {
            $mailDesc .= "Valore richiesto: € " . number_format($expectedPrice, 2, ',', '.') . "\n";
        }
        if ($notes !== '') {
            $mailDesc .= "\nNote:\n" . $notes . "\n";
        }

        $mailData = [
            'assistance_type'     => 'VALUTAZIONE USATO',
            'name'                => trim($firstName . ' ' . $lastName),
            'phone'               => $phone,
            'email'               => $email,
            'device_type'         => ucfirst($deviceType) . ' ' . $brandName . ' ' . $modelName,
            'address'             => '', // non serve
            'problem_description' => $mailDesc,
            'urgency'             => 'normale',
            'time_preference'     => 'qualsiasi',
        ];

        $mailOpts = [
            'subject'  => 'Richiesta Valutazione Usato - ' . trim($firstName . ' ' . $lastName),
            'reply_to' => $email ?: null,
        ];

        $mailResult = send_assistance_email($mailData, $mailOpts);
    }

    respond([
        'ok'         => true,
        'id'         => $newId,
        'message'    => 'Richiesta di valutazione registrata correttamente.',
        'wa_message' => $waMessage,
        'mail_sent'  => $mailResult // per debug o log lato client
    ]);
} catch (Throwable $e) {
    if (defined('APP_DEBUG') && APP_DEBUG) {
        respond(['ok' => false, 'message' => 'Errore: ' . $e->getMessage()], 500);
    }
    respond(['ok' => false, 'message' => 'Si è verificato un errore inatteso.'], 500);
}
