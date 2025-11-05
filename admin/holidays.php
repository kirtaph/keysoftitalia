<?php
include_once 'includes/header.php';
$stmt = $pdo->query('SELECT * FROM ks_store_holidays ORDER BY name');
$holidays = $stmt->fetchAll();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="section-title">Gestione Festività</h2>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#holidayModal" id="addHolidayBtn">
        <i class="fas fa-plus"></i> Aggiungi Festività
    </button>
</div>

<table class="table table-striped table-bordered">
    <thead class="table-dark">
        <tr>
            <th>Nome</th>
            <th>Tipo Regola</th>
            <th>Mese</th>
            <th>Giorno</th>
            <th>Offset Pasqua</th>
            <th class="text-center">Chiuso</th>
            <th class="text-center">Attivo</th>
            <th class="text-center">Azioni</th>
        </tr>
    </thead>
    <tbody id="holidaysTableBody">
        <?php foreach ($holidays as $holiday): ?>
            <tr id="holiday-<?php echo $holiday['id']; ?>">
                <td><?php echo htmlspecialchars($holiday['name'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo htmlspecialchars($holiday['rule_type'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo htmlspecialchars($holiday['month'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo htmlspecialchars($holiday['day'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo htmlspecialchars($holiday['offset_days'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                <td class="text-center"><?php echo $holiday['is_closed'] ? '<i class="fas fa-check-circle text-success"></i>' : '<i class="fas fa-times-circle text-danger"></i>'; ?></td>
                <td class="text-center"><?php echo $holiday['active'] ? '<i class="fas fa-check-circle text-success"></i>' : '<i class="fas fa-times-circle text-danger"></i>'; ?></td>
                <td class="text-center">
                    <button class="btn btn-sm btn-outline-primary edit-btn" data-id="<?php echo $holiday['id']; ?>" data-bs-toggle="tooltip" title="Modifica">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-danger delete-btn" data-id="<?php echo $holiday['id']; ?>" data-bs-toggle="tooltip" title="Elimina">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<!-- Modale per Aggiungi/Modifica Festività -->
<div class="modal fade" id="holidayModal" tabindex="-1" aria-labelledby="holidayModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="holidayModalLabel">Aggiungi Festività</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="holidayForm">
                    <input type="hidden" id="holidayId" name="id">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nome</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="rule_type" class="form-label">Tipo Regola</label>
                        <select class="form-select" id="rule_type" name="rule_type" required>
                            <option value="fixed">Fissa</option>
                            <option value="easter">Legata a Pasqua</option>
                        </select>
                    </div>
                     <div id="fixed-fields">
                        <div class="mb-3">
                            <label for="month" class="form-label">Mese</label>
                            <input type="number" class="form-control" id="month" name="month" min="1" max="12">
                        </div>
                        <div class="mb-3">
                            <label for="day" class="form-label">Giorno</label>
                            <input type="number" class="form-control" id="day" name="day" min="1" max="31">
                        </div>
                    </div>
                    <div id="easter-fields" style="display: none;">
                        <div class="mb-3">
                            <label for="offset_days" class="form-label">Offset da Pasqua (giorni)</label>
                            <input type="number" class="form-control" id="offset_days" name="offset_days">
                        </div>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="is_closed" name="is_closed" value="1" checked>
                        <label class="form-check-label" for="is_closed">Chiuso</label>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="is_active" name="active" value="1" checked>
                        <label class="form-check-label" for="is_active">Attivo</label>
                    </div>
                     <div class="mb-3">
                        <label for="notice" class="form-label">Avviso</label>
                        <input type="text" class="form-control" id="notice" name="notice">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                <button type="button" class="btn btn-primary" id="saveHolidayBtn">Salva</button>
            </div>
        </div>
    </div>
</div>

<?php include_once 'includes/footer.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const holidayModal = new bootstrap.Modal(document.getElementById('holidayModal'));
    const modalTitle = document.getElementById('holidayModalLabel');
    const holidayForm = document.getElementById('holidayForm');
    const holidayIdInput = document.getElementById('holidayId');
    const nameInput = document.getElementById('name');
    const ruleTypeSelect = document.getElementById('rule_type');
    const monthInput = document.getElementById('month');
    const dayInput = document.getElementById('day');
    const offsetDaysInput = document.getElementById('offset_days');
    const isClosedCheckbox = document.getElementById('is_closed');
    const isActiveCheckbox = document.getElementById('is_active');
    const noticeInput = document.getElementById('notice');

    ruleTypeSelect.addEventListener('change', function() {
        if (this.value === 'fixed') {
            document.getElementById('fixed-fields').style.display = 'block';
            document.getElementById('easter-fields').style.display = 'none';
        } else {
            document.getElementById('fixed-fields').style.display = 'none';
            document.getElementById('easter-fields').style.display = 'block';
        }
    });

    function resetForm() {
        holidayForm.reset();
        holidayIdInput.value = '';
        isClosedCheckbox.checked = true;
        isActiveCheckbox.checked = true;
        modalTitle.textContent = 'Aggiungi Festività';
        document.getElementById('fixed-fields').style.display = 'block';
        document.getElementById('easter-fields').style.display = 'none';
    }

    document.getElementById('addHolidayBtn').addEventListener('click', () => {
        resetForm();
    });

    document.getElementById('holidaysTableBody').addEventListener('click', function(e) {
        const editBtn = e.target.closest('.edit-btn');
        if (editBtn) {
            const id = editBtn.dataset.id;
            fetch(`ajax_actions/holidays_actions.php?action=get&id=${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        holidayIdInput.value = data.holiday.id;
                        nameInput.value = data.holiday.name;
                        ruleTypeSelect.value = data.holiday.rule_type;
                        monthInput.value = data.holiday.month;
                        dayInput.value = data.holiday.day;
                        offsetDaysInput.value = data.holiday.offset_days;
                        isClosedCheckbox.checked = !!parseInt(data.holiday.is_closed);
                        isActiveCheckbox.checked = !!parseInt(data.holiday.active);
                        noticeInput.value = data.holiday.notice;
                        
                        // Trigger change event to show/hide fields
                        ruleTypeSelect.dispatchEvent(new Event('change'));

                        modalTitle.textContent = 'Modifica Festività';
                        holidayModal.show();
                    } else {
                        alert('Errore nel recupero dei dati.');
                    }
                });
        }
    });

    document.getElementById('saveHolidayBtn').addEventListener('click', function() {
        const formData = new FormData(holidayForm);
        formData.append('action', holidayIdInput.value ? 'edit' : 'add');
        if (!isClosedCheckbox.checked) {
            formData.append('is_closed', '0');
        }
        if (!isActiveCheckbox.checked) {
            formData.append('active', '0');
        }

        fetch('ajax_actions/holidays_actions.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                holidayModal.hide();
                location.reload();
            } else {
                alert(data.message || 'Si è verificato un errore.');
            }
        });
    });

    document.getElementById('holidaysTableBody').addEventListener('click', function(e) {
        const deleteBtn = e.target.closest('.delete-btn');
        if (deleteBtn) {
            const id = deleteBtn.dataset.id;
            if (confirm('Sei sicuro di voler eliminare questa festività?')) {
                const formData = new FormData();
                formData.append('action', 'delete');
                formData.append('id', id);

                fetch('ajax_actions/holidays_actions.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        document.getElementById(`holiday-${id}`).remove();
                    } else {
                        alert(data.message || 'Si è verificato un errore.');
                    }
                });
            }
        }
    });
});
</script>
