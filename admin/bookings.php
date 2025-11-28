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
                                <?php 
                                $iconClass = 'fa-laptop'; // Default
                                $type = strtolower($b['device_type']);
                                if (strpos($type, 'smartphone') !== false || strpos($type, 'iphone') !== false) {
                                    $iconClass = 'fa-mobile-alt';
                                } elseif (strpos($type, 'tablet') !== false || strpos($type, 'ipad') !== false) {
                                    $iconClass = 'fa-tablet-alt';
                                } elseif (strpos($type, 'console') !== false || strpos($type, 'playstation') !== false || strpos($type, 'xbox') !== false) {
                                    $iconClass = 'fa-gamepad';
                                } elseif (strpos($type, 'watch') !== false) {
                                    $iconClass = 'fa-clock';
                                }
                                ?>
                                <i class="fas <?php echo $iconClass; ?> fa-lg text-secondary"></i>
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
<div class="modal fade" id="bookingModal" tabindex="-1" aria-labelledby="bookingModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="bookingModalLabel"><i class="fas fa-calendar-check me-2"></i>Gestione Prenotazione</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="bookingForm">
                    <input type="hidden" id="bookingId" name="id">
                    
                    <!-- Dettagli Principali -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card h-100 border-0 bg-light">
                                <div class="card-body">
                                    <h6 class="card-title text-primary fw-bold mb-3"><i class="fas fa-info-circle me-2"></i>Dettagli Intervento</h6>
                                    <p id="deviceDetails" class="card-text mb-2"></p>
                                    <p id="problemDetails" class="card-text small text-muted mb-0"></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card h-100 border-0 bg-light">
                                <div class="card-body">
                                    <h6 class="card-title text-primary fw-bold mb-3"><i class="fas fa-user me-2"></i>Cliente & Appuntamento</h6>
                                    <p id="customerDetails" class="card-text mb-2"></p>
                                    <p id="appointmentDetails" class="card-text small text-muted mb-0"></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sezione Stato e Comunicazione -->
                    <div class="row g-4">
                        <div class="col-md-5">
                            <label for="status" class="form-label fw-bold">Stato Prenotazione</label>
                            <select class="form-select mb-3" id="status" name="status" required>
                                <option value="pending">In attesa</option>
                                <option value="confirmed">Confermata</option>
                                <option value="cancelled">Cancellata</option>
                                <option value="completed">Completata</option>
                            </select>

                            <label for="notes" class="form-label fw-bold">Note Interne</label>
                            <textarea class="form-control" id="notes" name="notes" rows="4" placeholder="Note visibili solo allo staff..."></textarea>
                        </div>

                        <div class="col-md-7">
                            <div class="card border-primary h-100">
                                <div class="card-header bg-primary text-white py-2">
                                    <h6 class="mb-0 small fw-bold"><i class="fas fa-comment-alt me-2"></i>Comunicazione Cliente</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="messagePreview" class="form-label small text-muted">Messaggio da inviare:</label>
                                        <textarea class="form-control form-control-sm bg-light" id="messagePreview" rows="4"></textarea>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <a href="#" id="sendWaBtn" target="_blank" class="btn btn-success btn-sm flex-grow-1">
                                            <i class="fab fa-whatsapp me-1"></i> Invia WhatsApp
                                        </a>
                                        <a href="#" id="sendEmailBtn" class="btn btn-outline-secondary btn-sm flex-grow-1">
                                            <i class="far fa-envelope me-1"></i> Invia Email
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer bg-light">
                <a href="#" id="printBtn" target="_blank" class="btn btn-outline-dark me-auto">
                    <i class="fas fa-print me-1"></i> Stampa Scheda
                </a>
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Chiudi</button>
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
    const messagePreview = document.getElementById('messagePreview');
    
    // Action Buttons
    const printBtn = document.getElementById('printBtn');
    const sendWaBtn = document.getElementById('sendWaBtn');
    const sendEmailBtn = document.getElementById('sendEmailBtn');

    // Current Booking Data (for templates)
    let currentBooking = {};

    // Tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Template Generator
    function updateMessageTemplate() {
        const status = statusSelect.value;
        const name = currentBooking.customer_first_name;
        const device = `${currentBooking.brand_name} ${currentBooking.model_name}`;
        const date = currentBooking.preferred_date; // Should format this nicely
        const time = currentBooking.preferred_time_slot;
        
        // Simple date formatter
        const dateObj = new Date(currentBooking.preferred_date);
        const dateStr = dateObj.toLocaleDateString('it-IT');

        let message = "";

        switch(status) {
            case 'confirmed':
                message = `Ciao ${name}, il tuo appuntamento per la riparazione del ${device} è confermato per il ${dateStr} alle ore ${time}. Ti aspettiamo!`;
                break;
            case 'completed':
                message = `Ciao ${name}, buone notizie! Il tuo ${device} è pronto per il ritiro. Puoi passare in negozio quando vuoi durante gli orari di apertura.`;
                break;
            case 'cancelled':
                message = `Ciao ${name}, come concordato il tuo appuntamento per il ${device} è stato cancellato. Contattaci se desideri fissarne un altro.`;
                break;
            case 'pending':
            default:
                message = `Ciao ${name}, abbiamo ricevuto la tua richiesta per ${device}. Ti contatteremo a breve per confermare l'appuntamento.`;
                break;
        }

        messagePreview.value = message;
        updateActionLinks(message);
    }

    function updateActionLinks(message) {
        const phone = currentBooking.customer_phone ? currentBooking.customer_phone.replace(/[^0-9]/g, '') : '';
        const email = currentBooking.customer_email;
        
        // WhatsApp
        if (phone) {
            sendWaBtn.href = `https://wa.me/${phone}?text=${encodeURIComponent(message)}`;
            sendWaBtn.classList.remove('disabled');
        } else {
            sendWaBtn.href = '#';
            sendWaBtn.classList.add('disabled');
        }

        // Email
        if (email) {
            sendEmailBtn.href = `mailto:${email}?subject=Aggiornamento Riparazione KeySoft&body=${encodeURIComponent(message)}`;
            sendEmailBtn.classList.remove('disabled');
        } else {
            sendEmailBtn.href = '#';
            sendEmailBtn.classList.add('disabled');
        }
    }

    // Status Change Listener
    statusSelect.addEventListener('change', updateMessageTemplate);
    messagePreview.addEventListener('input', function() {
        updateActionLinks(this.value);
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
                        currentBooking = data.booking;
                        const b = currentBooking;
                        bookingIdInput.value = b.id;
                        
                        // Update UI
                        deviceDetails.innerHTML = `<span class="fw-bold fs-5">${b.device_type}</span><br>${b.brand_name} ${b.model_name}`;
                        customerDetails.innerHTML = `<span class="fw-bold">${b.customer_first_name} ${b.customer_last_name}</span><br>${b.customer_phone}<br>${b.customer_email}`;
                        
                        const dateObj = new Date(b.preferred_date);
                        const dateStr = dateObj.toLocaleDateString('it-IT');
                        appointmentDetails.innerHTML = `<strong>Data:</strong> ${dateStr}<br><strong>Ora:</strong> ${b.preferred_time_slot}<br><strong>Tipo:</strong> ${b.dropoff_type}`;
                        
                        problemDetails.innerHTML = `<strong>Problema:</strong> ${b.problem_summary}`;

                        statusSelect.value = b.status;
                        notesInput.value = b.notes || '';

                        // Update Action Links
                        printBtn.href = `print_booking.php?id=${b.id}`;
                        
                        // Generate initial template
                        updateMessageTemplate();

                        bookingModal.show();
                    } else {
                        alert('Errore nel recupero dei dati.');
                    }
                });
        }
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
                const statusText = statusCell.textContent.toLowerCase();

                const searchMatch = clientText.includes(searchTerm) || contactText.includes(searchTerm);
                
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
