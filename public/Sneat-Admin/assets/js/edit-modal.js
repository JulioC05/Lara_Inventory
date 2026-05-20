document.addEventListener('DOMContentLoaded', () => {
  /*
    |--------------------------------------------------------------------------
    | ABRIR MODAL EDITAR
    |--------------------------------------------------------------------------
    */

  document.addEventListener('click', function (e) {
    const boton = e.target.closest('.btn-edit');

    if (!boton) return;

    /*
        |--------------------------------------------------------------------------
        | FORM ACTION
        |--------------------------------------------------------------------------
        */

    const form = document.getElementById('formEditar');

    form.action = boton.dataset.url;

    /*
        |--------------------------------------------------------------------------
        | RECORRER TODOS LOS data-*
        |--------------------------------------------------------------------------
        */

    Object.keys(boton.dataset).forEach(key => {
      // ignorar url
      if (key === 'url') return;

      const value = boton.dataset[key];

      /*
            |--------------------------------------------------------------------------
            | INPUT / SELECT / TEXTAREA
            |--------------------------------------------------------------------------
            */

      const field = document.getElementById(`edit_${key}`);

      if (!field) return;

      /*
            |--------------------------------------------------------------------------
            | INPUT FILE
            |--------------------------------------------------------------------------
            */

      if (field.type === 'file') {
        // NO setear value por seguridad

        /*
                |--------------------------------------------------------------------------
                | PREVIEW IMAGEN
                |--------------------------------------------------------------------------
                */

        const preview = document.getElementById(`preview_edit_${key}`);

        if (preview) {
          if (value) {
            preview.src = value;

            // guardar imagen original
            preview.dataset.original = value;

            preview.style.display = 'block';
          } else {
            preview.src = '';

            preview.dataset.original = '';

            preview.style.display = 'none';
          }
        }

        return;
      }

      /*
            |--------------------------------------------------------------------------
            | CHECKBOX
            |--------------------------------------------------------------------------
            */

      if (field.type === 'checkbox') {
        field.checked = value == 1 || value === 'true';

        return;
      }

      /*
            |--------------------------------------------------------------------------
            | RADIO
            |--------------------------------------------------------------------------
            */

      if (field.type === 'radio') {
        field.checked = field.value === value;

        return;
      }

      /*
            |--------------------------------------------------------------------------
            | NORMAL INPUTS / SELECTS / TEXTAREAS
            |--------------------------------------------------------------------------
            */

      field.value = value ?? '';
    });
  });

  /*
    |--------------------------------------------------------------------------
    | PREVIEW NUEVA IMAGEN - CREATE
    |--------------------------------------------------------------------------
    */

  const inputImagenCreate = document.getElementById('create_imagen');

  const previewImagenCreate = document.getElementById('preview_create_imagen');

  if (inputImagenCreate && previewImagenCreate) {
    inputImagenCreate.addEventListener('change', function (e) {
      const file = e.target.files[0];

      if (!file) return;

      const reader = new FileReader();

      reader.onload = function (event) {
        previewImagenCreate.src = event.target.result;

        previewImagenCreate.style.display = 'block';
      };

      reader.readAsDataURL(file);
    });
  }

  /*
    |--------------------------------------------------------------------------
    | PREVIEW NUEVA IMAGEN - EDITAR
    |--------------------------------------------------------------------------
    */

  const inputImagen = document.getElementById('edit_imagen');

  const previewImagen = document.getElementById('preview_edit_imagen');

  if (inputImagen && previewImagen) {
    inputImagen.addEventListener('change', function (e) {
      const file = e.target.files[0];

      if (!file) return;

      const reader = new FileReader();

      reader.onload = function (event) {
        previewImagen.src = event.target.result;

        previewImagen.style.display = 'block';
      };

      reader.readAsDataURL(file);
    });
  }

  /*
    |--------------------------------------------------------------------------
    | RESTAURAR IMAGEN ORIGINAL
    |--------------------------------------------------------------------------
    */

  const btnRestore = document.getElementById('btn_restore_imagen');

  if (btnRestore && inputImagen && previewImagen) {
    btnRestore.addEventListener('click', function () {
      // limpiar input file
      inputImagen.value = '';

      // restaurar original
      const original = previewImagen.dataset.original;

      if (original) {
        previewImagen.src = original;

        previewImagen.style.display = 'block';
      } else {
        previewImagen.src = '';

        previewImagen.style.display = 'none';
      }
    });
  }
});
