<?php

use App\Http\Controllers\ClientesController;
use App\Http\Controllers\DashboardOperadorController;
use App\Http\Controllers\OfertaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EstatOfertaController;
use App\Http\Controllers\TipusTransportController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/dashboard/stats', [DashboardOperadorController::class, 'stats']);
Route::get('/dashboard/ofertes', [DashboardOperadorController::class, 'ultimes']);
Route::get('/dashboard/alertes', [DashboardOperadorController::class, 'alertes']);
Route::get('/dashboard/distribucio', [DashboardOperadorController::class, 'distribucio']);

// Clientes
Route::get('/clientes', [ClientesController::class, 'index']);
Route::put('/clientes/{id}/estado', [ClientesController::class, 'toggleEstado']);


// Ofertas
Route::get('/ofertes', [OfertaController::class, 'index']);
Route::get('/estats-ofertes', [EstatOfertaController::class, 'index']);
Route::get('/tipus-transports', [TipusTransportController::class, 'index']);
