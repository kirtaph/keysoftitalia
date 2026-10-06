(() => {
    const key = 'ksi-admin-feedback';
    function show(message, type = 'danger') {
        document.querySelectorAll('[data-admin-message]').forEach(el => el.remove());
        const box = document.createElement('div');
        box.dataset.adminMessage = 'true';
        box.className = `alert alert-${type} d-flex justify-content-between align-items-start gap-3`;
        box.setAttribute('role', type === 'danger' ? 'alert' : 'status');
        const text = document.createElement('span');
        text.textContent = message;
        const close = document.createElement('button');
        close.type = 'button'; close.className = 'btn-close'; close.setAttribute('aria-label', 'Chiudi messaggio');
        close.addEventListener('click', () => box.remove());
        box.append(text, close);
        (document.querySelector('.modal.show .modal-body') || document.querySelector('.admin-content')).prepend(box);
    }
    function reload(message) {
        try { sessionStorage.setItem(key, JSON.stringify({path: location.pathname, message})); } catch (_) {}
        location.reload();
    }
    async function request(url, options = {}, button = null) {
        if (button?.disabled) throw new Error('Operazione già in corso. Attendi la risposta.');
        const contents = button ? Array.from(button.childNodes) : [];
        if (button) { button.disabled = true; button.textContent = 'Attendi…'; }
        try {
            let response;
            try { response = await fetch(url, options); }
            catch (_) { throw new Error('Connessione interrotta. Controlla la rete e riprova.'); }
            let data;
            try { data = await response.json(); }
            catch (_) { throw new Error('Il server non ha risposto correttamente. Aggiorna la pagina e riprova.'); }
            if (response.status === 401) throw new Error('La sessione è scaduta. Accedi di nuovo al pannello.');
            if (response.status === 403) throw new Error('La sessione è cambiata. Aggiorna la pagina prima di riprovare.');
            if (!response.ok || data.status !== 'success') throw new Error(data.message || 'Operazione non riuscita. Riprova tra poco.');
            return data;
        } finally { if (button) { button.disabled = false; button.replaceChildren(...contents); } }
    }
    window.AdminFeedback = {show, reload, request};
    document.addEventListener('DOMContentLoaded', () => {
        try {
            const saved = JSON.parse(sessionStorage.getItem(key) || 'null'); sessionStorage.removeItem(key);
            if (saved?.path === location.pathname) show(saved.message, 'success');
        } catch (_) {}
    });
})();
