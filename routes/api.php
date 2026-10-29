<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\OrderController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/orders', [OrderController::class, 'store']);

Route::get('/customers/order-history', [
    OrderController::class,
    'customerHistory',
]);

Route::get('/products/low-stock', [
    OrderController::class,
    'lowStock',
]);
