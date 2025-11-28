<?php
include_once '../../config/config.php';

header('Content-Type: application/json');

$action = $_REQUEST['action'] ?? null;
$id = $_REQUEST['id'] ?? null;

try {
    switch ($action) {
        case 'get':
            $stmt = $pdo->prepare('SELECT * FROM price_rules WHERE id = ?');
            $stmt->execute([$id]);
            $rule = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($rule) {
                echo json_encode(['status' => 'success', 'rule' => $rule]);
            } else {
                throw new Exception('Regola di prezzo non trovata.');
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
            echo json_encode(['status' => 'success', 'message' => 'Regola aggiunta con successo.']);
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
            echo json_encode(['status' => 'success', 'message' => 'Regola aggiornata con successo.']);
            break;

        case 'delete':
            if (empty($id)) {
                throw new Exception('ID della regola non fornito.');
            }
            $stmt = $pdo->prepare('DELETE FROM price_rules WHERE id = ?');
            $stmt->execute([$id]);
            echo json_encode(['status' => 'success', 'message' => 'Regola eliminata con successo.']);
            break;

        case 'update_price':
            $min = $_POST['min_price'] ?? null;
            $max = $_POST['max_price'] ?? null;
            
            if (!$id) throw new Exception('ID mancante');
            
            // Allow updating just one or both
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
            
            if (empty($updates)) throw new Exception('Nessun dato da aggiornare');
            
            $sql .= implode(', ', $updates) . " WHERE id = ?";
            $params[] = $id;
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            
            echo json_encode(['status' => 'success', 'message' => 'Prezzo aggiornato']);
            break;

        case 'clone':
            if (!$id) throw new Exception('ID mancante');
            
            // Get original
            $stmt = $pdo->prepare("SELECT * FROM price_rules WHERE id = ?");
            $stmt->execute([$id]);
            $orig = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$orig) throw new Exception('Regola originale non trovata');
            
            // Insert copy
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
            
            echo json_encode(['status' => 'success', 'message' => 'Regola clonata']);
            break;

        default:
            throw new Exception('Azione non valida.');
            break;
    }
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
