/**
 * Key Soft Italia — Production Service Worker
 * Fully PWA compliant: handles precaching, offline fallback, and asset caching.
 */

const CACHE_NAME = 'ks-pwa-v5';
const OFFLINE_URL = 'offline.html';

// Asset da pre-memorizzare all'installazione
const PRECACHE_ASSETS = [
  OFFLINE_URL,
  'assets/favicon.ico',
  'assets/css/variables.css',
  'assets/css/main.css',
  'assets/css/components.css',
  'assets/js/main.js',
  'assets/img/logo.png',
  'assets/img/pwa/icon-192.png',
  'assets/img/pwa/icon-512.png',
  'assets/img/pwa/icon-maskable-192.png',
  'assets/img/pwa/icon-maskable-512.png'
];

// Installa ed esegui il precaching in modo robusto
self.addEventListener('install', (event) => {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then((cache) => {
        console.log('[Service Worker] Pre-caching offline page and shell assets');
        
        // Caching resiliente: se un file fallisce (es. 404), non interrompe l'installazione
        const cachePromises = PRECACHE_ASSETS.map((asset) => {
          return cache.add(asset).catch((err) => {
            console.warn(`[Service Worker] Failed to precache asset: ${asset}`, err);
          });
        });
        
        return Promise.all(cachePromises);
      })
      .then(() => self.skipWaiting())
  );
});

// Attiva e pulisci cache vecchie
self.addEventListener('activate', (event) => {
  event.waitUntil(
    caches.keys().then((cacheNames) => {
      return Promise.all(
        cacheNames.map((cache) => {
          if (cache !== CACHE_NAME) {
            console.log('[Service Worker] Cleaning old cache:', cache);
            return caches.delete(cache);
          }
        })
      );
    }).then(() => self.clients.claim())
  );
});

// Gestione dei fetch
self.addEventListener('fetch', (event) => {
  const req = event.request;
  const url = new URL(req.url);

  // 1. Escludi richieste di admin, ajax, POST e cross-origin non-GET
  if (
    req.method !== 'GET' ||
    url.pathname.includes('/admin/') ||
    url.pathname.includes('/ajax/') ||
    url.pathname.includes('ajax_actions')
  ) {
    return; // Pass-through alla rete
  }

  // 2. Strategia per le Pagine HTML (Navigate)
  if (req.mode === 'navigate' || req.headers.get('accept').includes('text/html')) {
    event.respondWith(
      fetch(req)
        .then((response) => {
          // Salva una copia nella cache se la risposta è valida
          if (response.status === 200) {
            const responseClone = response.clone();
            caches.open(CACHE_NAME).then((cache) => {
              cache.put(req, responseClone);
            });
          }
          return response;
        })
        .catch(() => {
          // Rete fallita: prova a recuperare dalla cache
          return caches.match(req)
            .then((cachedResponse) => {
              if (cachedResponse) {
                return cachedResponse;
              }
              // Se non è in cache, mostra l'offline fallback
              return caches.match(OFFLINE_URL);
            });
        })
    );
    return;
  }

  // 3. Strategia Network-First per Fogli di Stile e Script (permette aggiornamenti immediati del codice modificato)
  if (req.destination === 'style' || req.destination === 'script' || /\.(css|js)$/i.test(url.pathname)) {
    event.respondWith(
      fetch(req)
        .then((response) => {
          if (response && response.status === 200 && url.origin === self.location.origin) {
            const responseClone = response.clone();
            caches.open(CACHE_NAME).then((cache) => {
              cache.put(req, responseClone);
            });
          }
          return response;
        })
        .catch(() => {
          return caches.match(req, { ignoreSearch: true });
        })
    );
    return;
  }

  // 4. Strategia Cache-First per Immagini e Font (caricamento istantaneo)
  if (
    req.destination === 'image' ||
    req.destination === 'font' ||
    /\.(png|jpe?g|webp|gif|svg|ico|woff2?|ttf)$/i.test(url.pathname)
  ) {
    event.respondWith(
      caches.match(req, { ignoreSearch: true })
        .then((cachedResponse) => {
          if (cachedResponse) {
            return cachedResponse;
          }

          return fetch(req).then((response) => {
            if (response && response.status === 200 && url.origin === self.location.origin) {
              const responseClone = response.clone();
              caches.open(CACHE_NAME).then((cache) => {
                cache.put(req, responseClone);
              });
            }
            return response;
          }).catch((err) => {
            if (req.destination === 'image') {
              return caches.match('assets/img/logo.png');
            }
            throw err;
          });
        })
    );
  }
});

// Ascolta messaggi per compatibilità retroattiva
self.addEventListener('message', (event) => {
  if (event.data && event.data.type === 'SKIP_WAITING') {
    self.skipWaiting();
  }
  
  // Cancella la cache corrente (ad esempio se forzato dal pannello di controllo)
  if (event.data && event.data.type === 'CLEAR_CSS_CACHE') {
    event.waitUntil(
      caches.delete(CACHE_NAME).then(() => {
        console.log('[Service Worker] Cache cancellata su richiesta client');
        return self.clients.matchAll();
      }).then((clients) => {
        clients.forEach(client => client.postMessage({
          type: 'CSS_CACHE_CLEARED'
        }));
      })
    );
  }
});