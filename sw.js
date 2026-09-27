// Palavreado - Service Worker (100% Offline Resilience)
const CACHE_NAME = 'palavreado-v1.0';
const ASSETS_TO_CACHE = [
  './',
  './index.php',
  './manifest.json',
  './palavras.json',
  './favicon.svg',
  './favicon-32x32.png',
  './icon-192.png',
  './icon-512.png',
  './privacidade.php',
  './termos.php',
  './suporte.php'
];

self.addEventListener('install', (event) => {
  event.waitUntil(
    caches.open(CACHE_NAME).then((cache) => {
      return cache.addAll(ASSETS_TO_CACHE).catch((err) => {
        console.warn('[SW] Aviso no cache inicial:', err);
      });
    }).then(() => self.skipWaiting())
  );
});

self.addEventListener('activate', (event) => {
  event.waitUntil(
    caches.keys().then((keys) => {
      return Promise.all(
        keys.map((key) => {
          if (key !== CACHE_NAME) {
            return caches.delete(key);
          }
        })
      );
    }).then(() => self.clients.claim())
  );
});

self.addEventListener('fetch', (event) => {
  const req = event.request;
  if (req.method !== 'GET') return;

  // Cache-First for dictionary, images and styles
  if (req.url.includes('palavras.json') || req.url.includes('.png') || req.url.includes('.svg') || req.url.includes('.css')) {
    event.respondWith(
      caches.match(req).then((cached) => {
        if (cached) return cached;
        return fetch(req).then((networkRes) => {
          if (networkRes && networkRes.status === 200) {
            const clone = networkRes.clone();
            caches.open(CACHE_NAME).then((c) => c.put(req, clone));
          }
          return networkRes;
        }).catch(() => cached);
      })
    );
    return;
  }

  // Network-First for HTML/PHP pages with offline cache fallback
  event.respondWith(
    fetch(req).then((networkRes) => {
      if (networkRes && networkRes.status === 200) {
        const clone = networkRes.clone();
        caches.open(CACHE_NAME).then((c) => c.put(req, clone));
      }
      return networkRes;
    }).catch(() => {
      return caches.match(req).then((cached) => {
        if (cached) return cached;
        if (req.headers.get('accept')?.includes('text/html')) {
          return caches.match('./index.php');
        }
      });
    })
  );
});
