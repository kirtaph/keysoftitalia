<?php
include_once 'includes/header.php';
// Fetching models for the dropdown
$models_stmt = $pdo->query('SELECT m.id, m.name, b.name as brand_name FROM models m JOIN brands b ON m.brand_id = b.id ORDER BY b.name, m.name');
$models = $models_stmt->fetchAll();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="section-title">Gestione Prodotti</h2>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#productModal" id="addProductBtn">
        <i class="fas fa-plus"></i> Aggiungi Prodotto
    </button>
</div>

<!-- Products Table -->
<table class="table table-striped table-bordered">
    <thead class="table-dark">
        <tr>
            <th>SKU</th>
            <th>Modello</th>
            <th>Colore</th>
            <th>Storage</th>
            <th>Grado</th>
            <th>Prezzo di Listino</th>
            <th>Prezzo di Vendita</th>
            <th>Disponibile</th>
            <th>In Vetrina</th>
            <th class="text-center">Azioni</th>
        </tr>
    </thead>
    <tbody id="productsTableBody">
        <!-- Products will be loaded here via AJAX -->
    </tbody>
</table>

<!-- Modal for Add/Edit Product -->
<div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="productModalLabel">Aggiungi Prodotto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="productForm" enctype="multipart/form-data">
                    <input type="hidden" id="productId" name="id">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="model_id" class="form-label">Modello</label>
                                <select class="form-select" id="model_id" name="model_id" required>
                                    <option value="">Seleziona un modello</option>
                                    <?php foreach ($models as $model): ?>
                                        <option value="<?php echo $model['id']; ?>"><?php echo htmlspecialchars($model['brand_name'] . ' - ' . $model['name'], ENT_QUOTES, 'UTF-8'); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="sku" class="form-label">SKU</label>
                                <input type="text" class="form-control" id="sku" name="sku" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="color" class="form-label">Colore</label>
                                <input type="text" class="form-control" id="color" name="color">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="storage_gb" class="form-label">Storage (GB)</label>
                                <input type="number" class="form-control" id="storage_gb" name="storage_gb">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="grade" class="form-label">Grado</label>
                                <select class="form-select" id="grade" name="grade">
                                    <option value="Nuovo">Nuovo</option>
                                    <option value="Expo">Expo</option>
                                    <option value="A+">A+</option>
                                    <option value="A">A</option>
                                    <option value="B">B</option>
                                    <option value="C">C</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="list_price" class="form-label">Prezzo di Listino (€)</label>
                                <input type="number" step="0.01" class="form-control" id="list_price" name="list_price">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="price_eur" class="form-label">Prezzo di Vendita (€)</label>
                                <input type="number" step="0.01" class="form-control" id="price_eur" name="price_eur" required>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="short_desc" class="form-label">Descrizione Breve</label>
                        <textarea class="form-control" id="short_desc" name="short_desc" rows="2"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="full_desc" class="form-label">Descrizione Completa</label>
                        <textarea class="form-control" id="full_desc" name="full_desc" rows="5"></textarea>
                    </div>
                     <div class="row">
                        <div class="col-md-6">
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="is_available" name="is_available" value="1" checked>
                                <label class="form-check-label" for="is_available">Disponibile</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" value="1">
                                <label class="form-check-label" for="is_featured">In Vetrina</label>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="product_images" class="form-label">Aggiungi Immagini</label>
                        <input type="file" class="form-control" id="product_images" name="product_images[]" multiple>
                    </div>
                    <div id="existing-images-container" class="mb-3">
                        <!-- Existing images will be loaded here -->
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                <button type="button" class="btn btn-primary" id="saveProductBtn">Salva</button>
            </div>
        </div>
    </div>
</div>

<?php include_once 'includes/footer.php'; ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const productModal = new bootstrap.Modal(document.getElementById('productModal'));
    const modalTitle = document.getElementById('productModalLabel');
    const productForm = document.getElementById('productForm');
    const productIdInput = document.getElementById('productId');
    const productsTableBody = document.getElementById('productsTableBody');
    let sortable;

    function fetchProducts() {
        fetch('ajax_actions/product_actions.php?action=list')
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    productsTableBody.innerHTML = '';
                    data.products.forEach(product => {
                        const row = `
                            <tr id="product-${product.id}">
                                <td>${product.sku}</td>
                                <td>${product.brand_name} ${product.model_name}</td>
                                <td>${product.color}</td>
                                <td>${product.storage_gb} GB</td>
                                <td>${product.grade}</td>
                                <td>€ ${product.list_price}</td>
                                <td>€ ${product.price_eur}</td>
                                <td class="text-center">${product.is_available ? '<i class="fas fa-check-circle text-success"></i>' : '<i class="fas fa-times-circle text-danger"></i>'}</td>
                                <td class="text-center">${product.is_featured ? '<i class="fas fa-check-circle text-success"></i>' : '<i class="fas fa-times-circle text-danger"></i>'}</td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-outline-primary edit-btn" data-id="${product.id}" data-bs-toggle="tooltip" title="Modifica">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger delete-btn" data-id="${product.id}" data-bs-toggle="tooltip" title="Elimina">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </td>
                            </tr>
                        `;
                        productsTableBody.insertAdjacentHTML('beforeend', row);
                    });
                }
            });
    }

    function resetForm() {
        productForm.reset();
        productIdInput.value = '';
        modalTitle.textContent = 'Aggiungi Prodotto';
        document.getElementById('existing-images-container').innerHTML = '';
    }

    document.getElementById('addProductBtn').addEventListener('click', () => {
        resetForm();
    });

    productsTableBody.addEventListener('click', function(e) {
        const editBtn = e.target.closest('.edit-btn');
        const deleteBtn = e.target.closest('.delete-btn');

        if (editBtn) {
            const id = editBtn.dataset.id;
            fetch(`ajax_actions/product_actions.php?action=get&id=${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        const p = data.product;
                        productIdInput.value = p.id;
                        document.getElementById('model_id').value = p.model_id;
                        document.getElementById('sku').value = p.sku;
                        document.getElementById('color').value = p.color;
                        document.getElementById('storage_gb').value = p.storage_gb;
                        document.getElementById('grade').value = p.grade;
                        document.getElementById('list_price').value = p.list_price;
                        document.getElementById('price_eur').value = p.price_eur;
                        document.getElementById('short_desc').value = p.short_desc;
                        document.getElementById('full_desc').value = p.full_desc;
                        document.getElementById('is_available').checked = !!parseInt(p.is_available);
                        document.getElementById('is_featured').checked = !!parseInt(p.is_featured);
                        modalTitle.textContent = 'Modifica Prodotto';

                        const imagesContainer = document.getElementById('existing-images-container');
                        imagesContainer.innerHTML = '';
                        if (p.images && p.images.length > 0) {
                            let imagesHTML = '<h6>Immagini Esistenti (Trascina per ordinare)</h6><div class="row sortable-images">';
                            p.images.forEach(img => {
                                const isCoverClass = parseInt(img.is_cover) ? 'is-cover' : '';
                                imagesHTML += `
                                    <div class="col-md-3" id="image-${img.id}" data-id="${img.id}">
                                        <div class="image-thumbnail ${isCoverClass}">
                                            <img src="../${img.path}" class="img-fluid mb-2" alt="Product Image">
                                            <div class="cover-overlay">COPERTINA</div>
                                        </div>
                                        <button type="button" class="btn btn-sm btn-primary set-cover-btn" data-id="${img.id}">Copertina</button>
                                        <button type="button" class="btn btn-sm btn-danger delete-image-btn" data-id="${img.id}">Elimina</button>
                                    </div>
                                `;
                            });
                            imagesHTML += '</div>';
                            imagesContainer.innerHTML = imagesHTML;
                            
                            if (sortable) {
                                sortable.destroy();
                            }
                            sortable = new Sortable(imagesContainer.querySelector('.sortable-images'), {
                                animation: 150,
                            });
                        }

                        productModal.show();
                    } else {
                        alert('Errore nel recupero dei dati del prodotto.');
                    }
                });
        }

        if (deleteBtn) {
            const id = deleteBtn.dataset.id;
            if (confirm('Sei sicuro di voler eliminare questo prodotto?')) {
                const formData = new FormData();
                formData.append('action', 'delete');
                formData.append('id', id);

                fetch('ajax_actions/product_actions.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        document.getElementById(`product-${id}`).remove();
                    } else {
                        alert(data.message || 'Si è verificato un errore.');
                    }
                });
            }
        }
    });

    document.getElementById('saveProductBtn').addEventListener('click', function() {
        if (productIdInput.value) { // Only for existing products
            const sortableContainer = document.querySelector('.sortable-images');
            if (sortableContainer) {
                const imageIds = [...sortableContainer.children].map(el => el.dataset.id);
                const coverImage = sortableContainer.querySelector('.is-cover');
                const coverImageId = coverImage ? coverImage.closest('.col-md-3').dataset.id : null;

                const imageDetailsData = new FormData();
                imageDetailsData.append('action', 'update_image_details');
                imageDetailsData.append('product_id', productIdInput.value);
                if(coverImageId) {
                    imageDetailsData.append('cover_image_id', coverImageId);
                }
                imageIds.forEach(id => {
                    imageDetailsData.append('sort_order[]', id);
                });

                fetch('ajax_actions/product_actions.php', {
                    method: 'POST',
                    body: imageDetailsData
                });
            }
        }

        const formData = new FormData(productForm);
        formData.append('action', productIdInput.value ? 'edit' : 'add');
        
        fetch('ajax_actions/product_actions.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                productModal.hide();
                fetchProducts();
            } else {
                alert(data.message || 'Si è verificato un errore.');
            }
        });
    });

    document.getElementById('existing-images-container').addEventListener('click', function(e) {
        const setCoverBtn = e.target.closest('.set-cover-btn');
        const deleteBtn = e.target.closest('.delete-image-btn');
        if (setCoverBtn) {
            const id = setCoverBtn.dataset.id;
            const images = this.querySelectorAll('.image-thumbnail');
            images.forEach(img => img.classList.remove('is-cover'));
            this.querySelector(`#image-${id} .image-thumbnail`).classList.add('is-cover');
        }
        if (deleteBtn) {
            const id = deleteBtn.dataset.id;
            if (confirm('Sei sicuro di voler eliminare questa immagine?')) {
                const formData = new FormData();
                formData.append('action', 'delete_image');
                formData.append('id', id);

                fetch('ajax_actions/product_actions.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        document.getElementById(`image-${id}`).remove();
                    } else {
                        alert(data.message || 'Si è verificato un errore.');
                    }
                });
            }
        }
    });

    fetchProducts();
});
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.0/Sortable.min.js"></script>
