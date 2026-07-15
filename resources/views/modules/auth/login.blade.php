@extends('layouts.login')

@section('titulo', $titulo)
@section('contenido')
    <div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner">
                <!-- Register -->
                <div class="card px-sm-6 px-0">
                    <div class="card-body">
                        <!-- Logo -->
                        <div class="app-brand justify-content-center">
                            <a href="index.html" class="app-brand-link gap-2">
                                <span class="app-brand-logo demo">
                                    <span class="text-primary">
                                    </span>
                                </span>
                                <span class="app-brand-text demo text-heading fw-bold">Económica</span>
                            </a>
                        </div>
                        <!-- /Logo -->
                        <p class="mb-6">Login de usuario</p>

                        <form class="needs-validation mb-6" novalidate method="POST" action="{{ route('logear') }}" id="loginForm">
                            @csrf
                            <div class="mb-6">
                                <label class="form-label" for="bs-validation-email">Email</label>
                                <input type="email" name="email" id="bs-validation-email" class="form-control"
                                    placeholder="Ingrese su correo" required />
                                <div class="invalid-feedback">Ingrese un correo valido</div>
                            </div>
                            <div class="mb-6 form-password-toggle">
                                <label class="form-label" for="bs-validation-password">Contraseña</label>
                                <div class="input-group input-group-merge">
                                    <input type="password" name="password" id="bs-validation-password" class="form-control"
                                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                        required />
                                    <span class="input-group-text cursor-pointer" id="basic-default-password4"><i
                                            class="icon-base bx bx-hide"></i></span>
                                </div>
                                <div class="invalid-feedback">Ingrese una contraseña.</div>
                            </div>
                            <div class="mb-8">
                                <div class="d-flex justify-content-between">
                                    <div class="form-check mb-0">
                                        {{-- <input class="form-check-input" type="checkbox" id="remember-me" /> --}}
                                        {{-- <label class="form-check-label" for="remember-me"> Remember Me </label> --}}
                                    </div>
                                    <a href="{{ route('password.request') }}">
                                        <span>Olvido su contraseña?</span>
                                    </a>
                                </div>
                            </div>
                            <div class="mb-6">
                                <button class="btn btn-primary d-grid w-100" type="submit">Login</button>
                            </div>
                        </form>

                        <div>
                            @if ($errors->any())
                                <p>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                </p>
                            @endif
                        </div>

                        {{-- <p class="text-center">
                <span>New on our platform?</span>
                <a href="auth-register-basic.html">
                  <span>Create an account</span>
                </a>
              </p> --}}
                    </div>
                </div>
                <!-- /Register -->
            </div>
        </div>
    </div>
@endsection
