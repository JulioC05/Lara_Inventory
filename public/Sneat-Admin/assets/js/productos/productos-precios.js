document.addEventListener('DOMContentLoaded', () => {
  initCreatePricing();

  initEditPricing();
});

/*
|--------------------------------------------------------------------------
| CREATE MODAL
|--------------------------------------------------------------------------
*/

function initCreatePricing() {
  const precioCompra = document.getElementById('precio_compra');

  const margen = document.getElementById('margen_ganancia');

  const precioVenta = document.getElementById('precio_venta');

  if (!precioCompra || !margen || !precioVenta) return;

  // Compra + margen => venta
  function calcularVenta() {
    const compra = parseFloat(precioCompra.value) || 0;

    const margenValor = parseFloat(margen.value) || 0;

    const venta = compra + (compra * margenValor) / 100;

    precioVenta.value = venta.toFixed(2);
  }

  // Compra + venta => margen
  function calcularMargen() {
    const compra = parseFloat(precioCompra.value) || 0;

    const venta = parseFloat(precioVenta.value) || 0;

    if (compra <= 0) {
      margen.value = 0;

      return;
    }

    const margenCalculado = ((venta - compra) / compra) * 100;

    margen.value = margenCalculado.toFixed(2);
  }

  margen.addEventListener('input', calcularVenta);

  precioCompra.addEventListener('input', calcularVenta);

  precioVenta.addEventListener('input', calcularMargen);
}

/*
|--------------------------------------------------------------------------
| EDIT MODALS
|--------------------------------------------------------------------------
*/

function initEditPricing() {
  document.querySelectorAll('[id^="edit_precio_compra"]').forEach(precioCompra => {
    const id = precioCompra.id.replace('edit_precio_compra', '');

    const margen = document.getElementById(`edit_margen_ganancia`);

    const precioVenta = document.getElementById(`edit_precio_venta`);

    if (!margen || !precioVenta) return;

    // Compra + margen => venta
    function calcularVenta() {
      const compra = parseFloat(precioCompra.value) || 0;

      const margenValor = parseFloat(margen.value) || 0;

      const venta = compra + (compra * margenValor) / 100;

      precioVenta.value = venta.toFixed(2);
    }

    // Compra + venta => margen
    function calcularMargen() {
      const compra = parseFloat(precioCompra.value) || 0;

      const venta = parseFloat(precioVenta.value) || 0;

      if (compra <= 0) {
        margen.value = 0;

        return;
      }

      const margenCalculado = ((venta - compra) / compra) * 100;

      margen.value = margenCalculado.toFixed(2);
    }

    margen.addEventListener('input', calcularVenta);

    precioCompra.addEventListener('input', calcularVenta);

    precioVenta.addEventListener('input', calcularMargen);
  });
}
