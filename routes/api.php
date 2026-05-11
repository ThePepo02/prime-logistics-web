<?php

// ── IMPORTACIONES DE CONTROLADORES ──────────────────────────────
// Cada controlador gestiona un grupo de rutas relacionadas
use App\Http\Controllers\Api\DatosMaestrosController; // Datos maestros del admin
use App\Http\Controllers\Api\EmpresaController;        // CRUD de empresas
use App\Http\Controllers\AuthController;               // Login y logout
use App\Http\Controllers\ClientesController;           // Lista de clientes
use App\Http\Controllers\DashboardAdminController;     // Dashboard del administrador
use App\Http\Controllers\DashboardOperadorController;  // Dashboard del operador
use App\Http\Controllers\EstatOfertaController;        // Estados de ofertas (filtros)
use App\Http\Controllers\NotificacionsController;      // Notificaciones
use App\Http\Controllers\OfertaController;             // Ofertas comerciales + NuevoPedido
use App\Http\Controllers\OperacionesController;        // Operaciones en curso
use App\Http\Controllers\TipusTransportController;     // Tipos de transporte (filtros)
use App\Http\Controllers\UsuariosController;           // Gestión de usuarios
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// ── RUTAS PÚBLICAS — no necesitan token ─────────────────────────
// Cualquiera puede llamar a estas rutas sin estar autenticado

Route::post('/login', [AuthController::class, 'login']);
// POST /api/login → recibe email y contraseña, devuelve token

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
// POST /api/logout → borra el token del usuario

// ── RUTAS PROTEGIDAS — necesitan token de Sanctum ───────────────
// Todas las rutas dentro de este grupo requieren el header:
// Authorization: Bearer {token}
Route::middleware('auth:sanctum')->group(function () {

    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    // GET /api/user → devuelve el usuario autenticado actual

    // ── ADMIN: DASHBOARD ────────────────────────────────────────
    Route::get('/data', [DashboardAdminController::class, 'getDashboardData']);
    // GET /api/data → estadísticas generales del admin

    Route::get('/advanced-stats', [DashboardAdminController::class, 'getAdvancedStats']);
    // GET /api/advanced-stats → estadísticas avanzadas

    Route::post('/export', [DashboardAdminController::class, 'exportDashboardData']);
    Route::get('/export', [DashboardAdminController::class, 'exportDashboardData']);
    // GET|POST /api/export → exportar datos del dashboard

    Route::get('/notifications', [DashboardAdminController::class, 'getNotifications']);
    // GET /api/notifications → notificaciones del admin

    Route::put('/notifications/{id}/read', [DashboardAdminController::class, 'markNotificationAsRead']);
    // PUT /api/notifications/1/read → marcar notificación como leída

    // ── ADMIN: GESTIÓN DE USUARIOS ──────────────────────────────
    // CRUD completo de usuarios — solo el admin puede gestionar usuarios
    Route::get('/usuarios', [UsuariosController::class, 'index']);
    // GET /api/usuarios → lista todos los usuarios

    Route::get('/usuarios/stats', [UsuariosController::class, 'stats']);
    // GET /api/usuarios/stats → estadísticas de usuarios

    Route::post('/usuarios', [UsuariosController::class, 'store']);
    // POST /api/usuarios → crear nuevo usuario

    Route::get('/usuarios/{id}', [UsuariosController::class, 'show']);
    // GET /api/usuarios/1 → ver un usuario

    Route::put('/usuarios/{id}', [UsuariosController::class, 'update']);
    // PUT /api/usuarios/1 → editar un usuario

    Route::delete('/usuarios/{id}', [UsuariosController::class, 'destroy']);
    // DELETE /api/usuarios/1 → eliminar un usuario

    // ── ADMIN: DATOS MAESTROS ───────────────────────────────────
    Route::get('/datos-maestros/dashboard', [DatosMaestrosController::class, 'getDashboardData']);
    Route::get('/datos-maestros/estadisticas', [DatosMaestrosController::class, 'getEstadisticas']);

    Route::apiResource('empresas', EmpresaController::class);
    // Genera automáticamente GET, POST, PUT, DELETE para /api/empresas

    // ── OPERADOR: DASHBOARD ─────────────────────────────────────
    Route::get('/dashboard/stats', [DashboardOperadorController::class, 'stats']);
    // GET /api/dashboard/stats → KPIs del dashboard (ofertas pendientes, aceptadas...)

    Route::get('/dashboard/ofertes', [DashboardOperadorController::class, 'ultimes']);
    // GET /api/dashboard/ofertes → últimas ofertas para la tabla del dashboard

    Route::get('/dashboard/alertes', [DashboardOperadorController::class, 'alertes']);
    // GET /api/dashboard/alertes → ofertas que requieren atención

    Route::get('/dashboard/distribucio', [DashboardOperadorController::class, 'distribucio']);
    // GET /api/dashboard/distribucio → distribución por tipo de transporte

    // ── OPERADOR: CLIENTES ──────────────────────────────────────
    Route::get('/clientes', [ClientesController::class, 'index']);
    // GET /api/clientes → lista de clientes para la página Clientes

    Route::post('/clientes', [ClientesController::class, 'store']);
    // POST /api/clientes → crear nuevo cliente

    Route::put('/clientes/{id}/estado', [ClientesController::class, 'toggleEstado']);
    // PUT /api/clientes/1/estado → activa o desactiva un cliente

    Route::put('/clientes/{id}', [ClientesController::class, 'update']);
    // PUT /api/clientes/1 → editar un cliente existente

    Route::delete('/clientes/{id}', [ClientesController::class, 'destroy']);
    // DELETE /api/clientes/1 → eliminar un cliente

    // ── OPERADOR: OFERTAS COMERCIALES ───────────────────────────
    Route::get('/ofertes', [OfertaController::class, 'index']);
    // GET /api/ofertes → lista paginada de ofertas para la tabla

    Route::post('/ofertes', [OfertaController::class, 'store']);
    // POST /api/ofertes → INSERT nueva oferta en la BD (CREATE del CRUD)
    // Lo llama NuevoPedido.vue al pulsar "Enviar Pedido" o "Guardar Borrador"

    // ── OPERADOR: NUEVO PEDIDO ──────────────────────────────────
    Route::get('/clientes-rol', [OfertaController::class, 'clientes']);
    // GET /api/clientes-rol → usuarios con rol_id = 3 (clientes)
    // Lo usa PasoIdentificacion.vue para llenar el select de clientes

    // ── OPERADOR: FILTROS ────────────────────────────────────────
    Route::get('/estats-ofertes', [EstatOfertaController::class, 'index']);
    // GET /api/estats-ofertes → estados (Borrador, Enviada, Aceptada...)
    // Lo usa OfertasComerciales.vue para el filtro de estado

    Route::get('/tipus-transports', [TipusTransportController::class, 'index']);
    // GET /api/tipus-transports → tipos de transporte (Marítimo, Aéreo, Terrestre)
    // Lo usan OfertasComerciales.vue y PasoEspecificaciones.vue

    Route::get('/ports', [OfertaController::class, 'ports']);
    // GET /api/ports → lista de puertos para los selects

    Route::get('/tipus-carrega', [OfertaController::class, 'tipusCarrega']);
    // GET /api/tipus-carrega → tipos de carga para el select

    Route::get('/transportistes', [OfertaController::class, 'transportistes']);
    // GET /api/transportistes → agentes de transporte para el select

    Route::get('/tipus-incoterms', [OfertaController::class, 'tipusIncoterm']);
    // GET /api/tipus-incoterms → incoterms para el select

    // ── OPERADOR: OPERACIONES ────────────────────────────────────
    Route::get('/operaciones/stats', [OperacionesController::class, 'stats']);
    Route::get('/operaciones/distribucio', [OperacionesController::class, 'distribucio']);
    Route::get('/operaciones/operacions', [OperacionesController::class, 'operacions']);

    // ── NOTIFICACIONES ───────────────────────────────────────────
    Route::get('/notificacions', [NotificacionsController::class, 'index']);
    // GET /api/notificacions → todas las notificaciones

    Route::put('/notificacions/marcar-totes', [NotificacionsController::class, 'marcarTotes']);
    // PUT /api/notificacions/marcar-totes → marcar todas como leídas

    Route::put('/notificacions/{id}/llegir', [NotificacionsController::class, 'marcarLlegida']);
    // PUT /api/notificacions/1/llegir → marcar una notificación como leída
});
