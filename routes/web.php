<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', function() {
    return view('auth.login');
});

Route::get('/dashboard', function() {
    return view('admin.dashboard');
});

Route::get('/operador', function () {
    return view('operador');
});

Route::get('/clientes', function () {
    return view('operador');            // usa el mismo blade del operador
});
