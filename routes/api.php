<?php

// ── IMPORTACIONES DE CONTROLADORES ──────────────────────────────────────
use App\Http\Controllers\Api\DatosMaestrosController;
use App\Http\Controllers\Api\EmpresaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientesController;
use App\Http\Controllers\DashboardAdminController;
use App\Http\Controllers\DashboardOperadorController;
use App\Http\Controllers\EstatOfertaController;
use App\Http\Controllers\NotificacionsController;
use App\Http\Controllers\OfertaController;
use App\Http\Controllers\OperacionesController;
use App\Http\Controllers\SupersetController;
use App\Http\Controllers\TipusTransportController;
use App\Http\Controllers\UsuariosController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// ── RUTAS PÚBLICAS – no necesitan token ─────────────────────────────────
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// ── RUTAS PROTEGIDAS – necesitan token de Sanctum ───────────────────────
Route::middleware('auth:sanctum')->group(function () {

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // ── ADMIN: DASHBOARD ─────────────────────────────────────────────────
    Route::get('/data', [DashboardAdminController::class, 'getDashboardData']);
    Route::get('/advanced-stats', [DashboardAdminController::class, 'getAdvancedStats']);
    Route::post('/export', [DashboardAdminController::class, 'exportDashboardData']);
    Route::get('/export', [DashboardAdminController::class, 'exportDashboardData']);
    Route::get('/notifications', [DashboardAdminController::class, 'getNotifications']);
    Route::put('/notifications/{id}/read', [DashboardAdminController::class, 'markNotificationAsRead']);

    // ── ADMIN: GESTIÓN DE USUARIOS ───────────────────────────────────────
    Route::get('/usuarios', [UsuariosController::class, 'index']);
    Route::get('/usuarios/stats', [UsuariosController::class, 'stats']);
    Route::post('/usuarios', [UsuariosController::class, 'store']);
    Route::get('/usuarios/{id}', [UsuariosController::class, 'show']);
    Route::put('/usuarios/{id}', [UsuariosController::class, 'update']);
    Route::delete('/usuarios/{id}', [UsuariosController::class, 'destroy']);

    // ── ADMIN: DATOS MAESTROS ────────────────────────────────────────────
    Route::get('/datos-maestros/dashboard', [DatosMaestrosController::class, 'getDashboardData']);
    Route::get('/datos-maestros/estadisticas', [DatosMaestrosController::class, 'getEstadisticas']);
    Route::apiResource('empresas', EmpresaController::class);

    // ── OPERADOR: DASHBOARD ──────────────────────────────────────────────
    Route::get('/dashboard/stats', [DashboardOperadorController::class, 'stats']);
    Route::get('/dashboard/ofertes', [DashboardOperadorController::class, 'ultimes']);
    Route::get('/dashboard/alertes', [DashboardOperadorController::class, 'alertes']);
    Route::get('/dashboard/distribucio', [DashboardOperadorController::class, 'distribucio']);

    // ── OPERADOR: CLIENTES ───────────────────────────────────────────────
    // IMPORTANTE: rutas específicas ANTES que rutas con parámetro
    Route::get('/clientes', [ClientesController::class, 'index']);
    Route::post('/clientes', [ClientesController::class, 'store']);
    Route::put('/clientes/{id}/estado', [ClientesController::class, 'toggleEstado']);
    Route::get('/clientes/{id}', [ClientesController::class, 'show']);
    Route::put('/clientes/{id}', [ClientesController::class, 'update']);
    Route::delete('/clientes/{id}', [ClientesController::class, 'destroy']);

    // ── OPERADOR: OFERTAS COMERCIALES ────────────────────────────────────
    Route::get('/ofertes', [OfertaController::class, 'index']);
    Route::post('/ofertes', [OfertaController::class, 'store']);

    // ── OPERADOR: NUEVO PEDIDO ───────────────────────────────────────────
    Route::get('/clientes-rol', [OfertaController::class, 'clientes']);

    // ── OPERADOR: FILTROS Y SELECTS ──────────────────────────────────────
    Route::get('/estats-ofertes', [EstatOfertaController::class, 'index']);
    Route::get('/tipus-transports', [TipusTransportController::class, 'index']);
    Route::get('/ports', [OfertaController::class, 'ports']);
    Route::get('/tipus-carrega', [OfertaController::class, 'tipusCarrega']);
    Route::get('/transportistes', [OfertaController::class, 'transportistes']);
    Route::get('/tipus-incoterms', [OfertaController::class, 'tipusIncoterm']);

    // ── OPERADOR: OPERACIONES ────────────────────────────────────────────
    Route::get('/operaciones/stats', [OperacionesController::class, 'stats']);
    Route::get('/operaciones/distribucio', [OperacionesController::class, 'distribucio']);
    Route::get('/operaciones/operacions', [OperacionesController::class, 'operacions']);

    // ── NOTIFICACIONES ───────────────────────────────────────────────────
    Route::get('/notificacions', [NotificacionsController::class, 'index']);
    Route::put('/notificacions/marcar-totes', [NotificacionsController::class, 'marcarTotes']);
    Route::put('/notificacions/{id}/llegir', [NotificacionsController::class, 'marcarLlegida']);

    // ── SUPERSET — Guest token para embeber dashboards ───────────────────────
    Route::get('/superset/guest-token', [SupersetController::class, 'guestToken']);

});
