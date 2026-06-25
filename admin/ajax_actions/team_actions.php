<?php
require_once __DIR__ . '/init.php';

$action = $_POST['action'] ?? $_GET['action'] ?? '';

try {
    switch ($action) {
        case 'list':
            $stmt = $pdo->query("SELECT * FROM team_members ORDER BY sort_order ASC, name ASC");
            $members = $stmt->fetchAll(PDO::FETCH_ASSOC);
            jsonSuccess(['members' => $members]);
            break;

        case 'get':
            $id = $_GET['id'] ?? null;
            if (!$id) jsonError('ID mancante');
            
            $stmt = $pdo->prepare("SELECT * FROM team_members WHERE id = ?");
            $stmt->execute([$id]);
            $member = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$member) jsonError('Membro non trovato');
            jsonSuccess(['member' => $member]);
            break;

        case 'add':
        case 'edit':
            $id = $_POST['id'] ?? null;
            $name = trim($_POST['name'] ?? '');
            $role = trim($_POST['role'] ?? '');
            $bio = trim($_POST['bio'] ?? '');
            $skills = trim($_POST['skills'] ?? '');
            $aos_animation = trim($_POST['aos_animation'] ?? 'fade-up');
            $sort_order = isset($_POST['sort_order']) ? (int)$_POST['sort_order'] : 0;
            $status = isset($_POST['status']) ? (int)$_POST['status'] : 1;
            
            if (!$name || !$role) {
                jsonError('Nome e Ruolo sono obbligatori.');
            }

            $photo_path = null;
            
            if (isset($_FILES['photo_file']) && $_FILES['photo_file']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = '../../assets/img/team/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                
                $fileInfo = pathinfo($_FILES['photo_file']['name']);
                $ext = strtolower($fileInfo['extension']);
                $allowed = ['png', 'jpg', 'jpeg', 'webp'];
                
                if (!in_array($ext, $allowed)) {
                    jsonError('Formato immagine non supportato.');
                }
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $mime = finfo_file($finfo, $_FILES['photo_file']['tmp_name']);
                finfo_close($finfo);
                if (!in_array($mime, ['image/png', 'image/jpeg', 'image/webp'])) {
                    jsonError('Tipo MIME non consentito.');
                }
                if ($_FILES['photo_file']['size'] > 5 * 1024 * 1024) {
                    jsonError('File troppo grande. Massimo 5MB.');
                }
                
                $filename = uniqid('team_') . '.' . $ext;
                $targetPath = $uploadDir . $filename;
                
                if (move_uploaded_file($_FILES['photo_file']['tmp_name'], $targetPath)) {
                    $photo_path = 'img/team/' . $filename;
                } else {
                    jsonError("Errore durante l'upload dell'immagine.");
                }
            }

            if ($action === 'add') {
                if (!$photo_path) {
                    jsonError('La foto è obbligatoria per un nuovo membro.');
                }
                $stmt = $pdo->prepare("INSERT INTO team_members (name, role, photo_path, bio, skills, aos_animation, sort_order, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$name, $role, $photo_path, $bio, $skills, $aos_animation, $sort_order, $status]);
            } else {
                if (!$id) jsonError('ID mancante per la modifica.');
                
                if ($photo_path) {
                    $stmt = $pdo->prepare("UPDATE team_members SET name=?, role=?, photo_path=?, bio=?, skills=?, aos_animation=?, sort_order=?, status=? WHERE id=?");
                    $stmt->execute([$name, $role, $photo_path, $bio, $skills, $aos_animation, $sort_order, $status, $id]);
                } else {
                    $stmt = $pdo->prepare("UPDATE team_members SET name=?, role=?, bio=?, skills=?, aos_animation=?, sort_order=?, status=? WHERE id=?");
                    $stmt->execute([$name, $role, $bio, $skills, $aos_animation, $sort_order, $status, $id]);
                }
            }
            
            jsonSuccess([]);
            break;

        case 'delete':
            $id = $_POST['id'] ?? null;
            if (!$id) jsonError('ID mancante');
            
            $stmt = $pdo->prepare("DELETE FROM team_members WHERE id = ?");
            $stmt->execute([$id]);
            
            jsonSuccess([]);
            break;

        default:
            jsonError('Azione non valida');
    }
} catch (Throwable $e) {
    jsonError('Errore del server.', $e);
}
