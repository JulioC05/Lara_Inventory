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
                                <h5 class="card-title mb-0 text-md-start text-center">Proveedores</h5>
                                <div class="btn-group flex-wrap mb-0">
                                    <button type="button" data-bs-toggle="modal" data-bs-target="#createModal"
                                        class="btn create-new btn-primary">
                                        <span class="d-flex align-items-center gap-2">
                                            <i class="icon-base bx bx-plus icon-sm"></i>
                                            <span class="d-none d-sm-inline-block">Añadir Nuevo</span>
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <table class="dt-responsive table table-bordered dataTable dtr-column" id=""
                        style="width: 100%;">
                        <thead>
                            <tr>
                                <th></th>
                                <th>RUC</th>
                                <th>Razon Social</th>
                                <th>Contacto</th>
                                <th>Email</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($proveedores as $item)
                                <tr>
                                    <td></td>
                                    <td><strong>{{ $item->ruc }}</strong></td>
                                    <td>{{ $item->razon_social }}</td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span>{{ $item->contacto_nombre ?? 'No asignado' }}</span>
                                            <small class="text-muted">{{ $item->telefono ?? '-' }}</small>
                                        </div>
                                    </td>
                                    <td>{{ $item->email ?? '-' }}</td>
                                    <td>
                                        <span
                                            class="badge {{ $item->estado ? 'bg-label-primary' : 'bg-label-danger' }} me-1">
                                            {{ $item->estado ? 'Activo' : 'Inactivo' }}
                                        </span>
                                    </td>
                                    @role('Admin|Almacen')
                                        <td>
                                            <button type="button" data-bs-toggle="modal" data-bs-target="#editModal"
                                                data-url="{{ route('proveedores.update', $item->id) }}"
                                                data-id="{{ $item->id }}" data-ruc="{{ $item->ruc }}"
                                                data-razon_social="{{ $item->razon_social }}"
                                                data-contacto_nombre="{{ $item->contacto_nombre }}"
                                                data-telefono="{{ $item->telefono }}" data-email="{{ $item->email }}"
                                                data-direccion="{{ $item->direccion }}" class="btn btn-info btn-edit"><i
                                                    class="bx bx-edit fs-4"></i></button>
                                            <form action="{{ route('proveedores.estado', $item->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit"
                                                    class="btn {{ $item->estado ? 'btn-warning' : 'btn-success' }}">
                                                    <i class="bx {{ $item->estado ? 'bx-block' : 'bx-check' }} fs-4"></i>
                                                </button>
                                            </form>
                                            @role('Admin')
                                                <button type="button" data-bs-toggle="modal" data-bs-target="#deleteModal"
                                                    data-url="{{ route('proveedores.destroy', $item->id) }}"
                                                    data-nombre="{{ $item->nombre }}" class="btn btn-danger btn-delete"><i
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
                                <th>RUC</th>
                                <th>Razon Social</th>
                                <th>Contacto</th>
                                <th>Email</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            @include('modules.proveedores.modals.create')
            @include('modules.proveedores.modals.edit')
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
@endsection
