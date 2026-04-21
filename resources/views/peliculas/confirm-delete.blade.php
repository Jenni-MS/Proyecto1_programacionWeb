@extends('layouts.app')
@section('titulo', 'Eliminar: ' . $pelicula->titulo)

@section('contenido')
<div class="container py-5" style="max-width:600px">

    {{-- Breadcrumb --}}
    <nav class="mb-4" style="font-size:.82rem">
        <a href="{{ route('home') }}" class="text-secondary text-decoration-none">Inicio</a>
        <span class="text-secondary mx-2">›</span>
        <a href="{{ route('peliculas.index') }}" class="text-secondary text-decoration-none">Catálogo</a>
        <span class="text-secondary mx-2">›</span>
        <a href="{{ route('peliculas.show', $pelicula) }}" class="text-secondary text-decoration-none">{{ $pelicula->titulo }}</a>
        <span class="text-secondary mx-2">›</span>
        <span class="text-danger">Eliminar</span>
    </nav>

    <div class="text-center mb-4">
        <div style="font-size:4rem">🗑️</div>
        <h1 class="seccion-titulo mt-2">Confirmar eliminación</h1>
    </div>

    <div style="background:var(--cine-card); border:1px solid rgba(220,53,69,.4); border-radius:10px; padding:1.5rem"
         class="mb-4">

        <div class="d-flex gap-3 align-items-start">
            <img src="{{ $pelicula->imagen_portada_url }}"
                 alt="{{ $pelicula->titulo }}"
                 style="width:80px; border-radius:6px; border:1px solid var(--cine-borde); flex-shrink:0">
            <div>
                <h5 class="text-white mb-1">{{ $pelicula->titulo }}</h5>
                @if($pelicula->titulo_original && $pelicula->titulo_original !== $pelicula->titulo)
                <p class="text-secondary fst-italic mb-1" style="font-size:.85rem">{{ $pelicula->titulo_original }}</p>
                @endif
                <div class="d-flex flex-wrap gap-2 mb-2">
                    <span class="badge bg-danger">{{ $pelicula->categoria->nombre }}</span>
                    <span class="badge bg-secondary">{{ $pelicula->formato }}</span>
                    <span class="badge bg-secondary">{{ $pelicula->anio_lanzamiento }}</span>
                </div>
                <p class="text-secondary mb-0" style="font-size:.83rem">
                    <i class="bi bi-camera-video me-1"></i>{{ $pelicula->director }}
                    &nbsp;·&nbsp;
                    {{ $pelicula->duracion_minutos }} min
                </p>
            </div>
        </div>
    </div>

    <div class="alert mb-4" style="background:rgba(220,53,69,.12); border:1px solid rgba(220,53,69,.35); color:#ea868f; border-radius:8px">
        <i class="bi bi-exclamation-triangle me-2"></i>
        <strong>¿Está seguro?</strong> Esta película será eliminada del catálogo.
        Si la categoría <strong>{{ $pelicula->categoria->nombre }}</strong> tenía solo esta película,
        aparecerá vacía. La acción puede deshacerse desde la base de datos (soft delete).
    </div>

    <div class="d-flex gap-3 justify-content-center">
        <form method="POST" action="{{ route('peliculas.destroy', $pelicula) }}">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger px-4">
                <i class="bi bi-trash me-1"></i>Sí, eliminar película
            </button>
        </form>
        <a href="{{ route('peliculas.show', $pelicula) }}" class="btn btn-outline-secondary px-4">
            Cancelar
        </a>
    </div>

</div>
@endsection
