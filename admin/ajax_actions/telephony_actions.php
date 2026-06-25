<?php
require_once __DIR__ . '/init.php';

$action = $_REQUEST['action'] ?? null;

try {
    switch ($action) {
        case 'list':
            $stmt = $pdo->query("SELECT tp.id, tp.partner_id, COALESCE(p.name, tp.operator_name) AS operator_name, COALESCE(p.logo_path, tp.logo_path) AS logo_path, tp.plan_name, tp.price, tp.price_detail, tp.is_featured, tp.status, tp.created_at FROM telephony_promotions tp LEFT JOIN telephony_partners p ON tp.partner_id = p.id ORDER BY tp.is_featured DESC, operator_name ASC, tp.price ASC");
            $promotions = $stmt->fetchAll(PDO::FETCH_ASSOC);
            jsonSuccess(['promotions' => $promotions]);
            break;

        case 'get':
            $id = $_GET['id'] ?? null;
            if (!$id) jsonError('ID promozione non fornito.');
            
            $stmt = $pdo->prepare('SELECT * FROM telephony_promotions WHERE id = ?');
            $stmt->execute([$id]);
            $promotion = $stmt->fetch(PDO::FETCH_ASSOC);
            
            jsonSuccess(['promotion' => $promotion]);
            break;

        case 'add':
        case 'edit':
            $id = $_POST['id'] ?? null;
            $partner_id = !empty($_POST['partner_id']) ? (int)$_POST['partner_id'] : null;
            $plan_name = trim($_POST['plan_name'] ?? '');
            $price = trim($_POST['price'] ?? '');
            $price_detail = trim($_POST['price_detail'] ?? '/mese');
            $features = trim($_POST['features'] ?? '');
            $status = isset($_POST['status']) ? (int)$_POST['status'] : 1;
            $is_featured = isset($_POST['is_featured']) ? 1 : 0;

            if (!$partner_id) jsonError('L\'operatore (Brand) è obbligatorio.');
            if (empty($plan_name)) jsonError('Il nome dell\'offerta è obbligatorio.');
            if ($price === '') jsonError('Il prezzo è obbligatorio.');
            
            $price = floatval($price);

            if ($action === 'add') {
                $stmt = $pdo->prepare(
                    'INSERT INTO telephony_promotions (partner_id, plan_name, price, price_detail, features, is_featured, status)
                     VALUES (?, ?, ?, ?, ?, ?, ?)'
                );
                $stmt->execute([$partner_id, $plan_name, $price, $price_detail, $features, $is_featured, $status]);
            } else {
                $stmt = $pdo->prepare(
                    'UPDATE telephony_promotions SET partner_id=?, plan_name=?, price=?, price_detail=?, features=?, is_featured=?, status=? WHERE id = ?'
                );
                $stmt->execute([$partner_id, $plan_name, $price, $price_detail, $features, $is_featured, $status, $id]);
            }
            
            jsonSuccess(['message' => 'Promozione salvata con successo.']);
            break;

        case 'delete':
            $id = $_POST['id'] ?? null;
            if (!$id) jsonError('ID promozione non fornito.');

            $stmt = $pdo->prepare('SELECT logo_path FROM telephony_promotions WHERE id = ?');
            $stmt->execute([$id]);
            $promo = $stmt->fetch(PDO::FETCH_ASSOC);

            $stmt = $pdo->prepare('DELETE FROM telephony_promotions WHERE id = ?');
            $stmt->execute([$id]);

            if ($promo && $promo['logo_path'] && file_exists('../../' . $promo['logo_path'])) {
                @unlink('../../' . $promo['logo_path']);
            }

            jsonSuccess(['message' => 'Promozione eliminata con successo.']);
            break;

        case 'list_requests':
            $stmt = $pdo->query("SELECT id, promotion_id, operator_name, plan_name, current_spend, num_lines, phone, estimated_savings, status, created_at FROM telephony_requests ORDER BY created_at DESC");
            $requests = $stmt->fetchAll(PDO::FETCH_ASSOC);
            jsonSuccess(['requests' => $requests]);
            break;

        case 'update_request_status':
            $id = $_POST['id'] ?? null;
            $status = trim($_POST['status'] ?? '');
            if (!$id) jsonError('ID richiesta non fornito.');
            if (empty($status)) jsonError('Stato non fornito.');

            $stmt = $pdo->prepare('UPDATE telephony_requests SET status = ? WHERE id = ?');
            $stmt->execute([$status, $id]);

            jsonSuccess(['message' => 'Stato richiesta aggiornato con successo.']);
            break;

        case 'delete_request':
            $id = $_POST['id'] ?? null;
            if (!$id) jsonError('ID richiesta non fornito.');

            $stmt = $pdo->prepare('DELETE FROM telephony_requests WHERE id = ?');
            $stmt->execute([$id]);

            jsonSuccess(['message' => 'Richiesta eliminata con successo.']);
            break;

        case 'list_partners':
            $stmt = $pdo->query("SELECT id, name, description, icon_class, icon_color, logo_path, sort_order, status, created_at FROM telephony_partners ORDER BY sort_order ASC, name ASC");
            $partners = $stmt->fetchAll(PDO::FETCH_ASSOC);
            jsonSuccess(['partners' => $partners]);
            break;

        case 'get_partner':
            $id = $_GET['id'] ?? null;
            if (!$id) jsonError('ID partner non fornito.');

            $stmt = $pdo->prepare('SELECT * FROM telephony_partners WHERE id = ?');
            $stmt->execute([$id]);
            $partner = $stmt->fetch(PDO::FETCH_ASSOC);

            jsonSuccess(['partner' => $partner]);
            break;

        case 'add_partner':
        case 'edit_partner':
            $id = $_POST['id'] ?? null;
            $name = trim($_POST['name'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $icon_class = trim($_POST['icon_class'] ?? 'ri-smartphone-line');
            $icon_color = trim($_POST['icon_color'] ?? 'var(--ks-orange)');
            $sort_order = isset($_POST['sort_order']) ? (int)$_POST['sort_order'] : 0;
            $status = isset($_POST['status']) ? (int)$_POST['status'] : 1;

            if (empty($name)) jsonError('Il nome del partner è obbligatorio.');

            $uploadDir = '../../uploads/operators/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $logo_path = null;

            if ($action === 'edit_partner') {
                $stmt = $pdo->prepare('SELECT logo_path FROM telephony_partners WHERE id = ?');
                $stmt->execute([$id]);
                $current_partner = $stmt->fetch(PDO::FETCH_ASSOC);
                $logo_path = $current_partner['logo_path'] ?? null;
            }

            if (isset($_FILES['logo_file']) && $_FILES['logo_file']['error'] == UPLOAD_ERR_OK) {
                $fileExt = strtolower(pathinfo($_FILES['logo_file']['name'], PATHINFO_EXTENSION));
                if (!in_array($fileExt, ['jpg', 'jpeg', 'png', 'webp', 'gif', 'svg'])) {
                    jsonError('Estensione file logo non consentita.');
                }
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $mime = finfo_file($finfo, $_FILES['logo_file']['tmp_name']);
                finfo_close($finfo);
                if (!in_array($mime, ['image/jpeg', 'image/png', 'image/webp', 'image/gif', 'image/svg+xml'])) {
                    jsonError('Tipo MIME file logo non consentito.');
                }
                if ($_FILES['logo_file']['size'] > 5 * 1024 * 1024) {
                    jsonError('Logo troppo grande. Massimo 5MB.');
                }
                if ($action === 'edit_partner' && $logo_path && file_exists('../../' . $logo_path)) {
                    @unlink('../../' . $logo_path);
                }
                $fileName = uniqid() . '-' . preg_replace('/[^a-zA-Z0-9\._-]/', '', basename($_FILES['logo_file']['name']));
                $targetPath = $uploadDir . $fileName;
                if (move_uploaded_file($_FILES['logo_file']['tmp_name'], $targetPath)) {
                    $logo_path = 'uploads/operators/' . $fileName;
                }
            }

            if ($action === 'add_partner') {
                $stmt = $pdo->prepare(
                    'INSERT INTO telephony_partners (name, description, icon_class, icon_color, logo_path, sort_order, status)
                     VALUES (?, ?, ?, ?, ?, ?, ?)'
                );
                $stmt->execute([$name, $description, $icon_class, $icon_color, $logo_path, $sort_order, $status]);
            } else {
                if (!$id) jsonError('ID partner non fornito per la modifica.');
                $stmt = $pdo->prepare(
                    'UPDATE telephony_partners SET name = ?, description = ?, icon_class = ?, icon_color = ?, logo_path = ?, sort_order = ?, status = ? WHERE id = ?'
                );
                $stmt->execute([$name, $description, $icon_class, $icon_color, $logo_path, $sort_order, $status, $id]);
            }

            jsonSuccess(['message' => 'Partner salvato con successo.']);
            break;

        case 'delete_partner':
            $id = $_POST['id'] ?? null;
            if (!$id) jsonError('ID partner non fornito.');

            $stmt = $pdo->prepare('SELECT logo_path FROM telephony_partners WHERE id = ?');
            $stmt->execute([$id]);
            $partner = $stmt->fetch(PDO::FETCH_ASSOC);

            $stmt = $pdo->prepare('DELETE FROM telephony_partners WHERE id = ?');
            $stmt->execute([$id]);

            if ($partner && $partner['logo_path'] && file_exists('../../' . $partner['logo_path'])) {
                @unlink('../../' . $partner['logo_path']);
            }

            jsonSuccess(['message' => 'Partner eliminato con successo.']);
            break;
        
        default:
            jsonError('Azione non valida.');
            break;
    }
} catch (Throwable $e) {
    jsonError('Errore del server.', $e);
}
