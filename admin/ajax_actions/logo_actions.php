<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo json_encode(['status' => 'error', 'message' => 'Non autorizzato']);
    exit;
}

require_once '../../config/config.php';

header('Content-Type: application/json');

$action = $_POST['action'] ?? $_GET['action'] ?? '';

try {
    switch ($action) {
        case 'list':
            $stmt = $pdo->query("SELECT * FROM logo_campaigns ORDER BY start_date DESC");
            $campaigns = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode(['status' => 'success', 'campaigns' => $campaigns]);
            break;

        case 'get':
            $id = $_GET['id'] ?? null;
            if (!$id) throw new Exception('ID mancante');
            
            $stmt = $pdo->prepare("SELECT * FROM logo_campaigns WHERE id = ?");
            $stmt->execute([$id]);
            $campaign = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$campaign) throw new Exception('Campagna non trovata');
            echo json_encode(['status' => 'success', 'campaign' => $campaign]);
            break;

        case 'add':
        case 'edit':
            $id = $_POST['id'] ?? null;
            $name = trim($_POST['name'] ?? '');
            $start_date = $_POST['start_date'] ?? '';
            $end_date = $_POST['end_date'] ?? '';
            $effect_class = trim($_POST['effect_class'] ?? '');
            $status = isset($_POST['status']) ? (int)$_POST['status'] : 1;
            
            // Check if editing a system campaign
            $isSystemCampaign = false;
            $systemKey = null;
            if ($action === 'edit' && $id) {
                $stmtCheck = $pdo->prepare("SELECT system_key FROM logo_campaigns WHERE id = ?");
                $stmtCheck->execute([$id]);
                $systemKey = $stmtCheck->fetchColumn();
                if ($systemKey !== null && $systemKey !== false) {
                    $isSystemCampaign = true;
                }
            }

            // For non-system campaigns, dates are mandatory.
            // For flyer_active, dates are ignored or not required because they depend on the flyer validity itself.
            if (!$isSystemCampaign) {
                if (!$name || !$start_date || !$end_date) {
                    throw new Exception('Compila tutti i campi obbligatori (Nome, Data Inizio, Data Fine).');
                }
                if ($start_date > $end_date) {
                    throw new Exception('La data di fine non può essere precedente alla data di inizio.');
                }
            } else {
                if (!$name) {
                    throw new Exception('Il nome della campagna è obbligatorio.');
                }
                // Default fallback values for system campaign dates if empty
                if (!$start_date) $start_date = '2000-01-01';
                if (!$end_date) $end_date = '2000-12-31';
            }

            $logo_path = null;
            
            // Upload logo
            if (isset($_FILES['logo_file']) && $_FILES['logo_file']['error'] !== UPLOAD_ERR_NO_FILE) {
                if ($_FILES['logo_file']['error'] !== UPLOAD_ERR_OK) {
                    switch ($_FILES['logo_file']['error']) {
                        case UPLOAD_ERR_INI_SIZE:
                            throw new Exception('Il file caricato supera la dimensione massima consentita dal server.');
                        case UPLOAD_ERR_PARTIAL:
                            throw new Exception('Il caricamento dell\'immagine è stato interrotto.');
                        default:
                            throw new Exception('Errore nel caricamento del file (Codice: ' . $_FILES['logo_file']['error'] . ').');
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
                    throw new Exception('Formato immagine non supportato.');
                }
                
                $filename = uniqid('logo_') . '.' . $ext;
                $targetPath = $uploadDir . $filename;
                
                if (move_uploaded_file($_FILES['logo_file']['tmp_name'], $targetPath)) {
                    $logo_path = 'img/campaigns/' . $filename;
                } else {
                    throw new Exception("Errore durante l'upload del file.");
                }
            }

            if ($action === 'add') {
                if (!$logo_path) {
                    throw new Exception('Il logo è obbligatorio per una nuova campagna.');
                }
                $stmt = $pdo->prepare("INSERT INTO logo_campaigns (name, start_date, end_date, logo_path, effect_class, status) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->execute([$name, $start_date, $end_date, $logo_path, $effect_class, $status]);
            } else {
                if (!$id) throw new Exception('ID mancante per la modifica.');
                
                if ($logo_path) {
                    // Get old logo to delete (only delete if it is not the default fallback or if it's in campaigns dir)
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
            
            echo json_encode(['status' => 'success']);
            break;

        case 'delete':
            $id = $_POST['id'] ?? null;
            if (!$id) throw new Exception('ID mancante');

            // Prevent deleting system campaigns
            $stmtCheck = $pdo->prepare("SELECT system_key, logo_path FROM logo_campaigns WHERE id = ?");
            $stmtCheck->execute([$id]);
            $campaignData = $stmtCheck->fetch(PDO::FETCH_ASSOC);
            
            if (!$campaignData) throw new Exception('Campagna non trovata');
            if ($campaignData['system_key'] !== null) {
                throw new Exception('Non è consentito eliminare le campagne di sistema.');
            }
            
            $old = $campaignData['logo_path'];
            if ($old && $old !== 'img/logo.png' && file_exists('../../assets/' . $old)) {
                unlink('../../assets/' . $old);
            }
            
            $stmt = $pdo->prepare("DELETE FROM logo_campaigns WHERE id = ?");
            $stmt->execute([$id]);
            
            echo json_encode(['status' => 'success']);
            break;

        default:
            throw new Exception('Azione non valida');
    }
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
