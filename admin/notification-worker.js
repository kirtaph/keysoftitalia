self.addEventListener('install', event => event.waitUntil(self.skipWaiting()));
self.addEventListener('activate', event => event.waitUntil(clients.claim()));

function notificationTarget(value) {
    const fallback = new URL('notifications.php', self.registration.scope);
    try {
        const target = new URL(typeof value === 'string' ? value : fallback.href, self.registration.scope);
        return target.origin === fallback.origin && target.pathname.startsWith(new URL(self.registration.scope).pathname) ? target.href : fallback.href;
    } catch (_) { return fallback.href; }
}
self.addEventListener('push', event => {
    let payload = {};
    try { payload = event.data?.json() || {}; } catch (_) {}
    const title = typeof payload.title === 'string' ? payload.title.slice(0,160) : 'Nuove richieste sul sito';
    const body = typeof payload.body === 'string' ? payload.body.slice(0,500) : 'Apri il centro notifiche per vedere le richieste ricevute.';
    const tag = typeof payload.tag === 'string' ? payload.tag.slice(0,80) : 'ksi-admin-requests';
    event.waitUntil(self.registration.showNotification(title, {
        body, tag, icon: '../assets/img/pwa/icon-192.png',
        data: {url: notificationTarget(payload.url)}
    }));
});
self.addEventListener('notificationclick', event => {
    event.notification.close();
    const target = notificationTarget(event.notification.data?.url);
    event.waitUntil(clients.matchAll({type:'window',includeUncontrolled:true}).then(async windows => {
        for (const window of windows) {
            if (window.url.startsWith(self.registration.scope)) {
                await window.navigate(target); return window.focus();
            }
        }
        return clients.openWindow(target);
    }));
});
