<?php
require_once __DIR__ . '/init.php';
require_once __DIR__ . '/../../src/AdminMedia.php';
$media = new \KeySoftItalia\AdminMedia();

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
            if (!$id) throw new InvalidArgumentException('ID volantino non fornito.');
            
            $stmt = $pdo->prepare('SELECT * FROM flyers WHERE id = ?');
            $stmt->execute([$id]);
            $flyer = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$flyer) throw new InvalidArgumentException('Record non trovato.');
            jsonSuccess(['flyer' => $flyer]);
            break;

        case 'add':
        case 'edit':
            $pdo->beginTransaction();
            $id = $_POST['id'] ?? null;
            $title = $_POST['title'];
            $slug = $_POST['slug'];
            $description = $_POST['description'] ?? null;
            $start_date = $_POST['start_date'];
            $end_date = $_POST['end_date'];
            $status = $_POST['status'] ?? 0;
            $show_home = isset($_POST['show_home']) ? 1 : 0;
            $internal_notes = $_POST['internal_notes'] ?? null;

            $cover_image_path = null;
            $pdf_file_path = null;

            if ($action === 'edit') {
                $stmt = $pdo->prepare('SELECT cover_image, pdf_file FROM flyers WHERE id = ?');
                $stmt->execute([$id]);
                $current_flyer = $stmt->fetch(PDO::FETCH_ASSOC);
                if (!$current_flyer) throw new InvalidArgumentException('Volantino non trovato.');
                $cover_image_path = $current_flyer['cover_image'];
                $pdf_file_path = $current_flyer['pdf_file'];
            }

            $newFile = $media->upload($_FILES['cover_image'] ?? null, 'uploads/flyers', 5, false);
            if ($newFile) { $media->retire($cover_image_path, 'uploads/flyers'); $cover_image_path = $newFile; }


            $newFile = $media->upload($_FILES['pdf_file'] ?? null, 'uploads/flyers', 20, true);
            if ($newFile) { $media->retire($pdf_file_path, 'uploads/flyers'); $pdf_file_path = $newFile; }


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
            
            $pdo->commit();
            $media->saved();
            jsonSuccess(['message' => 'Volantino salvato con successo.']);
            break;

        case 'delete':
            $id = $_POST['id'] ?? null;
            if (!$id) throw new InvalidArgumentException('ID volantino non fornito.');

            $stmt = $pdo->prepare('SELECT cover_image, pdf_file FROM flyers WHERE id = ?');
            $stmt->execute([$id]);
            $files = $stmt->fetch(PDO::FETCH_ASSOC);

            $stmt = $pdo->prepare('DELETE FROM flyers WHERE id = ?');
            $stmt->execute([$id]);

            if ($files) {
                \KeySoftItalia\AdminMedia::remove($files['cover_image'] ?? null, 'uploads/flyers');
                \KeySoftItalia\AdminMedia::remove($files['pdf_file'] ?? null, 'uploads/flyers');
            }

            jsonSuccess(['message' => 'Volantino eliminato con successo.']);
            break;
        
        default:
            throw new InvalidArgumentException('Azione non valida.');
            break;
    }
} catch (Throwable $e) {
    $media->abort();
    jsonError($e instanceof InvalidArgumentException ? $e->getMessage() : 'Errore del server.', $e);
}