<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}
require_once '../config/config.php';
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
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
                            <li class="nav-item"><a href="dashboard.php" class="nav-link">Dashboard</a></li>
                            <li class="nav-item"><a href="devices.php" class="nav-link">Dispositivi</a></li>
                            <li class="nav-item"><a href="brands.php" class="nav-link">Marchi</a></li>
                            <li class="nav-item"><a href="models.php" class="nav-link">Modelli</a></li>
                            <li class="nav-item"><a href="issues.php" class="nav-link">Problemi</a></li>
                            <li class="nav-item"><a href="price_rules.php" class="nav-link">Regole di Prezzo</a></li>
                            <li class="nav-item"><a href="quotes.php" class="nav-link">Preventivi</a></li>
                            <li class="nav-item"><a href="users.php" class="nav-link">Utenti</a></li>
                            <li class="nav-item"><a href="logout.php" class="nav-link">Logout</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <main class="section">
        <div class="container">