<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
<<<<<<< HEAD
});
=======
});

Route::get('/dashboard', function () {
    return view('operador');
});
>>>>>>> main
