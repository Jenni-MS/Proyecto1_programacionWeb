<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PeliculaController;
use App\Http\Controllers\CategoriaController;
use Illuminate\Support\Facades\Route;

// ─── Página principal ─────────────────────────────────────────────────────────
Route::get('/', [HomeController::class, 'index'])->name('home');

// ─── Catálogo de películas ────────────────────────────────────────────────────
Route::prefix('peliculas')->name('peliculas.')->group(function () {
    Route::get('/',          [PeliculaController::class, 'index'])->name('index');
    Route::get('/buscar',    [PeliculaController::class, 'buscar'])->name('buscar');  // AJAX
    Route::get('/{pelicula}',[PeliculaController::class, 'show'])->name('show');
});

// ─── Categorías ───────────────────────────────────────────────────────────────
Route::get('/categoria/{categoria:slug}', [CategoriaController::class, 'show'])
     ->name('categorias.show');




