<?php
include_once 'includes/header.php';

// Logica per gestire le operazioni CRUD
$action = $_GET['action'] ?? 'list';
$id = $_GET['id'] ?? null;

switch ($action) {
    case 'add':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $device_id = $_POST['device_id'];
            $label = $_POST['label'];
            $severity = $_POST['severity'];
            $is_active = isset($_POST['is_active']) ? 1 : 0;
            $sort_order = $_POST['sort_order'];
            $stmt = $pdo->prepare('INSERT INTO issues (device_id, label, severity, is_active, sort_order) VALUES (?, ?, ?, ?, ?)');
            $stmt->execute([$device_id, $label, $severity, $is_active, $sort_order]);
            header('Location: issues.php');
            exit;
        }
        break;
    case 'edit':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $device_id = $_POST['device_id'];
            $label = $_POST['label'];
            $severity = $_POST['severity'];
            $is_active = isset($_POST['is_active']) ? 1 : 0;
            $sort_order = $_POST['sort_order'];
            $stmt = $pdo->prepare('UPDATE issues SET device_id = ?, label = ?, severity = ?, is_active = ?, sort_order = ? WHERE id = ?');
            $stmt->execute([$device_id, $label, $severity, $is_active, $sort_order, $id]);
            header('Location: issues.php');
            exit;
        }
        $stmt = $pdo->prepare('SELECT * FROM issues WHERE id = ?');
        $stmt->execute([$id]);
        $issue = $stmt->fetch();
        break;
    case 'delete':
        $stmt = $pdo->prepare('DELETE FROM issues WHERE id = ?');
        $stmt->execute([$id]);
        header('Location: issues.php');
        exit;
    default:
        $stmt = $pdo->query('SELECT i.*, d.name as device_name FROM issues i JOIN devices d ON i.device_id = d.id ORDER BY d.name, i.sort_order ASC');
        $issues = $stmt->fetchAll();
        break;
}
$devices_stmt = $pdo->query('SELECT * FROM devices ORDER BY name ASC');
$devices = $devices_stmt->fetchAll();
$severities = ['low', 'mid', 'high'];
?>

<div class="section-header">
    <h2 class="section-title">Gestione Problemi</h2>
</div>

<?php if ($action === 'list'): ?>
    <a href="?action=add" class="btn btn-primary mb-4">Aggiungi Problema</a>
    <table class="table">
        <thead>
            <tr>
                <th>Label</th>
                <th>Dispositivo</th>
                <th>Gravità</th>
                <th>Ordine</th>
                <th>Attivo</th>
                <th>Azioni</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($issues as $issue): ?>
                <tr>
                    <td><?php echo htmlspecialchars($issue['label']); ?></td>
                    <td><?php echo htmlspecialchars($issue['device_name']); ?></td>
                    <td><?php echo htmlspecialchars($issue['severity']); ?></td>
                    <td><?php echo htmlspecialchars($issue['sort_order']); ?></td>
                    <td><?php echo $issue['is_active'] ? 'Sì' : 'No'; ?></td>
                    <td>
                        <a href="?action=edit&id=<?php echo $issue['id']; ?>" class="btn btn-sm btn-outline-orange">Modifica</a>
                        <a href="?action=delete&id=<?php echo $issue['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Sei sicuro?')">Elimina</a>
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
                <?php foreach ($devices as $device): ?>
                    <option value="<?php echo $device['id']; ?>" <?php echo isset($issue) && $issue['device_id'] == $device['id'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($device['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="label" class="form-label">Label</label>
            <input type="text" class="form-control" id="label" name="label" value="<?php echo htmlspecialchars($issue['label'] ?? ''); ?>" required>
        </div>
        <div class="form-group">
            <label for="severity" class="form-label">Gravità</label>
            <select class="form-control" id="severity" name="severity" required>
                <?php foreach ($severities as $severity): ?>
                    <option value="<?php echo $severity; ?>" <?php echo isset($issue) && $issue['severity'] == $severity ? 'selected' : ''; ?>>
                        <?php echo ucfirst($severity); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="sort_order" class="form-label">Ordine</label>
            <input type="number" class="form-control" id="sort_order" name="sort_order" value="<?php echo htmlspecialchars($issue['sort_order'] ?? '0'); ?>" required>
        </div>
        <div class="form-group form-check">
            <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" <?php echo (isset($issue) && $issue['is_active']) || !isset($issue) ? 'checked' : ''; ?>>
            <label class="form-check-label" for="is_active">Attivo</label>
        </div>
        <button type="submit" class="btn btn-primary"><?php echo $action === 'add' ? 'Aggiungi' : 'Salva Modifiche'; ?></button>
        <a href="issues.php" class="btn btn-secondary">Annulla</a>
    </form>
<?php endif; ?>

<?php include_once 'includes/footer.php'; ?>
