<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GameController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\MidtransWebhookController;
use App\Http\Controllers\OrderStatusController;


Route::get('/', [GameController::class, 'index'])->name('games.index');
Route::get('/games/{game:slug}', [GameController::class, 'show'])->name('games.show');

Route::post('/checkout/{game:slug}', [CheckoutController::class, 'store'])->name('checkout.store');

Route::get('/orders/{order}', [OrderStatusController::class, 'show'])->name('orders.show');
Route::get('/orders/{order}/status', [OrderStatusController::class, 'json'])->name('orders.status');

