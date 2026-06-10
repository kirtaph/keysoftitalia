<?php
include_once 'includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="section-title"><i class="fas fa-code me-2 text-primary"></i>Gestione Sviluppo Web</h2>
    <div id="actionBtnArea">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#packageModal" id="addPackageBtn">
            <i class="fas fa-plus"></i> Aggiungi Pacchetto
        </button>
    </div>
</div>

<!-- Tabs Control -->
<ul class="nav nav-tabs mb-4" id="webDevTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active fw-bold" id="packages-tab" data-bs-toggle="tab" data-bs-target="#packages-panel" type="button" role="tab" aria-selected="true">
            <i class="fas fa-box me-1"></i> Pacchetti & Listino
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link fw-bold" id="showcase-tab" data-bs-toggle="tab" data-bs-target="#showcase-panel" type="button" role="tab" aria-selected="false">
            <i class="fas fa-laptop-code me-1"></i> Showcase Portfolio
        </button>
    </li>
</ul>

<div class="tab-content" id="webDevTabsContent">
    <!-- PANEL 1: PACKAGES -->
    <div class="tab-pane fade show active" id="packages-panel" role="tabpanel" aria-labelledby="packages-tab">
        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 60px;">Ord.</th>
                                <th>Nome Pacchetto</th>
                                <th>Prezzo</th>
                                <th>Sottotitolo/Target</th>
                                <th>Servizi Inclusi</th>
                                <th class="text-center">Consigliato</th>
                                <th class="text-center">Stato</th>
                                <th class="text-center" style="width: 120px;">Azioni</th>
                            </tr>
                        </thead>
                        <tbody id="packageTableBody">
                            <!-- Loaded via AJAX -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- PANEL 2: SHOWCASE PORTFOLIO -->
    <div class="tab-pane fade" id="showcase-panel" role="tabpanel" aria-labelledby="showcase-tab">
        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 60px;">Ord.</th>
                                <th style="width: 100px;">Anteprima</th>
                                <th>Nome Progetto</th>
                                <th>Descrizione</th>
                                <th>Tecnologie Usate</th>
                                <th>Link Live</th>
                                <th class="text-center">Stato</th>
                                <th class="text-center" style="width: 120px;">Azioni</th>
                            </tr>
                        </thead>
                        <tbody id="showcaseTableBody">
                            <!-- Loaded via AJAX -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Add/Edit Package -->
<div class="modal fade" id="packageModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="packageModalLabel">Nuovo Pacchetto</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="packageForm">
                    <input type="hidden" id="packageId" name="id">
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Nome Pacchetto *</label>
                            <input type="text" class="form-control" id="pack_title" name="title" required placeholder="es. Sito Vetrina, E-commerce Base">
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Sottotitolo / Breve desc.</label>
                            <input type="text" class="form-control" id="pack_subtitle" name="subtitle" placeholder="es. Ottimo per liberi professionisti">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">Prezzo (€)</label>
                            <input type="number" step="0.01" class="form-control" id="pack_price" name="price" placeholder="es. 499 (lascia vuoto se su misura)">
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Dettaglio Prezzo</label>
                            <input type="text" class="form-control" id="pack_price_detail" name="price_detail" placeholder="es. una tantum, a partire da">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">Ordinamento</label>
                            <input type="number" class="form-control" id="pack_sort" name="sort_order" value="0">
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-bold">Caratteristiche Incluse (una per riga)</label>
                            <textarea class="form-control" id="pack_features" name="features" rows="5" placeholder="es. Design personalizzato&#10;Fino a 5 pagine&#10;Mobile responsive&#10;Ottimizzazione SEO base"></textarea>
                            <small class="text-muted">Inserisci ciascun servizio andando a capo.</small>
                        </div>

                        <div class="col-md-6 d-flex align-items-center">
                            <div class="form-check form-switch mt-3">
                                <input class="form-check-input" type="checkbox" role="switch" id="pack_is_featured" name="is_featured">
                                <label class="form-check-label fw-bold" for="pack_is_featured">Mostra come Consigliato</label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Stato Pubblicazione *</label>
                            <select class="form-select" id="pack_status" name="status" required>
                                <option value="1">Pubblicato</option>
                                <option value="0">Bozza</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                <button type="button" class="btn btn-primary" id="savePackageBtn"><i class="fas fa-save me-1"></i> Salva Pacchetto</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Add/Edit Showcase Progetto -->
<div class="modal fade" id="showcaseModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="showcaseModalLabel">Nuovo Progetto Showcase</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="showcaseForm" enctype="multipart/form-data">
                    <input type="hidden" id="showcaseId" name="id">
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Nome Progetto / Cliente *</label>
                            <input type="text" class="form-control" id="show_title" name="title" required placeholder="es. Fashion Store, Studio Legale Rossi">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Tecnologie Usate *</label>
                            <input type="text" class="form-control" id="show_tech" name="technologies" required placeholder="es. WordPress, WooCommerce, Stripe">
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-bold">Breve Descrizione *</label>
                            <input type="text" class="form-control" id="show_desc" name="description" required placeholder="es. Portale annunci immobiliari con ricerca su mappa">
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Link Progetto Live (Opzionale)</label>
                            <input type="url" class="form-control" id="show_url" name="project_url" placeholder="https://example.com">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-bold">Ordinamento</label>
                            <input type="number" class="form-control" id="show_sort" name="sort_order" value="0">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-bold">Stato *</label>
                            <select class="form-select" id="show_status" name="status" required>
                                <option value="1">Pubblicato</option>
                                <option value="0">Bozza</option>
                            </select>
                        </div>
                        
                        <div class="col-md-12">
                            <label class="form-label fw-bold">Screenshot Progetto (Mockup)</label>
                            <input type="file" class="form-control" id="image_file" name="image_file" accept="image/*">
                            <div class="form-text">Carica un'immagine del sito sviluppato. Verrà mostrato come mockup.</div>
                            <div id="preview-screenshot" class="mt-2"></div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                <button type="button" class="btn btn-primary" id="saveShowcaseBtn"><i class="fas fa-save me-1"></i> Salva Progetto</button>
            </div>
        </div>
    </div>
</div>

<style>
.preview-thumbnail {
    max-height: 120px;
    max-width: 200px;
    border-radius: 6px;
    border: 1px solid #dee2e6;
    object-fit: cover;
    background: #f8f9fa;
    padding: 3px;
}
</style>

<?php include_once 'includes/footer.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const packageModal = new bootstrap.Modal(document.getElementById('packageModal'));
    const showcaseModal = new bootstrap.Modal(document.getElementById('showcaseModal'));
    const actionBtnArea = document.getElementById('actionBtnArea');
    
    // Tab switching controls button area
    document.getElementById('packages-tab').addEventListener('shown.bs.tab', function() {
        actionBtnArea.innerHTML = `
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#packageModal" id="addPackageBtn">
                <i class="fas fa-plus"></i> Aggiungi Pacchetto
            </button>
        `;
        document.getElementById('addPackageBtn').addEventListener('click', resetPackageForm);
    });

    document.getElementById('showcase-tab').addEventListener('shown.bs.tab', function() {
        actionBtnArea.innerHTML = `
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#showcaseModal" id="addShowcaseBtn">
                <i class="fas fa-plus"></i> Aggiungi Progetto
            </button>
        `;
        document.getElementById('addShowcaseBtn').addEventListener('click', resetShowcaseForm);
        fetchShowcases();
    });

    // ==========================================
    // PACKAGES LOGIC
    // ==========================================
    const packageTableBody = document.getElementById('packageTableBody');
    const savePackageBtn = document.getElementById('savePackageBtn');
    const packageForm = document.getElementById('packageForm');

    function fetchPackages() {
        fetch('ajax_actions/web_dev_actions.php?action=list_packages')
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    packageTableBody.innerHTML = '';
                    if (data.packages.length === 0) {
                        packageTableBody.innerHTML = '<tr><td colspan="8" class="text-center py-4 text-muted">Nessun pacchetto listino inserito.</td></tr>';
                        return;
                    }
                    data.packages.forEach(pack => {
                        const priceFormatted = pack.price 
                            ? `€${parseFloat(pack.price).toFixed(2)}${pack.price_detail}`
                            : `${pack.price_detail || 'Su misura'}`;

                        const featuresList = pack.features
                            ? pack.features.split('\n').filter(line => line.trim() !== '').slice(0, 3).map(line => `• ${line.trim()}`).join('<br>') + (pack.features.split('\n').length > 3 ? '...' : '')
                            : '<span class="text-muted">-</span>';

                        const featuredIcon = pack.is_featured == 1
                            ? '<i class="fas fa-star text-warning fa-lg" title="Consigliato"></i>'
                            : '<i class="far fa-star text-muted fa-lg" style="opacity: 0.3;"></i>';

                        const statusBadge = pack.status == 1 
                            ? '<span class="badge bg-success"><i class="fas fa-check me-1"></i>Attivo</span>' 
                            : '<span class="badge bg-secondary"><i class="fas fa-pen me-1"></i>Bozza</span>';

                        const row = `
                            <tr id="package-${pack.id}">
                                <td class="text-muted fw-bold">${pack.sort_order}</td>
                                <td><span class="fw-bold text-dark">${pack.title}</span></td>
                                <td class="fw-bold text-primary">${priceFormatted}</td>
                                <td class="text-muted small">${pack.subtitle || '-'}</td>
                                <td class="text-muted" style="font-size: 0.85rem;">${featuresList}</td>
                                <td class="text-center">${featuredIcon}</td>
                                <td class="text-center">${statusBadge}</td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-outline-primary edit-pack-btn me-1" data-id="${pack.id}" title="Modifica"><i class="fas fa-edit"></i></button>
                                    <button class="btn btn-sm btn-outline-danger delete-pack-btn" data-id="${pack.id}" title="Elimina"><i class="fas fa-trash-alt"></i></button>
                                </td>
                            </tr>
                        `;
                        packageTableBody.insertAdjacentHTML('beforeend', row);
                    });
                }
            });
    }

    function resetPackageForm() {
        packageForm.reset();
        document.getElementById('packageId').value = '';
        document.getElementById('packageModalLabel').textContent = 'Nuovo Pacchetto Listino';
    }

    packageTableBody.addEventListener('click', function(e) {
        const editBtn = e.target.closest('.edit-pack-btn');
        if (editBtn) {
            const id = editBtn.dataset.id;
            fetch(`ajax_actions/web_dev_actions.php?action=get_package&id=${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        const p = data.package;
                        document.getElementById('packageId').value = p.id;
                        document.getElementById('pack_title').value = p.title;
                        document.getElementById('pack_subtitle').value = p.subtitle || '';
                        document.getElementById('pack_price').value = p.price || '';
                        document.getElementById('pack_price_detail').value = p.price_detail || '';
                        document.getElementById('pack_sort').value = p.sort_order;
                        document.getElementById('pack_features').value = p.features || '';
                        document.getElementById('pack_status').value = p.status;
                        document.getElementById('pack_is_featured').checked = !!parseInt(p.is_featured);

                        document.getElementById('packageModalLabel').textContent = 'Modifica Pacchetto Listino';
                        packageModal.show();
                    }
                });
        }
    });

    packageTableBody.addEventListener('click', function(e) {
        const deleteBtn = e.target.closest('.delete-pack-btn');
        if (deleteBtn) {
            const id = deleteBtn.dataset.id;
            if (confirm('Sei sicuro di voler eliminare questo pacchetto?')) {
                const formData = new FormData();
                formData.append('action', 'delete_package');
                formData.append('id', id);

                fetch('ajax_actions/web_dev_actions.php', { method: 'POST', body: formData })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            fetchPackages();
                        } else {
                            alert(data.message || 'Errore durante l\'eliminazione.');
                        }
                    });
            }
        }
    });

    savePackageBtn.addEventListener('click', function() {
        if (!packageForm.checkValidity()) {
            packageForm.reportValidity();
            return;
        }
        
        const formData = new FormData(packageForm);
        formData.append('action', 'save_package');
        
        fetch('ajax_actions/web_dev_actions.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                packageModal.hide();
                fetchPackages();
            } else {
                alert(data.message || 'Si è verificato un errore.');
            }
        });
    });


    // ==========================================
    // SHOWCASE LOGIC
    // ==========================================
    const showcaseTableBody = document.getElementById('showcaseTableBody');
    const saveShowcaseBtn = document.getElementById('saveShowcaseBtn');
    const showcaseForm = document.getElementById('showcaseForm');

    function fetchShowcases() {
        fetch('ajax_actions/web_dev_actions.php?action=list_showcase')
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    showcaseTableBody.innerHTML = '';
                    if (data.showcase.length === 0) {
                        showcaseTableBody.innerHTML = '<tr><td colspan="8" class="text-center py-4 text-muted">Nessun progetto in showcase inserito.</td></tr>';
                        return;
                    }
                    data.showcase.forEach(item => {
                        const imageHtml = item.image_path 
                            ? `<img src="../${item.image_path}" class="rounded border p-1" style="width: 80px; height: 50px; object-fit: cover; background: #fff;">`
                            : `<div class="bg-light rounded border d-flex align-items-center justify-content-center text-muted" style="width: 80px; height: 50px;"><i class="fas fa-laptop"></i></div>`;

                        const statusBadge = item.status == 1 
                            ? '<span class="badge bg-success"><i class="fas fa-check me-1"></i>Attivo</span>' 
                            : '<span class="badge bg-secondary"><i class="fas fa-pen me-1"></i>Bozza</span>';

                        const linkHtml = item.project_url
                            ? `<a href="${item.project_url}" target="_blank" class="btn btn-sm btn-outline-info"><i class="fas fa-external-link-alt"></i></a>`
                            : '<span class="text-muted">-</span>';

                        const row = `
                            <tr id="showcase-${item.id}">
                                <td class="text-muted fw-bold">${item.sort_order}</td>
                                <td>${imageHtml}</td>
                                <td><span class="fw-bold text-dark">${item.title}</span></td>
                                <td class="text-muted small">${item.description}</td>
                                <td><span class="badge bg-light text-dark border">${item.technologies}</span></td>
                                <td>${linkHtml}</td>
                                <td class="text-center">${statusBadge}</td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-outline-primary edit-showcase-btn me-1" data-id="${item.id}" title="Modifica"><i class="fas fa-edit"></i></button>
                                    <button class="btn btn-sm btn-outline-danger delete-showcase-btn" data-id="${item.id}" title="Elimina"><i class="fas fa-trash-alt"></i></button>
                                </td>
                            </tr>
                        `;
                        showcaseTableBody.insertAdjacentHTML('beforeend', row);
                    });
                }
            });
    }

    function resetShowcaseForm() {
        showcaseForm.reset();
        document.getElementById('showcaseId').value = '';
        document.getElementById('preview-screenshot').innerHTML = '';
        document.getElementById('showcaseModalLabel').textContent = 'Nuovo Progetto Showcase';
    }

    showcaseTableBody.addEventListener('click', function(e) {
        const editBtn = e.target.closest('.edit-showcase-btn');
        if (editBtn) {
            const id = editBtn.dataset.id;
            fetch(`ajax_actions/web_dev_actions.php?action=get_showcase&id=${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        const s = data.showcase;
                        document.getElementById('showcaseId').value = s.id;
                        document.getElementById('show_title').value = s.title;
                        document.getElementById('show_desc').value = s.description;
                        document.getElementById('show_tech').value = s.technologies;
                        document.getElementById('show_url').value = s.project_url || '';
                        document.getElementById('show_sort').value = s.sort_order;
                        document.getElementById('show_status').value = s.status;
                        
                        document.getElementById('preview-screenshot').innerHTML = s.image_path 
                            ? `<img src="../${s.image_path}" class="preview-thumbnail mt-2">` 
                            : '';

                        document.getElementById('showcaseModalLabel').textContent = 'Modifica Progetto Showcase';
                        showcaseModal.show();
                    }
                });
        }
    });

    showcaseTableBody.addEventListener('click', function(e) {
        const deleteBtn = e.target.closest('.delete-showcase-btn');
        if (deleteBtn) {
            const id = deleteBtn.dataset.id;
            if (confirm('Sei sicuro di voler eliminare questo progetto?')) {
                const formData = new FormData();
                formData.append('action', 'delete_showcase');
                formData.append('id', id);

                fetch('ajax_actions/web_dev_actions.php', { method: 'POST', body: formData })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            fetchShowcases();
                        } else {
                            alert(data.message || 'Errore durante l\'eliminazione.');
                        }
                    });
            }
        }
    });

    saveShowcaseBtn.addEventListener('click', function() {
        if (!showcaseForm.checkValidity()) {
            showcaseForm.reportValidity();
            return;
        }
        
        const formData = new FormData(showcaseForm);
        formData.append('action', 'save_showcase');
        
        fetch('ajax_actions/web_dev_actions.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                showcaseModal.hide();
                fetchShowcases();
            } else {
                alert(data.message || 'Si è verificato un errore.');
            }
        });
    });

    // Initial load for Tab 1
    fetchPackages();
});
</script>
