<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
});

Route::get('/cliente/dashboard', function () {
    return view('client.dashboard');
})->name('client.dashboard');
