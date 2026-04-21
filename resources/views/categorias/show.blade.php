@extends('layouts.app')
@section('titulo', $categoria->nombre)

@section('contenido')
<div class="container py-5">

    {{-- Mensaje de éxito (ej: película eliminada desde esta categoría) --}}
    @if(session('exito'))
    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert"
         style="background:rgba(25,135,84,.15); border-color:rgba(25,135,84,.4); color:#75b798">
        <i class="bi bi-check-circle me-2"></i>{{ session('exito') }}
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <nav class="mb-4" style="font-size:.82rem">
        <a href="{{ route('home') }}" class="text-secondary text-decoration-none">Inicio</a>
        <span class="text-secondary mx-2">›</span>
        <span class="text-white">{{ $categoria->nombre }}</span>
    </nav>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="seccion-titulo mb-0">{{ $categoria->nombre }}</h1>
        <a href="{{ route('peliculas.create') }}" class="btn btn-sm btn-outline-danger">
            <i class="bi bi-plus-circle me-1"></i>Agregar película
        </a>
    </div>

    @if($categoria->descripcion)
    <p class="text-secondary mb-4">{{ $categoria->descripcion }}</p>
    @endif

    @if($peliculas->isEmpty())
    <div class="text-center py-5 text-secondary">
        <i class="bi bi-inbox" style="font-size:3rem"></i>
        <p class="mt-3">No hay películas disponibles en esta categoría.</p>
        <a href="{{ route('peliculas.create') }}" class="btn btn-outline-danger mt-2">
            <i class="bi bi-plus-circle me-1"></i>Agregar la primera película
        </a>
    </div>
    @else
    <div class="row row-cols-2 row-cols-sm-3 row-cols-xl-5 g-3">
        @foreach($peliculas as $pelicula)
        <div class="col">
            @include('components.pelicula-card', ['pelicula' => $pelicula])
        </div>
        @endforeach
    </div>

    <div class="mt-4 d-flex justify-content-center">
        {{ $peliculas->links() }}
    </div>
    @endif
</div>
@endsection
