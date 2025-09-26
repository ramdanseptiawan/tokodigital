<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StorageController;

// Route untuk serve file storage menggunakan controller
Route::get('/storage/{path}', [StorageController::class, 'show'])
    ->where('path', '.*')
    ->name('storage.show');