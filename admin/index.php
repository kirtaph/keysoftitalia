<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="../assets/css/main.css">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: var(--ks-gray-100);
        }
        .login-card {
            width: 100%;
            max-width: 400px;
            padding: var(--ks-spacing-8);
        }
        #error-message {
            display: none;
            margin-top: 1rem;
        }
    </style>
</head>
<body>
    <div class="card login-card">
        <h3 class="card-title text-center">Admin Login</h3>
        <div id="error-message" class="alert alert-danger" role="alert"></div>
        <form id="login-form">
            <div class="form-group">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Login</button>
        </form>
    </div>
    <script>
        document.getElementById('login-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const errorMessage = document.getElementById('error-message');

            fetch('ajax_login.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = 'dashboard.php';
                } else {
                    errorMessage.textContent = data.message;
                    errorMessage.style.display = 'block';
                }
            })
            .catch(error => {
                errorMessage.textContent = 'Si è verificato un errore. Riprova.';
                errorMessage.style.display = 'block';
            });
        });
    </script>
</body>
</html>
