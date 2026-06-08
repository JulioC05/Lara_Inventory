<!-- Default Modal -->
<div class="col-lg-4 col-md-6">

    <div class="mt-4">
        <!-- Modal -->
        <div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel1">Agregar Clientes</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form class="needs-validation" novalidate action="{{ route('clientes.store') }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <x-forms.input label="Nombre de la categoria" name="nombre" id="nombre_create"
                                type="text" placeholder="Ingrese una categoria" validacion="Ingrese una categoria"
                                bag="store" required />
                            <x-forms.select label="Categoría*" name="categoria_id" validacion="Seleccione una categoria"
                                bag="store" required class="col mb-0">
                                <option value="">
                                    Seleccione
                                </option>
                                @foreach ($categorias as $categoria)
                                    <option value="{{ $categoria->id }}" @selected(old('categoria_id') == $categoria->id)>
                                        {{ $categoria->nombre }}
                                    </option>
                                @endforeach
                            </x-forms.select>
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
