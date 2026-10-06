(() => {
    const badge = document.getElementById('notificationBadge');
    if (!badge) return;
    const inbox = document.getElementById('inboxList');
    const status = document.getElementById('inboxStatus');
    const url = new URL(location.href);
    let page = Math.max(1, Number(url.searchParams.get('page')) || 1);
    let unread = url.searchParams.get('unread') === '1';
    let cursor = 0, pages = 1, publicKey = '', busy = false, stopped = false;
    let registration = null;
    let rendered = '';
    const icons = {quote:'fa-file-invoice-dollar', used:'fa-recycle', booking:'fa-calendar-check', telephony:'fa-phone', utility:'fa-bolt', liberty:'fa-download', contact:'fa-envelope', assistance:'fa-screwdriver-wrench'};
    async function api(action, values = {}, write = false) {
        let response;
        const query = new URLSearchParams({action, ...values});
        if (write) {
            const body = new FormData(); body.set('action', action);
            Object.entries(values).forEach(([key,value]) => body.set(key,value));
            response = await fetch('ajax_actions/notification_actions.php', {method:'POST',body});
        } else response = await fetch('ajax_actions/notification_actions.php?' + query, {cache:'no-store'});
        if (response.status === 401) { stopped = true; throw new Error('La sessione è scaduta. Accedi di nuovo al pannello.'); }
        let data;
        try { data = await response.json(); } catch (_) { throw new Error('Il server non ha risposto correttamente. Riprova.'); }
        if (!response.ok || data.status !== 'success') throw new Error(data.message || 'Impossibile aggiornare le notifiche. Riprova.');
        return data;
    }
    function message(text, error = false) {
        if (status) { status.textContent = text; status.classList.toggle('error', error); }
        else { badge.title = text; badge.closest('a').setAttribute('aria-label',text); }
    }
    function stateUrl() {
        const current = new URL(location.href);
        current.searchParams.set('page',page);
        if (unread) current.searchParams.set('unread','1'); else current.searchParams.delete('unread');
        history.replaceState(null,'',current);
    }
    function render(data) {
        badge.textContent = data.unread > 99 ? '99+' : data.unread;
        badge.hidden = data.unread === 0;
        badge.closest('a').setAttribute('aria-label',`Centro notifiche: ${data.unread} da leggere`);
        if (!inbox) return;
        page = data.page; pages = data.pages;
        document.getElementById('inboxAll').setAttribute('aria-pressed',String(!unread));
        document.getElementById('inboxUnread').setAttribute('aria-pressed',String(unread));
        document.getElementById('inboxUnreadCount').textContent = `(${data.unread})`;
        document.getElementById('inboxReadAll').disabled = data.unread === 0;
        inbox.replaceChildren();
        for (const row of data.rows) {
            const element = document.createElement('article'); element.className = 'inbox-row' + (row.read_at ? '' : ' unread');
            const symbol = document.createElement('span'); symbol.className = 'inbox-symbol'; symbol.setAttribute('aria-hidden','true');
            const icon = document.createElement('i'); icon.className = 'fas ' + (icons[row.source] || 'fa-bell'); symbol.append(icon);
            const copy = document.createElement('div'); copy.className = 'inbox-copy';
            const link = document.createElement('a'); link.href = row.url; link.textContent = row.title + (row.source_id ? ` · #${row.source_id}` : '');
            link.addEventListener('click', async event => {
                // Keep modified clicks available as normal links; reading is a separate explicit action then.
                if (event.ctrlKey || event.metaKey || event.shiftKey || event.altKey) return;
                event.preventDefault();
                try { await api('read',{id:row.id},true); location.assign(link.href); } catch (error) { message(error.message,true); }
            });
            const when = document.createElement('small');
            const date = new Date(row.created_at.replace(' ','T'));
            when.textContent = (row.read_at ? 'Letta · ' : 'Da leggere · ') + new Intl.DateTimeFormat('it-IT',{dateStyle:'medium',timeStyle:'short'}).format(date);
            copy.append(link,when); element.append(symbol,copy);
            if (!row.read_at) {
                const mark = document.createElement('button'); mark.type = 'button';mark.className = 'btn btn-outline-secondary btn-sm';mark.textContent = 'Segna come letta';
                mark.setAttribute('aria-label',`Segna come letta: ${row.title}`);
                mark.addEventListener('click',async () => { mark.disabled = true; try { await api('read',{id:row.id},true); await load(); } catch (error) { mark.disabled = false; message(error.message,true); } });
                element.append(mark);
            }
            inbox.append(element);
        }
        if (!data.rows.length) {
            const empty = document.createElement('div'); empty.className = 'inbox-empty';
            empty.textContent = unread ? 'Hai letto tutte le richieste. Le nuove compariranno qui automaticamente.' : 'Le richieste inviate dal sito compariranno qui automaticamente.'; inbox.append(empty);
        }
        document.getElementById('inboxPage').textContent = `Pagina ${page} di ${pages} · ${data.total} notifiche`;
        document.getElementById('inboxPrevious').disabled = page === 1;
        document.getElementById('inboxNext').disabled = page === pages;
        message(`Aggiornato alle ${new Intl.DateTimeFormat('it-IT',{timeStyle:'short'}).format(new Date())}. Controllo automatico ogni 30 secondi.`);
    }
    async function pushState() {
        if (!inbox) return;
        const label = document.getElementById('pushState'), enable = document.getElementById('enablePush'), disable = document.getElementById('disablePush'), configure = document.getElementById('configurePush');
        if (configure) configure.hidden = true;
        const test = document.getElementById('testPush');
        if (test) test.hidden = true;
        if (!window.isSecureContext) { label.textContent = 'Per attivare le notifiche desktop apri il backend tramite HTTPS. Il centro notifiche funziona anche qui.'; return; }
        if (!('Notification' in window) || !('serviceWorker' in navigator) || !('PushManager' in window)) { label.textContent = 'Questo browser non supporta Web Push. Usa Chrome, Edge o Firefox aggiornati.'; return; }
        if (!publicKey) {
            enable.disabled = true;
            if (configure) configure.hidden = false;
            label.textContent = 'Manca la configurazione Web Push del server. Premi “Configura Web Push”, poi attiva le notifiche su questo browser.';
            return;
        }
        try {
            registration = await navigator.serviceWorker.getRegistration(new URL('./',location.href).href);
            // Ignore the public site's worker, whose scope is the root.
            if (registration && registration.scope !== new URL('./',location.href).href) registration = null;
            if (registration) registration.update().catch(() => {});
            const subscription = registration ? await registration.pushManager.getSubscription() : null;
            if (subscription && Notification.permission === 'granted') {
                await api('subscribe',{subscription:JSON.stringify(subscription)},true);
                enable.hidden = true; disable.hidden = false;
                if (test) test.hidden = false;
                label.textContent = 'Browser registrato. Invia una notifica di prova per verificare la ricezione. Gli avvisi automatici richiedono il servizio di invio sul hosting.';
            } else if (Notification.permission === 'denied') {
                enable.disabled = true;label.textContent = 'Notifiche bloccate. Consenti le notifiche nelle impostazioni di questo sito, poi aggiorna la pagina.';
            } else { enable.disabled = false; enable.hidden = false; disable.hidden = true;label.textContent = 'Attivale su questo browser per ricevere gli avvisi. I dati dei clienti non vengono mostrati sul desktop.'; }
        } catch (error) { label.textContent = error.message || 'Impossibile verificare Web Push. Aggiorna la pagina.'; }
    }
    async function load() {
        if (busy || stopped) return;
        busy = true; if (inbox) inbox.setAttribute('aria-busy','true');
        try {
            const data = await api('list',{page,unread:unread?'1':'0'});cursor = data.cursor;publicKey = data.publicKey;
            const snapshot = JSON.stringify([data.rows,data.unread,data.page,data.total,unread]);
            if (snapshot !== rendered) { render(data); rendered = snapshot; }
            else if (inbox) message(`Aggiornato alle ${new Intl.DateTimeFormat('it-IT',{timeStyle:'short'}).format(new Date())}. Controllo automatico ogni 30 secondi.`);
        }
        catch (error) { message(error.message || 'Connessione interrotta. Riprova.',true); }
        finally { busy = false;if (inbox) inbox.setAttribute('aria-busy','false'); }
    }
    if (inbox) {
        document.getElementById('testPush')?.addEventListener('click',async event => {
            const button = event.currentTarget; button.disabled = true;
            const label = document.getElementById('pushState');
            label.textContent = 'Invio della notifica di prova…';
            try {
                const subscription = await registration?.pushManager.getSubscription();
                if (!subscription) throw new Error('Attiva prima le notifiche su questo browser.');
                const result = await api('test_push',{subscription:JSON.stringify(subscription)},true);
                label.textContent = result.message;
            } catch (error) { label.textContent = error.message || 'Invio della prova non riuscito.'; }
            finally { button.disabled = false; }
        });
        document.getElementById('configurePush')?.addEventListener('click',async event => {
            const button = event.currentTarget; button.disabled = true;
            try {
                const configured = await api('setup_push',{},true);
                publicKey = configured.publicKey || '';
                await load();
                await pushState();
            } catch (error) {
                document.getElementById('pushState').textContent = error.message || 'Configurazione non riuscita. Controlla i permessi e le dipendenze del server.';
            } finally { button.disabled = false; }
        });
        document.getElementById('inboxRefresh').addEventListener('click',load);
        document.getElementById('inboxAll').addEventListener('click',() => { unread = false;page = 1;stateUrl();load(); });
        document.getElementById('inboxUnread').addEventListener('click',() => { unread = true;page = 1;stateUrl();load(); });
        document.getElementById('inboxPrevious').addEventListener('click',() => { if (page > 1) { --page;stateUrl();load(); } });
        document.getElementById('inboxNext').addEventListener('click',() => { if (page < pages) { ++page;stateUrl();load(); } });
        document.getElementById('inboxReadAll').addEventListener('click',async event => {
            const button = event.currentTarget;button.disabled = true;
            try { await api('read_all',{through:cursor},true);await load(); } catch (error) { button.disabled = false;message(error.message,true); }
        });
        document.getElementById('enablePush').addEventListener('click',async event => {
            const button = event.currentTarget;button.disabled = true;
            try {
                const permission = await Notification.requestPermission();
                if (permission !== 'granted') { await pushState(); return; }
                registration = await navigator.serviceWorker.register('notification-worker.js',{scope:'./'});
                if (!registration.active) await new Promise((resolve,reject) => {
                    const worker = registration.installing || registration.waiting;
                    if (!worker) return reject(new Error('Impossibile attivare il servizio notifiche. Riprova.'));
                    worker.addEventListener('statechange',() => { if (worker.state === 'activated') resolve(); else if (worker.state === 'redundant') reject(new Error('Servizio notifiche non disponibile.')); });
                });
                const raw = atob(publicKey.replace(/-/g,'+').replace(/_/g,'/'));
                const key = Uint8Array.from(raw,c => c.charCodeAt(0));
                const subscription = await registration.pushManager.subscribe({userVisibleOnly:true,applicationServerKey:key});
                await api('subscribe',{subscription:JSON.stringify(subscription)},true); await pushState();
            } catch (error) { document.getElementById('pushState').textContent = error.message || 'Attivazione non riuscita. Riprova.';button.disabled = false; }
        });
        document.getElementById('disablePush').addEventListener('click',async event => {
            const button = event.currentTarget;button.disabled = true;
            try {
                const subscription = await registration?.pushManager.getSubscription();
                if (subscription) { await api('unsubscribe',{subscription:JSON.stringify(subscription)},true);await subscription.unsubscribe(); }
                await pushState();
            } catch (error) { document.getElementById('pushState').textContent = error.message; }
            finally { button.disabled = false; }
        });
        const open = url.searchParams.get('open');
        if (/^[1-9]\d*$/.test(open || '')) {
            api('get',{id:open}).then(data => {
                const row = data.notification;
                if (!['contact','assistance'].includes(row.source)) return;
                const panel = document.getElementById('inboxDetail');panel.className = 'inbox-detail';
                const title = document.createElement('h2'); title.textContent = row.title;panel.append(title);
                const values = JSON.parse(row.detail || '{}'), list = document.createElement('dl');
                const labels = {name:'Nome',phone:'Telefono',email:'Email',assistance_type:'Tipo richiesta',device_type:'Dispositivo',address:'Indirizzo',problem_description:'Messaggio',urgency:'Urgenza',time_preference:'Disponibilità',subject:'Oggetto',source:'Origine'};
                for (const [key,value] of Object.entries(values)) { if (!value) continue;const term = document.createElement('dt'), description = document.createElement('dd');term.textContent = labels[key] || key;description.textContent = String(value);list.append(term,description); }
                panel.append(list);
            }).catch(error => message(error.message,true));
        }
    }
    load().then(pushState);
    setInterval(load,30000);
    window.addEventListener('online',load);
    document.addEventListener('visibilitychange',() => { if (!document.hidden) load(); });
})();
