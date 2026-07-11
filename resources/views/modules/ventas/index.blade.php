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
                                <h5 class="card-title mb-0 text-md-start text-center">Ventas</h5>
                                <div class="btn-group flex-wrap mb-0">
                                    <a href="{{ route('ventas.create') }}" class="btn create-new btn-primary">
                                        <span class="d-flex align-items-center gap-2">
                                            <i class="icon-base bx bx-plus icon-sm"></i>
                                            <span class="d-none d-sm-inline-block">Nueva venta</span>
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
                                <th>#</th>
                                <th>Comprobante</th>
                                <th>Cliente</th>
                                <th>Usuario</th>
                                <th>Total</th>
                                <th>Estado</th>
                                <th>Fecha</th>
                                <th width="120">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($ventas as $venta)
                                <tr>
                                    <td></td>
                                    <td>
                                        {{ $venta->numero_comprobante }}
                                    </td>
                                    <td>
                                        {{ ucfirst($venta->tipo_comprobante) }}
                                    </td>
                                    <td>
                                        @if ($venta->cliente)
                                            @if ($venta->cliente->tipo_persona === 'juridica')
                                                {{ $venta->cliente->razon_social }}
                                            @else
                                                {{ $venta->cliente->nombre_completo }}
                                            @endif
                                        @else
                                            Cliente Varios
                                        @endif
                                    </td>
                                    <td>
                                        {{ $venta->usuario->name }}
                                    </td>
                                    <td>
                                        S/ {{ number_format($venta->total, 2) }}
                                    </td>
                                    <td>
                                        @if ($venta->estado === 'completada')
                                            <span class="badge bg-label-success">
                                                Completada
                                            </span>
                                        @else
                                            <span class="badge bg-label-danger">
                                                Anulada
                                            </span>
                                        @endif

                                    </td>
                                    <td>
                                        {{ $venta->fecha_venta }}
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('ventas.show', $venta) }}" class="btn btn-sm btn-info">
                                                Ver
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th></th>
                                <th>#</th>
                                <th>Comprobante</th>
                                <th>Cliente</th>
                                <th>Usuario</th>
                                <th>Total</th>
                                <th>Estado</th>
                                <th>Fecha</th>
                                <th width="120">Acciones</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

        </div>
    </div>
@endsection
