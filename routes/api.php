<?php

<<<<<<< HEAD
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
=======
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

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    //Datos del administrador (dashboard, usuarios, datos maestros)

    //Dashboard Admin
    Route::get('/data', [DashboardAdminController::class, 'getDashboardData']);
    Route::get('/advanced-stats', [DashboardAdminController::class, 'getAdvancedStats']);

    Route::post('/export', [DashboardAdminController::class, 'exportDashboardData']);
    Route::get('/export', [DashboardAdminController::class, 'exportDashboardData']);

    Route::get('/notifications', [DashboardAdminController::class, 'getNotifications']);
    Route::put('/notifications/{id}/read', [DashboardAdminController::class, 'markNotificationAsRead']);

    //Gestion de usuarios - admin
    Route::get('/usuarios', [UsuariosController::class, 'index']);
    Route::get('/usuarios/stats', [UsuariosController::class, 'stats']);
    Route::post('/usuarios', [UsuariosController::class, 'store']);
    Route::get('/usuarios/{id}', [UsuariosController::class, 'show']);
    Route::put('/usuarios/{id}', [UsuariosController::class, 'update']);
    Route::delete('/usuarios/{id}', [UsuariosController::class, 'destroy']);


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

    // Operaciones
    Route::get('/operaciones/stats', [OperacionesController::class, 'stats']);
    Route::get('/operaciones/distribucio', [OperacionesController::class, 'distribucio']);
    Route::get('/operaciones/operacions', [OperacionesController::class, 'operacions']);

    // NOTIFICACIONS
    Route::get('/notificacions', [NotificacionsController::class, 'index']);
    Route::put('/notificacions/marcar-totes', [NotificacionsController::class, 'marcarTotes']);
    Route::put('/notificacions/{id}/llegir', [NotificacionsController::class, 'marcarLlegida']);
>>>>>>> ea23f4298696ad98487a64ea3b4adcb5d0cd246b
});
