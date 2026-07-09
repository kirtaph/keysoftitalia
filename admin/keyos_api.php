<?php
declare(strict_types=1);

include_once 'includes/header.php';
require_once __DIR__ . '/../src/ApiCredentialStore.php';

use KeySoftItalia\Api\ApiCredentialStore;

$credentialStore = new ApiCredentialStore(BASE_PATH . 'config/runtime/keyos-api.php');
try {
    $credentialStatus = $credentialStore->status();
} catch (Throwable) {
    $credentialStatus = ['configured' => false, 'masked_key' => null, 'created_at' => null];
}
$endpoint = rtrim(BASE_URL, '/') . '/api/v1/refurbished';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="section-title"><i class="fas fa-key me-2 text-primary"></i>API KeyOS</h2>
    <span id="statusBadge" class="badge <?php echo $credentialStatus['configured'] ? 'bg-success' : 'bg-warning text-dark'; ?>">
        <?php echo $credentialStatus['configured'] ? 'Configurata' : 'Non configurata'; ?>
    </span>
</div>

<div class="row g-4">
    <div class="col-lg-7">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h5 class="card-title">Credenziali server-to-server</h5>
                <p class="text-muted">
                    Genera qui la coppia da inserire in KeyOS. La chiave precedente viene invalidata immediatamente.
                    Il secret completo viene mostrato una sola volta.
                </p>

                <dl class="row mb-4">
                    <dt class="col-sm-4">Endpoint</dt>
                    <dd class="col-sm-8"><code><?php echo htmlspecialchars($endpoint, ENT_QUOTES, 'UTF-8'); ?></code></dd>
                    <dt class="col-sm-4">Chiave attiva</dt>
                    <dd class="col-sm-8" id="maskedKey"><?php echo htmlspecialchars($credentialStatus['masked_key'] ?? '—', ENT_QUOTES, 'UTF-8'); ?></dd>
                    <dt class="col-sm-4">Generata il</dt>
                    <dd class="col-sm-8" id="createdAt">
                        <?php echo $credentialStatus['created_at'] ? htmlspecialchars((string)$credentialStatus['created_at'], ENT_QUOTES, 'UTF-8') : '—'; ?>
                    </dd>
                </dl>

                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#rotateModal">
                    <i class="fas fa-sync-alt me-1"></i>
                    <?php echo $credentialStatus['configured'] ? 'Ruota credenziali' : 'Genera credenziali'; ?>
                </button>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h5 class="card-title">Parametri da configurare in KeyOS</h5>
                <ul class="mb-0">
                    <li>Base URL indicata a sinistra</li>
                    <li>Header <code>X-KSI-Key</code></li>
                    <li>Secret per firma HMAC-SHA256</li>
                    <li>Header timestamp Unix <code>X-KSI-Timestamp</code></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div id="credentialsResult" class="card shadow-sm border-success mt-4 d-none">
    <div class="card-header bg-success text-white">
        <i class="fas fa-check-circle me-1"></i> Copia ora queste credenziali
    </div>
    <div class="card-body p-4">
        <div class="alert alert-warning">
            Il secret non sarà più visualizzabile dopo aver lasciato o ricaricato questa pagina.
        </div>
        <label class="form-label fw-bold">API Key</label>
        <div class="input-group mb-3">
            <input id="generatedKey" class="form-control font-monospace" readonly>
            <button class="btn btn-outline-secondary copy-btn" type="button" data-copy="generatedKey">Copia</button>
        </div>
        <label class="form-label fw-bold">Secret HMAC</label>
        <div class="input-group">
            <input id="generatedSecret" class="form-control font-monospace" readonly>
            <button class="btn btn-outline-secondary copy-btn" type="button" data-copy="generatedSecret">Copia</button>
        </div>
    </div>
</div>

<div class="modal fade" id="rotateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Conferma generazione</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Se esiste una chiave attiva, KeyOS smetterà di autenticarsi finché non riceverà la nuova coppia.</p>
                <label for="confirmation" class="form-label">Scrivi <strong>RUOTA</strong> per confermare</label>
                <input id="confirmation" class="form-control" autocomplete="off">
                <div id="generateError" class="text-danger small mt-2"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annulla</button>
                <button type="button" class="btn btn-danger" id="generateBtn">Genera e attiva</button>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('generateBtn').addEventListener('click', async function () {
    const button = this;
    const error = document.getElementById('generateError');
    const form = new FormData();
    form.append('action', 'generate');
    form.append('confirmation', document.getElementById('confirmation').value);
    button.disabled = true;
    error.textContent = '';
    try {
        const response = await fetch('ajax_actions/keyos_api_actions.php', { method: 'POST', body: form });
        const data = await response.json();
        if (!response.ok || data.status !== 'success') throw new Error(data.message || 'Errore di generazione');
        document.getElementById('generatedKey').value = data.api_key;
        document.getElementById('generatedSecret').value = data.secret;
        document.getElementById('credentialsResult').classList.remove('d-none');
        document.getElementById('maskedKey').textContent =
            data.api_key.slice(0, 8) + '••••••••' + data.api_key.slice(-4);
        document.getElementById('createdAt').textContent = data.created_at;
        const badge = document.getElementById('statusBadge');
        badge.className = 'badge bg-success';
        badge.textContent = 'Configurata';
        bootstrap.Modal.getInstance(document.getElementById('rotateModal')).hide();
    } catch (e) {
        error.textContent = e.message;
    } finally {
        button.disabled = false;
    }
});

document.querySelectorAll('.copy-btn').forEach(function (button) {
    button.addEventListener('click', async function () {
        const input = document.getElementById(button.dataset.copy);
        await navigator.clipboard.writeText(input.value);
        const oldText = button.textContent;
        button.textContent = 'Copiato';
        setTimeout(() => button.textContent = oldText, 1200);
    });
});
</script>

<?php include_once 'includes/footer.php'; ?>
