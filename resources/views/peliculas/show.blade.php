@extends('layouts.app')
@section('titulo', $pelicula->titulo)

@section('contenido')

{{-- Mensaje de éxito --}}
@if(session('exito'))
<div class="alert alert-success alert-dismissible fade show mb-0 rounded-0" role="alert"
     style="background:rgba(25,135,84,.15); border-color:rgba(25,135,84,.4); color:#75b798">
    <i class="bi bi-check-circle me-2"></i>{{ session('exito') }}
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
</div>
@endif

{{-- ══ DETALLE HERO ══ --}}
<div class="py-5" style="background: linear-gradient(to right, var(--cine-oscuro) 50%, #1a0510)">
    <div class="container">

        {{-- Breadcrumb --}}
        <nav class="mb-4" style="font-size:.82rem">
            <a href="{{ route('home') }}" class="text-secondary text-decoration-none">Inicio</a>
            <span class="text-secondary mx-2">›</span>
            <a href="{{ route('peliculas.index') }}" class="text-secondary text-decoration-none">Catálogo</a>
            <span class="text-secondary mx-2">›</span>
            <span class="text-white">{{ $pelicula->titulo }}</span>
        </nav>

        <div class="row g-4">
            {{-- Portada --}}
            <div class="col-md-3 col-sm-4">
                <img src="{{ $pelicula->imagen_portada_url }}"
                     alt="{{ $pelicula->titulo }}"
                     class="img-fluid rounded-3 shadow"
                     style="border: 1px solid var(--cine-borde)">
            </div>

            {{-- Info --}}
            <div class="col-md-9 col-sm-8">
                {{-- Badges --}}
                <div class="d-flex flex-wrap gap-2 mb-3">
                    <span class="badge bg-secondary">{{ $pelicula->formato }}</span>
                    <span class="badge bg-secondary">{{ $pelicula->clasificacion }}</span>
                    <span class="badge bg-danger">
                        <a href="{{ route('categorias.show', $pelicula->categoria->slug) }}"
                           class="text-white text-decoration-none">{{ $pelicula->categoria->nombre }}</a>
                    </span>
                    @foreach($pelicula->generos as $genero)
                    <span class="badge" style="background-color: var(--cine-borde); color:#aaa">
                        {{ $genero->nombre }}
                    </span>
                    @endforeach
                </div>

                <h1 style="font-family:'Bebas Neue',sans-serif; font-size:clamp(2rem,5vw,3.5rem);
                            letter-spacing:2px; color:#fff; line-height:1.1">
                    {{ $pelicula->titulo }}
                </h1>

                @if($pelicula->titulo_original && $pelicula->titulo_original !== $pelicula->titulo)
                <p class="text-secondary fst-italic mb-2" style="font-size:.9rem">
                    {{ $pelicula->titulo_original }}
                </p>
                @endif

                <div class="d-flex flex-wrap gap-3 text-secondary mb-3" style="font-size:.88rem">
                    <span><i class="bi bi-camera-video me-1"></i>{{ $pelicula->director }}</span>
                    <span><i class="bi bi-calendar me-1"></i>{{ $pelicula->anio_lanzamiento }}</span>
                    <span><i class="bi bi-clock me-1"></i>{{ $pelicula->duracion_minutos }} min</span>
                    <span><i class="bi bi-globe me-1"></i>{{ $pelicula->idioma_original }}</span>
                </div>

                {{-- Calificaciones --}}
                <div class="d-flex gap-3 mb-4">
                    @if($pelicula->calificacion_imdb)
                    <div class="text-center px-3 py-2 rounded-3"
                         style="background:rgba(255,193,7,.15); border:1px solid rgba(255,193,7,.3)">
                        <div class="text-warning fw-bold fs-5">{{ $pelicula->calificacion_imdb }}</div>
                        <div class="text-warning" style="font-size:.7rem; opacity:.7">IMDb</div>
                    </div>
                    @endif
                    <div class="text-center px-3 py-2 rounded-3"
                         style="background:rgba(220,53,69,.15); border:1px solid rgba(220,53,69,.3)">
                        <div class="text-danger fw-bold fs-5">{{ $pelicula->calificacion_local }}</div>
                        <div class="text-danger" style="font-size:.7rem; opacity:.7">CineStore</div>
                    </div>
                </div>

                <p class="text-secondary lh-lg mb-4" style="max-width:600px">
                    {{ $pelicula->sinopsis ?? $pelicula->descripcion }}
                </p>

                @if($pelicula->idiomas_disponibles)
                <p class="text-secondary mb-4" style="font-size:.85rem">
                    <span class="text-white">Idiomas:</span>
                    {{ implode(', ', $pelicula->idiomas_disponibles) }}
                    @if($pelicula->subtitulos)
                    &nbsp;·&nbsp;
                    <span class="text-white">Subtítulos:</span>
                    {{ implode(', ', $pelicula->subtitulos) }}
                    @endif
                </p>
                @endif

                {{-- Precio --}}
                <div class="d-flex flex-wrap align-items-center gap-3 mb-3">
                    <div>
                        <span class="text-danger fw-bold" style="font-size:2rem">
                            {{ $pelicula->precio_formateado }}
                        </span>
                        @if($pelicula->precio_alquiler)
                        <span class="text-secondary ms-2" style="font-size:.85rem">
                            o alquiler ₡{{ number_format($pelicula->precio_alquiler, 0, ',', '.') }}
                        </span>
                        @endif
                    </div>
                </div>

                <p class="mb-4" style="font-size:.8rem; color:#555">
                    {{ $pelicula->stock > 0 ? "✅ {$pelicula->stock} unidades en stock" : '❌ Sin stock' }}
                </p>

                {{-- ══ ACCIONES CRUD ══ --}}
                <div class="d-flex flex-wrap gap-2 pt-3" style="border-top:1px solid var(--cine-borde)">
                    <a href="{{ route('peliculas.edit', $pelicula) }}"
                       class="btn btn-sm btn-outline-warning">
                        <i class="bi bi-pencil-square me-1"></i>Editar película
                    </a>
                    <a href="{{ route('peliculas.confirm-delete', $pelicula) }}"
                       class="btn btn-sm btn-outline-danger">
                        <i class="bi bi-trash me-1"></i>Eliminar película
                    </a>
                    <a href="{{ route('peliculas.create') }}"
                       class="btn btn-sm btn-outline-light">
                        <i class="bi bi-plus-circle me-1"></i>Agregar película
                    </a>
                    <a href="{{ route('peliculas.index') }}"
                       class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i>Volver al catálogo
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ══ RELACIONADAS ══ --}}
@if($relacionadas->isNotEmpty())
<section class="container py-5" style="border-top:1px solid var(--cine-borde)">
    <h2 class="seccion-titulo">También te puede interesar</h2>
    <div class="row row-cols-2 row-cols-sm-4 g-3">
        @foreach($relacionadas as $rel)
        <div class="col">
            @include('components.pelicula-card', ['pelicula' => $rel])
        </div>
        @endforeach
    </div>
</section>
@endif

@endsection
