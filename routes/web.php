<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GameController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\MidtransWebhookController;
use App\Http\Controllers\OrderStatusController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CartCheckoutController;

// Redirect root to products
Route::get('/', function () {
    return redirect()->route('products.index');
});

// Products routes
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product:slug}', [ProductController::class, 'show'])->name('products.show');

// Cart routes
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::get('/cart/count', [CartController::class, 'count'])->name('cart.count');
Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/{cartItem}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{cartItem}', [CartController::class, 'remove'])->name('cart.remove');
Route::delete('/cart', [CartController::class, 'clear'])->name('cart.clear');

// Checkout routes
Route::get('/checkout/cart', [CartCheckoutController::class, 'show'])->name('checkout.cart');
Route::post('/checkout/cart', [CartCheckoutController::class, 'store'])->name('checkout.cart.store');
Route::post('/checkout/product/{product:slug}', [CartCheckoutController::class, 'storeProduct'])->name('checkout.product.store');

// Legacy game routes (for backward compatibility)
Route::get('/games', [GameController::class, 'index'])->name('games.index');
Route::get('/games/{game:slug}', [GameController::class, 'show'])->name('games.show');
Route::post('/checkout/{game:slug}', [CheckoutController::class, 'store'])->name('checkout.store');

// Order routes
Route::get('/orders/{order:order_id}', [OrderStatusController::class, 'show'])->name('orders.show');
Route::get('/orders/{order:order_id}/status', [OrderStatusController::class, 'json'])->name('orders.status');

