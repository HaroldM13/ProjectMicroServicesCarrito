<?php
use App\http\Controllers\Api\ProductoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return 'texto';
});

Route::apiResource('productos', ProductoController::class);
