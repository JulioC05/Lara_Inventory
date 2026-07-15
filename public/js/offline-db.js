// const DB_NAME = "LaEconomicaOfflineDB";
// const DB_VERSION = 1;
// const STORE_NAME = "ventas_pendientes";

// Función limpia para abrir la base de datos local de forma segura
function abrirDB() {
    return new Promise((resolve, reject) => {
        const request = indexedDB.open("LaEconomicaOfflineDB", 1);

        // La estructura de las tablas ahora la maneja el Service Worker de forma global,
        // pero dejamos esta validación como un respaldo secundario por seguridad.
        request.onupgradeneeded = (event) => {
            const db = event.target.result;
            if (!db.objectStoreNames.contains("ventas_pendientes")) {
                db.createObjectStore("ventas_pendientes", {
                    keyPath: "id",
                    autoIncrement: true,
                });
                console.log(
                    "📦 Tabla ventas_pendientes creada en upgradeneeded local.",
                );
            }
        };

        request.onsuccess = (event) => resolve(event.target.result);
        request.onerror = (event) => reject(event.target.error);
    });
}

// Guardar una venta en IndexedDB garantizando la conexión
async function guardarVentaOffline(datosVenta) {
    try {
        const db = await abrirDB();

        return new Promise((resolve, reject) => {
            // Verificación crítica: si la tabla no existe por un desajuste de caché, la crea en caliente
            if (!db.objectStoreNames.contains("ventas_pendientes")) {
                console.error(
                    `🚨 La tabla ${"ventas_pendientes"} no existe en la sesión actual.`,
                );
                reject(
                    new Error("Estructura de base de datos no inicializada."),
                );
                return;
            }

            const transaction = db.transaction(["ventas_pendientes"], "readwrite");
            const store = transaction.objectStore("ventas_pendientes");

            // Marca de tiempo de la transacción offline
            datosVenta.creado_offline_at = new Date().toISOString();

            const request = store.add(datosVenta);

            request.onsuccess = () => {
                console.log(
                    "✅ Venta guardada localmente en IndexedDB (Modo Offline).",
                );
                resolve(true);
            };
            request.onerror = (event) => reject(event.target.error);
        });
    } catch (error) {
        console.error(
            "🚨 Error crítico al abrir la conexión para guardar venta:",
            error,
        );
        throw error;
    }
}

// Aseguramos el alcance global en el navegador
window.abrirDB = abrirDB;
window.guardarVentaOffline = guardarVentaOffline;
