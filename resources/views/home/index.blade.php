@extends('layouts.app')
@section('titulo', 'Inicio')

@section('contenido')

{{-- ══ HERO ══ --}}
<section class="hero">
    <div class="container">
        <h1>Tu cine<span>.</span> En casa<span>.</span></h1>
        <p class="text-secondary mb-4" style="font-size:1.1rem">
            Más de 500 títulos en DVD, Blu-ray y 4K UHD. Entrega a todo Costa Rica.
        </p>
        <a href="{{ route('peliculas.index') }}" class="btn btn-danger btn-lg px-5">
            <i class="bi bi-collection-play me-2"></i>Ver catálogo completo
        </a>
    </div>
</section>

{{-- ══ PELÍCULAS DESTACADAS ══ --}}
@if($destacadas->isNotEmpty())
<section class="container py-5">
    <h2 class="seccion-titulo"><i class="bi bi-star-fill text-warning me-2"></i>Destacadas</h2>
    <div class="row row-cols-2 row-cols-sm-3 row-cols-lg-6 g-3">
        @foreach($destacadas as $pelicula)
        <div class="col">
            @include('components.pelicula-card', ['pelicula' => $pelicula])
        </div>
        @endforeach
    </div>
</section>
@endif

{{-- ══ CATEGORÍAS ══ --}}
<section class="py-5" style="background-color: var(--cine-gris)">
    <div class="container">
        <h2 class="seccion-titulo">🎭 Explorar por categoría</h2>
        <div class="row row-cols-2 row-cols-sm-3 row-cols-md-5 g-3">
            @foreach($categorias as $categoria)
            <div class="col">
                <a href="{{ route('categorias.show', $categoria) }}"
                   class="d-block text-decoration-none text-center p-3 rounded-3 h-100"
                   style="background-color: var(--cine-card); border: 1px solid var(--cine-borde);
                          transition: border-color .2s, background .2s;"
                   onmouseover="this.style.borderColor='var(--cine-rojo)'; this.style.background='#1a0510'"
                   onmouseout="this.style.borderColor='var(--cine-borde)'; this.style.background='var(--cine-card)'">
                    <i class="bi bi-{{ $categoria->icono }}" style="font-size:1.8rem; color: var(--cine-rojo)"></i>
                    <div class="fw-semibold text-white" style="font-size:.9rem">{{ $categoria->nombre }}</div>
                    <div class="text-secondary" style="font-size:.75rem">{{ $categoria->peliculas_count }} títulos</div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ══ NOVEDADES ══ --}}
<section class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="seccion-titulo mb-0">🆕 Novedades</h2>
        <a href="{{ route('peliculas.index') }}" class="text-danger text-decoration-none" style="font-size:.9rem">
            Ver todas <i class="bi bi-arrow-right"></i>
        </a>
    </div>
    <div class="row row-cols-2 row-cols-sm-4 g-3">
        @foreach($recientes->take(8) as $pelicula)
        <div class="col">
            @include('components.pelicula-card', ['pelicula' => $pelicula])
        </div>
        @endforeach
    </div>
</section>

@endsection
