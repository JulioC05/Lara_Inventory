<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Detectar si el usuario opera desde un smartphone o tablet
        const esMovil = /Android|iPhone|iPad|iPod/i.test(navigator.userAgent);
        const scannerToggle = document.getElementById('scannerToggle'); // Tu checkbox/switch
        let html5QrCode = null;
        let escaneoBloqueado = false;

        scannerToggle.addEventListener('change', async function() {
            if (this.checked) {
                if (esMovil) {
                    iniciarScannerCamara();
                } else {
                    activarScannerPistola();
                }
            } else {
                detenerScanner();
            }
        });

        function activarScannerPistola() {
            // Buscamos la barra de búsqueda interna de TomSelect
            const tomInput = document.querySelector('.ts-control input');
            if (!tomInput) return;

            tomInput.focus();
            productoTom.close(); // Cierra el menú desplegable para no tapar la pantalla
        }

        // async function iniciarScannerCamara() {
        //     try {
        //         let reader = document.getElementById('reader');
        //         if (!reader) {
        //             reader = document.createElement('div');
        //             reader.id = 'reader';
        //             reader.classList.add('mt-3', 'rounded');
        //             document.querySelector('.scanner-container').appendChild(reader);
        //         }

        //         html5QrCode = new Html5Qrcode('reader');

        //         await html5QrCode.start({
        //                 facingMode: 'environment'
        //             }, // Usa la cámara trasera
        //             {
        //                 fps: 10,
        //                 qrbox: {
        //                     width: 250,
        //                     height: 250
        //                 }
        //             },
        //             onScanSuccess
        //         );
        //     } catch (error) {
        //         console.error(error);
        //         scannerToggle.checked = false;
        //         alert('No se pudo acceder a la cámara: ' + error);
        //     }
        // }

        async function iniciarScannerCamara() {
            try {
                let reader = document.getElementById('reader');
                if (!reader) {
                    reader = document.createElement('div');
                    reader.id = 'reader';
                    reader.classList.add('mt-3', 'rounded');
                    document.querySelector('.scanner-container').appendChild(reader);
                }

                html5QrCode = new Html5Qrcode('reader');

                await html5QrCode.start({
                        facingMode: 'environment'
                    }, // Usa la cámara trasera
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
                console.error('Error al iniciar cámara:', error);

                // 1. Desactivar el switch para que no quede como 'encendido'
                if (scannerToggle) {
                    scannerToggle.checked = false;
                }

                // 2. Convertir el error a string para evaluarlo
                const errStr = String(error).toLowerCase();

                // 3. Evaluar si fue un bloqueo explícito de permisos
                if (
                    errStr.includes('notallowederror') ||
                    errStr.includes('permission denied') ||
                    errStr.includes('permissiondeniederror') ||
                    errStr.includes('notallowed')
                ) {
                    // Mostrar modal educativo de como desbloquear la cámara
                    const modalEl = document.getElementById('cameraPermissionModal');
                    if (modalEl) {
                        const modal = new bootstrap.Modal(modalEl);
                        modal.show();
                    } else {
                        alert(
                            'Acceso a la cámara denegado. Habilita el permiso desde la configuración de tu dispositivo.');
                    }
                } else {
                    // Error técnico alternativo (ej. la cámara está siendo usada por otra App)
                    const scanToastEl = document.getElementById('scanSuccessToast');
                    const toastBody = document.getElementById('scanToastBody');

                    if (scanToastEl && toastBody) {
                        // Cambiamos temporalmente el color a peligro para el error
                        scanToastEl.classList.remove('bg-success');
                        scanToastEl.classList.add('bg-danger');

                        toastBody.innerHTML =
                            `<strong>Error de Cámara:</strong><br><small>No se pudo iniciar el dispositivo de video.</small>`;

                        const toast = new bootstrap.Toast(scanToastEl);
                        toast.show();
                    } else {
                        alert(
                            'No se pudo acceder a la cámara. Verifica que no esté siendo usada por otra app.');
                    }
                }
            }
        }

        function onScanSuccess(decodedText) {
            // Control Antirebote (Evita lecturas infinitas duplicadas en un segundo)
            if (escaneoBloqueado) return;
            escaneoBloqueado = true;

            buscarProductoPorCodigo(decodedText);

            // Cooldown de 2 segundos entre lecturas de botellas
            setTimeout(() => {
                escaneoBloqueado = false;
            }, 2000);
        }

        function buscarProductoPorCodigo(codigo) {
            // Convertimos las opciones de tu select de compras en array para buscar por dataset
            const option = Array.from(
                document.querySelectorAll('#productoSelect option')
            ).find(opt =>
                String(opt.dataset.codigo).trim() === String(codigo).trim()
            );

            if (!option) {
                console.warn('Código de barras no registrado en licores:', codigo);
                return;
            }

            // Añadir el producto encontrado directamente al carrito de compras
            agregarProducto(option.value);

            // Limpieza del input de escritorio por si quedó texto residual de la pistola
            const tomInput = document.querySelector('.ts-control input');
            if (tomInput) {
                tomInput.value = '';
            }
        }

        async function detenerScanner() {
            try {
                if (html5QrCode && html5QrCode.isScanning) {
                    await html5QrCode.stop();
                    await html5QrCode.clear();
                }
            } catch (error) {
                console.error(error);
            }
            escaneoBloqueado = false;
        }
    });
</script>
