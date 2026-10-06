<?php include_once 'includes/header.php'; ?>
<div class="inbox-heading">
    <div><h1>Centro notifiche</h1><p>Tutte le richieste ricevute dal sito, in un unico posto. Apri una richiesta per gestirla nel modulo dedicato.</p></div>
    <button type="button" class="btn btn-outline-secondary" id="inboxRefresh"><i class="fas fa-rotate-right me-2" aria-hidden="true"></i>Aggiorna</button>
</div>
<section class="inbox-settings" aria-label="Notifiche desktop">
    <div class="inbox-settings-copy"><strong>Resta aggiornato, anche con il pannello chiuso</strong><p id="pushState">Verifica delle notifiche desktop…</p></div>
    <button type="button" class="btn btn-primary" id="enablePush" disabled>Attiva notifiche desktop</button>
    <button type="button" class="btn btn-outline-secondary" id="disablePush" hidden>Disattiva su questo browser</button>
</section>
<div id="inboxDetail"></div>
<div class="inbox-toolbar">
    <div class="inbox-tabs" role="group" aria-label="Filtra le notifiche">
        <button type="button" id="inboxAll" aria-pressed="true">Tutte</button>
        <button type="button" id="inboxUnread" aria-pressed="false">Da leggere <span id="inboxUnreadCount"></span></button>
    </div>
    <button type="button" class="btn btn-outline-secondary btn-sm" id="inboxReadAll">Segna tutte come lette</button>
</div>
<p id="inboxStatus" class="notification-status" role="status" aria-live="polite">Caricamento delle richieste…</p>
<div class="inbox-list" id="inboxList" aria-busy="true"></div>
<div class="inbox-pagination">
    <span id="inboxPage" class="small text-muted"></span>
    <div class="d-flex gap-2"><button type="button" class="btn btn-outline-secondary btn-sm" id="inboxPrevious">Precedente</button><button type="button" class="btn btn-outline-secondary btn-sm" id="inboxNext">Successiva</button></div>
</div>
<?php include_once 'includes/footer.php'; ?>
