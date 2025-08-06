<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductoController; // Importá el controlador

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

// Página pública (solo ver productos)
Route::get('/', [ProductoController::class, 'indexPublic'])->name('home');

// Panel de administración (CRUD de productos)
Route::prefix('admin')->group(function () {
    Route::get('/', [ProductoController::class, 'index'])->name('productos.index');
    Route::get('/create', [ProductoController::class, 'create'])->name('productos.create');
    Route::post('/', [ProductoController::class, 'store'])->name('productos.store');
    Route::get('/{producto}/edit', [ProductoController::class, 'edit'])->name('productos.edit');
    Route::put('/{producto}', [ProductoController::class, 'update'])->name('productos.update');
    Route::delete('/{producto}', [ProductoController::class, 'destroy'])->name('productos.destroy');
});