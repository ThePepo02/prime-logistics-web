<?php

<<<<<<< HEAD
=======
use App\Http\Controllers\DashboardOperadorController;
>>>>>>> main
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
<<<<<<< HEAD
=======

Route::get('/dashboard/stats', [DashboardOperadorController::class, 'stats']);
Route::get('/dashboard/ofertes', [DashboardOperadorController::class, 'ultimes']);


Route::get('/dashboard/alertes', [DashboardOperadorController::class, 'alertes']);
Route::get('/dashboard/distribucio', [DashboardOperadorController::class, 'distribucio']);
>>>>>>> main
