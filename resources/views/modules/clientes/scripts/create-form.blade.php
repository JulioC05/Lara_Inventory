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
                // Opcional: Puedes definir un estándar de longitud máxima para CE/Pasaporte si lo deseas (ej. 12 o 15)
                numeroDocumento.maxLength = 15;
            }

            // Cortar texto sobrante si existiera por un cambio abrupto de tipo de documento
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

            // =========================
            // PERSONA NATURAL
            // =========================
            if (tipo === 'natural') {
                camposNatural.style.display = 'block';
                camposJuridica.style.display = 'none';

                // Opciones documento
                createTipoDocumento.innerHTML = `
                <option value="DNI">DNI</option>
                <option value="PASAPORTE">Pasaporte</option>
                <option value="CE">CE</option>
            `;

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

                // Opciones documento
                createTipoDocumento.innerHTML = `
                <option value="RUC">RUC</option>
            `;

                // Limpiar campos de natural
                if (nombre) nombre.value = '';
                if (apellido) apellido.value = '';
            }

            // Ejecutar inmediatamente la actualización del maxlength según la opción seleccionada por defecto
            actualizarDocumento();
            // cambiarFormulario();
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
                // Si cambian a PASAPORTE o CE, permitimos que lo que ya esté escrito mantenga sus letras
                // (el evento 'input' se encargará de validar lo nuevo que se digite)
            });
        }

        // Restricción inteligente en tiempo real
        if (numeroDocumento) {
            numeroDocumento.addEventListener('input', () => {
                const tipo = createTipoDocumento.value;

                // Si es DNI o RUC, removemos activamente cualquier letra o símbolo
                if (documentosNumericos.includes(tipo)) {
                    numeroDocumento.value = numeroDocumento.value.replace(/\D/g, '');
                }
                // Si es CE o PASAPORTE, permitimos letras y números (Alfanumérico), eliminando caracteres raros si quieres
                else if (tipo === 'CE' || tipo === 'PASAPORTE') {
                    // Esto permite letras (mayúsculas/minúsculas) y números. Borra espacios o signos.
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
                // Limpiamos el input del número de documento para que empiece de cero al abrir
                if (numeroDocumento) numeroDocumento.value = '';

                // Disparamos la cadena: Lee Persona -> Renderiza Documentos -> Aplica Maxlength
                cambiarFormulario();
            });
        } else {
            cambiarFormulario();
        }
    });
</script>
