<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Inventory\CategoriaController;
use App\Http\Controllers\Clientes;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Inventory\MarcaController;
use App\Http\Controllers\Inventory\ProductoController;
use App\Http\Controllers\Reports\ReportController;
use App\Http\Controllers\Sales\VentaController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Support\Facades\Route;

// Route::get('/crear-admin', [AuthController::class, 'crearAdmin']);

Route::get('/', [AuthController::class, 'index'])->name('login');
Route::post('/logear', [AuthController::class, 'logear'])->name('logear');

Route::middleware("auth")->group(function () {
    // Route::get('/home', [Dashboard::class, 'index'])->name('home');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::middleware('auth')->group(function () {
    Route::prefix('reportes')->name('reportes.')->group(function () {
        Route::get('/ventas', [ReportController::class, 'ventas'])->name('ventas');
        Route::get('/movimientos-stock',[ReportController::class, 'movimientosStock'])->name('movimientos-stock');
    });
});

// Route::prefix('reportes')->name('reportes.')->group(function () {
//     Route::get('/productos-mas-vendidos', [ReportController::class, 'productosMasVendidos'])->name('productos-mas-vendidos');
//     Route::get('/movimientos-stock', [ReportController::class, 'movimientosStock'])->name('movimientos-stock');
// });

Route::middleware('auth')->group(function () {
    Route::resource('ventas', VentaController::class)->except(['edit', 'update']);

    // Route::put('ventas/{categoria}/estado', [VentaController::class, 'cambiarEstado'])->name('ventas.estado');
});

Route::middleware('auth')->group(function () {
    Route::resource('categorias', CategoriaController::class)->except(['create', 'edit', 'show']);

    Route::put('categorias/{categoria}/estado', [CategoriaController::class, 'cambiarEstado'])->name('categorias.estado');
});

Route::middleware('auth')->group(function () {
    Route::resource('marcas', MarcaController::class)->except(['create', 'edit', 'show']);

    Route::put('marcas/{marca}/estado', [MarcaController::class, 'cambiarEstado'])->name('marcas.estado');
});

Route::middleware('auth')->group(function () {
    Route::resource('productos', ProductoController::class)->except(['create', 'edit', 'show']);

    Route::put('productos/{producto}/estado', [ProductoController::class, 'cambiarEstado'])->name('productos.estado');
});

Route::prefix('clientes')->middleware('auth')->group(function () {
    Route::get('/', [Clientes::class, 'index'])->name('clientes');
});

Route::middleware('auth')->group(function () {
    Route::resource('usuarios', UsuarioController::class)->except(['create', 'edit', 'show']);

    Route::put('usuarios/{usuario}/estado', [UsuarioController::class, 'cambiarEstado'])->name('usuarios.estado');
});
