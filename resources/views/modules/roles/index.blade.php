@extends('layouts.main')

@section('titulo', $titulo)
@section('content')
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            {{-- <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Seguridad /</span> Roles y Accesos del Sistema</h4> --}}

            <div class="card">
                <div class="card-header border-bottom">
                    <h5 class="card-title mb-0">Matriz de Control de Accesos</h5>
                    <small class="text-muted">Vista general de lo que puede realizar cada rol en los módulos de la
                        licorería</small>
                </div>
                <table class="table table-bordered dtr-column">
                    <thead class="table-light">
                        <tr>
                            <th class="text-nowrap fw-bold" style="font-size: 0.9rem;">MÓDULO / SECCIÓN</th>
                            @foreach ($roles as $rol)
                                <th class="text-center text-nowrap fw-bold text-primary" style="font-size: 0.9rem;">
                                    <i class="bx bx-user-check me-1"></i> {{ $rol->name }}
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($matrizModulos as $modulo => $accesos)
                            <tr>
                                <td class="text-nowrap fw-semibold text-heading">{{ $modulo }}</td>

                                @foreach ($roles as $rol)
                                    <td class="text-center">
                                        @php
                                            $permisoValor = $accesos[$rol->name] ?? '❌';
                                        @endphp

                                        @if ($permisoValor === 'CRUD')
                                            <span class="badge bg-label-primary fw-bold px-3">CRUD</span>
                                        @elseif($permisoValor === '✅')
                                            <span class="badge bg-label-success px-3"><i
                                                    class="bx bx-check font-medium-2"></i> Acceso</span>
                                        @elseif($permisoValor === 'C/E/A')
                                            <span class="badge bg-label-warning fw-bold px-3" data-bs-toggle="tooltip"
                                                data-bs-placement="top"
                                                title="Crear, Editar y Activar/Desactivar (Sin Eliminación)">
                                                <i class="bx bx-edit-alt me-1"></i> C / E / A
                                            </span>
                                        @elseif($permisoValor === 'Ver')
                                            <span class="badge bg-label-info fw-bold px-3">Solo Ver</span>
                                        @else
                                            <span class="badge bg-label-secondary text-muted px-3"><i
                                                    class="bx bx-x font-medium-2"></i> Denegado</span>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>
@endsection
