<?php
$escape = static fn($value) => htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
?>
<form method="get" class="mb-4" aria-label="Filtra le richieste">
    <div class="row g-3 align-items-end">
        <div class="col-lg-4">
            <label for="searchInput" class="form-label">Cerca una richiesta</label>
            <input type="search" name="q" id="searchInput" class="form-control" maxlength="160" value="<?= $escape($list['q']) ?>" placeholder="Cliente, email, telefono, marca o modello">
        </div>
        <div class="col-sm-6 col-lg-2">
            <label for="statusFilter" class="form-label">Stato</label>
            <select id="statusFilter" name="status" class="form-select">
                <option value="">Tutti gli stati</option>
                <?php foreach (\KeySoftItalia\AdminList::STATUSES[$list['module']] as $value => $label): ?>
                    <option value="<?= $escape($value) ?>" <?= $list['status'] === $value ? 'selected' : '' ?>><?= $escape($label) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <?php if ($list['module'] === 'quotes'): ?>
        <div class="col-sm-6 col-lg-2">
            <label for="deviceFilter" class="form-label">Dispositivo</label>
            <select id="deviceFilter" name="device" class="form-select">
                <option value="">Tutti i dispositivi</option>
                <?php foreach ($pdo->query('SELECT DISTINCT name FROM devices ORDER BY name')->fetchAll(PDO::FETCH_COLUMN) as $device): ?>
                    <option value="<?= $escape($device) ?>" <?= $list['device'] === $device ? 'selected' : '' ?>><?= $escape($device) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <?php endif; ?>
        <div class="col-sm-6 col-lg-2">
            <label for="pageSize" class="form-label">Per pagina</label>
            <select name="per_page" id="pageSize" class="form-select">
                <?php foreach ([25,50,100] as $size): ?><option value="<?= $size ?>" <?= $list['per_page'] === $size ? 'selected' : '' ?>><?= $size ?> richieste</option><?php endforeach; ?>
            </select>
        </div>
        <div class="col-sm-6 col-lg-auto d-flex gap-2">
            <button class="btn btn-primary" type="submit">Cerca</button>
            <?php if ($list['q'] !== '' || $list['status'] !== '' || $list['device'] !== ''): ?><a class="btn btn-outline-secondary" href="<?= $escape(basename($_SERVER['SCRIPT_NAME'])) ?>">Azzera filtri</a><?php endif; ?>
        </div>
    </div>
</form>
