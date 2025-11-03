<?php
session_start();
require_once '../config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        header('Location: index.php?error=Username e password sono obbligatori.');
        exit;
    }

    try {
        $stmt = $pdo->prepare('SELECT id, username, password FROM users WHERE username = ?');
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header('Location: dashboard.php');
            exit;
        } else {
            header('Location: index.php?error=Username o password non validi.');
            exit;
        }
    } catch (PDOException $e) {
        header('Location: index.php?error=Errore del database.');
        exit;
    }
} else {
    header('Location: index.php');
    exit;
}
?>