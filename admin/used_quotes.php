<?php
include_once 'includes/header.php';

$stmt = $pdo->query('SELECT * FROM used_device_quotes ORDER BY created_at DESC');
$quotes = $stmt->fetchAll();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="section-title mb-0">Valutazioni Usato</h2>
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
                <select id="statusFilter" class="form-select">
                    <option value="">Tutti gli stati</option>
                    <option value="pending">In attesa</option>
                    <option value="reviewed">Revisionata</option>
                    <option value="contacted">Contattato</option>
                    <option value="accepted">Accettata</option>
                    <option value="rejected">Rifiutata</option>
                </select>
            </div>
        </div>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-hover align-middle">
        <thead>
            <tr>
                <th>ID</th>
                <th>Data</th>
                <th>Dispositivo</th>
                <th>Condizione</th>
                <th>Offerta</th>
                <th>Cliente</th>
                <th>Contatti</th>
                <th>Stato</th>
                <th class="text-center">Azioni</th>
            </tr>
        </thead>
        <tbody id="quotesTableBody">
            <?php foreach ($quotes as $q): ?>
                <tr id="quote-<?php echo $q['id']; ?>">
                    <td class="fw-bold">#<?php echo $q['id']; ?></td>
                    <td><?php echo date('d/m/Y', strtotime($q['created_at'])); ?><br><small class="text-muted"><?php echo date('H:i', strtotime($q['created_at'])); ?></small></td>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="me-2">
                                <?php 
                                $type = strtolower($q['device_type']);
                                if($type == 'smartphone'): ?>
                                    <i class="fas fa-mobile-alt fa-lg text-secondary"></i>
                                <?php elseif($type == 'tablet'): ?>
                                    <i class="fas fa-tablet-alt fa-lg text-secondary"></i>
                                <?php elseif($type == 'console'): ?>
                                    <i class="fas fa-gamepad fa-lg text-secondary"></i>
                                <?php else: ?>
                                    <i class="fas fa-laptop fa-lg text-secondary"></i>
                                <?php endif; ?>
                            </div>
                            <div>
                                <div class="fw-bold"><?php echo htmlspecialchars($q['brand_name']); ?></div>
                                <div class="small text-muted"><?php echo htmlspecialchars($q['model_name']); ?></div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="badge bg-light text-dark border"><?php echo htmlspecialchars($q['device_condition']); ?></span>
                    </td>
                    <td class="fw-bold text-success">
                        <?php if ($q['expected_price']): ?>
                            € <?php echo number_format($q['expected_price'], 2, ',', '.'); ?>
                        <?php else: ?>
                            <span class="text-muted">-</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div class="fw-bold"><?php echo htmlspecialchars($q['customer_first_name'] . ' ' . $q['customer_last_name']); ?></div>
                    </td>
                    <td>
                        <div class="d-flex flex-column gap-1">
                            <a href="mailto:<?php echo htmlspecialchars($q['customer_email']); ?>" class="text-decoration-none small">
                                <i class="far fa-envelope me-1"></i> <?php echo htmlspecialchars($q['customer_email']); ?>
                            </a>
                            <div class="d-flex align-items-center gap-2">
                                <span class="small"><i class="fas fa-phone me-1"></i> <?php echo htmlspecialchars($q['customer_phone']); ?></span>
                                <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', $q['customer_phone']); ?>" target="_blank" class="text-success" data-bs-toggle="tooltip" title="Chatta su WhatsApp">
                                    <i class="fab fa-whatsapp"></i>
                                </a>
                            </div>
                        </div>
                    </td>
                    <td>
                        <?php
                        $statusClass = match($q['status']) {
                            'reviewed' => 'bg-info text-dark',
                            'contacted' => 'bg-primary',
                            'accepted' => 'bg-success',
                            'rejected' => 'bg-danger',
                            default => 'bg-warning text-dark'
                        };
                        $statusLabel = match($q['status']) {
                            'pending' => 'In Attesa',
                            'reviewed' => 'Revisionata',
                            'contacted' => 'Contattato',
                            'accepted' => 'Accettata',
                            'rejected' => 'Rifiutata',
                            default => $q['status']
                        };
                        ?>
                        <span class="badge <?php echo $statusClass; ?> rounded-pill"><?php echo $statusLabel; ?></span>
                    </td>
                    <td class="text-center">
                        <div class="btn-group">
                            <button class="btn btn-sm btn-outline-primary edit-btn" data-id="<?php echo $q['id']; ?>" data-bs-toggle="tooltip" title="Gestisci">
                                <i class="fas fa-cog"></i>
                            </button>
                            <a href="print_used_quote.php?id=<?php echo $q['id']; ?>" target="_blank" class="btn btn-sm btn-outline-secondary" data-bs-toggle="tooltip" title="Stampa">
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

<!-- Modal Gestione Valutazione -->
<div class="modal fade" id="quoteModal" tabindex="-1" aria-labelledby="quoteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="quoteModalLabel">Gestione Valutazione</h5>
                <div class="ms-auto">
                    <a href="#" id="printBtn" target="_blank" class="btn btn-outline-secondary btn-sm me-2">
                        <i class="fas fa-print me-1"></i> Stampa Scheda
                    </a>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>
            <div class="modal-body">
                <form id="quoteForm">
                    <input type="hidden" id="quoteId" name="id">
                    
                    <!-- Quick Actions Toolbar -->
                    <div class="d-flex gap-2 mb-4 p-3 bg-light rounded align-items-center">
                        <span class="fw-bold small text-uppercase text-muted me-2">Azioni:</span>
                        <a href="#" id="waBtn" target="_blank" class="btn btn-success btn-sm text-white">
                            <i class="fab fa-whatsapp me-1"></i> Invia Offerta
                        </a>
                        <div class="vr mx-2"></div>
                        <button type="button" class="btn btn-outline-primary btn-sm quick-status" data-status="contacted">
                            <i class="fas fa-paper-plane me-1"></i> Contattato
                        </button>
                        <button type="button" class="btn btn-outline-success btn-sm quick-status" data-status="accepted">
                            <i class="fas fa-check me-1"></i> Accettata
                        </button>
                        <button type="button" class="btn btn-outline-danger btn-sm quick-status" data-status="rejected">
                            <i class="fas fa-times me-1"></i> Rifiutata
                        </button>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body">
                                    <h6 class="card-title text-primary"><i class="fas fa-mobile-alt me-2"></i>Dispositivo</h6>
                                    <p id="deviceDetails" class="card-text mb-0"></p>
                                    <hr class="my-2">
                                    <p id="conditionDetails" class="card-text small"></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body">
                                    <h6 class="card-title text-primary"><i class="fas fa-user me-2"></i>Cliente</h6>
                                    <p id="customerDetails" class="card-text mb-0"></p>
                                    <hr class="my-2">
                                    <div class="mb-2">
                                        <label for="expected_price" class="form-label fw-bold small text-uppercase">Offerta (€)</label>
                                        <input type="number" step="0.01" class="form-control form-control-lg fw-bold text-success" id="expected_price" name="expected_price">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="status" class="form-label fw-bold">Stato Valutazione</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="pending">In attesa</option>
                                <option value="reviewed">Revisionata</option>
                                <option value="contacted">Contattato</option>
                                <option value="accepted">Accettata</option>
                                <option value="rejected">Rifiutata</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="notes" class="form-label fw-bold">Note Interne</label>
                            <textarea class="form-control" id="notes" name="notes" rows="1" placeholder="Note visibili solo allo staff..."></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Chiudi</button>
                <button type="button" class="btn btn-primary" id="saveQuoteBtn">Salva Modifiche</button>
            </div>
        </div>
    </div>
</div>

<?php include_once 'includes/footer.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const quoteModal = new bootstrap.Modal(document.getElementById('quoteModal'));
    const quoteForm = document.getElementById('quoteForm');
    const quoteIdInput = document.getElementById('quoteId');
    
    // UI Elements
    const deviceDetails = document.getElementById('deviceDetails');
    const customerDetails = document.getElementById('customerDetails');
    const conditionDetails = document.getElementById('conditionDetails');
    const statusSelect = document.getElementById('status');
    const priceInput = document.getElementById('expected_price');
    const notesInput = document.getElementById('notes');
    
    // Action Buttons
    const printBtn = document.getElementById('printBtn');
    const waBtn = document.getElementById('waBtn');

    // Tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Edit Button Click
    document.getElementById('quotesTableBody').addEventListener('click', function(e) {
        const editBtn = e.target.closest('.edit-btn');
        if (editBtn) {
            const id = editBtn.dataset.id;
            fetch(`ajax_actions/used_quote_actions.php?action=get&id=${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        const q = data.quote;
                        quoteIdInput.value = q.id;
                        
                        // Update UI
                        deviceDetails.innerHTML = `<span class="fw-bold">${q.device_type}</span><br>${q.brand_name} ${q.model_name}`;
                        customerDetails.innerHTML = `<span class="fw-bold">${q.customer_first_name} ${q.customer_last_name}</span><br>${q.customer_phone}<br>${q.customer_email}`;
                        
                        let condHtml = `<div class="mb-2">Condizione: <span class="badge bg-light text-dark border">${q.device_condition}</span></div>`;
                        
                        let defects = [];
                        try { defects = JSON.parse(q.defects || '[]'); } catch(e){}
                        if(defects.length) condHtml += `<div class="mb-1"><span class="text-danger fw-bold">Difetti:</span> ${defects.join(', ')}</div>`;
                        
                        let accessories = [];
                        try { accessories = JSON.parse(q.accessories || '[]'); } catch(e){}
                        if(accessories.length) condHtml += `<div><span class="text-success fw-bold">Accessori:</span> ${accessories.join(', ')}</div>`;
                        
                        conditionDetails.innerHTML = condHtml;

                        statusSelect.value = q.status;
                        priceInput.value = q.expected_price || '';
                        notesInput.value = q.notes || '';

                        // Function to update links
                        const updateLinks = () => {
                            const price = priceInput.value;
                            
                            // Update Print Link
                            printBtn.href = `print_used_quote.php?id=${q.id}&price=${price}`;
                            
                            // Update WhatsApp Link
                            const phone = q.customer_phone.replace(/[^0-9]/g, '');
                            let msg = `Ciao ${q.customer_first_name}, abbiamo valutato il tuo ${q.brand_name} ${q.model_name}. `;
                            if (price) {
                                msg += `La nostra offerta è di € ${parseFloat(price).toLocaleString()}.`;
                            } else {
                                msg += `Stiamo ancora elaborando l'offerta.`;
                            }
                            const waText = encodeURIComponent(msg);
                            waBtn.href = `https://wa.me/${phone}?text=${waText}`;
                        };

                        // Initial update
                        updateLinks();

                        // Listen for changes
                        priceInput.oninput = updateLinks;

                        quoteModal.show();
                    } else {
                        alert('Errore nel recupero dei dati.');
                    }
                });
        }
    });

    // Quick Status Actions
    document.querySelectorAll('.quick-status').forEach(btn => {
        btn.addEventListener('click', function() {
            statusSelect.value = this.dataset.status;
        });
    });

    // Save Button Click
    document.getElementById('saveQuoteBtn').addEventListener('click', function() {
        const formData = new FormData(quoteForm);
        formData.append('action', 'edit');

        fetch('ajax_actions/used_quote_actions.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                quoteModal.hide();
                location.reload();
            } else {
                alert(data.message || 'Errore durante il salvataggio.');
            }
        });
    });

    // Delete Button Click
    document.getElementById('quotesTableBody').addEventListener('click', function(e) {
        const deleteBtn = e.target.closest('.delete-btn');
        if (deleteBtn) {
            const id = deleteBtn.dataset.id;
            if (confirm('Sei sicuro di voler eliminare questa valutazione?')) {
                const formData = new FormData();
                formData.append('action', 'delete');
                formData.append('id', id);

                fetch('ajax_actions/used_quote_actions.php', {
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
    const statusFilter = document.getElementById('statusFilter');
    const tableBody = document.querySelector('.table tbody');
    const tableRows = tableBody.getElementsByTagName('tr');

    function filterTable() {
        const searchTerm = searchInput.value.toLowerCase();
        const statusTerm = statusFilter.value.toLowerCase();

        for (let i = 0; i < tableRows.length; i++) {
            const clientCell = tableRows[i].getElementsByTagName('td')[5];
            const contactCell = tableRows[i].getElementsByTagName('td')[6];
            const statusCell = tableRows[i].getElementsByTagName('td')[7];

            if (clientCell && contactCell && statusCell) {
                const clientText = clientCell.textContent.toLowerCase();
                const contactText = contactCell.textContent.toLowerCase();
                const statusText = statusCell.textContent.toLowerCase();

                const searchMatch = clientText.includes(searchTerm) || contactText.includes(searchTerm);
                
                let statusMatch = false;
                if (statusTerm === '') {
                    statusMatch = true;
                } else {
                    const map = {
                        'pending': 'In Attesa',
                        'reviewed': 'Revisionata',
                        'contacted': 'Contattato',
                        'accepted': 'Accettata',
                        'rejected': 'Rifiutata'
                    };
                    statusMatch = statusText.includes(map[statusTerm].toLowerCase());
                }

                if (searchMatch && statusMatch) {
                    tableRows[i].style.display = '';
                } else {
                    tableRows[i].style.display = 'none';
                }
            }
        }
    }

    searchInput.addEventListener('keyup', filterTable);
    statusFilter.addEventListener('change', filterTable);
});
</script>
