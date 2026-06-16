@extends('layouts.main')

@section('titulo', $titulo)
@section('content')
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="card">
                <div class="card-datatable text-nowrap">
                    <div class="dt-container dt-bootstrap5">
                        <div class="row card-header flex-column flex-md-row pb-0">
                            <div
                                class="d-md-flex justify-content-between align-items-center dt-layout-start col-md-auto me-auto mt-0">
                                <h5 class="card-title mb-0 text-md-start text-center">Compras</h5>
                                <div class="btn-group flex-wrap mb-0">
                                    <a href="{{ route('compras.create') }}" class="btn create-new btn-primary">
                                        <span class="d-flex align-items-center gap-2">
                                            <i class="icon-base bx bx-plus icon-sm"></i>
                                            <span class="d-none d-sm-inline-block">Nueva Compra</span>
                                        </span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <table class="dt-responsive table table-bordered dataTable dtr-column" id=""
                        style="width: 100%;">
                        <thead>
                            <tr>
                                <th></th>
                                <th>F. Pedido</th>
                                <th>Proveedor</th>
                                <th>Comprobante</th>
                                <th>Total</th>
                                <th>Estado</th>
                                <th>F. Entrega</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($compras as $item)
                                <tr>
                                    <td></td>
                                    <td>
                                        <span
                                            class="fw-semibold">{{ \Carbon\Carbon::parse($item->fecha_pedido)->format('d/m/Y') }}</span>
                                        <br><small
                                            class="text-muted">{{ \Carbon\Carbon::parse($item->fecha_pedido)->format('h:i A') }}</small>
                                    </td>

                                    <td>{{ $item->proveedor->razon_social }}</td>

                                    <td>
                                        <span class="badge bg-label-secondary">
                                            {{ $item->numero_comprobante ?? 'Sin Comprobante' }}
                                        </span>
                                    </td>

                                    <td>S/ {{ number_format($item->total, 2) }}</td>

                                    <td>
                                        @if ($item->estado === 'pendiente')
                                            <span class="badge bg-label-warning">
                                                <i class="bx bx-time-five me-1"></i> Pendiente
                                            </span>
                                        @elseif($item->estado === 'recibida')
                                            <span class="badge bg-label-success">
                                                <i class="bx bx-check-shield me-1"></i> Recibida
                                            </span>
                                        @else
                                            <span class="badge bg-label-danger">
                                                <i class="bx bx-x-circle me-1"></i> Anulada
                                            </span>
                                        @endif
                                    </td>

                                    <td>
                                        @if ($item->fecha_entrega)
                                            <span>{{ \Carbon\Carbon::parse($item->fecha_entrega)->format('d/m/Y') }}</span>
                                            <br><small
                                                class="text-muted">{{ \Carbon\Carbon::parse($item->fecha_entrega)->format('h:i A') }}</small>
                                        @else
                                            <span class="text-muted">--:--</span>
                                        @endif
                                    </td>

                                    <td class="text-center">
                                        <div class="d-flex justify-content-center align-items-center gap-1">
                                            <a href="{{ route('compras.show', $item->id) }}"
                                                class="btn btn-sm btn-icon btn-label-secondary" title="Ver Detalle">
                                                <i class="bx bx-show-alt"></i>
                                            </a>

                                            @if ($item->estado === 'pendiente')
                                                <button type="button"
                                                    class="btn btn-sm btn-icon btn-label-success btn-recibir"
                                                    title="Recibir Mercadería en Almacén" data-bs-toggle="modal"
                                                    data-bs-target="#modalRecibirCompra"
                                                    data-url="{{ route('compras.anular', $item->id) }}"
                                                    data-id="{{ $item->id }}"
                                                    data-proveedor="{{ $item->proveedor->razon_social }}"
                                                    data-total="S/ {{ number_format($item->total, 2) }}">
                                                    <i class="bx bx-package"></i>
                                                </button>
                                            @endif

                                            @if ($item->estado !== 'anulada')
                                                <form action="{{ route('compras.anular', $item->id) }}" method="POST"
                                                    class="d-inline"
                                                    onsubmit="return confirm('¿Seguro que deseas anular esta compra? El stock revertido se descontará.')">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="btn btn-sm btn-icon btn-label-danger"
                                                        title="Anular Compra">
                                                        <i class="bx bx-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th></th>
                                <th>F. Pedido</th>
                                <th>Proveedor</th>
                                <th>Comprobante</th>
                                <th>Total</th>
                                <th>Estado</th>
                                <th>F. Entrega</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

        </div>
    </div>

    @include('modules.compras.modals.recibir-compra-modal')
@endsection
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('modalRecibirCompra');
            const form = document.getElementById('formRecibirCompra');

            if (modal && form) {
                modal.addEventListener('show.bs.modal', function(event) {
                    // SEGURIDAD: Bootstrap guarda el botón original en event.relatedTarget
                    let button = event.relatedTarget;

                    // Si por alguna razón el navegador detectó el clic en el ícono interno, 
                    // escalamos al botón contenedor más cercano de forma forzada:
                    if (button.tagName !== 'BUTTON') {
                        button = button.closest('button');
                    }

                    // Si a pesar de todo no hay botón válido, abortamos para evitar errores
                    if (!button) return;

                    // Extraemos los atributos data-* con total seguridad
                    const compraId = button.getAttribute('data-id');
                    const proveedor = button.getAttribute('data-proveedor');
                    const total = button.getAttribute('data-total');

                    // 1. Inyectamos los textos informativos dentro de las etiquetas correctas
                    // Nota el uso de innerHTML o textContent asegurando que limpien lo anterior
                    document.getElementById('lbl-modal-proveedor').textContent = proveedor ??
                        'No especificado';
                    document.getElementById('lbl-modal-total').textContent = total ?? 'S/ 0.00';

                    // 2. Cambiamos el action del formulario de forma forzada a la ruta PUT correcta
                    form.setAttribute('action', `/compras/${compraId}/recibir`);

                });
            }
        });
    </script>
@endpush
