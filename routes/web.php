<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PeliculaController;
use App\Http\Controllers\CategoriaController;
use Illuminate\Support\Facades\Route;

// ─── Página principal ──────────────────────────────────────────────────────────
Route::get('/', [HomeController::class, 'index'])->name('home');

// ─── Catálogo de películas (CRUD completo) ─────────────────────────────────────
Route::prefix('peliculas')->name('peliculas.')->group(function () {
    Route::get('/',                          [PeliculaController::class, 'index'])->name('index');
    Route::get('/crear',                     [PeliculaController::class, 'create'])->name('create');
    Route::post('/',                         [PeliculaController::class, 'store'])->name('store');
    Route::get('/buscar',                    [PeliculaController::class, 'buscar'])->name('buscar');
    Route::get('/{pelicula}',               [PeliculaController::class, 'show'])->name('show');
    Route::get('/{pelicula}/editar',        [PeliculaController::class, 'edit'])->name('edit');
    Route::put('/{pelicula}',               [PeliculaController::class, 'update'])->name('update');
    Route::get('/{pelicula}/eliminar',      [PeliculaController::class, 'confirmDelete'])->name('confirm-delete');
    Route::delete('/{pelicula}',            [PeliculaController::class, 'destroy'])->name('destroy');
});

// ─── Categorías ────────────────────────────────────────────────────────────────
Route::get('/categoria/{categoria:slug}', [CategoriaController::class, 'show'])
     ->name('categorias.show');
