@extends('layouts.main')

@section('titulo', $titulo)

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        {{-- <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Configuración /</span> Mi Perfil</h4> --}}

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bx bx-check-circle me-1"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('info'))
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <i class="bx bx-info-circle me-1"></i>
                {{ session('info') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <h5 class="card-header">Detalles de la Cuenta</h5>

                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="card-body">
                            <div class="d-flex align-items-center align-items-sm-center gap-4">

                                <div id="avatarContainer" class="flex-shrink-0">
                                    @if (auth()->user()->avatar &&
                                            auth()->user()->avatar !== 'avatars/default.png' &&
                                            Storage::disk('public')->exists(auth()->user()->avatar))
                                        <img src="{{ asset('storage/' . $user->avatar) }}" alt="user-avatar"
                                            class="d-block rounded" height="100" width="100" id="uploadedAvatar"
                                            style="object-fit: cover;" />
                                    @else
                                        <div class="avatar avatar-xl d-block" id="avatarInitials"
                                            style="width: 100px; height: 100px;">
                                            <span
                                                class="avatar-initial rounded bg-label-primary fw-semibold fs-1 d-flex align-items-center justify-content-center w-100 h-100">
                                                {{ $user->iniciales }}
                                            </span>
                                        </div>
                                        <img id="uploadedAvatar" class="d-none rounded" height="100" width="100"
                                            style="object-fit: cover;" />
                                    @endif
                                </div>

                                <div class="button-wrapper">
                                    <label for="upload" class="btn btn-primary me-2 mb-3" tabindex="0">
                                        <span class="d-none d-sm-block">Subir nueva foto</span>
                                        <i class="bx bx-upload d-block d-sm-none"></i>
                                        <input type="file" id="upload" name="avatar" class="account-file-input"
                                            hidden accept="image/png, image/jpeg, image/jpg"
                                            onchange="previewImage(this)" />
                                    </label>

                                    <p class="text-muted mb-0">Permitido JPG, JPEG o PNG. Máximo de 2MB.</p>

                                    @error('avatar')
                                        <div class="text-danger mt-1" style="font-size: 13px;">{{ $message }}</div>
                                    @enderror
                                </div>

                            </div>
                        </div>

                        <hr class="my-0">

                        <div class="card-body">
                            <div class="row">
                                <div class="mb-3 col-md-6">
                                    <label for="name" class="form-label">Nombre Completo</label>
                                    <input class="form-control @error('name') is-invalid @enderror" type="text"
                                        id="name" name="name" value="{{ old('name', $user->name) }}" autofocus />
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3 col-md-6">
                                    <label for="email" class="form-label">Correo Electrónico (No modificable)</label>
                                    <input class="form-control bg-light" type="text" id="email"
                                        value="{{ $user->email }}" readonly data-bs-toggle="tooltip"
                                        title="El correo solo puede ser cambiado por el Administrador" />
                                </div>

                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Rol asignado en el sistema</label>
                                    <input class="form-control bg-light text-uppercase" type="text"
                                        value="{{ $user->getRoleNames()->first() ?? 'Empleado' }}" readonly />
                                </div>
                            </div>

                            <hr class="my-4">
                            <h5 class="mb-4">Seguridad y Contraseña <small class="text-muted">(Dejar en blanco si no
                                    deseas cambiarla)</small></h5>

                            <div class="row">
                                <div class="mb-3 col-md-4">
                                    <label for="current_password" class="form-label">Contraseña Actual</label>
                                    <input class="form-control @error('current_password') is-invalid @enderror"
                                        type="password" name="current_password" id="current_password"
                                        placeholder="••••••••" />
                                    @error('current_password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3 col-md-4">
                                    <label for="new_password" class="form-label">Nueva Contraseña</label>
                                    <input class="form-control @error('new_password') is-invalid @enderror" type="password"
                                        name="new_password" id="new_password" placeholder="••••••••" />
                                    @error('new_password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3 col-md-4">
                                    <label for="new_password_confirmation" class="form-label">Confirmar Nueva
                                        Contraseña</label>
                                    <input class="form-control" type="password" name="new_password_confirmation"
                                        id="new_password_confirmation" placeholder="••••••••" />
                                </div>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary me-2">Guardar Cambios</button>
                                <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">Cancelar</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function previewImage(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    var initialsDiv = document.getElementById('avatarInitials');
                    var imgTag = document.getElementById('uploadedAvatar');

                    // Si existía el bloque de iniciales, lo ocultamos
                    if (initialsDiv) {
                        initialsDiv.classList.add('d-none');
                    }

                    // Mostramos y actualizamos la etiqueta de imagen
                    imgTag.classList.remove('d-none');
                    imgTag.classList.add('d-block');
                    imgTag.src = e.target.result;
                }

                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endsection
