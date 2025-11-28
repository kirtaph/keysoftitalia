<?php
include_once 'includes/header.php';

$stmt = $pdo->query('SELECT * FROM repair_bookings ORDER BY created_at DESC');
$bookings = $stmt->fetchAll();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="section-title mb-0">Gestione Prenotazioni</h2>
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
                    <option value="confirmed">Confermata</option>
                    <option value="cancelled">Cancellata</option>
                    <option value="completed">Completata</option>
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
                <th>Problema</th>
                <th>Appuntamento</th>
                <th>Cliente</th>
                <th>Contatti</th>
                <th>Stato</th>
                <th class="text-center">Azioni</th>
            </tr>
        </thead>
        <tbody id="bookingsTableBody">
            <?php foreach ($bookings as $b): ?>
                <tr id="booking-<?php echo $b['id']; ?>">
                    <td class="fw-bold">#<?php echo $b['id']; ?></td>
                    <td><?php echo date('d/m/Y', strtotime($b['created_at'])); ?><br><small class="text-muted"><?php echo date('H:i', strtotime($b['created_at'])); ?></small></td>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="me-2">
                                <?php if($b['device_type'] == 'Smartphone'): ?>
                                    <i class="fas fa-mobile-alt fa-lg text-secondary"></i>
                                <?php elseif($b['device_type'] == 'Tablet'): ?>
                                    <i class="fas fa-tablet-alt fa-lg text-secondary"></i>
                                <?php else: ?>
                                    <i class="fas fa-laptop fa-lg text-secondary"></i>
                                <?php endif; ?>
                            </div>
                            <div>
                                <div class="fw-bold"><?php echo htmlspecialchars($b['brand_name']); ?></div>
                                <div class="small text-muted"><?php echo htmlspecialchars($b['model_name']); ?></div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="d-inline-block text-truncate" style="max-width: 150px;" title="<?php echo htmlspecialchars($b['problem_summary']); ?>">
                            <?php echo htmlspecialchars($b['problem_summary']); ?>
                        </span>
                    </td>
                    <td>
                        <div class="small">
                            <i class="far fa-calendar me-1"></i> <?php echo date('d/m/Y', strtotime($b['preferred_date'])); ?><br>
                            <i class="far fa-clock me-1"></i> <?php echo htmlspecialchars($b['preferred_time_slot']); ?>
                        </div>
                    </td>
                    <td>
                        <div class="fw-bold"><?php echo htmlspecialchars($b['customer_first_name'] . ' ' . $b['customer_last_name']); ?></div>
                        <?php if ($b['customer_company']): ?>
                            <div class="small text-muted"><?php echo htmlspecialchars($b['customer_company']); ?></div>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div class="d-flex flex-column gap-1">
                            <a href="mailto:<?php echo htmlspecialchars($b['customer_email']); ?>" class="text-decoration-none small">
                                <i class="far fa-envelope me-1"></i> <?php echo htmlspecialchars($b['customer_email']); ?>
                            </a>
                            <div class="d-flex align-items-center gap-2">
                                <span class="small"><i class="fas fa-phone me-1"></i> <?php echo htmlspecialchars($b['customer_phone']); ?></span>
                                <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', $b['customer_phone']); ?>" target="_blank" class="text-success" data-bs-toggle="tooltip" title="Chatta su WhatsApp">
                                    <i class="fab fa-whatsapp"></i>
                                </a>
                            </div>
                        </div>
                    </td>
                    <td>
                        <?php
                        $statusClass = match($b['status']) {
                            'confirmed' => 'bg-success',
                            'cancelled' => 'bg-danger',
                            'completed' => 'bg-secondary',
                            default => 'bg-warning text-dark'
                        };
                        $statusLabel = match($b['status']) {
                            'pending' => 'In Attesa',
                            'confirmed' => 'Confermata',
                            'cancelled' => 'Cancellata',
                            'completed' => 'Completata',
                            default => $b['status']
                        };
                        ?>
                        <span class="badge <?php echo $statusClass; ?> rounded-pill"><?php echo $statusLabel; ?></span>
                    </td>
                    <td class="text-center">
                        <div class="btn-group">
                            <button class="btn btn-sm btn-outline-primary edit-btn" data-id="<?php echo $b['id']; ?>" data-bs-toggle="tooltip" title="Gestisci">
                                <i class="fas fa-cog"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger delete-btn" data-id="<?php echo $b['id']; ?>" data-bs-toggle="tooltip" title="Elimina">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Modal Gestione Prenotazione -->
<div class="modal fade" id="bookingModal" tabindex="-1" aria-labelledby="bookingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bookingModalLabel">Gestione Prenotazione</h5>
                <div class="ms-auto">
                    <a href="#" id="printBtn" target="_blank" class="btn btn-outline-secondary btn-sm me-2">
                        <i class="fas fa-print me-1"></i> Stampa Scheda
                    </a>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>
            <div class="modal-body">
                <form id="bookingForm">
                    <input type="hidden" id="bookingId" name="id">
                    
                    <!-- Quick Actions Toolbar -->
                    <div class="d-flex gap-2 mb-4 p-3 bg-light rounded align-items-center">
                        <span class="fw-bold small text-uppercase text-muted me-2">Azioni Rapide:</span>
                        <a href="#" id="waBtn" target="_blank" class="btn btn-success btn-sm text-white">
                            <i class="fab fa-whatsapp me-1"></i> Invia WhatsApp
                        </a>
                        <div class="vr mx-2"></div>
                        <button type="button" class="btn btn-outline-success btn-sm quick-status" data-status="confirmed">
                            <i class="fas fa-check me-1"></i> Conferma
                        </button>
                        <button type="button" class="btn btn-outline-secondary btn-sm quick-status" data-status="completed">
                            <i class="fas fa-check-double me-1"></i> Completa
                        </button>
                        <button type="button" class="btn btn-outline-danger btn-sm quick-status" data-status="cancelled">
                            <i class="fas fa-times me-1"></i> Cancella
                        </button>
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
                                    <p id="appointmentDetails" class="card-text small text-muted"></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label fw-bold">Stato Attuale</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="pending">In attesa</option>
                            <option value="confirmed">Confermata</option>
                            <option value="cancelled">Cancellata</option>
                            <option value="completed">Completata</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label fw-bold">Note Interne</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Aggiungi note visibili solo allo staff..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Chiudi</button>
                <button type="button" class="btn btn-primary" id="saveBookingBtn">Salva Modifiche</button>
            </div>
        </div>
    </div>
</div>

<?php include_once 'includes/footer.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const bookingModal = new bootstrap.Modal(document.getElementById('bookingModal'));
    const bookingForm = document.getElementById('bookingForm');
    const bookingIdInput = document.getElementById('bookingId');
    
    // UI Elements
    const deviceDetails = document.getElementById('deviceDetails');
    const customerDetails = document.getElementById('customerDetails');
    const appointmentDetails = document.getElementById('appointmentDetails');
    const problemDetails = document.getElementById('problemDetails');
    const statusSelect = document.getElementById('status');
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
    document.getElementById('bookingsTableBody').addEventListener('click', function(e) {
        const editBtn = e.target.closest('.edit-btn');
        if (editBtn) {
            const id = editBtn.dataset.id;
            fetch(`ajax_actions/booking_actions.php?action=get&id=${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        const b = data.booking;
                        bookingIdInput.value = b.id;
                        
                        // Update UI
                        deviceDetails.innerHTML = `<span class="fw-bold">${b.device_type}</span><br>${b.brand_name} ${b.model_name}`;
                        customerDetails.innerHTML = `<span class="fw-bold">${b.customer_first_name} ${b.customer_last_name}</span><br>${b.customer_phone}<br>${b.customer_email}`;
                        appointmentDetails.innerHTML = `<strong>Data:</strong> ${b.preferred_date}<br><strong>Ora:</strong> ${b.preferred_time_slot}<br><strong>Tipo:</strong> ${b.dropoff_type}`;
                        problemDetails.innerHTML = `<strong>Problema:</strong> ${b.problem_summary}`;

                        statusSelect.value = b.status;
                        notesInput.value = b.notes || '';

                        // Update Action Links
                        printBtn.href = `print_booking.php?id=${b.id}`;
                        
                        const phone = b.customer_phone.replace(/[^0-9]/g, '');
                        const waText = encodeURIComponent(`Ciao ${b.customer_first_name}, aggiornamento sulla tua riparazione #${b.id}: `);
                        waBtn.href = `https://wa.me/${phone}?text=${waText}`;

                        bookingModal.show();
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
            // Optional: Auto-save on quick action
            // document.getElementById('saveBookingBtn').click();
        });
    });

    // Save Button Click
    document.getElementById('saveBookingBtn').addEventListener('click', function() {
        const formData = new FormData(bookingForm);
        formData.append('action', 'edit');

        fetch('ajax_actions/booking_actions.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                bookingModal.hide();
                location.reload();
            } else {
                alert(data.message || 'Errore durante il salvataggio.');
            }
        });
    });

    // Delete Button Click
    document.getElementById('bookingsTableBody').addEventListener('click', function(e) {
        const deleteBtn = e.target.closest('.delete-btn');
        if (deleteBtn) {
            const id = deleteBtn.dataset.id;
            if (confirm('Sei sicuro di voler eliminare questa prenotazione?')) {
                const formData = new FormData();
                formData.append('action', 'delete');
                formData.append('id', id);

                fetch('ajax_actions/booking_actions.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        document.getElementById(`booking-${id}`).remove();
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
                const statusText = statusCell.textContent.toLowerCase(); // Contains hidden text or badge text

                const searchMatch = clientText.includes(searchTerm) || contactText.includes(searchTerm);
                
                // For status, we check if the badge text (or value) matches
                // The badge contains the translated status, so we might need to map it or check class
                // Simpler: check if the row's data-status matches (if we added it) or just text content
                // Let's rely on the text content of the badge which is "In Attesa", "Confermata" etc.
                // But the filter value is "pending", "confirmed".
                // Better approach: Add a data attribute to the row or cell.
                
                // Quick fix: Check if the status cell contains the mapped Italian string corresponding to the filter value
                let statusMatch = false;
                if (statusTerm === '') {
                    statusMatch = true;
                } else {
                    const map = {
                        'pending': 'In Attesa',
                        'confirmed': 'Confermata',
                        'cancelled': 'Cancellata',
                        'completed': 'Completata'
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
