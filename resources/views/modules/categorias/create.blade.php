@extends('layouts.main')

@section('titulo', $titulo)
@section('content')
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Agregar Categoria </h5>
                    {{-- <small class="text-body float-end">Default label</small> --}}
                </div>
                <div class="card-body">
                    <form action="{{ route('categorias.store') }}" method="POST">
                        @csrf
                        <div class="mb-6">
                            <label class="form-label" for="nombre">Nombre de la categoria</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Ron..." />
                        </div>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                        <a href="{{ route('categorias.index') }}" type="submit" class="btn btn-secondary">Cancelar</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
