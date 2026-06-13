<?php
include_once '../../config/config.php';

header('Content-Type: application/json');

// Security Check: Ensure user is logged in
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

$action = $_REQUEST['action'] ?? null;

try {
    switch ($action) {
        case 'list':
            $stmt = $pdo->query("
                SELECT up.id, up.partner_id, COALESCE(p.name, up.operator_name) AS operator_name, COALESCE(p.logo_path, up.logo_path) AS logo_path, 
                       up.plan_name, up.utility_type, up.price, up.price_detail, up.is_featured, up.status, up.created_at 
                FROM utility_promotions up 
                LEFT JOIN utility_partners p ON up.partner_id = p.id 
                ORDER BY up.is_featured DESC, operator_name ASC, up.price ASC
            ");
            $promotions = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode(['status' => 'success', 'promotions' => $promotions]);
            break;

        case 'get':
            $id = $_GET['id'] ?? null;
            if (!$id) throw new Exception('ID promozione non fornito.');
            
            $stmt = $pdo->prepare('SELECT * FROM utility_promotions WHERE id = ?');
            $stmt->execute([$id]);
            $promotion = $stmt->fetch(PDO::FETCH_ASSOC);
            
            echo json_encode(['status' => 'success', 'promotion' => $promotion]);
            break;

        case 'add':
        case 'edit':
            $id = $_POST['id'] ?? null;
            $partner_id = !empty($_POST['partner_id']) ? (int)$_POST['partner_id'] : null;
            $plan_name = trim($_POST['plan_name'] ?? '');
            $utility_type = trim($_POST['utility_type'] ?? 'luce');
            $price = trim($_POST['price'] ?? '');
            $price_detail = trim($_POST['price_detail'] ?? '/mese');
            $features = trim($_POST['features'] ?? '');
            $status = isset($_POST['status']) ? (int)$_POST['status'] : 1;
            $is_featured = isset($_POST['is_featured']) ? 1 : 0;

            if (!$partner_id) throw new Exception('Il partner (Fornitore) è obbligatorio.');
            if (empty($plan_name)) throw new Exception('Il nome dell\'offerta è obbligatorio.');
            if (!in_array($utility_type, ['luce', 'gas', 'dual'])) throw new Exception('Tipo utenza non valido.');
            if ($price === '') throw new Exception('Il prezzo è obbligatorio.');
            
            $price = floatval($price);

            if ($action === 'add') {
                $stmt = $pdo->prepare(
                    'INSERT INTO utility_promotions (partner_id, plan_name, utility_type, price, price_detail, features, is_featured, status)
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?)'
                );
                $stmt->execute([$partner_id, $plan_name, $utility_type, $price, $price_detail, $features, $is_featured, $status]);
            } else { // edit
                $stmt = $pdo->prepare(
                    'UPDATE utility_promotions SET partner_id=?, plan_name=?, utility_type=?, price=?, price_detail=?, features=?, is_featured=?, status=? WHERE id = ?'
                );
                $stmt->execute([$partner_id, $plan_name, $utility_type, $price, $price_detail, $features, $is_featured, $status, $id]);
            }
            
            echo json_encode(['status' => 'success', 'message' => 'Promozione salvata con successo.']);
            break;

        case 'delete':
            $id = $_POST['id'] ?? null;
            if (!$id) throw new Exception('ID promozione non fornito.');

            // Otteniamo il percorso del file logo prima di eliminare la riga (per compatibilità legacy, se ci fosse logo_path in promozioni)
            $stmt = $pdo->prepare('SELECT logo_path FROM utility_promotions WHERE id = ?');
            $stmt->execute([$id]);
            $promo = $stmt->fetch(PDO::FETCH_ASSOC);

            // Eliminiamo dal DB
            $stmt = $pdo->prepare('DELETE FROM utility_promotions WHERE id = ?');
            $stmt->execute([$id]);

            // Eliminiamo il file fisico del logo se presente sulla promozione
            if ($promo && !empty($promo['logo_path']) && file_exists('../../' . $promo['logo_path'])) {
                @unlink('../../' . $promo['logo_path']);
            }

            echo json_encode(['status' => 'success', 'message' => 'Promozione eliminata con successo.']);
            break;

        case 'list_requests':
            $stmt = $pdo->query("
                SELECT id, promotion_id, operator_name, plan_name, utility_type, current_spend, phone, estimated_savings, status, created_at 
                FROM utility_requests 
                ORDER BY created_at DESC
            ");
            $requests = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode(['status' => 'success', 'requests' => $requests]);
            break;

        case 'update_request_status':
            $id = $_POST['id'] ?? null;
            $status = trim($_POST['status'] ?? '');
            if (!$id) throw new Exception('ID richiesta non fornito.');
            if (empty($status)) throw new Exception('Stato non fornito.');

            $stmt = $pdo->prepare('UPDATE utility_requests SET status = ? WHERE id = ?');
            $stmt->execute([$status, $id]);

            echo json_encode(['status' => 'success', 'message' => 'Stato richiesta aggiornato con successo.']);
            break;

        case 'delete_request':
            $id = $_POST['id'] ?? null;
            if (!$id) throw new Exception('ID richiesta non fornito.');

            $stmt = $pdo->prepare('DELETE FROM utility_requests WHERE id = ?');
            $stmt->execute([$id]);

            echo json_encode(['status' => 'success', 'message' => 'Richiesta eliminata con successo.']);
            break;

        case 'list_partners':
            $stmt = $pdo->query("SELECT id, name, description, icon_class, icon_color, logo_path, sort_order, status, created_at FROM utility_partners ORDER BY sort_order ASC, name ASC");
            $partners = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode(['status' => 'success', 'partners' => $partners]);
            break;

        case 'get_partner':
            $id = $_GET['id'] ?? null;
            if (!$id) throw new Exception('ID partner non fornito.');

            $stmt = $pdo->prepare('SELECT * FROM utility_partners WHERE id = ?');
            $stmt->execute([$id]);
            $partner = $stmt->fetch(PDO::FETCH_ASSOC);

            echo json_encode(['status' => 'success', 'partner' => $partner]);
            break;

        case 'add_partner':
        case 'edit_partner':
            $id = $_POST['id'] ?? null;
            $name = trim($_POST['name'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $icon_class = trim($_POST['icon_class'] ?? 'ri-flashlight-line');
            $icon_color = trim($_POST['icon_color'] ?? 'var(--ks-orange)');
            $sort_order = isset($_POST['sort_order']) ? (int)$_POST['sort_order'] : 0;
            $status = isset($_POST['status']) ? (int)$_POST['status'] : 1;

            if (empty($name)) throw new Exception('Il nome del partner è obbligatorio.');

            // --- Gestione upload logo ---
            $uploadDir = '../../uploads/utilities/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $logoPath = null;
            
            // Se stiamo modificando, recuperiamo il logo esistente
            if ($action === 'edit_partner' && $id) {
                $stmt = $pdo->prepare('SELECT logo_path FROM utility_partners WHERE id = ?');
                $stmt->execute([$id]);
                $oldPartner = $stmt->fetch();
                if ($oldPartner) {
                    $logoPath = $oldPartner['logo_path'];
                }
            }

            if (isset($_FILES['logo_file']) && $_FILES['logo_file']['error'] === UPLOAD_ERR_OK) {
                $fileTmpPath = $_FILES['logo_file']['tmp_name'];
                $fileName = $_FILES['logo_file']['name'];
                $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];
                if (!in_array($fileExtension, $allowedExtensions)) {
                    throw new Exception('Formato file non consentito per il logo.');
                }

                $newFileName = 'partner_' . time() . '_' . rand(100, 999) . '.' . $fileExtension;
                $destPath = $uploadDir . $newFileName;

                if (move_uploaded_file($fileTmpPath, $destPath)) {
                    // Elimina vecchio logo se esisteva
                    if ($logoPath && file_exists('../../' . $logoPath)) {
                        @unlink('../../' . $logoPath);
                    }
                    $logoPath = 'uploads/utilities/' . $newFileName;
                } else {
                    throw new Exception('Impossibile salvare l\'immagine caricata.');
                }
            }

            if ($action === 'add_partner') {
                $stmt = $pdo->prepare(
                    'INSERT INTO utility_partners (name, description, icon_class, icon_color, logo_path, sort_order, status)
                     VALUES (?, ?, ?, ?, ?, ?, ?)'
                );
                $stmt->execute([$name, $description, $icon_class, $icon_color, $logoPath, $sort_order, $status]);
            } else { // edit_partner
                $stmt = $pdo->prepare(
                    'UPDATE utility_partners SET name=?, description=?, icon_class=?, icon_color=?, logo_path=?, sort_order=?, status=? WHERE id = ?'
                );
                $stmt->execute([$name, $description, $icon_class, $icon_color, $logoPath, $sort_order, $status, $id]);
            }

            echo json_encode(['status' => 'success', 'message' => 'Brand Partner salvato con successo.']);
            break;

        case 'delete_partner':
            $id = $_POST['id'] ?? null;
            if (!$id) throw new Exception('ID partner non fornito.');

            // Ottieni il logo del partner
            $stmt = $pdo->prepare('SELECT logo_path FROM utility_partners WHERE id = ?');
            $stmt->execute([$id]);
            $partner = $stmt->fetch();

            $stmt = $pdo->prepare('DELETE FROM utility_partners WHERE id = ?');
            $stmt->execute([$id]);

            if ($partner && $partner['logo_path'] && file_exists('../../' . $partner['logo_path'])) {
                @unlink('../../' . $partner['logo_path']);
            }

            echo json_encode(['status' => 'success', 'message' => 'Brand Partner eliminato con successo.']);
            break;

        default:
            throw new Exception('Azione non valida.');
    }
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>
