// 1️⃣ REGISTRO ÚNICO Y SEGURO DEL SERVICE WORKER
if ("serviceWorker" in navigator) {
    window.addEventListener("load", function () {
        // Registro plano sin parámetros para evitar duplicados en el mismo dominio
        navigator.serviceWorker
            .register("/sw.js")
            .then(function (registration) {
                console.log(
                    "✅ SW único y activo en el alcance:",
                    registration.scope,
                );
            })
            .catch(function (error) {
                console.error("🚨 Error al registrar el SW:", error);
            });
    });
}

// 2️⃣ FUNCIÓN PARA DESPERTAR AL SERVICE WORKER (COMUNICACIÓN INTERNA)
function notificarSincronizacionAlSW() {
    if (navigator.onLine && "serviceWorker" in navigator) {
        // .ready NO registra, solo toma el SW que ya está corriendo de forma segura
        navigator.serviceWorker.ready.then((registration) => {
            if (registration.active) {
                registration.active.postMessage({
                    tipo: "FORZAR_SINCRONIZACION",
                });
            }
        });
    }
}

// 3️⃣ MONITOREO DE RED Y CICLO DE CONTROL AUTOMÁTICO
// Escenario A: El usuario recupera internet en caliente
window.addEventListener("online", () => {
    console.log("🌐 Conexión recuperada en vivo.");
    notificarSincronizacionAlSW();
});

// Escenario B: Al cargar cualquier página nueva (incluso desde la caché offline)
window.addEventListener("DOMContentLoaded", () => {
    console.log("📄 Nueva página cargada.");
    notificarSincronizacionAlSW();

    // Reloj de control activo: Revisa la red cada 10 segundos
    setInterval(() => {
        if (navigator.onLine) {
            notificarSincronizacionAlSW();
        }
    }, 10000);
});

// 4️⃣ ESCUCHAR RESPUESTAS DEL SW PARA DISPARAR LOS TOASTS DE SNEAT
if ("serviceWorker" in navigator) {
    navigator.serviceWorker.addEventListener("message", (event) => {
        if (event.data.tipo === "VENTA_SINCRONIZADA") {
            if (typeof toastr !== "undefined") {
                toastr.success(
                    "¡Una venta offline se sincronizó con el servidor!",
                );
            } else {
                alert(
                    "🔄 ¡Una venta registrada en modo offline se acaba de sincronizar con éxito!",
                );
            }

            // Si el usuario está en el historial de ventas, refrescamos la tabla para mostrar la nueva venta
            if (window.location.pathname === "/ventas") {
                setTimeout(() => window.location.reload(), 1500);
            }
        }
    });
}
