@extends('layouts.app')
@section('titulo', 'Editar: ' . $pelicula->titulo)

@section('contenido')
<div class="container py-5" style="max-width:860px">

    {{-- Breadcrumb --}}
    <nav class="mb-4" style="font-size:.82rem">
        <a href="{{ route('home') }}" class="text-secondary text-decoration-none">Inicio</a>
        <span class="text-secondary mx-2">›</span>
        <a href="{{ route('peliculas.index') }}" class="text-secondary text-decoration-none">Catálogo</a>
        <span class="text-secondary mx-2">›</span>
        <a href="{{ route('peliculas.show', $pelicula) }}" class="text-secondary text-decoration-none">{{ $pelicula->titulo }}</a>
        <span class="text-secondary mx-2">›</span>
        <span class="text-white">Editar</span>
    </nav>

    <h1 class="seccion-titulo mb-4"><i class="bi bi-pencil-square me-2"></i>Editar Película</h1>

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

    <form method="POST" action="{{ route('peliculas.update', $pelicula) }}">
        @csrf
        @method('PUT')

        @include('peliculas._form', ['categorias' => $categorias, 'generos' => $generos, 'formatos' => $formatos, 'clasificaciones' => $clasificaciones])

        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-warning text-dark fw-semibold">
                <i class="bi bi-save me-1"></i>Guardar cambios
            </button>
            <a href="{{ route('peliculas.show', $pelicula) }}" class="btn btn-outline-secondary">Cancelar</a>
            <a href="{{ route('peliculas.confirm-delete', $pelicula) }}" class="btn btn-outline-danger ms-auto">
                <i class="bi bi-trash me-1"></i>Eliminar esta película
            </a>
        </div>
    </form>
</div>
@endsection
