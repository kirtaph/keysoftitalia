<?php
include_once 'includes/header.php';

// Logica per gestire le operazioni CRUD
$action = $_GET['action'] ?? 'list';
$id = $_GET['id'] ?? null;

switch ($action) {
    case 'add':
        // Logica per aggiungere un nuovo dispositivo
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'];
            $slug = $_POST['slug'];
            $sort_order = $_POST['sort_order'];
            $stmt = $pdo->prepare('INSERT INTO devices (name, slug, sort_order) VALUES (?, ?, ?)');
            $stmt->execute([$name, $slug, $sort_order]);
            header('Location: devices.php');
            exit;
        }
        break;
    case 'edit':
        // Logica per modificare un dispositivo
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'];
            $slug = $_POST['slug'];
            $sort_order = $_POST['sort_order'];
            $stmt = $pdo->prepare('UPDATE devices SET name = ?, slug = ?, sort_order = ? WHERE id = ?');
            $stmt->execute([$name, $slug, $sort_order, $id]);
            header('Location: devices.php');
            exit;
        }
        $stmt = $pdo->prepare('SELECT * FROM devices WHERE id = ?');
        $stmt->execute([$id]);
        $device = $stmt->fetch();
        break;
    case 'delete':
        // Logica per eliminare un dispositivo
        $stmt = $pdo->prepare('DELETE FROM devices WHERE id = ?');
        $stmt->execute([$id]);
        header('Location: devices.php');
        exit;
    default:
        // Elenco dei dispositivi
        $stmt = $pdo->query('SELECT * FROM devices ORDER BY sort_order ASC');
        $devices = $stmt->fetchAll();
        break;
}
?>

<div class="section-header">
    <h2 class="section-title">Gestione Dispositivi</h2>
</div>

<?php if ($action === 'list'): ?>
    <a href="?action=add" class="btn btn-primary mb-4">Aggiungi Dispositivo</a>
    <table class="table">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Slug</th>
                <th>Ordine</th>
                <th>Azioni</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($devices as $device): ?>
                <tr>
                    <td><?php echo htmlspecialchars($device['name']); ?></td>
                    <td><?php echo htmlspecialchars($device['slug']); ?></td>
                    <td><?php echo htmlspecialchars($device['sort_order']); ?></td>
                    <td>
                        <a href="?action=edit&id=<?php echo $device['id']; ?>" class="btn btn-sm btn-outline-orange">Modifica</a>
                        <a href="?action=delete&id=<?php echo $device['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Sei sicuro?')">Elimina</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php elseif ($action === 'add' || $action === 'edit'): ?>
    <form method="POST">
        <div class="form-group">
            <label for="name" class="form-label">Nome</label>
            <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($device['name'] ?? ''); ?>" required>
        </div>
        <div class="form-group">
            <label for="slug" class="form-label">Slug</label>
            <input type="text" class="form-control" id="slug" name="slug" value="<?php echo htmlspecialchars($device['slug'] ?? ''); ?>" required>
        </div>
        <div class="form-group">
            <label for="sort_order" class="form-label">Ordine</label>
            <input type="number" class="form-control" id="sort_order" name="sort_order" value="<?php echo htmlspecialchars($device['sort_order'] ?? '0'); ?>" required>
        </div>
        <button type="submit" class="btn btn-primary"><?php echo $action === 'add' ? 'Aggiungi' : 'Salva Modifiche'; ?></button>
        <a href="devices.php" class="btn btn-secondary">Annulla</a>
    </form>
<?php endif; ?>

<?php include_once 'includes/footer.php'; ?>
