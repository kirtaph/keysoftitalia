<?php
include_once 'includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="section-title"><i class="fas fa-bolt me-2 text-primary"></i>Gestione Forniture Luce & Gas</h2>
    <div id="actionBtnArea">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#promoModal" id="addPromoBtn">
            <i class="fas fa-plus"></i> Aggiungi Offerta
        </button>
    </div>
</div>

<!-- Tabs Control -->
<ul class="nav nav-tabs mb-4" id="utilityTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active fw-bold" id="promos-tab" data-bs-toggle="tab" data-bs-target="#promos-panel" type="button" role="tab" aria-selected="true">
            <i class="fas fa-tags me-1"></i> Offerte & Promozioni
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link fw-bold" id="requests-tab" data-bs-toggle="tab" data-bs-target="#requests-panel" type="button" role="tab" aria-selected="false">
            <i class="fas fa-envelope-open-text me-1"></i> Richieste di Consulenza
            <span class="badge bg-danger ms-1 d-none" id="requestsBadgeCount">0</span>
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link fw-bold" id="partners-tab" data-bs-toggle="tab" data-bs-target="#partners-panel" type="button" role="tab" aria-selected="false">
            <i class="fas fa-handshake me-1"></i> Brand Partner (Fornitori)
        </button>
    </li>
</ul>

<div class="tab-content" id="utilityTabsContent">
    <!-- PANEL 1: PROMOTIONS -->
    <div class="tab-pane fade show active" id="promos-panel" role="tabpanel" aria-labelledby="promos-tab">
        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 100px;">Logo</th>
                                <th>Fornitore</th>
                                <th>Nome Offerta</th>
                                <th>Tipo Utenza</th>
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
                                <th>Telefono Cliente</th>
                                <th>Offerta Selezionata</th>
                                <th>Tipo</th>
                                <th>Spesa Mensile Attuale</th>
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
                                <th>Nome Fornitore</th>
                                <th>Descrizione</th>
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
                <h5 class="modal-title" id="promoModalLabel">Nuova Offerta Luce/Gas</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="promoForm">
                    <input type="hidden" id="promoId" name="id">
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Fornitore Partner *</label>
                            <select class="form-select" id="partner_id" name="partner_id" required>
                                <option value="">— Seleziona un partner —</option>
                            </select>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Nome Offerta *</label>
                            <input type="text" class="form-control" id="plan_name" name="plan_name" required placeholder="es. Luce Flex, Gas Sicuro 24">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Tipo Utenza *</label>
                            <select class="form-select" id="utility_type" name="utility_type" required>
                                <option value="luce">Luce</option>
                                <option value="gas">Gas</option>
                                <option value="dual">Dual (Luce + Gas)</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-bold">Prezzo Stimato Mensile (€) *</label>
                            <input type="number" step="0.01" class="form-control" id="price" name="price" required placeholder="es. 49.90">
                        </div>
                        
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Prezzo Dettaglio</label>
                            <input type="text" class="form-control" id="price_detail" name="price_detail" required value="/mese" placeholder="es. /mese, PUN + fixed">
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-bold">Caratteristiche Offerta (una per riga)</label>
                            <textarea class="form-control" id="features" name="features" rows="5" placeholder="es. Energia 100% da fonti rinnovabili&#10;Prezzo materia prima bloccato 12 mesi&#10;Nessun costo di attivazione&#10;Gestione interamente da App"></textarea>
                            <small class="text-muted">Inserisci ciascuna caratteristica andando a capo.</small>
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
                            <input type="text" class="form-control" id="partner_name" name="name" required placeholder="es. Enel Energia, Plenitude">
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Descrizione</label>
                            <input type="text" class="form-control" id="partner_description" name="description" placeholder="es. Leader dell'energia in Italia">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Classe Icona (Remix Icon) *</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i id="partnerIconPreview" class="ri-flashlight-line"></i></span>
                                <input type="text" class="form-control" id="partner_icon_class" name="icon_class" required value="ri-flashlight-line" placeholder="es. ri-flashlight-line, ri-fire-line, ri-leaf-line">
                            </div>
                            <small class="text-muted">Puoi usare qualsiasi icona da <a href="https://remixicon.com/" target="_blank">Remix Icon</a>.</small>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Colore Icona *</label>
                            <input type="text" class="form-control" id="partner_icon_color" name="icon_color" required value="var(--ks-orange)" placeholder="es. var(--ks-orange), #22c55e, #00a2e8">
                            <small class="text-muted">Supporta HEX, RGB o variabili CSS (es: `var(--ks-orange)`, `#22c55e`).</small>
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
                            <div class="form-text">Carica il logo ufficiale. Verrà usato nelle offerte al posto dell'icona. Formati: PNG, JPG, WEBP, SVG.</div>
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
                <i class="fas fa-plus"></i> Aggiungi Partner
            </button>
        `;
        document.getElementById('addPartnerBtn').addEventListener('click', () => resetPartnerForm());
        fetchPartners();
    });

    function fetchPromotions() {
        fetch('ajax_actions/utility_actions.php?action=list')
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    promoTableBody.innerHTML = '';
                    if (data.promotions.length === 0) {
                        promoTableBody.innerHTML = '<tr><td colspan="9" class="text-center py-4 text-muted">Nessuna promozione luce/gas inserita.</td></tr>';
                        return;
                    }
                    data.promotions.forEach(promo => {
                        const logoHtml = promo.logo_path 
                            ? `<img src="../${promo.logo_path}" class="rounded border p-1" style="width: 80px; height: 40px; object-fit: contain; background: #fff;">`
                            : `<div class="bg-light rounded border d-flex align-items-center justify-content-center text-muted" style="width: 80px; height: 40px;"><i class="fas fa-bolt"></i></div>`;

                        const statusBadge = promo.status == 1 
                            ? '<span class="badge bg-success"><i class="fas fa-check me-1"></i>Pubblicato</span>' 
                            : '<span class="badge bg-secondary"><i class="fas fa-pen me-1"></i>Bozza</span>';

                        const featuredIcon = promo.is_featured == 1
                            ? '<i class="fas fa-star text-warning fa-lg" title="In Evidenza"></i>'
                            : '<i class="far fa-star text-muted fa-lg" style="opacity: 0.3;"></i>';

                        const typeBadge = promo.utility_type === 'luce' 
                            ? '<span class="badge bg-warning text-dark"><i class="fas fa-lightbulb me-1"></i>Luce</span>'
                            : (promo.utility_type === 'gas' 
                                ? '<span class="badge bg-info text-dark"><i class="fas fa-fire me-1"></i>Gas</span>' 
                                : '<span class="badge bg-primary"><i class="fas fa-bolt me-1"></i>Dual</span>');

                        const featuresList = promo.features
                            ? promo.features.split('\n').filter(line => line.trim() !== '').slice(0, 3).map(line => `• ${line.trim()}`).join('<br>') + (promo.features.split('\n').length > 3 ? '...' : '')
                            : '<span class="text-muted">-</span>';

                        const priceFormatted = `€${parseFloat(promo.price).toFixed(2)}${promo.price_detail}`;

                        const row = `
                            <tr id="promo-${promo.id}">
                                <td>${logoHtml}</td>
                                <td><span class="fw-bold">${promo.operator_name}</span></td>
                                <td>${promo.plan_name}</td>
                                <td>${typeBadge}</td>
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
        fetch('ajax_actions/utility_actions.php?action=list_requests')
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    requestsTableBody.innerHTML = '';
                    
                    const pendingCount = data.requests.filter(r => r.status === 'In attesa').length;
                    if (pendingCount > 0) {
                        requestsBadgeCount.textContent = pendingCount;
                        requestsBadgeCount.classList.remove('d-none');
                    } else {
                        requestsBadgeCount.classList.add('d-none');
                    }

                    if (data.requests.length === 0) {
                        requestsTableBody.innerHTML = '<tr><td colspan="8" class="text-center py-4 text-muted">Nessuna richiesta di consulenza ricevuta.</td></tr>';
                        return;
                    }

                    data.requests.forEach(req => {
                        const dateFormatted = new Date(req.created_at).toLocaleString('it-IT', {
                            day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit'
                        });

                        const savingsVal = parseFloat(req.estimated_savings);
                        const savingsFormatted = savingsVal > 0 
                            ? `<span class="fw-bold text-success">€${savingsVal.toFixed(0)}/anno</span>`
                            : `<span class="text-muted">Migliore tariffa</span>`;

                        const typeBadge = req.utility_type === 'luce' 
                            ? '<span class="badge bg-warning text-dark">Luce</span>'
                            : (req.utility_type === 'gas' 
                                ? '<span class="badge bg-info text-dark">Gas</span>' 
                                : '<span class="badge bg-primary">Dual</span>');

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
                                <td>${typeBadge}</td>
                                <td>€${parseFloat(req.current_spend).toFixed(2)}/mese</td>
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

            fetch('ajax_actions/utility_actions.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    const row = document.getElementById(`request-${id}`);
                    const select = row.querySelector('.req-status-select');
                    // Aggiorna classe/stile se necessario
                    fetchRequests(); // Ricarica per aggiornare badge e ordinamenti
                } else {
                    alert(data.message || 'Errore durante l\'aggiornamento dello stato.');
                }
            });
        }
    });

    // Delete request
    requestsTableBody.addEventListener('click', function(e) {
        const btn = e.target.closest('.delete-req-btn');
        if (btn) {
            const id = btn.dataset.id;
            if (confirm('Sei sicuro di voler eliminare questa richiesta di consulenza?')) {
                const formData = new FormData();
                formData.append('action', 'delete_request');
                formData.append('id', id);

                fetch('ajax_actions/utility_actions.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        document.getElementById(`request-${id}`).remove();
                        fetchRequests();
                    } else {
                        alert(data.message || 'Errore durante l\'eliminazione.');
                    }
                });
            }
        }
    });

    function fetchPartners() {
        fetch('ajax_actions/utility_actions.php?action=list_partners')
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    partnerTableBody.innerHTML = '';
                    const selectPartner = document.getElementById('partner_id');
                    selectPartner.innerHTML = '<option value="">— Seleziona un partner —</option>';
                    
                    if (data.partners.length === 0) {
                        partnerTableBody.innerHTML = '<tr><td colspan="9" class="text-center py-4 text-muted">Nessun Brand Partner inserito.</td></tr>';
                        return;
                    }
                    data.partners.forEach(partner => {
                        const statusBadge = partner.status == 1 
                            ? '<span class="badge bg-success">Attivo</span>' 
                            : '<span class="badge bg-secondary">Disattivato</span>';

                        const logoHtml = partner.logo_path 
                            ? `<img src="../${partner.logo_path}" class="rounded border p-1" style="width: 50px; height: 30px; object-fit: contain; background: #fff;">`
                            : '<span class="text-muted">-</span>';

                        const iconHtml = `<i class="${partner.icon_class}" style="font-size: 1.5rem; color: ${partner.icon_color}"></i>`;

                        const row = `
                            <tr id="partner-${partner.id}">
                                <td class="text-center">${iconHtml}</td>
                                <td><span class="fw-bold">${partner.name}</span></td>
                                <td class="text-muted" style="font-size: 0.85rem;">${partner.description || '-'}</td>
                                <td><code>${partner.icon_class}</code></td>
                                <td><code style="color: ${partner.icon_color}">${partner.icon_color}</code></td>
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

                        // Aggiunge all'elenco a discesa del form offerta
                        if (partner.status == 1) {
                            const opt = document.createElement('option');
                            opt.value = partner.id;
                            opt.textContent = partner.name;
                            selectPartner.appendChild(opt);
                        }
                    });
                }
            });
    }

    // Modal promo save
    savePromoBtn.addEventListener('click', function() {
        const formData = new FormData(promoForm);
        const action = promoIdInput.value ? 'edit' : 'add';
        formData.append('action', action);

        fetch('ajax_actions/utility_actions.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                promoModal.hide();
                fetchPromotions();
                promoForm.reset();
            } else {
                alert(data.message || 'Errore durante il salvataggio.');
            }
        });
    });

    // Delete promotion
    promoTableBody.addEventListener('click', function(e) {
        const btn = e.target.closest('.delete-btn');
        if (btn) {
            const id = btn.dataset.id;
            if (confirm('Sei sicuro di voler eliminare questa offerta?')) {
                const formData = new FormData();
                formData.append('action', 'delete');
                formData.append('id', id);

                fetch('ajax_actions/utility_actions.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        document.getElementById(`promo-${id}`).remove();
                    } else {
                        alert(data.message || 'Errore durante l\'eliminazione.');
                    }
                });
            }
        }
    });

    // Edit promotion modal fill
    promoTableBody.addEventListener('click', function(e) {
        const btn = e.target.closest('.edit-btn');
        if (btn) {
            const id = btn.dataset.id;
            fetch(`ajax_actions/utility_actions.php?action=get&id=${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        const promo = data.promotion;
                        modalTitle.textContent = 'Modifica Offerta Luce/Gas';
                        promoIdInput.value = promo.id;
                        document.getElementById('partner_id').value = promo.partner_id;
                        document.getElementById('plan_name').value = promo.plan_name;
                        document.getElementById('utility_type').value = promo.utility_type;
                        document.getElementById('price').value = promo.price;
                        document.getElementById('price_detail').value = promo.price_detail;
                        document.getElementById('features').value = promo.features;
                        document.getElementById('is_featured').checked = promo.is_featured == 1;
                        document.getElementById('status').value = promo.status;
                        
                        promoModal.show();
                    }
                });
        }
    });

    // Modal partner save
    savePartnerBtn.addEventListener('click', function() {
        const formData = new FormData(partnerForm);
        const action = partnerIdInput.value ? 'edit_partner' : 'add_partner';
        formData.append('action', action);

        fetch('ajax_actions/utility_actions.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                partnerModal.hide();
                fetchPartners();
                partnerForm.reset();
            } else {
                alert(data.message || 'Errore durante il salvataggio.');
            }
        });
    });

    // Edit partner modal fill
    partnerTableBody.addEventListener('click', function(e) {
        const btn = e.target.closest('.edit-partner-btn');
        if (btn) {
            const id = btn.dataset.id;
            fetch(`ajax_actions/utility_actions.php?action=get_partner&id=${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        const partner = data.partner;
                        partnerModalTitle.textContent = 'Modifica Partner';
                        partnerIdInput.value = partner.id;
                        document.getElementById('partner_name').value = partner.name;
                        document.getElementById('partner_description').value = partner.description || '';
                        document.getElementById('partner_icon_class').value = partner.icon_class;
                        document.getElementById('partner_icon_color').value = partner.icon_color;
                        document.getElementById('partner_sort_order').value = partner.sort_order;
                        document.getElementById('partner_status').value = partner.status;
                        
                        // Show current logo if present
                        const logoPreview = document.getElementById('partner-logo-current');
                        if (partner.logo_path) {
                            logoPreview.innerHTML = `<img src="../${partner.logo_path}" class="preview-thumbnail mt-2">`;
                        } else {
                            logoPreview.innerHTML = '';
                        }
                        
                        partnerModal.show();
                    }
                });
        }
    });

    // Delete partner
    partnerTableBody.addEventListener('click', function(e) {
        const btn = e.target.closest('.delete-partner-btn');
        if (btn) {
            const id = btn.dataset.id;
            if (confirm('Sei sicuro di voler eliminare questo partner? Cosi eliminerai anche le sue promozioni.')) {
                const formData = new FormData();
                formData.append('action', 'delete_partner');
                formData.append('id', id);

                fetch('ajax_actions/utility_actions.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        document.getElementById(`partner-${id}`).remove();
                        fetchPartners();
                        fetchPromotions();
                    } else {
                        alert(data.message || 'Errore durante l\'eliminazione.');
                    }
                });
            }
        }
    });

    // Update partner icon preview in form
    document.getElementById('partner_icon_class').addEventListener('input', function(e) {
        document.getElementById('partnerIconPreview').className = e.target.value;
    });

    function resetForm() {
        modalTitle.textContent = 'Nuova Offerta Luce/Gas';
        promoIdInput.value = '';
        promoForm.reset();
        document.getElementById('price_detail').value = '/mese';
    }

    function resetPartnerForm() {
        partnerModalTitle.textContent = 'Nuovo Brand Partner';
        partnerIdInput.value = '';
        partnerForm.reset();
        document.getElementById('partnerIconPreview').className = 'ri-flashlight-line';
        document.getElementById('partner_icon_class').value = 'ri-flashlight-line';
        document.getElementById('partner_icon_color').value = 'var(--ks-orange)';
        document.getElementById('partner-logo-current').innerHTML = '';
    }

    // Initial load
    fetchPromotions();
    fetchPartners();
    fetchRequests();
});
</script>
