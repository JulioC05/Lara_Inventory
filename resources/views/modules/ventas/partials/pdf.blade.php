<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>{{ strtoupper($venta->tipo_comprobante) }} - {{ $venta->numero_comprobante }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #333;
            font-size: 12px;
            line-height: 1.4;
        }

        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 10px;
        }

        table {
            width: 100%;
            line-height: inherit;
            text-align: left;
            border-collapse: collapse;
        }

        table td {
            padding: 5px;
            vertical-align: top;
        }

        .header-table td {
            padding: 0;
        }

        .title {
            font-size: 24px;
            font-weight: bold;
            color: #696cff;
        }

        .comprobante-box {
            border: 2px solid #696cff;
            text-align: center;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 5px;
        }

        .comprobante-box h3 {
            margin: 5px 0;
            color: #696cff;
        }

        .info-tab {
            margin-top: 20px;
            background: #fcfcfc;
            border: 1px solid #eee;
            padding: 10px;
            border-radius: 4px;
        }

        .details-table {
            margin-top: 20px;
        }

        .details-table th {
            background: #696cff;
            color: #fff;
            padding: 8px;
            font-weight: bold;
            font-size: 11px;
        }

        .details-table td {
            padding: 8px;
            border-bottom: 1px solid #eee;
        }

        .text-right {
            text-align: right;
        }

        .totals-table {
            width: 40%;
            float: right;
            margin-top: 15px;
        }

        .totals-table td {
            padding: 5px;
        }

        .badge {
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }

        .bg-success {
            background-color: #e8fadf;
            color: #71dd37;
        }

        .bg-danger {
            background-color: #ffeef0;
            color: #ff3e1d;
        }
    </style>
</head>

<body>
    <div class="invoice-box">
        <table class="header-table">
            <tr>
                <td>
                    <span class="title">LICORERÍA TU NEGOCIO</span><br>
                    <small>RUC: 20123456789</small><br>
                    <small>Av. Principal 123 - Lima, Perú</small><br>
                    <small>Teléfono: (01) 456-7890</small>
                </td>
                <td style="width: 250px;">
                    <div class="comprobante-box">
                        <h3>{{ strtoupper($venta->tipo_comprobante) }}</h3>
                        <h4>ELECTRONICA</h4>
                        <h3>{{ $venta->numero_comprobante ?? 'N/A' }}</h3>
                    </div>
                </td>
            </tr>
        </table>

        <div class="info-tab">
            <table class="header-table">
                <tr>
                    <td>
                        <strong>CLIENTE:</strong> {{ $venta->cliente->nombre ?? 'Clientes Varios' }}<br>
                        <strong>CORREO:</strong> {{ $venta->cliente->email ?? '-' }}<br>
                        <strong>TELÉFONO:</strong> {{ $venta->cliente->telefono ?? '-' }}
                    </td>
                    <td class="text-right" style="width: 250px;">
                        <strong>FECHA EMISIÓN:</strong>
                        {{ \Carbon\Carbon::parse($venta->fecha_venta)->format('d/m/Y H:i') }}<br>
                        <strong>MÉTODO PAGO:</strong> {{ $venta->metodoPago->nombre }}<br>
                        <strong>ESTADO:</strong>
                        <span class="badge {{ $venta->estado === 'completada' ? 'bg-success' : 'bg-danger' }}">
                            {{ strtoupper($venta->estado) }}
                        </span>
                    </td>
                </tr>
            </table>
        </div>

        <table class="details-table">
            <thead>
                <tr>
                    <th style="width: 40px;">ITEM</th>
                    <th>PRODUCTO / LICOR</th>
                    <th class="text-right" style="width: 100px;">PRECIO UNIT.</th>
                    <th class="text-right" style="width: 80px;">CANTIDAD</th>
                    <th class="text-right" style="width: 100px;">TOTAL</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($venta->detalles as $index => $detalle)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $detalle->producto->nombre }}</td>
                        <td class="text-right">S/ {{ number_format($detalle->precio_unitario, 2) }}</td>
                        <td class="text-right">{{ $detalle->cantidad }}</td>
                        <td class="text-right">S/ {{ number_format($detalle->total, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <table class="totals-table">
            <tr>
                <td>Subtotal:</td>
                <td class="text-right">S/ {{ number_format($venta->subtotal, 2) }}</td>
            </tr>
            <tr>
                <td>IGV (18%):</td>
                <td class="text-right">S/ {{ number_format($venta->igv, 2) }}</td>
            </tr>
            @if ($venta->descuento > 0)
                <tr>
                    <td>Descuento:</td>
                    <td class="text-right text-danger">- S/ {{ number_format($venta->descuento, 2) }}</td>
                </tr>
            @endif
            <tr style="font-weight: bold; font-size: 14px; border-top: 1px solid #333;">
                <td>TOTAL:</td>
                <td class="text-right">S/ {{ number_format($venta->total, 2) }}</td>
            </tr>
            @if ($venta->monto_recibido > 0)
                <tr style="font-size: 11px; color: #666;">
                    <td>Efectivo Recibido:</td>
                    <td class="text-right">S/ {{ number_format($venta->monto_recibido, 2) }}</td>
                </tr>
                <tr style="font-size: 11px; color: #666;">
                    <td>Vuelto:</td>
                    <td class="text-right">S/ {{ number_format($venta->vuelto, 2) }}</td>
                </tr>
            @endif
        </table>

        <div style="clear: both;"></div>

        <div style="margin-top: 50px; text-align: center; color: #999; font-size: 10px;">
            <p>Representación impresa de una {{ $venta->tipo_comprobante }} electrónica.</p>
            <p>¡Gracias por su compra!</p>
        </div>
    </div>
</body>

</html>
