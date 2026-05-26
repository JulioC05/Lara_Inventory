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
                                <h5 class="card-title mb-0 text-md-start text-center">Usuarios</h5>
                                <div class="btn-group flex-wrap mb-0">
                                    <button type="button" data-bs-toggle="modal" data-bs-target="#createModal"
                                        class="btn create-new btn-primary">
                                        <span class="d-flex align-items-center gap-2">
                                            <i class="icon-base bx bx-user-plus icon-sm"></i>
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
                                <th>Nombre</th>
                                <th>Email</th>
                                <th>Rol</th>
                                <th>Estado</th>
                                <th>Fecha</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($usuarios as $item)
                                <tr>
                                    <td></td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->email }}</td>
                                    <td>{{ $item->getRoleNames()->first() }}</td>
                                    <td>
                                        <span
                                            class="badge {{ $item->activo ? 'bg-label-primary' : 'bg-label-danger' }} me-1">
                                            {{ $item->activo ? 'Activo' : 'Inactivo' }}
                                        </span>
                                    </td>
                                    <td>{{ $item->created_at }}</td>
                                    <td>
                                        <button type="button" data-bs-toggle="modal" data-bs-target="#editModal"
                                            data-url="{{ route('usuarios.update', $item->id) }}"
                                            data-name="{{ $item->name }}" data-email="{{ $item->email }}"
                                            data-role="{{ $item->getRoleNames()->first() }}"
                                            class="btn btn-info btn-edit-user"><i class="bx bx-edit fs-4"></i></button>
                                        @if (Auth::id() !== $item->id)
                                            <form action="{{ route('usuarios.estado', $item->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit"
                                                    class="btn {{ $item->activo ? 'btn-warning' : 'btn-success' }}">
                                                    <i class="bx {{ $item->activo ? 'bx-block' : 'bx-check' }} fs-4"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th></th>
                                <th>Nombre</th>
                                <th>Email</th>
                                <th>Rol</th>
                                <th>Estado</th>
                                <th>Fecha</th>
                                <th>Acciones</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            @include('modules.usuarios.modals.create')
            @include('modules.usuarios.modals.edit')
            {{-- @include('components.modals.delete') --}}

            {{-- @if ($errors->any())
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
            @endif --}}

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
