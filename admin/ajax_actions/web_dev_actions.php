<?php
include_once '../../config/config.php';

header('Content-Type: application/json');

// Security Check: Ensure user is logged in
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

$action = $_REQUEST['action'] ?? null;

try {
    switch ($action) {
        // ==========================================
        // PACKAGES CRUD
        // ==========================================
        case 'list_packages':
            $stmt = $pdo->query("SELECT * FROM web_packages ORDER BY sort_order ASC, title ASC");
            $packages = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode(['status' => 'success', 'packages' => $packages]);
            break;

        case 'get_package':
            $id = $_GET['id'] ?? null;
            if (!$id) throw new Exception('ID pacchetto non fornito.');
            
            $stmt = $pdo->prepare('SELECT * FROM web_packages WHERE id = ?');
            $stmt->execute([$id]);
            $package = $stmt->fetch(PDO::FETCH_ASSOC);
            
            echo json_encode(['status' => 'success', 'package' => $package]);
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

            if (empty($title)) throw new Exception('Il titolo del pacchetto è obbligatorio.');
            
            $price = ($price_raw !== '') ? floatval($price_raw) : null;

            if (empty($id)) { // Add
                $stmt = $pdo->prepare(
                    'INSERT INTO web_packages (title, price, price_detail, subtitle, features, is_featured, status, sort_order)
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?)'
                );
                $stmt->execute([$title, $price, $price_detail, $subtitle, $features, $is_featured, $status, $sort_order]);
            } else { // Edit
                $stmt = $pdo->prepare(
                    'UPDATE web_packages SET title=?, price=?, price_detail=?, subtitle=?, features=?, is_featured=?, status=?, sort_order=? WHERE id = ?'
                );
                $stmt->execute([$title, $price, $price_detail, $subtitle, $features, $is_featured, $status, $sort_order, $id]);
            }
            
            echo json_encode(['status' => 'success', 'message' => 'Pacchetto salvato con successo.']);
            break;

        case 'delete_package':
            $id = $_POST['id'] ?? null;
            if (!$id) throw new Exception('ID pacchetto non fornito.');

            $stmt = $pdo->prepare('DELETE FROM web_packages WHERE id = ?');
            $stmt->execute([$id]);

            echo json_encode(['status' => 'success', 'message' => 'Pacchetto eliminato con successo.']);
            break;


        // ==========================================
        // SHOWCASE CRUD
        // ==========================================
        case 'list_showcase':
            $stmt = $pdo->query("SELECT * FROM web_showcase ORDER BY sort_order ASC, title ASC");
            $showcase = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode(['status' => 'success', 'showcase' => $showcase]);
            break;

        case 'get_showcase':
            $id = $_GET['id'] ?? null;
            if (!$id) throw new Exception('ID progetto non fornito.');
            
            $stmt = $pdo->prepare('SELECT * FROM web_showcase WHERE id = ?');
            $stmt->execute([$id]);
            $item = $stmt->fetch(PDO::FETCH_ASSOC);
            
            echo json_encode(['status' => 'success', 'showcase' => $item]);
            break;

        case 'save_showcase':
            $id = $_POST['id'] ?? null;
            $title = trim($_POST['title'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $technologies = trim($_POST['technologies'] ?? '');
            $project_url = trim($_POST['project_url'] ?? '');
            $status = isset($_POST['status']) ? (int)$_POST['status'] : 1;
            $sort_order = isset($_POST['sort_order']) ? (int)$_POST['sort_order'] : 0;

            if (empty($title)) throw new Exception('Il titolo del progetto è obbligatorio.');
            if (empty($description)) throw new Exception('La descrizione del progetto è obbligatoria.');
            if (empty($technologies)) throw new Exception('Le tecnologie utilizzate sono obbligatorie.');

            // --- Gestione upload ---
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

            // Gestione file immagine
            if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] == UPLOAD_ERR_OK) {
                // Elimino file precedente se esiste
                if (!empty($id) && $image_path && file_exists('../../' . $image_path)) {
                    @unlink('../../' . $image_path);
                }
                $fileName = uniqid() . '-' . preg_replace('/[^a-zA-Z0-9\._-]/', '', basename($_FILES['image_file']['name']));
                $targetPath = $uploadDir . $fileName;
                if (move_uploaded_file($_FILES['image_file']['tmp_name'], $targetPath)) {
                    $image_path = 'uploads/showcase/' . $fileName;
                }
            }

            if (empty($id)) { // Add
                $stmt = $pdo->prepare(
                    'INSERT INTO web_showcase (title, description, technologies, image_path, project_url, status, sort_order)
                     VALUES (?, ?, ?, ?, ?, ?, ?)'
                );
                $stmt->execute([$title, $description, $technologies, $image_path, $project_url, $status, $sort_order]);
            } else { // Edit
                $stmt = $pdo->prepare(
                    'UPDATE web_showcase SET title=?, description=?, technologies=?, image_path=?, project_url=?, status=?, sort_order=? WHERE id = ?'
                );
                $stmt->execute([$title, $description, $technologies, $image_path, $project_url, $status, $sort_order, $id]);
            }
            
            echo json_encode(['status' => 'success', 'message' => 'Progetto salvato con successo.']);
            break;

        case 'delete_showcase':
            $id = $_POST['id'] ?? null;
            if (!$id) throw new Exception('ID progetto non fornito.');

            // Otteniamo il percorso dell'immagine prima di eliminare
            $stmt = $pdo->prepare('SELECT image_path FROM web_showcase WHERE id = ?');
            $stmt->execute([$id]);
            $item = $stmt->fetch(PDO::FETCH_ASSOC);

            // Eliminiamo dal DB
            $stmt = $pdo->prepare('DELETE FROM web_showcase WHERE id = ?');
            $stmt->execute([$id]);

            // Eliminiamo il file fisico dell'immagine
            if ($item && $item['image_path'] && file_exists('../../' . $item['image_path'])) {
                @unlink('../../' . $item['image_path']);
            }

            echo json_encode(['status' => 'success', 'message' => 'Progetto eliminato con successo.']);
            break;
        
        default:
            throw new Exception('Azione non valida.');
            break;
    }
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
