@extends('layouts.main')

@section('titulo', $titulo)
@section('content')
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">

            @include('modules.dashboard.cards')

            @include('modules.dashboard.charts.ventas-chart')

            <div class="row mt-6">
                <div class="col-xl-6">

                    @include('modules.dashboard.tables.top-productos')

                </div>
                <div class="col-xl-6">

                    @include('modules.dashboard.tables.movimientos-stock')

                </div>
            </div>
        </div>
    </div>
@endsection
@section('page-script')

    @include('modules.dashboard.scripts.ventas-chart-script')

@endsection
