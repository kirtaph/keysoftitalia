const CACHE_NAME = 'v1';
const CSS_CACHE_NAME = 'css-no-cache';

// Installazione del service worker
self.addEventListener('install', (event) => {
  console.log('Service Worker: Installato');
  self.skipWaiting();
});

// Attivazione del service worker
self.addEventListener('activate', (event) => {
  console.log('Service Worker: Attivato');
  
  // Pulizia cache vecchie
  event.waitUntil(
    caches.keys().then((cacheNames) => {
      return Promise.all(
        cacheNames.map((cache) => {
          if (cache !== CACHE_NAME && cache !== CSS_CACHE_NAME) {
            console.log('Service Worker: Pulizia cache vecchia', cache);
            return caches.delete(cache);
          }
        })
      );
    })
  );
  
  return self.clients.claim();
});

// Intercetta le richieste
self.addEventListener('fetch', (event) => {
  const url = new URL(event.request.url);
  
  // Controlla se è un file CSS
  if (event.request.url.endsWith('.css') || 
      event.request.destination === 'style') {
    
    console.log('Service Worker: Forzato reload CSS:', event.request.url);
    
    // Strategia: Network First per i CSS (forza il caricamento dalla rete)
    event.respondWith(
      fetch(event.request, {
        cache: 'no-store', // Non usa la cache del browser
        headers: {
          'Cache-Control': 'no-cache, no-store, must-revalidate',
          'Pragma': 'no-cache',
          'Expires': '0'
        }
      })
      .then((response) => {
        // Clona la risposta
        const responseClone = response.clone();
        
        // Opzionale: salva in cache per uso offline (commentare se non necessario)
        // caches.open(CSS_CACHE_NAME).then((cache) => {
        //   cache.put(event.request, responseClone);
        // });
        
        return response;
      })
      .catch((error) => {
        console.log('Service Worker: Errore nel fetch del CSS', error);
        
        // Fallback: prova dalla cache se la rete fallisce
        return caches.match(event.request);
      })
    );
    
  } else {
    // Per altri file, usa la strategia Cache First
    event.respondWith(
      caches.match(event.request)
        .then((response) => {
          return response || fetch(event.request);
        })
    );
  }
});

// Ascolta messaggi dal client per forzare aggiornamenti
self.addEventListener('message', (event) => {
  if (event.data && event.data.type === 'SKIP_WAITING') {
    self.skipWaiting();
  }
  
  if (event.data && event.data.type === 'CLEAR_CSS_CACHE') {
    event.waitUntil(
      caches.delete(CSS_CACHE_NAME).then(() => {
        console.log('Service Worker: Cache CSS cancellata');
        return self.clients.matchAll();
      }).then((clients) => {
        clients.forEach(client => client.postMessage({
          type: 'CSS_CACHE_CLEARED'
        }));
      })
    );
  }
});