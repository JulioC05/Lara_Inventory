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

    let carrito = [];
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

        const existente = carrito.find(item => item.id == productoId);

        if (existente) {
            if ((existente.cantidad + 1) > stock) {
                alert('Stock insuficiente');
                return;
            }
            existente.cantidad++;
        } else {
            carrito.push({
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

        carrito.forEach((producto, index) => {
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
        carrito[index].cantidad = parseInt(cantidad) || 1;
        renderCarrito();
    }

    function eliminarProducto(index) {
        carrito.splice(index, 1);
        renderCarrito();
    }

    /*
    |--------------------------------------------------------------------------
    | LÓGICA DE CONTROL SUNAT EN TIEMPO REAL
    |--------------------------------------------------------------------------
    */
    function validarReglasSunat(totalActual = null) {
        if (totalActual === null) {
            totalActual = parseFloat(document.getElementById('totalInput').value) || 0;
        }

        const tipoComprobante = selectComprobante.value;
        const clienteSeleccionado = selectCliente.value;

        // Conseguimos la opción que está seleccionada actualmente
        const optionCliente = selectCliente.options[selectCliente.selectedIndex];
        const tipoDocumentoCliente = optionCliente ? optionCliente.dataset.documento : '';

        // Limpiar alertas previas
        selectCliente.classList.remove('is-invalid', 'border-danger');

        // 1. Validar Boleta >= S/ 700
        if (tipoComprobante === 'boleta' && totalActual >= SUNAT_LIMITE_BOLETA) {
            if (clienteSeleccionado == "1" || !clienteSeleccionado || tipoDocumentoCliente !== 'DNI') {
                selectCliente.classList.add('is-invalid', 'border-danger');
            }
        }

        // 2. 🔥 VALIDAR FACTURA (Regla Estricta)
        if (tipoComprobante === 'factura') {
            // Si es el cliente genérico o el documento NO es RUC, se pinta de rojo
            if (clienteSeleccionado == "1" || !clienteSeleccionado || tipoDocumentoCliente !== 'RUC') {
                selectCliente.classList.add('is-invalid', 'border-danger');
            }
        }
    }

    // Listeners para reaccionar al cambio de los selectores de la barra lateral
    if (selectComprobante) {
        selectComprobante.addEventListener('change', () => validarReglasSunat());
    }
    if (selectCliente) {
        selectCliente.addEventListener('change', () => validarReglasSunat());
    }

    // Bloqueo preventivo al intentar enviar el formulario con errores
    if (formVenta) {
        formVenta.addEventListener('submit', function(e) {
            const totalActual = parseFloat(document.getElementById('totalInput').value) || 0;
            const tipoComprobante = selectComprobante.value;
            const clienteSeleccionado = selectCliente.value;

            if (tipoComprobante === 'boleta' && totalActual >= SUNAT_LIMITE_BOLETA) {
                if (clienteSeleccionado == "1" || !clienteSeleccionado) {
                    e.preventDefault();
                    alert(
                        `🚨 Regla SUNAT: Para boletas con montos mayores o iguales a S/ ${SUNAT_LIMITE_BOLETA} es obligatorio identificar al cliente con su DNI/CE.`
                    );
                    return false;
                }
            }

            if (tipoComprobante === 'factura' && (clienteSeleccionado == "1" || tipoDocumentoCliente !==
                'RUC')) {
                e.preventDefault();
                alert(
                    '🚨 Regla SUNAT: Las Facturas solo pueden ser emitidas a clientes registrados con un número de RUC válido.');
                return false;
            }
        });
    }
</script>
