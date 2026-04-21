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

        if ($request->filled('categoria')) {
            $query->whereHas('categoria', fn($q) =>
                $q->where('slug', $request->categoria)
            );
        }

        if ($request->filled('genero')) {
            $query->whereHas('generos', fn($q) =>
                $q->where('slug', $request->genero)
            );
        }

        if ($request->filled('formato')) {
            $query->where('formato', $request->formato);
        }

        if ($request->filled('precio_min')) {
            $query->where('precio', '>=', $request->precio_min);
        }
        if ($request->filled('precio_max')) {
            $query->where('precio', '<=', $request->precio_max);
        }

        if ($request->filled('buscar')) {
            $termino = $request->buscar;
            $query->where(function ($q) use ($termino) {
                $q->where('titulo', 'like', "%{$termino}%")
                  ->orWhere('director', 'like', "%{$termino}%")
                  ->orWhere('sinopsis', 'like', "%{$termino}%");
            });
        }

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
     * Formulario para crear nueva película.
     */
    public function create()
    {
        $categorias = Categoria::where('activa', true)->orderBy('nombre')->get();
        $generos    = Genero::orderBy('nombre')->get();
        $formatos   = ['DVD', 'Blu-ray', '4K UHD', 'Digital'];
        $clasificaciones = ['G', 'PG', 'PG-13', 'R', 'NC-17'];

        return view('peliculas.create', compact('categorias', 'generos', 'formatos', 'clasificaciones'));
    }

    /**
     * Guardar nueva película.
     */
    public function store(Request $request)
    {
        $data = $this->validar($request);

        $data['idiomas_disponibles'] = $this->parseLista($request->idiomas_disponibles);
        $data['subtitulos']          = $this->parseLista($request->subtitulos);
        $data['destacado']           = $request->boolean('destacado');
        $data['disponible']          = $request->boolean('disponible');

        $pelicula = Pelicula::create($data);

        if ($request->filled('generos')) {
            $pelicula->generos()->sync($request->generos);
        }

        return redirect()->route('peliculas.show', $pelicula)
                         ->with('exito', 'Película creada correctamente.');
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
     * Formulario para editar película.
     */
    public function edit(Pelicula $pelicula)
    {
        $pelicula->load('generos');
        $categorias = Categoria::where('activa', true)->orderBy('nombre')->get();
        $generos    = Genero::orderBy('nombre')->get();
        $formatos   = ['DVD', 'Blu-ray', '4K UHD', 'Digital'];
        $clasificaciones = ['G', 'PG', 'PG-13', 'R', 'NC-17'];
        $generosSeleccionados = $pelicula->generos->pluck('id')->toArray();

        return view('peliculas.edit', compact(
            'pelicula', 'categorias', 'generos',
            'formatos', 'clasificaciones', 'generosSeleccionados'
        ));
    }

    /**
     * Guardar cambios de una película.
     */
    public function update(Request $request, Pelicula $pelicula)
    {
        $data = $this->validar($request, $pelicula->id);

        $data['idiomas_disponibles'] = $this->parseLista($request->idiomas_disponibles);
        $data['subtitulos']          = $this->parseLista($request->subtitulos);
        $data['destacado']           = $request->boolean('destacado');
        $data['disponible']          = $request->boolean('disponible');

        $pelicula->update($data);

        $pelicula->generos()->sync($request->generos ?? []);

        return redirect()->route('peliculas.show', $pelicula)
                         ->with('exito', 'Película actualizada correctamente.');
    }

    /**
     * Confirmación de eliminación.
     */
    public function confirmDelete(Pelicula $pelicula)
    {
        return view('peliculas.confirm-delete', compact('pelicula'));
    }

    /**
     * Eliminar película (soft delete).
     */
    public function destroy(Pelicula $pelicula)
    {
        $categoria = $pelicula->categoria;
        $pelicula->delete();

        // Si venía de la página de categoría, redirigir allí con mensaje
        return redirect()->route('categorias.show', $categoria->slug)
                         ->with('exito', "La película \"{$pelicula->titulo}\" fue eliminada.");
    }

    /**
     * Búsqueda rápida.
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

    // ─── Helpers privados ─────────────────────────────────────────

    private function validar(Request $request, ?int $ignorarId = null): array
    {
        return $request->validate([
            'titulo'              => 'required|string|max:200',
            'titulo_original'     => 'nullable|string|max:200',
            'descripcion'         => 'required|string',
            'sinopsis'            => 'nullable|string',
            'categoria_id'        => 'required|exists:categorias,id',
            'director'            => 'required|string|max:150',
            'anio_lanzamiento'    => 'required|integer|min:1888|max:' . (date('Y') + 2),
            'duracion_minutos'    => 'required|integer|min:1|max:999',
            'clasificacion'       => 'required|in:G,PG,PG-13,R,NC-17',
            'idioma_original'     => 'required|string|max:60',
            'formato'             => 'required|in:DVD,Blu-ray,4K UHD,Digital',
            'precio'              => 'required|numeric|min:0',
            'precio_alquiler'     => 'nullable|numeric|min:0',
            'stock'               => 'required|integer|min:0',
            'imagen_portada'      => 'nullable|string|max:500',
            'trailer_url'         => 'nullable|url|max:500',
            'calificacion_imdb'   => 'nullable|numeric|min:0|max:10',
            'calificacion_local'  => 'nullable|numeric|min:0|max:10',
            'fecha_disponibilidad'=> 'nullable|date',
        ]);
    }

    private function parseLista(?string $valor): ?array
    {
        if (empty($valor)) return null;
        return array_values(array_filter(array_map('trim', explode(',', $valor))));
    }
}
