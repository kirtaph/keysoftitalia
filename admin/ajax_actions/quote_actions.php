<?php
require_once __DIR__ . '/init.php';

$action = $_REQUEST['action'] ?? '';

try {
    if ($action === 'get') {
        $id = $_GET['id'] ?? null;
        if (!$id) jsonError('ID mancante');

        $stmt = $pdo->prepare("SELECT q.*, d.name as device_name FROM quotes q JOIN devices d ON q.device_id = d.id WHERE q.id = ?");
        $stmt->execute([$id]);
        $quote = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($quote) {
            jsonSuccess(['quote' => $quote]);
        } else {
            jsonError('Preventivo non trovato');
        }
    } elseif ($action === 'delete') {
        $id = $_POST['id'] ?? null;
        if (!$id) jsonError('ID mancante');

        $stmt = $pdo->prepare("DELETE FROM quotes WHERE id = ?");
        $stmt->execute([$id]);

        jsonSuccess([]);

    } elseif ($action === 'edit') {
        $id = $_POST['id'] ?? null;
        $status = $_POST['status'] ?? 'pending';
        $notes = $_POST['notes'] ?? '';
        $est_min = $_POST['est_min'] ?? null;
        $est_max = $_POST['est_max'] ?? null;

        if (!$id) jsonError('ID mancante');

        $stmt = $pdo->prepare("UPDATE quotes SET status = ?, notes = ?, est_min = ?, est_max = ? WHERE id = ?");
        $stmt->execute([$status, $notes, $est_min, $est_max, $id]);

        jsonSuccess([]);

    } elseif ($action === 'create_price_rule') {
        $quote_id = $_POST['quote_id'] ?? null;
        $price = $_POST['price'] ?? 0;

        if (!$quote_id) jsonError('ID Preventivo mancante');
        if (!$price) jsonError('Prezzo mancante');

        $stmt = $pdo->prepare("SELECT * FROM quotes WHERE id = ?");
        $stmt->execute([$quote_id]);
        $quote = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$quote) jsonError('Preventivo non trovato');

        $problems = json_decode($quote['problems_json'], true);
        if (empty($problems)) jsonError('Nessun problema specificato nel preventivo');

        $count = 0;
        foreach ($problems as $problemName) {
            $stmt = $pdo->prepare("SELECT id FROM issues WHERE label = ? AND device_id = ?");
            $stmt->execute([$problemName, $quote['device_id']]);
            $issueId = $stmt->fetchColumn();

            if (!$issueId) {
                $stmt = $pdo->prepare("INSERT INTO issues (label, device_id, severity) VALUES (?, ?, 'mid')");
                $stmt->execute([$problemName, $quote['device_id']]);
                $issueId = $pdo->lastInsertId();
            }

            $modelId = null;
            $brandId = null;
            
            $stmt = $pdo->prepare("SELECT id FROM brands WHERE name = ? OR name LIKE ?");
            $stmt->execute([$quote['brand_text'], $quote['brand_text']]);
            $brandId = $stmt->fetchColumn();
            
            if ($brandId) {
                $modelName = trim($quote['model_text']);
                $stmt = $pdo->prepare("SELECT id FROM models WHERE brand_id = ? AND (name = ? OR name LIKE ?)");
                $stmt->execute([$brandId, $modelName, "%$modelName%"]);
                $modelId = $stmt->fetchColumn();
            }
            
            $sql = "SELECT id FROM price_rules WHERE device_id = ? AND issue_id = ? AND brand_id " . ($brandId ? "= ?" : "IS NULL") . " AND model_id " . ($modelId ? "= ?" : "IS NULL");
            $params = [$quote['device_id'], $issueId];
            if ($brandId) $params[] = $brandId;
            if ($modelId) $params[] = $modelId;

            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            $existing = $stmt->fetchColumn();

            if ($existing) {
                $stmt = $pdo->prepare("UPDATE price_rules SET min_price = ? WHERE id = ?");
                $stmt->execute([$price, $existing]);
            } else {
                $stmt = $pdo->prepare("INSERT INTO price_rules (device_id, brand_id, model_id, issue_id, min_price) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$quote['device_id'], $brandId, $modelId, $issueId, $price]);
            }
            $count++;
        }

        jsonSuccess(['message' => "$count regole create/aggiornate"]);

    } else {
        jsonError('Azione non valida');
    }
} catch (Throwable $e) {
    jsonError('Errore del server.', $e);
}
