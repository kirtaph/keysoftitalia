<?php
include_once 'includes/header.php';

// --- KPI Data Fetching ---

// 1. Total Bookings (Today)
$stmt = $pdo->prepare("SELECT COUNT(*) FROM repair_bookings WHERE DATE(created_at) = CURDATE()");
$stmt->execute();
$bookingsToday = $stmt->fetchColumn();

// 2. Pending Bookings
$stmt = $pdo->query("SELECT COUNT(*) FROM repair_bookings WHERE status = 'pending'");
$pendingBookings = $stmt->fetchColumn();

// 3. Pending Used Quotes
$stmt = $pdo->query("SELECT COUNT(*) FROM used_device_quotes WHERE status = 'pending'");
$pendingQuotes = $stmt->fetchColumn();

// 4. Total Products
$stmt = $pdo->query("SELECT COUNT(*) FROM products WHERE is_available = 1");
$activeProducts = $stmt->fetchColumn();

// --- Chart Data Fetching ---

// Bookings last 7 days
$dates = [];
$counts = [];
for ($i = 6; $i >= 0; $i--) {
    $date = date('Y-m-d', strtotime("-$i days"));
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM repair_bookings WHERE DATE(created_at) = ?");
    $stmt->execute([$date]);
    $dates[] = date('d/m', strtotime($date));
    $counts[] = $stmt->fetchColumn();
}

// Device Types Breakdown (Bookings)
$stmt = $pdo->query("SELECT device_type, COUNT(*) as count FROM repair_bookings GROUP BY device_type");
$deviceTypes = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
$deviceLabels = array_keys($deviceTypes);
$deviceCounts = array_values($deviceTypes);

?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="section-title mb-1">Dashboard</h2>
        <p class="text-muted mb-0">Benvenuto, <?php echo htmlspecialchars($_SESSION['username'] ?? 'Admin'); ?>!</p>
    </div>
    <div>
        <span class="text-muted small"><?php echo date('l, d F Y'); ?></span>
    </div>
</div>

<!-- KPI Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card h-100 border-start border-4 border-primary">
            <div class="card-body">
                <div class="kpi-card">
                    <div class="kpi-info">
                        <h6>Prenotazioni Oggi</h6>
                        <h3 class="text-primary"><?php echo $bookingsToday; ?></h3>
                    </div>
                    <div class="kpi-icon bg-primary bg-opacity-10 text-primary">
                        <i class="fas fa-calendar-day"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card h-100 border-start border-4 border-warning">
            <div class="card-body">
                <div class="kpi-card">
                    <div class="kpi-info">
                        <h6>Prenotazioni in Attesa</h6>
                        <h3 class="text-warning"><?php echo $pendingBookings; ?></h3>
                    </div>
                    <div class="kpi-icon bg-warning bg-opacity-10 text-warning">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card h-100 border-start border-4 border-info">
            <div class="card-body">
                <div class="kpi-card">
                    <div class="kpi-info">
                        <h6>Valutazioni in Attesa</h6>
                        <h3 class="text-info"><?php echo $pendingQuotes; ?></h3>
                    </div>
                    <div class="kpi-icon bg-info bg-opacity-10 text-info">
                        <i class="fas fa-recycle"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card h-100 border-start border-4 border-success">
            <div class="card-body">
                <div class="kpi-card">
                    <div class="kpi-info">
                        <h6>Prodotti Attivi</h6>
                        <h3 class="text-success"><?php echo $activeProducts; ?></h3>
                    </div>
                    <div class="kpi-icon bg-success bg-opacity-10 text-success">
                        <i class="fas fa-box"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row mb-4">
    <div class="col-lg-8 mb-4">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Andamento Prenotazioni (7 Giorni)</h6>
            </div>
            <div class="card-body">
                <canvas id="bookingsChart"></canvas>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4 mb-4">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Dispositivi Riparati</h6>
            </div>
            <div class="card-body">
                <canvas id="devicesChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">Ultime Prenotazioni</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>ID</th>
                                <th>Cliente</th>
                                <th>Dispositivo</th>
                                <th>Data</th>
                                <th>Stato</th>
                                <th>Azioni</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $stmt = $pdo->query("SELECT * FROM repair_bookings ORDER BY created_at DESC LIMIT 5");
                            $recentBookings = $stmt->fetchAll();
                            
                            if (count($recentBookings) > 0):
                                foreach ($recentBookings as $b):
                            ?>
                            <tr>
                                <td>#<?php echo $b['id']; ?></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-initial rounded-circle bg-light text-primary me-2" style="width:32px;height:32px;display:flex;align-items:center;justify-content:center;font-weight:bold;">
                                            <?php echo strtoupper(substr($b['customer_first_name'], 0, 1)); ?>
                                        </div>
                                        <div>
                                            <div class="fw-bold"><?php echo htmlspecialchars($b['customer_first_name'] . ' ' . $b['customer_last_name']); ?></div>
                                            <div class="small text-muted"><?php echo htmlspecialchars($b['customer_email']); ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td><?php echo htmlspecialchars($b['device_type'] . ' ' . $b['brand_name']); ?></td>
                                <td><?php echo date('d/m/Y', strtotime($b['created_at'])); ?></td>
                                <td>
                                    <?php
                                    $statusClass = match($b['status']) {
                                        'confirmed' => 'bg-success',
                                        'cancelled' => 'bg-danger',
                                        'completed' => 'bg-secondary',
                                        default => 'bg-warning text-dark'
                                    };
                                    ?>
                                    <span class="badge <?php echo $statusClass; ?> rounded-pill"><?php echo htmlspecialchars($b['status']); ?></span>
                                </td>
                                <td>
                                    <a href="bookings.php" class="btn btn-sm btn-outline-primary">Dettagli</a>
                                </td>
                            </tr>
                            <?php endforeach; else: ?>
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">Nessuna prenotazione recente.</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer text-center bg-white">
                <a href="bookings.php" class="text-primary text-decoration-none fw-bold">Visualizza Tutte <i class="fas fa-arrow-right ms-1"></i></a>
            </div>
        </div>
    </div>
</div>

<?php include_once 'includes/footer.php'; ?>

<script>
// Bookings Chart
const ctxBookings = document.getElementById('bookingsChart').getContext('2d');
new Chart(ctxBookings, {
    type: 'line',
    data: {
        labels: <?php echo json_encode($dates); ?>,
        datasets: [{
            label: 'Prenotazioni',
            data: <?php echo json_encode($counts); ?>,
            borderColor: '#0d6efd',
            backgroundColor: 'rgba(13, 110, 253, 0.1)',
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false }
        },
        scales: {
            y: { beginAtZero: true, ticks: { stepSize: 1 } }
        }
    }
});

// Devices Chart
const ctxDevices = document.getElementById('devicesChart').getContext('2d');
new Chart(ctxDevices, {
    type: 'doughnut',
    data: {
        labels: <?php echo json_encode($deviceLabels); ?>,
        datasets: [{
            data: <?php echo json_encode($deviceCounts); ?>,
            backgroundColor: ['#0d6efd', '#198754', '#ffc107', '#0dcaf0', '#dc3545'],
            borderWidth: 0
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'bottom' }
        },
        cutout: '70%'
    }
});
</script>
