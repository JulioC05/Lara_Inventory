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

                const option = document.querySelector(
                    `option[value="${data.value}"]`
                );

                const imagen = option.dataset.imagen;

                const precio = option.dataset.precio;

                const codigo = option.dataset.codigo;

                return `
                <div class="producto-option">

                    <img
                        src="${imagen}"
                        class="producto-option-img"
                    >

                    <div class="producto-option-info">

                        <div class="producto-option-title">
                            ${escape(data.text)}
                        </div>
                        <div class="producto-option-price">
                            P. venta S/ : ${precio}
                        </div>

                    </div>

                </div>
            `;
            }
        },

        onChange: function() {

            agregarProducto();
        }
    });

    let carrito = [];

    const IGV_PERCENT = 0.18;

    const carritoBody = document.getElementById('carritoBody');

    const productosContainer = document.getElementById('productosContainer');

    // document.getElementById('btnAgregarProducto')
    //     .addEventListener('click', agregarProducto);

    function agregarProducto(productoId = null) {

        const select =
            document.getElementById(
                'productoSelect'
            );

        productoId =
            productoId || select.value;

        if (!productoId) {
            return;
        }

        const option = select.querySelector(
            `option[value="${productoId}"]`
        );

        const nombre = option.dataset.nombre;

        const precio = parseFloat(option.dataset.precio);

        const stock = parseInt(option.dataset.stock);

        const cantidad = 1;

        /*
        |--------------------------------------------------------------------------
        | VALIDAR STOCK
        |--------------------------------------------------------------------------
        */

        const existente = carrito.find(
            item => item.id == productoId
        );

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

        /*
        |--------------------------------------------------------------------------
        | LIMPIAR SELECT
        |--------------------------------------------------------------------------
        */

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
                        <input
                            type="number"
                            min="1"
                            value="${producto.cantidad}"
                            class="form-control"
                            onchange="actualizarCantidad(${index}, this.value)"
                        >
                    </td>

                    <td>S/ ${producto.precio.toFixed(2)}</td>

                    <td>S/ ${subtotal.toFixed(2)}</td>

                    <td>
                        <button
                            type="button"
                            class="btn btn-sm btn-danger"
                            onclick="eliminarProducto(${index})"
                        >
                            X
                        </button>
                    </td>

                </tr>
            `;

            productosContainer.innerHTML += `
                <input type="hidden" name="productos[${index}][id]" value="${producto.id}">
                <input type="hidden" name="productos[${index}][cantidad]" value="${producto.cantidad}">
                <input type="hidden" name="productos[${index}][precio_unitario]" value="${producto.precio}">
            `;
        });

        const total = subtotalGeneral;

        const subtotalSinIgv = total / 1.18;

        const igv = total - subtotalSinIgv;

        document.getElementById('subtotalText').innerText =
            `S/ ${subtotalSinIgv.toFixed(2)}`;

        document.getElementById('igvText').innerText =
            `S/ ${igv.toFixed(2)}`;

        document.getElementById('totalText').innerText =
            `S/ ${total.toFixed(2)}`;

        document.getElementById('subtotalInput').value =
            subtotalSinIgv.toFixed(2);

        document.getElementById('igvInput').value =
            igv.toFixed(2);

        document.getElementById('totalInput').value =
            total.toFixed(2);
    }

    function actualizarCantidad(index, cantidad) {

        carrito[index].cantidad = parseInt(cantidad);

        renderCarrito();
    }

    function eliminarProducto(index) {

        carrito.splice(index, 1);

        renderCarrito();
    }
</script>
