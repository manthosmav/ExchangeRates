<?php

use App\Http\Controllers\ExchangeController;
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

Route::get('/rates', [ExchangeController::class, 'index']);
Route::post('/store-rates', [ExchangeController::class, 'store']);
Route::get('/stored-rates', [ExchangeController::class, 'storedRates']);
Route::get('/stored-rates/{id}', [ExchangeController::class, 'specificRate']);