<?php
require_once __DIR__ . '/init.php';

$action = $_REQUEST['action'] ?? null;
$id = $_REQUEST['id'] ?? null;

try {
    switch ($action) {
        case 'get':
            $stmt = $pdo->prepare('SELECT * FROM price_rules WHERE id = ?');
            $stmt->execute([$id]);
            $rule = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($rule) {
                jsonSuccess(['rule' => $rule]);
            } else {
                jsonError('Regola di prezzo non trovata.');
            }
            break;

        case 'add':
            $stmt = $pdo->prepare('INSERT INTO price_rules (device_id, issue_id, brand_id, model_id, min_price, max_price, notes, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
            $stmt->execute([
                $_POST['device_id'],
                $_POST['issue_id'],
                empty($_POST['brand_id']) ? null : $_POST['brand_id'],
                empty($_POST['model_id']) ? null : $_POST['model_id'],
                $_POST['min_price'],
                empty($_POST['max_price']) ? null : $_POST['max_price'],
                $_POST['notes'] ?? '',
                isset($_POST['is_active']) ? 1 : 0
            ]);
            jsonSuccess(['message' => 'Regola aggiunta con successo.']);
            break;

        case 'edit':
            $stmt = $pdo->prepare('UPDATE price_rules SET device_id = ?, issue_id = ?, brand_id = ?, model_id = ?, min_price = ?, max_price = ?, notes = ?, is_active = ? WHERE id = ?');
            $stmt->execute([
                $_POST['device_id'],
                $_POST['issue_id'],
                empty($_POST['brand_id']) ? null : $_POST['brand_id'],
                empty($_POST['model_id']) ? null : $_POST['model_id'],
                $_POST['min_price'],
                empty($_POST['max_price']) ? null : $_POST['max_price'],
                $_POST['notes'] ?? '',
                isset($_POST['is_active']) ? 1 : 0,
                $id
            ]);
            jsonSuccess(['message' => 'Regola aggiornata con successo.']);
            break;

        case 'delete':
            if (empty($id)) {
                jsonError('ID della regola non fornito.');
            }
            $stmt = $pdo->prepare('DELETE FROM price_rules WHERE id = ?');
            $stmt->execute([$id]);
            jsonSuccess(['message' => 'Regola eliminata con successo.']);
            break;

        case 'update_price':
            $min = $_POST['min_price'] ?? null;
            $max = $_POST['max_price'] ?? null;
            
            if (!$id) jsonError('ID mancante');
            
            $sql = "UPDATE price_rules SET ";
            $params = [];
            $updates = [];
            
            if ($min !== null) {
                $updates[] = "min_price = ?";
                $params[] = $min;
            }
            if ($max !== null) {
                $updates[] = "max_price = ?";
                $params[] = $max === '' ? null : $max;
            }
            
            if (empty($updates)) jsonError('Nessun dato da aggiornare');
            
            $sql .= implode(', ', $updates) . " WHERE id = ?";
            $params[] = $id;
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            
            jsonSuccess(['message' => 'Prezzo aggiornato']);
            break;

        case 'clone':
            if (!$id) jsonError('ID mancante');
            
            $stmt = $pdo->prepare("SELECT * FROM price_rules WHERE id = ?");
            $stmt->execute([$id]);
            $orig = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$orig) jsonError('Regola originale non trovata');
            
            $stmt = $pdo->prepare('INSERT INTO price_rules (device_id, issue_id, brand_id, model_id, min_price, max_price, notes, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
            $stmt->execute([
                $orig['device_id'],
                $orig['issue_id'],
                $orig['brand_id'],
                $orig['model_id'],
                $orig['min_price'],
                $orig['max_price'],
                $orig['notes'] . ' (Copia)',
                $orig['is_active']
            ]);
            
            jsonSuccess(['message' => 'Regola clonata']);
            break;

        default:
            jsonError('Azione non valida.');
            break;
    }
} catch (Throwable $e) {
    jsonError('Errore del server.', $e);
}
