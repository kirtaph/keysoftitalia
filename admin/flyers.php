<?php
include_once 'includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="section-title"><i class="fas fa-newspaper me-2 text-primary"></i>Gestione Volantini</h2>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#flyerModal" id="addFlyerBtn">
        <i class="fas fa-plus"></i> Aggiungi Volantino
    </button>
</div>

<!-- Flyers Table -->
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 80px;">Cover</th>
                        <th>Titolo & Link</th>
                        <th>Periodo Validità</th>
                        <th class="text-center">Stato</th>
                        <th class="text-center">Home</th>
                        <th class="text-center" style="width: 120px;">Azioni</th>
                    </tr>
                </thead>
                <tbody id="flyersTableBody">
                    <!-- Flyers will be loaded here via AJAX -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Wizard Modal for Add/Edit Flyer -->
<div class="modal fade" id="flyerModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="flyerModalLabel">Nuovo Volantino</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <!-- Wizard Progress -->
                <div class="wizard-progress bg-light border-bottom p-3">
                    <div class="d-flex justify-content-between position-relative">
                        <div class="progress position-absolute top-50 start-0 w-100 translate-middle-y" style="height: 2px; z-index: 0;">
                            <div class="progress-bar bg-primary" id="wizardProgressBar" style="width: 0%;"></div>
                        </div>
                        <div class="wizard-step active" data-step="1">
                            <div class="step-circle bg-primary text-white">1</div>
                            <div class="step-label">Info</div>
                        </div>
                        <div class="wizard-step" data-step="2">
                            <div class="step-circle bg-secondary text-white">2</div>
                            <div class="step-label">Media</div>
                        </div>
                        <div class="wizard-step" data-step="3">
                            <div class="step-circle bg-secondary text-white">3</div>
                            <div class="step-label">Riepilogo</div>
                        </div>
                    </div>
                </div>

                <form id="flyerForm" enctype="multipart/form-data" class="p-4">
                    <input type="hidden" id="flyerId" name="id">
                    
                    <!-- Step 1: Info Generali -->
                    <div class="wizard-content" id="step1">
                        <h6 class="mb-3 text-primary">Informazioni Generali</h6>
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label">Titolo Volantino *</label>
                                <input type="text" class="form-control" id="title" name="title" required placeholder="es. Offerte di Natale">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Slug (URL) *</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">/volantini/</span>
                                    <input type="text" class="form-control" id="slug" name="slug" required placeholder="offerte-natale">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Data Inizio *</label>
                                <input type="date" class="form-control" id="start_date" name="start_date" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Data Fine *</label>
                                <input type="date" class="form-control" id="end_date" name="end_date" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Descrizione (SEO)</label>
                                <textarea class="form-control" id="description" name="description" rows="2" placeholder="Breve descrizione per i motori di ricerca..."></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Step 2: Media & Files -->
                    <div class="wizard-content d-none" id="step2">
                        <h6 class="mb-3 text-primary">Media & Allegati</h6>
                        
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="card h-100 border-dashed">
                                    <div class="card-body text-center">
                                        <i class="fas fa-image fa-3x text-muted mb-3"></i>
                                        <h6>Copertina (JPG/PNG)</h6>
                                        <p class="small text-muted">Immagine visibile nell'elenco.</p>
                                        <input type="file" class="form-control form-control-sm mb-2" id="cover_image" name="cover_image" accept="image/*">
                                        <div id="preview-cover" class="mt-2"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card h-100 border-dashed">
                                    <div class="card-body text-center">
                                        <i class="fas fa-file-pdf fa-3x text-danger mb-3"></i>
                                        <h6>File PDF</h6>
                                        <p class="small text-muted">Il volantino completo da sfogliare.</p>
                                        <input type="file" class="form-control form-control-sm mb-2" id="pdf_file" name="pdf_file" accept=".pdf">
                                        <div id="preview-pdf" class="mt-2"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 3: Review & Status -->
                    <div class="wizard-content d-none" id="step3">
                        <h6 class="mb-3 text-primary">Pubblicazione & Note</h6>
                        
                        <div class="card bg-light border-0 mb-3">
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Stato Pubblicazione</label>
                                        <select class="form-select" id="status" name="status">
                                            <option value="0">Bozza (Nascosto)</option>
                                            <option value="1">Pubblicato (Visibile)</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 d-flex align-items-end">
                                        <div class="form-check form-switch mb-2">
                                            <input class="form-check-input" type="checkbox" id="show_home" name="show_home" value="1">
                                            <label class="form-check-label" for="show_home">Mostra in Home Page</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Note Interne</label>
                            <textarea class="form-control" id="internal_notes" name="internal_notes" rows="3" placeholder="Note visibili solo agli amministratori..."></textarea>
                        </div>
                        
                        <div class="alert alert-info small">
                            <i class="fas fa-info-circle me-1"></i> Controlla tutti i dati prima di salvare. Puoi tornare indietro per modificare.
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer bg-light d-flex justify-content-between">
                <button type="button" class="btn btn-outline-secondary" id="prevStepBtn" disabled>Indietro</button>
                <div>
                    <button type="button" class="btn btn-primary" id="nextStepBtn">Avanti</button>
                    <button type="button" class="btn btn-success d-none" id="saveFlyerBtn">Salva Volantino</button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.wizard-step {
    position: relative;
    z-index: 1;
    text-align: center;
    width: 30px;
}
.step-circle {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    margin: 0 auto;
    transition: all 0.3s ease;
}
.step-label {
    font-size: 0.75rem;
    margin-top: 5px;
    position: absolute;
    left: 50%;
    transform: translateX(-50%);
    white-space: nowrap;
    color: #6c757d;
}
.wizard-step.active .step-circle {
    background-color: #0d6efd !important;
    transform: scale(1.1);
}
.wizard-step.active .step-label {
    color: #0d6efd;
    font-weight: bold;
}
.border-dashed {
    border: 2px dashed #dee2e6;
}
</style>

<?php include_once 'includes/footer.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const flyerModal = new bootstrap.Modal(document.getElementById('flyerModal'));
    const modalTitle = document.getElementById('flyerModalLabel');
    const flyerForm = document.getElementById('flyerForm');
    const flyerIdInput = document.getElementById('flyerId');
    const flyersTableBody = document.getElementById('flyersTableBody');
    
    // Wizard Elements
    const steps = [document.getElementById('step1'), document.getElementById('step2'), document.getElementById('step3')];
    const prevBtn = document.getElementById('prevStepBtn');
    const nextBtn = document.getElementById('nextStepBtn');
    const saveBtn = document.getElementById('saveFlyerBtn');
    const progressBar = document.getElementById('wizardProgressBar');
    const stepIndicators = document.querySelectorAll('.wizard-step');
    let currentStep = 0;

    function updateWizard() {
        // Show/Hide Steps
        steps.forEach((step, index) => {
            if (index === currentStep) {
                step.classList.remove('d-none');
            } else {
                step.classList.add('d-none');
            }
        });

        // Update Buttons
        prevBtn.disabled = currentStep === 0;
        if (currentStep === steps.length - 1) {
            nextBtn.classList.add('d-none');
            saveBtn.classList.remove('d-none');
        } else {
            nextBtn.classList.remove('d-none');
            saveBtn.classList.add('d-none');
        }

        // Update Progress
        const progress = (currentStep / (steps.length - 1)) * 100;
        progressBar.style.width = `${progress}%`;

        // Update Indicators
        stepIndicators.forEach((indicator, index) => {
            const circle = indicator.querySelector('.step-circle');
            if (index <= currentStep) {
                indicator.classList.add('active');
                circle.classList.remove('bg-secondary');
                circle.classList.add('bg-primary');
            } else {
                indicator.classList.remove('active');
                circle.classList.remove('bg-primary');
                circle.classList.add('bg-secondary');
            }
        });
    }

    nextBtn.addEventListener('click', () => {
        if (validateStep(currentStep)) {
            currentStep++;
            updateWizard();
        }
    });

    prevBtn.addEventListener('click', () => {
        if (currentStep > 0) {
            currentStep--;
            updateWizard();
        }
    });

    function validateStep(step) {
        if (step === 0) {
            const required = ['title', 'slug', 'start_date', 'end_date'];
            let isValid = true;
            required.forEach(id => {
                const el = document.getElementById(id);
                if (!el.value.trim()) {
                    el.classList.add('is-invalid');
                    isValid = false;
                } else {
                    el.classList.remove('is-invalid');
                }
            });
            return isValid;
        }
        return true;
    }

    function fetchFlyers() {
        fetch('ajax_actions/flyer_actions.php?action=list')
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    flyersTableBody.innerHTML = '';
                    if (data.flyers.length === 0) {
                        flyersTableBody.innerHTML = '<tr><td colspan="6" class="text-center py-4 text-muted">Nessun volantino presente.</td></tr>';
                        return;
                    }
                    data.flyers.forEach(flyer => {
                        const rootPath = window.location.pathname.replace(/\/admin\/.*$/, '/');
                        const shareUrl = new URL(
                            rootPath + 'volantini.php?flyer=' + encodeURIComponent(flyer.slug),
                            window.location.origin
                        );
                        const shareLink = shareUrl.toString();
                        
                        const coverHtml = flyer.cover_image 
                            ? `<img src="../${flyer.cover_image}" class="rounded border" style="width: 50px; height: 70px; object-fit: cover;">`
                            : `<div class="bg-light rounded border d-flex align-items-center justify-content-center text-muted" style="width: 50px; height: 70px;"><i class="fas fa-image"></i></div>`;

                        const statusBadge = flyer.status == 1 
                            ? '<span class="badge bg-success"><i class="fas fa-check me-1"></i>Pubblicato</span>' 
                            : '<span class="badge bg-secondary"><i class="fas fa-pen me-1"></i>Bozza</span>';

                        const row = `
                            <tr id="flyer-${flyer.id}">
                                <td>${coverHtml}</td>
                                <td>
                                    <div class="fw-bold text-dark">${flyer.title}</div>
                                    <div class="input-group input-group-sm mt-1" style="max-width: 250px;">
                                        <input type="text" class="form-control bg-light border-0 text-muted" value="${shareLink}" readonly style="font-size: 0.8rem;">
                                        <button class="btn btn-outline-secondary border-0 copy-link-btn" data-link="${shareLink}" title="Copia Link"><i class="fas fa-copy"></i></button>
                                    </div>
                                </td>
                                <td>
                                    <div class="small text-muted"><i class="far fa-calendar-alt me-1"></i>Dal: ${flyer.start_date}</div>
                                    <div class="small text-muted"><i class="far fa-calendar-check me-1"></i>Al: &nbsp;${flyer.end_date}</div>
                                </td>
                                <td class="text-center">${statusBadge}</td>
                                <td class="text-center">
                                    ${flyer.show_home == 1 
                                        ? '<i class="fas fa-home text-primary fa-lg" title="Visibile in Home"></i>' 
                                        : '<i class="fas fa-home text-muted fa-lg" style="opacity: 0.2;" title="Non in Home"></i>'}
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-outline-primary edit-btn me-1" data-id="${flyer.id}" title="Modifica"><i class="fas fa-edit"></i></button>
                                    <button class="btn btn-sm btn-outline-danger delete-btn" data-id="${flyer.id}" title="Elimina"><i class="fas fa-trash-alt"></i></button>
                                </td>
                            </tr>
                        `;
                        flyersTableBody.insertAdjacentHTML('beforeend', row);
                    });
                }
            });
    }

    flyersTableBody.addEventListener('click', function(e) {
        const copyBtn = e.target.closest('.copy-link-btn');
        if (copyBtn) {
            const link = copyBtn.dataset.link;
            navigator.clipboard.writeText(link).then(() => {
                const icon = copyBtn.querySelector('i');
                icon.className = 'fas fa-check text-success';
                setTimeout(() => {
                    icon.className = 'fas fa-copy';
                }, 2000);
            });
        }
    });

    function resetForm() {
        flyerForm.reset();
        flyerIdInput.value = '';
        modalTitle.textContent = 'Nuovo Volantino';
        document.getElementById('preview-cover').innerHTML = '';
        document.getElementById('preview-pdf').innerHTML = '';
        currentStep = 0;
        updateWizard();
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
                        
                        document.getElementById('preview-cover').innerHTML = f.cover_image 
                            ? `<img src="../${f.cover_image}" class="img-thumbnail mt-2" style="max-height: 100px;">` 
                            : '';
                        
                        document.getElementById('preview-pdf').innerHTML = f.pdf_file 
                            ? `<div class="alert alert-info py-2 mt-2 mb-0 small"><i class="fas fa-file-pdf me-2"></i><a href="../${f.pdf_file}" target="_blank" class="alert-link">PDF Attuale</a></div>` 
                            : '';

                        modalTitle.textContent = 'Modifica Volantino';
                        flyerModal.show();
                        currentStep = 0;
                        updateWizard();
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

    saveBtn.addEventListener('click', function() {
        if (!flyerForm.checkValidity()) {
            flyerForm.reportValidity();
            return;
        }
        
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