<!-- Default Modal -->
<div class="col-lg-4 col-md-6">

    <div class="mt-4">
        <!-- Modal -->
        <div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel1">Agregar Usuario</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form class="needs-validation" novalidate action="{{ route('usuarios.store') }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-6">
                                <label class="form-label" for="name">Nombre</label>
                                <input type="text"
                                    class="form-control {{ $errors->store->has('store_name') ? 'is-invalid' : '' }}"
                                    id="name" name="store_name" placeholder="Ingrese el nombre"
                                    value="{{ old('store_name') }}" required>
                                @if ($errors->store->has('store_name'))
                                    <div class="invalid-feedback">
                                        {{ $errors->store->first('store_name') }}
                                    </div>
                                @else
                                    <div class="invalid-feedback">
                                        Ingrese un nombre
                                    </div>
                                @endif
                            </div>
                            <div class="mb-6">
                                <label class="form-label" for="email">Email</label>
                                <input type="email" name="store_email" id="email"
                                    class="form-control {{ $errors->store->has('store_email') ? 'is-invalid' : '' }}"
                                    value="{{ old('store_email') }}" placeholder="Ingrese el correo" required />
                                @if ($errors->store->has('store_email'))
                                    <div class="invalid-feedback">
                                        {{ $errors->store->first('store_email') }}
                                    </div>
                                @else
                                    <div class="invalid-feedback">
                                        Ingrese un correo valido
                                    </div>
                                @endif
                            </div>
                            <div class="mb-6 form-password-toggle">
                                <label class="form-label" for="password">Contraseña</label>
                                <div class="input-group input-group-merge">
                                    <input type="password" name="password" id="password"
                                        class="form-control input-pass-main {{ $errors->store->has('password') ? 'is-invalid' : '' }}"
                                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                        required />
                                    <span class="input-group-text cursor-pointer" id="basic-default-password4"><i
                                            class="icon-base bx bx-hide"></i></span>
                                </div>
                                @if ($errors->store->has('password'))
                                    <div class="invalid-feedback">
                                        {{ $errors->store->first('password') }}
                                    </div>
                                @else
                                    <div class="invalid-feedback">
                                        Ingrese una contraseña
                                    </div>
                                @endif
                            </div>
                            <div class="mb-6 form-password-toggle">
                                <label class="form-label" for="password_confirmation">Confirmar Contraseña</label>
                                <div class="input-group input-group-merge">
                                    <input type="password" name="password_confirmation" id="password_confirmation"
                                        class="form-control input-pass-confirm {{ $errors->store->has('password_confirmation') ? 'is-invalid' : '' }}"
                                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                        required />
                                    <span class="input-group-text cursor-pointer" id="basic-default-password4"><i
                                            class="icon-base bx bx-hide"></i></span>
                                </div>
                                @if ($errors->store->has('password_confirmation'))
                                    <div class="invalid-feedback">
                                        {{ $errors->store->first('password_confirmation') }}
                                    </div>
                                @else
                                    <div class="invalid-feedback">
                                        Las contraseñas debem coincidir
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
