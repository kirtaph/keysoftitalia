<?php
include_once 'includes/header.php';
$stmt = $pdo->query('SELECT i.*, d.name as device_name FROM issues i JOIN devices d ON i.device_id = d.id ORDER BY d.sort_order, i.sort_order ASC');
$issues = $stmt->fetchAll();
$devices_stmt = $pdo->query('SELECT * FROM devices ORDER BY sort_order ASC');
$devices = $devices_stmt->fetchAll();
$severities = ['low', 'mid', 'high'];
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="section-title">Gestione Problemi</h2>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#issueModal" id="addIssueBtn">
        <i class="fas fa-plus"></i> Aggiungi Problema
    </button>
</div>

<div class="card mb-4">
    <div class="card-header">Filtri e Ricerca</div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <input type="text" id="searchInput" class="form-control" placeholder="Cerca per nome...">
            </div>
            <div class="col-md-6">
                <select id="deviceFilter" class="form-select">
                    <option value="">Filtra per dispositivo</option>
                    <?php foreach ($devices as $device): ?>
                        <option value="<?php echo htmlspecialchars($device['name'], ENT_QUOTES, 'UTF-8'); ?>">
                            <?php echo htmlspecialchars($device['name'], ENT_QUOTES, 'UTF-8'); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </div>
</div>

<table class="table table-striped table-bordered">
    <thead class="table-dark">
        <tr>
            <th>Label</th>
            <th>Dispositivo</th>
            <th>Gravità</th>
            <th>Ordine</th>
            <th class="text-center">Attivo</th>
            <th class="text-center">Azioni</th>
        </tr>
    </thead>
    <tbody id="issuesTableBody">
        <?php foreach ($issues as $issue): ?>
            <tr id="issue-<?php echo $issue['id']; ?>">
                <td><?php echo htmlspecialchars($issue['label'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo htmlspecialchars($issue['device_name'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo htmlspecialchars($issue['severity'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo htmlspecialchars($issue['sort_order'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td class="text-center"><?php echo $issue['is_active'] ? '<i class="fas fa-check-circle text-success"></i>' : '<i class="fas fa-times-circle text-danger"></i>'; ?></td>
                <td class="text-center">
                    <button class="btn btn-sm btn-outline-primary edit-btn" data-id="<?php echo $issue['id']; ?>" data-bs-toggle="tooltip" title="Modifica">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-danger delete-btn" data-id="<?php echo $issue['id']; ?>" data-bs-toggle="tooltip" title="Elimina">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<!-- Modale per Aggiungi/Modifica Problema -->
<div class="modal fade" id="issueModal" tabindex="-1" aria-labelledby="issueModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="issueModalLabel">Aggiungi Problema</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="issueForm">
                    <input type="hidden" id="issueId" name="id">
                    <div class="mb-3">
                        <label for="device_id" class="form-label">Dispositivo</label>
                        <select class="form-select" id="device_id" name="device_id" required>
                            <?php foreach ($devices as $device): ?>
                                <option value="<?php echo $device['id']; ?>"><?php echo htmlspecialchars($device['name'], ENT_QUOTES, 'UTF-8'); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="label" class="form-label">Label</label>
                        <input type="text" class="form-control" id="label" name="label" required>
                    </div>
                    <div class="mb-3">
                        <label for="severity" class="form-label">Gravità</label>
                        <select class="form-select" id="severity" name="severity" required>
                            <?php foreach ($severities as $severity): ?>
                                <option value="<?php echo $severity; ?>"><?php echo ucfirst($severity); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="sort_order" class="form-label">Ordine</label>
                        <input type="number" class="form-control" id="sort_order" name="sort_order" value="0" required>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" checked>
                        <label class="form-check-label" for="is_active">Attivo</label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                <button type="button" class="btn btn-primary" id="saveIssueBtn">Salva</button>
            </div>
        </div>
    </div>
</div>

<?php include_once 'includes/footer.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const issueModal = new bootstrap.Modal(document.getElementById('issueModal'));
    const modalTitle = document.getElementById('issueModalLabel');
    const issueForm = document.getElementById('issueForm');
    const issueIdInput = document.getElementById('issueId');
    const deviceIdSelect = document.getElementById('device_id');
    const labelInput = document.getElementById('label');
    const severitySelect = document.getElementById('severity');
    const sortOrderInput = document.getElementById('sort_order');
    const isActiveCheckbox = document.getElementById('is_active');

    function resetForm() {
        issueForm.reset();
        issueIdInput.value = '';
        isActiveCheckbox.checked = true;
        sortOrderInput.value = '0';
        modalTitle.textContent = 'Aggiungi Problema';
    }

    document.getElementById('addIssueBtn').addEventListener('click', () => {
        resetForm();
    });

    document.getElementById('issuesTableBody').addEventListener('click', function(e) {
        const editBtn = e.target.closest('.edit-btn');
        if (editBtn) {
            const id = editBtn.dataset.id;
            fetch(`ajax_actions/issue_actions.php?action=get&id=${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        issueIdInput.value = data.issue.id;
                        deviceIdSelect.value = data.issue.device_id;
                        labelInput.value = data.issue.label;
                        severitySelect.value = data.issue.severity;
                        sortOrderInput.value = data.issue.sort_order;
                        isActiveCheckbox.checked = !!parseInt(data.issue.is_active);
                        modalTitle.textContent = 'Modifica Problema';
                        issueModal.show();
                    } else {
                        alert('Errore nel recupero dei dati del problema.');
                    }
                });
        }
    });

    document.getElementById('saveIssueBtn').addEventListener('click', function() {
        const formData = new FormData(issueForm);
        formData.append('action', issueIdInput.value ? 'edit' : 'add');
        if (!isActiveCheckbox.checked) {
            formData.set('is_active', '0');
        }

        fetch('ajax_actions/issue_actions.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                issueModal.hide();
                location.reload();
            } else {
                alert(data.message || 'Si è verificato un errore.');
            }
        });
    });

    document.getElementById('issuesTableBody').addEventListener('click', function(e) {
        const deleteBtn = e.target.closest('.delete-btn');
        if (deleteBtn) {
            const id = deleteBtn.dataset.id;
            if (confirm('Sei sicuro di voler eliminare questo problema?')) {
                const formData = new FormData();
                formData.append('action', 'delete');
                formData.append('id', id);

                fetch('ajax_actions/issue_actions.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        document.getElementById(`issue-${id}`).remove();
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

    const searchInput = document.getElementById('searchInput');
    const deviceFilter = document.getElementById('deviceFilter');
    const tableBody = document.getElementById('issuesTableBody');
    const tableRows = tableBody.getElementsByTagName('tr');

    function filterTable() {
        const searchTerm = searchInput.value.toLowerCase();
        const deviceTerm = deviceFilter.value.toLowerCase();

        for (let i = 0; i < tableRows.length; i++) {
            const nameCell = tableRows[i].getElementsByTagName('td')[0];
            const deviceCell = tableRows[i].getElementsByTagName('td')[1];
            if (nameCell && deviceCell) {
                const nameText = nameCell.textContent.toLowerCase();
                const deviceText = deviceCell.textContent.toLowerCase();
                const nameMatch = nameText.includes(searchTerm);
                const deviceMatch = deviceTerm === '' || deviceText.includes(deviceTerm);
                if (nameMatch && deviceMatch) {
                    tableRows[i].style.display = '';
                } else {
                    tableRows[i].style.display = 'none';
                }
            }
        }
    }

    searchInput.addEventListener('keyup', filterTable);
    deviceFilter.addEventListener('change', filterTable);
});
</script>
