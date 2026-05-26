<div class="card mb-6">

    <div class="card-body">

        <form method="GET">

            <div class="row g-4">

                {{-- Tipo --}}
                <div class="col-md-4">

                    <label class="form-label">
                        Tipo Movimiento
                    </label>

                    <select name="tipo" id="tipo" class="form-select">

                        <option value="">
                            Todos
                        </option>

                        <option value="entrada" @selected(request('tipo') === 'entrada')>
                            Entrada
                        </option>

                        <option value="salida" @selected(request('tipo') === 'salida')>
                            Salida
                        </option>

                        <option value="ajuste" @selected(request('tipo') === 'ajuste')>
                            Ajuste
                        </option>
                    </select>

                </div>

                {{-- Motivo --}}
                <div class="col-md-4">

                    <label class="form-label">
                        Motivo
                    </label>

                    <select name="motivo" id="motivo" class="form-select">

                        <option value="">
                            Todos
                        </option>

                        <option value="venta" @selected(request('motivo') === 'venta')>
                            Venta
                        </option>

                        <option value="anulacion_venta" @selected(request('motivo') === 'anulacion_venta')>
                            Anulación Venta
                        </option>

                        <option value="compra" @selected(request('motivo') === 'compra')>
                            Compra
                        </option>

                        <option value="anulacion_compra" @selected(request('motivo') === 'anulacion_compra')>
                            Anulación Compra
                        </option>

                        <option value="ajuste" @selected(request('motivo') === 'ajuste')>
                            Ajuste
                        </option>

                    </select>

                </div>

                {{-- Botones --}}
                <div class="col-md-4 d-flex align-items-end gap-2">

                    <button type="submit" class="btn btn-primary">

                        <i class="ti ti-search"></i>

                        Filtrar

                    </button>

                    <a href="{{ route('reportes.movimientos-stock') }}" class="btn btn-label-secondary">

                        Limpiar

                    </a>

                </div>

            </div>

        </form>

    </div>

</div>
@section('page-script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const tipoSelect = document.getElementById('tipo');

            const motivoSelect = document.getElementById('motivo');

            const motivos = {

                entrada: [

                    {
                        value: 'compra',
                        text: 'Compra'
                    },

                    {
                        value: 'anulacion_venta',
                        text: 'Anulación Venta'
                    }
                ],

                salida: [

                    {
                        value: 'venta',
                        text: 'Venta'
                    },

                    {
                        value: 'anulacion_compra',
                        text: 'Anulación Compra'
                    }
                ],

                ajuste: [

                    {
                        value: 'ajuste',
                        text: 'Ajuste'
                    }
                ]
            };

            tipoSelect.addEventListener('change', function() {

                const tipo = this.value;

                motivoSelect.innerHTML = '';

                /*
                |--------------------------------------------------------------------------
                | OPTION TODOS
                |--------------------------------------------------------------------------
                */

                motivoSelect.innerHTML += `
                <option value="">
                    Todos
                </option>
            `;

                /*
                |--------------------------------------------------------------------------
                | SI NO HAY TIPO
                |--------------------------------------------------------------------------
                */

                if (!tipo) return;

                /*
                |--------------------------------------------------------------------------
                | CARGAR MOTIVOS
                |--------------------------------------------------------------------------
                */

                motivos[tipo].forEach(motivo => {

                    motivoSelect.innerHTML += `
                    <option value="${motivo.value}">
                        ${motivo.text}
                    </option>
                `;
                });
            });

        });
    </script>
@endsection
