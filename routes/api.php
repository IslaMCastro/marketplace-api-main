<?php

use App\Http\Controllers\TipoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


//Rotas Tipos
Route::middleware('api')->prefix('tipos')->group(function () {
    Route::get('/', [TipoController::class, 'index']);
    Route::post('/', [TipoController::class, 'store']);
    Route::get('/{tipo}', [TipoController::class, 'show']);
    Route::put('/{tipo}', [TipoController::class, 'update']);
    Route::delete('/{tipo}', [TipoController::class, 'destroy']);
});