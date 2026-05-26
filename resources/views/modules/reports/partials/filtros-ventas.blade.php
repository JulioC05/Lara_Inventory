<div class="card mb-6">

    <div class="card-body">

        <form method="GET">

            <div class="row g-4">

                {{-- Fecha Inicio --}}
                <div class="col-md-3">

                    <label class="form-label">
                        Fecha Inicio
                    </label>

                    <input type="date" name="fecha_inicio" class="form-control" value="{{ request('fecha_inicio') }}">

                </div>

                {{-- Fecha Fin --}}
                <div class="col-md-3">

                    <label class="form-label">
                        Fecha Fin
                    </label>

                    <input type="date" name="fecha_fin" class="form-control" value="{{ request('fecha_fin') }}">

                </div>

                {{-- Estado --}}
                <div class="col-md-3">

                    <label class="form-label">
                        Estado
                    </label>

                    <select name="estado" class="form-select">

                        <option value="">
                            Todos
                        </option>

                        <option value="completada" @selected(request('estado') === 'completada')>
                            Completada
                        </option>

                        <option value="anulada" @selected(request('estado') === 'anulada')>
                            Anulada
                        </option>

                    </select>

                </div>

                {{-- Tipo --}}
                <div class="col-md-3">

                    <label class="form-label">
                        Comprobante
                    </label>

                    <select name="tipo_comprobante" class="form-select">

                        <option value="">
                            Todos
                        </option>

                        <option value="boleta" @selected(request('tipo_comprobante') === 'boleta')>
                            Boleta
                        </option>

                        <option value="factura" @selected(request('tipo_comprobante') === 'factura')>
                            Factura
                        </option>

                    </select>

                </div>

                {{-- Botones --}}
                <div class="col-12 d-flex gap-2">

                    <button type="submit" class="btn btn-primary">

                        <i class="ti ti-search"></i>

                        Filtrar

                    </button>

                    <a href="{{ route('reportes.ventas') }}" class="btn btn-label-secondary">

                        Limpiar

                    </a>

                </div>

            </div>

        </form>

    </div>

</div>
