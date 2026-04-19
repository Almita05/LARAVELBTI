<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DocenteController;
use App\Http\Controllers\MateriaController;
use App\Http\Controllers\AlumnoController;

Route::get('/', function () {
    return view('principal.home');
});



//MENU alumnos
Route::get('/alumnos', [AlumnoController::class, 'index']);
Route::get('/alumnos/lista', [AlumnoController::class, 'lista']);
Route::post('/alumnos', [AlumnoController::class, 'store']);


//MENU DOCENTES
Route::get('/docentes', [DocenteController::class, 'index']);
Route::get('/docentes/lista', [DocenteController::class, 'lista']);
Route::post('/docentes', [DocenteController::class, 'store']);



//materias
Route::get('/materias', [MateriaController::class, 'index']);
Route::get('/materias/lista', [MateriaController::class, 'lista']);
Route::post('/materias', [MateriaController::class, 'store']);


//rutas para cards de header_remove
Route::get('/alumnos', [AlumnoController::class, 'index'])->name('alumnos');
Route::get('/docentes', [DocenteController::class, 'index'])->name('docentes');
Route::get('/planes', [PlanController::class, 'index'])->name('planes');
Route::get('/equivalencias', [EquivalenciaController::class, 'index'])->name('equivalencias');
Route::get('/grupos', [GrupoController::class, 'index'])->name('grupos');
Route::get('/materias', [MateriaController::class, 'index'])->name('materias');