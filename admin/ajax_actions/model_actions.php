<?php
require_once __DIR__ . '/init.php';

$action = $_REQUEST['action'] ?? '';

try {
    if ($action === 'get') {
        $id = $_GET['id'] ?? null;
        if (!$id) jsonError('ID mancante');

        $stmt = $pdo->prepare("SELECT * FROM models WHERE id = ?");
        $stmt->execute([$id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            jsonSuccess(['data' => $data]);
        } else {
            jsonError('Modello non trovato');
        }
    } elseif ($action === 'list') {
        $brand_id = $_GET['brand_id'] ?? null;
        $sql = "SELECT * FROM models";
        $params = [];
        if ($brand_id) {
            $sql .= " WHERE brand_id = ?";
            $params[] = $brand_id;
        }
        $sql .= " ORDER BY name ASC";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $models = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        jsonSuccess(['models' => $models]);

    } elseif ($action === 'add') {
        $brand_id = $_POST['brand_id'] ?? null;
        $name = $_POST['name'] ?? '';
        
        if (!$brand_id || !$name) jsonError('Dati mancanti');

        $stmt = $pdo->prepare("INSERT INTO models (brand_id, name, is_active) VALUES (?, ?, 1)");
        $stmt->execute([$brand_id, $name]);

        jsonSuccess(['message' => 'Modello aggiunto']);
    } elseif ($action === 'edit') {
        $id = $_POST['id'] ?? null;
        $brand_id = $_POST['brand_id'] ?? null;
        $name = $_POST['name'] ?? '';
        
        if (!$id || !$brand_id || !$name) jsonError('Dati mancanti');

        $stmt = $pdo->prepare("UPDATE models SET brand_id = ?, name = ? WHERE id = ?");
        $stmt->execute([$brand_id, $name, $id]);

        jsonSuccess(['message' => 'Modello aggiornato']);
    } elseif ($action === 'delete') {
        $id = $_POST['id'] ?? null;
        if (!$id) jsonError('ID mancante');

        $stmt = $pdo->prepare("DELETE FROM models WHERE id = ?");
        $stmt->execute([$id]);

        jsonSuccess(['message' => 'Modello eliminato']);
    } else {
        jsonError('Azione non valida');
    }
} catch (Throwable $e) {
    jsonError('Errore del server.', $e);
}
