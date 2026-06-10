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
        case 'list':
            $stmt = $pdo->query("SELECT id, title, fb_video_url, category, duration, is_featured, status, cover_image, created_at FROM videos ORDER BY created_at DESC");
            $videos = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode(['status' => 'success', 'videos' => $videos]);
            break;

        case 'get':
            $id = $_GET['id'] ?? null;
            if (!$id) throw new Exception('ID video non fornito.');
            
            $stmt = $pdo->prepare('SELECT * FROM videos WHERE id = ?');
            $stmt->execute([$id]);
            $video = $stmt->fetch(PDO::FETCH_ASSOC);
            
            echo json_encode(['status' => 'success', 'video' => $video]);
            break;

        case 'add':
        case 'edit':
            $id = $_POST['id'] ?? null;
            $title = trim($_POST['title'] ?? '');
            $fb_video_url = trim($_POST['fb_video_url'] ?? '');
            $category = trim($_POST['category'] ?? 'prodotti');
            $duration = trim($_POST['duration'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $status = isset($_POST['status']) ? (int)$_POST['status'] : 1;
            $is_featured = isset($_POST['is_featured']) ? 1 : 0;

            if (empty($title)) throw new Exception('Il titolo è obbligatorio.');
            if (empty($fb_video_url)) throw new Exception('L\'URL del video Facebook è obbligatorio.');

            // Validate Facebook URL basic format
            if (strpos($fb_video_url, 'facebook.com') === false && strpos($fb_video_url, 'fb.watch') === false) {
                throw new Exception('Fornisci un URL video di Facebook valido.');
            }

            // If this video is featured, reset all other videos
            if ($is_featured === 1) {
                $pdo->exec("UPDATE videos SET is_featured = 0");
            }

            // --- Gestione upload ---
            $uploadDir = '../../uploads/videos/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $cover_image_path = null;

            if ($action === 'edit') {
                $stmt = $pdo->prepare('SELECT cover_image FROM videos WHERE id = ?');
                $stmt->execute([$id]);
                $current_video = $stmt->fetch(PDO::FETCH_ASSOC);
                $cover_image_path = $current_video['cover_image'] ?? null;
            }

            // Gestione Immagine di Copertina
            if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] == UPLOAD_ERR_OK) {
                // Se c'è un file nuovo, elimino quello vecchio se esiste
                if ($action === 'edit' && $cover_image_path && file_exists('../../' . $cover_image_path)) {
                    @unlink('../../' . $cover_image_path);
                }
                $fileName = uniqid() . '-' . preg_replace('/[^a-zA-Z0-9\._-]/', '', basename($_FILES['cover_image']['name']));
                $targetPath = $uploadDir . $fileName;
                if (move_uploaded_file($_FILES['cover_image']['tmp_name'], $targetPath)) {
                    $cover_image_path = 'uploads/videos/' . $fileName;
                }
            }

            if ($action === 'add') {
                $stmt = $pdo->prepare(
                    'INSERT INTO videos (title, fb_video_url, category, duration, description, cover_image, is_featured, status)
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?)'
                );
                $stmt->execute([$title, $fb_video_url, $category, $duration, $description, $cover_image_path, $is_featured, $status]);
            } else { // edit
                $stmt = $pdo->prepare(
                    'UPDATE videos SET title=?, fb_video_url=?, category=?, duration=?, description=?, cover_image=?, is_featured=?, status=? WHERE id = ?'
                );
                $stmt->execute([$title, $fb_video_url, $category, $duration, $description, $cover_image_path, $is_featured, $status, $id]);
            }
            
            echo json_encode(['status' => 'success', 'message' => 'Video salvato con successo.']);
            break;

        case 'delete':
            $id = $_POST['id'] ?? null;
            if (!$id) throw new Exception('ID video non fornito.');

            // Get file path before deleting
            $stmt = $pdo->prepare('SELECT cover_image FROM videos WHERE id = ?');
            $stmt->execute([$id]);
            $video = $stmt->fetch(PDO::FETCH_ASSOC);

            // Delete from DB
            $stmt = $pdo->prepare('DELETE FROM videos WHERE id = ?');
            $stmt->execute([$id]);

            // Delete file
            if ($video && $video['cover_image'] && file_exists('../../' . $video['cover_image'])) {
                @unlink('../../' . $video['cover_image']);
            }

            echo json_encode(['status' => 'success', 'message' => 'Video eliminato con successo.']);
            break;
        
        default:
            throw new Exception('Azione non valida.');
            break;
    }
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
