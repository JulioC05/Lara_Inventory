@extends('layouts.login')

@section('titulo', $titulo)
@section('contenido')
    <div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner">
                <div class="card px-sm-6 px-0">
                    <div class="card-body">
                        <p class="mb-6">🔒 Restablecer Nueva Contraseña</p>

                        <form class="needs-validation mb-6" novalidate method="POST" action="{{ route('password.update') }}">
                            @csrf
                            <input type="hidden" name="token" value="{{ $token }}">

                            <div class="mb-6">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control"
                                    value="{{ $email ?? old('email') }}" required readonly />
                            </div>

                            <div class="mb-6">
                                <label class="form-label">Nueva Contraseña</label>
                                <input type="password" name="password" class="form-control"
                                    placeholder="Mínimo 8 caracteres" required />
                            </div>

                            <div class="mb-6">
                                <label class="form-label">Confirmar Contraseña</label>
                                <input type="password" name="password_confirmation" class="form-control"
                                    placeholder="Repita la contraseña" required />
                            </div>

                            <button class="btn btn-primary d-grid w-100" type="submit">Actualizar Contraseña</button>
                        </form>

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
