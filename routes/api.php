<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Manager\WarehouseController;
use App\Http\Controllers\Manager\SectionController;
use App\Http\Controllers\Manager\CategoryController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::middleware('throttle:login')->group(function () {
        Route::post('login', [AuthController::class, 'login']);
        Route::post('forgot-password', [ResetPasswordController::class, 'forgotPassword']);
        Route::post('reset-password', [ResetPasswordController::class, 'resetPassword']);
    });

    // Protegido via JWT
    Route::middleware(['auth:api', 'throttle:api'])->group(function () {
        Route::get('me', [AuthController::class, 'me']);
        Route::post('refresh', [AuthController::class, 'refresh']);
        Route::post('logout', [AuthController::class, 'logout']);
    });
});

// Módulo Manager protegido via JWT
Route::middleware(['auth:api', 'throttle:api'])->prefix('manager')->group(function () {
    Route::apiResource('warehouses', WarehouseController::class);
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('sections', SectionController::class);
});
