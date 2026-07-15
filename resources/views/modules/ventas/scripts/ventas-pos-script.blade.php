<script>
 


    const productoTom = new TomSelect('#productoSelect', {
        create: false,
        maxOptions: 1000,
        placeholder: 'Buscar producto...',
        searchField: ['text', 'codigo'],
        openOnFocus: true,
        valueField: 'value',
        labelField: 'text',
        render: {
            option: function(data, escape) {
                const option = document.querySelector(`option[value="${data.value}"]`);
                const imagen = option.dataset.imagen;
                const precio = option.dataset.precio;
                const codigo = option.dataset.codigo;
                return `
                <div class="producto-option">
                    <img src="${imagen}" class="producto-option-img">
                    <div class="producto-option-info">
                        <div class="producto-option-title">${escape(data.text)}</div>
                        <div class="producto-option-price">P. venta S/ : ${precio}</div>
                    </div>
                </div>`;
            }
        },
        onChange: function() {
            agregarProducto();
        }
    });

    window.carrito = [];
    const IGV_PERCENT = 0.18;
    const SUNAT_LIMITE_BOLETA = 700.00; // 🇵🇪 Límite legal SUNAT para identificación

    const carritoBody = document.getElementById('carritoBody');
    const productosContainer = document.getElementById('productosContainer');

    // Captura de elementos select de la barra lateral
    const selectComprobante = document.querySelector('select[name="tipo_comprobante"]');
    const selectCliente = document.querySelector('select[name="cliente_id"]');
    const formVenta = document.getElementById('saleForm');

    function agregarProducto(productoId = null) {
        const select = document.getElementById('productoSelect');
        productoId = productoId || select.value;

        if (!productoId) return;

        const option = select.querySelector(`option[value="${productoId}"]`);
        const nombre = option.dataset.nombre;
        const precio = parseFloat(option.dataset.precio);
        const stock = parseInt(option.dataset.stock);
        const cantidad = 1;

        const existente = window.carrito.find(item => item.id == productoId);

        if (existente) {
            if ((existente.cantidad + 1) > stock) {
                alert('Stock insuficiente');
                return;
            }
            existente.cantidad++;
        } else {
            window.carrito.push({
                id: productoId,
                nombre: nombre,
                precio: precio,
                cantidad: cantidad,
            });
        }

        renderCarrito();
        productoTom.clear();
    }

    function renderCarrito() {
        carritoBody.innerHTML = '';
        productosContainer.innerHTML = '';
        let subtotalGeneral = 0;

        window.carrito.forEach((producto, index) => {
            const subtotal = producto.precio * producto.cantidad;
            subtotalGeneral += subtotal;

            carritoBody.innerHTML += `
                <tr style="border-style: hidden;">
                    <td>${producto.nombre}</td>
                    <td>
                        <input type="number" min="1" value="${producto.cantidad}" class="form-control" onchange="actualizarCantidad(${index}, this.value)">
                    </td>
                    <td>S/ ${producto.precio.toFixed(2)}</td>
                    <td>S/ ${subtotal.toFixed(2)}</td>
                    <td>
                        <button type="button" class="btn btn-sm btn-danger" onclick="eliminarProducto(${index})">X</button>
                    </td>
                </tr>`;

            productosContainer.innerHTML += `
                <input type="hidden" name="productos[${index}][id]" value="${producto.id}">
                <input type="hidden" name="productos[${index}][cantidad]" value="${producto.cantidad}">
                <input type="hidden" name="productos[${index}][precio_unitario]" value="${producto.precio}">`;
        });

        const total = subtotalGeneral;
        const subtotalSinIgv = total / 1.18;
        const igv = total - subtotalSinIgv;

        document.getElementById('subtotalText').innerText = `S/ ${subtotalSinIgv.toFixed(2)}`;
        document.getElementById('igvText').innerText = `S/ ${igv.toFixed(2)}`;
        document.getElementById('totalText').innerText = `S/ ${total.toFixed(2)}`;

        document.getElementById('subtotalInput').value = subtotalSinIgv.toFixed(2);
        document.getElementById('igvInput').value = igv.toFixed(2);
        document.getElementById('totalInput').value = total.toFixed(2);

        // 🔥 Validar reglas SUNAT tras actualizar los montos del carrito
        validarReglasSunat(total);
    }

    function actualizarCantidad(index, cantidad) {
        window.carrito[index].cantidad = parseInt(cantidad) || 1;
        renderCarrito();
    }

    function eliminarProducto(index) {
        window.carrito.splice(index, 1);
        renderCarrito();
    }

    function limpiarFormularioVenta() {
        window.carrito = [];
        renderCarrito();
        if (formVenta) formVenta.reset();
        if (productoTom) productoTom.clear();
    }

    /*
    |--------------------------------------------------------------------------
    | LÓGICA DE CONTROL SUNAT EN TIEMPO REAL
    |--------------------------------------------------------------------------
    |*/
    function validarReglasSunat(totalActual = null) {
        if (totalActual === null) {
            totalActual = parseFloat(document.getElementById('totalInput').value) || 0;
        }

        const tipoComprobante = selectComprobante.value;
        const clienteSeleccionado = selectCliente.value;

        const optionCliente = selectCliente.options[selectCliente.selectedIndex];
        const tipoDocumentoCliente = optionCliente ? optionCliente.dataset.documento : '';

        selectCliente.classList.remove('is-invalid', 'border-danger');

        if (tipoComprobante === 'boleta' && totalActual >= SUNAT_LIMITE_BOLETA) {
            if (clienteSeleccionado == "1" || !clienteSeleccionado || tipoDocumentoCliente !== 'DNI') {
                selectCliente.classList.add('is-invalid', 'border-danger');
            }
        }

        if (tipoComprobante === 'factura') {
            if (clienteSeleccionado == "1" || !clienteSeleccionado || tipoDocumentoCliente !== 'RUC') {
                selectCliente.classList.add('is-invalid', 'border-danger');
            }
        }
    }

    if (selectComprobante) {
        selectComprobante.addEventListener('change', () => validarReglasSunat());
    }
    if (selectCliente) {
        selectCliente.addEventListener('change', () => validarReglasSunat());
    }


    document.addEventListener('DOMContentLoaded', () => {
        const saleForm = document.getElementById('saleForm');

        if (saleForm) {
            // Agregamos 'async' para poder usar 'await' dentro del evento
            saleForm.addEventListener('submit', async function(event) {
                // 1. 🔥 REGLA DE ORO: Detiene la recarga inmediatamente antes de validar nada
                event.preventDefault();
                event.stopPropagation();

                console.log("🔍 Verificando conexión real a internet...");

                // Extraemos los valores del formulario usando los name/id estructurados
                const selectComprobante = document.querySelector(
                    'select[name="tipo_comprobante"]') || document.getElementById(
                    'tipoComprobante');
                const selectCliente = document.querySelector('select[name="cliente_id"]') ||
                    document.getElementById('cliente');
                const totalInput = document.getElementById('totalInput');

                const tipoComprobante = selectComprobante ? selectComprobante.value : "";
                const clienteSeleccionado = selectCliente ? selectCliente.value : "";
                const totalActual = totalInput ? parseFloat(totalInput.value) : 0;

                // --- VALIDACIÓN INTELIGENTE DE TIPO DE DOCUMENTO (SUNAT) ---
                let tipoDocumentoCliente = '';
                if (selectCliente && selectCliente.selectedIndex !== -1) {
                    const optionSeleccionada = selectCliente.options[selectCliente.selectedIndex];
                    // Intentamos leer el dataset; si TomSelect lo borró, leemos el texto visible de la opción
                    tipoDocumentoCliente = optionSeleccionada.dataset.documento || "";

                    const textoOption = optionSeleccionada.text.toUpperCase();
                    if (!tipoDocumentoCliente) {
                        if (textoOption.includes('RUC') || optionSeleccionada.value.length === 11) {
                            tipoDocumentoCliente = 'RUC';
                        } else if (textoOption.includes('DNI') || optionSeleccionada.value
                            .length === 8) {
                            tipoDocumentoCliente = 'DNI';
                        }
                    }
                } else {
                    // Fallback de fuerza bruta si el nodo no responde por la caché
                    tipoDocumentoCliente = (clienteSeleccionado.length === 11) ? 'RUC' : 'DNI';
                }

                // --- 1. VALIDACIONES REGLAS SUNAT (FIJADAS) ---
                if (tipoComprobante === 'boleta' && totalActual >= SUNAT_LIMITE_BOLETA) {
                    if (clienteSeleccionado == "1" || !clienteSeleccionado ||
                        tipoDocumentoCliente !== 'DNI') {
                        alert(
                            `🚨 Regla SUNAT: Para boletas con montos mayores o iguales a S/ ${SUNAT_LIMITE_BOLETA} es obligatorio identificar al cliente con su DNI/CE.`
                        );
                        return;
                    }
                }

                // 🔥 CORREGIDO: Ahora valida correctamente si es RUC para clientes jurídicos
                if (tipoComprobante === 'factura' && (clienteSeleccionado == "1" ||
                        tipoDocumentoCliente !== 'RUC')) {
                    alert(
                        '🚨 Regla SUNAT: Las Facturas solo pueden ser emitidas a clientes registrados con un número de RUC válido de 11 dígitos.'
                    );
                    return;
                }

                // Validación de carrito vacío
                const listaProductos = window.carrito || [];
                if (listaProductos.length === 0) {
                    alert('🚨 Error: No puedes registrar una venta con el carrito vacío.');
                    return;
                }

                // Creamos una bandera de control de red por defecto
                let estaOnline = navigator.onLine;

                // --- TRUCO DEFINITIVO PARA PWA ---
                if (estaOnline) {
                    try {
                        // Intentamos descargar un elemento mínimo añadiendo un timestamp para evitar el caché
                        await fetch('/favicon.ico', {
                            method: 'HEAD',
                            cache: 'no-store'
                        });
                        estaOnline = true; // El servidor respondió, hay internet real
                        console.log("En Linea");
                    } catch (error) {
                        estaOnline =
                            false; // La petición falló: estamos realmente offline en la PWA
                    }
                }

                // 2. Ahora evaluamos la conexión real comprobada
                if (estaOnline) {
                    console.log(
                        "🟢 Conexión confirmada. Enviando venta de forma directa al servidor..."
                    );

                    // Desactivamos temporalmente el listener para enviar el formulario nativamente a Laravel sin bucles
                    HTMLFormElement.prototype.submit.call(this);
                } else {
                    console.log(
                        "🔴 Modo offline real detectado por fallo de red. Guardando en IndexedDB..."
                    );

                    // Estructuramos el JSON limpio con tu arquitectura de datos offline
                    const datosVentaOffline = {
                        cliente_id: clienteSeleccionado,
                        tipo_comprobante: tipoComprobante,
                        metodo_pago_id: document.querySelector('select[name="metodo_pago_id"]')
                            ?.value || "1",
                        subtotal: document.getElementById('subtotalInput')?.value || "0.00",
                        igv: document.getElementById('igvInput')?.value || "0.00",
                        total: totalInput ? totalInput.value : "0.00",
                        productos: listaProductos.map(item => ({
                            id: item.id,
                            cantidad: item.cantidad,
                            precio_unitario: item.precio
                        }))
                    };

                    // Guardado oficial en tu base de datos de IndexedDB (LaEconomicaOfflineDB)
                    if (typeof guardarVentaOffline === 'function') {
                        try {
                            await guardarVentaOffline(datosVentaOffline);

                            // Registramos la tarea de sincronización de fondo si el Service Worker está listo
                            if ('serviceWorker' in navigator && 'SyncManager' in window) {
                                const registration = await navigator.serviceWorker.ready;
                                await registration.sync.register('sincronizar-ventas');
                                console.log('🔄 Sincronización registrada en el Service Worker.');
                            }

                            alert(
                                "📦 ¡Venta guardada localmente de forma segura en modo Offline! Se procesará automáticamente cuando vuelva el internet."
                            );

                            if (typeof limpiarFormularioVenta === 'function') {
                                limpiarFormularioVenta();
                            }
                        } catch (errDB) {
                            console.error("🚨 Error al insertar en IndexedDB:", errDB);
                            alert("🚨 No se pudo resguardar la venta en la base de datos local.");
                        }
                    } else {
                        alert(
                            '🚨 Error crítico: El archivo de base de datos offline (offline-db.js) no se cargó correctamente.'
                        );
                    }
                }
            });
        }
    });

    window.actualizarCantidad = actualizarCantidad;
    window.eliminarProducto = eliminarProducto;
    window.limpiarFormularioVenta = limpiarFormularioVenta;
</script>
