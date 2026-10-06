<?php
require_once __DIR__ . '/init.php';
require_once __DIR__ . '/../../src/AdminMedia.php';
$media = new \KeySoftItalia\AdminMedia();

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
            if (!$id) throw new InvalidArgumentException('ID video non fornito.');
            
            $stmt = $pdo->prepare('SELECT * FROM videos WHERE id = ?');
            $stmt->execute([$id]);
            $video = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$video) throw new InvalidArgumentException('Record non trovato.');
            jsonSuccess(['video' => $video]);
            break;

        case 'add':
        case 'edit':
            $pdo->beginTransaction();
            $id = $_POST['id'] ?? null;
            $title = trim($_POST['title'] ?? '');
            $fb_video_url = trim($_POST['fb_video_url'] ?? '');
            $category = trim($_POST['category'] ?? 'prodotti');
            $duration = trim($_POST['duration'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $status = isset($_POST['status']) ? (int)$_POST['status'] : 1;
            $is_featured = isset($_POST['is_featured']) ? 1 : 0;

            if (empty($title)) throw new InvalidArgumentException('Il titolo è obbligatorio.');
            if (empty($fb_video_url)) throw new InvalidArgumentException('L\'URL del video Facebook è obbligatorio.');

            $host = strtolower((string)parse_url($fb_video_url, PHP_URL_HOST));
            if (parse_url($fb_video_url, PHP_URL_SCHEME) !== 'https' || !(in_array($host, ['facebook.com', 'fb.watch'], true) || str_ends_with($host, '.facebook.com'))) {
                throw new InvalidArgumentException('Fornisci un URL video di Facebook valido.');
            }

            if ($is_featured === 1) {
                $pdo->exec("UPDATE videos SET is_featured = 0");
            }

            $cover_image_path = null;

            if ($action === 'edit') {
                $stmt = $pdo->prepare('SELECT cover_image FROM videos WHERE id = ?');
                $stmt->execute([$id]);
                $current_video = $stmt->fetch(PDO::FETCH_ASSOC);
                if (!$current_video) throw new InvalidArgumentException('Video non trovato.');
                $cover_image_path = $current_video['cover_image'] ?? null;
            }

            $newFile = $media->upload($_FILES['cover_image'] ?? null, 'uploads/videos', 5, false);
            if ($newFile) { $media->retire($cover_image_path, 'uploads/videos'); $cover_image_path = $newFile; }


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
            
            $pdo->commit();
            $media->saved();
            jsonSuccess(['message' => 'Video salvato con successo.']);
            break;

        case 'delete':
            $id = $_POST['id'] ?? null;
            if (!$id) throw new InvalidArgumentException('ID video non fornito.');

            $stmt = $pdo->prepare('SELECT cover_image FROM videos WHERE id = ?');
            $stmt->execute([$id]);
            $video = $stmt->fetch(PDO::FETCH_ASSOC);

            $stmt = $pdo->prepare('DELETE FROM videos WHERE id = ?');
            $stmt->execute([$id]);

            \KeySoftItalia\AdminMedia::remove($video['cover_image'] ?? null, 'uploads/videos');

            jsonSuccess(['message' => 'Video eliminato con successo.']);
            break;
        
        default:
            throw new InvalidArgumentException('Azione non valida.');
            break;
    }
} catch (Throwable $e) {
    $media->abort();
    jsonError($e instanceof InvalidArgumentException ? $e->getMessage() : 'Errore del server.', $e);
}
