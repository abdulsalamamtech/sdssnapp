<?php

// routes/api.php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\Admin\ProductTypeController;
use App\Http\Controllers\Api\Refinery\ProductController;
use App\Http\Controllers\Api\Marketer\PurchaseController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Authentication Routes
Route::prefix('register')->group(function () {
    Route::post('refinery', [RegisterController::class, 'registerRefinery']);
    Route::post('marketer', [RegisterController::class, 'registerMarketer']);
    Route::post('driver', [RegisterController::class, 'registerDriver']);
    Route::post('customer', [RegisterController::class, 'registerCustomer']);
});

// Admin Routes
Route::middleware(['auth:sanctum', 'role:administrator'])->prefix('admin')->group(function () {
    Route::apiResource('products/types', ProductTypeController::class);
});

// Refinery Routes
Route::middleware(['auth:sanctum', 'role:refinery'])->prefix('refineries')->group(function () {
    // Products
    Route::apiResource('products', ProductController::class);

    // Purchases
    Route::get('purchases', [RefineryPurchaseController::class, 'index']);
    Route::get('purchases/{purchase}', [RefineryPurchaseController::class, 'show']);
    
});