            <div class="row g-6">

                {{-- Ventas Hoy --}}
                <div class="col-lg-3 col-sm-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div class="card-info">
                                    <p class="text-heading mb-1">Ventas Hoy</p>
                                    <div class="d-flex align-items-center mb-1">
                                        <h4 class="card-title mb-0 me-2">S/
                                            {{ number_format($ventasHoy, 2) }}</h4>
                                        {{-- <span class="text-success">(+29%)</span> --}}
                                    </div>
                                    <span>Total vendido hoy</span>
                                </div>
                                <div class="card-icon">
                                    <span class="badge bg-label-success rounded p-2">
                                        <i class='icon-base bx bx-calendar-event icon-lg'></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                {{-- Ventas Mes --}}
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div class="card-info">
                                    <p class="text-heading mb-1">Ventas Mes</p>
                                    <div class="d-flex align-items-center mb-1">
                                        <h4 class="card-title mb-0 me-2">S/
                                            {{ number_format($ventasMes, 2) }}</h4>
                                        {{-- <span class="text-success">(+18%)</span> --}}
                                    </div>
                                    <span>Total mensual</span>
                                </div>
                                <div class="card-icon">
                                    <span class="badge bg-label-info rounded p-2">
                                        <i class='icon-base bx bx-calendar-alt icon-lg'></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Productos vendidoy hoy --}}
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div class="card-info">
                                    <p class="text-heading mb-1">Productos vendidos</p>
                                    <div class="d-flex align-items-center mb-1">
                                        <h4 class="card-title mb-0 me-2">{{ $productosVendidosHoy }}</h4>
                                        {{-- <span class="text-success">(+18%)</span> --}}
                                    </div>
                                    <span>Total vendidos</span>
                                </div>
                                <div class="card-icon">
                                    <span class="badge bg-label-primary rounded p-2">
                                        <i class='icon-base bx bx-list-check icon-lg'></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Bajo Stock --}}

                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div class="card-info">
                                    <p class="text-heading mb-1">Bajo Stock</p>
                                    <div class="d-flex align-items-center mb-1">
                                        <h4 class="card-title mb-0 me-2">{{ $productosBajoStock }}</h4>
                                        {{-- <span class="text-success">(+18%)</span> --}}
                                    </div>
                                    <span>Productos críticos</span>
                                </div>
                                <div class="card-icon">
                                    <span class="badge bg-label-warning rounded p-2">
                                        <i class="icon-base bx bx-error icon-lg"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
