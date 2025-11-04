<?php
use App\http\Controllers\Api\ProductoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::apiResource('productos', ProductoController::class);
Route::get('productos', [ProductoController::class, 'index']);
Route::get('productos/{id}', [ProductoController::class, 'show']);
Route::post('productos', [ProductoController::class, 'store']);
Route::put('productos/{id}', [ProductoController::class, 'update']);
Route::delete('productos/{id}', [ProductoController::class, 'destroy']);

// Direccion de paginaciones -- Juan_perez
Route::get('productos/paginar/{cantidad}', [ProductoController::class, 'paginarSimple']);
Route::get('productos/paginar/{cantidad}/pagina/{pagina}', [ProductoController::class, 'paginarAvanzada']);
Route::patch('productos/{id}/actualizar-stock', [ProductoController::class, 'actualizarStock']);
