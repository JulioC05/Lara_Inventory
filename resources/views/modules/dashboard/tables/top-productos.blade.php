<div class="row">

    <div class="col-12">

        <div class="card">

            <div class="card-header d-flex justify-content-between align-items-center">

                <div>

                    <h5 class="card-title mb-1">
                        Productos Más Vendidos
                    </h5>

                    <p class="card-subtitle text-muted mb-0">
                        Productos con mayor salida
                    </p>

                </div>

            </div>

            <div class="card-body">

                <ul class="p-0 m-0">

                    @forelse ($productosMasVendidos as $item)
                        <li class="d-flex align-items-center mb-4 pb-1">

                            {{-- Imagen --}}
                            <div class="avatar flex-shrink-0 me-4">

                                <img src="{{ asset('storage/' . $item->producto->imagen) }}"
                                    alt="{{ $item->producto->nombre }}" class="rounded">

                            </div>

                            {{-- Info --}}
                            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">

                                <div class="me-2">

                                    <h6 class="mb-0">
                                        {{ $item->producto->nombre }}
                                    </h6>

                                    <small class="text-muted">
                                        Stock:
                                        {{ $item->producto->stock }}
                                    </small>

                                </div>

                                {{-- Badge --}}
                                <div class="user-progress">

                                    <span class="badge bg-label-primary">

                                        {{ $item->total_vendidos }}
                                        vendidos

                                    </span>

                                </div>

                            </div>

                        </li>

                    @empty

                        <li>

                            <span class="text-muted">
                                No hay datos disponibles
                            </span>

                        </li>
                    @endforelse

                </ul>

            </div>

        </div>

    </div>

</div>
