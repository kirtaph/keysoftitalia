<?php
include_once 'includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="section-title"><i class="fas fa-phone-alt me-2 text-primary"></i>Gestione Telefonia</h2>
    <div id="actionBtnArea">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#promoModal" id="addPromoBtn">
            <i class="fas fa-plus"></i> Aggiungi Offerta
        </button>
    </div>
</div>

<!-- Tabs Control -->
<ul class="nav nav-tabs mb-4" id="telephonyTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active fw-bold" id="promos-tab" data-bs-toggle="tab" data-bs-target="#promos-panel" type="button" role="tab" aria-selected="true">
            <i class="fas fa-tags me-1"></i> Offerte & Promozioni
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link fw-bold" id="requests-tab" data-bs-toggle="tab" data-bs-target="#requests-panel" type="button" role="tab" aria-selected="false">
            <i class="fas fa-envelope-open-text me-1"></i> Richieste di Passaggio
            <span class="badge bg-danger ms-1 d-none" id="requestsBadgeCount">0</span>
        </button>
    </li>
</ul>

<div class="tab-content" id="telephonyTabsContent">
    <!-- PANEL 1: PROMOTIONS -->
    <div class="tab-pane fade show active" id="promos-panel" role="tabpanel" aria-labelledby="promos-tab">
        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 100px;">Logo Operatore</th>
                                <th>Operatore</th>
                                <th>Nome Offerta</th>
                                <th>Prezzo</th>
                                <th>Dettagli/Caratteristiche</th>
                                <th class="text-center">In Evidenza</th>
                                <th class="text-center">Stato</th>
                                <th class="text-center" style="width: 120px;">Azioni</th>
                            </tr>
                        </thead>
                        <tbody id="promoTableBody">
                            <!-- Loaded via AJAX -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- PANEL 2: REQUESTS -->
    <div class="tab-pane fade" id="requests-panel" role="tabpanel" aria-labelledby="requests-tab">
        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Data Richiesta</th>
                                <th>Cellulare Cliente</th>
                                <th>Offerta Richiesta</th>
                                <th>Spesa Precedente (cad.)</th>
                                <th>SIM Totali</th>
                                <th class="text-success">Risparmio Stimato</th>
                                <th>Stato Richiesta</th>
                                <th class="text-center" style="width: 100px;">Azioni</th>
                            </tr>
                        </thead>
                        <tbody id="requestsTableBody">
                            <!-- Loaded via AJAX -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Add/Edit Promotion -->
<div class="modal fade" id="promoModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="promoModalLabel">Nuova Offerta Telefonia</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="promoForm" enctype="multipart/form-data">
                    <input type="hidden" id="promoId" name="id">
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Nome Operatore *</label>
                            <input type="text" class="form-control" id="operator_name" name="operator_name" required placeholder="es. TIM, Vodafone, Fastweb, Iliad">
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Nome Offerta *</label>
                            <input type="text" class="form-control" id="plan_name" name="plan_name" required placeholder="es. Professional Premium, Unlimited Power">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Prezzo Mensile (€) *</label>
                            <input type="number" step="0.01" class="form-control" id="price" name="price" required placeholder="es. 9.99">
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Prezzo Dettaglio</label>
                            <input type="text" class="form-control" id="price_detail" name="price_detail" required value="/mese" placeholder="es. /mese, una tantum">
                        </div>
                        
                        <div class="col-md-12">
                            <label class="form-label fw-bold">Logo Operatore *</label>
                            <input type="file" class="form-control" id="logo_file" name="logo_file" accept="image/*">
                            <div class="form-text">Carica un logo per l'operatore. Formati supportati: PNG, JPG, WEBP, SVG.</div>
                            <div id="preview-logo" class="mt-2"></div>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-bold">Caratteristiche Offerta (una per riga)</label>
                            <textarea class="form-control" id="features" name="features" rows="5" placeholder="es. Minuti illimitati nazionali&#10;50 Giga in 5G&#10;100 SMS inclusi&#10;Centralino virtuale gratis per 3 mesi"></textarea>
                            <small class="text-muted">Inserisci ciascuna caratteristica/servizio incluso andando a capo.</small>
                        </div>

                        <div class="col-md-6 d-flex align-items-center">
                            <div class="form-check form-switch mt-3">
                                <input class="form-check-input" type="checkbox" role="switch" id="is_featured" name="is_featured">
                                <label class="form-check-label fw-bold" for="is_featured">Mostra in Evidenza</label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Stato Pubblicazione *</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="1">Pubblicato</option>
                                <option value="0">Bozza (Nascondi)</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                <button type="button" class="btn btn-primary" id="savePromoBtn"><i class="fas fa-save me-1"></i> Salva Offerta</button>
            </div>
        </div>
    </div>
</div>

<style>
.preview-thumbnail {
    max-height: 80px;
    max-width: 150px;
    border-radius: 6px;
    border: 1px solid #dee2e6;
    object-fit: contain;
    background: #f8f9fa;
    padding: 5px;
}
.badge-status-in-attesa { background-color: #ffe8d6; color: #a05a00; }
.badge-status-contattato { background-color: #dbf0fe; color: #005a9e; }
.badge-status-completato { background-color: #d4f4dd; color: #16a34a; }
.badge-status-annullato { background-color: #fee2e2; color: #dc2626; }
</style>

<?php include_once 'includes/footer.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const promoModal = new bootstrap.Modal(document.getElementById('promoModal'));
    const modalTitle = document.getElementById('promoModalLabel');
    const promoForm = document.getElementById('promoForm');
    const promoIdInput = document.getElementById('promoId');
    const promoTableBody = document.getElementById('promoTableBody');
    const requestsTableBody = document.getElementById('requestsTableBody');
    const savePromoBtn = document.getElementById('savePromoBtn');
    const requestsBadgeCount = document.getElementById('requestsBadgeCount');
    const addPromoBtn = document.getElementById('addPromoBtn');
    const actionBtnArea = document.getElementById('actionBtnArea');
    
    // Switch add buttons dynamically based on active tab
    document.getElementById('promos-tab').addEventListener('shown.bs.tab', function() {
        actionBtnArea.innerHTML = `
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#promoModal" id="addPromoBtn">
                <i class="fas fa-plus"></i> Aggiungi Offerta
            </button>
        `;
        document.getElementById('addPromoBtn').addEventListener('click', () => resetForm());
    });
    
    document.getElementById('requests-tab').addEventListener('shown.bs.tab', function() {
        actionBtnArea.innerHTML = `
            <button type="button" class="btn btn-outline-secondary" id="refreshRequestsBtn">
                <i class="fas fa-sync-alt"></i> Aggiorna Richieste
            </button>
        `;
        document.getElementById('refreshRequestsBtn').addEventListener('click', fetchRequests);
        fetchRequests();
    });

    function fetchPromotions() {
        fetch('ajax_actions/telephony_actions.php?action=list')
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    promoTableBody.innerHTML = '';
                    if (data.promotions.length === 0) {
                        promoTableBody.innerHTML = '<tr><td colspan="8" class="text-center py-4 text-muted">Nessuna promozione telefonica inserita.</td></tr>';
                        return;
                    }
                    data.promotions.forEach(promo => {
                        const logoHtml = promo.logo_path 
                            ? `<img src="../${promo.logo_path}" class="rounded border p-1" style="width: 80px; height: 40px; object-fit: contain; background: #fff;">`
                            : `<div class="bg-light rounded border d-flex align-items-center justify-content-center text-muted" style="width: 80px; height: 40px;"><i class="fas fa-phone-alt"></i></div>`;

                        const statusBadge = promo.status == 1 
                            ? '<span class="badge bg-success"><i class="fas fa-check me-1"></i>Pubblicato</span>' 
                            : '<span class="badge bg-secondary"><i class="fas fa-pen me-1"></i>Bozza</span>';

                        const featuredIcon = promo.is_featured == 1
                            ? '<i class="fas fa-star text-warning fa-lg" title="In Evidenza"></i>'
                            : '<i class="far fa-star text-muted fa-lg" style="opacity: 0.3;"></i>';

                        const featuresList = promo.features
                            ? promo.features.split('\n').filter(line => line.trim() !== '').slice(0, 3).map(line => `• ${line.trim()}`).join('<br>') + (promo.features.split('\n').length > 3 ? '...' : '')
                            : '<span class="text-muted">-</span>';

                        const priceFormatted = `€${parseFloat(promo.price).toFixed(2)}${promo.price_detail}`;

                        const row = `
                            <tr id="promo-${promo.id}">
                                <td>${logoHtml}</td>
                                <td><span class="fw-bold">${promo.operator_name}</span></td>
                                <td>${promo.plan_name}</td>
                                <td class="fw-bold text-primary">${priceFormatted}</td>
                                <td class="text-muted" style="font-size: 0.85rem;">${featuresList}</td>
                                <td class="text-center">${featuredIcon}</td>
                                <td class="text-center">${statusBadge}</td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-outline-primary edit-btn me-1" data-id="${promo.id}" title="Modifica"><i class="fas fa-edit"></i></button>
                                    <button class="btn btn-sm btn-outline-danger delete-btn" data-id="${promo.id}" title="Elimina"><i class="fas fa-trash-alt"></i></button>
                                </td>
                            </tr>
                        `;
                        promoTableBody.insertAdjacentHTML('beforeend', row);
                    });
                }
            });
    }

    function fetchRequests() {
        fetch('ajax_actions/telephony_actions.php?action=list_requests')
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    requestsTableBody.innerHTML = '';
                    
                    // Show pending request count in badge
                    const pendingCount = data.requests.filter(r => r.status === 'In attesa').length;
                    if (pendingCount > 0) {
                        requestsBadgeCount.textContent = pendingCount;
                        requestsBadgeCount.classList.remove('d-none');
                    } else {
                        requestsBadgeCount.classList.add('d-none');
                    }

                    if (data.requests.length === 0) {
                        requestsTableBody.innerHTML = '<tr><td colspan="8" class="text-center py-4 text-muted">Nessuna richiesta di passaggio ricevuta.</td></tr>';
                        return;
                    }

                    data.requests.forEach(req => {
                        const dateFormatted = new Date(req.created_at).toLocaleString('it-IT', {
                            day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit'
                        });

                        const savingsVal = parseFloat(req.estimated_savings);
                        const savingsFormatted = savingsVal > 0 
                            ? `<span class="fw-bold text-success">€${savingsVal.toFixed(0)}/anno</span>`
                            : `<span class="text-muted">Nessuno (Miglior piano)</span>`;

                        const selectStatus = `
                            <select class="form-select form-select-sm req-status-select" data-id="${req.id}">
                                <option value="In attesa" ${req.status === 'In attesa' ? 'selected' : ''}>In attesa</option>
                                <option value="Contattato" ${req.status === 'Contattato' ? 'selected' : ''}>Contattato</option>
                                <option value="Completato" ${req.status === 'Completato' ? 'selected' : ''}>Completato</option>
                                <option value="Annullato" ${req.status === 'Annullato' ? 'selected' : ''}>Annullato</option>
                            </select>
                        `;

                        const row = `
                            <tr id="request-${req.id}">
                                <td style="font-size: 0.9rem;">${dateFormatted}</td>
                                <td><a href="tel:${req.phone}" class="fw-bold text-decoration-none"><i class="fas fa-phone-alt me-1"></i> ${req.phone}</a></td>
                                <td>
                                    <div class="fw-bold">${req.operator_name}</div>
                                    <div class="small text-muted">${req.plan_name}</div>
                                </td>
                                <td>€${parseFloat(req.current_spend).toFixed(2)}/mese</td>
                                <td class="text-center">${req.num_lines}</td>
                                <td>${savingsFormatted}</td>
                                <td>${selectStatus}</td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-outline-danger delete-req-btn" data-id="${req.id}" title="Elimina Richiesta"><i class="fas fa-trash-alt"></i></button>
                                </td>
                            </tr>
                        `;
                        requestsTableBody.insertAdjacentHTML('beforeend', row);
                    });
                }
            });
    }

    // Handle status change
    requestsTableBody.addEventListener('change', function(e) {
        if (e.target.classList.contains('req-status-select')) {
            const id = e.target.dataset.id;
            const newStatus = e.target.value;

            const formData = new FormData();
            formData.append('action', 'update_request_status');
            formData.append('id', id);
            formData.append('status', newStatus);

            fetch('ajax_actions/telephony_actions.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status !== 'success') {
                    alert(data.message || 'Errore durante l\'aggiornamento dello stato.');
                } else {
                    // Refresh requests to update counts
                    fetchRequests();
                }
            });
        }
    });

    // Handle request delete
    requestsTableBody.addEventListener('click', function(e) {
        const deleteBtn = e.target.closest('.delete-req-btn');
        if (deleteBtn) {
            const id = deleteBtn.dataset.id;
            if (confirm('Sei sicuro di voler eliminare questa richiesta?')) {
                const formData = new FormData();
                formData.append('action', 'delete_request');
                formData.append('id', id);

                fetch('ajax_actions/telephony_actions.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        fetchRequests();
                    } else {
                        alert(data.message || 'Errore durante l\'eliminazione.');
                    }
                });
            }
        }
    });

    function resetForm() {
        promoForm.reset();
        promoIdInput.value = '';
        modalTitle.textContent = 'Nuova Offerta Telefonia';
        document.getElementById('preview-logo').innerHTML = '';
        document.getElementById('logo_file').required = true;
    }

    addPromoBtn.addEventListener('click', () => {
        resetForm();
    });

    promoTableBody.addEventListener('click', function(e) {
        const editBtn = e.target.closest('.edit-btn');
        if (editBtn) {
            const id = editBtn.dataset.id;
            fetch(`ajax_actions/telephony_actions.php?action=get&id=${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        const p = data.promotion;
                        promoIdInput.value = p.id;
                        document.getElementById('operator_name').value = p.operator_name;
                        document.getElementById('plan_name').value = p.plan_name;
                        document.getElementById('price').value = p.price;
                        document.getElementById('price_detail').value = p.price_detail || '/mese';
                        document.getElementById('features').value = p.features || '';
                        document.getElementById('status').value = p.status;
                        document.getElementById('is_featured').checked = !!parseInt(p.is_featured);
                        
                        document.getElementById('preview-logo').innerHTML = p.logo_path 
                            ? `<img src="../${p.logo_path}" class="preview-thumbnail mt-2">` 
                            : '';

                        document.getElementById('logo_file').required = false;

                        modalTitle.textContent = 'Modifica Offerta Telefonia';
                        promoModal.show();
                    }
                });
        }
    });

    promoTableBody.addEventListener('click', function(e) {
        const deleteBtn = e.target.closest('.delete-btn');
        if (deleteBtn) {
            const id = deleteBtn.dataset.id;
            if (confirm('Sei sicuro di voler eliminare questa offerta?')) {
                const formData = new FormData();
                formData.append('action', 'delete');
                formData.append('id', id);

                fetch('ajax_actions/telephony_actions.php', { method: 'POST', body: formData })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            fetchPromotions();
                        } else {
                            alert(data.message || 'Errore durante l\'eliminazione.');
                        }
                    });
            }
        }
    });

    savePromoBtn.addEventListener('click', function() {
        if (!promoForm.checkValidity()) {
            promoForm.reportValidity();
            return;
        }
        
        const formData = new FormData(promoForm);
        formData.append('action', promoIdInput.value ? 'edit' : 'add');
        
        fetch('ajax_actions/telephony_actions.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                promoModal.hide();
                fetchPromotions();
            } else {
                alert(data.message || 'Si è verificato un errore.');
            }
        });
    });

    // Initial loads
    fetchPromotions();
    
    // Check pending count for badge on startup
    fetch('ajax_actions/telephony_actions.php?action=list_requests')
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const pendingCount = data.requests.filter(r => r.status === 'In attesa').length;
                if (pendingCount > 0) {
                    requestsBadgeCount.textContent = pendingCount;
                    requestsBadgeCount.classList.remove('d-none');
                }
            }
        });
});
</script>
