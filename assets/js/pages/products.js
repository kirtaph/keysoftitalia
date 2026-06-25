document.addEventListener('DOMContentLoaded', function() {

    // --- GLOBAL MODALS ---
    const modals = {
        'Product': new bootstrap.Modal(document.getElementById('productModal')),
        'Device': new bootstrap.Modal(document.getElementById('deviceModal')),
        'Brand': new bootstrap.Modal(document.getElementById('brandModal')),
        'Model': new bootstrap.Modal(document.getElementById('modelModal'))
    };

    let pendingParentModal = null;
    let currentStep = 1;
    const totalSteps = 3;

    // --- WIZARD LOGIC ---
    function showStep(step) {
        document.querySelectorAll('.wizard-content').forEach(el => el.classList.add('d-none'));
        document.getElementById(`step${step}`).classList.remove('d-none');

        document.querySelectorAll('.wizard-steps .step').forEach(el => {
            el.classList.remove('active');
            if(el.dataset.step == step) el.classList.add('active');
        });

        document.getElementById('prevStepBtn').style.visibility = step === 1 ? 'hidden' : 'visible';
        if (step === totalSteps) {
            document.getElementById('nextStepBtn').classList.add('d-none');
            document.getElementById('saveProductBtn').classList.remove('d-none');
        } else {
            document.getElementById('nextStepBtn').classList.remove('d-none');
            document.getElementById('saveProductBtn').classList.add('d-none');
        }
        currentStep = step;
    }

    document.getElementById('nextStepBtn').addEventListener('click', () => {
        if(currentStep < totalSteps) showStep(currentStep + 1);
    });

    document.getElementById('prevStepBtn').addEventListener('click', () => {
        if(currentStep > 1) showStep(currentStep - 1);
    });

    // --- PRODUCT CRUD ---
    const productsTableBody = document.getElementById('productsTableBody');
    const productForm = document.getElementById('productForm');

    const filterSearch = document.getElementById('filterSearch');
    const filterBrand = document.getElementById('filterBrand');
    const filterGrade = document.getElementById('filterGrade');
    const filterStatus = document.getElementById('filterStatus');
    const resetFiltersBtn = document.getElementById('resetFiltersBtn');

    function fetchProducts() {
        const params = new URLSearchParams({
            action: 'list',
            search: filterSearch.value,
            brand_id: filterBrand.value,
            grade: filterGrade.value,
            status: filterStatus.value
        });

        fetch(`ajax_actions/product_actions.php?${params}`)
            .then(r => r.json())
            .then(data => {
                if (data.status === 'success') {
                    productsTableBody.innerHTML = '';
                    if (data.products.length === 0) {
                        productsTableBody.innerHTML = '<tr><td colspan="6" class="text-center py-4 text-muted">Nessun prodotto trovato.</td></tr>';
                        return;
                    }
                    data.products.forEach(p => {
                        const imgSrc = p.cover_image ? `../${p.cover_image}` : '';
                        const imgHtml = imgSrc
                            ? `<img src="${imgSrc}" alt="${p.model_name}" style="width: 100%; height: 100%; object-fit: cover;">`
                            : `<i class="fas fa-mobile-alt text-muted fa-2x"></i>`;

                        const gradeBadge = {
                            'Nuovo': 'bg-success',
                            'Expo': 'bg-info text-dark',
                            'A+': 'bg-primary',
                            'A': 'bg-primary bg-opacity-75',
                            'B': 'bg-secondary',
                            'C': 'bg-warning text-dark'
                        }[p.grade] || 'bg-secondary';

                        const row = `
                            <tr id="product-${p.id}">
                                <td>
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center border" style="width: 60px; height: 60px; overflow: hidden;">
                                        ${imgHtml}
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-bold text-dark">${p.model_name}</div>
                                    <div class="small text-muted mb-1">${p.brand_name} &bull; ${p.sku}</div>
                                    <div>
                                        ${p.color ? `<span class="badge bg-light text-dark border me-1">${p.color}</span>` : ''}
                                        ${p.storage_gb ? `<span class="badge bg-light text-dark border">${p.storage_gb} GB</span>` : ''}
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="badge ${gradeBadge}">${p.grade || '-'}</span>
                                </td>
                                <td>
                                    <div class="fw-bold text-primary">€ ${p.price_eur}</div>
                                    ${p.list_price ? `<div class="small text-decoration-line-through text-muted">€ ${p.list_price}</div>` : ''}
                                </td>
                                <td class="text-center">
                                    ${p.is_available == 1
                                        ? '<i class="fas fa-check-circle text-success fa-lg me-2" data-bs-toggle="tooltip" title="Disponibile"></i>'
                                        : '<i class="fas fa-times-circle text-danger fa-lg me-2" data-bs-toggle="tooltip" title="Non Disponibile"></i>'}
                                    ${p.is_featured == 1
                                        ? '<i class="fas fa-star text-warning fa-lg" data-bs-toggle="tooltip" title="In Vetrina"></i>'
                                        : '<i class="far fa-star text-muted fa-lg" style="opacity: 0.3;"></i>'}
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-outline-primary edit-btn me-1" data-id="${p.id}" title="Modifica"><i class="fas fa-edit"></i></button>
                                    <button class="btn btn-sm btn-outline-danger delete-btn" data-id="${p.id}" title="Elimina"><i class="fas fa-trash-alt"></i></button>
                                </td>
                            </tr>
                        `;
                        productsTableBody.insertAdjacentHTML('beforeend', row);
                    });

                    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
                    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                      return new bootstrap.Tooltip(tooltipTriggerEl)
                    })
                }
            });
    }

    filterSearch.addEventListener('input', () => fetchProducts());
    filterBrand.addEventListener('change', () => fetchProducts());
    filterGrade.addEventListener('change', () => fetchProducts());
    filterStatus.addEventListener('change', () => fetchProducts());

    resetFiltersBtn.addEventListener('click', () => {
        filterSearch.value = '';
        filterBrand.value = '';
        filterGrade.value = '';
        filterStatus.value = '';
        fetchProducts();
    });

    document.getElementById('addProductBtn').addEventListener('click', () => {
        productForm.reset();
        document.getElementById('productId').value = '';
        document.getElementById('existing-images-container').innerHTML = '';
        document.getElementById('productModalLabel').textContent = 'Aggiungi Prodotto';
        showStep(1);
    });

    productsTableBody.addEventListener('click', (e) => {
        const editBtn = e.target.closest('.edit-btn');
        const deleteBtn = e.target.closest('.delete-btn');

        if (editBtn) {
            const id = editBtn.dataset.id;
            fetch(`ajax_actions/product_actions.php?action=get&id=${id}`)
                .then(r => r.json())
                .then(data => {
                    if (data.status === 'success') {
                        const p = data.product;
                        document.getElementById('productId').value = p.id;
                        document.getElementById('model_id').value = p.model_id;
                        document.getElementById('sku').value = p.sku;
                        document.getElementById('color').value = p.color;
                        document.getElementById('storage_gb').value = p.storage_gb;
                        document.getElementById('grade').value = p.grade;
                        document.getElementById('list_price').value = p.list_price;
                        document.getElementById('price_eur').value = p.price_eur;
                        document.getElementById('short_desc').value = p.short_desc;
                        document.getElementById('full_desc').value = p.full_desc;
                        document.getElementById('is_available').checked = (p.is_available == 1);
                        document.getElementById('is_featured').checked = (p.is_featured == 1);
                        document.getElementById('productModalLabel').textContent = 'Modifica Prodotto';

                        const imgContainer = document.getElementById('existing-images-container');
                        imgContainer.innerHTML = '';
                        if (p.images && p.images.length > 0) {
                            p.images.forEach(img => {
                                const isCover = (img.is_cover == 1);
                                const html = `
                                    <div class="col-md-3 col-6 mb-2" id="image-${img.id}" data-id="${img.id}">
                                        <div class="image-card ${isCover ? 'is-cover' : ''}">
                                            ${isCover ? '<div class="cover-badge">COPERTINA</div>' : ''}
                                            <img src="../${img.path}" alt="Product Image">
                                            <div class="image-actions">
                                                <button type="button" class="btn btn-sm btn-light text-primary set-cover-btn" data-id="${img.id}" title="Imposta Copertina"><i class="fas fa-star"></i></button>
                                                <button type="button" class="btn btn-sm btn-light text-danger delete-image-btn" data-id="${img.id}" title="Elimina"><i class="fas fa-trash"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                `;
                                imgContainer.insertAdjacentHTML('beforeend', html);
                            });
                            new Sortable(imgContainer, { animation: 150 });
                        }
                        showStep(1);
                        modals['Product'].show();
                    }
                });
        }

        if (deleteBtn) {
            if (confirm('Eliminare questo prodotto?')) {
                const fd = new FormData();
                fd.append('action', 'delete');
                fd.append('id', deleteBtn.dataset.id);
                fetch('ajax_actions/product_actions.php', { method: 'POST', body: fd })
                    .then(r => r.json())
                    .then(d => { if(d.status === 'success') fetchProducts(); });
            }
        }
    });

    document.getElementById('saveProductBtn').addEventListener('click', () => {
        const imgContainer = document.getElementById('existing-images-container');
        if (imgContainer.children.length > 0 && document.getElementById('productId').value) {
            const ids = [...imgContainer.children].map(el => el.dataset.id);
            const coverEl = imgContainer.querySelector('.is-cover');
            const coverId = coverEl ? coverEl.closest('.col-md-3').dataset.id : null;

            const fd = new FormData();
            fd.append('action', 'update_image_details');
            fd.append('product_id', document.getElementById('productId').value);
            if(coverId) fd.append('cover_image_id', coverId);
            ids.forEach(id => fd.append('sort_order[]', id));
            fetch('ajax_actions/product_actions.php', { method: 'POST', body: fd });
        }

        const fd = new FormData(productForm);
        fd.append('action', document.getElementById('productId').value ? 'edit' : 'add');
        if(!document.getElementById('is_available').checked) fd.set('is_available', 0);
        if(!document.getElementById('is_featured').checked) fd.set('is_featured', 0);

        fetch('ajax_actions/product_actions.php', { method: 'POST', body: fd })
            .then(r => r.json())
            .then(data => {
                if(data.status === 'success') {
                    modals['Product'].hide();
                    fetchProducts();
                } else {
                    alert(data.message);
                }
            });
    });

    // Image Actions
    document.getElementById('existing-images-container').addEventListener('click', (e) => {
        const delBtn = e.target.closest('.delete-image-btn');
        const coverBtn = e.target.closest('.set-cover-btn');

        if(delBtn) {
            if(confirm('Eliminare immagine?')) {
                const fd = new FormData();
                fd.append('action', 'delete_image');
                fd.append('id', delBtn.dataset.id);
                fetch('ajax_actions/product_actions.php', { method: 'POST', body: fd })
                    .then(r => r.json())
                    .then(d => { if(d.status === 'success') document.getElementById(`image-${delBtn.dataset.id}`).remove(); });
            }
        }
        if(coverBtn) {
            const container = document.getElementById('existing-images-container');
            container.querySelectorAll('.image-card').forEach(c => {
                c.classList.remove('is-cover');
                const badge = c.querySelector('.cover-badge');
                if(badge) badge.remove();
            });
            const card = coverBtn.closest('.image-card');
            card.classList.add('is-cover');
            card.insertAdjacentHTML('afterbegin', '<div class="cover-badge">COPERTINA</div>');
        }
    });

    // --- AI & TOOLS ---
    document.getElementById('generateDescBtn').addEventListener('click', () => {
        const modelSelect = document.getElementById('model_id');
        const modelName = modelSelect.options[modelSelect.selectedIndex].text;
        const color = document.getElementById('color').value;
        const storage = document.getElementById('storage_gb').value;
        const grade = document.getElementById('grade').value;

        if(!modelSelect.value) { alert('Seleziona un modello prima.'); return; }

        const short = `${modelName} ${storage}GB ${color} - Grado ${grade}`;
        const full = `Scopri ${modelName} ricondizionato garantito da Key Soft Italia.
        
Specifiche:
- Modello: ${modelName}
- Colore: ${color}
- Memoria: ${storage} GB
- Condizioni: Grado ${grade} (Testato e Funzionante al 100%)

Il dispositivo è stato sottoposto a rigidi test di qualità dai nostri tecnici certificati. Include garanzia 12 mesi.`;

        document.getElementById('short_desc').value = short;
        document.getElementById('full_desc').value = full;
    });

    document.getElementById('searchImagesBtn').addEventListener('click', () => {
        const modelSelect = document.getElementById('model_id');
        const modelName = modelSelect.options[modelSelect.selectedIndex].text;
        const color = document.getElementById('color').value;

        if(!modelSelect.value) { alert('Seleziona un modello prima.'); return; }

        const query = encodeURIComponent(`${modelName} ${color} official render`);
        window.open(`https://www.google.com/search?tbm=isch&q=${query}`, '_blank');
    });

    // --- INLINE CREATION LOGIC (Nested Modals) ---
    function setupNestedModal(triggerBtnId, currentModalName, targetModalName, resetFn) {
        document.getElementById(triggerBtnId).addEventListener('click', () => {
            modals[currentModalName].hide();
            pendingParentModal = modals[currentModalName];
            resetFn();
            modals[targetModalName].show();
        });
    }

    setupNestedModal('quickAddModelBtn', 'Product', 'Model', () => {
        document.getElementById('modelForm').reset();
        document.getElementById('modelId').value = '';
    });

    setupNestedModal('quickAddBrandBtn', 'Model', 'Brand', () => {
        document.getElementById('brandForm').reset();
        document.getElementById('brandId').value = '';
    });

    setupNestedModal('quickAddDeviceBtn', 'Brand', 'Device', () => {
        document.getElementById('deviceForm').reset();
        document.getElementById('deviceId').value = '';
    });

    function handleSave(entity, formId, actionFile, reloadParentFn) {
        document.getElementById(`save${entity}Btn`).addEventListener('click', () => {
            const fd = new FormData(document.getElementById(formId));
            const id = document.getElementById(`${entity.toLowerCase()}Id`).value;
            fd.append('action', id ? 'edit' : 'add');

            fetch(`ajax_actions/${actionFile}`, { method: 'POST', body: fd })
                .then(r => r.json())
                .then(data => {
                    if(data.status === 'success') {
                        modals[entity].hide();
                        if(pendingParentModal) {
                            pendingParentModal.show();
                            if(reloadParentFn) reloadParentFn();
                            pendingParentModal = null;
                        } else {
                            location.reload();
                        }
                    } else {
                        alert(data.message);
                    }
                });
        });
    }

    handleSave('Device', 'deviceForm', 'device_actions.php', () => location.reload());
    handleSave('Brand', 'brandForm', 'brand_actions.php', () => location.reload());
    handleSave('Model', 'modelForm', 'model_actions.php', () => location.reload());

    // Import CSV Logic
    let previewData = [];
    const importForm = document.getElementById('importProductForm');
    const step1 = document.getElementById('importStep1');
    const step2 = document.getElementById('importStep2');
    const previewBody = document.getElementById('previewTableBody');
    const selectAll = document.getElementById('selectAllPreview');
    const countSpan = document.getElementById('selectedCount');

    if(importForm) {
        importForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const btn = document.getElementById('previewBtn');
            const originalText = btn.innerHTML;

            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Caricamento...';

            const fd = new FormData(this);

            fetch('ajax_actions/import_products.php', {
                method: 'POST',
                body: fd
            })
            .then(r => r.json())
            .then(data => {
                if(data.status === 'success') {
                    previewData = data.data;
                    renderPreview(previewData);

                    const s1 = document.getElementById('importStep1');
                    const s2 = document.getElementById('importStep2');

                    if(s1 && s2) {
                        s1.style.display = 'none';
                        s2.style.display = 'block';
                    }
                    updateCount();
                } else {
                    alert(data.message);
                }
            })
            .catch(err => alert('Errore di connessione'))
            .finally(() => {
                btn.disabled = false;
                btn.innerHTML = originalText;
            });
        });

        document.getElementById('backToUploadBtn').addEventListener('click', () => {
            step2.classList.add('d-none');
            step1.classList.remove('d-none');
            importForm.reset();
        });

        selectAll.addEventListener('change', (e) => {
            document.querySelectorAll('.preview-check').forEach(cb => cb.checked = e.target.checked);
            updateCount();
        });

        document.getElementById('confirmImportBtn').addEventListener('click', function() {
            const selectedIndexes = Array.from(document.querySelectorAll('.preview-check:checked')).map(cb => cb.value);

            if(selectedIndexes.length === 0) {
                alert('Seleziona almeno un prodotto.');
                return;
            }

            const selectedProducts = selectedIndexes.map(i => previewData[i]);
            const btn = this;
            const originalText = btn.innerHTML;

            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Importazione...';

            const fd = new FormData();
            fd.append('action', 'import');
            fd.append('products', JSON.stringify(selectedProducts));

            fetch('ajax_actions/import_products.php', {
                method: 'POST',
                body: fd
            })
            .then(r => r.json())
            .then(data => {
                if(data.status === 'success') {
                    alert(data.message);
                    location.reload();
                } else {
                    alert(data.message);
                }
            })
            .catch(err => alert('Errore durante l\'importazione'))
            .finally(() => {
                btn.disabled = false;
                btn.innerHTML = originalText;
            });
        });
    }

    function renderPreview(data) {
        const html = data.map((p, index) => `
            <tr>
                <td><input type="checkbox" class="form-check-input preview-check" value="${index}" checked onchange="updateCount()"></td>
                <td><input type="text" class="form-control form-control-sm" value="${p.sku}" onchange="updatePreviewData(${index}, 'sku', this.value)"></td>
                <td><input type="text" class="form-control form-control-sm" value="${p.brand}" onchange="updatePreviewData(${index}, 'brand', this.value)"></td>
                <td><input type="text" class="form-control form-control-sm" value="${p.model}" onchange="updatePreviewData(${index}, 'model', this.value)"></td>
                <td>
                    <div class="input-group input-group-sm" style="width: 100px;">
                        <input type="number" class="form-control" value="${p.storage}" onchange="updatePreviewData(${index}, 'storage', this.value)">
                        <span class="input-group-text">GB</span>
                    </div>
                </td>
                <td>
                    <div class="input-group input-group-sm" style="width: 100px;">
                        <span class="input-group-text">€</span>
                        <input type="number" class="form-control" value="${p.price}" step="0.01" onchange="updatePreviewData(${index}, 'price', this.value)">
                    </div>
                </td>
                <td>
                    <select class="form-select form-select-sm" onchange="updatePreviewData(${index}, 'grade', this.value)">
                        <option value="Nuovo" ${p.grade === 'Nuovo' ? 'selected' : ''}>Nuovo</option>
                        <option value="A+" ${p.grade === 'A+' ? 'selected' : ''}>A+</option>
                        <option value="A" ${p.grade === 'A' ? 'selected' : ''}>A</option>
                        <option value="B" ${p.grade === 'B' ? 'selected' : ''}>B</option>
                        <option value="C" ${p.grade === 'C' ? 'selected' : ''}>C</option>
                    </select>
                </td>
            </tr>
        `).join('');

        if(previewBody) {
            previewBody.innerHTML = html;
        }
    }

    window.updatePreviewData = function(index, field, value) {
        if(previewData[index]) {
            previewData[index][field] = value;
        }
    }

    window.updateCount = function() {
        const count = document.querySelectorAll('.preview-check:checked').length;
        document.getElementById('selectedCount').innerText = count;
    }

    fetchProducts();
});
