<div class="modal fade" id="modalRecibirCompra" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form id="formRecibirCompra" action="" method="POST" class="modal-content">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title">Confirmar Recepción de Almacén</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="alert alert-warning mb-3" role="alert">
                    <h6 class="alert-heading mb-1 fw-bold">Atención Logística</h6>
                    <p class="mb-0 small">Confirmar este formulario incrementará el stock de licores de forma permanente
                        e irreversible en el sistema.</p>
                </div>

                <p class="mb-2"><strong>Proveedor:</strong> <span id="lbl-modal-proveedor" class="fw-semibold text-dark"></span></p>
                <p class="mb-3"><strong>Monto Total de Factura:</strong> <span id="lbl-modal-total"
                        class="text-primary fw-bold"></span></p>

                <div class="mb-3">
                    <x-forms.input label="Número de Comprobante / Factura *" name="numero_comprobante"
                        id="modal_numero_comprobante" type="text" placeholder="Ej: F001-0004589"
                        validacion="Ingrese el número de la factura" bag="update" required />
                </div>

                <div class="mb-3">
                    <x-forms.input label="Fecha y Hora de Entrega Real *" name="fecha_entrega" id="modal_fecha_entrega"
                        type="datetime-local" value="{{ date('Y-m-d\TH:i') }}" validacion="Fecha requerida"
                        bag="update" required />
                </div>

                <div class="mb-0">
                    <label class="form-label" for="modal_observaciones">Observaciones de Recepción</label>
                    <textarea class="form-control" name="observaciones" id="modal_observaciones" rows="2"
                        placeholder="Ej: Llegó completo. Todo conforme en el almacén."></textarea>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-success">Ingresar al Inventario</button>
            </div>
        </form>
    </div>
</div>
