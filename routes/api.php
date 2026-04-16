<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardOperadorController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('/dashboard/stats', [DashboardOperadorController::class, 'stats']);
    Route::get('/dashboard/ofertes', [DashboardOperadorController::class, 'ultimes']);
    Route::get('/dashboard/alertes', [DashboardOperadorController::class, 'alertes']);
    Route::get('/dashboard/distribucio', [DashboardOperadorController::class, 'distribucio']);
});
