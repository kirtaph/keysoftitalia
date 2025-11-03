<?php
include_once 'includes/header.php';

// Logica per gestire le operazioni CRUD
$action = $_GET['action'] ?? 'list';
$id = $_GET['id'] ?? null;

switch ($action) {
    case 'add':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $device_id = $_POST['device_id'];
            $brand_id = $_POST['brand_id'] ?: null;
            $model_id = $_POST['model_id'] ?: null;
            $issue_id = $_POST['issue_id'];
            $min_price = $_POST['min_price'];
            $max_price = $_POST['max_price'] ?: null;
            $notes = $_POST['notes'];
            $is_active = isset($_POST['is_active']) ? 1 : 0;
            $stmt = $pdo->prepare('INSERT INTO price_rules (device_id, brand_id, model_id, issue_id, min_price, max_price, notes, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
            $stmt->execute([$device_id, $brand_id, $model_id, $issue_id, $min_price, $max_price, $notes, $is_active]);
            header('Location: price_rules.php');
            exit;
        }
        break;
    case 'edit':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $device_id = $_POST['device_id'];
            $brand_id = $_POST['brand_id'] ?: null;
            $model_id = $_POST['model_id'] ?: null;
            $issue_id = $_POST['issue_id'];
            $min_price = $_POST['min_price'];
            $max_price = $_POST['max_price'] ?: null;
            $notes = $_POST['notes'];
            $is_active = isset($_POST['is_active']) ? 1 : 0;
            $stmt = $pdo->prepare('UPDATE price_rules SET device_id = ?, brand_id = ?, model_id = ?, issue_id = ?, min_price = ?, max_price = ?, notes = ?, is_active = ? WHERE id = ?');
            $stmt->execute([$device_id, $brand_id, $model_id, $issue_id, $min_price, $max_price, $notes, $is_active, $id]);
            header('Location: price_rules.php');
            exit;
        }
        $stmt = $pdo->prepare('SELECT * FROM price_rules WHERE id = ?');
        $stmt->execute([$id]);
        $rule = $stmt->fetch();
        break;
    case 'delete':
        $stmt = $pdo->prepare('DELETE FROM price_rules WHERE id = ?');
        $stmt->execute([$id]);
        header('Location: price_rules.php');
        exit;
    default:
        $stmt = $pdo->query('SELECT pr.*, d.name as device_name, b.name as brand_name, m.name as model_name, i.label as issue_label FROM price_rules pr JOIN devices d ON pr.device_id = d.id LEFT JOIN brands b ON pr.brand_id = b.id LEFT JOIN models m ON pr.model_id = m.id JOIN issues i ON pr.issue_id = i.id ORDER BY pr.id DESC');
        $rules = $stmt->fetchAll();
        break;
}
$devices_stmt = $pdo->query('SELECT * FROM devices ORDER BY name ASC');
$devices = $devices_stmt->fetchAll();
?>

<div class="section-header">
    <h2 class="section-title">Gestione Regole di Prezzo</h2>
</div>

<?php if ($action === 'list'): ?>
    <a href="?action=add" class="btn btn-primary mb-4">Aggiungi Regola di Prezzo</a>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Dispositivo</th>
                    <th>Marchio</th>
                    <th>Modello</th>
                    <th>Problema</th>
                    <th>Prezzo Min</th>
                    <th>Prezzo Max</th>
                    <th>Note</th>
                    <th>Attivo</th>
                    <th>Azioni</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rules as $rule): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($rule['device_name']); ?></td>
                        <td><?php echo htmlspecialchars($rule['brand_name'] ?? 'N/A'); ?></td>
                        <td><?php echo htmlspecialchars($rule['model_name'] ?? 'N/A'); ?></td>
                        <td><?php echo htmlspecialchars($rule['issue_label']); ?></td>
                        <td><?php echo htmlspecialchars($rule['min_price']); ?></td>
                        <td><?php echo htmlspecialchars($rule['max_price']); ?></td>
                        <td><?php echo htmlspecialchars($rule['notes']); ?></td>
                        <td><?php echo $rule['is_active'] ? 'Sì' : 'No'; ?></td>
                        <td>
                            <a href="?action=edit&id=<?php echo $rule['id']; ?>" class="btn btn-sm btn-outline-orange">Modifica</a>
                            <a href="?action=delete&id=<?php echo $rule['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Sei sicuro?')">Elimina</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php elseif ($action === 'add' || $action === 'edit'): ?>
    <form method="POST">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="device_id" class="form-label">Dispositivo</label>
                    <select class="form-control" id="device_id" name="device_id" required>
                        <option value="">Seleziona un dispositivo</option>
                        <?php foreach ($devices as $device): ?>
                            <option value="<?php echo $device['id']; ?>" <?php echo isset($rule) && $rule['device_id'] == $device['id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($device['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="brand_id" class="form-label">Marchio (opzionale)</label>
                    <select class="form-control" id="brand_id" name="brand_id">
                        <!-- Caricato dinamicamente con JS -->
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="model_id" class="form-label">Modello (opzionale)</label>
                    <select class="form-control" id="model_id" name="model_id">
                        <!-- Caricato dinamicamente con JS -->
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="issue_id" class="form-label">Problema</label>
                    <select class="form-control" id="issue_id" name="issue_id" required>
                        <!-- Caricato dinamicamente con JS -->
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="min_price" class="form-label">Prezzo Minimo</label>
                    <input type="number" step="0.01" class="form-control" id="min_price" name="min_price" value="<?php echo htmlspecialchars($rule['min_price'] ?? ''); ?>" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="max_price" class="form-label">Prezzo Massimo (opzionale)</label>
                    <input type="number" step="0.01" class="form-control" id="max_price" name="max_price" value="<?php echo htmlspecialchars($rule['max_price'] ?? ''); ?>">
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label for="notes" class="form-label">Note</label>
                    <input type="text" class="form-control" id="notes" name="notes" value="<?php echo htmlspecialchars($rule['notes'] ?? ''); ?>">
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group form-check">
                    <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" <?php echo (isset($rule) && $rule['is_active']) || !isset($rule) ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="is_active">Attivo</label>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary"><?php echo $action === 'add' ? 'Aggiungi' : 'Salva Modifiche'; ?></button>
        <a href="price_rules.php" class="btn btn-secondary">Annulla</a>
    </form>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const deviceSelect = document.getElementById('device_id');
            const brandSelect = document.getElementById('brand_id');
            const modelSelect = document.getElementById('model_id');
            const issueSelect = document.getElementById('issue_id');

            const selectedBrandId = '<?php echo $rule['brand_id'] ?? ''; ?>';
            const selectedModelId = '<?php echo $rule['model_id'] ?? ''; ?>';
            const selectedIssueId = '<?php echo $rule['issue_id'] ?? ''; ?>';

            function loadOptions(url, selectElement, selectedValue = '') {
                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        selectElement.innerHTML = `<option value="">${selectElement.id === 'brand_id' || selectElement.id === 'model_id' ? 'Tutti' : 'Seleziona...'}</option>`;
                        data.forEach(item => {
                            const option = document.createElement('option');
                            option.value = item.id;
                            option.textContent = item.name || item.label;
                            if (item.id == selectedValue) {
                                option.selected = true;
                            }
                            selectElement.appendChild(option);
                        });
                    });
            }

            deviceSelect.addEventListener('change', function() {
                loadOptions(`ajax_get_brands.php?device_id=${this.value}`, brandSelect, selectedBrandId);
                loadOptions(`ajax_get_issues.php?device_id=${this.value}`, issueSelect, selectedIssueId);
                modelSelect.innerHTML = '<option value="">Tutti</option>';
            });

            brandSelect.addEventListener('change', function() {
                loadOptions(`ajax_get_models.php?brand_id=${this.value}`, modelSelect, selectedModelId);
            });

            if (deviceSelect.value) {
                loadOptions(`ajax_get_brands.php?device_id=${deviceSelect.value}`, brandSelect, selectedBrandId);
                loadOptions(`ajax_get_issues.php?device_id=${deviceSelect.value}`, issueSelect, selectedIssueId);
                if(selectedBrandId) {
                    loadOptions(`ajax_get_models.php?brand_id=${selectedBrandId}`, modelSelect, selectedModelId);
                }
            }
        });
    </script>
<?php endif; ?>

<?php include_once 'includes/footer.php'; ?>
