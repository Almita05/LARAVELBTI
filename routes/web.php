<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DocenteController;
use App\Http\Controllers\MateriaController;

Route::get('/', function () {
    return view('principal.home');
});



//MENU DOCENTES
Route::get('/docentes', [DocenteController::class, 'index']);
Route::get('/docentes/lista', [DocenteController::class, 'lista']);
Route::post('/docentes', [DocenteController::class, 'store']);



//materias
Route::get('/materias', [MateriaController::class, 'index']);
Route::get('/materias/lista', [MateriaController::class, 'lista']);
Route::post('/materias', [MateriaController::class, 'store']);