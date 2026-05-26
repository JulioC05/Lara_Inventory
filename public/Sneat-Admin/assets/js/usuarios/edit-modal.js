document.addEventListener('DOMContentLoaded', () => {
  const botonesEditar = document.querySelectorAll('.btn-edit-user');

  botonesEditar.forEach(boton => {
    boton.addEventListener('click', function () {
      document.getElementById('formEditar').action = this.dataset.url;

      document.getElementById('editName').value = this.dataset.name;

      document.getElementById('editEmail').value = this.dataset.email;

      document.getElementById('editRole').value = this.dataset.role;

      const contenedorRole = document.getElementById('contenedorRole');

      if (this.dataset.role === 'Admin') {
        contenedorRole.style.display = 'none'; // Lo oculta si es Admin
      } else {
        contenedorRole.style.display = 'block'; // Lo muestra para los demás
      }
    });
  });
});
