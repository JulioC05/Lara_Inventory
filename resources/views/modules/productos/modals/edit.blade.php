<!-- Default Modal -->
<div class="col-lg-4 col-md-6">

    <div class="mt-4">
        <!-- Modal -->
        <div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel1">Editar Producto</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form class="needs-validation" novalidate id="formEditar" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="row g-6">
                                <x-forms.select label="Categoría*" name="categoria_id" id="edit_categoria_id"
                                    validacion="Seleccione una categoria" bag="update" required class="col mb-0">
                                    <option value="">
                                        Seleccione
                                    </option>
                                    @foreach ($categorias as $categoria)
                                        <option value="{{ $categoria->id }}" @selected(old('categoria_id') == $categoria->id)>
                                            {{ $categoria->nombre }}
                                        </option>
                                    @endforeach
                                </x-forms.select>

                                <x-forms.select label="Marca*" name="marca_id" id="edit_marca_id"
                                    validacion="Seleccione una marca" bag="update" required class="col mb-0">
                                    <option value="">
                                        Seleccione
                                    </option>
                                    @foreach ($marcas as $marca)
                                        <option value="{{ $marca->id }}" @selected(old('marca_id') == $marca->id)>
                                            {{ $marca->nombre }}
                                        </option>
                                    @endforeach
                                </x-forms.select>
                            </div>
                            <div class="row g-6">
                                <x-forms.input label="Nombre*" name="nombre" id="edit_nombre" type="text"
                                    placeholder="Ingrese el nombre" validacion="Ingrese un nombre" bag="update"
                                    required class="col mb-0" />

                                <x-forms.input label="Código de barras" name="codigo_barras" id="edit_codigo_barras"
                                    type="text" placeholder="Ingrese el codigo de barras"
                                    validacion="Ingrese un codigo de barras" bag="update" class="col mb-0" />
                            </div>
                            <div class="row">
                                <x-forms.textarea label="Descripción" name="descripcion" id="edit_descripcion"
                                    rows="3" placeholder="Ingrese una descripción" bag="update" />
                            </div>
                            <div class="row g-6">
                                <x-forms.input label="Contenido (ml)" name="contenido_ml" id="edit_contenido_ml"
                                    type="number" placeholder="Ingrese el contenido" bag="update" class="col mb-0" />
                                <x-forms.input label="Graduación alcohólica (%)" name="graduacion_alcoholica"
                                    id="edit_graduacion_alcoholica" type="number" placeholder="Ingrese la graduación"
                                    bag="update" step="0.01" class="col mb-0" />
                            </div>

                            <div class="row g-6">
                                <x-forms.input label="Stock" name="stock" id="edit_stock" type="number"
                                    placeholder="Ingrese el stock" value="0" min="0"
                                    validacion="Ingrese un stock valido" bag="update" required class="col mb-0" />
                                <x-forms.input label="Stock mínimo" name="stock_minimo" id="edit_stock_minimo"
                                    value="5" min="0" type="number" placeholder="Ingrese el sotck"
                                    validacion="Ingrese el stock minimo valido" bag="update" required
                                    class="col mb-0" />
                            </div>
                            <div class="row g-6">
                                {{-- Precio de compra --}}
                                <x-forms.input label="Precio de compra" name="precio_compra" id="edit_precio_compra"
                                    step="0.01" min="0.00" type="number"
                                    placeholder="Ingrese el precio de compra"
                                    validacion="Ingrese un precio de compra valido" bag="update" required
                                    class="col mb-0" />
                                {{-- Margen de ganancia --}}
                                <x-forms.input label="Margen ganancia (%)" name="margen_ganancia"
                                    id="edit_margen_ganancia" step="0.01" min="0.00" type="number"
                                    placeholder="Ingrese el margen" validacion="Ingrese un margen valido"
                                    bag="update" required class="col mb-0" />
                                {{-- Precio de venta --}}
                                <x-forms.input label="Precio de venta" name="precio_venta" id="edit_precio_venta"
                                    step="0.01" min="0.00" type="number"
                                    placeholder="Ingrese el precio de venta"
                                    validacion="Ingrese un precio de venta valido" bag="update" required
                                    class="col mb-0" />
                            </div>
                            {{-- Imagen --}}
                            <div class="row g-6">
                                <div class="d-flex align-items-start align-items-sm-center gap-6 pb-4 border-bottom">
                                    <img src="" alt="product-avatar"
                                        class="d-block w-px-200 h-px-200 rounded" id="preview_edit_imagen">
                                    <div class="button-wrapper">
                                        <label for="edit_imagen" class="btn btn-primary me-3 mb-4" tabindex="0">
                                            <span class="d-none d-sm-block">Subir una nueva imagen</span>
                                            <i class="icon-base bx bx-upload d-block d-sm-none"></i>
                                            <x-forms.input label="" name="imagen" id="edit_imagen"
                                                type="file" validacion="Ingrese una imagen" bag="update"
                                                hidden="" class="account-file-input"
                                                accept="image/png, image/jpeg, image/jpg" />
                                        </label>
                                        <button type="button" id="btn_restore_imagen"
                                            class="btn btn-label-secondary account-image-reset mb-4">
                                            <i class="icon-base bx bx-reset d-block d-sm-none"></i>
                                            <span class="d-none d-sm-block">Reiniciar</span>
                                        </button>

                                        <div>Solo JPG, JPEG ó PNG. Tamaño maximo de 2MB</div>
                                    </div>
                                </div>
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

{{-- @push('scripts')
    <script>
        document
            .getElementById('btn_remove_imagen')
            .addEventListener('click', function() {

                const input =
                    document.getElementById('edit_imagen');

                const preview =
                    document.getElementById(
                        'preview_edit_imagen'
                    );

                if (imagen) {

                    preview.src = imagen;

                    preview.dataset.original = imagen;

                    preview.style.display = 'block';

                }
            });
    </script>
@endpush --}}
