<?php
include_once 'includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="section-title"><i class="fas fa-network-wired me-2 text-primary"></i>Gestione Consulenza IT</h2>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#packageModal" id="addPackageBtn">
        <i class="fas fa-plus"></i> Aggiungi Pacchetto
    </button>
</div>

<!-- Packages List -->
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 60px;">Ord.</th>
                        <th>Nome Pacchetto</th>
                        <th>Prezzo</th>
                        <th>Sottotitolo / Destinatari</th>
                        <th>Caratteristiche Incluse</th>
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

<!-- Modal for Add/Edit Package -->
<div class="modal fade" id="packageModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="packageModalLabel">Nuovo Pacchetto Assistenza IT</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="packageForm">
                    <input type="hidden" id="packageId" name="id">
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Nome Pacchetto *</label>
                            <input type="text" class="form-control" id="pack_title" name="title" required placeholder="es. Base, Professional, Premium">
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Sottotitolo / Destinatari</label>
                            <input type="text" class="form-control" id="pack_subtitle" name="subtitle" placeholder="es. Fino a 5 postazioni, PMI">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">Prezzo (€)</label>
                            <input type="number" step="0.01" class="form-control" id="pack_price" name="price" placeholder="es. 99 (vuoto se su misura)">
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Dettaglio Prezzo</label>
                            <input type="text" class="form-control" id="pack_price_detail" name="price_detail" value="/mese" placeholder="es. /mese, una tantum">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">Ordinamento</label>
                            <input type="number" class="form-control" id="pack_sort" name="sort_order" value="0">
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-bold">Caratteristiche Incluse (una per riga)</label>
                            <textarea class="form-control" id="pack_features" name="features" rows="5" placeholder="es. Assistenza remota illimitata&#10;Fino a 5 PC coperti&#10;Backup in cloud compreso&#10;Intervento entro 24 ore"></textarea>
                            <small class="text-muted">Inserisci ciascuna caratteristica andando a capo.</small>
                        </div>

                        <div class="col-md-6 d-flex align-items-center">
                            <div class="form-check form-switch mt-3">
                                <input class="form-check-input" type="checkbox" role="switch" id="pack_is_featured" name="is_featured">
                                <label class="form-check-label fw-bold" for="pack_is_featured">Mostra in Evidenza (Consigliato)</label>
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

<?php include_once 'includes/footer.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const packageModal = new bootstrap.Modal(document.getElementById('packageModal'));
    const packageTableBody = document.getElementById('packageTableBody');
    const savePackageBtn = document.getElementById('savePackageBtn');
    const packageForm = document.getElementById('packageForm');
    const addPackageBtn = document.getElementById('addPackageBtn');

    function fetchPackages() {
        fetch('ajax_actions/it_consulting_actions.php?action=list')
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    packageTableBody.innerHTML = '';
                    if (data.packages.length === 0) {
                        packageTableBody.innerHTML = '<tr><td colspan="8" class="text-center py-4 text-muted">Nessun pacchetto di consulenza inserito.</td></tr>';
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
                                    <button class="btn btn-sm btn-outline-primary edit-btn me-1" data-id="${pack.id}" title="Modifica"><i class="fas fa-edit"></i></button>
                                    <button class="btn btn-sm btn-outline-danger delete-btn" data-id="${pack.id}" title="Elimina"><i class="fas fa-trash-alt"></i></button>
                                </td>
                            </tr>
                        `;
                        packageTableBody.insertAdjacentHTML('beforeend', row);
                    });
                }
            });
    }

    function resetForm() {
        packageForm.reset();
        document.getElementById('packageId').value = '';
        document.getElementById('packageModalLabel').textContent = 'Nuovo Pacchetto Assistenza IT';
    }

    addPackageBtn.addEventListener('click', () => {
        resetForm();
    });

    packageTableBody.addEventListener('click', function(e) {
        const editBtn = e.target.closest('.edit-btn');
        if (editBtn) {
            const id = editBtn.dataset.id;
            fetch(`ajax_actions/it_consulting_actions.php?action=get&id=${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        const p = data.package;
                        document.getElementById('packageId').value = p.id;
                        document.getElementById('pack_title').value = p.title;
                        document.getElementById('pack_subtitle').value = p.subtitle || '';
                        document.getElementById('pack_price').value = p.price || '';
                        document.getElementById('pack_price_detail').value = p.price_detail || '/mese';
                        document.getElementById('pack_sort').value = p.sort_order;
                        document.getElementById('pack_features').value = p.features || '';
                        document.getElementById('pack_status').value = p.status;
                        document.getElementById('pack_is_featured').checked = !!parseInt(p.is_featured);

                        document.getElementById('packageModalLabel').textContent = 'Modifica Pacchetto Assistenza IT';
                        packageModal.show();
                    }
                });
        }
    });

    packageTableBody.addEventListener('click', function(e) {
        const deleteBtn = e.target.closest('.delete-btn');
        if (deleteBtn) {
            const id = deleteBtn.dataset.id;
            if (confirm('Sei sicuro di voler eliminare questo pacchetto?')) {
                const formData = new FormData();
                formData.append('action', 'delete');
                formData.append('id', id);

                fetch('ajax_actions/it_consulting_actions.php', { method: 'POST', body: formData })
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
        formData.append('action', 'save');
        
        fetch('ajax_actions/it_consulting_actions.php', {
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

    fetchPackages();
});
</script>
