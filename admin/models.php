<?php
include_once 'includes/header.php';
$stmt = $pdo->query('SELECT m.*, b.name as brand_name, d.name as device_name FROM models m JOIN brands b ON m.brand_id = b.id JOIN devices d ON b.device_id = d.id ORDER BY d.sort_order, b.name, m.name');
$models = $stmt->fetchAll();
$devices_stmt = $pdo->query('SELECT * FROM devices ORDER BY sort_order ASC');
$devices = $devices_stmt->fetchAll();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="section-title">Gestione Modelli</h2>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modelModal" id="addModelBtn">
        <i class="fas fa-plus"></i> Aggiungi Modello
    </button>
</div>

<table class="table table-striped table-bordered">
    <thead class="table-dark">
        <tr>
            <th>Nome</th>
            <th>Anno</th>
            <th>Marchio</th>
            <th>Dispositivo</th>
            <th class="text-center">Attivo</th>
            <th class="text-center">Azioni</th>
        </tr>
    </thead>
    <tbody id="modelsTableBody">
        <?php foreach ($models as $model): ?>
            <tr id="model-<?php echo $model['id']; ?>">
                <td><?php echo htmlspecialchars($model['name'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo htmlspecialchars($model['year'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo htmlspecialchars($model['brand_name'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo htmlspecialchars($model['device_name'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td class="text-center"><?php echo $model['is_active'] ? '<i class="fas fa-check-circle text-success"></i>' : '<i class="fas fa-times-circle text-danger"></i>'; ?></td>
                <td class="text-center">
                    <button class="btn btn-sm btn-outline-primary edit-btn" data-id="<?php echo $model['id']; ?>" data-bs-toggle="tooltip" title="Modifica">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-danger delete-btn" data-id="<?php echo $model['id']; ?>" data-bs-toggle="tooltip" title="Elimina">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<!-- Modale per Aggiungi/Modifica Modello -->
<div class="modal fade" id="modelModal" tabindex="-1" aria-labelledby="modelModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modelModalLabel">Aggiungi Modello</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="modelForm">
                    <input type="hidden" id="modelId" name="id">
                    <div class="mb-3">
                        <label for="device_id_modal" class="form-label">Dispositivo</label>
                        <select class="form-select" id="device_id_modal" name="device_id" required>
                            <option value="">Seleziona Dispositivo</option>
                            <?php foreach ($devices as $device): ?>
                                <option value="<?php echo $device['id']; ?>"><?php echo htmlspecialchars($device['name'], ENT_QUOTES, 'UTF-8'); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="brand_id_modal" class="form-label">Marchio</label>
                        <select class="form-select" id="brand_id_modal" name="brand_id" required>
                            <option value="">Seleziona prima un dispositivo</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="name_modal" class="form-label">Nome Modello</label>
                        <input type="text" class="form-control" id="name_modal" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="year_modal" class="form-label">Anno</label>
                        <input type="number" class="form-control" id="year_modal" name="year">
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="is_active_modal" name="is_active" value="1" checked>
                        <label class="form-check-label" for="is_active_modal">Attivo</label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                <button type="button" class="btn btn-primary" id="saveModelBtn">Salva</button>
            </div>
        </div>
    </div>
</div>

<?php include_once 'includes/footer.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modelModal = new bootstrap.Modal(document.getElementById('modelModal'));
    const modalTitle = document.getElementById('modelModalLabel');
    const modelForm = document.getElementById('modelForm');
    const modelIdInput = document.getElementById('modelId');
    const deviceSelect = document.getElementById('device_id_modal');
    const brandSelect = document.getElementById('brand_id_modal');
    const nameInput = document.getElementById('name_modal');
    const yearInput = document.getElementById('year_modal');
    const isActiveCheckbox = document.getElementById('is_active_modal');

    function resetForm() {
        modelForm.reset();
        modelIdInput.value = '';
        brandSelect.innerHTML = '<option value="">Seleziona prima un dispositivo</option>';
        isActiveCheckbox.checked = true;
        modalTitle.textContent = 'Aggiungi Modello';
    }

    function loadBrands(deviceId, selectedBrandId = null) {
        if (!deviceId) {
            brandSelect.innerHTML = '<option value="">Seleziona prima un dispositivo</option>';
            return;
        }
        fetch(`ajax_actions/brand_actions.php?action=get_by_device&device_id=${deviceId}`)
            .then(response => response.json())
            .then(data => {
                brandSelect.innerHTML = '<option value="">Seleziona Marchio</option>';
                if (data.status === 'success') {
                    data.brands.forEach(brand => {
                        const option = new Option(brand.name, brand.id);
                        brandSelect.add(option);
                    });
                    if (selectedBrandId) {
                        brandSelect.value = selectedBrandId;
                    }
                }
            });
    }

    deviceSelect.addEventListener('change', () => loadBrands(deviceSelect.value));

    document.getElementById('addModelBtn').addEventListener('click', () => {
        resetForm();
    });

    document.getElementById('modelsTableBody').addEventListener('click', function(e) {
        const editBtn = e.target.closest('.edit-btn');
        if (editBtn) {
            const id = editBtn.dataset.id;
            fetch(`ajax_actions/model_actions.php?action=get&id=${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        resetForm();
                        modelIdInput.value = data.model.id;
                        deviceSelect.value = data.model.device_id;
                        loadBrands(data.model.device_id, data.model.brand_id);
                        nameInput.value = data.model.name;
                        yearInput.value = data.model.year;
                        isActiveCheckbox.checked = !!parseInt(data.model.is_active);
                        modalTitle.textContent = 'Modifica Modello';
                        modelModal.show();
                    } else {
                        alert('Errore nel recupero dei dati del modello.');
                    }
                });
        }
    });

    document.getElementById('saveModelBtn').addEventListener('click', function() {
        const formData = new FormData(modelForm);
        formData.append('action', modelIdInput.value ? 'edit' : 'add');
        if (!isActiveCheckbox.checked) {
            formData.set('is_active', '0');
        }

        fetch('ajax_actions/model_actions.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                modelModal.hide();
                location.reload();
            } else {
                alert(data.message || 'Si è verificato un errore.');
            }
        });
    });

    document.getElementById('modelsTableBody').addEventListener('click', function(e) {
        const deleteBtn = e.target.closest('.delete-btn');
        if (deleteBtn) {
            const id = deleteBtn.dataset.id;
            if (confirm('Sei sicuro di voler eliminare questo modello?')) {
                const formData = new FormData();
                formData.append('action', 'delete');
                formData.append('id', id);

                fetch('ajax_actions/model_actions.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        document.getElementById(`model-${id}`).remove();
                    } else {
                        alert(data.message || 'Si è verificato un errore.');
                    }
                });
            }
        }
    });

    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
