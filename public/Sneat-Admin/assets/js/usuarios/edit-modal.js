document.addEventListener('DOMContentLoaded', () => {
  const botonesEditar = document.querySelectorAll('.btn-edit-user');

  botonesEditar.forEach(boton => {
    boton.addEventListener('click', function () {
      document.getElementById('formEditar').action = this.dataset.url;

      document.getElementById('editName').value = this.dataset.name;

      document.getElementById('editEmail').value = this.dataset.email;
    });
  });
});
