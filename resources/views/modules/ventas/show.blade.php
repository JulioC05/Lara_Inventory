@extends('layouts.main')

@section('titulo', $titulo)
@section('content')
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <div
                class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-6 row-gap-4">
                <div class="d-flex flex-column justify-content-center">
                    <div class="mb-1"><span class="h5"> {{ $venta->numero_comprobante }} </span>
                        @if ($venta->estado === 'completada')
                            <span class="badge bg-label-success me-1 ms-2">
                                Completada
                            </span>
                        @else
                            <span class="badge bg-label-danger me-1 ms-2">
                                Anulada
                            </span>
                        @endif
                        </span> <span class="badge bg-label-info">Usuario: {{ $venta->usuario->name }}</span>
                    </div>
                    <p class="mb-0">{{ $venta->fecha_venta }} </p>
                    {{-- <p class="mb-0">Aug 17, <span id="orderYear">2026</span>, 5:48 (ET)</p> --}}
                </div>
                <div class="d-flex align-content-center flex-wrap gap-2">
                    <a href="{{ route('ventas.pdf', $venta->id) }}" class="btn btn-primary">
                        <i class="bx bx-file me-1"></i> Descargar PDF
                    </a>

                    @if ($venta->estado === 'completada')
                        <form action="{{ route('ventas.destroy', $venta) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-label-danger delete-order">
                                Anular Venta
                            </button>
                        </form>
                    @endif
                </div>
            </div>
            {{-- Detalle de venta --}}
            <div class="row">
                <div class="col-12 col-lg-8">
                    <div class="card mb-6">
                        <div class="card-datatable table-responsive">
                            <div class="dt-container dt-bootstrap5 dt-empty-footer">
                                <div class="row card-header border-bottom mx-0 px-3">
                                    <div
                                        class="d-md-flex justify-content-between align-items-center dt-layout-start col-md-auto me-auto px-3">
                                        <h5 class="card-title mb-0">Detalle de venta</h5>
                                    </div>
                                </div>
                                <div class="justify-content-between dt-layout-table">
                                    <div
                                        class="d-md-flex justify-content-between align-items-center dt-layout-full table-responsive">
                                        <table class="datatables-order-details table dataTable dtr-column mb-0"
                                            id="" style="width: 100%;">
                                            <thead>
                                                <tr>
                                                    <th></th>
                                                    <th>Producto</th>
                                                    <th>Precio</th>
                                                    <th>Cantidad</th>
                                                    <th>Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($venta->detalles as $detalle)
                                                    <tr>
                                                        <td></td>
                                                        <td>
                                                            <div
                                                                class="d-flex justify-content-start align-items-center text-nowrap">
                                                                <div class="avatar-wrapper">
                                                                    <div class="avatar me-3">
                                                                        <img src="{{ asset('storage/' . $detalle->producto->imagen) }}"
                                                                            alt="{{ $detalle->producto->nombre }}"
                                                                            class="rounded-2">
                                                                    </div>
                                                                </div>
                                                                <div class="d-flex flex-column">
                                                                    <h6 class="text-body mb-0">
                                                                        {{ $detalle->producto->nombre }}</h6>
                                                                    {{-- <small>Storage:128gb</small> --}}
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            S/ {{ number_format($detalle->precio_unitario, 2) }}
                                                        </td>
                                                        <td>
                                                            {{ $detalle->cantidad }}
                                                        </td>
                                                        <td>
                                                            S/ {{ number_format($detalle->total, 2) }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                            </div>
                            <div class="d-flex justify-content-end align-items-center m-6 mb-2">
                                <div class="order-calculations">
                                    <div class="d-flex justify-content-start mb-2">
                                        <span class="w-px-100 text-heading">Subtotal:</span>
                                        <h6 class="mb-0">S/ {{ number_format($venta->subtotal, 2) }}</h6>
                                    </div>
                                    {{-- <div class="d-flex justify-content-start mb-2">
                                        <span class="w-px-100 text-heading">Discount:</span>
                                        <h6 class="mb-0">$2</h6>
                                    </div> --}}
                                    <div class="d-flex justify-content-start mb-2">
                                        <span class="w-px-100 text-heading">IGV:</span>
                                        <h6 class="mb-0">S/ {{ number_format($venta->igv, 2) }}</h6>
                                    </div>
                                    <div class="d-flex justify-content-start">
                                        <h6 class="w-px-100 mb-0">Total:</h6>
                                        <h6 class="mb-0">S/ {{ number_format($venta->total, 2) }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-4">
                    <div class="card mb-6">
                        <div class="card-header">
                            <h5 class="card-title m-0">Información del cliente</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-start align-items-center mb-6">
                                {{-- <div class="avatar me-3">
                                    <img src="../../assets/img/avatars/1.png" alt="Avatar" class="rounded-circle">
                                </div> --}}
                                <div class="d-flex flex-column">
                                    <a href="app-user-view-account.html" class="text-body text-nowrap">
                                        <h6 class="mb-0">{{ $venta->cliente->nombre }}</h6>
                                    </a>
                                    {{-- <span>Usuario: {{ $venta->usuario->name }}</span> --}}
                                </div>
                            </div>
                            <div class="d-flex justify-content-start align-items-center mb-6">
                                <span
                                    class="avatar rounded-circle bg-label-success me-3 d-flex align-items-center justify-content-center"><i
                                        class="icon-base bx bx-money icon-lg"></i></span>
                                <h6 class="text-nowrap mb-0">{{ $venta->metodoPago->nombre }}</h6>
                            </div>
                            <div class="d-flex justify-content-between">
                                <h6 class="mb-1">Contacto:</h6>
                                {{-- <h6 class="mb-1"><a href=" javascript:void(0)" data-bs-toggle="modal" data-bs-target="#editUser">Edit</a></h6> --}}
                            </div>
                            <p class=" mb-1">Correo: {{ $venta->cliente->email }}</p>
                            <p class=" mb-0">Telefono: {{ $venta->cliente->telefono }}</p>
                        </div>
                    </div>
                    <a href="{{ request('redirect') === 'reportes' ? route('reportes.ventas') : route('ventas.index') }}"
                        class="btn btn-secondary">
                        Volver
                    </a>
                </div>

            </div>

        </div>
    </div>
@endsection
