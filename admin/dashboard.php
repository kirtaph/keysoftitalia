<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/css/main.css">
    <link rel="stylesheet" href="../assets/css/components.css">
</head>
<body>
    <div class="header">
        <div class="container">
            <div class="header-main">
                <div class="header-main-content">
                    <a href="dashboard.php" class="logo">
                        <span class="logo-text">
                            <span class="logo-title">Key Soft Italia</span>
                            <span class="logo-subtitle">Admin Panel</span>
                        </span>
                    </a>
                    <nav class="nav-main">
                        <ul class="nav-menu">
                            <li class="nav-item"><a href="logout.php" class="nav-link">Logout</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <main class="section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Dashboard</h2>
                <p class="section-subtitle">Benvenuto, <?php echo htmlspecialchars($_SESSION['username']); ?>!</p>
            </div>
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Gestione Dispositivi</h5>
                            <p class="card-text">Aggiungi, modifica o elimina i tipi di dispositivo.</p>
                            <a href="devices.php" class="btn btn-primary">Vai</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Gestione Marchi</h5>
                            <p class="card-text">Aggiungi, modifica o elimina i marchi.</p>
                            <a href="brands.php" class="btn btn-primary">Vai</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Gestione Modelli</h5>
                            <p class="card-text">Aggiungi, modifica o elimina i modelli.</p>
                            <a href="models.php" class="btn btn-primary">Vai</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Gestione Problemi</h5>
                            <p class="card-text">Aggiungi, modifica o elimina i problemi comuni.</p>
                            <a href="issues.php" class="btn btn-primary">Vai</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Gestione Regole di Prezzo</h5>
                            <p class="card-text">Definisci i prezzi per le riparazioni.</p>
                            <a href="price_rules.php" class="btn btn-primary">Vai</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Visualizza Preventivi</h5>
                            <p class="card-text">Controlla i preventivi inviati dagli utenti.</p>
                            <a href="quotes.php" class="btn btn-primary">Vai</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Gestione Utenti</h5>
                            <p class="card-text">Aggiungi, modifica o elimina gli utenti amministratori.</p>
                            <a href="users.php" class="btn btn-primary">Vai</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>