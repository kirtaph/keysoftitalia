<?php
include_once 'includes/header.php';

// Default values (fail-safe)
$bookingsToday = 0; $pendingQuotes = 0; $pendingRepairQuotes = 0; $pendingServiceRequests = 0;
$activeFlyer = null; $isOpen = false;
$chartLabels = []; $chartTotal = []; $chartCompleted = []; $chartCancelled = [];
$statusLabels = []; $statusCounts = []; $statusColors = [];
$recentActivity = []; $pendingRequests = [];

// --- Dashboard cache (2 min TTL) ---
$cacheDir = __DIR__ . '/../cache';
if (!is_dir($cacheDir)) { mkdir($cacheDir, 0755, true); }
$cacheFile = $cacheDir . '/dashboard_cache.php';
$cacheKey = date('Y-m-d-H') . floor((int)date('i') / 2);

$cacheHit = false;
if (file_exists($cacheFile)) {
    $data = @unserialize(file_get_contents($cacheFile));
    if (is_array($data) && ($data['_key'] ?? null) === $cacheKey) {
        $bookingsToday         = $data['bookingsToday'];
        $pendingQuotes         = $data['pendingQuotes'];
        $pendingRepairQuotes   = $data['pendingRepairQuotes'];
        $pendingServiceRequests= $data['pendingServiceRequests'];
        $activeFlyer           = $data['activeFlyer'];
        $chartLabels           = $data['chartLabels'];
        $chartTotal            = $data['chartTotal'];
        $chartCompleted        = $data['chartCompleted'];
        $chartCancelled        = $data['chartCancelled'];
        $statusLabels          = $data['statusLabels'];
        $statusCounts          = $data['statusCounts'];
        $statusColors          = $data['statusColors'];
        $recentActivity        = $data['recentActivity'];
        $pendingRequests       = $data['pendingRequests'];
        $cacheHit              = true;
    }
}

if (!$cacheHit) {
    try {
        // 1. Prenotazioni Oggi
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM repair_bookings WHERE DATE(created_at) = CURDATE()");
        $stmt->execute();
        $bookingsToday = (int)$stmt->fetchColumn();

        // 2. Da Valutare (Usato)
        $stmt = $pdo->query("SELECT COUNT(*) FROM used_device_quotes WHERE status = 'pending'");
        $pendingQuotes = (int)$stmt->fetchColumn();

        // 3. Preventivi Riparazioni
        $stmt = $pdo->query("SELECT COUNT(*) FROM quotes WHERE status = 'pending'");
        $pendingRepairQuotes = (int)$stmt->fetchColumn();

        // 4. Richieste Servizi
        $stmt = $pdo->query("SELECT IFNULL((SELECT COUNT(*) FROM telephony_requests WHERE status = 'In attesa'),0) + IFNULL((SELECT COUNT(*) FROM utility_requests WHERE status = 'In attesa'),0) + IFNULL((SELECT COUNT(*) FROM liberty_demo_requests WHERE status = 0),0)");
        $pendingServiceRequests = (int)$stmt->fetchColumn();

        // 5. Volantino Attivo
        $stmt = $pdo->query("SELECT title FROM flyers WHERE status = 1 AND start_date <= CURDATE() AND end_date >= CURDATE() LIMIT 1");
        $activeFlyer = $stmt->fetchColumn();

        // Chart: Bookings trend last 30 days
        $stmt = $pdo->query("SELECT DATE(created_at) as day, COUNT(*) as total, SUM(CASE WHEN status='completed' THEN 1 ELSE 0 END) as completed, SUM(CASE WHEN status='cancelled' THEN 1 ELSE 0 END) as cancelled FROM repair_bookings WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY) GROUP BY DATE(created_at) ORDER BY day ASC");
        $chartLabels = []; $chartTotal = []; $chartCompleted = []; $chartCancelled = [];
        foreach ($stmt->fetchAll() as $row) {
            $chartLabels[] = date('d/m', strtotime($row['day']));
            $chartTotal[] = (int)$row['total'];
            $chartCompleted[] = (int)$row['completed'];
            $chartCancelled[] = (int)$row['cancelled'];
        }

        // Chart: Status breakdown
        $statusMap = ['pending'=>['label'=>'In Attesa','color'=>'#eab308'],'confirmed'=>['label'=>'Confermato','color'=>'#3b82f6'],'completed'=>['label'=>'Completato','color'=>'#22c55e'],'cancelled'=>['label'=>'Annullato','color'=>'#ef4444']];
        $stmt = $pdo->query("SELECT status, COUNT(*) as count FROM repair_bookings GROUP BY status");
        $statusLabels = []; $statusCounts = []; $statusColors = [];
        foreach ($stmt->fetchAll() as $row) {
            $statusLabels[] = $statusMap[$row['status']]['label'] ?? $row['status'];
            $statusCounts[] = (int)$row['count'];
            $statusColors[] = $statusMap[$row['status']]['color'] ?? '#64748b';
        }

        // Recent Activity (bookings + used quotes)
        $stmt = $pdo->query("(SELECT id, customer_first_name, customer_last_name, device_type, brand_name, created_at, status, 'booking' as source FROM repair_bookings) UNION ALL (SELECT id, customer_first_name, customer_last_name, device_type, brand_name, created_at, status, 'used_quote' as source FROM used_device_quotes) ORDER BY created_at DESC LIMIT 10");
        $recentActivity = $stmt->fetchAll();

        // Pending service requests
        $stmt = $pdo->query("(SELECT id, CONCAT('Telefonia: ',operator_name) as detail, created_at, status FROM telephony_requests WHERE status = 'In attesa') UNION ALL (SELECT id, CONCAT('Utility: ',operator_name) as detail, created_at, status FROM utility_requests WHERE status = 'In attesa') UNION ALL (SELECT id, CONCAT('Liberty: ',name) as detail, created_at, 'Nuovo' as status FROM liberty_demo_requests WHERE status = 0) ORDER BY created_at DESC LIMIT 5");
        $pendingRequests = $stmt->fetchAll();

        // Save to cache
        file_put_contents($cacheFile, serialize([
            '_key' => $cacheKey,
            'bookingsToday' => $bookingsToday,
            'pendingQuotes' => $pendingQuotes,
            'pendingRepairQuotes' => $pendingRepairQuotes,
            'pendingServiceRequests' => $pendingServiceRequests,
            'activeFlyer' => $activeFlyer,
            'chartLabels' => $chartLabels,
            'chartTotal' => $chartTotal,
            'chartCompleted' => $chartCompleted,
            'chartCancelled' => $chartCancelled,
            'statusLabels' => $statusLabels,
            'statusCounts' => $statusCounts,
            'statusColors' => $statusColors,
            'recentActivity' => $recentActivity,
            'pendingRequests' => $pendingRequests,
        ]), LOCK_EX);
    } catch (Exception $e) {
        error_log('Dashboard query error: ' . $e->getMessage());
    }
}

// Store hours check (always fresh, not cached)
try {
    $currentDow = date('N');
    $currentTime = date('H:i:s');
    $stmt = $pdo->prepare("SELECT * FROM ks_store_hours_weekly WHERE dow = ? AND active = 1");
    $stmt->execute([$currentDow]);
    foreach ($stmt->fetchAll() as $h) {
        if ($currentTime >= $h['open_time'] && $currentTime <= $h['close_time']) { $isOpen = true; break; }
    }
} catch (Exception $e) {
    // Keep default $isOpen = false
}

$storeStatus = $isOpen ? 'APERTO' : 'CHIUSO';
$storeColor = $isOpen ? 'text-success' : 'text-danger';
$storeIcon = $isOpen ? 'fa-door-open' : 'fa-door-closed';
$flyerStatus = $activeFlyer ? 'Attivo' : 'Nessuno';
$flyerColor = $activeFlyer ? 'text-success' : 'text-muted';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="section-title mb-1"><i class="fas fa-tachometer-alt me-2" style="color:#ac3b12;"></i>Dashboard</h2>
        <p class="text-muted mb-0">Panoramica delle attività di oggi.</p>
    </div>
    <div class="text-end">
        <h5 class="mb-0 fw-bold text-dark"><?php echo date('H:i'); ?></h5>
        <span class="text-muted small"><?php echo date('l, d F Y'); ?></span>
    </div>
</div>

<div class="workspace-welcome">
    <div><strong>Le richieste del sito, tutte nello stesso posto</strong><p>Controlla gli arrivi e attiva gli avvisi desktop dal centro notifiche.</p></div>
    <a href="notifications.php" class="btn btn-primary">Apri centro notifiche <i class="fas fa-arrow-right ms-2" aria-hidden="true"></i></a>
</div>
<section class="workspace-summary" aria-label="Riepilogo operativo">
    <a href="bookings.php"><small>Prenotazioni ricevute oggi</small><strong><?php echo $bookingsToday; ?></strong></a>
    <a href="used_quotes.php?status=pending"><small>Usato da valutare</small><strong><?php echo $pendingQuotes; ?></strong></a>
    <a href="quotes.php?status=pending"><small>Preventivi da gestire</small><strong><?php echo $pendingRepairQuotes; ?></strong></a>
    <a href="notifications.php?unread=1"><small>Servizi in attesa</small><strong><?php echo $pendingServiceRequests; ?></strong></a>
    <div><small>Negozio ora</small><strong class="<?php echo $storeColor; ?>"><?php echo $isOpen ? 'Aperto' : 'Chiuso'; ?></strong></div>
    <a href="flyers.php"><small>Volantino attivo</small><span><?php echo htmlspecialchars($activeFlyer ?: 'Nessuno'); ?></span></a>
</section>

<!-- Charts Row -->
<div class="row mb-4">
    <!-- Bookings Trend Chart -->
    <div class="col-lg-8 mb-4 mb-lg-0">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold" style="color:#ac3b12;"><i class="fas fa-chart-bar me-2"></i>Prenotazioni – Ultimi 30 Giorni</h6>
            </div>
            <div class="card-body">
                <div class="chart-wrapper"><canvas id="bookingsChart"></canvas></div>
            </div>
        </div>
    </div>
    <!-- Status Breakdown -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 py-3">
                <h6 class="mb-0 fw-bold" style="color:#ac3b12;"><i class="fas fa-chart-pie me-2"></i>Stato Prenotazioni</h6>
            </div>
            <div class="card-body d-flex align-items-center justify-content-center">
                <div class="chart-wrapper"><canvas id="statusChart"></canvas></div>
            </div>
        </div>
    </div>
</div>

<!-- Activity & Pending Requests Row -->
<div class="row">
    <!-- Recent Activity Table -->
    <div class="col-lg-8 mb-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold" style="color:#ac3b12;"><i class="fas fa-history me-2"></i>Attività Recenti</h6>
                <a href="bookings.php" class="btn btn-sm fw-bold" style="background:rgba(255,107,53,0.08);color:#ac3b12;border:none;border-radius:8px;">Vedi Tutte</a>
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
                            <?php if (count($recentActivity) > 0): foreach ($recentActivity as $r):
                                $isBooking = $r['source'] === 'booking';
                                $icon = $isBooking ? 'fa-tools' : 'fa-recycle';
                                $typeLabel = $isBooking ? 'Riparazione' : 'Valutazione Usato';
                                $typeColor = $isBooking ? '#ff6b35' : '#eab308';
                                $detail = $isBooking ? ucfirst($r['device_type']).' '.$r['brand_name'] : ucfirst($r['device_type']).' '.$r['brand_name'];
                                if ($isBooking) {
                                    $sLabel = match($r['status']) { 'confirmed'=>'Confermato','cancelled'=>'Annullato','completed'=>'Completato','pending'=>'In Attesa', default=>$r['status'] };
                                    $sClass = match($r['status']) { 'confirmed'=>'background:rgba(59,130,246,0.10);color:#3b82f6','cancelled'=>'background:rgba(239,68,68,0.10);color:#ef4444','completed'=>'background:rgba(34,197,94,0.10);color:#22c55e', default=>'background:rgba(234,179,8,0.10);color:#eab308' };
                                } else {
                                    $sLabel = match($r['status']) { 'reviewed'=>'Revisionato','contacted'=>'Contattato', default=>'In Attesa' };
                                    $sClass = match($r['status']) { 'reviewed'=>'background:rgba(59,130,246,0.10);color:#3b82f6','contacted'=>'background:rgba(34,197,94,0.10);color:#22c55e', default=>'background:rgba(234,179,8,0.10);color:#eab308' };
                                }
                            ?>
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <div class="icon-circle d-flex align-items-center justify-content-center me-2" style="width:32px;height:32px;background:<?php echo $typeColor; ?>10;color:<?php echo $typeColor; ?>;border-radius:50%;">
                                            <i class="fas <?php echo $icon; ?> small"></i>
                                        </div>
                                        <span class="fw-bold text-dark"><?php echo $typeLabel; ?></span>
                                    </div>
                                </td>
                                <td><?php echo htmlspecialchars($r['customer_first_name'].' '.$r['customer_last_name']); ?></td>
                                <td class="text-muted small"><?php echo htmlspecialchars($detail); ?></td>
                                <td class="text-muted small"><?php echo date('d/m H:i', strtotime($r['created_at'])); ?></td>
                                <td class="text-end pe-4"><span class="badge rounded-pill" style="<?php echo $sClass; ?>font-weight:500;"><?php echo $sLabel; ?></span></td>
                            </tr>
                            <?php endforeach; else: ?>
                            <tr><td colspan="5" class="text-center py-5 text-muted"><i class="fas fa-inbox fa-2x mb-3 d-block opacity-50"></i>Nessuna attività recente.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column -->
    <div class="col-lg-4 mb-4">
        <!-- Pending Service Requests -->
        <?php if (count($pendingRequests) > 0): ?>
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-0 py-3">
                <h6 class="mb-0 fw-bold" style="color:#eab308;"><i class="fas fa-clock me-2"></i>Richieste in Attesa</h6>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    <?php foreach ($pendingRequests as $pr): ?>
                    <div class="list-group-item d-flex justify-content-between align-items-center py-2 px-3 border-0 border-bottom" style="border-color:var(--ks-admin-border)!important;">
                        <div class="small"><?php echo htmlspecialchars($pr['detail']); ?></div>
                        <span class="badge rounded-pill" style="background:rgba(234,179,8,0.10);color:#eab308;font-weight:500;"><?php echo htmlspecialchars($pr['status']); ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Quick Actions -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-0 py-3">
                <h6 class="mb-0 fw-bold" style="color:#ac3b12;"><i class="fas fa-bolt me-2"></i>Azioni Rapide</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="bookings.php" class="btn text-start p-3 d-flex align-items-center dashboard-shortcut">
                        <div style="background:#ff6b35;color:#fff;border-radius:10px;width:40px;height:40px;display:flex;align-items:center;justify-content:center;margin-right:12px;flex-shrink:0;"><i class="fas fa-calendar-plus"></i></div>
                        <div><div class="fw-bold" style="color:var(--ks-admin-text);">Gestisci Prenotazioni</div><div class="small text-muted">Vedi calendario e appuntamenti</div></div>
                    </a>
                    <a href="used_quotes.php" class="btn text-start p-3 d-flex align-items-center dashboard-shortcut">
                        <div style="background:#eab308;color:#fff;border-radius:10px;width:40px;height:40px;display:flex;align-items:center;justify-content:center;margin-right:12px;flex-shrink:0;"><i class="fas fa-recycle"></i></div>
                        <div><div class="fw-bold" style="color:var(--ks-admin-text);">Valutazioni Usato</div><div class="small text-muted">Gestisci richieste di permuta</div></div>
                    </a>
                    <a href="products.php" class="btn text-start p-3 d-flex align-items-center dashboard-shortcut">
                        <div style="background:#22c55e;color:#fff;border-radius:10px;width:40px;height:40px;display:flex;align-items:center;justify-content:center;margin-right:12px;flex-shrink:0;"><i class="fas fa-box-open"></i></div>
                        <div><div class="fw-bold" style="color:var(--ks-admin-text);">Gestisci Prodotti</div><div class="small text-muted">Aggiungi o modifica prodotti</div></div>
                    </a>
                    <a href="weekly_hours.php" class="btn text-start p-3 d-flex align-items-center dashboard-shortcut">
                        <div style="background:#64748b;color:#fff;border-radius:10px;width:40px;height:40px;display:flex;align-items:center;justify-content:center;margin-right:12px;flex-shrink:0;"><i class="fas fa-clock"></i></div>
                        <div><div class="fw-bold" style="color:var(--ks-admin-text);">Orari & Chiusure</div><div class="small text-muted">Modifica orari o aggiungi ferie</div></div>
                    </a>
                </div>
            </div>
        </div>

        <!-- System Status -->
        <div class="card border-0 shadow-sm" style="background:linear-gradient(135deg,#0f172a 0%,#1a2639 100%);">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3 text-white"><i class="fas fa-info-circle me-2" style="color:#ac3b12;"></i>Info Sistema</h5>
                <ul class="list-unstyled mb-0 text-white">
                    <li class="mb-2 d-flex justify-content-between"><span><i class="fas fa-server me-2 opacity-50"></i>PHP Version</span><span class="fw-bold"><?php echo phpversion(); ?></span></li>
                    <li class="mb-2 d-flex justify-content-between"><span><i class="fas fa-database me-2 opacity-50"></i>Database</span><span class="fw-bold">Connected</span></li>
                    <li class="mb-2 d-flex justify-content-between"><span><i class="fas fa-code-branch me-2 opacity-50"></i>DB Version</span><span class="fw-bold" id="dbVersion">...</span></li>
                    <li class="d-flex justify-content-between"><span><i class="fas fa-user-shield me-2 opacity-50"></i>Admin</span><span class="fw-bold"><?php echo htmlspecialchars($_SESSION['username'] ?? 'User'); ?></span></li>
                </ul>
                <div id="migrationAlert" class="mt-3 d-none">
                    <div class="alert alert-warning text-dark mb-0 p-2 small">
                        <div class="fw-bold mb-1"><i class="fas fa-exclamation-triangle me-1"></i> Aggiornamento DB</div>
                        <div class="mb-2">Ci sono modifiche pendenti.</div>
                        <button id="updateDbBtn" class="btn btn-sm btn-light w-100 fw-bold text-warning">Aggiorna Ora</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once 'includes/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // --- Migration Check ---
    fetch('ajax_actions/migrate_action.php?action=check')
        .then(r => r.json()).then(d => {
            if (d.status === 'success') {
                let v = d.last_version; if (v.length > 15) v = v.substring(0,12)+'...';
                document.getElementById('dbVersion').textContent = v;
                if (d.pending_count > 0) document.getElementById('migrationAlert').classList.remove('d-none');
            }
        });

    document.getElementById('updateDbBtn')?.addEventListener('click', function() {
        if (!confirm('Sei sicuro di voler aggiornare il database?')) return;
        const ot = this.textContent; this.textContent = 'Aggiornamento...'; this.disabled = true;
        fetch('ajax_actions/migrate_action.php?action=execute')
            .then(r => r.json()).then(d => { alert(d.message); if (d.status === 'success') location.reload(); else { this.textContent = ot; this.disabled = false; } })
            .catch(() => { alert('Errore di comunicazione.'); this.textContent = ot; this.disabled = false; });
    });

    // --- Chart: Bookings Trend ---
    const ctx = document.getElementById('bookingsChart')?.getContext('2d');
    if (ctx) {
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($chartLabels); ?>,
                datasets: [{
                    label: 'Totale',
                    data: <?php echo json_encode($chartTotal); ?>,
                    backgroundColor: 'rgba(255,107,53,0.7)',
                    borderColor: '#ff6b35',
                    borderWidth: 1,
                    borderRadius: 4,
                    order: 2
                }, {
                    label: 'Completati',
                    data: <?php echo json_encode($chartCompleted); ?>,
                    backgroundColor: 'rgba(34,197,94,0.7)',
                    borderColor: '#22c55e',
                    borderWidth: 1,
                    borderRadius: 4,
                    order: 1
                }, {
                    label: 'Annullati',
                    data: <?php echo json_encode($chartCancelled); ?>,
                    backgroundColor: 'rgba(239,68,68,0.5)',
                    borderColor: '#ef4444',
                    borderWidth: 1,
                    borderRadius: 4,
                    order: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: true, position: 'top', labels: { boxWidth: 12, padding: 12, font: { size: 11 } } }
                },
                scales: {
                    x: { grid: { display: false }, ticks: { font: { size: 9 }, maxTicksLimit: 15 } },
                    y: { beginAtZero: true, ticks: { stepSize: 1, font: { size: 10 } }, grid: { color: 'rgba(0,0,0,0.04)' } }
                }
            }
        });
    }

    // --- Chart: Status Breakdown ---
    const ctx2 = document.getElementById('statusChart')?.getContext('2d');
    if (ctx2) {
        new Chart(ctx2, {
            type: 'doughnut',
            data: {
                labels: <?php echo json_encode($statusLabels); ?>,
                datasets: [{
                    data: <?php echo json_encode($statusCounts); ?>,
                    backgroundColor: <?php echo json_encode($statusColors); ?>,
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: { position: 'bottom', labels: { boxWidth: 10, padding: 10, font: { size: 10 } } }
                }
            }
        });
    }
});
</script>
