<?php

use App\Http\Controllers\Menu\PizzaController;
use App\Http\Controllers\Menu\ToppingController;
use App\Http\Controllers\Transaction\OrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('/pizzas', [PizzaController::class, 'grid']);
Route::get('/toppings', [ToppingController::class, 'grid']);
Route::post('/orders', [OrderController::class, 'create']);