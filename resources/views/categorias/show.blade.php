@extends('layouts.app')
@section('titulo', $categoria->nombre)

@section('contenido')
<div class="container py-5">

    <nav class="mb-4" style="font-size:.82rem">
        <a href="{{ route('home') }}" class="text-secondary text-decoration-none">Inicio</a>
        <span class="text-secondary mx-2">›</span>
        <span class="text-white">{{ $categoria->nombre }}</span>
    </nav>

    <h1 class="seccion-titulo">{{ $categoria->nombre }}</h1>

    @if($categoria->descripcion)
    <p class="text-secondary mb-4">{{ $categoria->descripcion }}</p>
    @endif

    @if($peliculas->isEmpty())
    <div class="text-center py-5 text-secondary">
        <i class="bi bi-inbox" style="font-size:3rem"></i>
        <p class="mt-3">No hay películas disponibles en esta categoría.</p>
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
