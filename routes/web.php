<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Inventory\CategoriaController;
use App\Http\Controllers\Clientes;
use App\Http\Controllers\Dashboard;
use App\Http\Controllers\DetalleVentas;
use App\Http\Controllers\Inventory\MarcaController;
use App\Http\Controllers\Productos;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\Ventas;
use Illuminate\Support\Facades\Route;

// Route::get('/crear-admin', [AuthController::class, 'crearAdmin']);

Route::get('/', [AuthController::class, 'index'])->name('login');
Route::post('/logear', [AuthController::class, 'logear'])->name('logear');

Route::middleware("auth")->group(function () {
    Route::get('/home', [Dashboard::class, 'index'])->name('home');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::prefix('ventas')->middleware('auth')->group(function () {
    Route::get('/nueva-venta', [Ventas::class, 'index'])->name('ventas-nueva');
});

Route::prefix('detalle')->middleware('auth')->group(function () {
    Route::get('/detalle-venta', [DetalleVentas::class, 'index'])->name('detalle-venta');
});

Route::middleware('auth')->group(function () {
    Route::resource('categorias', CategoriaController::class)->except(['create', 'edit', 'show']);

    Route::put('categorias/{categoria}/estado', [CategoriaController::class, 'cambiarEstado'])->name('categorias.estado');
});

Route::middleware('auth')->group(function () {
    Route::resource('marcas', MarcaController::class)->except(['create', 'edit', 'show']);

    Route::put('marcas/{marca}/estado', [MarcaController::class, 'cambiarEstado'])->name('marcas.estado');
});

Route::prefix('productos')->middleware('auth')->group(function () {
    Route::get('/', [Productos::class, 'index'])->name('productos');
});

Route::prefix('clientes')->middleware('auth')->group(function () {
    Route::get('/', [Clientes::class, 'index'])->name('clientes');
});

Route::prefix('usuarios')->middleware('auth')->group(function () {
    Route::get('/', [UsuarioController::class, 'index'])->name('usuarios');
});

Route::middleware('auth')->group(function () {
    Route::resource('usuarios', UsuarioController::class)->except(['create', 'edit', 'show']);

    Route::put('usuarios/{usuario}/estado', [UsuarioController::class, 'cambiarEstado'])->name('usuarios.estado');
});
