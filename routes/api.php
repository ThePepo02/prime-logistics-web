<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardOperadorController;
use App\Http\Controllers\ClientesController;
use App\Http\Controllers\OfertaController;
use App\Http\Controllers\EstatOfertaController;
use App\Http\Controllers\TipusTransportController;

use App\Http\Controllers\Client\DashboardController;
use App\Http\Controllers\Client\OrderController;
use App\Http\Controllers\Client\TrackingController;
use App\Http\Controllers\Client\NotificationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Dashboard Operador
    Route::get('/dashboard/stats', [DashboardOperadorController::class, 'stats']);
    Route::get('/dashboard/ofertes', [DashboardOperadorController::class, 'ultimes']);
    Route::get('/dashboard/alertes', [DashboardOperadorController::class, 'alertes']);
    Route::get('/dashboard/distribucio', [DashboardOperadorController::class, 'distribucio']);

    // Clientes
    Route::get('/clientes', [ClientesController::class, 'index']);

    // Ofertas
    Route::get('/ofertes', [OfertaController::class, 'index']);

    // Filtros
    Route::get('/estats-ofertes', [EstatOfertaController::class, 'index']);
    Route::get('/tipus-transports', [TipusTransportController::class, 'index']);
});

Route::prefix('client')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/orders', [OrderController::class, 'index']);
    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/tracking', [TrackingController::class, 'show']);
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::post('/accept-offer', [NotificationController::class, 'accept']);
    Route::post('/reject-offer', [NotificationController::class, 'reject']);
});
