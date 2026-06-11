<!-- Default Modal -->
<div class="col-lg-4 col-md-6">

    <div class="mt-4">
        <!-- Modal -->
        <div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel1">Agregar Proveedores</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form class="needs-validation" novalidate action="{{ route('proveedores.store') }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="row g-6">
                                <x-forms.input label="RUC *" name="ruc" id="ruc" type="text"
                                    inputmode="numeric" placeholder="10XXXXXXXXX o 20XXXXXXXXX"
                                    validacion="Ingrese un RUC valido" bag="store" class="col mb-0" required />
                                <x-forms.input label="Razon Social *" name="razon_social" id="razon_social"
                                    type="text" placeholder="Ej. Distribuidora de Licores S.A.C."
                                    validacion="Ingrese una Razón Social" bag="store" class="col mb-0" required />
                            </div>
                            <div class="row g-6">
                                <x-forms.input label="Nombre del Contacto" name="contacto_nombre" id="contacto_nombre"
                                    type="text" placeholder="Ej. Juan Pérez" validacion="Ingrese un nombre valido"
                                    bag="store" class="col mb-0" />
                                <x-forms.input label="Teléfono / Celular" name="telefono" id="telefono" type="text"
                                    placeholder="Ej. 987654321" validacion="Ingrese un número valido" bag="store"
                                    class="col mb-0" />
                            </div>
                            <div class="row">
                                <x-forms.input label="Correo Electrónico" name="email" id="email" type="text"
                                    placeholder="ejemplo@proveedor.com" validacion="Ingrese un número valido"
                                    bag="store" />
                            </div>
                            <div class="row">
                                <x-forms.input label="Dirección" name="direccion" id="direccion" type="text"
                                    placeholder="Ej. Av. Las Flores 123, Lima" validacion="Ingrese una dirección valida"
                                    bag="store" />
                            </div>
                            <button type="submit" class="btn btn-primary">Guardar</button>
                            <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
                                Cancelar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
