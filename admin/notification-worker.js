self.addEventListener('push', event => {
    event.waitUntil(self.registration.showNotification('Nuove richieste sul sito', {
        body: 'Apri il centro notifiche per vedere le richieste ricevute.',
        icon: '../assets/img/pwa/icon-192.png', tag: 'ksi-admin-requests',
        data: {url: new URL('notifications.php', self.registration.scope).href}
    }));
});
self.addEventListener('notificationclick', event => {
    event.notification.close();
    const target = new URL('notifications.php', self.registration.scope).href;
    event.waitUntil(clients.matchAll({type:'window',includeUncontrolled:true}).then(async windows => {
        for (const window of windows) {
            if (window.url.startsWith(self.registration.scope)) {
                await window.navigate(target); return window.focus();
            }
        }
        return clients.openWindow(target);
    }));
});
