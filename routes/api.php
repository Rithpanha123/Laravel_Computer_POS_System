<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PosApiController;
use Illuminate\Support\Facades\Route;


// Public Routes (មិនបាច់មាន Token)
Route::post('/login', [AuthController::class, 'login']);

// Protected Routes (ទាមទារ Token ពី Mobile App)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'profile']);
    
    Route::get('/products', [PosApiController::class, 'getProducts']);

    Route::middleware('auth:sanctum')->group(function () {
    Route::post('/checkout', [PosApiController::class, 'checkout']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/products', [PosApiController::class, 'getProducts']);
    Route::post('/checkout', [PosApiController::class, 'checkout']);
});
});