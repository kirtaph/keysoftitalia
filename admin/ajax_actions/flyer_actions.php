<?php
require_once __DIR__ . '/init.php';

// File upload validation helpers
function validateUpload(array $file, array $allowedExt, array $allowedMime, int $maxSize): void {
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowedExt)) {
        throw new Exception('Estensione file non consentita.');
    }
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    if (!in_array($mime, $allowedMime)) {
        throw new Exception('Tipo MIME non consentito.');
    }
    if ($file['size'] > $maxSize) {
        $maxMb = $maxSize / 1024 / 1024;
        throw new Exception("File troppo grande. Massimo {$maxMb}MB.");
    }
}
$ALLOWED_IMG_EXT  = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
$ALLOWED_IMG_MIME = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
$ALLOWED_PDF_EXT  = ['pdf'];
$ALLOWED_PDF_MIME = ['application/pdf'];
$MAX_IMG_SIZE = 5 * 1024 * 1024;
$MAX_PDF_SIZE = 20 * 1024 * 1024;

$action = $_REQUEST['action'] ?? null;

try {
    switch ($action) {
        case 'list':
            $stmt = $pdo->query("SELECT id, title, slug, start_date, end_date, status, show_home, cover_image FROM flyers ORDER BY start_date DESC");
            $flyers = $stmt->fetchAll(PDO::FETCH_ASSOC);
            jsonSuccess(['flyers' => $flyers]);
            break;

        case 'get':
            $id = $_GET['id'] ?? null;
            if (!$id) jsonError('ID volantino non fornito.');
            
            $stmt = $pdo->prepare('SELECT * FROM flyers WHERE id = ?');
            $stmt->execute([$id]);
            $flyer = $stmt->fetch(PDO::FETCH_ASSOC);
            
            jsonSuccess(['flyer' => $flyer]);
            break;

        case 'add':
        case 'edit':
            $id = $_POST['id'] ?? null;
            $title = $_POST['title'];
            $slug = $_POST['slug'];
            $description = $_POST['description'] ?? null;
            $start_date = $_POST['start_date'];
            $end_date = $_POST['end_date'];
            $status = $_POST['status'] ?? 0;
            $show_home = isset($_POST['show_home']) ? 1 : 0;
            $internal_notes = $_POST['internal_notes'] ?? null;

            $uploadDir = '../../uploads/flyers/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $cover_image_path = null;
            $pdf_file_path = null;

            if ($action === 'edit') {
                $stmt = $pdo->prepare('SELECT cover_image, pdf_file FROM flyers WHERE id = ?');
                $stmt->execute([$id]);
                $current_flyer = $stmt->fetch(PDO::FETCH_ASSOC);
                $cover_image_path = $current_flyer['cover_image'];
                $pdf_file_path = $current_flyer['pdf_file'];
            }

            if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] == UPLOAD_ERR_OK) {
                validateUpload($_FILES['cover_image'], $ALLOWED_IMG_EXT, $ALLOWED_IMG_MIME, $MAX_IMG_SIZE);
                if ($action === 'edit' && $cover_image_path && file_exists('../../' . $cover_image_path)) {
                    unlink('../../' . $cover_image_path);
                }
                $fileName = uniqid() . '-' . basename($_FILES['cover_image']['name']);
                $targetPath = $uploadDir . $fileName;
                if (move_uploaded_file($_FILES['cover_image']['tmp_name'], $targetPath)) {
                    $cover_image_path = 'uploads/flyers/' . $fileName;
                }
            }

            if (isset($_FILES['pdf_file']) && $_FILES['pdf_file']['error'] == UPLOAD_ERR_OK) {
                validateUpload($_FILES['pdf_file'], $ALLOWED_PDF_EXT, $ALLOWED_PDF_MIME, $MAX_PDF_SIZE);
                if ($action === 'edit' && $pdf_file_path && file_exists('../../' . $pdf_file_path)) {
                    unlink('../../' . $pdf_file_path);
                }
                $fileName = uniqid() . '-' . basename($_FILES['pdf_file']['name']);
                $targetPath = $uploadDir . $fileName;
                if (move_uploaded_file($_FILES['pdf_file']['tmp_name'], $targetPath)) {
                    $pdf_file_path = 'uploads/flyers/' . $fileName;
                }
            }

            if ($action === 'add') {
                $stmt = $pdo->prepare(
                    'INSERT INTO flyers (title, slug, description, start_date, end_date, status, show_home, cover_image, pdf_file, internal_notes)
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
                );
                $stmt->execute([$title, $slug, $description, $start_date, $end_date, $status, $show_home, $cover_image_path, $pdf_file_path, $internal_notes]);
            } else {
                $stmt = $pdo->prepare(
                    'UPDATE flyers SET title=?, slug=?, description=?, start_date=?, end_date=?, status=?, show_home=?, cover_image=?, pdf_file=?, internal_notes=? WHERE id = ?'
                );
                $stmt->execute([$title, $slug, $description, $start_date, $end_date, $status, $show_home, $cover_image_path, $pdf_file_path, $internal_notes, $id]);
            }
            
            jsonSuccess(['message' => 'Volantino salvato con successo.']);
            break;

        case 'delete':
            $id = $_POST['id'] ?? null;
            if (!$id) jsonError('ID volantino non fornito.');

            $stmt = $pdo->prepare('SELECT cover_image, pdf_file FROM flyers WHERE id = ?');
            $stmt->execute([$id]);
            $files = $stmt->fetch(PDO::FETCH_ASSOC);

            $stmt = $pdo->prepare('DELETE FROM flyers WHERE id = ?');
            $stmt->execute([$id]);

            if ($files) {
                if ($files['cover_image'] && file_exists('../../' . $files['cover_image'])) {
                    unlink('../../' . $files['cover_image']);
                }
                if ($files['pdf_file'] && file_exists('../../' . $files['pdf_file'])) {
                    unlink('../../' . $files['pdf_file']);
                }
            }

            jsonSuccess(['message' => 'Volantino eliminato con successo.']);
            break;
        
        default:
            jsonError('Azione non valida.');
            break;
    }
} catch (Throwable $e) {
    jsonError('Errore del server.', $e);
}