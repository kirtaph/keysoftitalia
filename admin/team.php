<?php include 'includes/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">Membri del Team</h1>
        <p class="text-muted">Gestisci i membri visibili nella sezione 'Chi Siamo'.</p>
    </div>
    <button class="btn btn-primary" id="addMemberBtn" data-bs-toggle="modal" data-bs-target="#memberModal">
        <i class="fas fa-plus me-2"></i> Nuovo Membro
    </button>
</div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 80px;" class="text-center">Foto</th>
                            <th>Nome e Ruolo</th>
                            <th>Bio</th>
                            <th>Skills</th>
                            <th class="text-center" style="width: 100px;">Ordine</th>
                            <th class="text-center" style="width: 100px;">Stato</th>
                            <th class="text-center" style="width: 120px;">Azioni</th>
                        </tr>
                    </thead>
                    <tbody id="membersTableBody">
                        <!-- Caricato via AJAX -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<!-- Modal Membro -->
<div class="modal fade" id="memberModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="memberModalLabel">Nuovo Membro del Team</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="memberForm">
                    <input type="hidden" id="memberId" name="id">
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Nome *</label>
                            <input type="text" class="form-control" id="name" name="name" required placeholder="es. Mario Rossi">
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Ruolo *</label>
                            <input type="text" class="form-control" id="role" name="role" required placeholder="es. Sviluppatore Web">
                        </div>
                        
                        <div class="col-md-12">
                            <label class="form-label fw-bold">Biografia</label>
                            <textarea class="form-control" id="bio" name="bio" rows="3" placeholder="Breve descrizione del membro..."></textarea>
                        </div>
                        
                        <div class="col-md-12">
                            <label class="form-label fw-bold">Competenze (Skills)</label>
                            <input type="text" class="form-control" id="skills" name="skills" placeholder="es. Hardware, Software, Relazioni">
                            <div class="form-text">Inserisci le competenze separate da una virgola. Verranno mostrate come 'tag'.</div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Foto *</label>
                            <input type="file" class="form-control" id="photo_file" name="photo_file" accept="image/*">
                            <div class="form-text">Si consiglia un'immagine quadrata con sfondo trasparente.</div>
                            <div id="preview-photo" class="mt-2 text-center"></div>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Animazione AOS</label>
                            <select class="form-select" id="aos_animation" name="aos_animation">
                                <option value="fade-up">Dal basso (fade-up)</option>
                                <option value="fade-right">Da sinistra (fade-right)</option>
                                <option value="fade-left">Da destra (fade-left)</option>
                                <option value="zoom-in">Ingrandimento (zoom-in)</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Ordinamento (Crescente)</label>
                            <input type="number" class="form-control" id="sort_order" name="sort_order" value="0">
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Stato *</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="1">Attivo (Visibile)</option>
                                <option value="0">Disattivato</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                <button type="button" class="btn btn-primary" id="saveBtn"><i class="fas fa-save me-1"></i> Salva Membro</button>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tableBody = document.getElementById('membersTableBody');
    const modal = new bootstrap.Modal(document.getElementById('memberModal'));
    const form = document.getElementById('memberForm');
    const saveBtn = document.getElementById('saveBtn');

    function fetchMembers() {
        fetch('ajax_actions/team_actions.php?action=list')
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    tableBody.innerHTML = '';
                    if (data.members.length === 0) {
                        tableBody.innerHTML = '<tr><td colspan="7" class="text-center py-4 text-muted">Nessun membro del team inserito.</td></tr>';
                        return;
                    }
                    data.members.forEach(m => {
                        const statusBadge = m.status == 1 
                            ? '<span class="badge bg-success">Attivo</span>' 
                            : '<span class="badge bg-secondary">Disattivato</span>';

                        const photoHtml = m.photo_path 
                            ? `<img src="../assets/${m.photo_path}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 50%;">` 
                            : '<div class="bg-light rounded-circle d-flex align-items-center justify-content-center text-muted mx-auto" style="width: 50px; height: 50px;"><i class="fas fa-user"></i></div>';
                        
                        let skillsHtml = '';
                        if (m.skills) {
                            const skillsArray = m.skills.split(',');
                            skillsArray.forEach(s => {
                                if (s.trim() !== '') {
                                    skillsHtml += `<span class="badge bg-light text-dark border me-1 mb-1">${s.trim()}</span>`;
                                }
                            });
                        } else {
                            skillsHtml = '<span class="text-muted">-</span>';
                        }

                        const bioShort = m.bio && m.bio.length > 50 ? m.bio.substring(0, 50) + '...' : (m.bio || '-');

                        const row = `
                            <tr>
                                <td class="text-center">${photoHtml}</td>
                                <td>
                                    <div class="fw-bold">${m.name}</div>
                                    <div class="small text-muted">${m.role}</div>
                                </td>
                                <td><span style="font-size: 0.85rem;" class="text-muted">${bioShort}</span></td>
                                <td>${skillsHtml}</td>
                                <td class="text-center">${m.sort_order}</td>
                                <td class="text-center">${statusBadge}</td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-outline-primary edit-btn me-1" data-id="${m.id}" title="Modifica"><i class="fas fa-edit"></i></button>
                                    <button class="btn btn-sm btn-outline-danger delete-btn" data-id="${m.id}" title="Elimina"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                        `;
                        tableBody.insertAdjacentHTML('beforeend', row);
                    });
                }
            });
    }

    document.getElementById('addMemberBtn').addEventListener('click', () => {
        form.reset();
        document.getElementById('memberId').value = '';
        document.getElementById('memberModalLabel').textContent = 'Nuovo Membro del Team';
        document.getElementById('preview-photo').innerHTML = '';
        document.getElementById('photo_file').required = true;
    });

    tableBody.addEventListener('click', e => {
        const editBtn = e.target.closest('.edit-btn');
        if (editBtn) {
            const id = editBtn.dataset.id;
            fetch(`ajax_actions/team_actions.php?action=get&id=${id}`)
                .then(r => r.json())
                .then(data => {
                    if (data.status === 'success') {
                        const m = data.member;
                        form.reset();
                        document.getElementById('memberId').value = m.id;
                        document.getElementById('name').value = m.name;
                        document.getElementById('role').value = m.role;
                        document.getElementById('bio').value = m.bio || '';
                        document.getElementById('skills').value = m.skills || '';
                        document.getElementById('aos_animation').value = m.aos_animation || 'fade-up';
                        document.getElementById('sort_order').value = m.sort_order;
                        document.getElementById('status').value = m.status;
                        
                        if (m.photo_path) {
                            document.getElementById('preview-photo').innerHTML = `<img src="../assets/${m.photo_path}" style="width: 100px; height: 100px; object-fit: cover; border-radius: 50%;" class="mt-2 shadow-sm">`;
                            document.getElementById('photo_file').required = false;
                        } else {
                            document.getElementById('preview-photo').innerHTML = '';
                            document.getElementById('photo_file').required = true;
                        }

                        document.getElementById('memberModalLabel').textContent = 'Modifica Membro';
                        modal.show();
                    }
                });
        }

        const delBtn = e.target.closest('.delete-btn');
        if (delBtn) {
            if (confirm('Sei sicuro di voler eliminare questo membro?')) {
                const fd = new FormData();
                fd.append('action', 'delete');
                fd.append('id', delBtn.dataset.id);
                fetch('ajax_actions/team_actions.php', { method: 'POST', body: fd })
                    .then(r => r.json())
                    .then(data => {
                        if (data.status === 'success') fetchMembers();
                        else alert(data.message);
                    });
            }
        }
    });

    saveBtn.addEventListener('click', () => {
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }
        const fd = new FormData(form);
        fd.append('action', document.getElementById('memberId').value ? 'edit' : 'add');
        
        fetch('ajax_actions/team_actions.php', { method: 'POST', body: fd })
            .then(r => r.json())
            .then(data => {
                if (data.status === 'success') {
                    modal.hide();
                    fetchMembers();
                } else {
                    alert(data.message);
                }
            });
    });

    fetchMembers();
});
</script>
