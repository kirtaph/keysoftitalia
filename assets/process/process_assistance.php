<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../assets/php/functions.php';
session_start();

// --- Basic anti-spam
if (!empty($_POST['website'])) {
  if (is_ajax_request()) {
    http_response_code(400);
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode(['ok'=>false, 'error'=>'bad_request']);
    exit;
  }
  header('Location: ' . url('errore.php?code=bad_request')); exit;
}

// --- CSRF
if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
  if (is_ajax_request()) {
    http_response_code(403);
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode(['ok'=>false, 'error'=>'csrf']);
    exit;
  }
  header('Location: ' . url('errore.php?code=csrf')); exit;
}

// --- Collect
$data = [
  'assistance_type'     => $_POST['assistance_type'] ?? '',
  'name'                => trim($_POST['name'] ?? ''),
  'phone'               => trim($_POST['phone'] ?? ''),
  'email'               => trim($_POST['email'] ?? ''),
  'device_type'         => trim($_POST['device_type'] ?? ''),
  'address'             => trim($_POST['address'] ?? ''),
  'problem_description' => trim($_POST['problem_description'] ?? ''),
  'urgency'             => trim($_POST['urgency'] ?? 'normale'),
  'time_preference'     => trim($_POST['time_preference'] ?? 'qualsiasi'),
];

// --- Send
$res = send_assistance_email($data, [
  'to'        => defined('EMAIL_ASSISTENZA') ? EMAIL_ASSISTENZA : 'info@tuodominio.it',
  'from'      => defined('EMAIL_FROM') ? EMAIL_FROM : 'no-reply@' . ($_SERVER['SERVER_NAME'] ?? 'tuodominio.it'),
  'from_name' => defined('SITE_NAME') ? SITE_NAME : 'Key Soft Italia',
  'reply_to'  => $data['email'] ?: (defined('EMAIL_FROM') ? EMAIL_FROM : ''),
]);

if (is_ajax_request()) {
  header('Content-Type: application/json; charset=UTF-8');
  if ($res['ok']) {
    echo json_encode([
      'ok'      => true,
      'message' => 'Richiesta inviata con successo. Ti contatteremo al più presto.'
    ]);
    exit;
  } else {
    http_response_code(500);
    echo json_encode([
      'ok'      => false,
      'error'   => 'send_failed',
      'message' => 'Si è verificato un errore durante l’invio. Riprova tra poco o utilizza WhatsApp.'
    ]);
    exit;
  }
}

// --- Fallback senza JS
if ($res['ok']) {
  header('Location: ' . url('grazie.php?type=assistenza')); exit;
} else {
  error_log('Assist Email Error: ' . $res['error']);
  header('Location: ' . url('errore.php?code=assist_email')); exit;
}
