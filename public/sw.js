const CACHE_NAME = "licoreria-v1";

const ASSETS_TO_CACHE = [
    "/",
    "/manifest.json",
    "/Sneat-Admin/assets/img/favicon/logo-imagen.ico",
    "/ventas", // 🌐 Ruta del listado de ventas en Laravel
    "/ventas/create", // 🌐 Aseguramos compatibilidad si entras con o sin slash al final
    "/ventas/create/",
    "/js/offline-db.js",
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

    // Datatables estructurados
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

// 🔥 FUNCIÓN CONSTRUCTORA GLOBAL PARA EVITAR ERRORES DE TABLA NO ENCONTRADA
function inicializarEstructuraDB() {
    return new Promise((resolve, reject) => {
        const request = indexedDB.open("LaEconomicaOfflineDB", 1);

        request.onupgradeneeded = (event) => {
            const db = event.target.result;
            if (!db.objectStoreNames.contains("ventas_pendientes")) {
                db.createObjectStore("ventas_pendientes", {
                    keyPath: "id",
                    autoIncrement: true,
                });
                console.log(
                    "📦 PWA: Base de datos e IndexedDB creadas con éxito desde el Service Worker.",
                );
            }
        };

        request.onsuccess = (e) => {
            e.target.result.close(); // Cerramos la conexión inicial limpia
            resolve();
        };
        request.onerror = (e) => reject(e.target.error);
    });
}

// 1. INSTALACIÓN: Tolerante a errores individuales de archivos
self.addEventListener("install", (event) => {
    self.skipWaiting();

    event.waitUntil(
        Promise.all([
            // 1. Forzamos la creación inmediata de la base de datos local
            inicializarEstructuraDB(),

            // 2. Tu código existente para cachear los archivos de Sneat Admin
            caches.open(CACHE_NAME).then((cache) => {
                console.log("PWA: Iniciando cacheo de archivos...");
                const cachePromises = ASSETS_TO_CACHE.map((asset) => {
                    return cache.add(asset).catch((error) => {
                        console.warn(
                            `PWA: No se pudo cachear el archivo: ${asset}`,
                            error,
                        );
                    });
                });
                return Promise.all(cachePromises);
            }),
        ]),
    );
});

// 2. ACTIVACIÓN: Reclama las pestañas abiertas y limpia versiones viejas
self.addEventListener("activate", (event) => {
    event.waitUntil(
        caches
            .keys()
            .then((cacheNames) => {
                return Promise.all(
                    cacheNames.map((cache) => {
                        if (cache !== CACHE_NAME) {
                            console.log(
                                "PWA: Eliminando caché obsoleta",
                                cache,
                            );
                            return caches.delete(cache);
                        }
                    }),
                );
            })
            .then(() => self.clients.claim()),
    );
});

// 3. INTERCEPCIÓN DE PETICIONES (Estrategias de Red)
self.addEventListener("fetch", (event) => {
    const request = event.request;

    // Solo intervenimos en peticiones HTTP/HTTPS de tipo GET
    if (request.method !== "GET") return;
    if (
        !request.url.startsWith("http://") &&
        !request.url.startsWith("https://")
    )
        return;

    const url = new URL(request.url);

    // Bypass inmediato a internet para procesos sensibles que nunca deben cachearse
    if (
        url.pathname.includes("/login") ||
        url.pathname.includes("/logear") ||
        url.pathname.includes("/logout") ||
        request.url.startsWith("blob:") ||
        request.url.startsWith("data:") ||
        request.url.includes("about:blank")
    ) {
        return;
    }

    // --- ESTRATEGIA A: ASSETS ESTÁTICOS (Cache-First) ---
    if (
        request.destination === "style" ||
        request.destination === "script" ||
        request.destination === "image" ||
        request.destination === "font" ||
        url.pathname.includes("/assets/")
    ) {
        event.respondWith(
            caches.match(request).then((cachedResponse) => {
                if (cachedResponse) return cachedResponse;

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

    // --- ESTRATEGIA B: VISTAS DINÁMICAS LARAVEL (Network-First con caída a Caché) ---
    event.respondWith(
        fetch(request)
            .then((networkResponse) => {
                // Si hay internet, guardamos una copia de lo que cargó en el caché
                if (networkResponse.status === 200) {
                    const cacheCopy = networkResponse.clone();
                    caches
                        .open(CACHE_NAME)
                        .then((cache) => cache.put(request, cacheCopy));
                }
                return networkResponse;
            })
            .catch(() => {
                // 🔥 SI NO HAY INTERNET:

                // Caso 1: El usuario está navegando entre páginas (módulos)
                if (request.mode === "navigate") {
                    return caches.match(request).then((cachedResponse) => {
                        // 1. Si la página ya fue visitada antes, se entrega la copia exacta en caché
                        if (cachedResponse) return cachedResponse;

                        // 2. Si el módulo no está cacheado, entregamos la pantalla de aviso seguro
                        return new Response(
                            `<!DOCTYPE html>
                            <html lang="es">
                            <head>
                                <meta charset="UTF-8">
                                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                                <title>Sección No Disponible - Modo Offline</title>
                                <link rel="stylesheet" href="/Sneat-Admin/assets/vendor/css/core.css">
                                <link rel="stylesheet" href="/Sneat-Admin/assets/css/demo.css">
                            </head>
                            <body class="bg-light">
                                <div class="container-xxl container-p-y d-flex align-items-center justify-content-center" style="min-height: 80vh;">
                                    <div class="text-center">
                                        <h1 class="mb-2 mx-2 fw-bold" style="font-size: 3rem;">⚠️ Sección No Disponible</h1>
                                        <p class="mb-4 mx-2 text-muted">Estás intentando ingresar a un módulo que requiere conexión a Internet y no ha sido guardado localmente.</p>
                                        <p class="fw-semibold text-warning">Por favor, conéctate a una red para sincronizar y habilitar este módulo.</p>
                                        <!-- Botón corregido con redirección universal al Dashboard para resguardar roles -->
                                        <a href="/dashboard" class="btn btn-primary mt-3">Regresar al Dashboard</a>
                                    </div>
                                </div>
                            </body>
                            </html>`,
                            {
                                headers: {
                                    "Content-Type": "text/html; charset=utf-8",
                                },
                            },
                        );
                    });
                }

                // Caso 2: Para cualquier otro recurso dinámico que no sea navegación de páginas (APIs, AJAX, etc.)
                return caches.match(request).then((cachedResponse) => {
                    if (cachedResponse) return cachedResponse;

                    return new Response(
                        JSON.stringify({
                            success: false,
                            message: "Modo Offline Activo en el Servidor",
                        }),
                        {
                            headers: {
                                "Content-Type":
                                    "application/json; charset=utf-8",
                            },
                        },
                    );
                });
            }),
    );
});

// 4. NOTIFICACIONES PUSH (Escucha desde Laravel)
self.addEventListener("push", (event) => {
    if (!event.data) return;

    const payload = event.data.json();
    const destinoViaje =
        payload.data && payload.data.url ? payload.data.url : "/";

    const opciones = {
        body: payload.body || "Tienes una nueva actualización de la licorería.",
        icon: payload.icon || "Sneat-Admin/assets/img/favicon/logo.ico",
        badge: payload.badge || "Sneat-Admin/assets/img/favicon/logo.ico",
        data: { url: destinoViaje },
        vibrate: [100, 50, 100],
        actions: [{ action: "open_url", title: "Revisar Ahora" }],
    };

    event.waitUntil(
        self.registration.showNotification(payload.title, opciones),
    );
});

self.addEventListener("notificationclick", (event) => {
    event.notification.close();
    const urlParaAbrir =
        event.notification.data && event.notification.data.url
            ? event.notification.data.url
            : "/";

    event.waitUntil(
        clients
            .matchAll({ type: "window", includeUncontrolled: true })
            .then((clientList) => {
                for (const client of clientList) {
                    if (client.url === urlParaAbrir && "focus" in client) {
                        return client.focus();
                    }
                }
                if (clients.openWindow) return clients.openWindow(urlParaAbrir);
            }),
    );
});

// // 5. 🔥 BACKGROUND SYNC: Sincronización Automática al Volver Internet
// self.addEventListener("sync", (event) => {
//     if (event.tag === "sincronizar-ventas") {
//         console.log("🔄 Red recuperada. Sincronizando ventas pendientes...");
//         event.waitUntil(enviarVentasPendientesAServidor());
//     }
// });

// --- SINCRONIZACIÓN GLOBAL AUTOMÁTICA EN SEGUNDO PLANO ---

function abrirDB() {
    return new Promise((resolve, reject) => {
        const request = indexedDB.open("LaEconomicaOfflineDB", 1);

        request.onsuccess = (e) => resolve(e.target.result);
        request.onerror = (e) => reject(e.target.error);
    });
}

// 🔥 VARIABLE DE CONTROL CRÍTICA: Evita que se ejecuten dos sincronizaciones en paralelo
let yaEstaSincronizando = false;

async function sincronizarVentasOffline() {
    // Si ya hay un proceso activo, rebotamos las peticiones duplicadas de inmediato
    if (yaEstaSincronizando) {
        console.log("⏳ SW: Sincronización en curso. Bloqueando petición duplicada.");
        return;
    }

    try {
        const db = await abrirDB();
        if (!db.objectStoreNames.contains('ventas_pendientes')) return;

        // Pasamos la bandera a true para bloquear el acceso al bucle
        yaEstaSincronizando = true;

        const transaction = db.transaction(['ventas_pendientes'], 'readonly');
        const store = transaction.objectStore('ventas_pendientes');
        
        const ventas = await new Promise((resolve) => {
            const req = store.getAll();
            req.onsuccess = () => resolve(req.result);
        });

        if (!ventas || ventas.length === 0) {
            yaEstaSincronizando = false; // Liberamos si está vacío
            return;
        }

        console.log(`🔄 SW: Procesando cola de ${ventas.length} ventas de forma segura...`);

        for (const venta of ventas) {
            try {
                const response = await fetch("/api/ventas/offline", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json",
                    },
                    body: JSON.stringify(venta),
                });

                const resultado = await response.json();

                if (response.ok && resultado.success === true) {
                    // 🔥 MEJORA CRÍTICA: Usamos una promesa con await para GARANTIZAR 
                    // que el registro se borre de IndexedDB antes de continuar con cualquier otra cosa
                    await new Promise((resolveBorrado, rejectBorrado) => {
                        const borrarTx = db.transaction(['ventas_pendientes'], 'readwrite');
                        const borrarStore = borrarTx.objectStore('ventas_pendientes');
                        const requestBorrar = borrarStore.delete(venta.id);
                        
                        requestBorrar.onsuccess = () => resolveBorrado();
                        requestBorrar.onerror = () => rejectBorrado();
                    });

                    console.log(`✅ SW: Venta ID ${venta.id} borrada de IndexedDB con éxito.`);
                    comunicarPestañas('VENTA_SINCRONIZADA');
                }
            } catch (error) {
                console.error("🚨 SW: Falló el envío de la venta:", error);
            }
        }
    } catch (err) {
        console.error("🚨 SW: Error en IndexedDB:", err);
    } finally {
        // 🔥 Al terminar todo el proceso (exitoso o con error), liberamos la bandera
        yaEstaSincronizando = false;
        // console.log("🔓 SW: Proceso de sincronización finalizado. Candado liberado.");
    }
}
// Función auxiliar para enviar mensajes a las pestañas activas (para los Toasts)
function comunicarPestañas(mensaje) {
    self.clients.matchAll().then((clients) => {
        clients.forEach((client) => client.postMessage({ tipo: mensaje }));
    });
}

// Escuchar evento de sincronización oficial del navegador (Background Sync)
self.addEventListener("sync", (event) => {
    if (event.tag === "sincronizar-ventas") {
        event.waitUntil(sincronizarVentasOffline());
    }
});

// Escuchar las órdenes enviadas desde el main.js de la página actual
self.addEventListener("message", (event) => {
    if (event.data.tipo === "FORZAR_SINCRONIZACION") {
        // console.log("⚡ SW: Pulso recibido. Ejecutando verificación de la base de datos...");

        // Ejecutamos la sincronización directamente de forma interna
        if (typeof sincronizarVentasOffline === "function") {
            sincronizarVentasOffline();
        }
    }
});

// // Lógica de vaciado de IndexedDB hacia la API de Laravel
// async function enviarVentasPendientesAServidor() {
//     const dbRequest = indexedDB.open("LaEconomicaOfflineDB", 1);

//     dbRequest.onsuccess = async (event) => {
//         const db = event.target.result;

//         // Verificamos que exista el almacén para evitar excepciones colaterales
//         if (!db.objectStoreNames.contains("ventas_pendientes")) return;

//         const transaction = db.transaction(["ventas_pendientes"], "readonly");
//         const store = transaction.objectStore("ventas_pendientes");
//         const getAllRequest = store.getAll();

//         getAllRequest.onsuccess = async () => {
//             const ventas = getAllRequest.result;
//             if (ventas.length === 0) return;

//             for (const venta of ventas) {
//                 try {
//                     // Envío directo al endpoint público que configuramos en api.php
//                     const response = await fetch("/api/ventas/offline", {
//                         method: "POST",
//                         headers: {
//                             "Content-Type": "application/json",
//                             Accept: "application/json",
//                         },
//                         body: JSON.stringify(venta),
//                     });

//                     const resultado = await response.json();

//                     if (response.ok && resultado.success) {
//                         // Eliminamos el registro de IndexedDB únicamente tras la confirmación de Laravel
//                         const borrarTx = db.transaction(
//                             ["ventas_pendientes"],
//                             "readwrite",
//                         );
//                         borrarTx
//                             .objectStore("ventas_pendientes")
//                             .delete(venta.id);
//                         console.log(
//                             `✅ Venta offline ID: ${venta.id} guardada en MySQL y limpia de la PWA.`,
//                         );
//                     }
//                 } catch (error) {
//                     console.error(
//                         "Fallo el envío en segundo plano para la venta ID:",
//                         venta.id,
//                         error,
//                     );
//                 }
//             }
//         };
//     };
// }
