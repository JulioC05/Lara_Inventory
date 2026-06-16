<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Inventory\CategoriaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Inventory\MarcaController;
use App\Http\Controllers\Inventory\ProductoController;
use App\Http\Controllers\Purchases\CompraController;
use App\Http\Controllers\Purchases\ProveedorController;
use App\Http\Controllers\Reports\ReportController;
use App\Http\Controllers\Sales\ClienteController;
use App\Http\Controllers\Sales\VentaController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AuthController::class, 'index'])->name('login');
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }

    return app(AuthController::class)->index();
})->name('login');
Route::post('/logear', [AuthController::class, 'logear'])->name('logear');

Route::middleware("auth")->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::middleware('auth')->group(function () {
    Route::prefix('reportes')->name('reportes.')->group(function () {
        Route::get('/ventas', [ReportController::class, 'ventas'])->name('ventas');
        Route::get('/movimientos-stock', [ReportController::class, 'movimientosStock'])->name('movimientos-stock');
    });
});

Route::middleware(['auth'])->group(function () {
    Route::group(['middleware' => ['role:Admin|Almacen']], function () {

        Route::resource('proveedores', ProveedorController::class);
        Route::put('proveedores/{proveedore}/estado', [ProveedorController::class, 'cambiarEstado'])->name('proveedores.estado');

        // NUEVA RUTA: Procesar la recepción física del pedido pendiente
        Route::put('compras/{compra}/recibir', [CompraController::class, 'recibir'])->name('compras.recibir');
        // Ruta para anulación
        Route::put('compras/{compra}/anular', [CompraController::class, 'anular'])->name('compras.anular');

        Route::resource('compras', CompraController::class)->only(['index', 'create', 'store', 'show']);
    });
});

Route::middleware('auth')->group(function () {
    Route::resource('ventas', VentaController::class)->except(['edit', 'update']);
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

Route::middleware('auth')->group(function () {
    Route::resource('clientes', ClienteController::class)->except(['create', 'edit', 'show']);

    Route::put('clientes/{cliente}/estado', [ClienteController::class, 'cambiarEstado'])->name('clientes.estado');
});

Route::middleware('auth')->group(function () {
    Route::resource('usuarios', UsuarioController::class)->except(['create', 'edit', 'show']);

    Route::put('usuarios/{usuario}/estado', [UsuarioController::class, 'cambiarEstado'])->name('usuarios.estado');
});
