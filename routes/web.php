<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', fn () => redirect()->route('products.index'))
    ->middleware('auth')->name('dashboard');

// Administrador y empleado: consultar, buscar, filtrar y ordenar
Route::get('/products', [ProductController::class, 'index'])
    ->middleware(['auth', 'role:admin,empleado'])
    ->name('products.index');

// Solo administrador: registrar, actualizar y eliminar
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('products', ProductController::class)->except(['index', 'show']);
});

require __DIR__.'/auth.php';
