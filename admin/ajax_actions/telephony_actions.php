<?php
require_once __DIR__ . '/init.php';
require_once __DIR__ . '/../../src/AdminMedia.php';
$media = new \KeySoftItalia\AdminMedia();

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
            if (!$id) throw new InvalidArgumentException('ID promozione non fornito.');
            
            $stmt = $pdo->prepare('SELECT * FROM telephony_promotions WHERE id = ?');
            $stmt->execute([$id]);
            $promotion = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$promotion) throw new InvalidArgumentException('Record non trovato.');
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

            if (!$partner_id) throw new InvalidArgumentException('L\'operatore (Brand) è obbligatorio.');
            if (empty($plan_name)) throw new InvalidArgumentException('Il nome dell\'offerta è obbligatorio.');
            if ($price === '') throw new InvalidArgumentException('Il prezzo è obbligatorio.');
            
            $price = \KeySoftItalia\BackendValidation::money($price, 'price');
            $stmt = $pdo->prepare('SELECT name FROM telephony_partners WHERE id = ?');
            $stmt->execute([$partner_id]); $partnerName = $stmt->fetchColumn();
            if ($partnerName === false) throw new InvalidArgumentException('Partner non trovato.');
            if ($action === 'edit') {
                $stmt = $pdo->prepare('SELECT id FROM telephony_promotions WHERE id = ?');
                $stmt->execute([$id]);
                if (!$stmt->fetchColumn()) throw new InvalidArgumentException('Promozione non trovata.');
            }


            if ($action === 'add') {
                $stmt = $pdo->prepare(
                    'INSERT INTO telephony_promotions (partner_id, plan_name, price, price_detail, features, is_featured, status, operator_name)
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?)'
                );
                $stmt->execute([$partner_id, $plan_name, $price, $price_detail, $features, $is_featured, $status, $partnerName]);
            } else {
                $stmt = $pdo->prepare(
                    'UPDATE telephony_promotions SET partner_id=?, plan_name=?, price=?, price_detail=?, features=?, is_featured=?, status=?, operator_name=? WHERE id = ?'
                );
                $stmt->execute([$partner_id, $plan_name, $price, $price_detail, $features, $is_featured, $status, $partnerName, $id]);
            }
            
            jsonSuccess(['message' => 'Promozione salvata con successo.']);
            break;

        case 'delete':
            $id = $_POST['id'] ?? null;
            if (!$id) throw new InvalidArgumentException('ID promozione non fornito.');

            $stmt = $pdo->prepare('SELECT logo_path FROM telephony_promotions WHERE id = ?');
            $stmt->execute([$id]);
            $promo = $stmt->fetch(PDO::FETCH_ASSOC);

            $stmt = $pdo->prepare('DELETE FROM telephony_promotions WHERE id = ?');
            $stmt->execute([$id]);

            \KeySoftItalia\AdminMedia::remove($promo['logo_path'] ?? null, 'uploads/operators');

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
            if (!$id) throw new InvalidArgumentException('ID richiesta non fornito.');
            if (empty($status)) throw new InvalidArgumentException('Stato non fornito.');

            $stmt = $pdo->prepare('UPDATE telephony_requests SET status = ? WHERE id = ?');
            $stmt->execute([$status, $id]);

            jsonSuccess(['message' => 'Stato richiesta aggiornato con successo.']);
            break;

        case 'delete_request':
            $id = $_POST['id'] ?? null;
            if (!$id) throw new InvalidArgumentException('ID richiesta non fornito.');

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
            if (!$id) throw new InvalidArgumentException('ID partner non fornito.');

            $stmt = $pdo->prepare('SELECT * FROM telephony_partners WHERE id = ?');
            $stmt->execute([$id]);
            $partner = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$partner) throw new InvalidArgumentException('Record non trovato.');
            jsonSuccess(['partner' => $partner]);
            break;

        case 'add_partner':
        case 'edit_partner':
            $pdo->beginTransaction();
            $id = $_POST['id'] ?? null;
            $name = trim($_POST['name'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $icon_class = trim($_POST['icon_class'] ?? 'ri-smartphone-line');
            $icon_color = trim($_POST['icon_color'] ?? 'var(--ks-orange)');
            $sort_order = isset($_POST['sort_order']) ? (int)$_POST['sort_order'] : 0;
            $status = isset($_POST['status']) ? (int)$_POST['status'] : 1;

            if (empty($name)) throw new InvalidArgumentException('Il nome del partner è obbligatorio.');

            $logo_path = null;
            if ($action === 'edit_partner') {
                $stmt = $pdo->prepare('SELECT logo_path FROM telephony_partners WHERE id = ?');
                $stmt->execute([$id]);
                $partner = $stmt->fetch(PDO::FETCH_ASSOC);
                if (!$partner) throw new InvalidArgumentException('Partner non trovato.');
                $logo_path = $partner['logo_path'];
            }
            $newLogo = $media->upload($_FILES['logo_file'] ?? null, 'uploads/operators', 5, false, true);
            if ($newLogo) { $media->retire($logo_path, 'uploads/operators'); $logo_path = $newLogo; }

            if ($action === 'add_partner') {
                $stmt = $pdo->prepare(
                    'INSERT INTO telephony_partners (name, description, icon_class, icon_color, logo_path, sort_order, status)
                     VALUES (?, ?, ?, ?, ?, ?, ?)'
                );
                $stmt->execute([$name, $description, $icon_class, $icon_color, $logo_path, $sort_order, $status]);
            } else {
                if (!$id) throw new InvalidArgumentException('ID partner non fornito per la modifica.');
                $stmt = $pdo->prepare(
                    'UPDATE telephony_partners SET name = ?, description = ?, icon_class = ?, icon_color = ?, logo_path = ?, sort_order = ?, status = ? WHERE id = ?'
                );
                $stmt->execute([$name, $description, $icon_class, $icon_color, $logo_path, $sort_order, $status, $id]);
            }

            $pdo->commit();
            $media->saved();
            jsonSuccess(['message' => 'Partner salvato con successo.']);
            break;

        case 'delete_partner':
            $id = $_POST['id'] ?? null;
            if (!$id) throw new InvalidArgumentException('ID partner non fornito.');

            $stmt = $pdo->prepare('SELECT logo_path FROM telephony_partners WHERE id = ?');
            $stmt->execute([$id]);
            $partner = $stmt->fetch(PDO::FETCH_ASSOC);

            $stmt = $pdo->prepare('DELETE FROM telephony_partners WHERE id = ?');
            $stmt->execute([$id]);

            \KeySoftItalia\AdminMedia::remove($partner['logo_path'] ?? null, 'uploads/operators');

            jsonSuccess(['message' => 'Partner eliminato con successo.']);
            break;
        
        default:
            throw new InvalidArgumentException('Azione non valida.');
            break;
    }
} catch (Throwable $e) {
    $media->abort();
    jsonError($e instanceof InvalidArgumentException ? $e->getMessage() : 'Errore del server.', $e);
}
