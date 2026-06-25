<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Orden de Compra - {{ $compra->numero_comprobante ?? 'OC-' . $compra->id }}</title>
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

        .bg-warning {
            background-color: #fff3e0;
            color: #ff9800;
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
                    <small>Módulo: Control de Abastecimiento (Almacén)</small>
                </td>
                <td style="width: 250px;">
                    <div class="comprobante-box">
                        <h3>NOTA DE INGRESO</h3>
                        <h4>ORDEN DE COMPRA</h4>
                        <h3>{{ $compra->numero_comprobante ?? 'OC-00' . $compra->id }}</h3>
                    </div>
                </td>
            </tr>
        </table>

        <div class="info-tab">
            <table class="header-table">
                <tr>
                    <td>
                        <strong>PROVEEDOR:</strong> {{ $compra->proveedor->razon_social }}<br>
                        <strong>RUC:</strong> {{ $compra->proveedor->ruc }}<br>
                        <strong>DIRECCIÓN:</strong> {{ $compra->proveedor->direccion ?? '-' }}<br>
                        <strong>CONTACTO:</strong> {{ $compra->proveedor->contacto_nombre ?? '-' }}
                    </td>
                    <td class="text-right" style="width: 250px;">
                        <strong>FECHA PEDIDO:</strong>
                        {{ \Carbon\Carbon::parse($compra->fecha_pedido)->format('d/m/Y H:i') }}<br>
                        <strong>FECHA ENTREGA:</strong>
                        {{ $compra->fecha_entrega ? \Carbon\Carbon::parse($compra->fecha_entrega)->format('d/m/Y') : 'Pendiente' }}<br>
                        <strong>MÉTODO PAGO:</strong> {{ $compra->metodoPago->nombre }}<br>
                        <strong>ESTADO:</strong>
                        @if ($compra->estado === 'recibida')
                            <span class="badge bg-success">RECIBIDA</span>
                        @elseif($compra->estado === 'pendiente')
                            <span class="badge bg-warning">PENDIENTE</span>
                        @else
                            <span class="badge bg-danger">ANULADA</span>
                        @endif
                    </td>
                </tr>
            </table>
        </div>

        <table class="details-table">
            <thead>
                <tr>
                    <th style="width: 40px;">ITEM</th>
                    <th>LICOR / PRODUCTO</th>
                    <th class="text-right" style="width: 90px;">COSTO UNIT.</th>
                    <th class="text-right" style="width: 60px;">CANT.</th>
                    <th class="text-right" style="width: 70px;">IGV</th>
                    <th class="text-right" style="width: 70px;">ISC</th>
                    <th class="text-right" style="width: 90px;">TOTAL</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($compra->detalles as $index => $detalle)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $detalle->producto->nombre }}</td>
                        <td class="text-right">S/ {{ number_format($detalle->precio_unitario_compra, 2) }}</td>
                        <td class="text-right">{{ $detalle->cantidad }}</td>
                        <td class="text-right">S/ {{ number_format($detalle->igv, 2) }}</td>
                        <td class="text-right">S/ {{ number_format($detalle->isc, 2) }}</td>
                        <td class="text-right">S/ {{ number_format($detalle->total, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <table class="totals-table">
            <tr>
                <td>Subtotal:</td>
                <td class="text-right">S/ {{ number_format($compra->subtotal, 2) }}</td>
            </tr>
            <tr>
                <td>IGV (18%):</td>
                <td class="text-right">S/ {{ number_format($compra->igv, 2) }}</td>
            </tr>
            <tr>
                <td>ISC Total:</td>
                <td class="text-right">S/ {{ number_format($compra->isc, 2) }}</td>
            </tr>
            <tr style="font-weight: bold; font-size: 14px; border-top: 1px solid #333;">
                <td>TOTAL COMPRA:</td>
                <td class="text-right">S/ {{ number_format($compra->total, 2) }}</td>
            </tr>
        </table>

        <div style="clear: both;"></div>

        @if ($compra->observaciones)
            <div style="margin-top: 20px; padding: 8px; border: 1px dashed #ccc; background: #fafafa;">
                <strong>Observaciones:</strong><br>
                <small style="color: #555;">{{ $compra->observaciones }}</small>
            </div>
        @endif

        <div style="margin-top: 60px; text-align: center; color: #999; font-size: 10px;">
            <p>Generado por el usuario: {{ $compra->usuario->name }} — Área de Logística e Inventarios.</p>
            <p>Documento interno de control de abastecimiento de mercadería.</p>
        </div>
    </div>
</body>

</html>
