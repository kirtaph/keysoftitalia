<?php
include_once 'includes/header.php';
$stmt = $pdo->query('SELECT pr.*, d.name as device_name, b.name as brand_name, m.name as model_name, i.label as issue_label
                     FROM price_rules pr
                     JOIN devices d ON pr.device_id = d.id
                     LEFT JOIN brands b ON pr.brand_id = b.id
                     LEFT JOIN models m ON pr.model_id = m.id
                     JOIN issues i ON pr.issue_id = i.id
                     ORDER BY d.sort_order, b.name, m.name, i.sort_order');
$rules = $stmt->fetchAll();
$devices_stmt = $pdo->query('SELECT * FROM devices ORDER BY sort_order ASC');
$devices = $devices_stmt->fetchAll();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="section-title">Gestione Regole di Prezzo</h2>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#priceRuleModal" id="addPriceRuleBtn">
        <i class="fas fa-plus"></i> Aggiungi Regola
    </button>
</div>

<div class="table-responsive">
    <table class="table table-striped table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Problema</th>
                <th>Dispositivo</th>
                <th>Marchio</th>
                <th>Modello</th>
                <th>Prezzo Min</th>
                <th>Prezzo Max</th>
                <th class="text-center">Attivo</th>
                <th class="text-center">Azioni</th>
            </tr>
        </thead>
        <tbody id="priceRulesTableBody">
            <?php foreach ($rules as $rule): ?>
                <tr id="rule-<?php echo $rule['id']; ?>">
                    <td><?php echo htmlspecialchars($rule['issue_label'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($rule['device_name'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($rule['brand_name'] ?? 'Tutti', ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($rule['model_name'] ?? 'Tutti', ENT_QUOTES, 'UTF-8'); ?></td>
                    <td>€ <?php echo htmlspecialchars(number_format($rule['min_price'], 2, ',', '.'), ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo $rule['max_price'] ? '€ ' . htmlspecialchars(number_format($rule['max_price'], 2, ',', '.'), ENT_QUOTES, 'UTF-8') : ''; ?></td>
                    <td class="text-center"><?php echo $rule['is_active'] ? '<i class="fas fa-check-circle text-success"></i>' : '<i class="fas fa-times-circle text-danger"></i>'; ?></td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-outline-primary edit-btn" data-id="<?php echo $rule['id']; ?>" data-bs-toggle="tooltip" title="Modifica">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-danger delete-btn" data-id="<?php echo $rule['id']; ?>" data-bs-toggle="tooltip" title="Elimina">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Modale per Aggiungi/Modifica Regola di Prezzo -->
<div class="modal fade" id="priceRuleModal" tabindex="-1" aria-labelledby="priceRuleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="priceRuleModalLabel">Aggiungi Regola di Prezzo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="priceRuleForm">
                    <input type="hidden" id="priceRuleId" name="id">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="device_id_modal" class="form-label">Dispositivo</label>
                            <select class="form-select" id="device_id_modal" name="device_id" required>
                                <option value="">Seleziona Dispositivo</option>
                                <?php foreach ($devices as $device): ?>
                                    <option value="<?php echo $device['id']; ?>"><?php echo htmlspecialchars($device['name'], ENT_QUOTES, 'UTF-8'); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="issue_id_modal" class="form-label">Problema</label>
                            <select class="form-select" id="issue_id_modal" name="issue_id" required>
                                <option value="">Seleziona prima un dispositivo</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="brand_id_modal" class="form-label">Marchio (Opzionale)</label>
                            <select class="form-select" id="brand_id_modal" name="brand_id">
                                <option value="">Tutti</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="model_id_modal" class="form-label">Modello (Opzionale)</label>
                            <select class="form-select" id="model_id_modal" name="model_id">
                                <option value="">Tutti</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="min_price_modal" class="form-label">Prezzo Minimo</label>
                            <input type="number" step="0.01" class="form-control" id="min_price_modal" name="min_price" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="max_price_modal" class="form-label">Prezzo Massimo (Opzionale)</label>
                            <input type="number" step="0.01" class="form-control" id="max_price_modal" name="max_price">
                        </div>
                        <div class="col-12 mb-3">
                            <label for="notes_modal" class="form-label">Note</label>
                            <textarea class="form-control" id="notes_modal" name="notes" rows="2"></textarea>
                        </div>
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_active_modal" name="is_active" value="1" checked>
                                <label class="form-check-label" for="is_active_modal">Attivo</label>
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
    const priceRuleModal = new bootstrap.Modal(document.getElementById('priceRuleModal'));
    const modalTitle = document.getElementById('priceRuleModalLabel');
    const priceRuleForm = document.getElementById('priceRuleForm');
    const priceRuleIdInput = document.getElementById('priceRuleId');
    const deviceSelect = document.getElementById('device_id_modal');
    const brandSelect = document.getElementById('brand_id_modal');
    const modelSelect = document.getElementById('model_id_modal');
    const issueSelect = document.getElementById('issue_id_modal');
    const isActiveCheckbox = document.getElementById('is_active_modal');

    function resetForm() {
        priceRuleForm.reset();
        priceRuleIdInput.value = '';
        brandSelect.innerHTML = '<option value="">Tutti</option>';
        modelSelect.innerHTML = '<option value="">Tutti</option>';
        issueSelect.innerHTML = '<option value="">Seleziona prima un dispositivo</option>';
        isActiveCheckbox.checked = true;
        modalTitle.textContent = 'Aggiungi Regola di Prezzo';
    }

    function loadSelectOptions(url, selectElement, placeholder, selectedValue = null, isOptional = false) {
        const placeholderText = isOptional ? `Tutti / ${placeholder}` : placeholder;
        selectElement.innerHTML = `<option value="">${placeholderText}</option>`;
        if (!url) return;

        fetch(url)
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    let items = data.brands || data.models || data.issues;
                    items.forEach(item => {
                        const option = new Option(item.name || item.label, item.id);
                        selectElement.add(option);
                    });
                    if (selectedValue) {
                        selectElement.value = selectedValue;
                    }
                }
            });
    }

    deviceSelect.addEventListener('change', function() {
        const deviceId = this.value;
        loadSelectOptions(deviceId ? `ajax_actions/brand_actions.php?action=get_by_device&device_id=${deviceId}` : null, brandSelect, 'Marchio', null, true);
        loadSelectOptions(deviceId ? `ajax_actions/issue_actions.php?action=get_by_device&device_id=${deviceId}` : null, issueSelect, 'Seleziona Problema');
        modelSelect.innerHTML = '<option value="">Tutti</option>';
    });

    brandSelect.addEventListener('change', function() {
        const brandId = this.value;
        loadSelectOptions(brandId ? `ajax_actions/model_actions.php?action=get_by_brand&brand_id=${brandId}` : null, modelSelect, 'Modello', null, true);
    });

    document.getElementById('addPriceRuleBtn').addEventListener('click', () => resetForm());

    document.getElementById('priceRulesTableBody').addEventListener('click', function(e) {
        const editBtn = e.target.closest('.edit-btn');
        if (editBtn) {
            const id = editBtn.dataset.id;
            fetch(`ajax_actions/price_rule_actions.php?action=get&id=${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        resetForm();
                        const rule = data.rule;
                        priceRuleIdInput.value = rule.id;
                        deviceSelect.value = rule.device_id;

                        // Carica e seleziona le opzioni in catena
                        const deviceId = rule.device_id;
                        loadSelectOptions(deviceId ? `ajax_actions/issue_actions.php?action=get_by_device&device_id=${deviceId}` : null, issueSelect, 'Seleziona Problema', rule.issue_id);
                        loadSelectOptions(deviceId ? `ajax_actions/brand_actions.php?action=get_by_device&device_id=${deviceId}` : null, brandSelect, 'Marchio', rule.brand_id, true);
                        if (rule.brand_id) {
                            loadSelectOptions(`ajax_actions/model_actions.php?action=get_by_brand&brand_id=${rule.brand_id}`, modelSelect, 'Modello', rule.model_id, true);
                        }

                        document.getElementById('min_price_modal').value = rule.min_price;
                        document.getElementById('max_price_modal').value = rule.max_price;
                        document.getElementById('notes_modal').value = rule.notes;
                        isActiveCheckbox.checked = !!parseInt(rule.is_active);
                        modalTitle.textContent = 'Modifica Regola di Prezzo';
                        priceRuleModal.show();
                    } else {
                        alert('Errore nel recupero dei dati.');
                    }
                });
        }
    });

    document.getElementById('savePriceRuleBtn').addEventListener('click', function() {
        const formData = new FormData(priceRuleForm);
        formData.append('action', priceRuleIdInput.value ? 'edit' : 'add');
        if (!isActiveCheckbox.checked) {
            formData.set('is_active', '0');
        }

        fetch('ajax_actions/price_rule_actions.php', { method: 'POST', body: formData })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    priceRuleModal.hide();
                    location.reload();
                } else {
                    alert(data.message || 'Si è verificato un errore.');
                }
            });
    });

    document.getElementById('priceRulesTableBody').addEventListener('click', function(e) {
        const deleteBtn = e.target.closest('.delete-btn');
        if (deleteBtn) {
            if (confirm('Sei sicuro di voler eliminare questa regola?')) {
                const id = deleteBtn.dataset.id;
                const formData = new FormData();
                formData.append('action', 'delete');
                formData.append('id', id);

                fetch('ajax_actions/price_rule_actions.php', { method: 'POST', body: formData })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            document.getElementById(`rule-${id}`).remove();
                        } else {
                            alert(data.message || 'Si è verificato un errore.');
                        }
                    });
            }
        }
    });

    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
});
</script>
