<?php
include_once 'includes/header.php';
$stmt = $pdo->query('SELECT id, username, email, created_at FROM users ORDER BY username ASC');
$users = $stmt->fetchAll();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="section-title"><i class="fas fa-users me-2 text-primary"></i>Gestione Utenti</h2>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#userModal" id="addUserBtn">
        <i class="fas fa-plus"></i> Aggiungi Utente
    </button>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 60px;"></th>
                        <th>Utente</th>
                        <th>Email</th>
                        <th>Data Creazione</th>
                        <th class="text-center" style="width: 120px;">Azioni</th>
                    </tr>
                </thead>
                <tbody id="usersTableBody">
                    <?php foreach ($users as $user): 
                        $initial = strtoupper(substr($user['username'], 0, 1));
                        $bgColors = ['bg-primary', 'bg-success', 'bg-danger', 'bg-warning', 'bg-info', 'bg-secondary'];
                        $bgColor = $bgColors[$user['id'] % count($bgColors)];
                    ?>
                        <tr id="user-<?php echo $user['id']; ?>">
                            <td class="text-center">
                                <div class="avatar-circle <?php echo $bgColor; ?> text-white rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 35px; height: 35px;">
                                    <?php echo $initial; ?>
                                </div>
                            </td>
                            <td class="fw-bold text-dark"><?php echo htmlspecialchars($user['username'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td class="text-muted"><?php echo htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td class="small text-muted"><i class="far fa-calendar-alt me-1"></i><?php echo date('d/m/Y', strtotime($user['created_at'])); ?></td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-outline-primary edit-btn me-1" data-id="<?php echo $user['id']; ?>" title="Modifica">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger delete-btn" data-id="<?php echo $user['id']; ?>" title="Elimina">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modale per Aggiungi/Modifica Utente -->
<div class="modal fade" id="userModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="userModalLabel">Aggiungi Utente</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="userForm">
                    <input type="hidden" id="userId" name="id">
                    
                    <div class="mb-3">
                        <label for="username" class="form-label fw-bold">Username</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="fas fa-user"></i></span>
                            <input type="text" class="form-control" id="username" name="username" required placeholder="es. mario.rossi">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label fw-bold">Email</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="fas fa-envelope"></i></span>
                            <input type="email" class="form-control" id="email" name="email" required placeholder="es. mario@example.com">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label fw-bold">Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="fas fa-lock"></i></span>
                            <input type="password" class="form-control" id="password" name="password" placeholder="********">
                        </div>
                        <div class="form-text small text-muted mt-1" id="passwordHelp">
                            <i class="fas fa-info-circle me-1"></i>Lascia vuoto per mantenere la password attuale.
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annulla</button>
                <button type="button" class="btn btn-primary" id="saveUserBtn">Salva Utente</button>
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
        passwordHelp.classList.add('d-none'); // Hide help text for new users (password is mandatory)
        passwordInput.placeholder = "Password richiesta";
        modalTitle.textContent = 'Aggiungi Utente';
    }

    document.getElementById('addUserBtn').addEventListener('click', () => {
        resetForm();
        passwordHelp.classList.remove('d-none'); // Actually, let's keep it but change text if needed, or just hide "leave empty" part
        passwordHelp.innerHTML = '<i class="fas fa-info-circle me-1"></i>Inserisci una password sicura.';
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
                        passwordInput.placeholder = "********";
                        passwordHelp.classList.remove('d-none');
                        passwordHelp.innerHTML = '<i class="fas fa-info-circle me-1"></i>Lascia vuoto per mantenere la password attuale.';
                        modalTitle.textContent = 'Modifica Utente';
                        userModal.show();
                    } else {
                        alert('Errore nel recupero dei dati utente.');
                    }
                });
        }
    });

    document.getElementById('saveUserBtn').addEventListener('click', function() {
        if (!userForm.checkValidity()) {
            userForm.reportValidity();
            return;
        }

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
});
</script>
