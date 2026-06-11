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
    <li class="nav-item" role="presentation">
        <button class="nav-link fw-bold" id="partners-tab" data-bs-toggle="tab" data-bs-target="#partners-panel" type="button" role="tab" aria-selected="false">
            <i class="fas fa-handshake me-1"></i> Brand Partner
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

    <!-- PANEL 3: PARTNERS -->
    <div class="tab-pane fade" id="partners-panel" role="tabpanel" aria-labelledby="partners-tab">
        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 80px;" class="text-center">Icona / Logo</th>
                                <th>Nome Partner</th>
                                <th>Sottotitolo / Descrizione</th>
                                <th>Classe Icona</th>
                                <th>Colore Icona</th>
                                <th class="text-center" style="width: 80px;">Logo</th>
                                <th class="text-center" style="width: 100px;">Ordinamento</th>
                                <th class="text-center" style="width: 100px;">Stato</th>
                                <th class="text-center" style="width: 120px;">Azioni</th>
                            </tr>
                        </thead>
                        <tbody id="partnerTableBody">
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
                            <label class="form-label fw-bold">Brand Partner (Operatore) *</label>
                            <select class="form-select" id="partner_id" name="partner_id" required>
                                <option value="">— Seleziona un operatore —</option>
                            </select>
                            <div class="mt-2 d-flex align-items-center gap-2" id="partner-preview-wrap" style="display:none!important">
                                <div id="partner-logo-preview"></div>
                                <span id="partner-name-preview" class="fw-bold text-muted small"></span>
                            </div>
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

<!-- Modal for Add/Edit Brand Partner -->
<div class="modal fade" id="partnerModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="partnerModalLabel">Nuovo Brand Partner</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="partnerForm" enctype="multipart/form-data">
                    <input type="hidden" id="partnerId" name="id">
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Nome Partner *</label>
                            <input type="text" class="form-control" id="partner_name" name="name" required placeholder="es. Kena Mobile, Fastweb Casa">
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Sottotitolo / Descrizione</label>
                            <input type="text" class="form-control" id="partner_description" name="description" placeholder="es. Rete TIM 5G a tariffe imbattibili">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Classe Icona (Remix Icon) *</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i id="partnerIconPreview" class="ri-smartphone-line"></i></span>
                                <input type="text" class="form-control" id="partner_icon_class" name="icon_class" required value="ri-smartphone-line" placeholder="es. ri-smartphone-line, ri-wifi-line">
                            </div>
                            <small class="text-muted">Puoi usare qualsiasi icona da <a href="https://remixicon.com/" target="_blank">Remix Icon</a>.</small>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Colore Icona *</label>
                            <input type="text" class="form-control" id="partner_icon_color" name="icon_color" required value="var(--ks-orange)" placeholder="es. var(--ks-orange), #7c4dff, #003996">
                            <small class="text-muted">Supporta HEX, RGB o CSS variables (es: `var(--ks-orange)`, `var(--ks-green)`).</small>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Ordinamento (Crescente)</label>
                            <input type="number" class="form-control" id="partner_sort_order" name="sort_order" required value="0">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Stato Pubblicazione *</label>
                            <select class="form-select" id="partner_status" name="status" required>
                                <option value="1">Attivo</option>
                                <option value="0">Disattivato</option>
                            </select>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-bold">Logo Brand (Immagine)</label>
                            <input type="file" class="form-control" id="partner_logo_file" name="logo_file" accept="image/*">
                            <div class="form-text">Carica il logo ufficiale dell'operatore. Se presente, verrà usato nelle offerte al posto dell'icona. Formati: PNG, JPG, WEBP, SVG.</div>
                            <div id="partner-logo-current" class="mt-2"></div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                <button type="button" class="btn btn-primary" id="savePartnerBtn"><i class="fas fa-save me-1"></i> Salva Partner</button>
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

    const partnerModal = new bootstrap.Modal(document.getElementById('partnerModal'));
    const partnerModalTitle = document.getElementById('partnerModalLabel');
    const partnerForm = document.getElementById('partnerForm');
    const partnerIdInput = document.getElementById('partnerId');
    const partnerTableBody = document.getElementById('partnerTableBody');
    const savePartnerBtn = document.getElementById('savePartnerBtn');
    
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
    
    document.getElementById('partners-tab').addEventListener('shown.bs.tab', function() {
        actionBtnArea.innerHTML = `
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#partnerModal" id="addPartnerBtn">
                <i class="fas fa-plus"></i> Aggiungi Brand Partner
            </button>
        `;
        document.getElementById('addPartnerBtn').addEventListener('click', () => resetPartnerForm());
        fetchPartners();
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

    function loadPartnersDropdown(selectedId = null) {
        fetch('ajax_actions/telephony_actions.php?action=list_partners')
            .then(r => r.json())
            .then(data => {
                const sel = document.getElementById('partner_id');
                sel.innerHTML = '<option value="">— Seleziona un operatore —</option>';
                if (data.status === 'success') {
                    data.partners.forEach(p => {
                        const opt = document.createElement('option');
                        opt.value = p.id;
                        opt.textContent = p.name;
                        if (selectedId && parseInt(selectedId) === parseInt(p.id)) opt.selected = true;
                        sel.appendChild(opt);
                    });
                }
            });
    }

    function resetForm() {
        promoForm.reset();
        promoIdInput.value = '';
        modalTitle.textContent = 'Nuova Offerta Telefonia';
        loadPartnersDropdown();
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
                        document.getElementById('plan_name').value = p.plan_name;
                        document.getElementById('price').value = p.price;
                        document.getElementById('price_detail').value = p.price_detail || '/mese';
                        document.getElementById('features').value = p.features || '';
                        document.getElementById('status').value = p.status;
                        document.getElementById('is_featured').checked = !!parseInt(p.is_featured);
                        
                        loadPartnersDropdown(p.partner_id);

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

    // --- Brand Partners Code ---
    function fetchPartners() {
        fetch('ajax_actions/telephony_actions.php?action=list_partners')
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    partnerTableBody.innerHTML = '';
                    if (data.partners.length === 0) {
                        partnerTableBody.innerHTML = '<tr><td colspan="8" class="text-center py-4 text-muted">Nessun Brand Partner inserito.</td></tr>';
                        return;
                    }
                    data.partners.forEach(partner => {
                        const statusBadge = partner.status == 1 
                            ? '<span class="badge bg-success"><i class="fas fa-check me-1"></i>Attivo</span>' 
                            : '<span class="badge bg-secondary"><i class="fas fa-eye-slash me-1"></i>Disattivato</span>';

                        const iconHtml = `<i class="${partner.icon_class}" style="font-size: 1.8rem; color: ${partner.icon_color || 'var(--ks-orange)'};"></i>`;
                        const logoHtml = partner.logo_path
                            ? `<img src="../${partner.logo_path}" style="max-height:36px;max-width:70px;object-fit:contain;" class="rounded border p-1 bg-white">`
                            : `<span class="text-muted small">—</span>`;

                        const row = `
                            <tr id="partner-${partner.id}">
                                <td class="text-center">${iconHtml}</td>
                                <td><span class="fw-bold">${partner.name}</span></td>
                                <td>${partner.description || '<span class="text-muted">-</span>'}</td>
                                <td><code>${partner.icon_class}</code></td>
                                <td><code>${partner.icon_color}</code></td>
                                <td class="text-center">${logoHtml}</td>
                                <td class="text-center">${partner.sort_order}</td>
                                <td class="text-center">${statusBadge}</td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-outline-primary edit-partner-btn me-1" data-id="${partner.id}" title="Modifica"><i class="fas fa-edit"></i></button>
                                    <button class="btn btn-sm btn-outline-danger delete-partner-btn" data-id="${partner.id}" title="Elimina"><i class="fas fa-trash-alt"></i></button>
                                </td>
                            </tr>
                        `;
                        partnerTableBody.insertAdjacentHTML('beforeend', row);
                    });
                }
            });
    }

    function resetPartnerForm() {
        partnerForm.reset();
        partnerIdInput.value = '';
        partnerModalTitle.textContent = 'Nuovo Brand Partner';
        document.getElementById('partnerIconPreview').className = 'ri-smartphone-line';
        document.getElementById('partner-logo-current').innerHTML = '';
    }

    // Live preview of icon
    document.getElementById('partner_icon_class').addEventListener('input', function() {
        document.getElementById('partnerIconPreview').className = this.value || 'ri-smartphone-line';
    });

    partnerTableBody.addEventListener('click', function(e) {
        const editBtn = e.target.closest('.edit-partner-btn');
        if (editBtn) {
            const id = editBtn.dataset.id;
            fetch(`ajax_actions/telephony_actions.php?action=get_partner&id=${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        const p = data.partner;
                        partnerIdInput.value = p.id;
                        document.getElementById('partner_name').value = p.name;
                        document.getElementById('partner_description').value = p.description || '';
                        document.getElementById('partner_icon_class').value = p.icon_class;
                        document.getElementById('partner_icon_color').value = p.icon_color;
                        document.getElementById('partner_sort_order').value = p.sort_order;
                        document.getElementById('partner_status').value = p.status;
                        
                        document.getElementById('partnerIconPreview').className = p.icon_class;
                        document.getElementById('partner-logo-current').innerHTML = p.logo_path
                            ? `<img src="../${p.logo_path}" class="preview-thumbnail mt-2" title="Logo corrente"> <small class="text-muted d-block mt-1">Logo attuale — carica un nuovo file per sostituirlo.</small>`
                            : '';
                        
                        partnerModalTitle.textContent = 'Modifica Brand Partner';
                        partnerModal.show();
                    }
                });
        }
    });

    partnerTableBody.addEventListener('click', function(e) {
        const deleteBtn = e.target.closest('.delete-partner-btn');
        if (deleteBtn) {
            const id = deleteBtn.dataset.id;
            if (confirm('Sei sicuro di voler eliminare questo Brand Partner?')) {
                const formData = new FormData();
                formData.append('action', 'delete_partner');
                formData.append('id', id);

                fetch('ajax_actions/telephony_actions.php', { method: 'POST', body: formData })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            fetchPartners();
                        } else {
                            alert(data.message || 'Errore durante l\'eliminazione.');
                        }
                    });
            }
        }
    });

    savePartnerBtn.addEventListener('click', function() {
        if (!partnerForm.checkValidity()) {
            partnerForm.reportValidity();
            return;
        }
        
        const formData = new FormData(partnerForm);
        formData.append('action', partnerIdInput.value ? 'edit_partner' : 'add_partner');
        
        fetch('ajax_actions/telephony_actions.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                partnerModal.hide();
                fetchPartners();
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
