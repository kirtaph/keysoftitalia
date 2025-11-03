<?php
include_once 'includes/header.php';

// Logica per gestire le operazioni CRUD
$action = $_GET['action'] ?? 'list';
$id = $_GET['id'] ?? null;

switch ($action) {
    case 'add':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $device_id = $_POST['device_id'];
            $name = $_POST['name'];
            $is_active = isset($_POST['is_active']) ? 1 : 0;
            $stmt = $pdo->prepare('INSERT INTO brands (device_id, name, is_active) VALUES (?, ?, ?)');
            $stmt->execute([$device_id, $name, $is_active]);
            header('Location: brands.php');
            exit;
        }
        break;
    case 'edit':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $device_id = $_POST['device_id'];
            $name = $_POST['name'];
            $is_active = isset($_POST['is_active']) ? 1 : 0;
            $stmt = $pdo->prepare('UPDATE brands SET device_id = ?, name = ?, is_active = ? WHERE id = ?');
            $stmt->execute([$device_id, $name, $is_active, $id]);
            header('Location: brands.php');
            exit;
        }
        $stmt = $pdo->prepare('SELECT * FROM brands WHERE id = ?');
        $stmt->execute([$id]);
        $brand = $stmt->fetch();
        break;
    case 'delete':
        $stmt = $pdo->prepare('DELETE FROM brands WHERE id = ?');
        $stmt->execute([$id]);
        header('Location: brands.php');
        exit;
    default:
        $stmt = $pdo->query('SELECT b.*, d.name as device_name FROM brands b JOIN devices d ON b.device_id = d.id ORDER BY d.name, b.name');
        $brands = $stmt->fetchAll();
        break;
}
$devices_stmt = $pdo->query('SELECT * FROM devices ORDER BY name ASC');
$devices = $devices_stmt->fetchAll();
?>

<div class="section-header">
    <h2 class="section-title">Gestione Marchi</h2>
</div>

<?php if ($action === 'list'): ?>
    <a href="?action=add" class="btn btn-primary mb-4">Aggiungi Marchio</a>
    <table class="table">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Dispositivo</th>
                <th>Attivo</th>
                <th>Azioni</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($brands as $brand): ?>
                <tr>
                    <td><?php echo htmlspecialchars($brand['name']); ?></td>
                    <td><?php echo htmlspecialchars($brand['device_name']); ?></td>
                    <td><?php echo $brand['is_active'] ? 'Sì' : 'No'; ?></td>
                    <td>
                        <a href="?action=edit&id=<?php echo $brand['id']; ?>" class="btn btn-sm btn-outline-orange">Modifica</a>
                        <a href="?action=delete&id=<?php echo $brand['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Sei sicuro?')">Elimina</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php elseif ($action === 'add' || $action === 'edit'): ?>
    <form method="POST">
        <div class="form-group">
            <label for="name" class="form-label">Nome</label>
            <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($brand['name'] ?? ''); ?>" required>
        </div>
        <div class="form-group">
            <label for="device_id" class="form-label">Dispositivo</label>
            <select class="form-control" id="device_id" name="device_id" required>
                <?php foreach ($devices as $device): ?>
                    <option value="<?php echo $device['id']; ?>" <?php echo isset($brand) && $brand['device_id'] == $device['id'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($device['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group form-check">
            <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" <?php echo (isset($brand) && $brand['is_active']) || !isset($brand) ? 'checked' : ''; ?>>
            <label class="form-check-label" for="is_active">Attivo</label>
        </div>
        <button type="submit" class="btn btn-primary"><?php echo $action === 'add' ? 'Aggiungi' : 'Salva Modifiche'; ?></button>
        <a href="brands.php" class="btn btn-secondary">Annulla</a>
    </form>
<?php endif; ?>

<?php include_once 'includes/footer.php'; ?>
