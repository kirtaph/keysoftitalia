<?php
include_once 'includes/header.php';

// Fetch demo requests directly from database
$demoRequests = [];
try {
    $stmt = $pdo->query("SELECT * FROM liberty_demo_requests ORDER BY created_at DESC");
    $demoRequests = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // ignore
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="section-title mb-0"><i class="fas fa-file-download me-2 text-primary"></i>Richieste Demo LibertyCommerce</h2>
        <p class="text-muted mb-0">Visualizza e gestisci l'elenco dei contatti che hanno scaricato la versione demo dal sito.</p>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Data Richiesta</th>
                        <th>Nominativo</th>
                        <th>Azienda / Attività</th>
                        <th>Email</th>
                        <th>Telefono</th>
                        <th>Città</th>
                        <th class="text-center">Stato</th>
                        <th class="text-center pe-4" style="width: 150px;">Azioni</th>
                    </tr>
                </thead>
                <tbody id="demoRequestTableBody">
                    <?php if (empty($demoRequests)): ?>
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="fas fa-inbox fa-3x mb-3 d-block text-muted" style="opacity: 0.5;"></i>
                                Nessuna richiesta di demo registrata.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($demoRequests as $req): ?>
                            <tr id="req-row-<?php echo $req['id']; ?>">
                                <td class="ps-4 text-muted small">
                                    <?php echo date('d/m/Y H:i', strtotime($req['created_at'])); ?>
                                </td>
                                <td>
                                    <span class="fw-bold text-dark"><?php echo htmlspecialchars($req['name']); ?></span>
                                </td>
                                <td class="text-muted">
                                    <?php echo htmlspecialchars($req['company'] ?: '-'); ?>
                                </td>
                                <td>
                                    <a href="mailto:<?php echo htmlspecialchars($req['email']); ?>" class="text-decoration-none">
                                        <?php echo htmlspecialchars($req['email']); ?>
                                    </a>
                                </td>
                                <td>
                                    <a href="tel:<?php echo htmlspecialchars($req['phone']); ?>" class="text-decoration-none text-dark">
                                        <?php echo htmlspecialchars($req['phone']); ?>
                                    </a>
                                </td>
                                <td class="text-muted">
                                    <?php echo htmlspecialchars($req['city'] ?: '-'); ?>
                                </td>
                                <td class="text-center" id="status-badge-<?php echo $req['id']; ?>">
                                    <?php if ($req['status'] == 1): ?>
                                        <span class="badge bg-success"><i class="fas fa-check me-1"></i>Gestito</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning text-dark"><i class="fas fa-clock me-1"></i>Nuovo</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center pe-4">
                                    <div class="btn-group btn-group-sm">
                                        <?php if ($req['status'] == 0): ?>
                                            <button class="btn btn-outline-success toggle-status-btn" data-id="<?php echo $req['id']; ?>" title="Segna come gestito">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        <?php else: ?>
                                            <button class="btn btn-outline-secondary toggle-status-btn" data-id="<?php echo $req['id']; ?>" title="Segna come da gestire">
                                                <i class="fas fa-undo"></i>
                                            </button>
                                        <?php endif; ?>
                                        <button class="btn btn-outline-danger delete-req-btn" data-id="<?php echo $req['id']; ?>" title="Elimina contatto">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include_once 'includes/footer.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle Status
    document.querySelectorAll('.toggle-status-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            const fd = new FormData();
            fd.append('action', 'toggle_status');
            fd.append('id', id);

            fetch('ajax_actions/liberty_demo_actions.php', {
                method: 'POST',
                body: fd
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    const badgeCell = document.getElementById(`status-badge-${id}`);
                    const row = document.getElementById(`req-row-${id}`);
                    const actionBtn = row.querySelector('.toggle-status-btn');
                    
                    if (data.new_status == 1) {
                        badgeCell.innerHTML = '<span class="badge bg-success"><i class="fas fa-check me-1"></i>Gestito</span>';
                        actionBtn.className = 'btn btn-outline-secondary toggle-status-btn';
                        actionBtn.innerHTML = '<i class="fas fa-undo"></i>';
                        actionBtn.title = 'Segna come da gestire';
                    } else {
                        badgeCell.innerHTML = '<span class="badge bg-warning text-dark"><i class="fas fa-clock me-1"></i>Nuovo</span>';
                        actionBtn.className = 'btn btn-outline-success toggle-status-btn';
                        actionBtn.innerHTML = '<i class="fas fa-check"></i>';
                        actionBtn.title = 'Segna come gestito';
                    }
                } else {
                    alert(data.message || 'Si è verificato un errore.');
                }
            })
            .catch(() => alert('Errore di connessione.'));
        });
    });

    // Delete request
    document.querySelectorAll('.delete-req-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            if (!confirm('Sei sicuro di voler eliminare questa richiesta? L\'azione è irreversibile.')) {
                return;
            }
            
            const id = this.dataset.id;
            const fd = new FormData();
            fd.append('action', 'delete');
            fd.append('id', id);

            fetch('ajax_actions/liberty_demo_actions.php', {
                method: 'POST',
                body: fd
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    const row = document.getElementById(`req-row-${id}`);
                    row.style.opacity = '0';
                    setTimeout(() => {
                        row.remove();
                        // If no rows left, reload to show empty state
                        if (document.querySelectorAll('#demoRequestTableBody tr').length === 0) {
                            location.reload();
                        }
                    }, 300);
                } else {
                    alert(data.message || 'Si è verificato un errore.');
                }
            })
            .catch(() => alert('Errore di connessione.'));
        });
    });
});
</script>
