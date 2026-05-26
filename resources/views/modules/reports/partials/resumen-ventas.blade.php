<div class="row mb-6">

    {{-- Total Ventas --}}
    <div class="col-md-6 col-xl-3">

        <div class="card">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <span class="text-heading">
                            Total Ventas
                        </span>

                        <h3 class="mb-1 mt-2">

                            S/
                            {{ number_format($totalVentas, 2) }}

                        </h3>

                    </div>

                    <div class="avatar">

                        <div class="avatar-initial bg-label-success rounded">

                            <i class="ti ti-cash ti-26px"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    {{-- Cantidad Ventas --}}
    <div class="col-md-6 col-xl-3">

        <div class="card">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <span class="text-heading">
                            Cantidad Ventas
                        </span>

                        <h3 class="mb-1 mt-2">

                            {{ $cantidadVentas }}

                        </h3>

                    </div>

                    <div class="avatar">

                        <div class="avatar-initial bg-label-primary rounded">

                            <i class="ti ti-shopping-cart ti-26px"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>
