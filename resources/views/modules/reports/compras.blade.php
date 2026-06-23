@extends('layouts.main')

@section('titulo', $titulo)
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Reportes /</span> Gestión de Compras</h4>

        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <span class="badge bg-label-success p-2 rounded"><i
                                        class="bx bx-dollar text-success"></i></span>
                            </div>
                        </div>
                        <span class="fw-semibold d-block mb-1">Inversión del Mes</span>
                        <h4 class="card-title mb-2">S/. {{ number_format($kpis->total_invertido ?? 0, 2) }}</h4>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <span class="badge bg-label-primary p-2 rounded"><i
                                        class="bx bx-cart text-primary"></i></span>
                            </div>
                        </div>
                        <span class="fw-semibold d-block mb-1">Órdenes Emitidas</span>
                        <h4 class="card-title mb-2">{{ $kpis->total_ordenes ?? 0 }}</h4>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <span class="badge bg-label-warning p-2 rounded"><i
                                        class="bx bx-time text-warning"></i></span>
                            </div>
                        </div>
                        <span class="fw-semibold d-block mb-1">Por Recibir</span>
                        <h4 class="card-title mb-2">{{ $kpis->ordenes_pendientes ?? 0 }}</h4>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <span class="badge bg-label-info p-2 rounded"><i class="bx bx-user text-info"></i></span>
                            </div>
                        </div>
                        <span class="fw-semibold d-block mb-1">Proveedor Top</span>
                        <h4 class="card-title mb-1 text-nowrap" style="font-size: 1.1rem;">
                            {{ $proveedorTop->razon_social ?? 'Ninguno' }}</h4>
                        <small class="text-muted">S/. {{ number_format($proveedorTop->total_proveedor ?? 0, 2) }}</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-lg-8 mb-4">
                <div class="card">
                    <div class="card-header header-elements">
                        <h5 class="card-title mb-0">Historial de Compras Recibidas (Año Actual)</h5>
                    </div>
                    <div class="card-body">
                        <div id="comprasAnualesChart"></div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-4 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Distribución por Categorías</h5>
                        <small class="text-muted">Mes actual</small>
                    </div>
                    <div class="card-body">
                        <div id="categoriasDonaChart"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <h5 class="card-header text-danger"><i class="bx bx-error-circle me-2"></i>Alerta de Variación de Costos</h5>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Proveedor</th>
                            <th>Costo Catálogo</th>
                            <th>Costo Facturado</th>
                            <th>Diferencia / Unidad</th>
                            <th>Comprobante</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @forelse($alertasPrecios as $alerta)
                            <tr>
                                <td><strong>{{ $alerta->producto }}</strong></td>
                                <td>{{ $alerta->proveedor }}</td>
                                <td>S/. {{ number_format($alerta->precio_catalogo, 2) }}</td>

                                <td class="{{ $alerta->tipo == 'subio' ? 'text-danger fw-bold' : 'text-success fw-bold' }}">
                                    S/. {{ number_format($alerta->precio_facturado, 2) }}
                                </td>

                                <td>
                                    @if ($alerta->tipo == 'subio')
                                        <span class="badge bg-label-danger">+S/.
                                            {{ number_format($alerta->diferencia, 2) }} ▲</span>
                                    @else
                                        <span class="badge bg-label-success">S/.
                                            {{ number_format($alerta->diferencia, 2) }} ▼</span>
                                    @endif
                                </td>
                                <td><small class="text-muted">{{ $alerta->numero_comprobante ?? 'S/N' }}</small></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">No se detectaron variaciones de precios
                                    este mes.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script> --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {

            // --- LÓGICA GRÁFICO ANUAL (BARRAS) ---
            var comprasAnualesData = @json($comprasMensualesAnual);
            var opcionesBarras = {
                chart: {
                    height: 320,
                    type: 'bar',
                    toolbar: {
                        show: false
                    }
                },
                plotOptions: {
                    bar: {
                        borderRadius: 4,
                        horizontal: false,
                        columnWidth: '50%'
                    }
                },
                dataLabels: {
                    enabled: false
                },
                series: [{
                    name: 'Total Compras',
                    data: comprasAnualesData
                }],
                colors: ['#696cff'], // Color púrpura original de Sneat
                xaxis: {
                    categories: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Set', 'Oct', 'Nov',
                        'Dic'
                    ]
                },
                yaxis: {
                    title: {
                        text: 'Soles (S/.)'
                    }
                }
            };
            var chartBarras = new ApexCharts(document.querySelector("#comprasAnualesChart"), opcionesBarras);
            chartBarras.render();

            // --- LÓGICA GRÁFICO DE DONA (CATEGORÍAS) ---
            var categoriasRaw = @json($categoriasDona);
            var labelsDona = categoriasRaw.map(item => item.categoria);
            var seriesDona = categoriasRaw.map(item => parseFloat(item.total_categoria));

            var opcionesDona = {
                chart: {
                    height: 320,
                    type: 'donut'
                },
                labels: labelsDona.length ? labelsDona : ['Sin Datos'],
                series: seriesDona.length ? seriesDona : [0],
                colors: ['#696cff', '#8592a3', '#71dd37', '#03c3ec', '#ff3e1d', '#ffab00'], // Paleta Sneat
                legend: {
                    position: 'bottom'
                },
                responsive: [{
                    breakpoint: 480,
                    options: {
                        chart: {
                            width: 200
                        },
                        legend: {
                            position: 'bottom'
                        }
                    }
                }]
            };
            var chartDona = new ApexCharts(document.querySelector("#categoriasDonaChart"), opcionesDona);
            chartDona.render();
        });
    </script>
@endpush
