<div class="card">

    <div class="card-header">

        <h5 class="card-title mb-0">
            Reporte Movimientos Stock
        </h5>

    </div>

    <div class="table-responsive">

        <table class="table table-hover">

            <thead>

                <tr>

                    <th>
                        Fecha
                    </th>

                    <th>
                        Producto
                    </th>

                    <th>
                        Tipo
                    </th>

                    <th>
                        Motivo
                    </th>

                    <th>
                        Cantidad
                    </th>

                    <th>
                        Stock Anterior
                    </th>

                    <th>
                        Stock Nuevo
                    </th>

                </tr>

            </thead>

            <tbody>

                @forelse ($movimientos as $movimiento)
                    @php

                        $color = 'warning';
                        $signo = '+';

                        switch ($movimiento->motivo) {
                            case 'venta':
                                $color = 'success';
                                $signo = '-';

                                break;

                            case 'anulacion_venta':
                                $color = 'danger';
                                $signo = '+';

                                break;

                            case 'compra':
                                $color = 'primary';
                                $signo = '+';

                                break;

                            case 'anulacion_compra':
                                $color = 'danger';
                                $signo = '-';

                                break;
                        }

                    @endphp

                    <tr>

                        {{-- Fecha --}}
                        <td>

                            <div class="d-flex flex-column">

                                <span>

                                    {{ $movimiento->created_at->format('d/m/Y') }}

                                </span>

                                <small class="text-muted">

                                    {{ $movimiento->created_at->format('H:i') }}

                                </small>

                            </div>

                        </td>

                        {{-- Producto --}}
                        <td>

                            <div class="d-flex align-items-center">

                                <div class="avatar avatar-sm me-3">

                                    <img src="{{ asset('storage/' . $movimiento->producto->imagen) }}" class="rounded">

                                </div>

                                <span class="fw-medium">

                                    {{ $movimiento->producto->nombre }}

                                </span>

                            </div>

                        </td>

                        {{-- Tipo --}}
                        <td>

                            <span
                                class="
                                badge
                                bg-label-{{ $color }}
                            ">

                                {{ ucfirst($movimiento->tipo_movimiento) }}

                            </span>

                        </td>

                        {{-- Motivo --}}
                        <td>

                            {{ str_replace('_', ' ', ucfirst($movimiento->motivo)) }}

                        </td>

                        {{-- Cantidad --}}
                        <td>

                            <span
                                class="
                                fw-medium
                                text-{{ $color }}
                            ">

                                {{ $signo }}{{ $movimiento->cantidad }}

                            </span>

                        </td>

                        {{-- Stock anterior --}}
                        <td>

                            {{ $movimiento->stock_anterior }}

                        </td>

                        {{-- Stock nuevo --}}
                        <td>

                            {{ $movimiento->stock_nuevo }}

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="7">

                            <div class="text-center py-6 text-muted">

                                No hay movimientos registrados

                            </div>

                        </td>

                    </tr>
                @endforelse

            </tbody>

        </table>

    </div>

    <div class="card-footer">

        {{ $movimientos->links() }}

    </div>

</div>
