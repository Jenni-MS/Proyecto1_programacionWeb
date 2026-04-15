<?php

namespace App\Http\Controllers;

use App\Models\Pelicula;
use App\Models\Categoria;
use App\Models\Genero;
use Illuminate\Http\Request;

class PeliculaController extends Controller
{
    /**
     * Catálogo principal con filtros y búsqueda.
     */
    public function index(Request $request)
    {
        $query = Pelicula::with(['categoria', 'generos'])
                         ->disponibles()
                         ->latest();

        // Filtro por categoría
        if ($request->filled('categoria')) {
            $query->whereHas('categoria', fn($q) =>
                $q->where('slug', $request->categoria)
            );
        }

        // Filtro por género
        if ($request->filled('genero')) {
            $query->whereHas('generos', fn($q) =>
                $q->where('slug', $request->genero)
            );
        }

        // Filtro por formato
        if ($request->filled('formato')) {
            $query->where('formato', $request->formato);
        }

        // Filtro por rango de precio
        if ($request->filled('precio_min')) {
            $query->where('precio', '>=', $request->precio_min);
        }
        if ($request->filled('precio_max')) {
            $query->where('precio', '<=', $request->precio_max);
        }

        // Búsqueda por texto
        if ($request->filled('buscar')) {
            $termino = $request->buscar;
            $query->where(function ($q) use ($termino) {
                $q->where('titulo', 'like', "%{$termino}%")
                  ->orWhere('director', 'like', "%{$termino}%")
                  ->orWhere('sinopsis', 'like', "%{$termino}%");
            });
        }

        // Ordenamiento
        match ($request->get('orden', 'reciente')) {
            'precio_asc'   => $query->orderBy('precio', 'asc'),
            'precio_desc'  => $query->orderBy('precio', 'desc'),
            'titulo'       => $query->orderBy('titulo', 'asc'),
            'calificacion' => $query->orderBy('calificacion_imdb', 'desc'),
            default        => $query->latest(),
        };

        $peliculas  = $query->paginate(12)->withQueryString();
        $categorias = Categoria::where('activa', true)->get();
        $generos    = Genero::orderBy('nombre')->get();
        $formatos   = ['DVD', 'Blu-ray', '4K UHD', 'Digital'];

        return view('peliculas.index', compact('peliculas', 'categorias', 'generos', 'formatos'));
    }

    /**
     * Detalle de una película.
     */
    public function show(Pelicula $pelicula)
    {
        $pelicula->load(['categoria', 'generos']);

        $relacionadas = Pelicula::disponibles()
            ->where('categoria_id', $pelicula->categoria_id)
            ->where('id', '!=', $pelicula->id)
            ->limit(4)
            ->get();

        return view('peliculas.show', compact('pelicula', 'relacionadas'));
    }

    /**
     * Búsqueda rápida (AJAX / autocomplete).
     */
    public function buscar(Request $request)
    {
        $termino = $request->get('q', '');

        $resultados = Pelicula::disponibles()
            ->where('titulo', 'like', "%{$termino}%")
            ->select('id', 'titulo', 'anio_lanzamiento', 'imagen_portada', 'precio')
            ->limit(8)
            ->get();

        return response()->json($resultados);
    }
}
