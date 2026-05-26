<div class="card h-100">

    <div class="card-header d-flex justify-content-between align-items-center">

        <div>

            <h5 class="card-title mb-1">
                Últimos Movimientos
            </h5>

            <p class="card-subtitle text-muted mb-0">
                Actividad reciente del inventario
            </p>

        </div>

    </div>

    <div class="card-body">

        <ul class="p-0 m-0">

            @forelse ($ultimosMovimientos as $movimiento)
                @php

                    $color = 'warning';
                    $icono = 'ti-adjustments';
                    $signo = '+';

                    switch ($movimiento->motivo) {
                        case 'venta':
                            $color = 'success';
                            $icono = 'ti-shopping-cart';
                            $signo = '-';

                            break;

                        case 'anulacion_venta':
                            $color = 'danger';
                            $icono = 'ti-rotate-clockwise';
                            $signo = '+';

                            break;

                        case 'compra':
                            $color = 'primary';
                            $icono = 'ti-truck-delivery';
                            $signo = '+';

                            break;

                        case 'anulacion_compra':
                            $color = 'danger';
                            $icono = 'ti-package-off';
                            $signo = '-';

                            break;

                        case 'ajuste':
                            $color = 'warning';
                            $icono = 'ti-adjustments';

                            break;
                    }

                @endphp

                <li class="d-flex align-items-center mb-4 pb-1">

                    {{-- Imagen --}}
                    <div class="avatar flex-shrink-0 me-4">

                        <img src="{{ asset('storage/' . $movimiento->producto->imagen) }}"
                            alt="{{ $movimiento->producto->nombre }}" class="rounded">

                    </div>

                    {{-- Información --}}
                    <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">

                        <div class="me-2">

                            <h6 class="mb-1">

                                {{ $movimiento->producto->nombre }}

                            </h6>

                            <small class="text-muted">

                                Motivo: {{ ucfirst($movimiento->motivo) }}

                            </small>

                        </div>

                        {{-- Badge --}}
                        <div class="d-flex align-items-center gap-2">

                            <span
                                class="
                                badge
                                bg-label-{{ $color }}
                            ">

                                <i class="ti {{ $icono }} me-1"></i>

                                {{ ucfirst($movimiento->tipo) }}
                                <small class="fw-mediumtext-{{ $color }}">

                                    {{ $signo }}

                                    {{ $movimiento->cantidad }}

                                </small>
                            </span>

                        </div>

                    </div>

                </li>

            @empty

                <li>

                    <span class="text-muted">

                        No hay movimientos registrados

                    </span>

                </li>
            @endforelse

        </ul>

    </div>

</div>
