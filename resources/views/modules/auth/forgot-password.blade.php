@extends('layouts.login')

@section('titulo', $titulo)
@section('contenido')
    <div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner">
                <div class="card px-sm-6 px-0">
                    <div class="card-body">
                        <p class="mb-6">🔑 Recuperar Contraseña</p>

                        @if (session('status'))
                            <div class="alert alert-success" role="alert">{{ session('status') }}</div>
                        @endif

                        <form class="needs-validation mb-6" novalidate method="POST" action="{{ route('password.email') }}">
                            @csrf
                            <div class="mb-6">
                                <label class="form-label" for="email">Ingresa tu Email registrado</label>
                                <input type="email" name="email" id="email" class="form-control" required
                                    placeholder="correo@laeconomica.com" />
                            </div>
                            <button class="btn btn-primary d-grid w-100 mb-3" type="submit">Generar Enlace</button>
                            <a href="{{ url('/') }}" class="d-flex align-items-center justify-content-center">Volver al
                                Login</a>
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
