<?php
require_once __DIR__ . '/init.php';

$action = $_POST['action'] ?? $_GET['action'] ?? '';

try {
    switch ($action) {
        case 'list':
            $stmt = $pdo->query("SELECT * FROM logo_campaigns ORDER BY start_date DESC");
            $campaigns = $stmt->fetchAll(PDO::FETCH_ASSOC);
            jsonSuccess(['campaigns' => $campaigns]);
            break;

        case 'get':
            $id = $_GET['id'] ?? null;
            if (!$id) jsonError('ID mancante');
            
            $stmt = $pdo->prepare("SELECT * FROM logo_campaigns WHERE id = ?");
            $stmt->execute([$id]);
            $campaign = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$campaign) jsonError('Campagna non trovata');
            jsonSuccess(['campaign' => $campaign]);
            break;

        case 'add':
        case 'edit':
            $id = $_POST['id'] ?? null;
            $name = trim($_POST['name'] ?? '');
            $start_date = $_POST['start_date'] ?? '';
            $end_date = $_POST['end_date'] ?? '';
            $effect_class = trim($_POST['effect_class'] ?? '');
            $status = isset($_POST['status']) ? (int)$_POST['status'] : 1;
            
            $isSystemCampaign = false;
            if ($action === 'edit' && $id) {
                $stmtCheck = $pdo->prepare("SELECT system_key FROM logo_campaigns WHERE id = ?");
                $stmtCheck->execute([$id]);
                $systemKey = $stmtCheck->fetchColumn();
                if ($systemKey !== null && $systemKey !== false) {
                    $isSystemCampaign = true;
                }
            }

            if (!$isSystemCampaign) {
                if (!$name || !$start_date || !$end_date) {
                    jsonError('Compila tutti i campi obbligatori (Nome, Data Inizio, Data Fine).');
                }
                if ($start_date > $end_date) {
                    jsonError('La data di fine non può essere precedente alla data di inizio.');
                }
            } else {
                if (!$name) {
                    jsonError('Il nome della campagna è obbligatorio.');
                }
                if (!$start_date) $start_date = '2000-01-01';
                if (!$end_date) $end_date = '2000-12-31';
            }

            $logo_path = null;
            
            if (isset($_FILES['logo_file']) && $_FILES['logo_file']['error'] !== UPLOAD_ERR_NO_FILE) {
                if ($_FILES['logo_file']['error'] !== UPLOAD_ERR_OK) {
                    switch ($_FILES['logo_file']['error']) {
                        case UPLOAD_ERR_INI_SIZE:
                            jsonError('Il file caricato supera la dimensione massima consentita dal server.');
                        case UPLOAD_ERR_PARTIAL:
                            jsonError('Il caricamento dell\'immagine è stato interrotto.');
                        default:
                            jsonError('Errore nel caricamento del file.');
                    }
                }

                $uploadDir = '../../assets/img/campaigns/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                
                $fileInfo = pathinfo($_FILES['logo_file']['name']);
                $ext = strtolower($fileInfo['extension']);
                $allowed = ['png', 'jpg', 'jpeg', 'webp', 'svg', 'gif'];
                
                if (!in_array($ext, $allowed)) {
                    jsonError('Formato immagine non supportato.');
                }
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $mime = finfo_file($finfo, $_FILES['logo_file']['tmp_name']);
                finfo_close($finfo);
                if (!in_array($mime, ['image/png', 'image/jpeg', 'image/webp', 'image/svg+xml', 'image/gif'])) {
                    jsonError('Tipo MIME non consentito.');
                }
                if ($_FILES['logo_file']['size'] > 5 * 1024 * 1024) {
                    jsonError('File troppo grande. Massimo 5MB.');
                }
                
                $filename = uniqid('logo_') . '.' . $ext;
                $targetPath = $uploadDir . $filename;
                
                if (move_uploaded_file($_FILES['logo_file']['tmp_name'], $targetPath)) {
                    $logo_path = 'img/campaigns/' . $filename;
                } else {
                    jsonError("Errore durante l'upload del file.");
                }
            }

            if ($action === 'add') {
                if (!$logo_path) {
                    jsonError('Il logo è obbligatorio per una nuova campagna.');
                }
                $stmt = $pdo->prepare("INSERT INTO logo_campaigns (name, start_date, end_date, logo_path, effect_class, status) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->execute([$name, $start_date, $end_date, $logo_path, $effect_class, $status]);
            } else {
                if (!$id) jsonError('ID mancante per la modifica.');
                
                if ($logo_path) {
                    $stmtOld = $pdo->prepare("SELECT logo_path FROM logo_campaigns WHERE id = ?");
                    $stmtOld->execute([$id]);
                    $old = $stmtOld->fetchColumn();
                    if ($old && $old !== 'img/logo.png' && file_exists('../../assets/' . $old)) {
                        unlink('../../assets/' . $old);
                    }
                    
                    $stmt = $pdo->prepare("UPDATE logo_campaigns SET name=?, start_date=?, end_date=?, logo_path=?, effect_class=?, status=? WHERE id=?");
                    $stmt->execute([$name, $start_date, $end_date, $logo_path, $effect_class, $status, $id]);
                } else {
                    $stmt = $pdo->prepare("UPDATE logo_campaigns SET name=?, start_date=?, end_date=?, effect_class=?, status=? WHERE id=?");
                    $stmt->execute([$name, $start_date, $end_date, $effect_class, $status, $id]);
                }
            }
            
            jsonSuccess([]);
            break;

        case 'delete':
            $id = $_POST['id'] ?? null;
            if (!$id) jsonError('ID mancante');

            $stmtCheck = $pdo->prepare("SELECT system_key, logo_path FROM logo_campaigns WHERE id = ?");
            $stmtCheck->execute([$id]);
            $campaignData = $stmtCheck->fetch(PDO::FETCH_ASSOC);
            
            if (!$campaignData) jsonError('Campagna non trovata');
            if ($campaignData['system_key'] !== null) {
                jsonError('Non è consentito eliminare le campagne di sistema.');
            }
            
            $old = $campaignData['logo_path'];
            if ($old && $old !== 'img/logo.png' && file_exists('../../assets/' . $old)) {
                unlink('../../assets/' . $old);
            }
            
            $stmt = $pdo->prepare("DELETE FROM logo_campaigns WHERE id = ?");
            $stmt->execute([$id]);
            
            jsonSuccess([]);
            break;

        default:
            jsonError('Azione non valida');
    }
} catch (Throwable $e) {
    jsonError('Errore del server.', $e);
}
