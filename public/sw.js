const CACHE_NAME = "licoreria-v1";

self.addEventListener("install", (event) => {
    self.skipWaiting();
});

self.addEventListener("activate", (event) => {
    event.waitUntil(self.clients.claim());
});

self.addEventListener("fetch", (event) => {
    const request = event.request;

    if (request.method !== "GET") return;

    const url = request.url;

    if (
        url.includes("/logear") ||
        url.includes("/logout") ||
        url.includes("/login") ||
        url.includes("/")
    ) {
        return;
    }

    event.respondWith(
        caches.match(request).then((cached) => {
            return cached || fetch(request);
        }),
    );
});
