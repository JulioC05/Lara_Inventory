<?php

use App\Http\Controllers\Sales\VentaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


Route::post('/ventas/offline', [VentaController::class, 'storeOffline']);