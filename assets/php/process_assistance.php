<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../assets/php/functions.php';
session_start();

// Honeypot
if (!empty($_POST['website'])) { http_response_code(400); exit('Bad request'); }

// CSRF (se già generi il token in sessione altrove)
if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
  http_response_code(403); exit('CSRF token invalid');
}

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

$res = send_assistance_email($data, [
  'to'        => EMAIL_ASSISTENZA,
  'from'      => EMAIL_FROM,
  'from_name' => SITE_NAME,
  'reply_to'  => $data['email'] ?: EMAIL_FROM,
]);

if ($res['ok']) {
  header('Location: ' . url('grazie.php?type=assistenza')); exit;
} else {
  error_log('Assist Email Error: ' . $res['error']);
  header('Location: ' . url('errore.php?code=assist_email')); exit;
}
