<?php
// assets/ajax/get_flyers.php

if (!defined('BASE_PATH')) {
    define('BASE_PATH', dirname(dirname(__DIR__)) . '/');
    require_once BASE_PATH . 'config/config.php';
}

// Ensure APP_DEBUG is defined
if (!defined('APP_DEBUG')) {
    define('APP_DEBUG', false);
}

header('Content-Type: application/json; charset=utf-8');

try {
    if (!isset($pdo) || !$pdo instanceof PDO) {
        throw new RuntimeException('Nessuna connessione al database.');
    }

    $status = isset($_GET['status']) ? trim((string)$_GET['status']) : 'all';
    if ($status === '') {
        $status = 'all';
    }

    $today = date('Y-m-d');

    // Base query: solo volantini pubblicati
    $sql    = "SELECT id, title, slug, description, start_date, end_date, status, show_home, cover_image, pdf_file
               FROM flyers
               WHERE status = 1";
    $params = [];

    switch ($status) {
        case 'upcoming':
            $sql    .= " AND start_date > :today";
            $params[':today'] = $today;
            $sql    .= " ORDER BY start_date ASC";
            break;

        case 'archived':
            $sql    .= " AND end_date < :today";
            $params[':today'] = $today;
            $sql    .= " ORDER BY end_date DESC";
            break;

        case 'all':
            $sql    .= " ORDER BY start_date DESC";
            break;

        case 'current':
        default:
            $status = 'current';
            $sql   .= " AND start_date <= :today AND end_date >= :today";
            $params[':today'] = $today;
            $sql   .= " ORDER BY start_date ASC";
            break;
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $baseUrl = rtrim(BASE_URL, '/') . '/';

    $flyers = [];
    foreach ($rows as $row) {
        $code = 'archived';
        if ($row['start_date'] > $today) {
            $code = 'upcoming';
        } elseif ($row['start_date'] <= $today && $row['end_date'] >= $today) {
            $code = 'current';
        }

        switch ($code) {
            case 'current':
                $label = 'In corso';
                break;
            case 'upcoming':
                $label = 'In arrivo';
                break;
            case 'archived':
            default:
                $label = 'Scaduto';
                $code  = 'archived';
                break;
        }

        $coverUrl = !empty($row['cover_image'])
            ? $baseUrl . ltrim($row['cover_image'], '/')
            : null;

        $pdfUrl = !empty($row['pdf_file'])
            ? $baseUrl . ltrim($row['pdf_file'], '/')
            : null;

        $flyers[] = [
            'id'              => (int)$row['id'],
            'title'           => $row['title'],
            'slug'            => $row['slug'],
            'description'     => $row['description'],
            'start_date'      => $row['start_date'],
            'end_date'        => $row['end_date'],
            'status_code'     => $code,
            'status_label'    => $label,
            'show_home'       => (bool)$row['show_home'],
            'cover_image_url' => $coverUrl,
            'pdf_url'         => $pdfUrl,
        ];
    }

    echo json_encode([
        'ok'     => true,
        'status' => $status,
        'today'  => $today,
        'count'  => count($flyers),
        'flyers' => $flyers,
    ], JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
    if (defined('APP_DEBUG') && APP_DEBUG) {
        error_log('get_flyers error: ' . $e->getMessage());
    }
    http_response_code(500);
    echo json_encode([
        'ok'    => false,
        'error' => 'Errore durante il caricamento dei volantini.',
    ], JSON_UNESCAPED_UNICODE);
}
