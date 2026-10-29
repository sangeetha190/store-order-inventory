<?php

use App\Http\Controllers\BillingController;
use App\Http\Controllers\OrderHistoryController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/billing', [BillingController::class, 'create'])
    ->name('billing.create');
Route::get('/orders/history', [OrderHistoryController::class, 'index'])
    ->name('orders.history');
