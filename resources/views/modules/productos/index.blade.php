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
                                <h5 class="card-title mb-0 text-md-start text-center">Productos</h5>
                                <div class="btn-group flex-wrap mb-0">
                                    @role('Admin|Almacen')
                                        <button type="button" data-bs-toggle="modal" data-bs-target="#createModal"
                                            class="btn create-new btn-primary">
                                            <span class="d-flex align-items-center gap-2">
                                                <i class="icon-base bx bx-plus icon-sm"></i>
                                                <span class="d-none d-sm-inline-block">Añadir Nuevo</span>
                                            </span>
                                        </button>
                                    @endrole
                                </div>
                            </div>
                        </div>
                    </div>
                    <table class="dt-responsive table table-bordered dataTable dtr-column" id=""
                        style="width: 100%;">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Imagen</th>
                                <th>Producto</th>
                                <th>Categoría</th>
                                <th>Marca</th>
                                <th>Stock</th>
                                <th>Precio de Compra</th>
                                <th>Precio de Venta</th>
                                <th>Estado</th>
                                @role('Admin|Almacen')
                                    <th width="150">Acciones</th>
                                @endrole
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($productos as $producto)
                                <tr>
                                    <td></td>
                                    <td>
                                        @if ($producto->imagen)
                                            <img src="{{ asset('storage/' . $producto->imagen) }}"
                                                alt="{{ $producto->nombre }}" width="60" class="rounded border">
                                            {{-- rounded-circle --}}
                                        @else
                                            <div class="bg-light d-flex align-items-center justify-content-center rounded border"
                                                style="width: 60px; height: 60px;">
                                                <i class="ti ti-photo text-muted"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="fw-semibold">
                                            {{ $producto->nombre }}
                                        </div>

                                        @if ($producto->codigo_barras)
                                            <small class="text-muted">
                                                {{ $producto->codigo_barras }}
                                            </small>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $producto->categoria->nombre }}
                                    </td>
                                    <td>
                                        {{ $producto->marca->nombre }}
                                    </td>

                                    <td>
                                        @if ($producto->stock <= $producto->stock_minimo)
                                            <span class="badge bg-label-danger">
                                                {{ $producto->stock }}
                                            </span>
                                        @else
                                            <span class="badge bg-label-success">
                                                {{ $producto->stock }}
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        S/ {{ number_format($producto->precio_compra, 2) }}
                                    </td>
                                    <td>
                                        S/ {{ number_format($producto->precio_venta, 2) }}
                                    </td>
                                    <td>
                                        <span
                                            class="badge {{ $producto->estado ? 'bg-label-primary' : 'bg-label-danger' }} me-1">
                                            {{ $producto->estado ? 'Activo' : 'Inactivo' }}
                                        </span>
                                    </td>
                                    @role('Admin|Almacen')
                                        <td>
                                            <button type="button" data-bs-toggle="modal" data-bs-target="#editModal"
                                                data-url="{{ route('productos.update', $producto->id) }}"
                                                data-id="{{ $producto->id }}"
                                                data-categoria_id="{{ $producto->categoria_id }}"
                                                data-marca_id="{{ $producto->marca_id }}"
                                                data-nombre="{{ $producto->nombre }}"
                                                data-codigo_barras="{{ $producto->codigo_barras }}"
                                                data-descripcion="{{ $producto->descripcion }}"
                                                data-contenido_ml="{{ $producto->contenido_ml }}"
                                                data-graduacion_alcoholica="{{ $producto->graduacion_alcoholica }}"
                                                data-imagen="{{ $producto->imagen ? asset('storage/' . $producto->imagen) : asset('images/default-product.png') }}"
                                                data-stock="{{ $producto->stock }}"
                                                data-stock_minimo="{{ $producto->stock_minimo }}"
                                                data-precio_compra="{{ $producto->precio_compra }}"
                                                data-margen_ganancia="{{ $producto->margen_ganancia }}"
                                                data-precio_venta="{{ $producto->precio_venta }}"
                                                class="btn btn-info btn-edit"><i class="bx bx-edit fs-4"></i></button>
                                            <form action="{{ route('productos.estado', $producto->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit"
                                                    class="btn {{ $producto->estado ? 'btn-warning' : 'btn-success' }}">
                                                    <i class="bx {{ $producto->estado ? 'bx-block' : 'bx-check' }} fs-4"></i>
                                                </button>
                                            </form>
                                            @role('Admin')
                                                <button type="button" data-bs-toggle="modal" data-bs-target="#deleteModal"
                                                    data-url="{{ route('productos.destroy', $producto->id) }}"
                                                    data-nombre="{{ $producto->nombre }}" class="btn btn-danger btn-delete"><i
                                                        class="bx bx-trash fs-4"></i></button>
                                            @endrole
                                        </td>
                                    @endrole
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th></th>
                                <th>Imagen</th>
                                <th>Producto</th>
                                <th>Categoría</th>
                                <th>Marca</th>
                                <th>Stock</th>
                                <th>Precio de Compra</th>
                                <th>Precio de Venta</th>
                                <th>Estado</th>
                                @role('Admin|Almacen')
                                    <th width="150">Acciones</th>
                                @endrole
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            @include('modules.productos.modals.create')
            @include('modules.productos.modals.edit')
            @include('components.modals.delete')

            @if ($errors->store->any())
                <script>
                    document.addEventListener('DOMContentLoaded', () => {
                        const modal = new bootstrap.Modal(
                            document.getElementById('createModal')
                        );
                        modal.show();
                    });
                    const inputs = document.querySelectorAll('.form-control');

                    inputs.forEach(input => {
                        input.addEventListener('input', function() {
                            this.classList.remove('is-invalid');
                        });
                    });
                </script>
            @endif

            @if ($errors->update->any())
                <script>
                    document.addEventListener('DOMContentLoaded', () => {
                        const modal = new bootstrap.Modal(
                            document.getElementById('editModal')
                        );
                        modal.show();
                    });
                    const inputs = document.querySelectorAll('.form-control');

                    inputs.forEach(input => {
                        input.addEventListener('input', function() {
                            this.classList.remove('is-invalid');
                        });
                    });
                </script>
            @endif
        </div>
    </div>
    @push('scripts')
        <script src="{{ asset('Sneat-Admin/assets/js/productos/productos-precios.js') }}"></script>
    @endpush
@endsection
