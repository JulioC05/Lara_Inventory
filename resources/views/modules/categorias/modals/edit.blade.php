<!-- Default Modal -->
<div class="col-lg-4 col-md-6">

    <div class="mt-4">
        <!-- Modal -->
        <div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel1">Editar Categoria</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                     <form id="formEditar" method="POST">
                        @csrf
                         @method('PUT')
                        <div class="modal-body">
                            <div class="mb-6">
                                <label class="form-label" for="nombre">Nombre de la categoria</label>
                                <input type="text" class="form-control" id="editNombre" name="nombre"
                                    placeholder="Ron..." />
                            </div>
                            <button type="submit" class="btn btn-primary">Actualizar</button>
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
