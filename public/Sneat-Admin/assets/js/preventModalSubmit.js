document.addEventListener('DOMContentLoaded', function () {
  // 1. Seleccionamos todos los formularios que usen validación de Bootstrap
  const forms = document.querySelectorAll('.needs-validation');

  Array.from(forms).forEach(form => {
    // Buscamos si este formulario específico tiene campos de contraseña
    const pass1 = form.querySelector('.input-pass-main');
    const pass2 = form.querySelector('.input-pass-confirm');

    if (pass1 && pass2) {
      const validarPass = () => {
        if (pass1.value !== pass2.value) {
          pass2.setCustomValidity('No coinciden');
        } else {
          pass2.setCustomValidity('');
        }
      };
      // Validar mientras escriben
      pass1.addEventListener('input', validarPass);
      pass2.addEventListener('input', validarPass);
    }

    // 2. Lógica estándar de Bootstrap para el envío
    form.addEventListener(
      'submit',
      event => {
        if (!form.checkValidity()) {
          event.preventDefault();
          event.stopPropagation();
        }
        form.classList.add('was-validated');
      },
      false
    );

    // 3. Opcional: Limpiar validación al cerrar modales
    // const modalElement = form.closest('.modal');
    // if (modalElement) {
    //   modalElement.addEventListener('hidden.bs.modal', () => {
    //     form.reset();
    //     form.classList.remove('was-validated');
    //     if (pass2) pass2.setCustomValidity('');
    //   });
    // }
  });
});
