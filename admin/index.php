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
    <title>Login Amministrazione - Key Soft Italia</title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            background-color: #f3f4f6;
            font-family: 'Inter', sans-serif;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            overflow: hidden;
            width: 100%;
            max-width: 400px;
        }
        .card-header {
            background: #fff;
            border-bottom: 1px solid #f0f0f0;
            padding: 2rem 1.5rem 1rem;
            text-align: center;
        }
        .brand-icon {
            width: 60px;
            height: 60px;
            background: rgba(13, 110, 253, 0.1);
            color: #0d6efd;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 1.5rem;
        }
        .card-body {
            padding: 2rem;
            background: #fff;
        }
        .form-floating > .form-control:focus {
            box-shadow: none;
            border-color: #0d6efd;
        }
        .btn-primary {
            padding: 0.8rem;
            font-weight: 600;
            border-radius: 0.5rem;
        }
        .footer-text {
            text-align: center;
            margin-top: 1.5rem;
            color: #6c757d;
            font-size: 0.875rem;
        }
    </style>
</head>
<body>

    <div class="card login-card">
        <div class="card-header">
            <div class="brand-icon">
                <i class="fas fa-user-shield"></i>
            </div>
            <h4 class="fw-bold text-dark mb-1">Admin Login</h4>
            <p class="text-muted small mb-0">Accedi al pannello di controllo</p>
        </div>
        <div class="card-body">
            <div id="error-message" class="alert alert-danger d-none d-flex align-items-center" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                <span id="error-text"></span>
            </div>

            <form id="login-form">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="username" name="username" placeholder="Username" required>
                    <label for="username"><i class="fas fa-user me-2 text-muted"></i>Username</label>
                </div>
                <div class="form-floating mb-4">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                    <label for="password"><i class="fas fa-lock me-2 text-muted"></i>Password</label>
                </div>
                
                <button type="submit" class="btn btn-primary w-100" id="loginBtn">
                    <span class="spinner-border spinner-border-sm me-2 d-none" id="loadingSpinner" role="status" aria-hidden="true"></span>
                    <span id="btnText">Accedi</span>
                </button>
            </form>

            <div class="footer-text">
                &copy; <?php echo date('Y'); ?> Key Soft Italia
            </div>
        </div>
    </div>

    <script>
        document.getElementById('login-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const form = this;
            const btn = document.getElementById('loginBtn');
            const spinner = document.getElementById('loadingSpinner');
            const btnText = document.getElementById('btnText');
            const errorAlert = document.getElementById('error-message');
            const errorText = document.getElementById('error-text');

            // Reset UI
            errorAlert.classList.add('d-none');
            btn.disabled = true;
            spinner.classList.remove('d-none');
            btnText.textContent = 'Verifica in corso...';

            const formData = new FormData(form);

            fetch('ajax_login.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = 'dashboard.php';
                } else {
                    throw new Error(data.message || 'Credenziali non valide');
                }
            })
            .catch(error => {
                errorText.textContent = error.message;
                errorAlert.classList.remove('d-none');
                
                // Reset Button
                btn.disabled = false;
                spinner.classList.add('d-none');
                btnText.textContent = 'Accedi';
                
                // Shake animation effect
                document.querySelector('.login-card').animate([
                    { transform: 'translateX(0)' },
                    { transform: 'translateX(-10px)' },
                    { transform: 'translateX(10px)' },
                    { transform: 'translateX(-10px)' },
                    { transform: 'translateX(0)' }
                ], {
                    duration: 400,
                    iterations: 1
                });
            });
        });
    </script>
</body>
</html>
