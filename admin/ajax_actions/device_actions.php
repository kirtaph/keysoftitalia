<?php
require_once __DIR__ . '/init.php';

$action = $_REQUEST['action'] ?? null;
$id = $_REQUEST['id'] ?? null;

try {
    switch ($action) {
        case 'get':
            $stmt = $pdo->prepare('SELECT * FROM devices WHERE id = ?');
            $stmt->execute([$id]);
            $device = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($device) {
                jsonSuccess(['data' => $device]);
            } else {
                jsonError('Dispositivo non trovato.');
            }
            break;

        case 'add':
            $name = $_POST['name'] ?? '';
            $slug = $_POST['slug'] ?? '';
            $sort_order = $_POST['sort_order'] ?? 0;
            if (empty($name) || empty($slug)) {
                jsonError('Nome e slug sono obbligatori.');
            }
            $stmt = $pdo->prepare('INSERT INTO devices (name, slug, sort_order) VALUES (?, ?, ?)');
            $stmt->execute([$name, $slug, $sort_order]);
            jsonSuccess(['message' => 'Dispositivo aggiunto con successo.']);
            break;

        case 'edit':
            $name = $_POST['name'] ?? '';
            $slug = $_POST['slug'] ?? '';
            $sort_order = $_POST['sort_order'] ?? 0;
            if (empty($name) || empty($slug) || empty($id)) {
                jsonError('ID, nome e slug sono obbligatori.');
            }
            $stmt = $pdo->prepare('UPDATE devices SET name = ?, slug = ?, sort_order = ? WHERE id = ?');
            $stmt->execute([$name, $slug, $sort_order, $id]);
            jsonSuccess(['message' => 'Dispositivo aggiornato con successo.']);
            break;

        case 'delete':
            if (empty($id)) {
                jsonError('ID del dispositivo non fornito.');
            }
            $stmt = $pdo->prepare('DELETE FROM devices WHERE id = ?');
            $stmt->execute([$id]);
            jsonSuccess(['message' => 'Dispositivo eliminato con successo.']);
            break;

        default:
            jsonError('Azione non valida.');
            break;
    }
} catch (Throwable $e) {
    jsonError('Errore del server.', $e);
}
