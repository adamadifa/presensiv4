// Installing service worker - TIDAK ADA CACHING
self.addEventListener("install", e => {
    // Langsung skip waiting, tidak ada caching sama sekali
    e.waitUntil(
        Promise.resolve().then(() => self.skipWaiting())
    );
});

// Fetch event - TIDAK INTERCEPT APAPUN, biarkan browser handle semua request
self.addEventListener('fetch', function (event) {
    // JANGAN INTERCEPT APAPUN - biarkan semua request langsung ke network
    // Ini memastikan tidak ada yang di-cache dan tidak mengganggu CDN/external resources

    // Skip semua request - biarkan browser handle langsung
    // Tidak ada caching sama sekali untuk:
    // - HTML pages (navigasi)
    // - API endpoints
    // - External CDN (jQuery, SweetAlert2, Ionicons, AmCharts, dll)
    // - Static assets (JS, CSS, images)
    // - Request lainnya

    return; // Langsung return tanpa intercept apapun
})

// Update a service worker - HAPUS SEMUA CACHE LAMA
self.addEventListener('activate', event => {
    event.waitUntil(
        caches.keys().then(cacheNames => {
            // Hapus SEMUA cache yang ada (termasuk cache lama)
            return Promise.all(
                cacheNames.map(cacheName => {
                    console.log('Deleting cache:', cacheName);
                    return caches.delete(cacheName);
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
