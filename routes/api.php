<?php

use App\Http\Controllers\Client\DashboardController;
use App\Http\Controllers\Client\OrderController;
use App\Http\Controllers\Client\TrackingController;
use App\Http\Controllers\Client\NotificationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('client')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/orders', [OrderController::class, 'index']);
    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/tracking', [TrackingController::class, 'show']);
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::post('/accept-offer', [NotificationController::class, 'accept']);
    Route::post('/reject-offer', [NotificationController::class, 'reject']);
});
