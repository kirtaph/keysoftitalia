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

<script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.0/Sortable.min.js"></script>
<script src="../assets/js/pages/products.js"></script>


