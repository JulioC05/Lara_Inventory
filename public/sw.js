const CACHE_NAME = "licoreria-v1";

const ASSETS_TO_CACHE = [
    "/",
    "/manifest.json",
    "/Sneat-Admin/assets/img/favicon/logo-imagen.ico",
    "https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap",
    "https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.css",
    "https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js",
    "https://unpkg.com/html5-qrcode",

    // CSS Local
    "/Sneat-Admin/assets/vendor/fonts/iconify-icons.css",
    "/Sneat-Admin/assets/vendor/css/core.css",
    "/Sneat-Admin/assets/css/demo.css",
    "/Sneat-Admin/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css",
    "/Sneat-Admin/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css",
    "/Sneat-Admin/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css",
    "/Sneat-Admin/assets/vendor/libs/apex-charts/apex-charts.css",

    // JS Local
    "/Sneat-Admin/assets/vendor/js/helpers.js",
    "/Sneat-Admin/assets/js/config.js",
    "/Sneat-Admin/assets/vendor/libs/jquery/jquery.js",
    "/Sneat-Admin/assets/vendor/libs/popper/popper.js",
    "/Sneat-Admin/assets/vendor/js/bootstrap.js",
    "/Sneat-Admin/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js",
    "/Sneat-Admin/assets/vendor/js/menu.js",

    // Datatables corregido según tu estructura exacta de carpetas
    "/Sneat-Admin/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js",
    "/Sneat-Admin/assets/js/tables-datatables-advanced.js",
    "/Sneat-Admin/assets/js/ventas-detalles.js",
    "/Sneat-Admin/assets/vendor/libs/apex-charts/apexcharts.js",
    "/Sneat-Admin/assets/js/main.js",
    "/Sneat-Admin/assets/js/dashboards-analytics.js",
    "/Sneat-Admin/assets/js/ui-toasts.js",
    "/Sneat-Admin/assets/js/delete-modal.js",
    "/Sneat-Admin/assets/js/edit-modal.js",
    "/Sneat-Admin/assets/js/usuarios/edit-modal.js",
    "/Sneat-Admin/assets/js/preventModalSubmit.js",
];

// --- INSTALACIÓN TOLERANTE A ERRORES (MÁGICA) ---
self.addEventListener("install", (event) => {
    self.skipWaiting();

    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => {
            console.log("PWA: Iniciando cacheo de archivos...");

            // En lugar de usar addAll, mapeamos cada archivo de forma independiente
            const cachePromises = ASSETS_TO_CACHE.map((asset) => {
                return cache.add(asset).catch((error) => {
                    // Si un archivo específico da 404 o falla, lo reporta pero NO rompe la PWA
                    console.warn(
                        `PWA: No se pudo cachear el archivo: ${asset}`,
                        error,
                    );
                });
            });

            return Promise.all(cachePromises);
        }),
    );
});

// 2. ACTIVACIÓN: Limpiar cachés antiguas si cambias de versión en el futuro
self.addEventListener("activate", (event) => {
    event.waitUntil(
        caches
            .keys()
            .then((cacheNames) => {
                return Promise.all(
                    cacheNames.map((cache) => {
                        if (cache !== CACHE_NAME) {
                            console.log("PWA: Borrando caché antigua", cache);
                            return caches.delete(cache);
                        }
                    }),
                );
            })
            .then(() => self.clients.claim()),
    );
});

// 3. INTERCEPCIÓN DE PETICIONES (Estrategia Inteligente)
self.addEventListener("fetch", (event) => {
    const request = event.request;

    // Ignorar métodos que no sean GET (POST de ventas/compras los manejaremos después)
    if (request.method !== "GET") return;

    if (
        !request.url.startsWith("http://") &&
        !request.url.startsWith("https://")
    ) {
        return;
    }

    const url = new URL(request.url);

    // EXCLUSIONES CRÍTICAS: Autenticación, blobs de DataTables y la ventana de impresión
    if (
        url.pathname.includes("/login") ||
        url.pathname.includes("/logear") ||
        url.pathname.includes("/logout") ||
        request.url.startsWith("blob:") ||
        request.url.startsWith("data:") ||
        request.url.includes("about:blank")
    ) {
        return; // Bypass directo a internet sin intervenir
    }

    // --- ESTRATEGIA PARA ASSETS ESTÁTICOS (CSS, JS, Imágenes, Fuentes) ---
    // Si la petición es un asset estático, aplicamos CACHE-FIRST (Vuela en velocidad)
    if (
        request.destination === "style" ||
        request.destination === "script" ||
        request.destination === "image" ||
        request.destination === "font" ||
        url.pathname.includes("/assets/") // Ajusta si tu carpeta de Sneat se llama diferente
    ) {
        event.respondWith(
            caches.match(request).then((cachedResponse) => {
                if (cachedResponse) {
                    return cachedResponse; // Devolver del disco duro al instante
                }
                // Si no estaba en caché, lo busca en internet y lo guarda para la próxima
                return fetch(request).then((networkResponse) => {
                    if (networkResponse.status === 200) {
                        const cacheCopy = networkResponse.clone();
                        caches
                            .open(CACHE_NAME)
                            .then((cache) => cache.put(request, cacheCopy));
                    }
                    return networkResponse;
                });
            }),
        );
        return;
    }

    // --- ESTRATEGIA PARA RUTAS DINÁMICAS DE LARAVEL (Dashboard, Ventas, Stock, etc.) ---
    // Aplicamos NETWORK-FIRST (Primero va a internet para ver la data real; si falla, usa la caché)
    event.respondWith(
        fetch(request)
            .then((networkResponse) => {
                // Si la petición fue exitosa, guardamos una copia de la pantalla en la caché de emergencia
                if (networkResponse.status === 200) {
                    const cacheCopy = networkResponse.clone();
                    caches
                        .open(CACHE_NAME)
                        .then((cache) => cache.put(request, cacheCopy));
                }
                return networkResponse;
            })
            .catch(() => {
                // ¡AQUÍ SUCEDE LA MAGIA OFFLINE!
                // Si el fetch falló (no hay internet), busca la última copia guardada de esa ruta
                return caches.match(request).then((cachedResponse) => {
                    if (cachedResponse) {
                        return cachedResponse;
                    }
                    // Opcional: Podrías retornar una vista genérica de "views/errors/offline.blade.php"
                    return new Response(
                        "<h1>Sin conexión a Internet</h1><p>Esta sección requiere conexión para mostrar datos nuevos.</p>",
                        {
                            headers: {
                                "Content-Type": "text/html; charset=utf-8",
                            },
                        },
                    );
                });
            }),
    );
});

// ESCUCHAR EL EVENTO PUSH (Cuando llega la notificación desde Laravel)
self.addEventListener("push", (event) => {
    if (!event.data) return;

    // Parsear la información enviada por Laravel
    const payload = event.data.json();
    // console.log("Payload recibido desde Laravel:", payload);

    const destinoViaje =
        payload.data && payload.data.url ? payload.data.url : "/";

    const opciones = {
        body: payload.body || "Tienes una nueva actualización de la licorería.",
        icon: payload.icon || "Sneat-Admin/assets/img/favicon/logo.ico",
        badge: payload.badge || "Sneat-Admin/assets/img/favicon/logo.ico",
        data: {
            url: destinoViaje, // Guardamos la URL en la notificación física
        },
        vibrate: [100, 50, 100], // Vibración en Android [vibrar, pausa, vibrar]
        actions: [{ action: "open_url", title: "Revisar Ahora" }],
    };

    event.waitUntil(
        self.registration.showNotification(payload.title, opciones),
    );
});

self.addEventListener("notificationclick", (event) => {
    event.notification.close(); // Cierra la alerta visual inmediatamente

    // 2. Recuperamos la URL sin importar dónde se hizo clic
    const urlParaAbrir =
        event.notification.data && event.notification.data.url
            ? event.notification.data.url
            : "/";

    // 3. Ejecutamos el flujo de apertura/enfoque de ventana
    event.waitUntil(
        clients
            .matchAll({ type: "window", includeUncontrolled: true })
            .then((clientList) => {
                // Si la pestaña ya existe con esa URL exacta, la trae al frente
                for (const client of clientList) {
                    if (client.url === urlParaAbrir && "focus" in client) {
                        return client.focus();
                    }
                }
                // Si no está abierta, abre una nueva pestaña
                if (clients.openWindow) {
                    return clients.openWindow(urlParaAbrir);
                }
            }),
    );
});
