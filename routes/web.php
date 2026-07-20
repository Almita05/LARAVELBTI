<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DocenteController;
use App\Http\Controllers\MateriaController;
use App\Http\Controllers\AlumnoController;
use App\Http\Controllers\PlanEstudioController;
use App\Http\Controllers\GrupoController;
use App\Http\Controllers\EquivalenciaController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\ActasCalificacionesController;
use App\Http\Controllers\ListasAsistenciasController;
use App\Http\Controllers\BoletasBtiController;
use App\Http\Controllers\KardexBgneController;
use App\Http\Controllers\BoletasBGNEExtraController;

Route::get('/', function () {
    return view('principal.home');
});




//MENU alumnos
Route::get('/alumnos', [AlumnoController::class, 'index']);
Route::get('/alumnos/lista', [AlumnoController::class, 'lista']);
Route::post('/alumnos', [AlumnoController::class, 'store']);
Route::get('/alumnos/modalAlta', [AlumnoController::class, 'modalAlta']);
Route::delete('/alumnos/{id}', [AlumnoController::class, 'destroy'])->name('alumnos.destroy');

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



//MENU grupos
Route::get('/equivalencias', [EquivalenciaController::class, 'index']);
Route::get('/equivalencias/lista', [EquivalenciaController::class, 'lista']);
Route::post('/equivalencias', [EquivalenciaController::class, 'store']);
Route::get('/equivalencias/modalAlta', [EquivalenciaController::class, 'modalAlta']);



//rutas para cards de header_remove
Route::get('/alumnos', [AlumnoController::class, 'index'])->name('alumnos');
Route::get('/docentes', [DocenteController::class, 'index'])->name('docentes');
Route::get('/planesBTI', [PlanEstudioController::class, 'bti'])->name('planesBTI');
Route::get('/planesBGNE', [PlanEstudioController::class, 'bgne'])->name('planesBGNE');
Route::get('/equivalencias', [EquivalenciaController::class, 'index'])->name('equivalencias');

Route::get('/grupos', [GrupoController::class, 'index'])->name('grupos');
Route::get('/materias', [MateriaController::class, 'index'])->name('materias');


//actas de calificaciones
Route::get('/actas_calificaciones', [ActasCalificacionesController::class, 'index'])->name('actas_calificaciones');
Route::get('/actas_calificacionesBTI', [ActasCalificacionesController::class, 'bti'])->name('actas_calificacionesBTI');
Route::get('/actas_calificacionesBGNES', [ActasCalificacionesController::class, 'bgneS'])->name('actas_calificacionesBGNES');
Route::get('/actas_calificacionesBGNED', [ActasCalificacionesController::class, 'bgneD'])->name('actas_calificacionesBGNED');

//listas de asistencias
Route::get('/listas_asistencias', [ListasAsistenciasController::class, 'index'])->name('listas_asistencias');
Route::get('/listas_asistenciasBTI', [ListasAsistenciasController::class, 'bti'])->name('listas_asistenciasBTI');
Route::get('/listas_asistenciasBGNES', [ListasAsistenciasController::class, 'bgneS'])->name('listas_asistenciasBGNES');
Route::get('/listas_asistenciasBGNED', [ListasAsistenciasController::class, 'bgneD'])->name('listas_asistenciasBGNED');


//boleta de calificaicones
Route::get('/boleta_calificaciones_bti', [BoletasBtiController::class, 'index'])->name('boleta_calificaciones_bti');

//kardex de no escolarizado
Route::get('/kardex_no_escolarizado', [KardexBgneController::class, 'index'])->name('kardex_no_escolarizado');
Route::get('/kardex_bgneS', [KardexBgneController::class, 'bgneS'])->name('kardex_bgneS');
Route::get('/kardex_bgneD', [KardexBgneController::class, 'bgneD'])->name('kardex_bgneD');


//
Route::get('/boleta_calificaciones_extraordinarios', [BoletasBGNEExtraController::class, 'index'])->name('boleta_calificaciones_extraordinarios');



//perfil
Route::get('/editarPerfil', [PerfilController::class, 'index']);