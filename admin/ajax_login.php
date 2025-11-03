<?php
session_start();
require_once '../config/config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        echo json_encode(['success' => false, 'message' => 'Username e password sono obbligatori.']);
        exit;
    }

    try {
        $stmt = $pdo->prepare('SELECT id, username, password FROM users WHERE username = ?');
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            echo json_encode(['success' => true]);
            exit;
        } else {
            echo json_encode(['success' => false, 'message' => 'Username o password non validi.']);
            exit;
        }
    } catch (PDOException $e) {
        // In un ambiente di produzione, dovresti loggare l'errore invece di mostrarlo.
        // error_log($e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Errore del database.']);
        exit;
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Metodo non consentito.']);
    exit;
}
?>
