<!-- Default Modal -->
<div class="col-lg-4 col-md-6">

    <div class="mt-4">
        <!-- Modal -->
        <div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel1">Editar Marca</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form class="needs-validation" novalidate id="formEditar" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <x-forms.input 
                                label="Marca"
                                name="nombre"
                                id="editNombre"
                                type="text"
                                placeholder="Ingrese una marca"
                                validacion="Ingrese una marca"
                                bag="update"
                                required
                            />
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
