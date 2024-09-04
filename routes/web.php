<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    ray('cursosdesarrolloweb.es');
    return view('welcome');
});
