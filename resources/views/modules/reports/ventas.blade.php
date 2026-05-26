@extends('layouts.main')

@section('titulo', $titulo)
@section('content')
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">

            @include('modules.reports.partials.resumen-ventas')

            @include('modules.reports.partials.filtros-ventas')

            @include('modules.reports.partials.tabla-ventas')

        </div>
    </div>
@endsection
