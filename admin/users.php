<?php
include_once 'includes/header.php';
$stmt = $pdo->query('SELECT id, username, email, created_at FROM users ORDER BY username ASC');
$users = $stmt->fetchAll();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="section-title">Gestione Utenti</h2>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#userModal" id="addUserBtn">
        <i class="fas fa-plus"></i> Aggiungi Utente
    </button>
</div>

<table class="table table-striped table-bordered">
    <thead class="table-dark">
        <tr>
            <th>Username</th>
            <th>Email</th>
            <th>Creato il</th>
            <th class="text-center">Azioni</th>
        </tr>
    </thead>
    <tbody id="usersTableBody">
        <?php foreach ($users as $user): ?>
            <tr id="user-<?php echo $user['id']; ?>">
                <td><?php echo htmlspecialchars($user['username'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo htmlspecialchars($user['created_at'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td class="text-center">
                    <button class="btn btn-sm btn-outline-primary edit-btn" data-id="<?php echo $user['id']; ?>" data-bs-toggle="tooltip" title="Modifica">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-danger delete-btn" data-id="<?php echo $user['id']; ?>" data-bs-toggle="tooltip" title="Elimina">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<!-- Modale per Aggiungi/Modifica Utente -->
<div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userModalLabel">Aggiungi Utente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="userForm">
                    <input type="hidden" id="userId" name="id">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password">
                        <small class="form-text text-muted" id="passwordHelp">Lascia vuoto per non modificare la password esistente.</small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                <button type="button" class="btn btn-primary" id="saveUserBtn">Salva</button>
            </div>
        </div>
    </div>
</div>

<?php include_once 'includes/footer.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const userModal = new bootstrap.Modal(document.getElementById('userModal'));
    const modalTitle = document.getElementById('userModalLabel');
    const userForm = document.getElementById('userForm');
    const userIdInput = document.getElementById('userId');
    const usernameInput = document.getElementById('username');
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const passwordHelp = document.getElementById('passwordHelp');

    function resetForm() {
        userForm.reset();
        userIdInput.value = '';
        passwordInput.required = true;
        passwordHelp.style.display = 'none';
        modalTitle.textContent = 'Aggiungi Utente';
    }

    document.getElementById('addUserBtn').addEventListener('click', () => {
        resetForm();
    });

    document.getElementById('usersTableBody').addEventListener('click', function(e) {
        const editBtn = e.target.closest('.edit-btn');
        if (editBtn) {
            const id = editBtn.dataset.id;
            fetch(`ajax_actions/user_actions.php?action=get&id=${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        userIdInput.value = data.user.id;
                        usernameInput.value = data.user.username;
                        emailInput.value = data.user.email;
                        passwordInput.required = false;
                        passwordHelp.style.display = 'block';
                        modalTitle.textContent = 'Modifica Utente';
                        userModal.show();
                    } else {
                        alert('Errore nel recupero dei dati utente.');
                    }
                });
        }
    });

    document.getElementById('saveUserBtn').addEventListener('click', function() {
        const formData = new FormData(userForm);
        const action = userIdInput.value ? 'edit' : 'add';
        formData.append('action', action);

        fetch('ajax_actions/user_actions.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                userModal.hide();
                location.reload();
            } else {
                alert(data.message || 'Si è verificato un errore.');
            }
        });
    });

    document.getElementById('usersTableBody').addEventListener('click', function(e) {
        const deleteBtn = e.target.closest('.delete-btn');
        if (deleteBtn) {
            const id = deleteBtn.dataset.id;
            if (confirm('Sei sicuro di voler eliminare questo utente?')) {
                const formData = new FormData();
                formData.append('action', 'delete');
                formData.append('id', id);

                fetch('ajax_actions/user_actions.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        document.getElementById(`user-${id}`).remove();
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
