<?php
require_once __DIR__ . '/../../src/BackendHttp.php';
if (session_status() === PHP_SESSION_NONE) {
    \KeySoftItalia\BackendHttp::startSession();
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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <!-- Custom Admin CSS -->
    <link rel="stylesheet" href="assets/css/admin-theme.css">
    <link rel="stylesheet" href="assets/css/admin-workspace.css">
    
    <?php
    // Ensure CSRF token exists for admin AJAX calls
    require_once __DIR__ . '/../../assets/php/functions.php';
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    ?>
    <script>
        // Auto-inject CSRF token into all admin POST fetch requests with FormData
        window.ADMIN_CSRF_TOKEN = '<?php echo $_SESSION['csrf_token']; ?>';
        (function() {
            var origFetch = window.fetch;
            window.fetch = function(url, opts) {
                if (opts && opts.body && opts.body instanceof FormData && 
                    (!opts.method || opts.method.toUpperCase() === 'POST')) {
                    opts.body.set('csrf_token', window.ADMIN_CSRF_TOKEN);
                }
                return origFetch.call(this, url, opts);
            };
        })();
    </script>
</head>
<body>
<a href="#adminContent" class="skip-link">Vai al contenuto</a>

<div class="admin-wrapper">
    <!-- Sidebar -->
    <nav class="admin-sidebar" id="sidebar" aria-label="Navigazione amministrazione">
        <div class="sidebar-header">
            <a href="dashboard.php" class="sidebar-brand">
                <div class="sidebar-brand-icon">
                    <i class="fas fa-key" aria-hidden="true"></i>
                </div>
                <div>
                    Key Soft Italia
                    <small>Area di lavoro</small>
                </div>
            </a>
        </div>
        
        <ul class="sidebar-menu">
            <li class="sidebar-item">
                <a href="dashboard.php" class="sidebar-link <?php echo $currentPage === 'dashboard.php' ? 'active' : ''; ?>">
                    <i class="fas fa-tachometer-alt" aria-hidden="true"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            
            <li class="sidebar-item"><a href="notifications.php" class="sidebar-link <?php echo $currentPage === 'notifications.php' ? 'active' : ''; ?>"><i class="fas fa-inbox" aria-hidden="true"></i><span>Centro notifiche</span></a></li>
            <li class="sidebar-divider"></li>
            <li class="sidebar-heading">Gestione Riparazioni</li>
            
            <li class="sidebar-item">
                <a href="bookings.php" class="sidebar-link <?php echo $currentPage === 'bookings.php' ? 'active' : ''; ?>">
                    <i class="fas fa-calendar-check" aria-hidden="true"></i>
                    <span>Prenotazioni</span>
                </a>
            </li>
            <li class="sidebar-item">
                <a href="quotes.php" class="sidebar-link <?php echo $currentPage === 'quotes.php' ? 'active' : ''; ?>">
                    <i class="fas fa-file-invoice-dollar" aria-hidden="true"></i>
                    <span>Preventivi</span>
                </a>
            </li>
            <li class="sidebar-item">
                <a href="price_rules.php" class="sidebar-link <?php echo $currentPage === 'price_rules.php' ? 'active' : ''; ?>">
                    <i class="fas fa-tags" aria-hidden="true"></i>
                    <span>Regole Prezzo</span>
                </a>
            </li>
            <li class="sidebar-divider"></li>
            <li class="sidebar-heading">Valutazione Usato</li>
            
            <li class="sidebar-item">
                <a href="used_quotes.php" class="sidebar-link <?php echo $currentPage === 'used_quotes.php' ? 'active' : ''; ?>">
                    <i class="fas fa-recycle" aria-hidden="true"></i>
                    <span>Richieste Valutazione</span>
                </a>
            </li>
            
            <li class="sidebar-divider"></li>
            <li class="sidebar-heading">Catalogo & Config</li>
            
            <li class="sidebar-item">
                <a href="devices.php" class="sidebar-link <?php echo $currentPage === 'devices.php' && (!isset($_GET['tab']) || $_GET['tab'] == 'devices') ? 'active' : ''; ?>" id="nav-devices">
                    <i class="fas fa-mobile-alt" aria-hidden="true"></i>
                    <span>Dispositivi</span>
                </a>
            </li>
            <li class="sidebar-item">
                <a href="devices.php#brands" class="sidebar-link" id="nav-brands">
                    <i class="fas fa-copyright" aria-hidden="true"></i>
                    <span>Marchi</span>
                </a>
            </li>
            <li class="sidebar-item">
                <a href="devices.php#models" class="sidebar-link" id="nav-models">
                    <i class="fas fa-tablet-alt" aria-hidden="true"></i>
                    <span>Modelli</span>
                </a>
            </li>
            <li class="sidebar-item">
                <a href="devices.php#issues" class="sidebar-link" id="nav-issues">
                    <i class="fas fa-tools" aria-hidden="true"></i>
                    <span>Problemi</span>
                </a>
            </li>
            <li class="sidebar-item">
                <a href="team.php" class="sidebar-link <?php echo $currentPage === 'team.php' ? 'active' : ''; ?>">
                    <i class="fas fa-users" aria-hidden="true"></i>
                    <span>Membri Team</span>
                </a>
            </li>
            
            <li class="sidebar-divider"></li>
            <li class="sidebar-heading">Offerte</li>
            
            <li class="sidebar-item">
                <a href="products.php" class="sidebar-link <?php echo $currentPage === 'products.php' ? 'active' : ''; ?>">
                    <i class="fas fa-box-open" aria-hidden="true"></i>
                    <span>Prodotti</span>
                </a>
            </li>
            <li class="sidebar-item">
                <a href="flyers.php" class="sidebar-link <?php echo $currentPage === 'flyers.php' ? 'active' : ''; ?>">
                    <i class="fas fa-newspaper" aria-hidden="true"></i>
                    <span>Volantini</span>
                </a>
            </li>
            <li class="sidebar-item">
                <a href="videos.php" class="sidebar-link <?php echo $currentPage === 'videos.php' ? 'active' : ''; ?>">
                    <i class="fas fa-video" aria-hidden="true"></i>
                    <span>Video Prodotti</span>
                </a>
            </li>
            <li class="sidebar-item">
                <a href="telefonia.php" class="sidebar-link <?php echo $currentPage === 'telefonia.php' ? 'active' : ''; ?>">
                    <i class="fas fa-phone-alt" aria-hidden="true"></i>
                    <span>Telefonia</span>
                </a>
            </li>
            <li class="sidebar-item">
                <a href="forniture.php" class="sidebar-link <?php echo $currentPage === 'forniture.php' ? 'active' : ''; ?>">
                    <i class="fas fa-bolt" aria-hidden="true"></i>
                    <span>Forniture Luce & Gas</span>
                </a>
            </li>
            <li class="sidebar-item">
                <a href="sviluppo-web.php" class="sidebar-link <?php echo $currentPage === 'sviluppo-web.php' ? 'active' : ''; ?>">
                    <i class="fas fa-code" aria-hidden="true"></i>
                    <span>Sviluppo Web</span>
                </a>
            </li>
            <li class="sidebar-item">
                <a href="consulenza-it.php" class="sidebar-link <?php echo $currentPage === 'consulenza-it.php' ? 'active' : ''; ?>">
                    <i class="fas fa-network-wired" aria-hidden="true"></i>
                    <span>Consulenza IT</span>
                </a>
            </li>
            <li class="sidebar-item">
                <a href="liberty_demo.php" class="sidebar-link <?php echo $currentPage === 'liberty_demo.php' ? 'active' : ''; ?>">
                    <i class="fas fa-file-download" aria-hidden="true"></i>
                    <span>Demo Liberty</span>
                </a>
            </li>

            <li class="sidebar-divider"></li>
            <li class="sidebar-heading">Impostazioni Sistema</li>
            
            <li class="sidebar-item">
                <a href="logo_campaigns.php" class="sidebar-link <?php echo $currentPage === 'logo_campaigns.php' ? 'active' : ''; ?>">
                    <i class="fas fa-images" aria-hidden="true"></i>
                    <span>Campagne Logo</span>
                </a>
            </li>
            <li class="sidebar-item">
                <a href="keyos_api.php" class="sidebar-link <?php echo $currentPage === 'keyos_api.php' ? 'active' : ''; ?>">
                    <i class="fas fa-key" aria-hidden="true"></i>
                    <span>API KeyOS</span>
                </a>
            </li>
            
            <li class="sidebar-divider"></li>
            <li class="sidebar-heading">Impostazioni</li>
            
            <li class="sidebar-item">
                <a href="weekly_hours.php" class="sidebar-link <?php echo $currentPage === 'weekly_hours.php' ? 'active' : ''; ?>">
                    <i class="fas fa-clock" aria-hidden="true"></i>
                    <span>Orari</span>
                </a>
            </li>
            <li class="sidebar-item">
                <a href="users.php" class="sidebar-link <?php echo $currentPage === 'users.php' ? 'active' : ''; ?>">
                    <i class="fas fa-users" aria-hidden="true"></i>
                    <span>Utenti</span>
                </a>
            </li>
        </ul>
    </nav>

    <!-- Main Content Wrapper -->
    <div class="admin-main">
        <!-- Topbar -->
        <header class="admin-topbar">
            <button type="button" class="topbar-toggle" id="sidebarToggle" aria-label="Apri menu di navigazione" aria-controls="sidebar" aria-expanded="false">
                <i class="fas fa-bars" aria-hidden="true"></i>
            </button>
            
            <div class="workspace-context"><strong>Area di lavoro</strong><span>Key Soft Italia</span></div>
            <div class="ms-auto d-flex align-items-center">
                <a href="notifications.php" class="inbox-trigger" aria-label="Centro notifiche"><i class="fas fa-bell" aria-hidden="true"></i><span class="inbox-label">Notifiche</span><span class="inbox-badge" id="notificationBadge" hidden></span></a>
                <a href="../index.php" target="_blank" rel="noopener noreferrer" class="site-link me-3 d-none d-md-inline-flex"><i class="fas fa-external-link-alt me-2" aria-hidden="true"></i>Vai al sito</a>

                <div class="user-profile">
                    <div class="dropdown">
                        <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle text-dark" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="user-avatar me-2" style="background:linear-gradient(135deg,#ff6b35,#ff8c42);">
                                <?php echo htmlspecialchars(strtoupper(substr($_SESSION['username'] ?? 'A', 0, 1))); ?>
                            </div>
                            <span class="d-none d-md-inline fw-bold"><?php echo htmlspecialchars($_SESSION['username'] ?? 'Admin'); ?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="users.php"><i class="fas fa-user-cog me-2" aria-hidden="true"></i>Profilo</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="logout.php"><i class="fas fa-sign-out-alt me-2" aria-hidden="true"></i>Logout</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main class="admin-content" id="adminContent" tabindex="-1">
