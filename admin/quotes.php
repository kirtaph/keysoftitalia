<?php
include_once 'includes/header.php';

$stmt = $pdo->query('SELECT q.*, d.name as device_name FROM quotes q JOIN devices d ON q.device_id = d.id ORDER BY q.created_at DESC');
$quotes = $stmt->fetchAll();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="section-title mb-0">Gestione Preventivi</h2>
    <button class="btn btn-outline-secondary btn-sm" onclick="location.reload()">
        <i class="fas fa-sync-alt"></i> Aggiorna
    </button>
</div>

<div class="card mb-4">
    <div class="card-header bg-white">
        <i class="fas fa-filter me-2 text-primary"></i> Filtri e Ricerca
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text bg-light"><i class="fas fa-search"></i></span>
                    <input type="text" id="searchInput" class="form-control" placeholder="Cerca per cliente, email, telefono...">
                </div>
            </div>
            <div class="col-md-6">
                <select id="deviceFilter" class="form-select">
                    <option value="">Tutti i dispositivi</option>
                    <?php
                    $devices_stmt = $pdo->query('SELECT DISTINCT name FROM devices ORDER BY name ASC');
                    $devices = $devices_stmt->fetchAll(PDO::FETCH_COLUMN);
                    foreach ($devices as $device): ?>
                        <option value="<?php echo htmlspecialchars($device, ENT_QUOTES, 'UTF-8'); ?>">
                            <?php echo htmlspecialchars($device, ENT_QUOTES, 'UTF-8'); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-hover align-middle">
        <thead>
            <tr>
                <th>Data</th>
                <th>Dispositivo</th>
                <th>Problemi</th>
                <th>Stima</th>
                <th>Cliente</th>
                <th>Contatti</th>
                <th class="text-center">Azioni</th>
            </tr>
        </thead>
        <tbody id="quotesTableBody">
            <?php foreach ($quotes as $q): ?>
                <tr id="quote-<?php echo $q['id']; ?>">
                    <td>
                        <?php echo date('d/m/Y', strtotime($q['created_at'])); ?><br>
                        <small class="text-muted"><?php echo date('H:i', strtotime($q['created_at'])); ?></small>
                    </td>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="me-2">
                                <i class="fas fa-mobile-alt fa-lg text-secondary"></i>
                            </div>
                            <div>
                                <div class="fw-bold"><?php echo htmlspecialchars($q['brand_text']); ?></div>
                                <div class="small text-muted"><?php echo htmlspecialchars($q['model_text']); ?></div>
                                <span class="badge bg-light text-dark border"><?php echo htmlspecialchars($q['device_name']); ?></span>
                            </div>
                        </div>
                    </td>
                    <td>
                        <?php
                            $problems = json_decode($q['problems_json'], true);
                            if ($problems) {
                                foreach ($problems as $problem) {
                                    echo '<span class="badge bg-info text-dark me-1 mb-1">' . htmlspecialchars($problem) . '</span>';
                                }
                            }
                        ?>
                        <?php if ($q['description']): ?>
                            <div class="small text-muted mt-1 text-truncate" style="max-width: 200px;" title="<?php echo htmlspecialchars($q['description']); ?>">
                                <i class="fas fa-comment-alt me-1"></i> <?php echo htmlspecialchars($q['description']); ?>
                            </div>
                        <?php endif; ?>
                    </td>
                    <td class="fw-bold text-success">
                        <?php
                            if ($q['est_min'] && $q['est_max']) {
                                echo '€ ' . number_format($q['est_min'], 0, ',', '.') . ' - ' . number_format($q['est_max'], 0, ',', '.');
                            } elseif ($q['est_min']) {
                                echo '€ ' . number_format($q['est_min'], 0, ',', '.');
                            } else {
                                echo '<span class="text-muted">N/D</span>';
                            }
                        ?>
                    </td>
                    <td>
                        <div class="fw-bold"><?php echo htmlspecialchars($q['first_name'] . ' ' . $q['last_name']); ?></div>
                        <?php if ($q['company']): ?>
                            <div class="small text-muted"><?php echo htmlspecialchars($q['company']); ?></div>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div class="d-flex flex-column gap-1">
                            <a href="mailto:<?php echo htmlspecialchars($q['email']); ?>" class="text-decoration-none small">
                                <i class="far fa-envelope me-1"></i> <?php echo htmlspecialchars($q['email']); ?>
                            </a>
                            <div class="d-flex align-items-center gap-2">
                                <span class="small"><i class="fas fa-phone me-1"></i> <?php echo htmlspecialchars($q['phone']); ?></span>
                                <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', $q['phone']); ?>" target="_blank" class="text-success" data-bs-toggle="tooltip" title="Chatta su WhatsApp">
                                    <i class="fab fa-whatsapp"></i>
                                </a>
                            </div>
                        </div>
                    </td>
                    <td class="text-center">
                        <div class="btn-group">
                            <button class="btn btn-sm btn-outline-primary view-btn" data-id="<?php echo $q['id']; ?>" data-bs-toggle="tooltip" title="Dettagli">
                                <i class="fas fa-eye"></i>
                            </button>
                            <a href="print_quote.php?id=<?php echo $q['id']; ?>" target="_blank" class="btn btn-sm btn-outline-secondary" data-bs-toggle="tooltip" title="Stampa">
                                <i class="fas fa-print"></i>
                            </a>
                            <button class="btn btn-sm btn-outline-danger delete-btn" data-id="<?php echo $q['id']; ?>" data-bs-toggle="tooltip" title="Elimina">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Modal Dettagli Preventivo -->
<div class="modal fade" id="quoteModal" tabindex="-1" aria-labelledby="quoteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="quoteModalLabel">Dettagli Preventivo</h5>
                <div class="ms-auto">
                    <a href="#" id="printBtn" target="_blank" class="btn btn-outline-secondary btn-sm me-2">
                        <i class="fas fa-print me-1"></i> Stampa
                    </a>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>
            <div class="modal-body">
                <!-- Quick Actions Toolbar -->
                <div class="d-flex gap-2 mb-4 p-3 bg-light rounded align-items-center justify-content-center">
                    <a href="#" id="waBtn" target="_blank" class="btn btn-success text-white">
                        <i class="fab fa-whatsapp me-2"></i> Invia Preventivo su WhatsApp
                    </a>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body">
                                <h6 class="card-title text-primary"><i class="fas fa-mobile-alt me-2"></i>Dispositivo</h6>
                                <p id="deviceDetails" class="card-text mb-0"></p>
                                <hr class="my-2">
                                <p id="problemDetails" class="card-text small text-muted"></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body">
                                <h6 class="card-title text-primary"><i class="fas fa-user me-2"></i>Cliente</h6>
                                <p id="customerDetails" class="card-text mb-0"></p>
                                <hr class="my-2">
                                <div class="alert alert-success mb-0 py-2">
                                    <small class="fw-bold text-uppercase">Stima Costi (Modificabile)</small><br>
                                    <div class="input-group input-group-sm mt-1">
                                        <span class="input-group-text">€</span>
                                        <input type="number" id="customMin" class="form-control fw-bold" placeholder="Min">
                                        <span class="input-group-text">-</span>
                                        <input type="number" id="customMax" class="form-control fw-bold" placeholder="Max">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Descrizione Aggiuntiva</label>
                    <div id="descriptionDetails" class="p-3 bg-light rounded border"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Chiudi</button>
            </div>
        </div>
    </div>
</div>

<?php include_once 'includes/footer.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const quoteModal = new bootstrap.Modal(document.getElementById('quoteModal'));
    
    // UI Elements
    const deviceDetails = document.getElementById('deviceDetails');
    const customerDetails = document.getElementById('customerDetails');
    const problemDetails = document.getElementById('problemDetails');
    // const priceDetails = document.getElementById('priceDetails'); // Removed
    const customMin = document.getElementById('customMin');
    const customMax = document.getElementById('customMax');
    const descriptionDetails = document.getElementById('descriptionDetails');
    
    // Action Buttons
    const printBtn = document.getElementById('printBtn');
    const waBtn = document.getElementById('waBtn');

    // Tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // View Button Click
    document.getElementById('quotesTableBody').addEventListener('click', function(e) {
        const viewBtn = e.target.closest('.view-btn');
        if (viewBtn) {
            const id = viewBtn.dataset.id;
            fetch(`ajax_actions/quote_actions.php?action=get&id=${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        const q = data.quote;
                        
                        // Update UI
                        deviceDetails.innerHTML = `<span class="fw-bold">${q.device_name}</span><br>${q.brand_text} ${q.model_text}`;
                        customerDetails.innerHTML = `<span class="fw-bold">${q.first_name} ${q.last_name}</span><br>${q.phone}<br>${q.email}`;
                        
                        const problems = JSON.parse(q.problems_json || '[]');
                        problemDetails.innerHTML = problems.map(p => `<span class="badge bg-secondary me-1">${p}</span>`).join('');

                        // Populate inputs
                        customMin.value = q.est_min ? parseFloat(q.est_min) : '';
                        customMax.value = q.est_max ? parseFloat(q.est_max) : '';

                        descriptionDetails.textContent = q.description || 'Nessuna descrizione aggiuntiva.';

                        // Function to update links
                        const updateLinks = () => {
                            const min = customMin.value;
                            const max = customMax.value;
                            let priceText = 'N/D';
                            
                            if (min && max) {
                                priceText = `€ ${parseFloat(min).toLocaleString()} - ${parseFloat(max).toLocaleString()}`;
                            } else if (min) {
                                priceText = `€ ${parseFloat(min).toLocaleString()}`;
                            }

                            // Update Print Link
                            printBtn.href = `print_quote.php?id=${q.id}&min=${min}&max=${max}`;
                            
                            // Update WhatsApp Link
                            const phone = q.phone.replace(/[^0-9]/g, '');
                            const waText = encodeURIComponent(`Ciao ${q.first_name}, ecco il preventivo richiesto per il tuo ${q.brand_text} ${q.model_text}. La stima è di ${priceText}.`);
                            waBtn.href = `https://wa.me/${phone}?text=${waText}`;
                        };

                        // Initial update
                        updateLinks();

                        // Listen for changes
                        customMin.oninput = updateLinks;
                        customMax.oninput = updateLinks;

                        quoteModal.show();
                    } else {
                        alert('Errore nel recupero dei dati.');
                    }
                });
        }
    });

    // Delete Button Click
    document.getElementById('quotesTableBody').addEventListener('click', function(e) {
        const deleteBtn = e.target.closest('.delete-btn');
        if (deleteBtn) {
            const id = deleteBtn.dataset.id;
            if (confirm('Sei sicuro di voler eliminare questo preventivo?')) {
                const formData = new FormData();
                formData.append('action', 'delete');
                formData.append('id', id);

                fetch('ajax_actions/quote_actions.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        document.getElementById(`quote-${id}`).remove();
                    } else {
                        alert(data.message || 'Errore durante l\'eliminazione.');
                    }
                });
            }
        }
    });

    // Search & Filter
    const searchInput = document.getElementById('searchInput');
    const deviceFilter = document.getElementById('deviceFilter');
    const tableBody = document.querySelector('.table tbody');
    const tableRows = tableBody.getElementsByTagName('tr');

    function filterTable() {
        const searchTerm = searchInput.value.toLowerCase();
        const deviceTerm = deviceFilter.value.toLowerCase();

        for (let i = 0; i < tableRows.length; i++) {
            const deviceCell = tableRows[i].getElementsByTagName('td')[1];
            const clientCell = tableRows[i].getElementsByTagName('td')[4];
            const contactCell = tableRows[i].getElementsByTagName('td')[5];

            if (deviceCell && clientCell && contactCell) {
                const deviceText = deviceCell.textContent.toLowerCase();
                const clientText = clientCell.textContent.toLowerCase();
                const contactText = contactCell.textContent.toLowerCase();

                const searchMatch = clientText.includes(searchTerm) || contactText.includes(searchTerm);
                const deviceMatch = deviceTerm === '' || deviceText.includes(deviceTerm);

                if (searchMatch && deviceMatch) {
                    tableRows[i].style.display = '';
                } else {
                    tableRows[i].style.display = 'none';
                }
            }
        }
    }

    searchInput.addEventListener('keyup', filterTable);
    deviceFilter.addEventListener('change', filterTable);
});
</script>
