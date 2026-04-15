{{-- Componente reutilizable: card de película --}}
{{-- Uso: @include('components.pelicula-card', ['pelicula' => $pelicula]) --}}

<article class="card-pelicula">
    {{-- Portada --}}
    <div class="portada-wrap position-relative">
        <a href="{{ route('peliculas.show', $pelicula) }}">
            <img src="{{ $pelicula->imagen_portada_url }}"
                 alt="{{ $pelicula->titulo }}"
                 loading="lazy">
        </a>
        <span class="badge-formato">{{ $pelicula->formato }}</span>
        @if($pelicula->destacado)
        <span class="badge-top">★ TOP</span>
        @endif
    </div>

    {{-- Cuerpo --}}
    <div class="card-body">
        <a href="{{ route('peliculas.show', $pelicula) }}" class="text-decoration-none">
            <h3 class="card-title">{{ $pelicula->titulo }}</h3>
        </a>
        <p class="text-secondary mb-1" style="font-size:.75rem">
            {{ $pelicula->director }} · {{ $pelicula->anio_lanzamiento }}
        </p>
        <div class="d-flex justify-content-between align-items-center mt-2 mb-2">
            <span class="precio">{{ $pelicula->precio_formateado }}</span>
            @if($pelicula->calificacion_imdb)
            <span class="text-warning" style="font-size:.78rem">
                ★ {{ $pelicula->calificacion_imdb }}
            </span>
            @endif
        </div>
    </div>
</article>
