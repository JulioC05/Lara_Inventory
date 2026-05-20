<div class="col-lg-4 col-md-6">
    <div class="mt-4">
        <div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel1">Agregar Producto</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form class="needs-validation" novalidate action="{{ route('productos.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="row g-6">
                                <x-forms.select label="Categoría*" name="categoria_id"
                                    validacion="Seleccione una categoria" bag="store" required class="col mb-0">
                                    <option value="">
                                        Seleccione
                                    </option>
                                    @foreach ($categorias as $categoria)
                                        <option value="{{ $categoria->id }}" @selected(old('categoria_id') == $categoria->id)>
                                            {{ $categoria->nombre }}
                                        </option>
                                    @endforeach
                                </x-forms.select>

                                <x-forms.select label="Marca*" name="marca_id" validacion="Seleccione una marca"
                                    bag="store" required class="col mb-0">
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
                                <x-forms.input label="Nombre*" name="nombre" id="nombre_store" type="text"
                                    placeholder="Ingrese el nombre" validacion="Ingrese un nombre" bag="store"
                                    required class="col mb-0" />

                                <x-forms.input label="Código de barras" name="codigo_barras" id="codigo_barras"
                                    type="text" placeholder="Ingrese el codigo de barras"
                                    validacion="Ingrese un codigo de barras" bag="store" class="col mb-0" />
                            </div>
                            <div class="row">
                                <x-forms.textarea label="Descripción" name="descripcion" rows="3"
                                    placeholder="Ingrese una descripción" bag="store" />
                            </div>
                            <div class="row g-6">
                                <x-forms.input label="Contenido (ml)" name="contenido_ml" id="contenido_ml"
                                    type="number" placeholder="Ingrese el contenido" bag="store" class="col mb-0" />
                                <x-forms.input label="Graduación alcohólica (%)" name="graduacion_alcoholica"
                                    id="graduacion_alcoholica" type="number" placeholder="Ingrese la graduación"
                                    bag="store" step="0.01" class="col mb-0" />
                            </div>
                            <div class="row g-6">
                                <x-forms.input label="Stock" name="stock" id="stock" type="number"
                                    placeholder="Ingrese el stock" value="0" min="0"
                                    validacion="Ingrese un stock valido" bag="store" required class="col mb-0" />
                                <x-forms.input label="Stock mínimo" name="stock_minimo" id="stock_minimo" value="5"
                                    min="0" type="number" placeholder="Ingrese el sotck"
                                    validacion="Ingrese el stock minimo valido" bag="store" required
                                    class="col mb-0" />
                            </div>
                            <div class="row g-6">
                                {{-- Precio de compra --}}
                                <x-forms.input label="Precio de compra" name="precio_compra" id="precio_compra"
                                    step="0.01" value="0.00" min="0.00" type="number"
                                    placeholder="Ingrese el precio de compra"
                                    validacion="Ingrese un precio de compra valido" bag="store" required
                                    class="col mb-0" />
                                {{-- Margen de ganancia --}}
                                <x-forms.input label="Margen ganancia (%)" name="margen_ganancia"
                                    id="margen_ganancia" step="0.01" value="20.00" min="0.00"
                                    type="number" placeholder="Ingrese el margen"
                                    validacion="Ingrese un margen valido" bag="store" required class="col mb-0" />
                                {{-- Precio de venta --}}
                                <x-forms.input label="Precio de venta" name="precio_venta" id="precio_venta"
                                    step="0.01" value="0.00" min="0.00" type="number"
                                    placeholder="Ingrese el precio de venta"
                                    validacion="Ingrese un precio de venta valido" bag="store" required
                                    class="col mb-0" />
                            </div>

                            <div class="row g-6">
                                <div class="d-flex align-items-start align-items-sm-center gap-6 pb-4 border-bottom">
                                    <img src="{{ asset('storage/images/default-product.jpg' ) }}" alt="product-avatar"
                                        class="d-block w-px-200 h-px-200 rounded" id="preview_create_imagen">
                                    <div class="button-wrapper">
                                        <label for="create_imagen" class="btn btn-primary me-3 mb-4" tabindex="0">
                                            <span class="d-none d-sm-block">Subir una nueva imagen</span>
                                            <i class="icon-base bx bx-upload d-block d-sm-none"></i>
                                            <x-forms.input label="" name="imagen" id="create_imagen"
                                                type="file" validacion="Ingrese una imagen" bag="update"
                                                hidden="" class="account-file-input"
                                                accept="image/png, image/jpeg, image/jpg" />
                                        </label>
                                        {{-- <button type="button" id="btn_restore_imagen"
                                            class="btn btn-label-secondary account-image-reset mb-4">
                                            <i class="icon-base bx bx-reset d-block d-sm-none"></i>
                                            <span class="d-none d-sm-block">Reiniciar</span>
                                        </button> --}}

                                        <div>Solo JPG, JPEG ó PNG. Tamaño maximo de 2MB</div>
                                    </div>
                                </div>
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
