<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LibroController;
use App\Http\Controllers\GeneroController;
use App\Http\Controllers\LocalizacionController;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/', [LibroController::class, 'libro'])->name('libro');

    Route::get('/libros', [LibroController::class, 'create']);
    Route::post('/libros', [LibroController::class, 'store']);

    Route::get('/libros/{libro}/editar', [LibroController::class, 'edit'])->name('libros.edit');
    Route::put('/libros/{libro}', [LibroController::class, 'update'])->name('libros.update');

    Route::delete('/libros/{id}', [LibroController::class, 'destroy']);

    Route::get('/generos', [GeneroController::class, 'index'])->name('genero.index');

    Route::get('/generos/create', [GeneroController::class, 'create'])->name('genero.create');
    Route::post('/generos', [GeneroController::class, 'store'])->name('genero.store');

    Route::get('/generos/{genero}/editar', [GeneroController::class, 'edit'])->name('genero.edit');
    Route::put('/generos/{genero}', [GeneroController::class, 'update'])->name('genero.update');

    Route::delete('/generos/{id}', [GeneroController::class, 'destroy'])->name('genero.destroy');

    Route::get('/localizaciones', [LocalizacionController::class, 'index'])->name('localizacion.index');

    Route::get('/localizaciones/create', [LocalizacionController::class, 'create'])->name('localizacion.create');
    Route::post('/localizaciones', [LocalizacionController::class, 'store'])->name('localizacion.store');

    Route::get('/localizaciones/{localizacion}/editar', [LocalizacionController::class, 'edit'])->name('localizacion.edit');
    Route::put('/localizaciones/{localizacion}', [LocalizacionController::class, 'update'])->name('localizacion.update');

    Route::delete('/localizaciones/{id}', [LocalizacionController::class, 'destroy'])->name('localizacion.destroy');
});


require __DIR__.'/auth.php';
