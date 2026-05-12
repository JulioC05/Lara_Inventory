<!-- Default Modal -->
<div class="col-lg-4 col-md-6">

    <div class="mt-4">
        <!-- Modal -->
        <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-sm" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="formEliminar" method="POST">
                        @csrf
                        @method('DELETE')
                        <div class="modal-body">
                            <div class="row">
                                <div class="col mb-6" style="text-align: center;">
                                    <h4 id="textoEliminar"></h4>
                                    <p>Esta acción no se puede deshacer</p>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer" style="justify-content: center;">
                            <button type="submit" class="btn btn-primary">Si, elimínalo</button>
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
