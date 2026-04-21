{{-- Partial compartido por create y edit --}}
@php
    $v = fn(string $campo, $default = '') =>
        old($campo, isset($pelicula) && $pelicula ? $pelicula->$campo : $default);
@endphp

<style>
    .form-label { color: #aaa; font-size: .83rem; margin-bottom: .3rem; }
    .form-control, .form-select {
        background-color: #1a1a2e;
        border: 1px solid var(--cine-borde);
        color: #e0e0e0;
    }
    .form-control:focus, .form-select:focus {
        background-color: #1a1a2e;
        border-color: var(--cine-rojo);
        color: #fff;
        box-shadow: 0 0 0 .2rem rgba(220,53,69,.2);
    }
    .form-control::placeholder { color: #555; }
    .form-check-input { background-color: #1a1a2e; border-color: var(--cine-borde); }
    .form-check-input:checked { background-color: var(--cine-rojo); border-color: var(--cine-rojo); }
    .seccion-form {
        background: var(--cine-card);
        border: 1px solid var(--cine-borde);
        border-radius: 10px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }
    .seccion-form-titulo {
        font-size: .72rem;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        color: var(--cine-rojo);
        margin-bottom: 1rem;
    }
    .genero-check {
        background: #12122a;
        border: 1px solid var(--cine-borde);
        border-radius: 6px;
        padding: .3rem .7rem;
        font-size: .82rem;
    }
</style>

{{-- ── Información principal ── --}}
<div class="seccion-form">
    <p class="seccion-form-titulo"><i class="bi bi-film me-1"></i>Información principal</p>
    <div class="row g-3">
        <div class="col-md-8">
            <label class="form-label">Título <span class="text-danger">*</span></label>
            <input type="text" name="titulo" class="form-control @error('titulo') is-invalid @enderror"
                   value="{{ $v('titulo') }}" required maxlength="200">
            @error('titulo')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-4">
            <label class="form-label">Título original</label>
            <input type="text" name="titulo_original" class="form-control"
                   value="{{ $v('titulo_original') }}" maxlength="200">
        </div>
        <div class="col-md-6">
            <label class="form-label">Director <span class="text-danger">*</span></label>
            <input type="text" name="director" class="form-control @error('director') is-invalid @enderror"
                   value="{{ $v('director') }}" required maxlength="150">
            @error('director')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-3">
            <label class="form-label">Año de lanzamiento <span class="text-danger">*</span></label>
            <input type="number" name="anio_lanzamiento" class="form-control @error('anio_lanzamiento') is-invalid @enderror"
                   value="{{ $v('anio_lanzamiento') }}" min="1888" max="{{ date('Y') + 2 }}" required>
            @error('anio_lanzamiento')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-3">
            <label class="form-label">Duración (min) <span class="text-danger">*</span></label>
            <input type="number" name="duracion_minutos" class="form-control @error('duracion_minutos') is-invalid @enderror"
                   value="{{ $v('duracion_minutos') }}" min="1" max="999" required>
            @error('duracion_minutos')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-12">
            <label class="form-label">Descripción breve <span class="text-danger">*</span></label>
            <textarea name="descripcion" class="form-control @error('descripcion') is-invalid @enderror"
                      rows="2" required>{{ $v('descripcion') }}</textarea>
            @error('descripcion')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-12">
            <label class="form-label">Sinopsis</label>
            <textarea name="sinopsis" class="form-control" rows="4">{{ $v('sinopsis') }}</textarea>
        </div>
    </div>
</div>

{{-- ── Clasificación y formato ── --}}
<div class="seccion-form">
    <p class="seccion-form-titulo"><i class="bi bi-tag me-1"></i>Clasificación y formato</p>
    <div class="row g-3">
        <div class="col-md-4">
            <label class="form-label">Categoría <span class="text-danger">*</span></label>
            <select name="categoria_id" class="form-select @error('categoria_id') is-invalid @enderror" required>
                <option value="">Seleccione...</option>
                @foreach($categorias as $cat)
                <option value="{{ $cat->id }}"
                    {{ (string)$v('categoria_id') === (string)$cat->id ? 'selected' : '' }}>
                    {{ $cat->nombre }}
                </option>
                @endforeach
            </select>
            @error('categoria_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-4">
            <label class="form-label">Clasificación <span class="text-danger">*</span></label>
            <select name="clasificacion" class="form-select" required>
                @foreach($clasificaciones as $c)
                <option value="{{ $c }}" {{ $v('clasificacion') === $c ? 'selected' : '' }}>{{ $c }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label">Formato <span class="text-danger">*</span></label>
            <select name="formato" class="form-select" required>
                @foreach($formatos as $f)
                <option value="{{ $f }}" {{ $v('formato') === $f ? 'selected' : '' }}>{{ $f }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label">Idioma original</label>
            <input type="text" name="idioma_original" class="form-control"
                   value="{{ $v('idioma_original', 'Inglés') }}" maxlength="60">
        </div>
        <div class="col-md-4">
            <label class="form-label">Idiomas disponibles <small class="text-secondary">(separados por coma)</small></label>
            <input type="text" name="idiomas_disponibles" class="form-control"
                   placeholder="Español, Inglés, Francés"
                   value="{{ is_array($v('idiomas_disponibles')) ? implode(', ', $v('idiomas_disponibles')) : $v('idiomas_disponibles') }}">
        </div>
        <div class="col-md-4">
            <label class="form-label">Subtítulos <small class="text-secondary">(separados por coma)</small></label>
            <input type="text" name="subtitulos" class="form-control"
                   placeholder="Español, Inglés"
                   value="{{ is_array($v('subtitulos')) ? implode(', ', $v('subtitulos')) : $v('subtitulos') }}">
        </div>

        {{-- Géneros --}}
        <div class="col-12">
            <label class="form-label">Géneros</label>
            <div class="d-flex flex-wrap gap-2">
                @foreach($generos as $g)
                <div class="form-check genero-check">
                    <input class="form-check-input" type="checkbox"
                           name="generos[]" value="{{ $g->id }}"
                           id="gen_{{ $g->id }}"
                           {{ in_array($g->id, $generosSeleccionados ?? []) ? 'checked' : '' }}>
                    <label class="form-check-label text-secondary" for="gen_{{ $g->id }}">
                        {{ $g->nombre }}
                    </label>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

{{-- ── Precios e inventario ── --}}
<div class="seccion-form">
    <p class="seccion-form-titulo"><i class="bi bi-currency-dollar me-1"></i>Precios e inventario</p>
    <div class="row g-3">
        <div class="col-md-4">
            <label class="form-label">Precio de venta (₡) <span class="text-danger">*</span></label>
            <input type="number" name="precio" class="form-control @error('precio') is-invalid @enderror"
                   step="0.01" min="0" value="{{ $v('precio') }}" required>
            @error('precio')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-4">
            <label class="form-label">Precio de alquiler (₡)</label>
            <input type="number" name="precio_alquiler" class="form-control"
                   step="0.01" min="0" value="{{ $v('precio_alquiler') }}">
        </div>
        <div class="col-md-4">
            <label class="form-label">Stock <span class="text-danger">*</span></label>
            <input type="number" name="stock" class="form-control @error('stock') is-invalid @enderror"
                   min="0" value="{{ $v('stock', 0) }}" required>
            @error('stock')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>
</div>

{{-- ── Multimedia y calificaciones ── --}}
<div class="seccion-form">
    <p class="seccion-form-titulo"><i class="bi bi-star me-1"></i>Multimedia y calificaciones</p>
    <div class="row g-3">
        <div class="col-md-8">
            <label class="form-label">URL portada <small class="text-secondary">(ruta o URL de imagen)</small></label>
            <input type="text" name="imagen_portada" class="form-control"
                   value="{{ $v('imagen_portada') }}" maxlength="500">
        </div>
        <div class="col-md-8">
            <label class="form-label">URL del trailer</label>
            <input type="url" name="trailer_url" class="form-control"
                   placeholder="https://youtube.com/..." value="{{ $v('trailer_url') }}" maxlength="500">
        </div>
        <div class="col-md-2">
            <label class="form-label">Calificación IMDb</label>
            <input type="number" name="calificacion_imdb" class="form-control"
                   step="0.1" min="0" max="10" value="{{ $v('calificacion_imdb') }}">
        </div>
        <div class="col-md-2">
            <label class="form-label">Calificación local</label>
            <input type="number" name="calificacion_local" class="form-control"
                   step="0.1" min="0" max="10" value="{{ $v('calificacion_local', 0) }}">
        </div>
        <div class="col-md-4">
            <label class="form-label">Fecha de disponibilidad</label>
            <input type="date" name="fecha_disponibilidad" class="form-control"
                   value="{{ $v('fecha_disponibilidad') }}">
        </div>
    </div>
</div>

{{-- ── Estado ── --}}
<div class="seccion-form">
    <p class="seccion-form-titulo"><i class="bi bi-toggle-on me-1"></i>Estado</p>
    <div class="d-flex gap-4">
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" name="disponible" id="sw_disponible"
                   value="1" {{ $v('disponible', true) ? 'checked' : '' }}>
            <label class="form-check-label text-secondary" for="sw_disponible">Disponible en catálogo</label>
        </div>
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" name="destacado" id="sw_destacado"
                   value="1" {{ $v('destacado') ? 'checked' : '' }}>
            <label class="form-check-label text-secondary" for="sw_destacado">Película destacada</label>
        </div>
    </div>
</div>
