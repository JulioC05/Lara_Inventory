@extends('layouts.main')

@section('titulo', $titulo)

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row invoice-add">

            <div class="col-lg-9 col-12 mb-lg-0 mb-6">
                <form action="{{ route('ventas.store') }}" method="POST" id="saleForm">
                    @csrf
                    <div class="card invoice-preview-card p-sm-12 p-6 invoice-perso">
                        <h4 class="mb-0">Nueva Venta</h4>
                        <div class="card-body px-0">
                            <div class="row">
                                <div class="col-md-12">

                                    <div class="col-md-9">
                                        <label class="form-label">Producto</label>

                                        <select id="productoSelect">

                                            <option value="">
                                            </option>

                                            @foreach ($productos as $producto)
                                                <option value="{{ $producto->id }}" data-nombre="{{ $producto->nombre }}"
                                                    data-codigo="{{ $producto->codigo_barras }}"
                                                    data-precio="{{ $producto->precio_venta }}"
                                                    data-stock="{{ $producto->stock }}"
                                                    data-imagen="{{ asset('storage/' . $producto->imagen) }}">
                                                    {{ $producto->nombre }}
                                                </option>
                                            @endforeach

                                        </select>

                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-check form-switch me-n2" style="padding-top: 1rem;">
                                            <input type="checkbox" class="form-check-input" id="scannerToggle">
                                            <label class="form-check-label" for="scannerToggle">Escanear productos</label>
                                        </div>
                                        <div class="scanner-container"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr class="mt-0 mb-6">

                        <div class="card-body pt-0 px-0">

                            <div class="mb-4">
                                <div class="repeater-wrapper pt-0 pt-md-1">
                                    <div class="border rounded position-relative pe-0">
                                        <div class="table-responsive mb-4">

                                            <table class="table table-bordered align-middle">

                                                <thead style="border-style: hidden;">
                                                    <th>Producto</th>
                                                    <th width="120">Cantidad</th>
                                                    <th width="150">Precio</th>
                                                    <th width="150">Subtotal</th>
                                                    <th width="50">Acción</th>
                                                    </tr>
                                                </thead>

                                                <tbody id="carritoBody" style="border-style: hidden;">

                                                </tbody>

                                            </table>

                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="row justify-content-end">

                            <div class="col-md-4">

                                <div class="card border">

                                    <div class="card-body">

                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Subtotal:</span>
                                            <span id="subtotalText">S/ 0.00</span>
                                        </div>

                                        <div class="d-flex justify-content-between mb-2">
                                            <span>IGV:</span>
                                            <span id="igvText">S/ 0.00</span>
                                        </div>

                                        <div class="d-flex justify-content-between fw-bold">
                                            <span>Total:</span>
                                            <span id="totalText">S/ 0.00</span>
                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                        {{-- INPUTS HIDDEN --}}
                        <input type="hidden" name="subtotal" id="subtotalInput">
                        <input type="hidden" name="igv" id="igvInput">
                        <input type="hidden" name="total" id="totalInput">

                        <div id="productosContainer"></div>

                        {{-- BOTONES --}}
                        <div class="mt-4 d-flex justify-content-end gap-2">

                            <a href="{{ route('ventas.index') }}" class="btn btn-outline-secondary">
                                Cancelar
                            </a>

                            <button type="submit" class="btn btn-success">
                                Finalizar Venta
                            </button>

                        </div>
                    </div>
            </div>
            <div class="col-lg-3 col-12 invoice-actions">
                <div class="card mb-6">
                    <div class="card-body">
                        <x-forms.select name="cliente_id" label="Cliente">
                            {{-- <option value="">Seleccione</option> --}}

                            @foreach ($clientes as $cliente)
                                <option value="{{ $cliente->id }}">
                                    {{ $cliente->nombre }}
                                </option>
                            @endforeach
                            </x-select>
                            <x-forms.select name="tipo_comprobante" label="Tipo Comprobante">
                                <option value="boleta">Boleta</option>
                                <option value="factura">Factura</option>
                                </x-select>
                                <x-forms.select name="metodo_pago_id" label="Método de Pago">
                                    @foreach ($metodosPago as $metodo)
                                        <option value="{{ $metodo->id }}">
                                            {{ $metodo->nombre }}
                                        </option>
                                    @endforeach
                                    </x-select>
                                    {{-- <div class="d-flex justify-content-between mb-2">
                            <label for="payment-terms">Payment Terms</label>
                            <div class="form-check form-switch me-n2">
                                <input type="checkbox" class="form-check-input" id="payment-terms" checked="">
                            </div>
                        </div> --}}
                                    </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@push('scripts')
    
    @include('modules.ventas.scripts.ventas-pos-script')
    @include('modules.ventas.scripts.pos-qrscanner-script')

@endpush
