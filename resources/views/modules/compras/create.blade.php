@extends('layouts.main')

@section('titulo', $titulo)

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        {{-- <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Compras /</span> Nueva Compra
        </h4> --}}

        <form action="{{ route('compras.store') }}" method="POST" id="form-compra">
            @csrf

            <div class="row">
                <div class="col-xl-4 col-lg-5 mb-4">
                    <div class="card h-100">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Datos del Pedido</h5>
                            <span class="badge bg-label-primary">Paso 1</span>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <x-forms.select label="Proveedor *" name="proveedor_id" id="proveedor_id"
                                    validacion="Seleccione un proveedor" bag="store" required>
                                    <option value="">-- Seleccionar Proveedor --</option>
                                    @foreach ($proveedores as $prov)
                                        <option value="{{ $prov->id }}"
                                            {{ old('proveedor_id') == $prov->id ? 'selected' : '' }}>
                                            {{ $prov->razon_social }} (RUC: {{ $prov->ruc }})
                                        </option>
                                    @endforeach
                                </x-forms.select>
                            </div>

                            <div class="mb-3">
                                <x-forms.select label="Método de Pago *" name="metodo_pago_id" id="metodo_pago_id"
                                    validacion="Seleccione el método de pago" bag="store" required>
                                    <option value="">-- Seleccionar Pago --</option>
                                    @foreach ($metodosPago as $mp)
                                        <option value="{{ $mp->id }}"
                                            {{ old('metodo_pago_id') == $mp->id ? 'selected' : '' }}>
                                            {{ $mp->nombre }}
                                        </option>
                                    @endforeach
                                </x-forms.select>
                            </div>

                            <div class="mb-3">
                                <x-forms.input label="N° Comprobante (Factura / Guía)" name="numero_comprobante"
                                    id="numero_comprobante" type="text" placeholder="Ej: F001-0001234"
                                    validacion="Ingrese un número válido" bag="store" />
                            </div>

                            <div class="row">
                                <div class="col-6 mb-3">
                                    <!-- date('Y-m-d\TH:i') genera el formato compatible con datetime-local (Ej: 2026-06-13T13:28) -->
                                    <x-forms.input label="F. y Hora Pedido *" name="fecha_pedido" id="fecha_pedido"
                                        type="datetime-local" value="{{ old('fecha_pedido', date('Y-m-d\TH:i')) }}"
                                        validacion="Fecha y hora requerida" bag="store" required />
                                </div>
                                <div class="col-6 mb-3">
                                    <x-forms.input label="F. y Hora Entrega" name="fecha_entrega" id="fecha_entrega"
                                        type="datetime-local" value="{{ old('fecha_entrega') }}" validacion="Fecha inválida"
                                        bag="store" />
                                </div>
                            </div>

                            <div class="mb-3">
                                <x-forms.select label="Estado de Recepción *" name="estado" id="estado"
                                    validacion="Seleccione un estado" bag="store" required>
                                    <option value="pendiente" {{ old('estado') == 'pendiente' ? 'selected' : '' }}>
                                        Pendiente (Solo Pedido)</option>
                                    <option value="recibida"
                                        {{ old('estado', 'recibida') == 'recibida' ? 'selected' : '' }}>Recibida (Ingresa
                                        al Stock)</option>
                                </x-forms.select>
                            </div>

                            <div class="mb-0">
                                <label class="form-label" for="observaciones">Observaciones / Incidencias</label>
                                <textarea class="form-control" name="observaciones" id="observaciones" rows="2"
                                    placeholder="Ej: Mercadería llega por transporte externo. Todo conforme.">{{ old('observaciones') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-8 col-lg-7">
                    <div class="card mb-4">
                        <div class="card-header pb-3">
                            <h5 class="mb-0">Agregar Artículos al Detalle</h5>
                        </div>
                        <div class="card-body">
                            <div class="row align-items-end g-3">
                                <div class="col-md-12">
                                    <label class="form-label" for="select_producto">Buscar Producto</label>
                                    <select id="productoSelect">
                                        <option value="">Buscar producto...</option>
                                        @foreach ($productos as $prod)
                                            <option value="{{ $prod->id }}" data-nombre="{{ $prod->nombre }}"
                                                data-codigo="{{ $prod->codigo_barras }}"
                                                data-precio="{{ $prod->precio_compra }}" data-stock="{{ $prod->stock }}"
                                                data-imagen="{{ $prod->imagen ? asset('storage/' . $prod->imagen) : asset('storage/images/default-product.jpg') }}">
                                                {{ $prod->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                {{-- <div class="col-md-3">
                                    <label class="form-label" for="input_cantidad">Cantidad</label>
                                    <input type="number" id="input_cantidad" class="form-control" min="1"
                                        value="1">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label" for="input_precio">Costo Unit. (S/)</label>
                                    <input type="number" id="input_precio" class="form-control" min="0"
                                        step="0.01" placeholder="0.00">
                                </div> --}}
                                <div class="form-check form-switch mb-2">
                                    <input class="form-check-input" type="checkbox" id="scannerToggle">
                                    <label class="form-check-label" for="chk_escanear">Escanear productos</label>
                                </div>
                                <div class="scanner-container">
                                </div>

                                <div id="productosContainer"></div>
                                {{-- <div class="col-12 text-end mt-3">
                                    <button type="button" id="btn-agregar-item"
                                        class="btn btn-label-primary w-100 w-md-auto">
                                        <i class="bx bx-plus me-1"></i> Añadir a la lista
                                    </button>
                                </div> --}}
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="table-responsive text-nowrap" style="min-height: 200px;">
                            <table class="table table-hover" id="tabla-detalles">
                                <thead>
                                    <tr>
                                        <th>Producto / Descripción</th>
                                        <th width="15%">Cantidad</th>
                                        <th width="15%">Precio Unit.</th>
                                        <th width="15%">Subtotal</th>
                                        <th width="10%" class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="table-border-bottom-0" id="tbody-detalles">
                                    <tr id="fila-vacia">
                                        <td colspan="5" class="text-center text-muted py-4">
                                            No has añadido ningún producto al pedido todavía.
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="card-footer border-top bg-light py-3">
                            <div class="row justify-content-end text-end g-2">
                                <div class="col-md-4 col-sm-6">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="fw-semibold text-muted">Subtotal (Sin IGV):</span>
                                        <span id="txt-subtotal">S/ 0.00</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="fw-semibold text-muted">IGV (18% incluido):</span>
                                        <span id="txt-igv">S/ 0.00</span>
                                    </div>
                                    <hr class="my-2">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="h6 mb-0 font-weight-bold">Total a Pagar:</span>
                                        <span class="h5 mb-0 text-primary font-weight-bold" id="txt-total">S/ 0.00</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-end mt-4">
                        <a href="{{ route('compras.index') }}" class="btn btn-label-secondary me-2">Cancelar</a>
                        <button type="submit" class="btn btn-primary" id="btn-guardar-compra" disabled>
                            <i class="bx bx-save me-1"></i> Procesar Compra
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
@push('scripts')
    @include('modules.compras.scripts.compras-pos-script')
    @include('modules.compras.scripts.compras-qrscanner-script')
@endpush
