<!-- Default Modal -->
<div class="col-lg-4 col-md-6">

    <div class="mt-4">
        <!-- Modal -->
        <div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel1">Editar Usuario</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form class="needs-validation" novalidate id="formEditar" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="mb-6">
                                <label class="form-label" for="name">Nombre</label>
                                <input type="text" id="editName" name="edit_name"
                                    class="form-control {{ $errors->update->has('edit_name') ? 'is-invalid' : '' }}"
                                    placeholder="Ingrese el nombre" value="{{ old('edit_name') }}" required>
                                @if ($errors->update->has('edit_name'))
                                    <div class="invalid-feedback">
                                        {{ $errors->update->first('edit_name') }}
                                    </div>
                                @else
                                    <div class="invalid-feedback">
                                        Ingrese un nombre
                                    </div>
                                @endif
                            </div>
                            <div class="mb-6">
                                <label class="form-label" for="email">Email</label>
                                <input type="email" id="editEmail" name="edit_email"
                                    class="form-control {{ $errors->update->has('edit_email') ? 'is-invalid' : '' }}"
                                    placeholder="Ingrese el correo" value="{{ old('edit_email') }}" required />
                                @if ($errors->update->has('edit_email'))
                                    <div class="invalid-feedback">
                                        {{ $errors->update->first('edit_email') }}
                                    </div>
                                @else
                                    <div class="invalid-feedback">
                                        Ingrese un correo valido
                                    </div>
                                @endif
                            </div>
                            @php

                                $role = $item->getRoleNames()->first();

                            @endphp

                                <div class="mb-6" id="contenedorRole">
                                    <label class="form-label">
                                        Rol
                                    </label>
                                    <select name="edit_role" id="editRole"
                                        class="form-select {{ $errors->update->has('edit_role') ? 'is-invalid' : '' }}">
                                        <option value="Cajero" @selected($role === 'Cajero')>
                                            Cajero
                                        </option>
                                        <option value="Almacen" @selected($role === 'Almacen')>
                                            Almacen
                                        </option>
                                    </select>
                                    @if ($errors->update->has('edit_role'))
                                        <div class="invalid-feedback">
                                            {{ $errors->update->first('edit_role') }}
                                        </div>
                                    @else
                                        <div class="invalid-feedback">
                                            Ingrese un correo valido
                                        </div>
                                    @endif
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
