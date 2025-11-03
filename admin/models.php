<?php
include_once 'includes/header.php';

// Logica per gestire le operazioni CRUD
$action = $_GET['action'] ?? 'list';
$id = $_GET['id'] ?? null;

switch ($action) {
    case 'add':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $brand_id = $_POST['brand_id'];
            $name = $_POST['name'];
            $year = $_POST['year'] ?: null;
            $is_active = isset($_POST['is_active']) ? 1 : 0;
            $stmt = $pdo->prepare('INSERT INTO models (brand_id, name, year, is_active) VALUES (?, ?, ?, ?)');
            $stmt->execute([$brand_id, $name, $year, $is_active]);
            header('Location: models.php');
            exit;
        }
        break;
    case 'edit':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $brand_id = $_POST['brand_id'];
            $name = $_POST['name'];
            $year = $_POST['year'] ?: null;
            $is_active = isset($_POST['is_active']) ? 1 : 0;
            $stmt = $pdo->prepare('UPDATE models SET brand_id = ?, name = ?, year = ?, is_active = ? WHERE id = ?');
            $stmt->execute([$brand_id, $name, $year, $is_active, $id]);
            header('Location: models.php');
            exit;
        }
        $stmt = $pdo->prepare('SELECT m.*, b.device_id FROM models m JOIN brands b ON m.brand_id = b.id WHERE m.id = ?');
        $stmt->execute([$id]);
        $model = $stmt->fetch();
        break;
    case 'delete':
        $stmt = $pdo->prepare('DELETE FROM models WHERE id = ?');
        $stmt->execute([$id]);
        header('Location: models.php');
        exit;
    default:
        $stmt = $pdo->query('SELECT m.*, b.name as brand_name, d.name as device_name FROM models m JOIN brands b ON m.brand_id = b.id JOIN devices d ON b.device_id = d.id ORDER BY d.name, b.name, m.name');
        $models = $stmt->fetchAll();
        break;
}
$devices_stmt = $pdo->query('SELECT * FROM devices ORDER BY name ASC');
$devices = $devices_stmt->fetchAll();
?>

<div class="section-header">
    <h2 class="section-title">Gestione Modelli</h2>
</div>

<?php if ($action === 'list'): ?>
    <a href="?action=add" class="btn btn-primary mb-4">Aggiungi Modello</a>
    <table class="table">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Anno</th>
                <th>Marchio</th>
                <th>Dispositivo</th>
                <th>Attivo</th>
                <th>Azioni</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($models as $model): ?>
                <tr>
                    <td><?php echo htmlspecialchars($model['name']); ?></td>
                    <td><?php echo htmlspecialchars($model['year']); ?></td>
                    <td><?php echo htmlspecialchars($model['brand_name']); ?></td>
                    <td><?php echo htmlspecialchars($model['device_name']); ?></td>
                    <td><?php echo $model['is_active'] ? 'Sì' : 'No'; ?></td>
                    <td>
                        <a href="?action=edit&id=<?php echo $model['id']; ?>" class="btn btn-sm btn-outline-orange">Modifica</a>
                        <a href="?action=delete&id=<?php echo $model['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Sei sicuro?')">Elimina</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php elseif ($action === 'add' || $action === 'edit'): ?>
    <form method="POST">
        <div class="form-group">
            <label for="device_id" class="form-label">Dispositivo</label>
            <select class="form-control" id="device_id" name="device_id" required>
                <option value="">Seleziona un dispositivo</option>
                <?php foreach ($devices as $device): ?>
                    <option value="<?php echo $device['id']; ?>" <?php echo isset($model) && $model['device_id'] == $device['id'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($device['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="brand_id" class="form-label">Marchio</label>
            <select class="form-control" id="brand_id" name="brand_id" required>
                <!-- Caricato dinamicamente con JS -->
            </select>
        </div>
        <div class="form-group">
            <label for="name" class="form-label">Nome Modello</label>
            <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($model['name'] ?? ''); ?>" required>
        </div>
        <div class="form-group">
            <label for="year" class="form-label">Anno</label>
            <input type="number" class="form-control" id="year" name="year" value="<?php echo htmlspecialchars($model['year'] ?? ''); ?>">
        </div>
        <div class="form-group form-check">
            <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" <?php echo (isset($model) && $model['is_active']) || !isset($model) ? 'checked' : ''; ?>>
            <label class="form-check-label" for="is_active">Attivo</label>
        </div>
        <button type="submit" class="btn btn-primary"><?php echo $action === 'add' ? 'Aggiungi' : 'Salva Modifiche'; ?></button>
        <a href="models.php" class="btn btn-secondary">Annulla</a>
    </form>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const deviceSelect = document.getElementById('device_id');
            const brandSelect = document.getElementById('brand_id');
            const selectedBrandId = '<?php echo $model['brand_id'] ?? ''; ?>';

            function loadBrands(deviceId, selectedBrand = '') {
                fetch(`ajax_get_brands.php?device_id=${deviceId}`)
                    .then(response => response.json())
                    .then(data => {
                        brandSelect.innerHTML = '<option value="">Seleziona un marchio</option>';
                        data.forEach(brand => {
                            const option = document.createElement('option');
                            option.value = brand.id;
                            option.textContent = brand.name;
                            if (brand.id == selectedBrand) {
                                option.selected = true;
                            }
                            brandSelect.appendChild(option);
                        });
                    });
            }

            if (deviceSelect.value) {
                loadBrands(deviceSelect.value, selectedBrandId);
            }

            deviceSelect.addEventListener('change', function() {
                loadBrands(this.value);
            });
        });
    </script>
<?php endif; ?>

<?php include_once 'includes/footer.php'; ?>
