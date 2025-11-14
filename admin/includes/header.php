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
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/main.css">
    <link rel="stylesheet" href="../assets/css/components.css">
    <style>
        .image-thumbnail {
            position: relative;
            cursor: move;
        }
        .cover-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            color: white;
            display: none;
            justify-content: center;
            align-items: center;
            font-weight: bold;
        }
        .image-thumbnail.is-cover .cover-overlay {
            display: flex;
        }
        .sortable-images .col-md-3 {
            padding: 5px;
        }
    </style>
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
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Preventivi
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li class="dropdown-item"><a href="devices.php" class="nav-link">Dispositivi</a></li>
                            <li class="dropdown-item"><a href="brands.php" class="nav-link">Marchi</a></li>
                            <li class="dropdown-item"><a href="models.php" class="nav-link">Modelli</a></li>
                            <li class="dropdown-item"><a href="issues.php" class="nav-link">Problemi</a></li>
                            <li class="dropdown-item"><a href="price_rules.php" class="nav-link">Regole di Prezzo</a></li>
                            <li class="dropdown-item"><a href="quotes.php" class="nav-link">Preventivi</a></li>
                            </ul>
                            </li>
                             <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownRicondizionati" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Offerte
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdownRicondizionati">
                                    <li><a class="dropdown-item" href="products.php">Prodotti</a></li>
                                    <li><a class="dropdown-item" href="flyers.php">Volantini</a></li>
                                </ul>
                            </li>
                            
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Orari di Apertura
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <li><a class="dropdown-item" href="weekly_hours.php">Orario Settimanale</a></li>
                                    <li><a class="dropdown-item" href="holidays.php">Festività</a></li>
                                    <li><a class="dropdown-item" href="exceptions.php">Eccezioni</a></li>
                                </ul>
                            </li>
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