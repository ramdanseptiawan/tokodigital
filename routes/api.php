<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MidtransWebhookController;
use App\Http\Controllers\OrderStatusController;

use App\Http\Controllers\HelloWorldController;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Moved from web.php
Route::post('/midtrans/notify', \App\Http\Controllers\MidtransWebhookController::class)
    ->name('api.midtrans.notify');
Route::get('/orders/{order}/status', [OrderStatusController::class, 'json'])->name('api.orders.status');
Route::get('/coba-nih',function(){
    return "hey";
Route::get('/hello', [HelloWorldController::class, 'index']);
});