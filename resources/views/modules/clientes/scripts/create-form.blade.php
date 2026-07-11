<script>
    document.addEventListener('DOMContentLoaded', () => {

        // =====================================
        // SELECTORES
        // =====================================
        const createTipoPersona = document.querySelector('#createModal [name="tipo_persona"]');
        const createTipoDocumento = document.querySelector('#createModal [name="tipo_documento"]');
        const numeroDocumento = document.querySelector('#createModal [name="numero_documento"]');

        // Contenedores de secciones
        const camposNatural = document.getElementById('campos-natural');
        const camposJuridica = document.getElementById('campos-juridica');

        // Inputs Persona Natural
        const nombre = document.getElementById('nombre');
        const apellido = document.getElementById('apellido');

        // Inputs Persona Jurídica
        const razonSocial = document.getElementById('razon_social');
        const contactoNombre = document.getElementById('contacto_nombre');
        const contactoCargo = document.getElementById('contacto_cargo');

        // Configuración de documentos numéricos
        const documentosNumericos = ['DNI', 'RUC'];

        // =====================================
        // 1. ACTUALIZAR REGLAS DEL DOCUMENTO (MAXLENGTH)
        // =====================================
        function actualizarDocumento() {
            if (!createTipoDocumento || !numeroDocumento) return;

            const tipo = createTipoDocumento.value;

            // Limpiar maxlength previo
            numeroDocumento.removeAttribute('maxlength');

            if (tipo === 'DNI') {
                numeroDocumento.maxLength = 8;
            } else if (tipo === 'RUC') {
                numeroDocumento.maxLength = 11;
            } else if (tipo === 'CE' || tipo === 'PASAPORTE') {
                numeroDocumento.maxLength = 15;
            }

            // Cortar texto sobrante si existiera por un cambio abrupto
            if (numeroDocumento.maxLength !== -1 && numeroDocumento.value.length > numeroDocumento.maxLength) {
                numeroDocumento.value = numeroDocumento.value.slice(0, numeroDocumento.maxLength);
            }
        }

        // =====================================
        // 2. DINÁMICA DEL FORMULARIO (NATURAL / JURÍDICA)
        // =====================================
        function cambiarFormulario() {
            if (!createTipoPersona || !createTipoDocumento) return;

            const tipo = createTipoPersona.value;

            // Guardar temporalmente el documento seleccionado antes de reconstruir el HTML
            const documentoPrevio = createTipoDocumento.value;

            // =========================
            // PERSONA NATURAL
            // =========================
            if (tipo === 'natural') {
                camposNatural.style.display = 'block';
                camposJuridica.style.display = 'none';

                // 🔥 Agregamos el "RUC" a las opciones de Persona Natural (RUC 10)
                createTipoDocumento.innerHTML = `
                    <option value="DNI">DNI</option>
                    <option value="RUC">RUC</option>
                    <option value="PASAPORTE">Pasaporte</option>
                    <option value="CE">CE</option>
                `;

                // Intentar restaurar la selección previa si era válida para natural
                if (['DNI', 'RUC', 'PASAPORTE', 'CE'].includes(documentoPrevio)) {
                    createTipoDocumento.value = documentoPrevio;
                }

                // Limpiar campos de jurídica
                if (razonSocial) razonSocial.value = '';
                if (contactoNombre) contactoNombre.value = '';
                if (contactoCargo) contactoCargo.value = '';
            }

            // =========================
            // PERSONA JURÍDICA
            // =========================
            else {
                camposNatural.style.display = 'none';
                camposJuridica.style.display = 'block';

                // Persona Jurídica solo maneja RUC (RUC 20)
                createTipoDocumento.innerHTML = `
                    <option value="RUC">RUC</option>
                `;

                // Limpiar campos de natural
                if (nombre) nombre.value = '';
                if (apellido) apellido.value = '';
            }

            // Ejecutar inmediatamente la actualización del maxlength
            actualizarDocumento();
        }

        // =====================================
        // 3. ESCUCHADORES DE EVENTOS (LISTENERS)
        // =====================================

        // Evento al cambiar el tipo de persona
        if (createTipoPersona) {
            createTipoPersona.addEventListener('change', cambiarFormulario);
        }

        // Evento al cambiar el tipo de documento directamente
        if (createTipoDocumento) {
            createTipoDocumento.addEventListener('change', () => {
                actualizarDocumento();
            });
        }

        // Restricción inteligente en tiempo real
        if (numeroDocumento) {
            numeroDocumento.addEventListener('input', () => {
                const tipo = createTipoDocumento.value;

                // Si es DNI o RUC, removemos letras o símbolos
                if (documentosNumericos.includes(tipo)) {
                    numeroDocumento.value = numeroDocumento.value.replace(/\D/g, '');
                }
                // Si es CE o PASAPORTE, permitimos alfanumérico
                else if (tipo === 'CE' || tipo === 'PASAPORTE') {
                    numeroDocumento.value = numeroDocumento.value.replace(/[^a-zA-Z0-9]/g, '');
                }
            });
        }

        // =====================================
        // 4. INICIALIZACIÓN COMPLETA AL ABRIR EL MODAL
        // =====================================
        const miModal = document.getElementById('createModal');

        if (miModal) {
            miModal.addEventListener('show.bs.modal', () => {
                if (numeroDocumento) numeroDocumento.value = '';
                cambiarFormulario();
            });
        } else {
            cambiarFormulario();
        }
    });
</script>
