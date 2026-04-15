<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
});

Route::get('/dashboard', function () {
    return view('operador');
});

Route::get('/clientes', function () {
    return view('operador');            // usa el mismo blade del operador
});
