<?php
require_once __DIR__ . '/init.php';

$action = $_REQUEST['action'] ?? null;

try {
    switch ($action) {
        case 'list':
            $stmt = $pdo->query("SELECT id, title, fb_video_url, category, duration, is_featured, status, cover_image, created_at FROM videos ORDER BY created_at DESC");
            $videos = $stmt->fetchAll(PDO::FETCH_ASSOC);
            jsonSuccess(['videos' => $videos]);
            break;

        case 'get':
            $id = $_GET['id'] ?? null;
            if (!$id) jsonError('ID video non fornito.');
            
            $stmt = $pdo->prepare('SELECT * FROM videos WHERE id = ?');
            $stmt->execute([$id]);
            $video = $stmt->fetch(PDO::FETCH_ASSOC);
            
            jsonSuccess(['video' => $video]);
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

            if (empty($title)) jsonError('Il titolo è obbligatorio.');
            if (empty($fb_video_url)) jsonError('L\'URL del video Facebook è obbligatorio.');

            if (strpos($fb_video_url, 'facebook.com') === false && strpos($fb_video_url, 'fb.watch') === false) {
                jsonError('Fornisci un URL video di Facebook valido.');
            }

            if ($is_featured === 1) {
                $pdo->exec("UPDATE videos SET is_featured = 0");
            }

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

            if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] == UPLOAD_ERR_OK) {
                $fileExt = strtolower(pathinfo($_FILES['cover_image']['name'], PATHINFO_EXTENSION));
                if (!in_array($fileExt, ['jpg', 'jpeg', 'png', 'webp', 'gif'])) {
                    jsonError('Estensione file immagine non consentita.');
                }
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $mime = finfo_file($finfo, $_FILES['cover_image']['tmp_name']);
                finfo_close($finfo);
                if (!in_array($mime, ['image/jpeg', 'image/png', 'image/webp', 'image/gif'])) {
                    jsonError('Tipo MIME file immagine non consentito.');
                }
                if ($_FILES['cover_image']['size'] > 5 * 1024 * 1024) {
                    jsonError('Immagine troppo grande. Massimo 5MB.');
                }
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
            } else {
                $stmt = $pdo->prepare(
                    'UPDATE videos SET title=?, fb_video_url=?, category=?, duration=?, description=?, cover_image=?, is_featured=?, status=? WHERE id = ?'
                );
                $stmt->execute([$title, $fb_video_url, $category, $duration, $description, $cover_image_path, $is_featured, $status, $id]);
            }
            
            jsonSuccess(['message' => 'Video salvato con successo.']);
            break;

        case 'delete':
            $id = $_POST['id'] ?? null;
            if (!$id) jsonError('ID video non fornito.');

            $stmt = $pdo->prepare('SELECT cover_image FROM videos WHERE id = ?');
            $stmt->execute([$id]);
            $video = $stmt->fetch(PDO::FETCH_ASSOC);

            $stmt = $pdo->prepare('DELETE FROM videos WHERE id = ?');
            $stmt->execute([$id]);

            if ($video && $video['cover_image'] && file_exists('../../' . $video['cover_image'])) {
                @unlink('../../' . $video['cover_image']);
            }

            jsonSuccess(['message' => 'Video eliminato con successo.']);
            break;
        
        default:
            jsonError('Azione non valida.');
            break;
    }
} catch (Throwable $e) {
    jsonError('Errore del server.', $e);
}
