<?php
include_once 'includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="section-title"><i class="fas fa-video me-2 text-primary"></i>Gestione Video Prodotti</h2>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#videoModal" id="addVideoBtn">
        <i class="fas fa-plus"></i> Aggiungi Video
    </button>
</div>

<!-- Videos Table -->
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 80px;">Copertina</th>
                        <th>Titolo & URL Facebook</th>
                        <th>Categoria</th>
                        <th>Durata</th>
                        <th class="text-center">In Evidenza</th>
                        <th class="text-center">Stato</th>
                        <th class="text-center" style="width: 120px;">Azioni</th>
                    </tr>
                </thead>
                <tbody id="videosTableBody">
                    <!-- Videos will be loaded here via AJAX -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal for Add/Edit Video -->
<div class="modal fade" id="videoModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="videoModalLabel">Nuovo Video Prodotto</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="videoForm" enctype="multipart/form-data">
                    <input type="hidden" id="videoId" name="id">
                    
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label fw-bold">Titolo Video *</label>
                            <input type="text" class="form-control" id="title" name="title" required placeholder="es. Presentazione iPhone 15 Pro Max Ricondizionato">
                        </div>
                        
                        <div class="col-md-12">
                            <label class="form-label fw-bold">URL Video Facebook *</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fab fa-facebook text-primary"></i></span>
                                <input type="url" class="form-control" id="fb_video_url" name="fb_video_url" required placeholder="https://www.facebook.com/watch/?v=...">
                            </div>
                            <small class="text-muted">Incolla l'URL completo del video su Facebook (es. da watch/?v=... o video da pagina).</small>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Categoria *</label>
                            <select class="form-select" id="category" name="category" required>
                                <option value="prodotti">Presentazione Prodotti</option>
                                <option value="recensioni">Recensioni</option>
                                <option value="novita">Novità</option>
                                <option value="consigli">Consigli & Curiosità</option>
                                <option value="tutorial">Tutorial</option>
                            </select>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Durata (es. 04:30)</label>
                            <input type="text" class="form-control" id="duration" name="duration" placeholder="es. 03:45">
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-bold">Descrizione</label>
                            <textarea class="form-control" id="description" name="description" rows="3" placeholder="Scrivi una breve descrizione del video..."></textarea>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-bold">Immagine di Copertina (Opzionale)</label>
                            <input type="file" class="form-control" id="cover_image" name="cover_image" accept="image/*">
                            <div class="form-text">Carica un'immagine personalizzata per la griglia del sito. Se vuoto, verrà mostrato un segnaposto colorato.</div>
                            <div id="preview-cover" class="mt-2"></div>
                        </div>

                        <div class="col-md-6 d-flex align-items-center">
                            <div class="form-check form-switch mt-3">
                                <input class="form-check-input" type="checkbox" role="switch" id="is_featured" name="is_featured">
                                <label class="form-check-label fw-bold" for="is_featured">Mostra in Evidenza (Hero)</label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Stato Pubblicazione *</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="1">Pubblicato</option>
                                <option value="0">Bozza (Nascondi)</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                <button type="button" class="btn btn-primary" id="saveVideoBtn"><i class="fas fa-save me-1"></i> Salva Video</button>
            </div>
        </div>
    </div>
</div>

<?php include_once 'includes/footer.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const videoModal = new bootstrap.Modal(document.getElementById('videoModal'));
    const modalTitle = document.getElementById('videoModalLabel');
    const videoForm = document.getElementById('videoForm');
    const videoIdInput = document.getElementById('videoId');
    const videosTableBody = document.getElementById('videosTableBody');
    const saveVideoBtn = document.getElementById('saveVideoBtn');
    
    function fetchVideos() {
        fetch('ajax_actions/video_actions.php?action=list')
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    videosTableBody.innerHTML = '';
                    if (data.videos.length === 0) {
                        videosTableBody.innerHTML = '<tr><td colspan="7" class="text-center py-4 text-muted">Nessun video presente.</td></tr>';
                        return;
                    }
                    data.videos.forEach(video => {
                        const coverHtml = video.cover_image 
                            ? `<img src="../${video.cover_image}" class="rounded border" style="width: 70px; height: 50px; object-fit: cover;">`
                            : `<div class="bg-light rounded border d-flex align-items-center justify-content-center text-muted" style="width: 70px; height: 50px;"><i class="fas fa-video"></i></div>`;

                        const statusBadge = video.status == 1 
                            ? '<span class="badge bg-success"><i class="fas fa-check me-1"></i>Pubblicato</span>' 
                            : '<span class="badge bg-secondary"><i class="fas fa-pen me-1"></i>Bozza</span>';

                        const featuredIcon = video.is_featured == 1
                            ? '<i class="fas fa-star text-warning fa-lg" title="In Evidenza"></i>'
                            : '<i class="far fa-star text-muted fa-lg" style="opacity: 0.3;"></i>';

                        let categoryLabel = '';
                        switch (video.category) {
                            case 'prodotti': categoryLabel = 'Presentazione Prodotti'; break;
                            case 'recensioni': categoryLabel = 'Recensioni'; break;
                            case 'novita': categoryLabel = 'Novità'; break;
                            case 'consigli': categoryLabel = 'Consigli & Curiosità'; break;
                            case 'tutorial': categoryLabel = 'Tutorial'; break;
                            default: categoryLabel = video.category;
                        }

                        const row = `
                            <tr id="video-${video.id}">
                                <td>${coverHtml}</td>
                                <td>
                                    <div class="fw-bold text-dark">${video.title}</div>
                                    <div class="input-group input-group-sm mt-1" style="max-width: 350px;">
                                        <input type="text" class="form-control bg-light border-0 text-muted" value="${video.fb_video_url}" readonly style="font-size: 0.8rem;">
                                        <button class="btn btn-outline-secondary border-0 copy-link-btn" data-link="${video.fb_video_url}" title="Copia Link"><i class="fas fa-copy"></i></button>
                                    </div>
                                </td>
                                <td><span class="badge bg-light text-dark border">${categoryLabel}</span></td>
                                <td>${video.duration || '<span class="text-muted">-</span>'}</td>
                                <td class="text-center">${featuredIcon}</td>
                                <td class="text-center">${statusBadge}</td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-outline-primary edit-btn me-1" data-id="${video.id}" title="Modifica"><i class="fas fa-edit"></i></button>
                                    <button class="btn btn-sm btn-outline-danger delete-btn" data-id="${video.id}" title="Elimina"><i class="fas fa-trash-alt"></i></button>
                                </td>
                            </tr>
                        `;
                        videosTableBody.insertAdjacentHTML('beforeend', row);
                    });
                }
            });
    }

    videosTableBody.addEventListener('click', function(e) {
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
        videoForm.reset();
        videoIdInput.value = '';
        modalTitle.textContent = 'Nuovo Video Prodotto';
        document.getElementById('preview-cover').innerHTML = '';
    }

    document.getElementById('addVideoBtn').addEventListener('click', () => {
        resetForm();
    });

    videosTableBody.addEventListener('click', function(e) {
        const editBtn = e.target.closest('.edit-btn');
        if (editBtn) {
            const id = editBtn.dataset.id;
            fetch(`ajax_actions/video_actions.php?action=get&id=${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        const v = data.video;
                        videoIdInput.value = v.id;
                        document.getElementById('title').value = v.title;
                        document.getElementById('fb_video_url').value = v.fb_video_url;
                        document.getElementById('category').value = v.category;
                        document.getElementById('duration').value = v.duration || '';
                        document.getElementById('description').value = v.description || '';
                        document.getElementById('status').value = v.status;
                        document.getElementById('is_featured').checked = !!parseInt(v.is_featured);
                        
                        document.getElementById('preview-cover').innerHTML = v.cover_image 
                            ? `<img src="../${v.cover_image}" class="preview-thumbnail mt-2">` 
                            : '';

                        modalTitle.textContent = 'Modifica Video Prodotto';
                        videoModal.show();
                    }
                });
        }
    });

    videosTableBody.addEventListener('click', function(e) {
        const deleteBtn = e.target.closest('.delete-btn');
        if (deleteBtn) {
            const id = deleteBtn.dataset.id;
            if (confirm('Sei sicuro di voler eliminare questo video?')) {
                const formData = new FormData();
                formData.append('action', 'delete');
                formData.append('id', id);

                fetch('ajax_actions/video_actions.php', { method: 'POST', body: formData })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            fetchVideos();
                        } else {
                            alert(data.message || 'Errore durante l\'eliminazione.');
                        }
                    });
            }
        }
    });

    saveVideoBtn.addEventListener('click', function() {
        if (!videoForm.checkValidity()) {
            videoForm.reportValidity();
            return;
        }
        
        const formData = new FormData(videoForm);
        formData.append('action', videoIdInput.value ? 'edit' : 'add');
        
        fetch('ajax_actions/video_actions.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                videoModal.hide();
                fetchVideos();
            } else {
                alert(data.message || 'Si è verificato un errore.');
            }
        });
    });

    fetchVideos();
});
</script>
