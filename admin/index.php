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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #0f172a;
            overflow: hidden;
            position: relative;
        }

        /* Mesh gradient background */
        .login-bg {
            position: fixed;
            inset: 0;
            z-index: 0;
            background:
                radial-gradient(800px circle at 10% 20%, rgba(255,107,53,0.20) 0%, transparent 55%),
                radial-gradient(600px circle at 90% 30%, rgba(59,130,246,0.15) 0%, transparent 55%),
                radial-gradient(700px circle at 50% 80%, rgba(139,92,246,0.12) 0%, transparent 55%),
                #0f172a;
            animation: loginMesh 12s ease-in-out infinite alternate;
        }

        @keyframes loginMesh {
            0%   { background-position: 0% 0%; }
            100% { background-position: 5% 3%; }
        }

        /* Floating blobs */
        .login-blob {
            position: fixed;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.5;
            pointer-events: none;
            z-index: 0;
        }
        .login-blob-1 {
            width: 400px; height: 400px;
            background: rgba(255,107,53,0.25);
            top: -100px; left: -100px;
            animation: blobFloat 20s ease-in-out infinite;
        }
        .login-blob-2 {
            width: 350px; height: 350px;
            background: rgba(59,130,246,0.20);
            bottom: -80px; right: -80px;
            animation: blobFloat 25s ease-in-out infinite reverse;
        }
        .login-blob-3 {
            width: 250px; height: 250px;
            background: rgba(139,92,246,0.15);
            top: 50%; left: 60%;
            animation: blobFloat 18s ease-in-out infinite 3s;
        }

        @keyframes blobFloat {
            0%, 100% { transform: translate(0, 0) scale(1); }
            25%      { transform: translate(40px, -50px) scale(1.1); }
            50%      { transform: translate(-20px, 20px) scale(0.95); }
            75%      { transform: translate(30px, 40px) scale(1.05); }
        }

        /* Login card */
        .login-wrapper {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 420px;
            padding: 1rem;
        }

        @keyframes cardIn {
            from { opacity: 0; transform: translateY(20px) scale(0.97); }
            to   { opacity: 1; transform: translateY(0) scale(1); }
        }

        .login-card {
            border: none;
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.06);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            overflow: hidden;
            animation: cardIn 0.6s ease both;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }

        .login-card-header {
            padding: 2.5rem 2rem 1.5rem;
            text-align: center;
        }

        .login-brand-icon {
            width: 64px;
            height: 64px;
            background: linear-gradient(135deg, #ff6b35 0%, #ff8c42 100%);
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.25rem;
            font-size: 1.6rem;
            color: #fff;
            box-shadow: 0 8px 24px rgba(255, 107, 53, 0.35);
        }

        .login-card-header h4 {
            color: #fff;
            font-weight: 700;
            font-size: 1.4rem;
            margin-bottom: 0.25rem;
        }

        .login-card-header p {
            color: rgba(255, 255, 255, 0.5);
            font-size: 0.85rem;
            margin: 0;
        }

        .login-card-body {
            padding: 0 2rem 2.5rem;
        }

        /* Form controls on dark glass */
        .form-floating > .form-control {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #fff;
            border-radius: 12px;
            height: calc(3.8rem + 2px);
            line-height: 1.25;
            padding-top: 1.2rem;
        }

        .form-floating > .form-control:focus {
            background: rgba(255, 255, 255, 0.08);
            border-color: #ff6b35;
            box-shadow: 0 0 0 3px rgba(255, 107, 53, 0.15);
        }

        .form-floating > .form-control::placeholder { color: transparent; }
        .form-floating > .form-control:not(:placeholder-shown) { padding-top: 1.2rem; }

        .form-floating > label {
            padding: 1rem 1rem;
            color: rgba(255, 255, 255, 0.45);
            font-size: 0.9rem;
        }

        .form-floating > .form-control:focus ~ label,
        .form-floating > .form-control:not(:placeholder-shown) ~ label {
            opacity: 0.8;
            transform: scale(0.85) translateY(-0.5rem) translateX(0.15rem);
            color: rgba(255, 255, 255, 0.6);
        }

        .form-floating > .form-control:-webkit-autofill {
            -webkit-box-shadow: 0 0 0 1000px rgba(15, 23, 42, 0.9) inset !important;
            -webkit-text-fill-color: #fff !important;
            border-color: rgba(255, 107, 53, 0.3);
        }

        .btn-login {
            padding: 0.85rem;
            font-weight: 700;
            border-radius: 12px;
            background: linear-gradient(135deg, #ff6b35 0%, #ff8c42 100%);
            border: none;
            color: #fff;
            transition: all 0.3s ease;
            box-shadow: 0 4px 14px rgba(255, 107, 53, 0.35);
        }

        .btn-login:hover,
        .btn-login:focus {
            background: linear-gradient(135deg, #e55a2b 0%, #ff6b35 100%);
            box-shadow: 0 6px 20px rgba(255, 107, 53, 0.45);
            transform: translateY(-1px);
            color: #fff;
        }

        .btn-login:disabled {
            opacity: 0.7;
            transform: none;
        }

        .login-footer-text {
            text-align: center;
            margin-top: 1.5rem;
            color: rgba(255, 255, 255, 0.3);
            font-size: 0.8rem;
        }

        .login-footer-text a {
            color: rgba(255, 107, 53, 0.7);
            text-decoration: none;
            transition: color 0.2s;
        }

        .login-footer-text a:hover {
            color: #ff6b35;
        }

        /* Alert on dark glass */
        .alert-glass {
            background: rgba(220, 53, 69, 0.15);
            border: 1px solid rgba(220, 53, 69, 0.25);
            color: #fca5a5;
            border-radius: 12px;
            padding: 0.75rem 1rem;
            font-size: 0.85rem;
        }

        /* Shake keyframe */
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            20%      { transform: translateX(-8px); }
            40%      { transform: translateX(8px); }
            60%      { transform: translateX(-5px); }
            80%      { transform: translateX(5px); }
        }

        .shake {
            animation: shake 0.4s ease;
        }

        /* Spinner */
        .spinner-border-sm {
            width: 1rem;
            height: 1rem;
            border-width: 2px;
        }
    </style>
</head>
<body>

    <div class="login-bg"></div>
    <div class="login-blob login-blob-1"></div>
    <div class="login-blob login-blob-2"></div>
    <div class="login-blob login-blob-3"></div>

    <div class="login-wrapper">
        <div class="login-card">
            <div class="login-card-header">
                <div class="login-brand-icon">
                    <i class="fas fa-key"></i>
                </div>
                <h4>Admin Login</h4>
                <p>Accedi al pannello di controllo</p>
            </div>
            <div class="login-card-body">
                <div id="error-message" class="alert-glass d-none d-flex align-items-center" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <span id="error-text"></span>
                </div>

                <form id="login-form" class="mt-2">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="username" name="username" placeholder="Username" required autocomplete="username">
                        <label for="username"><i class="fas fa-user me-2"></i>Username</label>
                    </div>
                    <div class="form-floating mb-4">
                        <input type="password" class="form-control" id="password" name="password" placeholder="Password" required autocomplete="current-password">
                        <label for="password"><i class="fas fa-lock me-2"></i>Password</label>
                    </div>

                    <button type="submit" class="btn btn-login w-100" id="loginBtn">
                        <span class="spinner-border spinner-border-sm me-2 d-none" id="loadingSpinner" role="status" aria-hidden="true"></span>
                        <span id="btnText">Accedi</span>
                    </button>
                </form>

                <div class="login-footer-text">
                    &copy; <?php echo date('Y'); ?> Key Soft Italia &mdash;
                    <a href="../index.php" target="_blank">Torna al sito</a>
                </div>
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
            const card = document.querySelector('.login-card');

            errorAlert.classList.add('d-none');
            card.classList.remove('shake');
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
                    btnText.textContent = 'Accesso riuscito!';
                    setTimeout(() => { window.location.href = 'dashboard.php'; }, 300);
                } else {
                    throw new Error(data.message || 'Credenziali non valide');
                }
            })
            .catch(error => {
                errorText.textContent = error.message;
                errorAlert.classList.remove('d-none');

                btn.disabled = false;
                spinner.classList.add('d-none');
                btnText.textContent = 'Accedi';

                card.classList.add('shake');
                setTimeout(() => card.classList.remove('shake'), 500);
            });
        });
    </script>
</body>
</html>
