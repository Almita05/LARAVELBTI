<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DocenteController;
use App\Http\Controllers\MateriaController;
use App\Http\Controllers\AlumnoController;
use App\Http\Controllers\PlanEstudioController;
use App\Http\Controllers\GrupoController;

Route::get('/', function () {
    return view('principal.home');
});




//MENU alumnos
Route::get('/alumnos', [AlumnoController::class, 'index']);
Route::get('/alumnos/lista', [AlumnoController::class, 'lista']);
Route::post('/alumnos', [AlumnoController::class, 'store']);
Route::get('/alumnos/modalAlta', [AlumnoController::class, 'modalAlta']);

//MENU DOCENTES
Route::get('/docentes', [DocenteController::class, 'index']);
Route::get('/docentes/lista', [DocenteController::class, 'lista']);
Route::post('/docentes', [DocenteController::class, 'store']);



//materias
Route::get('/materias', [MateriaController::class, 'index']);
Route::get('/materias/lista', [MateriaController::class, 'lista']);
Route::post('/materias', [MateriaController::class, 'store']);


//MENU grupos
Route::get('/grupos', [GrupoController::class, 'index']);
Route::get('/grupos/lista', [GrupoController::class, 'lista']);
Route::post('/grupos', [GrupoController::class, 'store']);
Route::get('/grupos/modalAlta', [GrupoController::class, 'modalAlta']);



//rutas para cards de header_remove
Route::get('/alumnos', [AlumnoController::class, 'index'])->name('alumnos');
Route::get('/docentes', [DocenteController::class, 'index'])->name('docentes');
Route::get('/planesBTI', [PlanEstudioController::class, 'bti'])->name('planesBTI');
Route::get('/planesBGNE', [PlanEstudioController::class, 'bgne'])->name('planesBGNE');
Route::get('/equivalencias', [EquivalenciaController::class, 'index'])->name('equivalencias');
Route::get('/grupos', [GrupoController::class, 'index'])->name('grupos');
Route::get('/materias', [MateriaController::class, 'index'])->name('materias');