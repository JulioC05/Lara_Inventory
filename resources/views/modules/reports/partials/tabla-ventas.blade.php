<div class="card">

    <div class="card-header">

        <h5 class="card-title mb-0">
            Reporte de Ventas
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
                        Comprobante
                    </th>

                    <th>
                        Cliente
                    </th>

                    <th>
                        Método Pago
                    </th>

                    <th>
                        Usuario
                    </th>

                    <th>
                        Total
                    </th>

                    <th>
                        Estado
                    </th>

                    <th>
                        Acción
                    </th>

                </tr>

            </thead>

            <tbody>

                @forelse ($ventas as $venta)
                    <tr>

                        {{-- Fecha --}}
                        <td>

                            <div class="d-flex flex-column">

                                <span>
                                    {{ Carbon\Carbon::parse($venta->fecha_venta)->format('d/m/Y') }}
                                </span>

                                <small class="text-muted">

                                    {{ Carbon\Carbon::parse($venta->fecha_venta)->format('H:i') }}

                                </small>

                            </div>

                        </td>

                        {{-- Comprobante --}}
                        <td>

                            <div class="d-flex flex-column">

                                <span class="fw-medium">

                                    {{ $venta->numero_comprobante }}

                                </span>

                                <small class="text-muted">

                                    {{ ucfirst($venta->tipo_comprobante) }}

                                </small>

                            </div>

                        </td>

                        {{-- Cliente --}}
                        <td>

                            {{ $venta->cliente?->nombre ?? 'Cliente General' }}

                        </td>

                        {{-- Metodo Pago --}}
                        <td>

                            {{ $venta->metodoPago->nombre }}

                        </td>

                        {{-- Usuario --}}
                        <td>

                            {{ $venta->usuario->name }}

                        </td>

                        {{-- Total --}}
                        <td>

                            <span class="fw-medium">
                                S/ {{ number_format($venta->total, 2) }}
                            </span>

                        </td>

                        {{-- Estado --}}
                        <td>

                            <span
                                class="
                                badge
                                bg-label-{{ $venta->estado === 'completada' ? 'success' : 'danger' }}
                            ">

                                {{ ucfirst($venta->estado) }}

                            </span>

                        </td>

                        {{-- Acción --}}
                        <td>

                            <a href="{{ route('ventas.show', ['venta' => $venta, 'redirect' => 'reportes']) }}"
                                class="btn btn-sm btn-info rounded-pill">

                                <i class="bx bxs-receipt fs-4"></i>

                            </a>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="8">

                            <div class="text-center py-6 text-muted">

                                No hay ventas registradas

                            </div>

                        </td>

                    </tr>
                @endforelse

            </tbody>

        </table>

    </div>

    {{-- Paginación --}}
    <div class="card-footer">

        {{ $ventas->links() }}

    </div>

</div>
