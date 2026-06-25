<?php
require_once __DIR__ . '/init.php';

$action = $_REQUEST['action'] ?? null;

try {
    switch ($action) {
        case 'get':
            $id = $_GET['id'] ?? null;
            if (!$id) jsonError('ID utente non fornito.');
            
            $stmt = $pdo->prepare('SELECT id, username, email FROM users WHERE id = ?');
            $stmt->execute([$id]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$user) jsonError('Utente non trovato.');
            
            jsonSuccess(['user' => $user]);
            break;

        case 'add':
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            
            if (empty($password)) jsonError('La password è obbligatoria per i nuovi utenti.');
            
            $stmt = $pdo->prepare('SELECT id FROM users WHERE username = ?');
            $stmt->execute([$username]);
            if ($stmt->fetch()) jsonError('Username già in uso.');

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            
            $stmt = $pdo->prepare('INSERT INTO users (username, email, password) VALUES (?, ?, ?)');
            $stmt->execute([$username, $email, $hashedPassword]);
            
            jsonSuccess(['message' => 'Utente creato con successo.']);
            break;

        case 'edit':
            $id = $_POST['id'] ?? null;
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'] ?? null;
            
            if (!$id) jsonError('ID utente non fornito.');

            $stmt = $pdo->prepare('SELECT id FROM users WHERE username = ? AND id != ?');
            $stmt->execute([$username, $id]);
            if ($stmt->fetch()) jsonError('Username già in uso da un altro utente.');

            if (!empty($password)) {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare('UPDATE users SET username = ?, email = ?, password = ? WHERE id = ?');
                $stmt->execute([$username, $email, $hashedPassword, $id]);
            } else {
                $stmt = $pdo->prepare('UPDATE users SET username = ?, email = ? WHERE id = ?');
                $stmt->execute([$username, $email, $id]);
            }
            
            jsonSuccess(['message' => 'Utente aggiornato con successo.']);
            break;

        case 'delete':
            $id = $_POST['id'] ?? null;
            if (!$id) jsonError('ID utente non fornito.');
            
            if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $id) {
                jsonError('Non puoi eliminare il tuo stesso account mentre sei loggato.');
            }

            $stmt = $pdo->prepare('DELETE FROM users WHERE id = ?');
            $stmt->execute([$id]);
            
            jsonSuccess(['message' => 'Utente eliminato con successo.']);
            break;

        default:
            jsonError('Azione non valida.');
    }
} catch (Throwable $e) {
    jsonError('Errore del server.', $e);
}
