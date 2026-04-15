<?php

namespace App\Http\Controllers;

use App\Models\Pelicula;
use App\Models\Categoria;

class HomeController extends Controller
{
    public function index()
    {
        $destacadas  = Pelicula::with('categoria')->destacados()->disponibles()->limit(6)->get();
        $recientes   = Pelicula::with('categoria')->disponibles()->latest()->limit(8)->get();
        $categorias  = Categoria::withCount('peliculas')->where('activa', true)->get();
        $enOferta    = Pelicula::disponibles()
                               ->whereNotNull('precio_alquiler')
                               ->inRandomOrder()
                               ->limit(4)
                               ->get();

        return view('home.index', compact('destacadas', 'recientes', 'categorias', 'enOferta'));
    }
}
