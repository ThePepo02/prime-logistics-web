<?php

use App\Http\Controllers\DatosMaestrosController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientesController;
use App\Http\Controllers\DashboardAdminController;
use App\Http\Controllers\DashboardOperadorController;
use App\Http\Controllers\EstatOfertaController;
use App\Http\Controllers\NotificacionsController;
use App\Http\Controllers\OfertaController;
use App\Http\Controllers\OperacionesController;
use App\Http\Controllers\TipusTransportController;
use App\Http\Controllers\UsuariosController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Client\DashboardController;
use App\Http\Controllers\Client\OrderController;
use App\Http\Controllers\Client\TrackingController;
use App\Http\Controllers\Client\NotificationController;
use App\Http\Controllers\Client\IncotermsController;
use App\Http\Controllers\TipusIncotermController;
use App\Http\Controllers\TrackingOfertaController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// ── RUTAS CLIENTE (públicas) ─────────────────────────────────────
Route::prefix('client')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/orders', [OrderController::class, 'index']);
    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/tracking', [TrackingController::class, 'show']);
    Route::post('/tracking/advance', [TrackingController::class, 'advance']);
    Route::post('/tracking/previous', [TrackingController::class, 'previous']); // ← añade esto
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::post('/accept-offer', [NotificationController::class, 'accept']);
    Route::post('/reject-offer', [NotificationController::class, 'reject']);
    Route::get('/incoterms', [IncotermsController::class, 'index']);
    Route::put('/incoterms/{id}', [IncotermsController::class, 'update']);
});

// ── RUTAS PROTEGIDAS ─────────────────────────────────────────────
Route::middleware('auth:sanctum')->group(function () {

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('/data', [DashboardAdminController::class, 'getDashboardData']);
    Route::get('/advanced-stats', [DashboardAdminController::class, 'getAdvancedStats']);
    Route::post('/export', [DashboardAdminController::class, 'exportDashboardData']);
    Route::get('/export', [DashboardAdminController::class, 'exportDashboardData']);
    Route::get('/notifications', [DashboardAdminController::class, 'getNotifications']);
    Route::put('/notifications/{id}/read', [DashboardAdminController::class, 'markNotificationAsRead']);

    Route::get('/usuarios', [UsuariosController::class, 'index']);
    Route::get('/usuarios/stats', [UsuariosController::class, 'stats']);
    Route::post('/usuarios', [UsuariosController::class, 'store']);
    Route::get('/usuarios/{id}', [UsuariosController::class, 'show']);
    Route::put('/usuarios/{id}', [UsuariosController::class, 'update']);
    Route::delete('/usuarios/{id}', [UsuariosController::class, 'destroy']);

    Route::get('/datos-maestros/dashboard', [DatosMaestrosController::class, 'getDashboardData']);
    Route::get('/datos-maestros/estadisticas', [DatosMaestrosController::class, 'getEstadisticas']);

    Route::apiResource('empresas', EmpresaController::class);

    Route::get('/dashboard/stats', [DashboardOperadorController::class, 'stats']);
    Route::get('/dashboard/ofertes', [DashboardOperadorController::class, 'ultimes']);
    Route::get('/dashboard/alertes', [DashboardOperadorController::class, 'alertes']);
    Route::get('/dashboard/distribucio', [DashboardOperadorController::class, 'distribucio']);

    Route::get('/clientes', [ClientesController::class, 'index']);
    Route::post('/clientes', [ClientesController::class, 'store']);
    Route::put('/clientes/{id}/estado', [ClientesController::class, 'toggleEstado']);
    Route::put('/clientes/{id}', [ClientesController::class, 'update']);
    Route::delete('/clientes/{id}', [ClientesController::class, 'destroy']);

    Route::get('/ofertes', [OfertaController::class, 'index']);
    Route::post('/ofertes', [OfertaController::class, 'store']);

    Route::get('/clientes-rol', [OfertaController::class, 'clientes']);

    Route::get('/estats-ofertes', [EstatOfertaController::class, 'index']);
    Route::get('/tipus-transports', [TipusTransportController::class, 'index']);
    Route::get('/ports', [OfertaController::class, 'ports']);
    Route::get('/tipus-carrega', [OfertaController::class, 'tipusCarrega']);
    Route::get('/transportistes', [OfertaController::class, 'transportistes']);
    Route::get('/tipus-incoterms', [OfertaController::class, 'tipusIncoterm']);

    Route::get('/operaciones/stats', [OperacionesController::class, 'stats']);
    Route::get('/operaciones/distribucio', [OperacionesController::class, 'distribucio']);
    Route::get('/operaciones/operacions', [OperacionesController::class, 'operacions']);

    Route::get('/notificacions', [NotificacionsController::class, 'index']);
    Route::put('/notificacions/marcar-totes', [NotificacionsController::class, 'marcarTotes']);
    Route::put('/notificacions/{id}/llegir', [NotificacionsController::class, 'marcarLlegida']);
});