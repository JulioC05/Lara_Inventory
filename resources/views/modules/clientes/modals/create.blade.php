<!-- Default Modal -->
<div class="col-lg-4 col-md-6">

    <div class="mt-4">
        <!-- Modal -->
        <div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel1">Agregar Clientes</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form class="needs-validation" novalidate action="{{ route('clientes.store') }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="row">
                                <x-forms.select label="Tipo de Persona*" name="tipo_persona"
                                    validacion="Seleccione el tipo de persona" bag="store" required>
                                    <option value="natural">
                                        Natural
                                    </option>
                                    <option value="juridica">
                                        Juridica
                                    </option>
                                </x-forms.select>
                            </div>
                            <div class="row g-6">
                                <x-forms.select label="Tipo de Documento*" name="tipo_documento"
                                    validacion="Seleccione el tipo de documento" bag="store" required
                                    class="col mb-0">
                                    {{-- <option value="DNI">
                                        DNI
                                    </option>
                                    <option value="PASAPORTE">
                                        Pasaporte
                                    </option>
                                    <option value="CE">
                                        CE
                                    </option>
                                    <option value="RUC">
                                        RUC
                                    </option> --}}
                                </x-forms.select>
                                <x-forms.input label="Número de documento" name="numero_documento" id="numero_documento"
                                    type="text" inputmode="numeric" placeholder="Ingrese el número del documento"
                                    validacion="Ingrese un número valido" bag="store" class="col mb-0" />
                            </div>
                            <div id="campos-natural">
                                <div class="row g-6">
                                    {{-- Persona Natural --}}
                                    <x-forms.input label="Nombre*" name="nombre" id="nombre" type="text"
                                        placeholder="Ingrese una categoria" validacion="Ingrese un nombre valido"
                                        bag="store" class="col mb-0" />
                                    <x-forms.input label="Apellido" name="apellido" id="apellido" type="text"
                                        placeholder="Ingrese un apellido" validacion="Ingrese un apellido valido"
                                        bag="store" class="col mb-0" />
                                    {{-- Persona Natural --}}
                                </div>
                            </div>
                            <div id="campos-juridica">
                                <div class="row">
                                    {{-- Persona Juridica --}}
                                    <x-forms.input label="Razón Social" name="razon_social" id="razon_social"
                                        type="text" placeholder="Ingrese la Razón Social"
                                        validacion="Ingrese una Razón Social" bag="store" />
                                </div>
                                <div class="row g-6">
                                    <x-forms.input label="Nombre del representante" name="contacto_nombre"
                                        id="contacto_nombre" type="text"
                                        placeholder="Ingrese el nombre completo del representante"
                                        validacion="Ingrese un nombre valido" bag="store" class="col mb-0" />
                                    <x-forms.input label="Cargo del representante" name="contacto_cargo"
                                        id="contacto_cargo" type="text"
                                        placeholder="Ingrese el cargo del representante"
                                        validacion="Ingrese un cargo valido" bag="store" class="col mb-0" />
                                    {{-- Persona Juridica --}}
                                </div>
                            </div>
                            <div class="row g-6">
                                <x-forms.input label="Telefono" name="telefono" id="telefono" type="text"
                                    inputmode="numeric" placeholder="Ingrese el número de telefono"
                                    validacion="Ingrese un número de telefono valido" bag="store" class="col mb-0" />
                                <x-forms.input label="Correo" name="email" id="email" type="text"
                                    placeholder="Ingrese el correo" validacion="Ingrese un correo valido"
                                    bag="store" class="col mb-0" />
                            </div>
                            <div class="row">
                                <x-forms.input label="Dirección" name="direccion" id="direccion" type="text"
                                    placeholder="Ingrese la dirección" validacion="Ingrese una dirección valida"
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
