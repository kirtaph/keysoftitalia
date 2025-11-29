<?php
require_once '../../config/config.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Non autorizzato']);
    exit;
}

$action = $_REQUEST['action'] ?? '';

try {
    if ($action === 'get') {
        $id = $_GET['id'] ?? null;
        if (!$id) throw new Exception('ID mancante');

        $stmt = $pdo->prepare("SELECT q.*, d.name as device_name FROM quotes q JOIN devices d ON q.device_id = d.id WHERE q.id = ?");
        $stmt->execute([$id]);
        $quote = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($quote) {
            echo json_encode(['status' => 'success', 'quote' => $quote]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Preventivo non trovato']);
        }
    } elseif ($action === 'delete') {
        $id = $_POST['id'] ?? null;
        if (!$id) throw new Exception('ID mancante');

        $stmt = $pdo->prepare("DELETE FROM quotes WHERE id = ?");
        $stmt->execute([$id]);

        echo json_encode(['status' => 'success']);

    } elseif ($action === 'edit') {
        $id = $_POST['id'] ?? null;
        $status = $_POST['status'] ?? 'pending';
        $notes = $_POST['notes'] ?? '';
        $est_min = $_POST['est_min'] ?? null;
        $est_max = $_POST['est_max'] ?? null;

        if (!$id) throw new Exception('ID mancante');

        $stmt = $pdo->prepare("UPDATE quotes SET status = ?, notes = ?, est_min = ?, est_max = ? WHERE id = ?");
        $stmt->execute([$status, $notes, $est_min, $est_max, $id]);

        echo json_encode(['status' => 'success']);

    } elseif ($action === 'create_price_rule') {
        $quote_id = $_POST['quote_id'] ?? null;
        $price = $_POST['price'] ?? 0;

        if (!$quote_id) throw new Exception('ID Preventivo mancante');
        if (!$price) throw new Exception('Prezzo mancante');

        // Get Quote Details
        $stmt = $pdo->prepare("SELECT * FROM quotes WHERE id = ?");
        $stmt->execute([$quote_id]);
        $quote = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$quote) throw new Exception('Preventivo non trovato');

        // Extract Problems (JSON)
        $problems = json_decode($quote['problems_json'], true);
        if (empty($problems)) throw new Exception('Nessun problema specificato nel preventivo');

        // Create Rule for each problem
        $count = 0;
        foreach ($problems as $problemName) {
            // Find Issue ID by label
            $stmt = $pdo->prepare("SELECT id FROM issues WHERE label = ? AND device_id = ?");
            $stmt->execute([$problemName, $quote['device_id']]);
            $issueId = $stmt->fetchColumn();

            if (!$issueId) {
                $stmt = $pdo->prepare("INSERT INTO issues (label, device_id, severity) VALUES (?, ?, 'mid')");
                $stmt->execute([$problemName, $quote['device_id']]);
                $issueId = $pdo->lastInsertId();
            }

            // Try to find Model ID by text
            $modelId = null;
            $brandId = null;
            
            // Find Brand - Try exact match first, then LIKE
            $stmt = $pdo->prepare("SELECT id FROM brands WHERE name = ? OR name LIKE ?");
            $stmt->execute([$quote['brand_text'], $quote['brand_text']]);
            $brandId = $stmt->fetchColumn();
            
            if ($brandId) {
                // Find Model - Try exact match first, then LIKE with wildcards
                $modelName = trim($quote['model_text']);
                $stmt = $pdo->prepare("SELECT id FROM models WHERE brand_id = ? AND (name = ? OR name LIKE ?)");
                $stmt->execute([$brandId, $modelName, "%$modelName%"]);
                $modelId = $stmt->fetchColumn();
            }

            // Check if rule exists
            // If modelId is null, we check for rule with model_id IS NULL (generic rule)
            // But user wants specific rule. If model not found, maybe we should warn? 
            // For now, if modelId is found, use it. If not, it will be NULL (generic).
            
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


        echo json_encode(['status' => 'success', 'message' => "$count regole create/aggiornate"]);

    } else {
        throw new Exception('Azione non valida');
    }
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
