<?php
include_once '../../config/config.php';

header('Content-Type: application/json');

$action = $_REQUEST['action'] ?? null;

try {
    switch ($action) {
        case 'get':
            $id = $_GET['id'] ?? null;
            if (!$id) throw new Exception('ID utente non fornito.');
            
            $stmt = $pdo->prepare('SELECT id, username, email FROM users WHERE id = ?');
            $stmt->execute([$id]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$user) throw new Exception('Utente non trovato.');
            
            echo json_encode(['status' => 'success', 'user' => $user]);
            break;

        case 'add':
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            
            if (empty($password)) throw new Exception('La password è obbligatoria per i nuovi utenti.');
            
            // Check if username already exists
            $stmt = $pdo->prepare('SELECT id FROM users WHERE username = ?');
            $stmt->execute([$username]);
            if ($stmt->fetch()) throw new Exception('Username già in uso.');

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            
            $stmt = $pdo->prepare('INSERT INTO users (username, email, password) VALUES (?, ?, ?)');
            $stmt->execute([$username, $email, $hashedPassword]);
            
            echo json_encode(['status' => 'success', 'message' => 'Utente creato con successo.']);
            break;

        case 'edit':
            $id = $_POST['id'] ?? null;
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'] ?? null;
            
            if (!$id) throw new Exception('ID utente non fornito.');

            // Check if username exists for OTHER users
            $stmt = $pdo->prepare('SELECT id FROM users WHERE username = ? AND id != ?');
            $stmt->execute([$username, $id]);
            if ($stmt->fetch()) throw new Exception('Username già in uso da un altro utente.');

            if (!empty($password)) {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare('UPDATE users SET username = ?, email = ?, password = ? WHERE id = ?');
                $stmt->execute([$username, $email, $hashedPassword, $id]);
            } else {
                $stmt = $pdo->prepare('UPDATE users SET username = ?, email = ? WHERE id = ?');
                $stmt->execute([$username, $email, $id]);
            }
            
            echo json_encode(['status' => 'success', 'message' => 'Utente aggiornato con successo.']);
            break;

        case 'delete':
            $id = $_POST['id'] ?? null;
            if (!$id) throw new Exception('ID utente non fornito.');
            
            // Prevent deleting self (optional but recommended)
            session_start();
            if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $id) {
                throw new Exception('Non puoi eliminare il tuo stesso account mentre sei loggato.');
            }

            $stmt = $pdo->prepare('DELETE FROM users WHERE id = ?');
            $stmt->execute([$id]);
            
            echo json_encode(['status' => 'success', 'message' => 'Utente eliminato con successo.']);
            break;

        default:
            throw new Exception('Azione non valida.');
    }
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
