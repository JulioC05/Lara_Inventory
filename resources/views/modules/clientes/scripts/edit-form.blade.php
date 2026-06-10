<script>
    document.addEventListener('DOMContentLoaded', () => {

        // =========================================
        // ELEMENTOS
        // =========================================
        const form = document.getElementById('formEditar');
        const editTipoPersona = document.querySelector('#edit_tipo_persona');
        const editTipoDocumento = document.querySelector('#edit_tipo_documento');
        const editCamposNatural = document.getElementById('edit-campos-natural');
        const editCamposJuridica = document.getElementById('edit-campos-juridica');

        // Inputs
        const editNumeroDocumento = document.querySelector('#edit_numero_documento');
        const editNombre = document.querySelector('#edit_nombre');
        const editApellido = document.querySelector('#edit_apellido');
        const editRazonSocial = document.querySelector('#edit_razon_social');
        const editContactoNombre = document.querySelector('#edit_contacto_nombre');
        const editContactoCargo = document.querySelector('#edit_contacto_cargo');
        const editTelefono = document.querySelector('#edit_telefono');
        const editEmail = document.querySelector('#edit_email');
        const editDireccion = document.querySelector('#edit_direccion');

        // Configuración de documentos numéricos
        const documentosNumericos = ['DNI', 'RUC'];

        // =========================================
        // 1. ACTUALIZAR REGLAS DEL DOCUMENTO (MAXLENGTH)
        // =========================================
        function editActualizarDocumento() {
            if (!editTipoDocumento || !editNumeroDocumento) return;

            const tipo = editTipoDocumento.value;

            // Limpiar maxlength previo
            editNumeroDocumento.removeAttribute('maxlength');

            if (tipo === 'DNI') {
                editNumeroDocumento.maxLength = 8;
            } else if (tipo === 'RUC') {
                editNumeroDocumento.maxLength = 11;
            } else if (tipo === 'CE' || tipo === 'PASAPORTE') {
                editNumeroDocumento.maxLength = 15; // Estándar máximo sugerido
            }

            // Cortar texto sobrante si existiera por un cambio abrupto
            if (editNumeroDocumento.maxLength !== -1 && editNumeroDocumento.value.length > editNumeroDocumento
                .maxLength) {
                editNumeroDocumento.value = editNumeroDocumento.value.slice(0, editNumeroDocumento.maxLength);
            }
        }

        // =========================================
        // 2. CAMBIAR FORMULARIO (DINÁMICO)
        // =========================================
        function editCambiarFormulario(tipo, documento = null) {
            if (!editTipoDocumento) return;

            editTipoDocumento.innerHTML = '';

            // ===============================
            // NATURAL
            // ===============================
            if (tipo === 'natural') {
                if (editCamposNatural) editCamposNatural.style.display = 'block';
                if (editCamposJuridica) editCamposJuridica.style.display = 'none';

                editTipoDocumento.append(new Option('DNI', 'DNI'));
                editTipoDocumento.append(new Option('PASAPORTE', 'PASAPORTE'));
                editTipoDocumento.append(new Option('CE', 'CE'));
            }
            // ===============================
            // JURIDICA
            // ===============================
            else {
                if (editCamposNatural) editCamposNatural.style.display = 'none';
                if (editCamposJuridica) editCamposJuridica.style.display = 'block';

                editTipoDocumento.append(new Option('RUC', 'RUC'));
            }

            // Si pasamos un documento específico (al cargar datos del botón), lo seleccionamos
            if (documento) {
                editTipoDocumento.value = documento;
            }

            // Aplicar las reglas de validación y maxlength inmediatamente
            editActualizarDocumento();
        }

        // =========================================
        // 3. BOTONES EDITAR (INICIALIZACIÓN AL HACER CLICK)
        // =========================================
        document.querySelectorAll('.btn-edit').forEach(btn => {
            btn.addEventListener('click', () => {

                // Datasets
                const id = btn.dataset.id;
                const tipo_persona = btn.dataset.tipo_persona;
                const tipo_documento = btn.dataset.tipo_documento;

                // URL del Formulario (descomenta si lo usas)
                // if (form) form.action = `/clientes/${id}`;

                // 1. Seteamos el tipo de persona primero
                if (editTipoPersona) editTipoPersona.value = tipo_persona;

                // 2. Construimos los selects de documentos y aplicamos el maxlength inicial interno
                editCambiarFormulario(tipo_persona, tipo_documento);

                // 3. Seteamos los valores en los inputs correspondientes
                if (editNumeroDocumento) editNumeroDocumento.value = btn.dataset
                    .numero_documento ?? '';
                if (editNombre) editNombre.value = btn.dataset.nombre ?? '';
                if (editApellido) editApellido.value = btn.dataset.apellido ?? '';
                if (editRazonSocial) editRazonSocial.value = btn.dataset.razon_social ?? '';
                if (editContactoNombre) editContactoNombre.value = btn.dataset
                    .contacto_nombre ?? '';
                if (editContactoCargo) editContactoCargo.value = btn.dataset.contacto_cargo ??
                    '';
                if (editTelefono) editTelefono.value = btn.dataset.telefono ?? '';
                if (editEmail) editEmail.value = btn.dataset.email ?? '';
                if (editDireccion) editDireccion.value = btn.dataset.direccion ?? '';

                // 4. Forzamos una re-verificación del texto cargado por si la base de datos tuviera datos corruptos
                editActualizarDocumento();
            });
        });

        // =========================================
        // 4. ESCUCHADORES DE EVENTOS (LISTENERS)
        // =========================================

        // Cambio manual del select Tipo Persona
        if (editTipoPersona) {
            editTipoPersona.addEventListener('change', () => {
                editCambiarFormulario(editTipoPersona.value);
            });
        }

        // Cambio manual del select Tipo Documento
        if (editTipoDocumento) {
            editTipoDocumento.addEventListener('change', editActualizarDocumento);
        }

        // Restricción inteligente en tiempo real para el edit
        if (editNumeroDocumento) {
            editNumeroDocumento.addEventListener('input', () => {
                const tipo = editTipoDocumento.value;

                // Restricción estricta de números para DNI/RUC
                if (documentosNumericos.includes(tipo)) {
                    editNumeroDocumento.value = editNumeroDocumento.value.replace(/\D/g, '');
                }
                // Filtro Alfanumérico limpio para CE/PASAPORTE (Sin espacios ni signos raros)
                else if (tipo === 'CE' || tipo === 'PASAPORTE') {
                    editNumeroDocumento.value = editNumeroDocumento.value.replace(/[^a-zA-Z0-9]/g, '');
                }
            });
        }
    });
</script>
