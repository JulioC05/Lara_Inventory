document.addEventListener('DOMContentLoaded', () => {
  document.addEventListener('click', function (e) {
    const boton = e.target.closest('.btn-edit');

    if (!boton) return;

    const url = boton.dataset.url;
    const id = boton.dataset.id;
    const categoria = boton.dataset.categoria;
    const marca = boton.dataset.marca;
    const nombre = boton.dataset.nombre;
    const codigo_barras = boton.dataset.codigo_barras;
    const descripcion = boton.dataset.descripcion;
    const contenido_ml = boton.dataset.contenido_ml;
    const graduacion_alcoholica = boton.dataset.graduacion_alcoholica;
    const imagen = boton.dataset.imagen;
    const stock = boton.dataset.stock;
    const stock_minimo = boton.dataset.stock_minimo;
    const precio_compra = boton.dataset.precio_compra;
    const margen_ganancia = boton.dataset.margen_ganancia;
    const precio_venta = boton.dataset.precio_venta;

    document.getElementById('formEditar').action = url;

    document.getElementById('categoria_id').value = categoria;
    document.getElementById('marca_id').value = marca;
    document.getElementById(`nombre_${id}`).value = nombre;
    document.getElementById('codigo_barras').value = codigo_barras;
    document.getElementById('descripcion').value = descripcion;
    document.getElementById('contenido_ml').value = contenido_ml;
    document.getElementById('graduacion_alcoholica').value = graduacion_alcoholica;

    const preview = document.getElementById('preview_imagen_edit');

    if (imagen) {
      preview.src = imagen;

      preview.style.display = 'block';
    } else {
      preview.style.display = 'none';
    }

    //   document.getElementById('edit_Nombre').value = imagen;

    document.getElementById('stock').value = stock;
    document.getElementById('stock_minimo').value = stock_minimo;
    document.getElementById(`precio_compra_${id}`).value = precio_compra;
    document.getElementById(`margen_ganancia_${id}`).value = margen_ganancia;
    document.getElementById(`precio_venta_${id}`).value = precio_venta;
  });
});
