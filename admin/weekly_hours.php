<?php
include_once 'includes/header.php';
$stmt = $pdo->query('SELECT * FROM ks_store_hours_weekly ORDER BY dow, seg');
$weekly_hours = $stmt->fetchAll();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="section-title">Gestione Orario Settimanale</h2>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#weeklyHourModal" id="addWeeklyHourBtn">
        <i class="fas fa-plus"></i> Aggiungi Orario
    </button>
</div>

<table class="table table-striped table-bordered">
    <thead class="table-dark">
        <tr>
            <th>Giorno della Settimana</th>
            <th>Segmento</th>
            <th>Orario Apertura</th>
            <th>Orario Chiusura</th>
            <th class="text-center">Attivo</th>
            <th class="text-center">Azioni</th>
        </tr>
    </thead>
    <tbody id="weeklyHoursTableBody">
        <?php foreach ($weekly_hours as $hour): ?>
            <tr id="weekly-hour-<?php echo $hour['id']; ?>">
                <td><?php echo htmlspecialchars($hour['dow'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo htmlspecialchars($hour['seg'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo htmlspecialchars($hour['open_time'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo htmlspecialchars($hour['close_time'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td class="text-center"><?php echo $hour['active'] ? '<i class="fas fa-check-circle text-success"></i>' : '<i class="fas fa-times-circle text-danger"></i>'; ?></td>
                <td class="text-center">
                    <button class="btn btn-sm btn-outline-primary edit-btn" data-id="<?php echo $hour['id']; ?>" data-bs-toggle="tooltip" title="Modifica">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-danger delete-btn" data-id="<?php echo $hour['id']; ?>" data-bs-toggle="tooltip" title="Elimina">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<!-- Modale per Aggiungi/Modifica Orario Settimanale -->
<div class="modal fade" id="weeklyHourModal" tabindex="-1" aria-labelledby="weeklyHourModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="weeklyHourModalLabel">Aggiungi Orario Settimanale</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="weeklyHourForm">
                    <input type="hidden" id="weeklyHourId" name="id">
                    <div class="mb-3">
                        <label for="dow" class="form-label">Giorno della Settimana (1=Lunedì...7=Domenica)</label>
                        <input type="number" class-="form-control" id="dow" name="dow" min="1" max="7" required>
                    </div>
                    <div class="mb-3">
                        <label for="seg" class="form-label">Segmento</label>
                        <input type="number" class="form-control" id="seg" name="seg" min="1" required>
                    </div>
                    <div class="mb-3">
                        <label for="open_time" class="form-label">Orario Apertura</label>
                        <input type="time" class="form-control" id="open_time" name="open_time" required>
                    </div>
                    <div class="mb-3">
                        <label for="close_time" class="form-label">Orario Chiusura</label>
                        <input type="time" class="form-control" id="close_time" name="close_time" required>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="is_active" name="active" value="1" checked>
                        <label class="form-check-label" for="is_active">Attivo</label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                <button type="button" class="btn btn-primary" id="saveWeeklyHourBtn">Salva</button>
            </div>
        </div>
    </div>
</div>

<?php include_once 'includes/footer.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const weeklyHourModal = new bootstrap.Modal(document.getElementById('weeklyHourModal'));
    const modalTitle = document.getElementById('weeklyHourModalLabel');
    const weeklyHourForm = document.getElementById('weeklyHourForm');
    const weeklyHourIdInput = document.getElementById('weeklyHourId');
    const dowInput = document.getElementById('dow');
    const segInput = document.getElementById('seg');
    const openTimeInput = document.getElementById('open_time');
    const closeTimeInput = document.getElementById('close_time');
    const isActiveCheckbox = document.getElementById('is_active');

    function resetForm() {
        weeklyHourForm.reset();
        weeklyHourIdInput.value = '';
        isActiveCheckbox.checked = true;
        modalTitle.textContent = 'Aggiungi Orario Settimanale';
    }

    document.getElementById('addWeeklyHourBtn').addEventListener('click', () => {
        resetForm();
    });

    document.getElementById('weeklyHoursTableBody').addEventListener('click', function(e) {
        const editBtn = e.target.closest('.edit-btn');
        if (editBtn) {
            const id = editBtn.dataset.id;
            fetch(`ajax_actions/weekly_hours_actions.php?action=get&id=${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        weeklyHourIdInput.value = data.weekly_hour.id;
                        dowInput.value = data.weekly_hour.dow;
                        segInput.value = data.weekly_hour.seg;
                        openTimeInput.value = data.weekly_hour.open_time;
                        closeTimeInput.value = data.weekly_hour.close_time;
                        isActiveCheckbox.checked = !!parseInt(data.weekly_hour.active);
                        modalTitle.textContent = 'Modifica Orario Settimanale';
                        weeklyHourModal.show();
                    } else {
                        alert('Errore nel recupero dei dati.');
                    }
                });
        }
    });

    document.getElementById('saveWeeklyHourBtn').addEventListener('click', function() {
        const formData = new FormData(weeklyHourForm);
        formData.append('action', weeklyHourIdInput.value ? 'edit' : 'add');
        if (!isActiveCheckbox.checked) {
            formData.append('active', '0');
        }

        fetch('ajax_actions/weekly_hours_actions.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                weeklyHourModal.hide();
                location.reload();
            } else {
                alert(data.message || 'Si è verificato un errore.');
            }
        });
    });

    document.getElementById('weeklyHoursTableBody').addEventListener('click', function(e) {
        const deleteBtn = e.target.closest('.delete-btn');
        if (deleteBtn) {
            const id = deleteBtn.dataset.id;
            if (confirm('Sei sicuro di voler eliminare questo orario?')) {
                const formData = new FormData();
                formData.append('action', 'delete');
                formData.append('id', id);

                fetch('ajax_actions/weekly_hours_actions.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        document.getElementById(`weekly-hour-${id}`).remove();
                    } else {
                        alert(data.message || 'Si è verificato un errore.');
                    }
                });
            }
        }
    });
});
</script>
