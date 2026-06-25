<?php
require_once __DIR__ . '/init.php';

$action = $_REQUEST['action'] ?? null;

try {
    switch ($action) {
        case 'list':
            $stmt = $pdo->query("SELECT * FROM it_packages ORDER BY sort_order ASC, title ASC");
            $packages = $stmt->fetchAll(PDO::FETCH_ASSOC);
            jsonSuccess(['packages' => $packages]);
            break;

        case 'get':
            $id = $_GET['id'] ?? null;
            if (!$id) jsonError('ID pacchetto non fornito.');
            
            $stmt = $pdo->prepare('SELECT * FROM it_packages WHERE id = ?');
            $stmt->execute([$id]);
            $package = $stmt->fetch(PDO::FETCH_ASSOC);
            
            jsonSuccess(['package' => $package]);
            break;

        case 'save':
            $id = $_POST['id'] ?? null;
            $title = trim($_POST['title'] ?? '');
            $price_raw = trim($_POST['price'] ?? '');
            $price_detail = trim($_POST['price_detail'] ?? '/mese');
            $subtitle = trim($_POST['subtitle'] ?? '');
            $features = trim($_POST['features'] ?? '');
            $status = isset($_POST['status']) ? (int)$_POST['status'] : 1;
            $is_featured = isset($_POST['is_featured']) ? 1 : 0;
            $sort_order = isset($_POST['sort_order']) ? (int)$_POST['sort_order'] : 0;

            if (empty($title)) jsonError('Il titolo del pacchetto è obbligatorio.');
            
            $price = ($price_raw !== '') ? floatval($price_raw) : null;

            if (empty($id)) {
                $stmt = $pdo->prepare(
                    'INSERT INTO it_packages (title, price, price_detail, subtitle, features, is_featured, status, sort_order)
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?)'
                );
                $stmt->execute([$title, $price, $price_detail, $subtitle, $features, $is_featured, $status, $sort_order]);
            } else {
                $stmt = $pdo->prepare(
                    'UPDATE it_packages SET title=?, price=?, price_detail=?, subtitle=?, features=?, is_featured=?, status=?, sort_order=? WHERE id = ?'
                );
                $stmt->execute([$title, $price, $price_detail, $subtitle, $features, $is_featured, $status, $sort_order, $id]);
            }
            
            jsonSuccess(['message' => 'Pacchetto salvato con successo.']);
            break;

        case 'delete':
            $id = $_POST['id'] ?? null;
            if (!$id) jsonError('ID pacchetto non fornito.');

            $stmt = $pdo->prepare('DELETE FROM it_packages WHERE id = ?');
            $stmt->execute([$id]);

            jsonSuccess(['message' => 'Pacchetto eliminato con successo.']);
            break;

        default:
            jsonError('Azione non valida.');
            break;
    }
} catch (Throwable $e) {
    jsonError('Errore del server.', $e);
}
