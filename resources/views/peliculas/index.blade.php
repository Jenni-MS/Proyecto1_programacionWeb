@extends('layouts.app')
@section('titulo', 'Catálogo')

@section('contenido')
<div class="container py-5">

    <h1 class="seccion-titulo"><i class="bi bi-camera-reels me-2"></i>Catálogo de Películas</h1>

    <div class="row g-4">

        {{-- ══ SIDEBAR FILTROS ══ --}}
        <div class="col-lg-3">
            <form method="GET" action="{{ route('peliculas.index') }}" class="sidebar-filtros">

                <p class="text-uppercase text-secondary fw-semibold mb-3"
                   style="font-size:.75rem; letter-spacing:1px">Filtros</p>

                {{-- Búsqueda --}}
                <div class="mb-3">
                    <label class="form-label">Buscar</label>
                    <input type="text" name="buscar" class="form-control input-busqueda"
                           placeholder="Título, director..."
                           value="{{ request('buscar') }}">
                </div>

                {{-- Categoría --}}
                <div class="mb-3">
                    <label class="form-label">Categoría</label>
                    <div class="d-flex flex-column gap-1">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="categoria"
                                   value="" id="cat_todas"
                                   {{ !request('categoria') ? 'checked' : '' }}>
                            <label class="form-check-label text-secondary" for="cat_todas">Todas</label>
                        </div>
                        @foreach($categorias as $cat)
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="categoria"
                                   value="{{ $cat->slug }}" id="cat_{{ $cat->slug }}"
                                   {{ request('categoria') === $cat->slug ? 'checked' : '' }}>
                            <label class="form-check-label text-secondary" for="cat_{{ $cat->slug }}">
                                {{ $cat->nombre }}
                            </label>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Formato --}}
                <div class="mb-3">
                    <label class="form-label">Formato</label>
                    <select name="formato" class="form-select">
                        <option value="">Todos</option>
                        @foreach($formatos as $fmt)
                        <option value="{{ $fmt }}" {{ request('formato') === $fmt ? 'selected' : '' }}>
                            {{ $fmt }}
                        </option>
                        @endforeach
                    </select>
                </div>

                {{-- Precio --}}
                <div class="mb-3">
                    <label class="form-label">Precio (₡)</label>
                    <div class="d-flex gap-2">
                        <input type="number" name="precio_min" class="form-control input-busqueda"
                               placeholder="Mín" value="{{ request('precio_min') }}">
                        <input type="number" name="precio_max" class="form-control input-busqueda"
                               placeholder="Máx" value="{{ request('precio_max') }}">
                    </div>
                </div>

                {{-- Ordenar --}}
                <div class="mb-4">
                    <label class="form-label">Ordenar por</label>
                    <select name="orden" class="form-select">
                        <option value="reciente"    {{ request('orden','reciente') === 'reciente'    ? 'selected':'' }}>Más reciente</option>
                        <option value="titulo"      {{ request('orden') === 'titulo'      ? 'selected':'' }}>Título A–Z</option>
                        <option value="precio_asc"  {{ request('orden') === 'precio_asc'  ? 'selected':'' }}>Precio ↑</option>
                        <option value="precio_desc" {{ request('orden') === 'precio_desc' ? 'selected':'' }}>Precio ↓</option>
                        <option value="calificacion"{{ request('orden') === 'calificacion'? 'selected':'' }}>Mejor calificados</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-danger w-100 mb-2">
                    <i class="bi bi-funnel me-1"></i>Aplicar filtros
                </button>
                <a href="{{ route('peliculas.index') }}"
                   class="d-block text-center text-secondary text-decoration-none"
                   style="font-size:.8rem">Limpiar filtros</a>
            </form>
        </div>

        {{-- ══ GRID PELÍCULAS ══ --}}
        <div class="col-lg-9">
            <p class="text-secondary mb-4" style="font-size:.85rem">
                {{ $peliculas->total() }} película(s) encontrada(s)
            </p>

            @if($peliculas->isEmpty())
            <div class="text-center py-5 text-secondary">
                <i class="bi bi-film" style="font-size:3rem"></i>
                <p class="mt-3">No se encontraron películas con esos filtros.</p>
            </div>
            @else
            <div class="row row-cols-2 row-cols-sm-3 row-cols-xl-4 g-3">
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

    </div>
</div>
@endsection
