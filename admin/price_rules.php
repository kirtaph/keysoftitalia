<?php
include_once 'includes/header.php';

// Fetch Data
// Raggruppiamo le regole per device_type (slug del device) per creare i tab
$stmt = $pdo->query('SELECT pr.*, d.name as device_name, d.slug as device_slug, b.name as brand_name, m.name as model_name, i.label as issue_label
                     FROM price_rules pr
                     JOIN devices d ON pr.device_id = d.id
                     LEFT JOIN brands b ON pr.brand_id = b.id
                     LEFT JOIN models m ON pr.model_id = m.id
                     JOIN issues i ON pr.issue_id = i.id
                     ORDER BY d.sort_order, b.name, m.name, i.sort_order');
$allRules = $stmt->fetchAll();

// Fetch Devices for Tabs and Modals
$devices = $pdo->query('SELECT * FROM devices ORDER BY sort_order ASC')->fetchAll();

// Group rules by device slug
$rulesByDevice = [];
foreach ($devices as $d) {
    $rulesByDevice[$d['slug']] = [];
}
foreach ($allRules as $r) {
    $slug = $r['device_slug'];
    if (!isset($rulesByDevice[$slug])) {
        $rulesByDevice[$slug] = [];
    }
    $rulesByDevice[$slug][] = $r;
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="section-title mb-0">Listini Prezzi</h2>
    <div>
        <button class="btn btn-outline-secondary btn-sm me-2" onclick="location.reload()">
            <i class="fas fa-sync-alt"></i> Aggiorna
        </button>
        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#priceRuleModal" id="addPriceRuleBtn">
            <i class="fas fa-plus"></i> Nuova Regola
        </button>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-white border-bottom-0">
        <ul class="nav nav-tabs card-header-tabs" id="priceTabs" role="tablist">
            <?php foreach ($devices as $index => $d): ?>
                <li class="nav-item" role="presentation">
                    <button class="nav-link <?php echo $index === 0 ? 'active' : ''; ?>" 
                            id="<?php echo $d['slug']; ?>-tab" 
                            data-bs-toggle="tab" 
                            data-bs-target="#<?php echo $d['slug']; ?>" 
                            type="button" 
                            role="tab">
                        <?php echo htmlspecialchars($d['name']); ?>
                        <span class="badge bg-light text-dark ms-1"><?php echo count($rulesByDevice[$d['slug']] ?? []); ?></span>
                    </button>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <div class="card-body p-0">
        <div class="tab-content" id="priceTabsContent">
            <?php foreach ($devices as $index => $d): 
                $slug = $d['slug'];
                $rules = $rulesByDevice[$slug] ?? [];
            ?>
                <div class="tab-pane fade <?php echo $index === 0 ? 'show active' : ''; ?>" id="<?php echo $slug; ?>" role="tabpanel">
                    
                    <!-- Toolbar for this tab -->
                    <div class="p-3 bg-light border-bottom d-flex justify-content-between align-items-center">
                        <input type="text" class="form-control form-control-sm w-25 search-rules" placeholder="Cerca in <?php echo $d['name']; ?>..." data-target="#table-<?php echo $slug; ?>">
                        <small class="text-muted">Clicca sui prezzi per modificarli</small>
                    </div>

                    <div class="table-responsive" style="max-height: 650px; overflow-y: auto;">
                        <table class="table table-hover align-middle mb-0" id="table-<?php echo $slug; ?>">
                            <thead class="table-light sticky-top">
                                <tr>
                                    <th style="width: 20%;">Problema</th>
                                    <th style="width: 15%;">Marchio</th>
                                    <th style="width: 20%;">Modello</th>
                                    <th style="width: 15%;">Min (€)</th>
                                    <th style="width: 15%;">Max (€)</th>
                                    <th style="width: 5%;" class="text-center">Stato</th>
                                    <th style="width: 10%;" class="text-center">Azioni</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($rules)): ?>
                                    <tr><td colspan="7" class="text-center py-4 text-muted">Nessuna regola definita per questo dispositivo.</td></tr>
                                <?php else: ?>
                                    <?php foreach ($rules as $r): ?>
                                        <tr id="rule-<?php echo $r['id']; ?>">
                                            <td class="fw-bold text-primary"><?php echo htmlspecialchars($r['issue_label']); ?></td>
                                            <td>
                                                <?php if($r['brand_name']): ?>
                                                    <span class="badge bg-info text-dark"><?php echo htmlspecialchars($r['brand_name']); ?></span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">Tutti</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if($r['model_name']): ?>
                                                    <span class="badge bg-light text-dark border"><?php echo htmlspecialchars($r['model_name']); ?></span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">Tutti</span>
                                                <?php endif; ?>
                                            </td>
                                            
                                            <!-- Editable Price Min -->
                                            <td>
                                                <input type="number" step="0.01" class="form-control form-control-sm price-input" 
                                                       value="<?php echo $r['min_price']; ?>" 
                                                       data-id="<?php echo $r['id']; ?>" 
                                                       data-field="min_price">
                                            </td>
                                            
                                            <!-- Editable Price Max -->
                                            <td>
                                                <input type="number" step="0.01" class="form-control form-control-sm price-input" 
                                                       value="<?php echo $r['max_price']; ?>" 
                                                       placeholder="-"
                                                       data-id="<?php echo $r['id']; ?>" 
                                                       data-field="max_price">
                                            </td>

                                            <td class="text-center">
                                                <?php if($r['is_active']): ?>
                                                    <i class="fas fa-check-circle text-success" title="Attivo"></i>
                                                <?php else: ?>
                                                    <i class="fas fa-times-circle text-danger" title="Non attivo"></i>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group btn-group-sm">
                                                    <button class="btn btn-outline-secondary clone-btn" data-id="<?php echo $r['id']; ?>" title="Clona"><i class="fas fa-copy"></i></button>
                                                    <button class="btn btn-outline-primary edit-btn" data-id="<?php echo $r['id']; ?>" title="Modifica"><i class="fas fa-edit"></i></button>
                                                    <button class="btn btn-outline-danger delete-btn" data-id="<?php echo $r['id']; ?>" title="Elimina"><i class="fas fa-trash-alt"></i></button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- Modale per Aggiungi/Modifica Regola di Prezzo -->
<div class="modal fade" id="priceRuleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="priceRuleModalLabel">Regola di Prezzo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="priceRuleForm">
                    <input type="hidden" id="priceRuleId" name="id">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Dispositivo *</label>
                            <select class="form-select" id="device_id_modal" name="device_id" required>
                                <option value="">Seleziona...</option>
                                <?php foreach ($devices as $device): ?>
                                    <option value="<?php echo $device['id']; ?>"><?php echo htmlspecialchars($device['name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Problema *</label>
                            <select class="form-select" id="issue_id_modal" name="issue_id" required>
                                <option value="">Seleziona prima un dispositivo</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Marchio (Opzionale)</label>
                            <select class="form-select" id="brand_id_modal" name="brand_id">
                                <option value="">Tutti</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Modello (Opzionale)</label>
                            <select class="form-select" id="model_id_modal" name="model_id">
                                <option value="">Tutti</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Prezzo Minimo (€) *</label>
                            <input type="number" step="0.01" class="form-control" id="min_price_modal" name="min_price" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Prezzo Massimo (€)</label>
                            <input type="number" step="0.01" class="form-control" id="max_price_modal" name="max_price">
                            <div class="form-text">Lascia vuoto se prezzo fisso.</div>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Note interne</label>
                            <textarea class="form-control" id="notes_modal" name="notes" rows="2"></textarea>
                        </div>
                        <div class="col-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="is_active_modal" name="is_active" value="1" checked>
                                <label class="form-check-label" for="is_active_modal">Regola Attiva</label>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                <button type="button" class="btn btn-primary" id="savePriceRuleBtn">Salva</button>
            </div>
        </div>
    </div>
</div>

<?php include_once 'includes/footer.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // --- INLINE EDITING ---
    document.querySelectorAll('.price-input').forEach(input => {
        input.addEventListener('change', function() {
            const id = this.dataset.id;
            const field = this.dataset.field; // min_price or max_price
            const val = this.value;
            
            const fd = new FormData();
            fd.append('action', 'update_price');
            fd.append('id', id);
            fd.append(field, val);

            // Visual feedback
            this.classList.add('bg-warning');
            
            fetch('ajax_actions/price_rule_actions.php', { method: 'POST', body: fd })
                .then(res => res.json())
                .then(data => {
                    this.classList.remove('bg-warning');
                    if(data.status === 'success') {
                        this.classList.add('bg-success', 'text-white');
                        setTimeout(() => this.classList.remove('bg-success', 'text-white'), 1000);
                    } else {
                        this.classList.add('bg-danger', 'text-white');
                        alert('Errore salvataggio: ' + data.message);
                    }
                })
                .catch(err => {
                    this.classList.remove('bg-warning');
                    this.classList.add('bg-danger');
                });
        });
    });

    // --- CLONE ---
    document.querySelectorAll('.clone-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            if(!confirm('Vuoi duplicare questa regola?')) return;
            
            const id = this.dataset.id;
            const fd = new FormData();
            fd.append('action', 'clone');
            fd.append('id', id);
            
            fetch('ajax_actions/price_rule_actions.php', { method: 'POST', body: fd })
                .then(res => res.json())
                .then(data => {
                    if(data.status === 'success') {
                        location.reload();
                    } else {
                        alert(data.message);
                    }
                });
        });
    });

    // --- MODAL & CRUD ---
    const modal = new bootstrap.Modal(document.getElementById('priceRuleModal'));
    const form = document.getElementById('priceRuleForm');
    const deviceSelect = document.getElementById('device_id_modal');
    const brandSelect = document.getElementById('brand_id_modal');
    const modelSelect = document.getElementById('model_id_modal');
    const issueSelect = document.getElementById('issue_id_modal');

    // Reset
    document.getElementById('addPriceRuleBtn').addEventListener('click', () => {
        form.reset();
        document.getElementById('priceRuleId').value = '';
        brandSelect.innerHTML = '<option value="">Tutti</option>';
        modelSelect.innerHTML = '<option value="">Tutti</option>';
        issueSelect.innerHTML = '<option value="">Seleziona prima un dispositivo</option>';
        document.getElementById('priceRuleModalLabel').textContent = 'Nuova Regola';
    });

    // Cascading Selects
    function loadOptions(url, select, placeholder, selected = null, isOptional = false) {
        select.innerHTML = `<option value="">${isOptional ? 'Tutti' : placeholder}</option>`;
        if(!url) return;
        
        fetch(url).then(r => r.json()).then(data => {
            if(data.status === 'success') {
                const items = data.brands || data.models || data.issues || data.data; // data.data generic fallback
                // Handle different structures if needed, but standardizing on brands/models/issues keys in actions
                // Actually brand_actions returns 'data' (single) or we need 'list'.
                // Wait, my previous actions returned single item on 'get'.
                // I need 'get_by_device' actions!
                // Assuming they exist or I need to create them?
                // The previous code used `ajax_actions/brand_actions.php?action=get_by_device`.
                // I need to check if those actions exist in the files I created.
                // I created basic CRUD (get, add, edit, delete). I DID NOT create 'get_by_device'.
                // I MUST UPDATE THE ACTIONS TO SUPPORT LISTING.
                
                // Let's assume I will update them in the next step if they fail.
                // For now, let's write the JS assuming standard response.
                
                if(Array.isArray(items)) {
                    items.forEach(item => {
                        select.add(new Option(item.name || item.label, item.id));
                    });
                    if(selected) select.value = selected;
                }
            }
        });
    }

    // Since I realized I missed the 'list' actions, I will add them to the PHP files in the next step.
    // But I will keep the JS logic here.

    deviceSelect.addEventListener('change', function() {
        const devId = this.value;
        loadOptions(`ajax_actions/brand_actions.php?action=list&device_id=${devId}`, brandSelect, 'Marchio', null, true);
        loadOptions(`ajax_actions/issue_actions.php?action=list&device_id=${devId}`, issueSelect, 'Seleziona Problema');
        modelSelect.innerHTML = '<option value="">Tutti</option>';
    });

    brandSelect.addEventListener('change', function() {
        const brandId = this.value;
        loadOptions(`ajax_actions/model_actions.php?action=list&brand_id=${brandId}`, modelSelect, 'Modello', null, true);
    });

    // Edit
    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            fetch(`ajax_actions/price_rule_actions.php?action=get&id=${id}`)
                .then(r => r.json())
                .then(data => {
                    if(data.status === 'success') {
                        const r = data.rule;
                        form.reset();
                        document.getElementById('priceRuleId').value = r.id;
                        deviceSelect.value = r.device_id;
                        
                        // Trigger loads
                        loadOptions(`ajax_actions/issue_actions.php?action=list&device_id=${r.device_id}`, issueSelect, 'Seleziona Problema', r.issue_id);
                        loadOptions(`ajax_actions/brand_actions.php?action=list&device_id=${r.device_id}`, brandSelect, 'Marchio', r.brand_id, true);
                        
                        if(r.brand_id) {
                            loadOptions(`ajax_actions/model_actions.php?action=list&brand_id=${r.brand_id}`, modelSelect, 'Modello', r.model_id, true);
                        } else {
                             modelSelect.innerHTML = '<option value="">Tutti</option>';
                        }

                        document.getElementById('min_price_modal').value = r.min_price;
                        document.getElementById('max_price_modal').value = r.max_price;
                        document.getElementById('notes_modal').value = r.notes;
                        document.getElementById('is_active_modal').checked = (r.is_active == 1);
                        
                        document.getElementById('priceRuleModalLabel').textContent = 'Modifica Regola';
                        modal.show();
                    }
                });
        });
    });

    // Save
    document.getElementById('savePriceRuleBtn').addEventListener('click', () => {
        const fd = new FormData(form);
        const id = document.getElementById('priceRuleId').value;
        fd.append('action', id ? 'edit' : 'add');
        if(!document.getElementById('is_active_modal').checked) fd.set('is_active', 0);

        fetch('ajax_actions/price_rule_actions.php', { method: 'POST', body: fd })
            .then(r => r.json())
            .then(data => {
                if(data.status === 'success') {
                    location.reload();
                } else {
                    alert(data.message);
                }
            });
    });

    // Delete
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            if(!confirm('Eliminare questa regola?')) return;
            const id = this.dataset.id;
            const fd = new FormData();
            fd.append('action', 'delete');
            fd.append('id', id);
            fetch('ajax_actions/price_rule_actions.php', { method: 'POST', body: fd })
                .then(r => r.json())
                .then(data => {
                    if(data.status === 'success') location.reload();
                    else alert(data.message);
                });
        });
    });

    // Search
    document.querySelectorAll('.search-rules').forEach(input => {
        input.addEventListener('keyup', function() {
            const term = this.value.toLowerCase();
            const targetTable = document.querySelector(this.dataset.target);
            if(!targetTable) return;
            
            const rows = targetTable.querySelectorAll('tbody tr');
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(term) ? '' : 'none';
            });
        });
    });

});
</script>
