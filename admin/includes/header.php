<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}
require_once '../config/config.php';

// Get current page for active state
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Key Soft Italia - Admin Panel</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <!-- Custom Admin CSS -->
    <link rel="stylesheet" href="assets/css/admin-theme.css">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<div class="admin-wrapper">
    <!-- Sidebar -->
    <nav class="admin-sidebar" id="sidebar">
        <div class="sidebar-header">
            <a href="dashboard.php" class="sidebar-brand">
                Key Soft Italia
                <small>Admin Panel</small>
            </a>
        </div>
        
        <ul class="sidebar-menu">
            <li class="sidebar-item">
                <a href="dashboard.php" class="sidebar-link <?php echo $currentPage === 'dashboard.php' ? 'active' : ''; ?>">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            
            <li class="sidebar-divider"></li>
            <div class="sidebar-heading">Gestione Riparazioni</div>
            
            <li class="sidebar-item">
                <a href="bookings.php" class="sidebar-link <?php echo $currentPage === 'bookings.php' ? 'active' : ''; ?>">
                    <i class="fas fa-calendar-check"></i>
                    <span>Prenotazioni</span>
                </a>
            </li>
            <li class="sidebar-item">
                <a href="quotes.php" class="sidebar-link <?php echo $currentPage === 'quotes.php' ? 'active' : ''; ?>">
                    <i class="fas fa-file-invoice-dollar"></i>
                    <span>Preventivi</span>
                </a>
            </li>
            <li class="sidebar-item">
                <a href="price_rules.php" class="sidebar-link <?php echo $currentPage === 'price_rules.php' ? 'active' : ''; ?>">
                    <i class="fas fa-tags"></i>
                    <span>Regole Prezzo</span>
                </a>
            </li>
            <li class="sidebar-divider"></li>
            <div class="sidebar-heading">Valutazione Usato</div>
            
            <li class="sidebar-item">
                <a href="used_quotes.php" class="sidebar-link <?php echo $currentPage === 'used_quotes.php' ? 'active' : ''; ?>">
                    <i class="fas fa-recycle"></i>
                    <span>Richieste Valutazione</span>
                </a>
            </li>
            
            <li class="sidebar-divider"></li>
            <div class="sidebar-heading">Catalogo & Config</div>
            
            <li class="sidebar-item">
                <a href="devices.php" class="sidebar-link <?php echo $currentPage === 'devices.php' && (!isset($_GET['tab']) || $_GET['tab'] == 'devices') ? 'active' : ''; ?>" id="nav-devices">
                    <i class="fas fa-mobile-alt"></i>
                    <span>Dispositivi</span>
                </a>
            </li>
            <li class="sidebar-item">
                <a href="devices.php#brands" class="sidebar-link" id="nav-brands">
                    <i class="fas fa-copyright"></i>
                    <span>Marchi</span>
                </a>
            </li>
            <li class="sidebar-item">
                <a href="devices.php#models" class="sidebar-link" id="nav-models">
                    <i class="fas fa-tablet-alt"></i>
                    <span>Modelli</span>
                </a>
            </li>
            <li class="sidebar-item">
                <a href="devices.php#issues" class="sidebar-link" id="nav-issues">
                    <i class="fas fa-tools"></i>
                    <span>Problemi</span>
                </a>
            </li>
            
            <li class="sidebar-divider"></li>
            <div class="sidebar-heading">Offerte</div>
            
            <li class="sidebar-item">
                <a href="products.php" class="sidebar-link <?php echo $currentPage === 'products.php' ? 'active' : ''; ?>">
                    <i class="fas fa-box-open"></i>
                    <span>Prodotti</span>
                </a>
            </li>
            <li class="sidebar-item">
                <a href="flyers.php" class="sidebar-link <?php echo $currentPage === 'flyers.php' ? 'active' : ''; ?>">
                    <i class="fas fa-newspaper"></i>
                    <span>Volantini</span>
                </a>
            </li>
            
            <li class="sidebar-divider"></li>
            <div class="sidebar-heading">Impostazioni</div>
            
            <li class="sidebar-item">
                <a href="weekly_hours.php" class="sidebar-link <?php echo $currentPage === 'weekly_hours.php' ? 'active' : ''; ?>">
                    <i class="fas fa-clock"></i>
                    <span>Orari</span>
                </a>
            </li>
            <li class="sidebar-item">
                <a href="users.php" class="sidebar-link <?php echo $currentPage === 'users.php' ? 'active' : ''; ?>">
                    <i class="fas fa-users"></i>
                    <span>Utenti</span>
                </a>
            </li>
        </ul>
    </nav>

    <!-- Main Content Wrapper -->
    <div class="admin-main">
        <!-- Topbar -->
        <header class="admin-topbar">
            <button class="topbar-toggle" id="sidebarToggle">
                <i class="fas fa-bars"></i>
            </button>
            
            <div class="ms-auto user-profile">
                <div class="dropdown">
                    <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle text-dark" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="user-avatar me-2">
                            <?php echo strtoupper(substr($_SESSION['username'] ?? 'A', 0, 1)); ?>
                        </div>
                        <span class="d-none d-md-inline fw-bold"><?php echo htmlspecialchars($_SESSION['username'] ?? 'Admin'); ?></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                        <li><a class="dropdown-item" href="users.php"><i class="fas fa-user-cog me-2"></i>Profilo</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                    </ul>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main class="admin-content">