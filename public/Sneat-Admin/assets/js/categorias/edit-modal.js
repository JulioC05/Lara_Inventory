document.addEventListener('DOMContentLoaded', () => {

    const botonesEditar = document.querySelectorAll('.btn-edit');

    botonesEditar.forEach(boton => {

        boton.addEventListener('click', function () {

            const url = this.dataset.url;
            const nombre = this.dataset.nombre;

            document.getElementById('formEditar').action = url;

            document.getElementById('editNombre').value = nombre;

        });

    });

});