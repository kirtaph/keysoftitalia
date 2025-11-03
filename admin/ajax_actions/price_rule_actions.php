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

        default:
            throw new Exception('Azione non valida.');
            break;
    }
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
