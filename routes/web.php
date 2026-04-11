<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DocenteController;

Route::get('/', function () {
    return view('principal.home');
});



//MENU DOCENTES
Route::get('/docentes', [DocenteController::class, 'index']);
Route::get('/docentes/lista', [DocenteController::class, 'lista']);
Route::post('/docentes', [DocenteController::class, 'store']);