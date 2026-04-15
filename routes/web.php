<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('client.dashboard');
});

Route::get('/cliente/dashboard', function () {
    return view('client.dashboard');
})->name('client.dashboard');

Route::get('/cliente/nuevo-pedido', function () {
    return view('client.new-order');
})->name('client.new-order');

Route::get('/cliente/mis-pedidos', function () {
    return view('client.orders');
})->name('client.orders');

Route::get('/cliente/tracking', function () {
    return view('client.tracking');
})->name('client.tracking');

Route::get('/cliente/notificaciones', function () {
    return view('client.notifications');
})->name('client.notifications');
