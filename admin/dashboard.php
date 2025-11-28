<?php
include_once 'includes/header.php';

// --- KPI Data Fetching ---

// 1. Prenotazioni Oggi
$stmt = $pdo->prepare("SELECT COUNT(*) FROM repair_bookings WHERE DATE(created_at) = CURDATE()");
$stmt->execute();
$bookingsToday = $stmt->fetchColumn();

// 2. Da Valutare (Preventivi Usato + Riparazioni Pending)
$stmt = $pdo->query("SELECT COUNT(*) FROM used_device_quotes WHERE status = 'pending'");
$pendingQuotes = $stmt->fetchColumn();

// 3. Volantino Attivo
$stmt = $pdo->query("SELECT title FROM flyers WHERE status = 'published' AND start_date <= CURDATE() AND end_date >= CURDATE() LIMIT 1");
$activeFlyer = $stmt->fetchColumn();
$flyerStatus = $activeFlyer ? 'Attivo' : 'Nessuno';
$flyerColor = $activeFlyer ? 'text-success' : 'text-muted';

// 4. Stato Negozio (Semplificato)
$currentDow = date('N'); // 1 (Mon) - 7 (Sun)
$currentTime = date('H:i:s');
// Fetch today's hours
$stmt = $pdo->prepare("SELECT * FROM ks_store_hours_weekly WHERE dow = ? AND active = 1");
$stmt->execute([$currentDow]);
$todayHours = $stmt->fetchAll();

$isOpen = false;
foreach ($todayHours as $h) {
    if ($currentTime >= $h['open_time'] && $currentTime <= $h['close_time']) {
        $isOpen = true;
        break;
    }
}
// Check exceptions (simplified, assuming no exceptions for now to keep it fast, or add query if needed)
// For dashboard speed, we'll stick to weekly hours for this quick check.

$storeStatus = $isOpen ? 'APERTO' : 'CHIUSO';
$storeColor = $isOpen ? 'text-success' : 'text-danger';
$storeIcon = $isOpen ? 'fa-door-open' : 'fa-door-closed';

?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="section-title mb-1"><i class="fas fa-tachometer-alt me-2 text-primary"></i>Dashboard</h2>
        <p class="text-muted mb-0">Panoramica delle attività di oggi.</p>
    </div>
    <div class="text-end">
        <h5 class="mb-0 fw-bold text-dark"><?php echo date('H:i'); ?></h5>
        <span class="text-muted small"><?php echo date('l, d F Y'); ?></span>
    </div>
</div>

<!-- KPI Cards Row -->
<div class="row mb-4">
    <!-- Prenotazioni Oggi -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h6 class="text-muted mb-0 text-uppercase small fw-bold">Prenotazioni Oggi</h6>
                    <div class="icon-shape bg-primary bg-opacity-10 text-primary rounded-circle p-2">
                        <i class="fas fa-calendar-day fa-lg"></i>
                    </div>
                </div>
                <h2 class="fw-bold mb-1"><?php echo $bookingsToday; ?></h2>
                <span class="badge bg-light text-muted rounded-pill">
                    <i class="fas fa-clock me-1"></i>Aggiornato ora
                </span>
            </div>
        </div>
    </div>

    <!-- Da Valutare -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h6 class="text-muted mb-0 text-uppercase small fw-bold">Da Valutare</h6>
                    <div class="icon-shape bg-warning bg-opacity-10 text-warning rounded-circle p-2">
                        <i class="fas fa-tasks fa-lg"></i>
                    </div>
                </div>
                <h2 class="fw-bold mb-1"><?php echo $pendingQuotes; ?></h2>
                <span class="text-muted small">Richieste in attesa</span>
            </div>
        </div>
    </div>

    <!-- Stato Negozio -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h6 class="text-muted mb-0 text-uppercase small fw-bold">Stato Negozio</h6>
                    <div class="icon-shape <?php echo $isOpen ? 'bg-success bg-opacity-10 text-success' : 'bg-danger bg-opacity-10 text-danger'; ?> rounded-circle p-2">
                        <i class="fas <?php echo $storeIcon; ?> fa-lg"></i>
                    </div>
                </div>
                <h2 class="fw-bold mb-1 <?php echo $storeColor; ?>"><?php echo $storeStatus; ?></h2>
                <span class="text-muted small">Basato su orari settimanali</span>
            </div>
        </div>
    </div>

    <!-- Volantino -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h6 class="text-muted mb-0 text-uppercase small fw-bold">Volantino</h6>
                    <div class="icon-shape bg-info bg-opacity-10 text-info rounded-circle p-2">
                        <i class="fas fa-newspaper fa-lg"></i>
                    </div>
                </div>
                <h5 class="fw-bold mb-1 text-truncate <?php echo $flyerColor; ?>" title="<?php echo $activeFlyer; ?>">
                    <?php echo $activeFlyer ? $activeFlyer : 'Nessuno Attivo'; ?>
                </h5>
                <span class="text-muted small">Campagna corrente</span>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions & Recent Activity -->
<div class="row">
    <!-- Left Column: Recent Activity -->
    <div class="col-lg-8 mb-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold text-primary"><i class="fas fa-history me-2"></i>Attività Recenti</h6>
                <a href="bookings.php" class="btn btn-sm btn-light text-primary fw-bold">Vedi Tutte</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Tipo</th>
                                <th>Cliente</th>
                                <th>Dettaglio</th>
                                <th>Data</th>
                                <th class="text-end pe-4">Stato</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Fetch recent bookings
                            $stmt = $pdo->query("SELECT id, customer_first_name, customer_last_name, device_type, brand_name, created_at, status, 'booking' as type FROM repair_bookings ORDER BY created_at DESC LIMIT 5");
                            $recentBookings = $stmt->fetchAll();
                            
                            if (count($recentBookings) > 0):
                                foreach ($recentBookings as $b):
                            ?>
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <div class="icon-circle bg-primary bg-opacity-10 text-primary rounded-circle me-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                            <i class="fas fa-tools small"></i>
                                        </div>
                                        <span class="fw-bold text-dark">Riparazione</span>
                                    </div>
                                </td>
                                <td><?php echo htmlspecialchars($b['customer_first_name'] . ' ' . $b['customer_last_name']); ?></td>
                                <td class="text-muted small"><?php echo htmlspecialchars(ucfirst($b['device_type']) . ' ' . $b['brand_name']); ?></td>
                                <td class="text-muted small"><?php echo date('d/m H:i', strtotime($b['created_at'])); ?></td>
                                <td class="text-end pe-4">
                                    <?php
                                    $statusLabel = match($b['status']) {
                                        'confirmed' => 'Confermato',
                                        'cancelled' => 'Annullato',
                                        'completed' => 'Completato',
                                        'pending' => 'In Attesa',
                                        default => $b['status']
                                    };
                                    $statusClass = match($b['status']) {
                                        'confirmed' => 'bg-success',
                                        'cancelled' => 'bg-danger',
                                        'completed' => 'bg-secondary',
                                        default => 'bg-warning text-dark'
                                    };
                                    ?>
                                    <span class="badge <?php echo $statusClass; ?> rounded-pill"><?php echo htmlspecialchars($statusLabel); ?></span>
                                </td>
                            </tr>
                            <?php endforeach; else: ?>
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="fas fa-inbox fa-2x mb-3 d-block opacity-50"></i>
                                    Nessuna attività recente.
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Quick Actions & Status -->
    <div class="col-lg-4 mb-4">
        <!-- Quick Actions -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-0 py-3">
                <h6 class="mb-0 fw-bold text-primary"><i class="fas fa-bolt me-2"></i>Azioni Rapide</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="bookings.php" class="btn btn-outline-primary text-start p-3 d-flex align-items-center">
                        <div class="bg-primary text-white rounded p-2 me-3"><i class="fas fa-calendar-plus"></i></div>
                        <div>
                            <div class="fw-bold">Gestisci Prenotazioni</div>
                            <div class="small text-muted">Vedi calendario e appuntamenti</div>
                        </div>
                    </a>
                    <a href="used_quotes.php" class="btn btn-outline-info text-start p-3 d-flex align-items-center">
                        <div class="bg-info text-white rounded p-2 me-3"><i class="fas fa-recycle"></i></div>
                        <div>
                            <div class="fw-bold">Valutazioni Usato</div>
                            <div class="small text-muted">Gestisci richieste di permuta</div>
                        </div>
                    </a>
                    <a href="products.php" class="btn btn-outline-success text-start p-3 d-flex align-items-center">
                        <div class="bg-success text-white rounded p-2 me-3"><i class="fas fa-box-open"></i></div>
                        <div>
                            <div class="fw-bold">Gestisci Prodotti</div>
                            <div class="small text-muted">Aggiungi o modifica prodotti</div>
                        </div>
                    </a>
                    <a href="weekly_hours.php" class="btn btn-outline-secondary text-start p-3 d-flex align-items-center">
                        <div class="bg-secondary text-white rounded p-2 me-3"><i class="fas fa-clock"></i></div>
                        <div>
                            <div class="fw-bold">Orari & Chiusure</div>
                            <div class="small text-muted">Modifica orari o aggiungi ferie</div>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <!-- System Status / Info -->
        <div class="card border-0 shadow-sm bg-primary text-white">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3"><i class="fas fa-info-circle me-2"></i>Info Sistema</h5>
                <ul class="list-unstyled mb-0">
                    <li class="mb-2 d-flex justify-content-between">
                        <span><i class="fas fa-server me-2 opacity-75"></i>PHP Version</span>
                        <span class="fw-bold"><?php echo phpversion(); ?></span>
                    </li>
                    <li class="mb-2 d-flex justify-content-between">
                        <span><i class="fas fa-database me-2 opacity-75"></i>Database</span>
                        <span class="fw-bold">Connected</span>
                    </li>
                    <li class="d-flex justify-content-between">
                        <span><i class="fas fa-user-shield me-2 opacity-75"></i>Admin</span>
                        <span class="fw-bold"><?php echo htmlspecialchars($_SESSION['username'] ?? 'User'); ?></span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php include_once 'includes/footer.php'; ?>

