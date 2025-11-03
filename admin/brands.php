<?php
include_once 'includes/header.php';
$stmt = $pdo->query('SELECT b.*, d.name as device_name FROM brands b JOIN devices d ON b.device_id = d.id ORDER BY d.sort_order, b.name');
$brands = $stmt->fetchAll();
$devices_stmt = $pdo->query('SELECT * FROM devices ORDER BY sort_order ASC');
$devices = $devices_stmt->fetchAll();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="section-title">Gestione Marchi</h2>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#brandModal" id="addBrandBtn">
        <i class="fas fa-plus"></i> Aggiungi Marchio
    </button>
</div>

<table class="table table-striped table-bordered">
    <thead class="table-dark">
        <tr>
            <th>Nome</th>
            <th>Dispositivo</th>
            <th class="text-center">Attivo</th>
            <th class="text-center">Azioni</th>
        </tr>
    </thead>
    <tbody id="brandsTableBody">
        <?php foreach ($brands as $brand): ?>
            <tr id="brand-<?php echo $brand['id']; ?>">
                <td><?php echo htmlspecialchars($brand['name'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo htmlspecialchars($brand['device_name'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td class="text-center"><?php echo $brand['is_active'] ? '<i class="fas fa-check-circle text-success"></i>' : '<i class="fas fa-times-circle text-danger"></i>'; ?></td>
                <td class="text-center">
                    <button class="btn btn-sm btn-outline-primary edit-btn" data-id="<?php echo $brand['id']; ?>" data-bs-toggle="tooltip" title="Modifica">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-danger delete-btn" data-id="<?php echo $brand['id']; ?>" data-bs-toggle="tooltip" title="Elimina">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<!-- Modale per Aggiungi/Modifica Marchio -->
<div class="modal fade" id="brandModal" tabindex="-1" aria-labelledby="brandModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="brandModalLabel">Aggiungi Marchio</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="brandForm">
                    <input type="hidden" id="brandId" name="id">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nome</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="device_id" class="form-label">Dispositivo</label>
                        <select class="form-select" id="device_id" name="device_id" required>
                            <?php foreach ($devices as $device): ?>
                                <option value="<?php echo $device['id']; ?>"><?php echo htmlspecialchars($device['name'], ENT_QUOTES, 'UTF-8'); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" checked>
                        <label class="form-check-label" for="is_active">Attivo</label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                <button type="button" class="btn btn-primary" id="saveBrandBtn">Salva</button>
            </div>
        </div>
    </div>
</div>

<?php include_once 'includes/footer.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const brandModal = new bootstrap.Modal(document.getElementById('brandModal'));
    const modalTitle = document.getElementById('brandModalLabel');
    const brandForm = document.getElementById('brandForm');
    const brandIdInput = document.getElementById('brandId');
    const nameInput = document.getElementById('name');
    const deviceIdSelect = document.getElementById('device_id');
    const isActiveCheckbox = document.getElementById('is_active');

    function resetForm() {
        brandForm.reset();
        brandIdInput.value = '';
        isActiveCheckbox.checked = true;
        modalTitle.textContent = 'Aggiungi Marchio';
    }

    document.getElementById('addBrandBtn').addEventListener('click', () => {
        resetForm();
    });

    document.getElementById('brandsTableBody').addEventListener('click', function(e) {
        const editBtn = e.target.closest('.edit-btn');
        if (editBtn) {
            const id = editBtn.dataset.id;
            fetch(`ajax_actions/brand_actions.php?action=get&id=${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        brandIdInput.value = data.brand.id;
                        nameInput.value = data.brand.name;
                        deviceIdSelect.value = data.brand.device_id;
                        isActiveCheckbox.checked = !!parseInt(data.brand.is_active);
                        modalTitle.textContent = 'Modifica Marchio';
                        brandModal.show();
                    } else {
                        alert('Errore nel recupero dei dati del marchio.');
                    }
                });
        }
    });

    document.getElementById('saveBrandBtn').addEventListener('click', function() {
        const formData = new FormData(brandForm);
        formData.append('action', brandIdInput.value ? 'edit' : 'add');
        if (!isActiveCheckbox.checked) {
            formData.append('is_active', '0');
        }

        fetch('ajax_actions/brand_actions.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                brandModal.hide();
                location.reload();
            } else {
                alert(data.message || 'Si è verificato un errore.');
            }
        });
    });

    document.getElementById('brandsTableBody').addEventListener('click', function(e) {
        const deleteBtn = e.target.closest('.delete-btn');
        if (deleteBtn) {
            const id = deleteBtn.dataset.id;
            if (confirm('Sei sicuro di voler eliminare questo marchio?')) {
                const formData = new FormData();
                formData.append('action', 'delete');
                formData.append('id', id);

                fetch('ajax_actions/brand_actions.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        document.getElementById(`brand-${id}`).remove();
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
