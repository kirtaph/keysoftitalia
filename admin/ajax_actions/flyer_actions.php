<?php
include_once '../../config/config.php';

header('Content-Type: application/json');

$action = $_REQUEST['action'] ?? null;

try {
    switch ($action) {
        case 'list':
            $stmt = $pdo->query("SELECT id, title, start_date, end_date, status, show_home FROM flyers ORDER BY start_date DESC");
            $flyers = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode(['status' => 'success', 'flyers' => $flyers]);
            break;

        case 'get':
            $id = $_GET['id'] ?? null;
            if (!$id) throw new Exception('ID volantino non fornito.');
            
            $stmt = $pdo->prepare('SELECT * FROM flyers WHERE id = ?');
            $stmt->execute([$id]);
            $flyer = $stmt->fetch(PDO::FETCH_ASSOC);
            
            echo json_encode(['status' => 'success', 'flyer' => $flyer]);
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

            $cover_image_path = $_POST['existing_cover_image'] ?? null;
            if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] == UPLOAD_ERR_OK) {
                $fileName = uniqid() . '-' . basename($_FILES['cover_image']['name']);
                $targetPath = $uploadDir . $fileName;
                if (move_uploaded_file($_FILES['cover_image']['tmp_name'], $targetPath)) {
                    $cover_image_path = 'uploads/flyers/' . $fileName;
                }
            }

            $pdf_file_path = $_POST['existing_pdf_file'] ?? null;
            if (isset($_FILES['pdf_file']) && $_FILES['pdf_file']['error'] == UPLOAD_ERR_OK) {
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
            
            echo json_encode(['status' => 'success', 'message' => 'Volantino salvato con successo.']);
            break;

        case 'delete':
            $id = $_POST['id'] ?? null;
            if (!$id) throw new Exception('ID volantino non fornito.');

            // Get file paths before deleting
            $stmt = $pdo->prepare('SELECT cover_image, pdf_file FROM flyers WHERE id = ?');
            $stmt->execute([$id]);
            $files = $stmt->fetch(PDO::FETCH_ASSOC);

            // Delete from DB
            $stmt = $pdo->prepare('DELETE FROM flyers WHERE id = ?');
            $stmt->execute([$id]);

            // Delete files
            if ($files) {
                if ($files['cover_image'] && file_exists('../../' . $files['cover_image'])) {
                    unlink('../../' . $files['cover_image']);
                }
                if ($files['pdf_file'] && file_exists('../../' . $files['pdf_file'])) {
                    unlink('../../' . $files['pdf_file']);
                }
            }

            echo json_encode(['status' => 'success', 'message' => 'Volantino eliminato con successo.']);
            break;
        
        default:
            throw new Exception('Azione non valida.');
            break;
    }
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
