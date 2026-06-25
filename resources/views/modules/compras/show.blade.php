@extends('layouts.main')

@section('titulo', $titulo)
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center py-3 mb-4">
            <h4 class="fw-bold mb-0">
                <span class="text-muted fw-light">Compras /</span> Detalle de la Compra N° #{{ $compra->id }}
            </h4>
            <div class="d-flex align-content-center flex-wrap gap-2">
                <a href="{{ route('compras.pdf', $compra->id) }}" class="btn btn-primary">
                    <i class="bx bx-file me-1"></i> Descargar PDF
                </a>
                <a href="{{ route('compras.index') }}" class="btn btn-label-secondary">
                    <i class="bx bx-arrow-back me-1"></i> Volver al Historial
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card invoice-preview-card">
                    <div class="card-body border-bottom">
                        <div class="d-flex justify-content-between flex-xl-row flex-column p-sm-3 p-0">
                            <div class="mb-xl-0 mb-4">
                                <div class="d-flex svg-illustration mb-3 gap-2">
                                    <span class="app-brand-text fw-bolder text-primary h4 mb-0">TU LICORERÍA</span>
                                </div>
                                <p class="mb-1">Control de Entrada de Almacén</p>
                                <p class="mb-0">Usuario Responsable: <strong>{{ $compra->usuario->name }}</strong></p>
                            </div>
                            <div>
                                <h4 class="fw-semibold mb-2">COMPRA #{{ $compra->id }}</h4>
                                <div class="mb-2">
                                    <span class="me-1">Estado:</span>
                                    @if ($compra->estado === 'pendiente')
                                        <span class="badge bg-label-warning">Pendiente</span>
                                    @elseif($compra->estado === 'recibida')
                                        <span class="badge bg-label-success">Recibida</span>
                                    @else
                                        <span class="badge bg-label-danger">Anulada</span>
                                    @endif
                                </div>
                                <div>
                                    <span class="me-1">N° Comprobante:</span>
                                    <span
                                        class="fw-semibold">{{ $compra->numero_comprobante ?? 'SIN COMPROBANTE (SÓLO PEDIDO)' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body border-bottom">
                        <div class="row p-sm-3 p-0">
                            <div class="col-xl-6 col-md-12 mb-xl-0 mb-4">
                                <h6 class="pb-2 fw-bold text-muted">DATOS DEL PROVEEDOR:</h6>
                                <p class="mb-1 fw-semibold text-primary" style="font-size: 1.1rem;">
                                    {{ $compra->proveedor->razon_social }}</p>
                                <p class="mb-1"><strong>RUC:</strong> {{ $compra->proveedor->ruc }}</p>
                                <p class="mb-1"><strong>Contacto:</strong>
                                    {{ $compra->proveedor->contacto_nombre ?? 'No asignado' }}</p>
                                <p class="mb-1"><strong>Teléfono:</strong> {{ $compra->proveedor->telefono ?? '-' }}</p>
                                <p class="mb-0"><strong>Dirección:</strong> {{ $compra->proveedor->direccion ?? '-' }}
                                </p>
                            </div>

                            <div class="col-xl-6 col-md-12">
                                <h6 class="pb-2 fw-bold text-muted">TIEMPOS Y LOGÍSTICA:</h6>
                                <table class="table table-sm table-borderless mb-0">
                                    <tbody>
                                        <tr>
                                            <td class="ps-0 py-1"><strong>Fecha de Pedido:</strong></td>
                                            <td class="py-1">
                                                {{ \Carbon\Carbon::parse($compra->fecha_pedido)->format('d/m/Y h:i A') }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="ps-0 py-1"><strong>Fecha de Entrega Real:</strong></td>
                                            <td class="py-1">
                                                {{ $compra->fecha_entrega ? \Carbon\Carbon::parse($compra->fecha_entrega)->format('d/m/Y h:i A') : 'No entregado físicamente aún' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="ps-0 py-1"><strong>Método de Pago Pactado:</strong></td>
                                            <td class="py-1"><span
                                                    class="badge bg-label-secondary">{{ $compra->metodoPago->nombre ?? 'Efectivo/Transferencia' }}</span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive text-nowrap">
                        <table class="table table-hover border-bottom mb-0">
                            <thead>
                                <tr class="table-light">
                                    <th>Licor / Producto</th>
                                    <th class="text-center">Cantidad Solicitada</th>
                                    <th class="text-end">Costo Unitario</th>
                                    <th class="text-end">IGV Calculado (18%)</th>
                                    <th class="text-end">Total por Fila</th>
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0">
                                @foreach ($compra->detalles as $detalle)
                                    <tr>
                                        <td>
                                            <span
                                                class="fw-semibold text-dark">{{ $detalle->producto->nombre ?? 'Producto Eliminado' }}</span>
                                            @if ($detalle->producto->codigo_barras)
                                                <br><small class="text-muted"><i
                                                        class="bx bx-barcode me-1"></i>{{ $detalle->producto->codigo_barras }}</small>
                                            @endif
                                        </td>
                                        <td class="text-center fw-bold text-dark">{{ $detalle->cantidad }} u.</td>
                                        <td class="text-end">S/ {{ number_format($detalle->precio_unitario_compra, 2) }}
                                        </td>
                                        <td class="text-end text-muted">S/ {{ number_format($detalle->igv, 2) }}</td>
                                        <td class="text-end fw-semibold text-dark">S/
                                            {{ number_format($detalle->total, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="card-body font-size-sm">
                        <div class="row p-sm-3 p-0">
                            <div class="col-xl-7 col-md-6 mb-xl-0 mb-4">
                                <h6 class="fw-bold text-muted mb-2">OBSERVACIONES / COMENTARIOS DE AUDITORÍA:</h6>
                                <div class="p-3 bg-light rounded" style="min-height: 80px; border-left: 4px solid #696cff;">
                                    <p class="mb-0 text-dark italic">
                                        {{ $compra->observaciones ?? 'Sin anotaciones particulares registradas para esta transacción.' }}
                                    </p>
                                </div>
                            </div>

                            <div
                                class="col-xl-5 col-md-6 text-end d-flex flex-column justify-content-start align-items-end">
                                <div style="width: 280px;">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="fw-semibold text-muted">Subtotal (Afecto sin IGV):</span>
                                        <span class="text-dark">S/ {{ number_format($compra->subtotal, 2) }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="fw-semibold text-muted">IGV (18% Neto acumulado):</span>
                                        <span class="text-dark">S/ {{ number_format($compra->igv, 2) }}</span>
                                    </div>
                                    <hr class="my-2">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="h6 mb-0 font-weight-bold text-dark">Total Liquidado:</span>
                                        <span class="h5 mb-0 text-primary font-weight-bold">S/
                                            {{ number_format($compra->total, 2) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
