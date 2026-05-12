document.addEventListener('DOMContentLoaded', () => {

    const botonesEliminar = document.querySelectorAll('.btn-delete');

    botonesEliminar.forEach(boton => {

        boton.addEventListener('click', function () {

            const url = this.dataset.url;
            const nombre = this.dataset.nombre;

            const form = document.getElementById('formEliminar');

            form.action = url;

            document.getElementById('textoEliminar')
                .innerText = `¿Estas seguro de eliminar "${nombre}"?`;

        });

    });

});