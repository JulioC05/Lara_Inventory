<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña - Económica</title>
    <style>
        body {
            background-color: #f5f5f9;
            font-family: "Public Sans", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            margin: 0;
            padding: 40px 20px;
            color: #566a7f;
        }
        .container {
            max-width: 520px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(67, 89, 113, 0.1);
            overflow: hidden;
            border: 1px solid #d9dee3;
        }
        .header {
            background-color: #ffffff;
            padding: 30px 30px 10px 30px;
            text-align: center;
        }
        .logo {
            font-size: 24px;
            font-weight: 700;
            color: #566a7f;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 0;
        }
        .logo span {
            color: #696cff; /* Púrpura de Sneat */
        }
        .content {
            padding: 20px 30px 30px 30px;
            line-height: 1.6;
            font-size: 15px;
        }
        h2 {
            color: #566a7f;
            font-size: 20px;
            font-weight: 600;
            margin-top: 0;
            margin-bottom: 20px;
        }
        .btn-container {
            text-align: center;
            margin: 30px 0;
        }
        .btn-sneat {
            background-color: #696cff;
            color: #ffffff !important;
            text-decoration: none;
            padding: 12px 24px;
            font-size: 15px;
            font-weight: 500;
            border-radius: 6px;
            display: inline-block;
            box-shadow: 0 2px 4px rgba(105, 108, 255, 0.4);
            transition: all 0.2s ease;
        }
        .footer {
            background-color: #f5f5f9;
            padding: 20px 30px;
            text-align: center;
            font-size: 12px;
            color: #a1acb8;
            border-top: 1px solid #d9dee3;
        }
        .expiration {
            font-size: 13px;
            color: #8592a3;
            background-color: #fff3cd;
            color: #664d03;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 20px;
            border: 1px solid #ffecb5;
        }
        .subtext {
            margin-top: 25px;
            padding-top: 15px;
            border-top: 1px solid #eceef1;
            font-size: 12px;
            word-break: break-all;
            color: #b4bdc6;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="header">
            <h1 class="logo">La <span>Económica</span></h1>
        </div>

        <div class="content">
            <h2>🔑 Restablecer Contraseña</h2>
            <p>Hola,</p>
            <p>Estás recibiendo este correo porque se generó una solicitud de restablecimiento de contraseña para tu cuenta de usuario en nuestro sistema de inventariado.</p>
            
            <div class="btn-container">
                <a href="{{ $url }}" class="btn-sneat" target="_blank">
                    Cambiar mi Contraseña
                </a>
            </div>

            <div class="expiration">
                ⚠️ Este enlace de recuperación expirará en <strong>{{ $count }} minutos</strong> por motivos de seguridad.
            </div>

            <p>Si tú no solicitaste este cambio, no te preocupes, puedes ignorar este correo de forma segura y tu clave actual se mantendrá intacta.</p>
            
            <div class="subtext">
                Si tienes problemas para hacer clic en el botón, copia y pega la siguiente URL en tu navegador web:<br>
                <a href="{{ $url }}" style="color: #696cff;">{{ $url }}</a>
            </div>
        </div>

        <div class="footer">
            &copy; {{ date('Y') }} La Económica. Sistema de Control Interno.<br>
            Por favor, no respondas a este correo automático.
        </div>
    </div>

</body>
</html>