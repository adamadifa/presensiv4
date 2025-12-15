// Installing service worker
const CACHE_NAME = 'pwa-sobat-coding-v2';
const CACHE_VERSION = '2';

/* Add relative URL of all the static content you want to store in
 * cache storage (this will help us use our app offline)*/
let resourcesToCache = ["assets/js/base.js", "assets/css/style.css"];

self.addEventListener("install", e => {
    e.waitUntil(
        caches.open(CACHE_NAME).then(cache => {
            // Jangan fail jika ada resource yang gagal di-cache
            return Promise.allSettled(
                resourcesToCache.map(resource =>
                    cache.add(resource).catch(err => {
                        console.log('Failed to cache:', resource, err);
                    })
                )
            );
        }).then(() => self.skipWaiting())
    );
});

// Cache and return requests
self.addEventListener('fetch', function (event) {
    const request = event.request;
    const url = new URL(request.url);

    // Skip non-GET requests
    if (request.method !== 'GET') {
        return;
    }

    // Skip request untuk API endpoints
    if (url.pathname.startsWith('/api/') ||
        url.pathname.includes('/api') ||
        (request.headers.get('accept') && request.headers.get('accept').includes('application/json'))) {
        return; // Biarkan request API langsung ke network tanpa intercept
    }

    // Skip request untuk external resources (CDN, dll) - biarkan browser handle
    if (url.origin !== location.origin) {
        return;
    }

    // Untuk navigasi request (HTML pages) - jangan intercept, biarkan browser handle langsung
    // Ini mencegah blank page pada akses pertama
    if (request.mode === 'navigate') {
        // Biarkan request langsung ke network tanpa intercept
        // Hanya intercept jika memang diperlukan (misalnya untuk offline support)
        // Untuk sekarang, skip intercept untuk navigasi agar tidak menyebabkan blank page
        return;
    }

    // Untuk static assets (js, css, images), gunakan cache first dengan network fallback
    if (url.pathname.match(/\.(js|css|png|jpg|jpeg|gif|svg|ico|woff|woff2|ttf|eot|webp)$/)) {
        event.respondWith(
            caches.match(request)
                .then(cachedResponse => {
                    // Jika ada di cache, return cached version (lebih cepat)
                    if (cachedResponse) {
                        return cachedResponse;
                    }

                    // Cache miss, fetch from network
                    return fetch(request)
                        .then(response => {
                            // Validasi response sebelum cache
                            if (!response || response.status !== 200 || response.type === 'error') {
                                // Response tidak valid, jangan cache dan return langsung
                                return response;
                            }

                            // Response valid, clone dan cache untuk penggunaan berikutnya
                            const responseToCache = response.clone();
                            caches.open(CACHE_NAME).then(cache => {
                                cache.put(request, responseToCache).catch(err => {
                                    console.log('Failed to cache:', request.url, err);
                                });
                            }).catch(err => {
                                console.log('Failed to open cache:', err);
                            });

                            return response;
                        })
                        .catch(error => {
                            // Jika fetch gagal, log error dan biarkan browser handle
                            // Jangan return error response karena bisa menyebabkan blank page
                            console.log('Fetch failed for:', request.url, error);
                            // Return response kosong dengan error status, browser akan handle
                            return new Response('', { status: 408, statusText: 'Request Timeout' });
                        });
                })
                .catch(error => {
                    // Jika cache match gagal, log error
                    console.log('Cache match failed:', error);
                    // Return response error, browser akan handle
                    return new Response('', { status: 500, statusText: 'Cache Error' });
                })
        );
        return;
    }

    // Untuk request lainnya, gunakan network first
    event.respondWith(
        fetch(request)
            .then(response => {
                return response;
            })
            .catch(() => {
                return caches.match(request)
                    .then(cachedResponse => {
                        return cachedResponse || new Response('Offline', { status: 503 });
                    });
            })
    );
})

// Update a service worker dan cleanup cache lama
const cacheWhitelist = [CACHE_NAME];
self.addEventListener('activate', event => {
    event.waitUntil(
        caches.keys().then(cacheNames => {
            return Promise.all(
                cacheNames.map(cacheName => {
                    // Hapus cache lama yang tidak ada di whitelist
                    if (cacheWhitelist.indexOf(cacheName) === -1) {
                        console.log('Deleting old cache:', cacheName);
                        return caches.delete(cacheName);
                    }
                })
            );
        })
            .then(() => {
                // Claim clients agar service worker langsung aktif
                return self.clients.claim();
            })
            .catch(error => {
                console.error('Error during activate:', error);
            })
    );
});
