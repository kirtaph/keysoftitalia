<?php
require_once __DIR__ . '/init.php';
require_once __DIR__ . '/../../src/AdminMedia.php';
$media = new \KeySoftItalia\AdminMedia();

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
            if (!$id) throw new InvalidArgumentException('ID mancante');
            
            $stmt = $pdo->prepare("SELECT * FROM team_members WHERE id = ?");
            $stmt->execute([$id]);
            $member = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$member) throw new InvalidArgumentException('Membro non trovato');
            jsonSuccess(['member' => $member]);
            break;

        case 'add':
        case 'edit':
            $pdo->beginTransaction();
            $id = $_POST['id'] ?? null;
            $name = trim($_POST['name'] ?? '');
            $role = trim($_POST['role'] ?? '');
            $bio = trim($_POST['bio'] ?? '');
            $skills = trim($_POST['skills'] ?? '');
            $aos_animation = trim($_POST['aos_animation'] ?? 'fade-up');
            $sort_order = isset($_POST['sort_order']) ? (int)$_POST['sort_order'] : 0;
            $status = isset($_POST['status']) ? (int)$_POST['status'] : 1;
            
            if (!$name || !$role) {
                throw new InvalidArgumentException('Nome e Ruolo sono obbligatori.');
            }

            $photo_path = null;
            
            $oldPhoto = null;
            if ($action === 'edit') {
                $stmt = $pdo->prepare('SELECT photo_path FROM team_members WHERE id = ?');
                $stmt->execute([$id]); $currentMember = $stmt->fetch();
                if (!$currentMember) throw new InvalidArgumentException('Membro non trovato.');
                $oldPhoto = $currentMember['photo_path'];
            }
            $photo = $media->upload($_FILES['photo_file'] ?? null, 'assets/img/team');
            if ($photo) { $photo_path = substr($photo, strlen('assets/')); $media->retire($oldPhoto, 'assets/img/team'); }


            if ($action === 'add') {
                if (!$photo_path) {
                    throw new InvalidArgumentException('La foto è obbligatoria per un nuovo membro.');
                }
                $stmt = $pdo->prepare("INSERT INTO team_members (name, role, photo_path, bio, skills, aos_animation, sort_order, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$name, $role, $photo_path, $bio, $skills, $aos_animation, $sort_order, $status]);
            } else {
                if (!$id) throw new InvalidArgumentException('ID mancante per la modifica.');
                
                if ($photo_path) {
                    $stmt = $pdo->prepare("UPDATE team_members SET name=?, role=?, photo_path=?, bio=?, skills=?, aos_animation=?, sort_order=?, status=? WHERE id=?");
                    $stmt->execute([$name, $role, $photo_path, $bio, $skills, $aos_animation, $sort_order, $status, $id]);
                } else {
                    $stmt = $pdo->prepare("UPDATE team_members SET name=?, role=?, bio=?, skills=?, aos_animation=?, sort_order=?, status=? WHERE id=?");
                    $stmt->execute([$name, $role, $bio, $skills, $aos_animation, $sort_order, $status, $id]);
                }
            }
            
            $pdo->commit();
            $media->saved();
            jsonSuccess([]);
            break;

        case 'delete':
            $id = $_POST['id'] ?? null;
            if (!$id) throw new InvalidArgumentException('ID mancante');
            
            $stmt = $pdo->prepare('SELECT photo_path FROM team_members WHERE id = ?');
            $stmt->execute([$id]); $member = $stmt->fetch();
            $stmt = $pdo->prepare("DELETE FROM team_members WHERE id = ?");
            $stmt->execute([$id]);
            \KeySoftItalia\AdminMedia::remove($member['photo_path'] ?? null, 'assets/img/team');
            jsonSuccess([]);
            break;

        default:
            throw new InvalidArgumentException('Azione non valida');
    }
} catch (Throwable $e) {
    $media->abort();
    jsonError($e instanceof InvalidArgumentException ? $e->getMessage() : 'Errore del server.', $e);
}
