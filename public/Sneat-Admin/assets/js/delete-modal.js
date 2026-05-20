document.addEventListener('DOMContentLoaded', () => {
  document.addEventListener('click', function (e) {
    const boton = e.target.closest('.btn-delete');

    if (!boton) return;

    const url = boton.dataset.url;
    const nombre = boton.dataset.nombre;

    const form = document.getElementById('formEliminar');

    form.action = url;

    document.getElementById('textoEliminar').innerText = `¿Estas seguro de eliminar "${nombre}"?`;
  });
});
