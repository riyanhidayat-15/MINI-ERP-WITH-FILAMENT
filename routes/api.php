<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\StockController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function() {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    
    Route::get('/stocks', [StockController::class, 'stocks']);
    Route::get('/movements', [StockController::class, 'movements']);
    Route::post('/stock-in', [StockController::class, 'stockIn']);
    Route::post('/stock-out', [StockController::class, 'stockOut']);
});

