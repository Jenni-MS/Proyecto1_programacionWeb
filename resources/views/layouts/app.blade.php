<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🎥</text></svg>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('titulo', 'CineStore') — Tu tienda de películas</title>

    {{-- Bootstrap 5 CDN --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">
    {{-- Bootstrap Icons CDN --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
          rel="stylesheet">
    {{-- Google Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:wght@300;400;600&display=swap"
          rel="stylesheet">

    <style>
        :root {
            --cine-rojo:   #dc3545;
            --cine-oscuro: #0a0a0f;
            --cine-gris:   #141420;
            --cine-card:   #1a1a2e;
            --cine-borde:  #2a2a40;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--cine-oscuro);
            color: #e0e0e0;
        }

        /* ── Navbar ── */
        .navbar-cine {
            background-color: var(--cine-gris);
            border-bottom: 1px solid var(--cine-borde);
        }
        .navbar-brand {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 1.7rem;
            letter-spacing: 3px;
            color: #fff !important;
        }
        .navbar-brand span { color: var(--cine-rojo); }
        .nav-link { color: #aaa !important; font-size: .9rem; }
        .nav-link:hover { color: #fff !important; }

        .barra-categorias {
            background-color: var(--cine-gris);
            border-bottom: 1px solid var(--cine-borde);
            padding: .4rem 0;
            font-size: .8rem;
        }
        .barra-categorias a {
            color: #888;
            text-decoration: none;
            margin-right: 1.2rem;
        }
        .barra-categorias a:hover { color: var(--cine-rojo); }

        /* ── Cards de película ── */
        .card-pelicula {
            background-color: var(--cine-card);
            border: 1px solid var(--cine-borde);
            border-radius: .75rem;
            overflow: hidden;
            transition: transform .25s, border-color .25s, box-shadow .25s;
            height: 100%;
        }
        .card-pelicula:hover {
            transform: translateY(-4px);
            border-color: rgba(220,53,69,.5);
            box-shadow: 0 8px 24px rgba(220,53,69,.15);
        }
        .card-pelicula .portada-wrap {
            aspect-ratio: 2/3;
            overflow: hidden;
        }
        .card-pelicula .portada-wrap img {
            width: 100%; height: 100%;
            object-fit: cover;
            transition: transform .4s;
        }
        .card-pelicula:hover .portada-wrap img { transform: scale(1.05); }
        .card-pelicula .card-body { padding: .75rem; }
        .card-pelicula .card-title {
            font-size: .85rem;
            font-weight: 600;
            color: #fff;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .card-pelicula .precio { color: var(--cine-rojo); font-weight: 700; }
        .card-pelicula .btn-carrito {
            background-color: rgba(220,53,69,.8);
            color: #fff;
            border: none;
            font-size: .78rem;
            width: 100%;
            padding: .4rem;
            border-radius: .4rem;
        }
        .card-pelicula .btn-carrito:hover { background-color: var(--cine-rojo); }

        /* ── Badge formato ── */
        .badge-formato {
            position: absolute; top: .5rem; left: .5rem;
            background: rgba(0,0,0,.7);
            color: #ccc; font-size: .65rem;
            padding: .2rem .5rem; border-radius: .3rem;
        }
        .badge-top {
            position: absolute; top: .5rem; right: .5rem;
            background: #ffc107; color: #000;
            font-size: .65rem; font-weight: 700;
            padding: .2rem .5rem; border-radius: .3rem;
        }

        /* ── Sidebar filtros ── */
        .sidebar-filtros {
            background-color: var(--cine-card);
            border: 1px solid var(--cine-borde);
            border-radius: .75rem;
            padding: 1.25rem;
        }
        .sidebar-filtros label { color: #aaa; font-size: .8rem; }
        .sidebar-filtros .form-control,
        .sidebar-filtros .form-select {
            background-color: #1e1e30;
            border: 1px solid var(--cine-borde);
            color: #ddd;
            font-size: .85rem;
        }
        .sidebar-filtros .form-control:focus,
        .sidebar-filtros .form-select:focus {
            background-color: #1e1e30;
            border-color: var(--cine-rojo);
            box-shadow: 0 0 0 .2rem rgba(220,53,69,.25);
            color: #ddd;
        }
        .form-check-input:checked { background-color: var(--cine-rojo); border-color: var(--cine-rojo); }

        /* ── Hero ── */
        .hero {
            background: linear-gradient(135deg, #0a0a0f 0%, #1a0510 50%, #0a0a0f 100%);
            padding: 5rem 1rem;
            text-align: center;
        }
        .hero h1 {
            font-family: 'Bebas Neue', sans-serif;
            font-size: clamp(3rem, 8vw, 6rem);
            letter-spacing: 4px;
            color: #fff;
        }
        .hero h1 span { color: var(--cine-rojo); }

        /* ── Secciones ── */
        .seccion-titulo {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 1.8rem;
            letter-spacing: 2px;
            color: #fff;
            margin-bottom: 1.25rem;
        }

        /* ── Detalle película ── */
        .detalle-hero {
            background: linear-gradient(to right, var(--cine-oscuro) 40%, transparent);
            position: relative;
        }
        .detalle-fondo {
            position: absolute; inset: 0;
            opacity: .15;
            object-fit: cover;
            width: 100%; height: 100%;
            filter: blur(20px);
        }

        /* ── Carrito ── */
        .carrito-item {
            background-color: var(--cine-card);
            border: 1px solid var(--cine-borde);
            border-radius: .75rem;
            padding: 1rem;
        }
        .resumen-carrito {
            background-color: var(--cine-card);
            border: 1px solid var(--cine-borde);
            border-radius: .75rem;
            padding: 1.5rem;
            position: sticky; top: 80px;
        }

        /* ── Alertas ── */
        .alerta-exito {
            background-color: rgba(25,135,84,.2);
            border-left: 4px solid #198754;
            color: #a3d9b8;
            padding: .75rem 1.25rem;
            font-size: .875rem;
        }
        .alerta-error {
            background-color: rgba(220,53,69,.2);
            border-left: 4px solid var(--cine-rojo);
            color: #f1aeb5;
            padding: .75rem 1.25rem;
            font-size: .875rem;
        }

        /* ── Footer ── */
        footer {
            background-color: var(--cine-gris);
            border-top: 1px solid var(--cine-borde);
            color: #666;
            font-size: .85rem;
        }
        footer a { color: #666; text-decoration: none; }
        footer a:hover { color: var(--cine-rojo); }
        footer .marca {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 1.4rem;
            letter-spacing: 3px;
            color: #fff;
        }

        /* ── Paginación oscura ── */
        .pagination .page-link {
            background-color: var(--cine-card);
            border-color: var(--cine-borde);
            color: #aaa;
        }
        .pagination .page-link:hover {
            background-color: #2a2a40;
            color: #fff;
        }
        .pagination .page-item.active .page-link {
            background-color: var(--cine-rojo);
            border-color: var(--cine-rojo);
            color: #fff;
        }
        .pagination .page-item.disabled .page-link { opacity: .4; }

        /* ── Inputs de búsqueda ── */
        .input-busqueda {
            background-color: #1e1e30;
            border: 1px solid var(--cine-borde);
            color: #ddd;
            border-radius: .5rem;
        }
        .input-busqueda:focus {
            background-color: #1e1e30;
            border-color: var(--cine-rojo);
            box-shadow: none;
            color: #ddd;
        }
        .input-busqueda::placeholder { color: #555; }
    </style>

    @stack('estilos')
</head>
<body>

{{-- ══ NAVBAR ══ --}}
<nav class="navbar navbar-expand-lg navbar-cine sticky-top">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">
            🎬 CINE<span>STORE</span>
        </a>

        <button class="navbar-toggler border-secondary" type="button"
                data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon" style="filter:invert(1)"></span>
        </button>

        <div class="collapse navbar-collapse" id="navMenu">
            {{-- Búsqueda --}}
            <form class="d-flex mx-auto my-2 my-lg-0" style="max-width:380px; width:100%"
                  action="{{ route('peliculas.index') }}" method="GET">
                <div class="input-group">
                    <input type="text" name="buscar" class="form-control input-busqueda"
                           placeholder="Buscar película o director..."
                           value="{{ request('buscar') }}">
                    <button class="btn btn-danger" type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </form>

            <ul class="navbar-nav ms-auto align-items-center gap-2">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('home') }}">Inicio</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('peliculas.index') }}">Catálogo</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

{{-- Barra de categorías --}}
<div class="barra-categorias d-none d-md-block">
    <div class="container d-flex flex-wrap gap-2">
        @foreach(\App\Models\Categoria::where('activa', true)->get() as $cat)
        <a href="{{ route('categorias.show', $cat) }}">
    <i class="bi bi-{{ $cat->icono }}"></i> {{ $cat->nombre }}
</a>
        @endforeach
    </div>
</div>

{{-- ══ CONTENIDO ══ --}}
<main>
    @yield('contenido')
</main>

{{-- ══ FOOTER ══ --}}
<footer class="mt-5 py-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="marca mb-2">🎬 CINESTORE</div>
                <p>Tu tienda virtual de películas en Costa Rica.<br>
                   DVD, Blu-ray, 4K UHD y Digital.</p>
            </div>
            <div class="col-md-4">
                <h6 class="text-white mb-3">Categorías</h6>
                <div class="d-flex flex-column gap-1">
                    @foreach(\App\Models\Categoria::where('activa', true)->limit(5)->get() as $cat)
                    <a href="{{ route('categorias.show', $cat) }}">{{ $cat->nombre }}</a>
                    @endforeach
                </div>
            </div>
            <div class="col-md-4">
                <h6 class="text-white mb-3">Información</h6>
                <div class="d-flex flex-column gap-1">
                    <a href="#">Sobre nosotros</a>
                    <a href="#">Política de envíos</a>
                    <a href="#">Devoluciones</a>
                    <a href="#">Contacto</a>
                </div>
            </div>
        </div>
        <hr style="border-color: var(--cine-borde); margin-top:2rem">
        <p class="text-center mb-0" style="font-size:.8rem">
            &copy; {{ date('Y') }} CineStore — EIF506 Diseño de Sitios Web · Universidad Nacional
        </p>
    </div>
</footer>

</body>
</html>
