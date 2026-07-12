<?php

use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Inventory\CategoriaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Inventory\MarcaController;
use App\Http\Controllers\Inventory\ProductoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Purchases\CompraController;
use App\Http\Controllers\Purchases\ProveedorController;
use App\Http\Controllers\PushSubscriptionController;
use App\Http\Controllers\Reports\ReportController;
use App\Http\Controllers\Sales\ClienteController;
use App\Http\Controllers\Sales\VentaController;
use App\Http\Controllers\UsuarioController;
use App\Notifications\TestNotification;
use Illuminate\Support\Facades\Route;

Route::get('/', [AuthController::class, 'index'])->name('login');
Route::get('/olvido-contrasena', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/olvido-contrasena', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/restablecer-contrasena/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/restablecer-contrasena', [ForgotPasswordController::class, 'reset'])->name('password.update');
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }

    return app(AuthController::class)->index();
})->name('login');
Route::post('/logear', [AuthController::class, 'logear'])->name('logear');

Route::middleware(['auth'])->group(function () {
    // ... tus rutas actuales del sistema (inventario, ventas, etc.) ...
    // RUTA TEMPORAL PARA MANDARTE UNA NOTIFICACIÓN DE PRUEBA
    Route::get('/test-push', function () {
        $user = auth()->user();

        // Enviamos la notificación usando el canal nativo
        $user->notify(new TestNotification());

        return "¡Notificación Push enviada al celular/navegador de " . $user->name . "!";
    })->name('push.test');
    // NUEVAS RUTAS PARA NOTIFICACIONES PUSH
    Route::post('/push-subscription/update', [PushSubscriptionController::class, 'update'])->name('push.update');
    Route::post('/push-subscription/delete', [PushSubscriptionController::class, 'destroy'])->name('push.delete');
});


Route::middleware("auth")->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

Route::middleware('auth')->group(function () {
    Route::prefix('reportes')->name('reportes.')->group(function () {
        Route::get('/ventas', [ReportController::class, 'ventas'])->name('ventas');
        Route::get('/movimientos-stock', [ReportController::class, 'movimientosStock'])->name('movimientos-stock');
        Route::get('/compras', [ReportController::class, 'compras'])->name('compras');
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

        Route::get('/compras/{id}/pdf', [CompraController::class, 'descargarPdf'])->name('compras.pdf');

        Route::resource('compras', CompraController::class)->only(['index', 'create', 'store', 'show']);
    });
});

Route::middleware('auth')->group(function () {
    Route::resource('ventas', VentaController::class)->except(['edit', 'update']);

    Route::get('/ventas/{id}/pdf', [VentaController::class, 'descargarPdf'])->name('ventas.pdf');
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
    Route::get('/roles', [UsuarioController::class, 'roles_accesos'])->name('usuarios.roles_accesos');

    Route::put('usuarios/{usuario}/estado', [UsuarioController::class, 'cambiarEstado'])->name('usuarios.estado');
});
