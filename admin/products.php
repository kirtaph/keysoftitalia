<?php
include_once 'includes/header.php';
// Fetching models for the dropdown
$models_stmt = $pdo->query('SELECT m.id, m.name, b.name as brand_name FROM models m JOIN brands b ON m.brand_id = b.id ORDER BY b.name, m.name');
$models = $models_stmt->fetchAll();

// Fetch Devices and Brands for the nested modals
$devices = $pdo->query('SELECT * FROM devices ORDER BY sort_order ASC')->fetchAll();
$brands = $pdo->query('SELECT b.*, d.name as device_name FROM brands b JOIN devices d ON b.device_id = d.id ORDER BY b.name ASC')->fetchAll();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="section-title">Gestione Prodotti</h2>
    <div>
        <button type="button" class="btn btn-success me-2" data-bs-toggle="modal" data-bs-target="#importProductModal">
            <i class="fas fa-file-csv"></i> Importa CSV
        </button>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#productModal" id="addProductBtn">
            <i class="fas fa-plus"></i> Aggiungi Prodotto
        </button>
    </div>
</div>

<!-- Filters Bar -->
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="fas fa-search text-muted"></i></span>
                    <input type="text" class="form-control border-start-0" id="filterSearch" placeholder="Cerca modello, SKU...">
                </div>
            </div>
            <div class="col-md-2">
                <select class="form-select" id="filterBrand">
                    <option value="">Tutti i Brand</option>
                    <?php 
                    // Fetch distinct brands that have products or all brands? Let's use $brands from top of file
                    // Note: $brands variable is already available from line 8
                    foreach ($brands as $b): ?>
                        <option value="<?php echo $b['id']; ?>"><?php echo htmlspecialchars($b['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-select" id="filterGrade">
                    <option value="">Tutti i Gradi</option>
                    <option value="Nuovo">Nuovo</option>
                    <option value="Expo">Expo</option>
                    <option value="A+">A+</option>
                    <option value="A">A</option>
                    <option value="B">B</option>
                    <option value="C">C</option>
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-select" id="filterStatus">
                    <option value="">Tutti gli Stati</option>
                    <option value="available">Disponibili</option>
                    <option value="featured">In Vetrina</option>
                    <option value="unavailable">Non Disponibili</option>
                </select>
            </div>
            <div class="col-md-2 d-grid">
                <button class="btn btn-outline-secondary" id="resetFiltersBtn">
                    <i class="fas fa-undo me-1"></i> Reset
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Products Table -->
<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 80px;">Foto</th>
                        <th>Prodotto</th>
                        <th class="text-center">Grado</th>
                        <th>Prezzo</th>
                        <th class="text-center">Stato</th>
                        <th class="text-center" style="width: 120px;">Azioni</th>
                    </tr>
                </thead>
                <tbody id="productsTableBody">
                    <!-- Products will be loaded here via AJAX -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal for Add/Edit Product (Wizard) -->
<div class="modal fade" id="productModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="productModalLabel">Aggiungi Prodotto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <!-- Wizard Steps Indicator -->
                <div class="wizard-steps mb-4">
                    <div class="step active" data-step="1">
                        <div class="step-icon"><i class="fas fa-mobile-alt"></i></div>
                        <div class="step-label">Identificazione</div>
                    </div>
                    <div class="step-line"></div>
                    <div class="step" data-step="2">
                        <div class="step-icon"><i class="fas fa-info-circle"></i></div>
                        <div class="step-label">Dettagli</div>
                    </div>
                    <div class="step-line"></div>
                    <div class="step" data-step="3">
                        <div class="step-icon"><i class="fas fa-images"></i></div>
                        <div class="step-label">Media</div>
                    </div>
                </div>

                <form id="productForm" enctype="multipart/form-data">
                    <input type="hidden" id="productId" name="id">
                    
                    <!-- Step 1: Identification -->
                    <div class="wizard-content" id="step1">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label">Modello *</label>
                                <div class="input-group">
                                    <select class="form-select" id="model_id" name="model_id" required>
                                        <option value="">Seleziona un modello</option>
                                        <?php foreach ($models as $model): ?>
                                            <option value="<?php echo $model['id']; ?>"><?php echo htmlspecialchars($model['brand_name'] . ' - ' . $model['name']); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <button class="btn btn-outline-secondary" type="button" id="quickAddModelBtn" title="Nuovo Modello">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">SKU *</label>
                                <input type="text" class="form-control" id="sku" name="sku" required placeholder="es. IPH13-128-BLK-A">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Grado Estetico</label>
                                <select class="form-select" id="grade" name="grade">
                                    <option value="Nuovo">Nuovo (Sigillato)</option>
                                    <option value="Expo">Expo (Come Nuovo)</option>
                                    <option value="A+">Grado A+ (Eccellente)</option>
                                    <option value="A">Grado A (Ottimo)</option>
                                    <option value="B">Grado B (Buono)</option>
                                    <option value="C">Grado C (Economico)</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Colore</label>
                                <input type="text" class="form-control" id="color" name="color" placeholder="es. Midnight Black">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Storage (GB)</label>
                                <input type="number" class="form-control" id="storage_gb" name="storage_gb" placeholder="es. 128">
                            </div>
                        </div>
                    </div>

                    <!-- Step 2: Details & Price -->
                    <div class="wizard-content d-none" id="step2">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Prezzo di Listino (€)</label>
                                <div class="input-group">
                                    <span class="input-group-text">€</span>
                                    <input type="number" step="0.01" class="form-control" id="list_price" name="list_price">
                                </div>
                                <div class="form-text">Prezzo originale barrato (opzionale)</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Prezzo di Vendita (€) *</label>
                                <div class="input-group">
                                    <span class="input-group-text">€</span>
                                    <input type="number" step="0.01" class="form-control fw-bold" id="price_eur" name="price_eur" required>
                                </div>
                            </div>
                            
                            <div class="col-12 mt-4">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <label class="form-label mb-0">Descrizione</label>
                                    <button type="button" class="btn btn-sm btn-outline-primary" id="generateDescBtn">
                                        <i class="fas fa-magic me-1"></i> Genera con AI
                                    </button>
                                </div>
                                <div class="mb-3">
                                    <input type="text" class="form-control mb-2" id="short_desc" name="short_desc" placeholder="Descrizione breve per le card">
                                    <textarea class="form-control" id="full_desc" name="full_desc" rows="5" placeholder="Descrizione completa del prodotto..."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 3: Images & Status -->
                    <div class="wizard-content d-none" id="step3">
                        <div class="row g-3">
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <label class="form-label fw-bold">Galleria Immagini</label>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" id="searchImagesBtn">
                                        <i class="fab fa-google me-1"></i> Cerca Immagini
                                    </button>
                                </div>
                                <div class="upload-area p-4 border rounded bg-light text-center mb-3">
                                    <i class="fas fa-cloud-upload-alt fa-2x text-muted mb-2"></i>
                                    <p class="mb-2">Trascina qui le immagini o clicca per selezionare</p>
                                    <input type="file" class="form-control" id="product_images" name="product_images[]" multiple style="max-width: 300px; margin: 0 auto;">
                                </div>
                                <div id="existing-images-container" class="row g-2">
                                    <!-- Images loaded here -->
                                </div>
                            </div>

                            <div class="col-12 mt-3">
                                <div class="card bg-light border-0">
                                    <div class="card-body">
                                        <h6 class="card-title">Visibilità</h6>
                                        <div class="d-flex gap-4">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="is_available" name="is_available" value="1" checked>
                                                <label class="form-check-label" for="is_available">Disponibile alla vendita</label>
                                            </div>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" value="1">
                                                <label class="form-check-label" for="is_featured">Metti in Vetrina</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-secondary" id="prevStepBtn" style="visibility: hidden;">Indietro</button>
                <div>
                    <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Annulla</button>
                    <button type="button" class="btn btn-primary" id="nextStepBtn">Avanti</button>
                    <button type="button" class="btn btn-success d-none" id="saveProductBtn">Salva Prodotto</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- --- NESTED MODALS FOR INLINE CREATION --- -->

<!-- Device Modal -->
<div class="modal fade" id="deviceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nuovo Dispositivo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="deviceForm">
                    <input type="hidden" id="deviceId" name="id">
                    <div class="mb-3">
                        <label class="form-label">Nome</label>
                        <input type="text" class="form-control" id="deviceName" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Slug</label>
                        <input type="text" class="form-control" id="deviceSlug" name="slug" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ordine</label>
                        <input type="number" class="form-control" id="deviceOrder" name="sort_order" value="0">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="saveDeviceBtn">Salva</button>
            </div>
        </div>
    </div>
</div>

<!-- Brand Modal -->
<div class="modal fade" id="brandModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nuovo Marchio</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="brandForm">
                    <input type="hidden" id="brandId" name="id">
                    <div class="mb-3">
                        <label class="form-label">Dispositivo</label>
                        <div class="input-group">
                            <select class="form-select" id="brandDeviceId" name="device_id" required>
                                <?php foreach ($devices as $d): ?>
                                    <option value="<?php echo $d['id']; ?>"><?php echo htmlspecialchars($d['name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                            <button class="btn btn-outline-secondary" type="button" id="quickAddDeviceBtn" title="Nuovo Dispositivo">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nome Marchio</label>
                        <input type="text" class="form-control" id="brandName" name="name" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="saveBrandBtn">Salva</button>
            </div>
        </div>
    </div>
</div>

<!-- Model Modal -->
<div class="modal fade" id="modelModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nuovo Modello</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="modelForm">
                    <input type="hidden" id="modelId" name="id">
                    <div class="mb-3">
                        <label class="form-label">Marchio</label>
                        <div class="input-group">
                            <select class="form-select" id="modelBrandId" name="brand_id" required>
                                <?php foreach ($brands as $b): ?>
                                    <option value="<?php echo $b['id']; ?>"><?php echo htmlspecialchars($b['name'] . ' (' . $b['device_name'] . ')'); ?></option>
                                <?php endforeach; ?>
                            </select>
                            <button class="btn btn-outline-secondary" type="button" id="quickAddBrandBtn" title="Nuovo Marchio">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nome Modello</label>
                        <input type="text" class="form-control" id="modelName" name="name" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="saveModelBtn">Salva</button>
            </div>
        </div>
    </div>
</div>



<style>
/* Wizard Styles */
.wizard-steps {
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: relative;
    padding: 0 20px;
}
.wizard-steps .step {
    text-align: center;
    z-index: 2;
    background: #fff;
    padding: 0 10px;
    opacity: 0.5;
    transition: all 0.3s;
}
.wizard-steps .step.active {
    opacity: 1;
    font-weight: bold;
    color: var(--bs-primary);
}
.wizard-steps .step-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #f8f9fa;
    border: 2px solid #dee2e6;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 5px;
    transition: all 0.3s;
}
.wizard-steps .step.active .step-icon {
    background: var(--bs-primary);
    color: #fff;
    border-color: var(--bs-primary);
}
.wizard-steps .step-line {
    flex-grow: 1;
    height: 2px;
    background: #dee2e6;
    position: absolute;
    left: 0;
    right: 0;
    top: 20px;
    z-index: 1;
}

/* Image Card Styles */
.image-card {
    position: relative;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    overflow: hidden;
    transition: transform 0.2s;
}
.image-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}
.image-card.is-cover {
    border: 2px solid var(--bs-primary);
}
.image-card img {
    height: 120px;
    object-fit: cover;
    width: 100%;
}
.image-actions {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: rgba(255,255,255,0.9);
    padding: 5px;
    display: flex;
    justify-content: center;
    gap: 5px;
    opacity: 0;
    transition: opacity 0.2s;
}
.image-card:hover .image-actions {
    opacity: 1;
}
.cover-badge {
    position: absolute;
    top: 5px;
    left: 5px;
    background: var(--bs-primary);
    color: white;
    padding: 2px 6px;
    border-radius: 4px;
    font-size: 0.7rem;
    font-weight: bold;
}
</style>

<?php include_once 'includes/footer.php'; ?>

<!-- Import Product Modal -->
<div class="modal fade" id="importProductModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Importa Prodotti da CSV</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <!-- Step 1: Upload -->
                <div id="importStep1">
                    <form id="importProductForm">
                        <input type="hidden" name="action" value="preview">
                        <div class="mb-3">
                            <label class="form-label">Seleziona File CSV</label>
                            <input type="file" class="form-control" name="csv_file" accept=".csv" required>
                            <div class="form-text">Il file deve usare il separatore punto e virgola (;).</div>
                        </div>
                        <div class="alert alert-info small">
                            <i class="fas fa-info-circle me-1"></i>
                            Carica il file per vedere un'anteprima dei prodotti che verranno importati.
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary" id="previewBtn">
                                <i class="fas fa-eye me-2"></i> Vedi Anteprima
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Step 2: Preview -->
                <div id="importStep2" style="display: none;">
                    <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                        <table class="table table-sm table-hover">
                            <thead class="table-light sticky-top">
                                <tr>
                                    <th width="30"><input type="checkbox" id="selectAllPreview" checked></th>
                                    <th>SKU</th>
                                    <th>Brand</th>
                                    <th>Modello</th>
                                    <th>Storage</th>
                                    <th>Prezzo</th>
                                    <th>Grado</th>
                                </tr>
                            </thead>
                            <tbody id="previewTableBody"></tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-between mt-3">
                        <button type="button" class="btn btn-secondary" id="backToUploadBtn">
                            <i class="fas fa-arrow-left me-2"></i> Indietro
                        </button>
                        <button type="button" class="btn btn-success" id="confirmImportBtn">
                            <i class="fas fa-file-import me-2"></i> Importa Selezionati (<span id="selectedCount">0</span>)
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Wizard Styles */
.wizard-steps {
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: relative;
    padding: 0 20px;
}
.wizard-steps .step {
    text-align: center;
    z-index: 2;
    background: #fff;
    padding: 0 10px;
    opacity: 0.5;
    transition: all 0.3s;
}
.wizard-steps .step.active {
    opacity: 1;
    font-weight: bold;
    color: var(--bs-primary);
}
.wizard-steps .step-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #f8f9fa;
    border: 2px solid #dee2e6;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 5px;
    transition: all 0.3s;
}
.wizard-steps .step.active .step-icon {
    background: var(--bs-primary);
    color: #fff;
    border-color: var(--bs-primary);
}
.wizard-steps .step-line {
    flex-grow: 1;
    height: 2px;
    background: #dee2e6;
    position: absolute;
    top: 20px;
    left: 40px;
    right: 40px;
    z-index: 1;
}
</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.0/Sortable.min.js"></script>
<script>
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
            if(el.dataset.step <= step) el.classList.add('active'); // Highlight previous steps too? Maybe just current. Let's do current.
            if(el.dataset.step == step) el.classList.add('active');
        });

        // Buttons
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
    
    // Filter Elements
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
                        // Determine cover image
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
                    
                    // Init Tooltips
                    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
                    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                      return new bootstrap.Tooltip(tooltipTriggerEl)
                    })
                }
            });
    }

    // Filter Events
    filterSearch.addEventListener('input', () => fetchProducts()); // Debounce could be added here
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
        showStep(1); // Reset to step 1
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
                        
                        // Load Images
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
                            // Sortable
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
        // Save Image Order first if needed
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
            // Visual update only, saved on "Save Product"
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

    // Product -> Add Model
    setupNestedModal('quickAddModelBtn', 'Product', 'Model', () => {
        document.getElementById('modelForm').reset();
        document.getElementById('modelId').value = '';
    });

    // Model -> Add Brand
    setupNestedModal('quickAddBrandBtn', 'Model', 'Brand', () => {
        document.getElementById('brandForm').reset();
        document.getElementById('brandId').value = '';
    });

    // Brand -> Add Device
    setupNestedModal('quickAddDeviceBtn', 'Brand', 'Device', () => {
        document.getElementById('deviceForm').reset();
        document.getElementById('deviceId').value = '';
    });

    // Save Handlers for Nested
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
                            if(reloadParentFn) reloadParentFn(); // Reload select in parent
                            pendingParentModal = null;
                        } else {
                            location.reload(); // Fallback
                        }
                    } else {
                        alert(data.message);
                    }
                });
        });
    }

    handleSave('Device', 'deviceForm', 'device_actions.php', () => location.reload()); // Reloading page is easiest for deep nesting
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
        // Preview Action
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
                console.log('Preview Data:', data);
                if(data.status === 'success') {
                    previewData = data.data;
                    renderPreview(previewData);
                    
                    const s1 = document.getElementById('importStep1');
                    const s2 = document.getElementById('importStep2');
                    
                    if(s1 && s2) {
                        s1.style.display = 'none';
                        s2.style.display = 'block';
                        
                        console.log('Steps switched. S1 hidden, S2 shown.');
                    } else {
                        alert('Errore DOM: Elementi step non trovati!');
                        console.error('Steps not found!', s1, s2);
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

        // Back Button
        document.getElementById('backToUploadBtn').addEventListener('click', () => {
            step2.classList.add('d-none');
            step1.classList.remove('d-none');
            importForm.reset();
        });

        // Select All
        selectAll.addEventListener('change', (e) => {
            document.querySelectorAll('.preview-check').forEach(cb => cb.checked = e.target.checked);
            updateCount();
        });

        // Confirm Import
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
                    location.reload(); // Reload to update Brands/Models dropdowns
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
        console.log('Rendering preview for', data.length, 'items');
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
        } else {
            alert('Errore: Tabella non trovata nel DOM');
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
</script>


