<?php include 'includes/header.php'; ?>

<div class="admin-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Campagne Logo</h1>
            <p class="text-muted">Gestisci loghi ed effetti dinamici in base al periodo dell'anno.</p>
        </div>
        <button class="btn btn-primary" id="addCampaignBtn" data-bs-toggle="modal" data-bs-target="#campaignModal">
            <i class="fas fa-plus me-2"></i> Nuova Campagna
        </button>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 100px;" class="text-center">Logo</th>
                            <th>Nome Campagna</th>
                            <th>Periodo</th>
                            <th>Effetto CSS</th>
                            <th class="text-center" style="width: 100px;">Stato</th>
                            <th class="text-center" style="width: 120px;">Azioni</th>
                        </tr>
                    </thead>
                    <tbody id="campaignsTableBody">
                        <!-- Caricato via AJAX -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Campagna -->
<div class="modal fade" id="campaignModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="campaignModalLabel">Nuova Campagna</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="campaignForm">
                    <input type="hidden" id="campaignId" name="id">
                    
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label fw-bold">Nome Campagna *</label>
                            <input type="text" class="form-control" id="name" name="name" required placeholder="es. Natale 2026">
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Data Inizio *</label>
                            <input type="date" class="form-control" id="start_date" name="start_date" required>
                            <div class="form-text text-info"><i class="fas fa-redo-alt me-1"></i> Ricorrente (solo giorno/mese)</div>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Data Fine *</label>
                            <input type="date" class="form-control" id="end_date" name="end_date" required>
                            <div class="form-text text-info"><i class="fas fa-redo-alt me-1"></i> Ricorrente (solo giorno/mese)</div>
                        </div>
                        
                        <div class="col-md-12">
                            <label class="form-label fw-bold">Immagine Logo *</label>
                            <input type="file" class="form-control" id="logo_file" name="logo_file" accept="image/*">
                            <div class="form-text">Il logo da mostrare in questo periodo (es. logo con cappello di Babbo Natale).</div>
                            <div id="preview-logo" class="mt-2"></div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Effetto CSS Visivo</label>
                            <select class="form-select" id="effect_class" name="effect_class">
                                <option value="">Nessun effetto</option>
                                <option value="theme-xmas">Neve (Natale)</option>
                                <option value="theme-halloween">Pipistrelli (Halloween)</option>
                                <option value="theme-summer">Luce Estiva</option>
                                <option value="theme-blackfriday">Sconto / Lampeggi (Black Friday)</option>
                                <option value="theme-spring">Petali / Primavera</option>
                                <option value="theme-autumn">Foglie / Autunno</option>
                                <option value="theme-winter">Ghiaccio / Inverno</option>
                            </select>
                            <div class="form-text">Attiva animazioni globali nel sito (es. neve che cade).</div>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Stato *</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="1">Attivo</option>
                                <option value="0">Disattivato</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                <button type="button" class="btn btn-primary" id="saveBtn"><i class="fas fa-save me-1"></i> Salva</button>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tableBody = document.getElementById('campaignsTableBody');
    const modal = new bootstrap.Modal(document.getElementById('campaignModal'));
    const form = document.getElementById('campaignForm');
    const saveBtn = document.getElementById('saveBtn');

    function formatDate(dateStr) {
        if (!dateStr) return '';
        const d = new Date(dateStr);
        return d.toLocaleDateString('it-IT');
    }

    function fetchCampaigns() {
        fetch('ajax_actions/logo_actions.php?action=list')
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    tableBody.innerHTML = '';
                    if (data.campaigns.length === 0) {
                        tableBody.innerHTML = '<tr><td colspan="6" class="text-center py-4 text-muted">Nessuna campagna inserita.</td></tr>';
                        return;
                    }
                    data.campaigns.forEach(c => {
                        const statusBadge = c.status == 1 
                            ? '<span class="badge bg-success">Attivo</span>' 
                            : '<span class="badge bg-secondary">Disattivato</span>';

                        const logoHtml = c.logo_path 
                            ? `<img src="../assets/${c.logo_path}" style="max-height: 40px; max-width: 80px; object-fit: contain; background: #222; padding: 2px; border-radius: 4px;">` 
                            : '-';
                        
                        let effectLabel = c.effect_class;
                        if(effectLabel === 'theme-xmas') effectLabel = 'Neve (Natale)';
                        else if(effectLabel === 'theme-halloween') effectLabel = 'Pipistrelli (Halloween)';
                        else if(effectLabel === 'theme-summer') effectLabel = 'Luce Estiva';
                        else if(effectLabel === 'theme-blackfriday') effectLabel = 'Sconto / Lampeggi (Black Friday)';
                        else if(effectLabel === 'theme-spring') effectLabel = 'Petali (Primavera)';
                        else if(effectLabel === 'theme-autumn') effectLabel = 'Foglie (Autunno)';
                        else if(effectLabel === 'theme-winter') effectLabel = 'Ghiaccio (Inverno)';
                        else if(!effectLabel) effectLabel = '<span class="text-muted">Nessuno</span>';

                        const periodText = c.system_key === 'flyer_active'
                            ? '<span class="badge bg-info text-dark"><i class="fas fa-file-pdf me-1"></i> Quando Volantino Attivo</span>'
                            : `${formatDate(c.start_date)} - ${formatDate(c.end_date)} (Ricorrente)`;

                        const deleteBtnHtml = c.system_key 
                            ? `<button class="btn btn-sm btn-outline-secondary" disabled title="Le campagne di sistema non possono essere eliminate"><i class="fas fa-lock"></i></button>`
                            : `<button class="btn btn-sm btn-outline-danger delete-btn" data-id="${c.id}"><i class="fas fa-trash"></i></button>`;

                        const row = `
                            <tr>
                                <td class="text-center">${logoHtml}</td>
                                <td class="fw-bold">${c.name} ${c.system_key ? ' <span class="badge bg-warning text-dark">Sistema</span>' : ''}</td>
                                <td>${periodText}</td>
                                <td>${effectLabel}</td>
                                <td class="text-center">${statusBadge}</td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-outline-primary edit-btn" data-id="${c.id}"><i class="fas fa-edit"></i></button>
                                    ${deleteBtnHtml}
                                </td>
                            </tr>
                        `;
                        tableBody.insertAdjacentHTML('beforeend', row);
                    });
                }
            });
    }

    document.getElementById('addCampaignBtn').addEventListener('click', () => {
        form.reset();
        document.getElementById('campaignId').value = '';
        document.getElementById('campaignModalLabel').textContent = 'Nuova Campagna';
        document.getElementById('preview-logo').innerHTML = '';
        document.getElementById('logo_file').required = true;
        
        // Show and make date inputs required for standard campaigns
        document.getElementById('start_date').required = true;
        document.getElementById('end_date').required = true;
        document.getElementById('start_date').closest('.col-md-6').style.display = 'block';
        document.getElementById('end_date').closest('.col-md-6').style.display = 'block';
    });

    tableBody.addEventListener('click', e => {
        const editBtn = e.target.closest('.edit-btn');
        if (editBtn) {
            const id = editBtn.dataset.id;
            fetch(`ajax_actions/logo_actions.php?action=get&id=${id}`)
                .then(r => r.json())
                .then(data => {
                    if (data.status === 'success') {
                        const c = data.campaign;
                        form.reset();
                        document.getElementById('campaignId').value = c.id;
                        document.getElementById('name').value = c.name;
                        document.getElementById('start_date').value = c.start_date;
                        document.getElementById('end_date').value = c.end_date;
                        document.getElementById('effect_class').value = c.effect_class || '';
                        document.getElementById('status').value = c.status;
                        
                        if (c.logo_path) {
                            document.getElementById('preview-logo').innerHTML = `<img src="../assets/${c.logo_path}" style="max-height:60px; background:#222; padding:4px; border-radius:4px;">`;
                            document.getElementById('logo_file').required = false;
                        } else {
                            document.getElementById('preview-logo').innerHTML = '';
                            document.getElementById('logo_file').required = true;
                        }

                        // Customize UI for system campaigns
                        if (c.system_key) {
                            document.getElementById('start_date').required = false;
                            document.getElementById('end_date').required = false;
                            document.getElementById('start_date').closest('.col-md-6').style.display = 'none';
                            document.getElementById('end_date').closest('.col-md-6').style.display = 'none';
                        } else {
                            document.getElementById('start_date').required = true;
                            document.getElementById('end_date').required = true;
                            document.getElementById('start_date').closest('.col-md-6').style.display = 'block';
                            document.getElementById('end_date').closest('.col-md-6').style.display = 'block';
                        }

                        document.getElementById('campaignModalLabel').textContent = 'Modifica Campagna';
                        modal.show();
                    }
                });
        }

        const delBtn = e.target.closest('.delete-btn');
        if (delBtn) {
            if (confirm('Sei sicuro di voler eliminare questa campagna?')) {
                const fd = new FormData();
                fd.append('action', 'delete');
                fd.append('id', delBtn.dataset.id);
                fetch('ajax_actions/logo_actions.php', { method: 'POST', body: fd })
                    .then(r => r.json())
                    .then(data => {
                        if (data.status === 'success') fetchCampaigns();
                        else alert(data.message);
                    });
            }
        }
    });

    saveBtn.addEventListener('click', () => {
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }
        const fd = new FormData(form);
        fd.append('action', document.getElementById('campaignId').value ? 'edit' : 'add');
        
        fetch('ajax_actions/logo_actions.php', { method: 'POST', body: fd })
            .then(r => r.json())
            .then(data => {
                if (data.status === 'success') {
                    modal.hide();
                    fetchCampaigns();
                } else {
                    alert(data.message);
                }
            });
    });

    fetchCampaigns();
});
</script>
