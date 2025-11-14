<?php
include_once 'includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="section-title">Gestione Volantini</h2>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#flyerModal" id="addFlyerBtn">
        <i class="fas fa-plus"></i> Aggiungi Volantino
    </button>
</div>

<!-- Flyers Table -->
<table class="table table-striped table-bordered">
    <thead class="table-dark">
        <tr>
            <th>Titolo</th>
            <th>Periodo Validità</th>
            <th>Stato</th>
            <th>In Home</th>
            <th class="text-center">Azioni</th>
        </tr>
    </thead>
    <tbody id="flyersTableBody">
        <!-- Flyers will be loaded here via AJAX -->
    </tbody>
</table>

<!-- Modal for Add/Edit Flyer -->
<div class="modal fade" id="flyerModal" tabindex="-1" aria-labelledby="flyerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="flyerModalLabel">Aggiungi Volantino</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="flyerForm" enctype="multipart/form-data">
                    <input type="hidden" id="flyerId" name="id">
                    <div class="mb-3">
                        <label for="title" class="form-label">Titolo</label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="slug" class="form-label">Slug</label>
                        <input type="text" class="form-control" id="slug" name="slug" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Descrizione</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="start_date" class="form-label">Data Inizio</label>
                                <input type="date" class="form-control" id="start_date" name="start_date" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="end_date" class="form-label">Data Fine</label>
                                <input type="date" class="form-control" id="end_date" name="end_date" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="status" class="form-label">Stato</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="0">Bozza</option>
                                    <option value="1">Pubblicato</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-check form-switch mt-4">
                                <input class="form-check-input" type="checkbox" id="show_home" name="show_home" value="1">
                                <label class="form-check-label" for="show_home">Mostra in Home</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="cover_image" class="form-label">Immagine di Copertina</label>
                                <input type="file" class="form-control" id="cover_image" name="cover_image" accept="image/*">
                                <div id="existing-cover-image" class="mt-2"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="pdf_file" class="form-label">File PDF</label>
                                <input type="file" class="form-control" id="pdf_file" name="pdf_file" accept=".pdf">
                                <div id="existing-pdf-file" class="mt-2"></div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="internal_notes" class="form-label">Note Interne</label>
                        <textarea class="form-control" id="internal_notes" name="internal_notes" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                <button type="button" class="btn btn-primary" id="saveFlyerBtn">Salva</button>
            </div>
        </div>
    </div>
</div>

<?php include_once 'includes/footer.php'; ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const flyerModal = new bootstrap.Modal(document.getElementById('flyerModal'));
    const modalTitle = document.getElementById('flyerModalLabel');
    const flyerForm = document.getElementById('flyerForm');
    const flyerIdInput = document.getElementById('flyerId');
    const flyersTableBody = document.getElementById('flyersTableBody');

    function fetchFlyers() {
        fetch('ajax_actions/flyer_actions.php?action=list')
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    flyersTableBody.innerHTML = '';
                    data.flyers.forEach(flyer => {
                        const row = `
                            <tr id="flyer-${flyer.id}">
                                <td>${flyer.title}</td>
                                <td>${flyer.start_date} - ${flyer.end_date}</td>
                                <td>${flyer.status == 1 ? '<span class="badge bg-success">Pubblicato</span>' : '<span class="badge bg-warning">Bozza</span>'}</td>
                                <td class="text-center">${flyer.show_home == 1 ? '<i class="fas fa-check-circle text-success"></i>' : ''}</td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-outline-primary edit-btn" data-id="${flyer.id}" title="Modifica"><i class="fas fa-edit"></i></button>
                                    <button class="btn btn-sm btn-danger delete-btn" data-id="${flyer.id}" title="Elimina"><i class="fas fa-trash-alt"></i></button>
                                </td>
                            </tr>
                        `;
                        flyersTableBody.insertAdjacentHTML('beforeend', row);
                    });
                }
            });
    }

    function resetForm() {
        flyerForm.reset();
        flyerIdInput.value = '';
        modalTitle.textContent = 'Aggiungi Volantino';
        document.getElementById('existing-cover-image').innerHTML = '';
        document.getElementById('existing-pdf-file').innerHTML = '';
    }

    document.getElementById('addFlyerBtn').addEventListener('click', () => {
        resetForm();
    });

    flyersTableBody.addEventListener('click', function(e) {
        const editBtn = e.target.closest('.edit-btn');
        if (editBtn) {
            const id = editBtn.dataset.id;
            fetch(`ajax_actions/flyer_actions.php?action=get&id=${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        const f = data.flyer;
                        flyerIdInput.value = f.id;
                        document.getElementById('title').value = f.title;
                        document.getElementById('slug').value = f.slug;
                        document.getElementById('description').value = f.description;
                        document.getElementById('start_date').value = f.start_date;
                        document.getElementById('end_date').value = f.end_date;
                        document.getElementById('status').value = f.status;
                        document.getElementById('show_home').checked = !!parseInt(f.show_home);
                        document.getElementById('internal_notes').value = f.internal_notes;
                        
                        document.getElementById('existing-cover-image').innerHTML = f.cover_image ? `<a href="../${f.cover_image}" target="_blank">Visualizza immagine</a>` : '';
                        document.getElementById('existing-pdf-file').innerHTML = f.pdf_file ? `<a href="../${f.pdf_file}" target="_blank">Visualizza PDF</a>` : '';

                        modalTitle.textContent = 'Modifica Volantino';
                        flyerModal.show();
                    }
                });
        }
    });

    flyersTableBody.addEventListener('click', function(e) {
        const deleteBtn = e.target.closest('.delete-btn');
        if (deleteBtn) {
            const id = deleteBtn.dataset.id;
            if (confirm('Sei sicuro di voler eliminare questo volantino?')) {
                const formData = new FormData();
                formData.append('action', 'delete');
                formData.append('id', id);

                fetch('ajax_actions/flyer_actions.php', { method: 'POST', body: formData })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            fetchFlyers();
                        } else {
                            alert(data.message || 'Errore');
                        }
                    });
            }
        }
    });

    document.getElementById('saveFlyerBtn').addEventListener('click', function() {
        const formData = new FormData(flyerForm);
        formData.append('action', flyerIdInput.value ? 'edit' : 'add');
        
        fetch('ajax_actions/flyer_actions.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                flyerModal.hide();
                fetchFlyers();
            } else {
                alert(data.message || 'Si è verificato un errore.');
            }
        });
    });
    
    // Auto-generate slug from title
    const titleInput = document.getElementById('title');
    const slugInput = document.getElementById('slug');
    titleInput.addEventListener('blur', function() {
        if(slugInput.value === '') {
            slugInput.value = titleInput.value.toLowerCase().trim().replace(/[^a-z0-9-]+/g, '-');
        }
    });

    fetchFlyers();
});
</script>
