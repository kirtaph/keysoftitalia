<?php
include_once 'includes/header.php';
$stmt = $pdo->query('SELECT * FROM ks_store_hours_exceptions ORDER BY date');
$exceptions = $stmt->fetchAll();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="section-title">Gestione Eccezioni Orario</h2>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exceptionModal" id="addExceptionBtn">
        <i class="fas fa-plus"></i> Aggiungi Eccezione
    </button>
</div>

<table class="table table-striped table-bordered">
    <thead class="table-dark">
        <tr>
            <th>Data</th>
            <th>Segmento</th>
            <th>Orario Apertura</th>
            <th>Orario Chiusura</th>
            <th class="text-center">Chiuso</th>
            <th>Avviso</th>
            <th class="text-center">Azioni</th>
        </tr>
    </thead>
    <tbody id="exceptionsTableBody">
        <?php foreach ($exceptions as $exception): ?>
            <tr id="exception-<?php echo $exception['id']; ?>">
                <td><?php echo htmlspecialchars($exception['date'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo htmlspecialchars($exception['seg'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo htmlspecialchars($exception['open_time'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo htmlspecialchars($exception['close_time'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                <td class="text-center"><?php echo $exception['is_closed'] ? '<i class="fas fa-check-circle text-success"></i>' : '<i class="fas fa-times-circle text-danger"></i>'; ?></td>
                <td><?php echo htmlspecialchars($exception['notice'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                <td class="text-center">
                    <button class="btn btn-sm btn-outline-primary edit-btn" data-id="<?php echo $exception['id']; ?>" data-bs-toggle="tooltip" title="Modifica">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-danger delete-btn" data-id="<?php echo $exception['id']; ?>" data-bs-toggle="tooltip" title="Elimina">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<!-- Modale per Aggiungi/Modifica Eccezione -->
<div class="modal fade" id="exceptionModal" tabindex="-1" aria-labelledby="exceptionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exceptionModalLabel">Aggiungi Eccezione</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="exceptionForm">
                    <input type="hidden" id="exceptionId" name="id">
                    <div class="mb-3">
                        <label for="date" class="form-label">Data</label>
                        <input type="date" class="form-control" id="date" name="date" required>
                    </div>
                    <div class="mb-3">
                        <label for="seg" class="form-label">Segmento</label>
                        <input type="number" class="form-control" id="seg" name="seg" min="1" required>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="is_closed" name="is_closed" value="1">
                        <label class="form-check-label" for="is_closed">Chiuso tutto il giorno</label>
                    </div>
                    <div id="time-fields">
                        <div class="mb-3">
                            <label for="open_time" class="form-label">Orario Apertura</label>
                            <input type="time" class="form-control" id="open_time" name="open_time">
                        </div>
                        <div class="mb-3">
                            <label for="close_time" class="form-label">Orario Chiusura</label>
                            <input type="time" class="form-control" id="close_time" name="close_time">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="notice" class="form-label">Avviso</label>
                        <input type="text" class="form-control" id="notice" name="notice">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                <button type="button" class="btn btn-primary" id="saveExceptionBtn">Salva</button>
            </div>
        </div>
    </div>
</div>

<?php include_once 'includes/footer.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const exceptionModal = new bootstrap.Modal(document.getElementById('exceptionModal'));
    const modalTitle = document.getElementById('exceptionModalLabel');
    const exceptionForm = document.getElementById('exceptionForm');
    const exceptionIdInput = document.getElementById('exceptionId');
    const dateInput = document.getElementById('date');
    const segInput = document.getElementById('seg');
    const isClosedCheckbox = document.getElementById('is_closed');
    const openTimeInput = document.getElementById('open_time');
    const closeTimeInput = document.getElementById('close_time');
    const noticeInput = document.getElementById('notice');

    isClosedCheckbox.addEventListener('change', function() {
        const timeFields = document.getElementById('time-fields');
        if (this.checked) {
            timeFields.style.display = 'none';
            openTimeInput.value = '';
            closeTimeInput.value = '';
        } else {
            timeFields.style.display = 'block';
        }
    });

    function resetForm() {
        exceptionForm.reset();
        exceptionIdInput.value = '';
        isClosedCheckbox.checked = false;
        document.getElementById('time-fields').style.display = 'block';
        modalTitle.textContent = 'Aggiungi Eccezione';
    }

    document.getElementById('addExceptionBtn').addEventListener('click', () => {
        resetForm();
    });

    document.getElementById('exceptionsTableBody').addEventListener('click', function(e) {
        const editBtn = e.target.closest('.edit-btn');
        if (editBtn) {
            const id = editBtn.dataset.id;
            fetch(`ajax_actions/exceptions_actions.php?action=get&id=${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        exceptionIdInput.value = data.exception.id;
                        dateInput.value = data.exception.date;
                        segInput.value = data.exception.seg;
                        isClosedCheckbox.checked = !!parseInt(data.exception.is_closed);
                        openTimeInput.value = data.exception.open_time;
                        closeTimeInput.value = data.exception.close_time;
                        noticeInput.value = data.exception.notice;
                        
                        // Trigger change event to show/hide fields
                        isClosedCheckbox.dispatchEvent(new Event('change'));

                        modalTitle.textContent = 'Modifica Eccezione';
                        exceptionModal.show();
                    } else {
                        alert('Errore nel recupero dei dati.');
                    }
                });
        }
    });

    document.getElementById('saveExceptionBtn').addEventListener('click', function() {
        const formData = new FormData(exceptionForm);
        formData.append('action', exceptionIdInput.value ? 'edit' : 'add');
        if (!isClosedCheckbox.checked) {
            formData.append('is_closed', '0');
        }

        fetch('ajax_actions/exceptions_actions.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                exceptionModal.hide();
                location.reload();
            } else {
                alert(data.message || 'Si è verificato un errore.');
            }
        });
    });

    document.getElementById('exceptionsTableBody').addEventListener('click', function(e) {
        const deleteBtn = e.target.closest('.delete-btn');
        if (deleteBtn) {
            const id = deleteBtn.dataset.id;
            if (confirm('Sei sicuro di voler eliminare questa eccezione?')) {
                const formData = new FormData();
                formData.append('action', 'delete');
                formData.append('id', id);

                fetch('ajax_actions/exceptions_actions.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        document.getElementById(`exception-${id}`).remove();
                    } else {
                        alert(data.message || 'Si è verificato un errore.');
                    }
                });
            }
        }
    });
});
</script>
