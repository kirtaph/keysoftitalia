<?php
include_once '../../config/config.php';

header('Content-Type: application/json');

$action = $_REQUEST['action'] ?? null;
$id = $_REQUEST['id'] ?? null;

try {
    switch ($action) {
        case 'get':
            $stmt = $pdo->prepare('SELECT id, username, email FROM users WHERE id = ?');
            $stmt->execute([$id]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($user) {
                echo json_encode(['status' => 'success', 'user' => $user]);
            } else {
                throw new Exception('Utente non trovato.');
            }
            break;

        case 'add':
            $username = $_POST['username'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            if (empty($username) || empty($email) || empty($password)) {
                throw new Exception('Tutti i campi sono obbligatori.');
            }
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare('INSERT INTO users (username, email, password) VALUES (?, ?, ?)');
            $stmt->execute([$username, $email, $hashed_password]);
            echo json_encode(['status' => 'success', 'message' => 'Utente aggiunto con successo.']);
            break;

        case 'edit':
            $username = $_POST['username'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            if (empty($username) || empty($email) || empty($id)) {
                throw new Exception('ID, username e email sono obbligatori.');
            }
            if (!empty($password)) {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare('UPDATE users SET username = ?, email = ?, password = ? WHERE id = ?');
                $stmt->execute([$username, $email, $hashed_password, $id]);
            } else {
                $stmt = $pdo->prepare('UPDATE users SET username = ?, email = ? WHERE id = ?');
                $stmt->execute([$username, $email, $id]);
            }
            echo json_encode(['status' => 'success', 'message' => 'Utente aggiornato con successo.']);
            break;

        case 'delete':
            if (empty($id)) {
                throw new Exception('ID utente non fornito.');
            }
            $stmt = $pdo->prepare('DELETE FROM users WHERE id = ?');
            $stmt->execute([$id]);
            echo json_encode(['status' => 'success', 'message' => 'Utente eliminato con successo.']);
            break;

        default:
            throw new Exception('Azione non valida.');
            break;
    }
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
