@extends('layouts.main')

@section('titulo', $titulo)
@section('content')
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">

            @include('modules.reports.partials.filtros-movimientos')

            @include('modules.reports.partials.tabla-movimientos')

        </div>
    </div>
@endsection
