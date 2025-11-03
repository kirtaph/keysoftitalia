<?php
include_once 'includes/header.php';
$stmt = $pdo->query('SELECT * FROM devices ORDER BY sort_order ASC');
$devices = $stmt->fetchAll();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="section-title">Gestione Dispositivi</h2>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#deviceModal" id="addDeviceBtn">
        <i class="fas fa-plus"></i> Aggiungi Dispositivo
    </button>
</div>

<table class="table table-striped table-bordered">
    <thead class="table-dark">
        <tr>
            <th>Nome</th>
            <th>Slug</th>
            <th>Ordine</th>
            <th class="text-center">Azioni</th>
        </tr>
    </thead>
    <tbody id="devicesTableBody">
        <?php foreach ($devices as $device): ?>
            <tr id="device-<?php echo $device['id']; ?>">
                <td data-label="Nome"><?php echo htmlspecialchars($device['name'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td data-label="Slug"><?php echo htmlspecialchars($device['slug'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td data-label="Ordine"><?php echo htmlspecialchars($device['sort_order'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td class="text-center">
                    <button class="btn btn-sm btn-outline-primary edit-btn" data-id="<?php echo $device['id']; ?>" data-bs-toggle="tooltip" title="Modifica">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-danger delete-btn" data-id="<?php echo $device['id']; ?>" data-bs-toggle="tooltip" title="Elimina">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<!-- Modale per Aggiungi/Modifica Dispositivo -->
<div class="modal fade" id="deviceModal" tabindex="-1" aria-labelledby="deviceModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deviceModalLabel">Aggiungi Dispositivo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="deviceForm">
                    <input type="hidden" id="deviceId" name="id">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nome</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="slug" class="form-label">Slug</label>
                        <input type="text" class="form-control" id="slug" name="slug" required>
                    </div>
                    <div class="mb-3">
                        <label for="sort_order" class="form-label">Ordine</label>
                        <input type="number" class="form-control" id="sort_order" name="sort_order" value="0" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                <button type="button" class="btn btn-primary" id="saveDeviceBtn">Salva</button>
            </div>
        </div>
    </div>
</div>

<?php include_once 'includes/footer.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const deviceModal = new bootstrap.Modal(document.getElementById('deviceModal'));
    const modalTitle = document.getElementById('deviceModalLabel');
    const deviceForm = document.getElementById('deviceForm');
    const deviceIdInput = document.getElementById('deviceId');
    const nameInput = document.getElementById('name');
    const slugInput = document.getElementById('slug');
    const sortOrderInput = document.getElementById('sort_order');

    // Funzione per resettare il form
    function resetForm() {
        deviceForm.reset();
        deviceIdInput.value = '';
        modalTitle.textContent = 'Aggiungi Dispositivo';
    }

    // Apre la modale per aggiungere un dispositivo
    document.getElementById('addDeviceBtn').addEventListener('click', function() {
        resetForm();
    });

    // Gestisce il click sul pulsante Modifica
    document.getElementById('devicesTableBody').addEventListener('click', function(e) {
        const editBtn = e.target.closest('.edit-btn');
        if (editBtn) {
            const id = editBtn.dataset.id;
            fetch(`ajax_actions/device_actions.php?action=get&id=${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        deviceIdInput.value = data.device.id;
                        nameInput.value = data.device.name;
                        slugInput.value = data.device.slug;
                        sortOrderInput.value = data.device.sort_order;
                        modalTitle.textContent = 'Modifica Dispositivo';
                        deviceModal.show();
                    } else {
                        alert('Errore nel recupero dei dati del dispositivo.');
                    }
                });
        }
    });

    // Salva o aggiorna un dispositivo
    document.getElementById('saveDeviceBtn').addEventListener('click', function() {
        const formData = new FormData(deviceForm);
        const action = deviceIdInput.value ? 'edit' : 'add';
        formData.append('action', action);

        fetch('ajax_actions/device_actions.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                deviceModal.hide();
                location.reload(); // Ricarica la pagina per visualizzare le modifiche
            } else {
                alert(data.message || 'Si è verificato un errore.');
            }
        });
    });

    // Gestisce l'eliminazione di un dispositivo
    document.getElementById('devicesTableBody').addEventListener('click', function(e) {
        const deleteBtn = e.target.closest('.delete-btn');
        if (deleteBtn) {
            const id = deleteBtn.dataset.id;
            if (confirm('Sei sicuro di voler eliminare questo dispositivo?')) {
                const formData = new FormData();
                formData.append('action', 'delete');
                formData.append('id', id);

                fetch('ajax_actions/device_actions.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        document.getElementById(`device-${id}`).remove();
                    } else {
                        alert(data.message || 'Si è verificato un errore.');
                    }
                });
            }
        }
    });

    // Abilita i tooltip
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
