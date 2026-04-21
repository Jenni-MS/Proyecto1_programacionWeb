@extends('layouts.app')
@section('titulo', 'Agregar Película')

@section('contenido')
<div class="container py-5" style="max-width:860px">

    {{-- Breadcrumb --}}
    <nav class="mb-4" style="font-size:.82rem">
        <a href="{{ route('home') }}" class="text-secondary text-decoration-none">Inicio</a>
        <span class="text-secondary mx-2">›</span>
        <a href="{{ route('peliculas.index') }}" class="text-secondary text-decoration-none">Catálogo</a>
        <span class="text-secondary mx-2">›</span>
        <span class="text-white">Agregar película</span>
    </nav>

    <h1 class="seccion-titulo mb-4"><i class="bi bi-plus-circle me-2"></i>Agregar Película</h1>

    @if($errors->any())
    <div class="alert alert-danger mb-4" style="background:rgba(220,53,69,.15); border-color:rgba(220,53,69,.4); color:#ea868f">
        <i class="bi bi-exclamation-triangle me-2"></i><strong>Corrija los siguientes errores:</strong>
        <ul class="mb-0 mt-2">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('peliculas.store') }}">
        @csrf

        @include('peliculas._form', ['pelicula' => null, 'categorias' => $categorias, 'generos' => $generos, 'formatos' => $formatos, 'clasificaciones' => $clasificaciones, 'generosSeleccionados' => []])

        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-danger">
                <i class="bi bi-save me-1"></i>Guardar película
            </button>
            <a href="{{ route('peliculas.index') }}" class="btn btn-outline-secondary">Cancelar</a>
        </div>
    </form>
</div>
@endsection
