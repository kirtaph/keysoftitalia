/**
 * Key Soft Italia — Service Worker (DEV)
 * Goal: force fresh CSS/JS from network while developing; cache images.
 */
const VERSION = 'dev-2';
const IMG_CACHE = 'img-cache-' + VERSION;

self.addEventListener('install', (event) => {
  self.skipWaiting();
});

self.addEventListener('activate', (event) => {
  event.waitUntil(
    caches.keys().then(keys => Promise.all(
      keys.filter(k => ![IMG_CACHE].includes(k)).map(k => caches.delete(k))
    )).then(() => self.clients.claim())
  );
});

/**
 * Strategy:
 * - CSS/JS/HTML -> network-first, bypass HTTP cache
 * - images/fonts -> cache-first
 */
self.addEventListener('fetch', (event) => {
  const req = event.request;
  const url = new URL(req.url);

  // Dev bypass for CSS/JS/HTML
  if (/\.(css|js|html?)$/i.test(url.pathname)) {
    event.respondWith((async () => {
      try {
        const fresh = await fetch(req, { cache: 'no-store' });
        return fresh;
      } catch (err) {
        // fallback to cache if offline (not ideal for dev, but better than nothing)
        const cached = await caches.match(req);
        if (cached) return cached;
        throw err;
      }
    })());
    return;
  }

  // Cache-first for images/fonts
  if (/\.(png|jpe?g|webp|gif|svg|ico|woff2?|ttf|otf)$/i.test(url.pathname)) {
    event.respondWith((async () => {
      const cache = await caches.open(IMG_CACHE);
      const match = await cache.match(req);
      if (match) return match;
      const resp = await fetch(req);
      if (resp && resp.ok) cache.put(req, resp.clone());
      return resp;
    })());
    return;
  }

  // default: just pass-through
});
