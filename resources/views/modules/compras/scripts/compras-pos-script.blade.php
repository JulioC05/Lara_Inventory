<script>
    // =========================================================================
    // 1. INICIALIZAR TOMSELECT (MÉTODO ULTRA SEGURO DE MEMORIA INTERNA)
    // =========================================================================
    const productoTom = new TomSelect('#productoSelect', {
        create: false,
        maxOptions: 1000,
        placeholder: 'Buscar producto para comprar...',
        searchField: ['text', 'codigo'],
        openOnFocus: true,
        valueField: 'value',
        labelField: 'text',
        dataAttr: 'data-*', // Absorbe todos los data-* del HTML una sola vez

        render: {
            option: function(data, escape) {
                // Si la imagen viene como string "undefined" o vacía, ponemos la de respaldo
                const imagen = (data.imagen && data.imagen !== 'undefined') ? data.imagen :
                    '/images/no-image.png';
                const precioCompra = data.precio || '0.00';

                return `
                <div class="producto-option">
                    <img src="${imagen}" class="producto-option-img" onerror="this.src='/images/no-image.png'">
                    <div class="producto-option-info">
                        <div class="producto-option-title">${escape(data.text)}</div>
                        <div class="producto-option-price">Último Costo S/: ${precioCompra}</div>
                    </div>
                </div>`;
            }
        },
        onChange: function(value) {
            // FLUJO AUTOMÁTICO: En cuanto cambia el valor (clic, enter o escáner), se agrega al "carrito"
            if (value) {
                agregarProducto(value);
            }
        }
    });

    let carrito = [];
    const carritoBody = document.getElementById('tbody-detalles');
    const productosContainer = document.getElementById('productosContainer');
    const btnGuardarCompra = document.getElementById('btn-guardar-compra');

    // =========================================================================
    // 2. AGREGAR PRODUCTO AUTOMÁTICAMENTE
    // =========================================================================
    function agregarProducto(productoId) {
        if (!productoId) return;

        // SEGURIDAD: Jalamos el producto directamente de la memoria interna de TomSelect
        const itemData = productoTom.options[productoId];
        if (!itemData) return;

        const nombre = itemData.text;
        // Jalamos los atributos data-* mapeados por TomSelect (nota cómo se vuelven minúsculas)
        const precioCosto = parseFloat(itemData.precio) || 0.00;
        const codigoBarras = itemData.codigo || '';

        // Validar si el artículo ya está en la lista de compra para incrementar cantidad
        const existente = carrito.find(item => item.id == productoId);

        if (existente) {
            existente.cantidad++;
        } else {
            carrito.push({
                id: productoId,
                nombre: nombre,
                precio: precioCosto, // Permite que empiece con el último costo registrado
                cantidad: 1,
            });
        }

        renderCarrito();

        // LIMPIAR SELECT AUTOMÁTICAMENTE: 
        // Usamos un pequeño delay (setTimeout) para que TomSelect complete su ciclo interno 
        // de cambio antes de forzar la limpieza, evitando que se quede congelado.
        setTimeout(() => {
            productoTom.clear(true); // El argumento true evita disparar el onChange infinitamente
        }, 50);
    }

    // =========================================================================
    // 3. RENDERIZAR TABLA Y TOTALES
    // =========================================================================
    function renderCarrito() {
        carritoBody.innerHTML = '';
        if (productosContainer) productosContainer.innerHTML = '';

        if (carrito.length === 0) {
            carritoBody.innerHTML = `
                <tr id="fila-vacia">
                    <td colspan="5" class="text-center text-muted py-4">
                        No has añadido ningún producto al pedido todavía.
                    </td>
                </tr>`;
            if (btnGuardarCompra) btnGuardarCompra.disabled = true;
            resetTotales();
            return;
        }

        if (btnGuardarCompra) btnGuardarCompra.disabled = false;
        let totalGeneral = 0;

        carrito.forEach((producto, index) => {
            const subtotalFila = producto.precio * producto.cantidad;
            totalGeneral += subtotalFila;

            carritoBody.innerHTML += `
                <tr>
                    <td><span class="fw-semibold">${producto.nombre}</span></td>
                    <td>
                        <input type="number" 
                               min="1" 
                               value="${producto.cantidad}" 
                               class="form-control form-control-sm" 
                               style="width: 80px;"
                               onchange="actualizarCantidad(${index}, this.value)"
                        >
                    </td>
                    <td>
                        <input type="number" 
                               min="0" 
                               step="0.01" 
                               value="${producto.precio.toFixed(2)}" 
                               class="form-control form-control-sm" 
                               style="width: 100px;"
                               onchange="actualizarPrecio(${index}, this.value)"
                        >
                    </td>
                    <td><span class="fw-bold">S/ ${subtotalFila.toFixed(2)}</span></td>
                    <td class="text-center">
                        <button type="button" class="btn btn-sm btn-icon btn-label-danger" onclick="eliminarProducto(${index})">
                            <i class="bx bx-trash"></i>
                        </button>
                    </td>
                </tr>`;

            // Inputs ocultos para enviar al controlador de Laravel de forma limpia
            if (productosContainer) {
                productosContainer.innerHTML += `
                    <input type="hidden" name="productos[]" value="${producto.id}">
                    <input type="hidden" name="cantidades[]" value="${producto.cantidad}">
                    <input type="hidden" name="precios[]" value="${producto.precio}">
                `;
            }
        });

        // Cálculos financieros (18% IGV incluido)
        const subtotalSinIgv = totalGeneral / 1.18;
        const igv = totalGeneral - subtotalSinIgv;

        document.getElementById('txt-subtotal').innerText = `S/ ${subtotalSinIgv.toFixed(2)}`;
        document.getElementById('txt-igv').innerText = `S/ ${igv.toFixed(2)}`;
        document.getElementById('txt-total').innerText = `S/ ${totalGeneral.toFixed(2)}`;
    }

    function actualizarCantidad(index, cantidad) {
        const cantInt = parseInt(cantidad);
        carrito[index].cantidad = cantInt > 0 ? cantInt : 1;
        renderCarrito();
    }

    function actualizarPrecio(index, precio) {
        const precioFloat = parseFloat(precio);
        carrito[index].precio = precioFloat >= 0 ? precioFloat : 0;
        renderCarrito();
    }

    function eliminarProducto(index) {
        carrito.splice(index, 1);
        renderCarrito();
    }

    function resetTotales() {
        document.getElementById('txt-subtotal').innerText = 'S/ 0.00';
        document.getElementById('txt-igv').innerText = 'S/ 0.00';
        document.getElementById('txt-total').innerText = 'S/ 0.00';
    }
</script>
