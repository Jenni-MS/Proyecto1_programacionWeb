<?php

namespace App\Http\Controllers;

use App\Models\Categoria;

class CategoriaController extends Controller
{
    public function show(Categoria $categoria)
    {
        $peliculas = $categoria->peliculas()
                               ->disponibles()
                               ->with('generos')
                               ->paginate(12);

        return view('categorias.show', compact('categoria', 'peliculas'));
    }
}
