<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instalar LicorControl</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#5a8dee">

    <style>
        body {
            font-family: 'Public Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: #f5f5f9;
            color: #566a7f;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            padding: 20px;
            box-sizing: border-box;
        }

        .card {
            background: #fff;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 2px 6px 0 rgba(67, 89, 113, 0.12);
            text-align: center;
            max-width: 400px;
            width: 100%;
        }

        h1 {
            color: #5a8dee;
            margin-bottom: 10px;
            font-size: 24px;
        }

        p {
            font-size: 15px;
            line-height: 1.6;
            margin-bottom: 30px;
        }

        .btn {
            display: inline-block;
            background-color: #5a8dee;
            color: #fff;
            padding: 12px 24px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.2s ease;
            box-shadow: 0 4px 8px 0 rgba(90, 141, 238, 0.4);
            border: none;
            cursor: pointer;
            width: 100%;
            box-sizing: border-box;
            font-size: 15px;
        }

        .btn:hover {
            background-color: #4871de;
            transform: translateY(-1px);
        }

        .btn-secondary {
            background-color: transparent;
            color: #5a8dee;
            box-shadow: none;
            margin-top: 15px;
        }

        .btn-secondary:hover {
            background-color: rgba(90, 141, 238, 0.08);
            transform: none;
        }

        .logo {
            font-size: 48px;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>

    <div class="card">
        <div class="logo">🍾</div>
        <h1>LicorControl App</h1>
        <p>Sistema de control de inventarios con soporte offline
        </p>

        {{-- <button id="btnInstalarPwa" class="btn" style="display: none;">Instalar Aplicación</button> --}}

        <a href="{{ route('login') }}" class="btn btn-secondary">Iniciar Sesión</a>
    </div>

    <script>
        let deferredPrompt;
        const btnInstalar = document.getElementById('btnInstalarPwa');

        // 1. Registramos el Service Worker en esta ruta
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then(reg => console.log('SW registrado para instalación', reg))
                    .catch(err => console.error('Error de SW', err));
            });
        }

        // 2. Capturamos el evento de instalación nativo del navegador
        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;
            // Si el celular es compatible, mostramos el botón de instalar de la tarjeta
            btnInstalar.style.display = 'block';
        });

        // 3. Acción al hacer clic en nuestro botón personalizado de instalar
        btnInstalar.addEventListener('click', async () => {
            if (!deferredPrompt) return;
            deferredPrompt.prompt();
            const {
                outcome
            } = await deferredPrompt.userChoice;
            console.log(`Resultado de la instalación: ${outcome}`);
            deferredPrompt = null;
            btnInstalar.style.display = 'none';
        });

        // Opcional: Si ya está instalada la app, ocultamos el botón
        window.addEventListener('appinstalled', () => {
            console.log('¡Aplicación instalada con éxito!');
            btnInstalar.style.display = 'none';
        });
    </script>
</body>

</html>
