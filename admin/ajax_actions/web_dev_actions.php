<?php
require_once __DIR__ . '/init.php';

$action = $_REQUEST['action'] ?? null;

try {
    switch ($action) {

        case 'list_packages':
            $stmt = $pdo->query("SELECT * FROM web_packages ORDER BY sort_order ASC, title ASC");
            $packages = $stmt->fetchAll(PDO::FETCH_ASSOC);
            jsonSuccess(['packages' => $packages]);
            break;

        case 'get_package':
            $id = $_GET['id'] ?? null;
            if (!$id) jsonError('ID pacchetto non fornito.');
            
            $stmt = $pdo->prepare('SELECT * FROM web_packages WHERE id = ?');
            $stmt->execute([$id]);
            $package = $stmt->fetch(PDO::FETCH_ASSOC);
            
            jsonSuccess(['package' => $package]);
            break;

        case 'save_package':
            $id = $_POST['id'] ?? null;
            $title = trim($_POST['title'] ?? '');
            $price_raw = trim($_POST['price'] ?? '');
            $price_detail = trim($_POST['price_detail'] ?? '');
            $subtitle = trim($_POST['subtitle'] ?? '');
            $features = trim($_POST['features'] ?? '');
            $status = isset($_POST['status']) ? (int)$_POST['status'] : 1;
            $is_featured = isset($_POST['is_featured']) ? 1 : 0;
            $sort_order = isset($_POST['sort_order']) ? (int)$_POST['sort_order'] : 0;

            if (empty($title)) jsonError('Il titolo del pacchetto è obbligatorio.');
            
            $price = ($price_raw !== '') ? floatval($price_raw) : null;

            if (empty($id)) {
                $stmt = $pdo->prepare(
                    'INSERT INTO web_packages (title, price, price_detail, subtitle, features, is_featured, status, sort_order)
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?)'
                );
                $stmt->execute([$title, $price, $price_detail, $subtitle, $features, $is_featured, $status, $sort_order]);
            } else {
                $stmt = $pdo->prepare(
                    'UPDATE web_packages SET title=?, price=?, price_detail=?, subtitle=?, features=?, is_featured=?, status=?, sort_order=? WHERE id = ?'
                );
                $stmt->execute([$title, $price, $price_detail, $subtitle, $features, $is_featured, $status, $sort_order, $id]);
            }
            
            jsonSuccess(['message' => 'Pacchetto salvato con successo.']);
            break;

        case 'delete_package':
            $id = $_POST['id'] ?? null;
            if (!$id) jsonError('ID pacchetto non fornito.');

            $stmt = $pdo->prepare('DELETE FROM web_packages WHERE id = ?');
            $stmt->execute([$id]);

            jsonSuccess(['message' => 'Pacchetto eliminato con successo.']);
            break;

        case 'list_showcase':
            $stmt = $pdo->query("SELECT * FROM web_showcase ORDER BY sort_order ASC, title ASC");
            $showcase = $stmt->fetchAll(PDO::FETCH_ASSOC);
            jsonSuccess(['showcase' => $showcase]);
            break;

        case 'get_showcase':
            $id = $_GET['id'] ?? null;
            if (!$id) jsonError('ID progetto non fornito.');
            
            $stmt = $pdo->prepare('SELECT * FROM web_showcase WHERE id = ?');
            $stmt->execute([$id]);
            $item = $stmt->fetch(PDO::FETCH_ASSOC);
            
            jsonSuccess(['showcase' => $item]);
            break;

        case 'save_showcase':
            $id = $_POST['id'] ?? null;
            $title = trim($_POST['title'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $technologies = trim($_POST['technologies'] ?? '');
            $project_url = trim($_POST['project_url'] ?? '');
            $status = isset($_POST['status']) ? (int)$_POST['status'] : 1;
            $sort_order = isset($_POST['sort_order']) ? (int)$_POST['sort_order'] : 0;

            if (empty($title)) jsonError('Il titolo del progetto è obbligatorio.');
            if (empty($description)) jsonError('La descrizione del progetto è obbligatoria.');
            if (empty($technologies)) jsonError('Le tecnologie utilizzate sono obbligatorie.');

            $uploadDir = '../../uploads/showcase/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $image_path = null;

            if (!empty($id)) {
                $stmt = $pdo->prepare('SELECT image_path FROM web_showcase WHERE id = ?');
                $stmt->execute([$id]);
                $current = $stmt->fetch(PDO::FETCH_ASSOC);
                $image_path = $current['image_path'] ?? null;
            }

            if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] == UPLOAD_ERR_OK) {
                $fileExt = strtolower(pathinfo($_FILES['image_file']['name'], PATHINFO_EXTENSION));
                if (!in_array($fileExt, ['jpg', 'jpeg', 'png', 'webp', 'gif'])) {
                    jsonError('Estensione file immagine non consentita.');
                }
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $mime = finfo_file($finfo, $_FILES['image_file']['tmp_name']);
                finfo_close($finfo);
                if (!in_array($mime, ['image/jpeg', 'image/png', 'image/webp', 'image/gif'])) {
                    jsonError('Tipo MIME file immagine non consentito.');
                }
                if ($_FILES['image_file']['size'] > 5 * 1024 * 1024) {
                    jsonError('Immagine troppo grande. Massimo 5MB.');
                }
                if (!empty($id) && $image_path && file_exists('../../' . $image_path)) {
                    @unlink('../../' . $image_path);
                }
                $fileName = uniqid() . '-' . preg_replace('/[^a-zA-Z0-9\._-]/', '', basename($_FILES['image_file']['name']));
                $targetPath = $uploadDir . $fileName;
                if (move_uploaded_file($_FILES['image_file']['tmp_name'], $targetPath)) {
                    $image_path = 'uploads/showcase/' . $fileName;
                }
            }

            if (empty($id)) {
                $stmt = $pdo->prepare(
                    'INSERT INTO web_showcase (title, description, technologies, image_path, project_url, status, sort_order)
                     VALUES (?, ?, ?, ?, ?, ?, ?)'
                );
                $stmt->execute([$title, $description, $technologies, $image_path, $project_url, $status, $sort_order]);
            } else {
                $stmt = $pdo->prepare(
                    'UPDATE web_showcase SET title=?, description=?, technologies=?, image_path=?, project_url=?, status=?, sort_order=? WHERE id = ?'
                );
                $stmt->execute([$title, $description, $technologies, $image_path, $project_url, $status, $sort_order, $id]);
            }
            
            jsonSuccess(['message' => 'Progetto salvato con successo.']);
            break;

        case 'delete_showcase':
            $id = $_POST['id'] ?? null;
            if (!$id) jsonError('ID progetto non fornito.');

            $stmt = $pdo->prepare('SELECT image_path FROM web_showcase WHERE id = ?');
            $stmt->execute([$id]);
            $item = $stmt->fetch(PDO::FETCH_ASSOC);

            $stmt = $pdo->prepare('DELETE FROM web_showcase WHERE id = ?');
            $stmt->execute([$id]);

            if ($item && $item['image_path'] && file_exists('../../' . $item['image_path'])) {
                @unlink('../../' . $item['image_path']);
            }

            jsonSuccess(['message' => 'Progetto eliminato con successo.']);
            break;
        
        default:
            jsonError('Azione non valida.');
            break;
    }
} catch (Throwable $e) {
    jsonError('Errore del server.', $e);
}
