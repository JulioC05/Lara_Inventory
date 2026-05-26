<script>
    document.addEventListener('DOMContentLoaded', () => {

        /*
        |--------------------------------------------------------------------------
        | DETECTAR MOVIL
        |--------------------------------------------------------------------------
        */

        const esMovil =
            /Android|iPhone|iPad|iPod/i
            .test(navigator.userAgent);

        /*
        |--------------------------------------------------------------------------
        | ELEMENTOS
        |--------------------------------------------------------------------------
        */

        const scannerToggle =
            document.getElementById(
                'scannerToggle'
            );

        /*
        |--------------------------------------------------------------------------
        | VARIABLES SCANNER
        |--------------------------------------------------------------------------
        */

        let html5QrCode = null;

        let ultimoCodigo = null;

        let escaneoActivo = true;

        /*
        |--------------------------------------------------------------------------
        | TOGGLE SCANNER
        |--------------------------------------------------------------------------
        */

        scannerToggle.addEventListener(
            'change',
            async function() {
                if (this.checked) {
                    if (esMovil) {
                        iniciarScannerCamara();
                    } else {
                        activarScannerPistola();
                    }
                } else {
                    detenerScanner();
                }
            }
        );

        /*
        |--------------------------------------------------------------------------
        | SCANNER PISTOLA
        |--------------------------------------------------------------------------
        */

        function activarScannerPistola() {
            const tomInput = document.querySelector(
                '.ts-control input'
            );

            if (!tomInput) {
                return;
            }

            /*
            |--------------------------------------------------------------------------
            | FOCUS INPUT
            |--------------------------------------------------------------------------
            */

            tomInput.focus();

            /*
            |--------------------------------------------------------------------------
            | CERRAR DROPDOWN
            |--------------------------------------------------------------------------
            */

            productoTom.close();
        }

        /*
        |--------------------------------------------------------------------------
        | INICIAR CAMARA
        |--------------------------------------------------------------------------
        */

        async function iniciarScannerCamara() {
            try {
                /*
                |--------------------------------------------------------------------------
                | CREAR CONTENEDOR SI NO EXISTE
                |--------------------------------------------------------------------------
                */

                let reader =
                    document.getElementById(
                        'reader'
                    );

                if (!reader) {
                    reader =
                        document.createElement('div');

                    reader.id = 'reader';

                    reader.classList.add(
                        'mt-3'
                    );

                    document.querySelector(
                        '.scanner-container'
                    ).appendChild(reader);
                }

                /*
                |--------------------------------------------------------------------------
                | CREAR INSTANCIA
                |--------------------------------------------------------------------------
                */

                html5QrCode =
                    new Html5Qrcode(
                        'reader'
                    );

                /*
                |--------------------------------------------------------------------------
                | INICIAR SCANNER
                |--------------------------------------------------------------------------
                */

                await html5QrCode.start(

                    {
                        facingMode: 'environment'
                    },

                    {
                        fps: 10,

                        qrbox: {
                            width: 250,
                            height: 250
                        }
                    },

                    onScanSuccess
                );
            } catch (error) {
                console.error(error);

                scannerToggle.checked = false;

                alert(error);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | ESCANEO EXITOSO
        |--------------------------------------------------------------------------
        */

        // function onScanSuccess(decodedText) {
        //     /*
        //     |--------------------------------------------------------------------------
        //     | EVITAR MULTIPLE ESCANEO
        //     |--------------------------------------------------------------------------
        //     */

        //     if (!escaneoActivo)
        //         return;

        //     if (ultimoCodigo === decodedText)
        //         return;

        //     ultimoCodigo = decodedText;

        //     escaneoActivo = false;

        //     /*
        //     |--------------------------------------------------------------------------
        //     | BUSCAR PRODUCTO
        //     |--------------------------------------------------------------------------
        //     */

        //     buscarProductoPorCodigo(
        //         decodedText
        //     );

        //     /*
        //     |--------------------------------------------------------------------------
        //     | DELAY ENTRE ESCANEOS
        //     |--------------------------------------------------------------------------
        //     */

        //     setTimeout(() => {
        //         escaneoActivo = true;

        //         ultimoCodigo = null;

        //     }, 1500);
        // }
        let escaneoBloqueado = false;

        function onScanSuccess(decodedText) {
            /*
            |--------------------------------------------------------------------------
            | BLOQUEAR ESCANEO
            |--------------------------------------------------------------------------
            */

            if (escaneoBloqueado) {
                return;
            }

            escaneoBloqueado = true;

            /*
            |--------------------------------------------------------------------------
            | BUSCAR PRODUCTO
            |--------------------------------------------------------------------------
            */

            buscarProductoPorCodigo(
                decodedText
            );

            /*
            |--------------------------------------------------------------------------
            | COOLDOWN
            |--------------------------------------------------------------------------
            */

            setTimeout(() => {
                escaneoBloqueado = false;

            }, 2000);
        }

        /*
        |--------------------------------------------------------------------------
        | BUSCAR PRODUCTO
        |--------------------------------------------------------------------------
        */

        function buscarProductoPorCodigo(codigo) {
            const option = Array.from(

                document.querySelectorAll(
                    '#productoSelect option'
                )

            ).find(option =>

                String(
                    option.dataset.codigo
                ).trim()

                ===

                String(
                    codigo
                ).trim()

            );

            /*
            |--------------------------------------------------------------------------
            | NO ENCONTRADO
            |--------------------------------------------------------------------------
            */

            if (!option) {
                console.warn(
                    'Producto no encontrado:',
                    codigo
                );

                return;
            }

            /*
            |--------------------------------------------------------------------------
            | AGREGAR PRODUCTO
            |--------------------------------------------------------------------------
            */

            alert('PRODUCTO ENCONTRADO');

            // console.log(option);

            agregarProducto(
                option.value
            );
            // productoSelect.trigger(
            //     'change'
            // );

            /*
            |--------------------------------------------------------------------------
            | LIMPIAR INPUT DESKTOP
            |--------------------------------------------------------------------------
            */

            const tomInput = document.querySelector(
                '.ts-control input'
            );

            if (tomInput) {
                tomInput.value = '';
            }
        }

        /*
        |--------------------------------------------------------------------------
        | DETENER SCANNER
        |--------------------------------------------------------------------------
        */

        async function detenerScanner() {
            try {
                if (
                    html5QrCode &&
                    html5QrCode.isScanning
                ) {
                    await html5QrCode.stop();

                    await html5QrCode.clear();
                }
            } catch (error) {
                console.error(error);
            }

            /*
            |--------------------------------------------------------------------------
            | RESET VARIABLES
            |--------------------------------------------------------------------------
            */

            ultimoCodigo = null;

            escaneoActivo = true;
        }
    });
</script>
