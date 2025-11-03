<?php
include_once 'includes/header.php';

// Logica per gestire le operazioni CRUD
$action = $_GET['action'] ?? 'list';
$id = $_GET['id'] ?? null;

switch ($action) {
    case 'add':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $stmt = $pdo->prepare('INSERT INTO users (username, email, password) VALUES (?, ?, ?)');
            $stmt->execute([$username, $email, $password]);
            header('Location: users.php');
            exit;
        }
        break;
    case 'edit':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            if (!empty($password)) {
                $password = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare('UPDATE users SET username = ?, email = ?, password = ? WHERE id = ?');
                $stmt->execute([$username, $email, $password, $id]);
            } else {
                $stmt = $pdo->prepare('UPDATE users SET username = ?, email = ? WHERE id = ?');
                $stmt->execute([$username, $email, $id]);
            }
            header('Location: users.php');
            exit;
        }
        $stmt = $pdo->prepare('SELECT * FROM users WHERE id = ?');
        $stmt->execute([$id]);
        $user = $stmt->fetch();
        break;
    case 'delete':
        $stmt = $pdo->prepare('DELETE FROM users WHERE id = ?');
        $stmt->execute([$id]);
        header('Location: users.php');
        exit;
    default:
        $stmt = $pdo->query('SELECT id, username, email, created_at FROM users ORDER BY username ASC');
        $users = $stmt->fetchAll();
        break;
}
?>

<div class="section-header">
    <h2 class="section-title">Gestione Utenti</h2>
</div>

<?php if ($action === 'list'): ?>
    <a href="?action=add" class="btn btn-primary mb-4">Aggiungi Utente</a>
    <table class="table">
        <thead>
            <tr>
                <th>Username</th>
                <th>Email</th>
                <th>Creato il</th>
                <th>Azioni</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                    <td><?php echo htmlspecialchars($user['created_at']); ?></td>
                    <td>
                        <a href="?action=edit&id=<?php echo $user['id']; ?>" class="btn btn-sm btn-outline-orange">Modifica</a>
                        <a href="?action=delete&id=<?php echo $user['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Sei sicuro?')">Elimina</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php elseif ($action === 'add' || $action === 'edit'): ?>
    <form method="POST">
        <div class="form-group">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($user['username'] ?? ''); ?>" required>
        </div>
        <div class="form-group">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" required>
        </div>
        <div class="form-group">
            <label for="password" class="form-label">Password <?php if ($action === 'edit') echo '(lascia vuoto per non cambiare)'; ?></label>
            <input type="password" class="form-control" id="password" name="password" <?php if ($action === 'add') echo 'required'; ?>>
        </div>
        <button type="submit" class="btn btn-primary"><?php echo $action === 'add' ? 'Aggiungi' : 'Salva Modifiche'; ?></button>
        <a href="users.php" class="btn btn-secondary">Annulla</a>
    </form>
<?php endif; ?>

<?php include_once 'includes/footer.php'; ?>
