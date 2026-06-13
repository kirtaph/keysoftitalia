<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo json_encode(['status' => 'error', 'message' => 'Non autorizzato']);
    exit;
}

require_once '../../config/config.php';

header('Content-Type: application/json');

$action = $_POST['action'] ?? $_GET['action'] ?? '';

try {
    switch ($action) {
        case 'list':
            $stmt = $pdo->query("SELECT * FROM team_members ORDER BY sort_order ASC, name ASC");
            $members = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode(['status' => 'success', 'members' => $members]);
            break;

        case 'get':
            $id = $_GET['id'] ?? null;
            if (!$id) throw new Exception('ID mancante');
            
            $stmt = $pdo->prepare("SELECT * FROM team_members WHERE id = ?");
            $stmt->execute([$id]);
            $member = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$member) throw new Exception('Membro non trovato');
            echo json_encode(['status' => 'success', 'member' => $member]);
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
                throw new Exception('Nome e Ruolo sono obbligatori.');
            }

            $photo_path = null;
            
            // Upload foto
            if (isset($_FILES['photo_file']) && $_FILES['photo_file']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = '../../assets/img/team/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                
                $fileInfo = pathinfo($_FILES['photo_file']['name']);
                $ext = strtolower($fileInfo['extension']);
                $allowed = ['png', 'jpg', 'jpeg', 'webp'];
                
                if (!in_array($ext, $allowed)) {
                    throw new Exception('Formato immagine non supportato.');
                }
                
                $filename = uniqid('team_') . '.' . $ext;
                $targetPath = $uploadDir . $filename;
                
                if (move_uploaded_file($_FILES['photo_file']['tmp_name'], $targetPath)) {
                    $photo_path = 'img/team/' . $filename;
                } else {
                    throw new Exception("Errore durante l'upload dell'immagine.");
                }
            }

            if ($action === 'add') {
                if (!$photo_path) {
                    throw new Exception('La foto è obbligatoria per un nuovo membro.');
                }
                $stmt = $pdo->prepare("INSERT INTO team_members (name, role, photo_path, bio, skills, aos_animation, sort_order, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$name, $role, $photo_path, $bio, $skills, $aos_animation, $sort_order, $status]);
            } else {
                if (!$id) throw new Exception('ID mancante per la modifica.');
                
                if ($photo_path) {
                    // Update con nuova foto
                    // Non cancelliamo le foto precedenti (patrizio.png ecc) perché potrebbero essere reference,
                    // ma si potrebbe implementare la cancellazione.
                    $stmt = $pdo->prepare("UPDATE team_members SET name=?, role=?, photo_path=?, bio=?, skills=?, aos_animation=?, sort_order=?, status=? WHERE id=?");
                    $stmt->execute([$name, $role, $photo_path, $bio, $skills, $aos_animation, $sort_order, $status, $id]);
                } else {
                    // Update senza cambiare foto
                    $stmt = $pdo->prepare("UPDATE team_members SET name=?, role=?, bio=?, skills=?, aos_animation=?, sort_order=?, status=? WHERE id=?");
                    $stmt->execute([$name, $role, $bio, $skills, $aos_animation, $sort_order, $status, $id]);
                }
            }
            
            echo json_encode(['status' => 'success']);
            break;

        case 'delete':
            $id = $_POST['id'] ?? null;
            if (!$id) throw new Exception('ID mancante');
            
            $stmt = $pdo->prepare("DELETE FROM team_members WHERE id = ?");
            $stmt->execute([$id]);
            
            echo json_encode(['status' => 'success']);
            break;

        default:
            throw new Exception('Azione non valida');
    }
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
