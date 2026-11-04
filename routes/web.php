<?php

use App\Http\Controllers\NoticiaController;
use App\Http\Controllers\ComentarioController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('noticia.index');
});

// Esto genera las 7 rutas automáticamente
Route::resource('noticia', NoticiaController::class);
Route::resource('comentario', ComentarioController::class)->except(['index', 'create', 'show']);

