<?php
declare(strict_types=1);
session_start();

require_once __DIR__ . '/../../config/config.php'; // $pdo, costanti, helpers
require_once __DIR__ . '/../php/functions.php'; // $pdo, costanti, helpers

header('Content-Type: application/json; charset=UTF-8');

$respond = function (bool $ok, string $msg, array $extra = [], int $code = 200) {
    http_response_code($code);
    echo json_encode(array_merge(['success' => $ok, 'message' => $msg], $extra));
    exit;
};

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $respond(false, 'Metodo non consentito.', [], 405);
}

// CSRF check
if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
    $respond(false, 'Richiesta non valida o sessione scaduta (CSRF). Ricarica la pagina e riprova.', [], 403);
}

// Honeypot check
if (!empty($_POST['website'] ?? '')) {
    $respond(true, 'Registrazione completata con successo! Avvio del download in corso...');
}

// Anti-flood check
$_SESSION['demo_flood'] = array_filter($_SESSION['demo_flood'] ?? [], fn($t) => $t > time() - 600);
if (count($_SESSION['demo_flood']) >= 5) {
    $respond(false, 'Hai effettuato troppe richieste di download. Riprova più tardi o contattaci.', [], 429);
}

// Data cleansing helpers
$clean = static function ($v, int $max = 100) {
    $v = trim((string)$v);
    $v = preg_replace('/\s+/u', ' ', $v);
    return mb_substr($v, 0, $max);
};
$phoneClean = static function ($p) {
    $p = preg_replace('/[^\d+\s]/', '', (string)$p);
    return mb_substr(trim($p), 0, 30);
};
$emailValid = static fn($e) => (bool)filter_var($e, FILTER_VALIDATE_EMAIL);

// Inputs
$name = $clean($_POST['name'] ?? '', 80);
$company = $clean($_POST['company'] ?? '', 100);
$email = $clean($_POST['email'] ?? '', 100);
$phone = $phoneClean($_POST['phone'] ?? '');
$city = $clean($_POST['city'] ?? '', 80);
$privacy = isset($_POST['privacy']) && in_array($_POST['privacy'], ['on', '1', 'true', 'yes'], true);

// Validation
$errors = [];
if (mb_strlen($name) < 2) {
    $errors['name'] = 'Il nome è obbligatorio.';
}
if (!$emailValid($email)) {
    $errors['email'] = 'Inserisci un indirizzo email valido.';
}
if (mb_strlen($phone) < 6) {
    $errors['phone'] = 'Inserisci un numero di telefono valido.';
}
if (!$privacy) {
    $errors['privacy'] = 'Devi accettare la privacy policy.';
}

if ($errors) {
    $respond(false, 'Controlla i campi inseriti.', ['errors' => $errors], 422);
}

try {
    // Insert request in database
    $stmt = $pdo->prepare("
        INSERT INTO liberty_demo_requests (name, company, email, phone, city) 
        VALUES (?, ?, ?, ?, ?)
    ");
    $stmt->execute([$name, $company, $email, $phone, $city]);

    $_SESSION['demo_flood'][] = time();

    // Trigger demo download url
    $demo_url = 'https://wsapp.libertycommerce.it/download/custom-download.php?q=05D6C34951ED78C8637220D873B7997E';

    $respond(true, 'Grazie per aver registrato la richiesta! Il download della demo di LibertyCommerce sta partendo.', [
        'download_url' => $demo_url
    ]);
} catch (PDOException $e) {
    $respond(false, 'Si è verificato un problema durante il salvataggio dei dati. Riprova più tardi.', [], 500);
}
