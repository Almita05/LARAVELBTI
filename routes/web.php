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
use App\Http\Controllers\TestController;
use App\Http\Controllers\AuthController;



Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLogin'])
    ->name('login');

Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout');


    
Route::middleware('auth.session')->group(function () {

    Route::get('/home', function () {
        return view('principal.home');
    })->name('home');

    Route::get('/editarPerfil', [PerfilController::class, 'index']);

    // Planes
    Route::get('/planesBTI', [PlanEstudioController::class, 'bti'])->name('planesBTI');
    Route::get('/planesBGNE', [PlanEstudioController::class, 'bgne'])->name('planesBGNE');

    // Actas
    Route::get('/actas_calificaciones', [ActasCalificacionesController::class, 'index'])->name('actas_calificaciones');
    Route::get('/actas_calificacionesBTI', [ActasCalificacionesController::class, 'bti'])->name('actas_calificacionesBTI');
    Route::get('/actas_calificacionesBGNES', [ActasCalificacionesController::class, 'bgneS'])->name('actas_calificacionesBGNES');
    Route::get('/actas_calificacionesBGNED', [ActasCalificacionesController::class, 'bgneD'])->name('actas_calificacionesBGNED');

    // Listas
    Route::get('/listas_asistencias', [ListasAsistenciasController::class, 'index'])->name('listas_asistencias');
    Route::get('/listas_asistenciasBTI', [ListasAsistenciasController::class, 'bti'])->name('listas_asistenciasBTI');
    Route::get('/listas_asistenciasBGNES', [ListasAsistenciasController::class, 'bgneS'])->name('listas_asistenciasBGNES');
    Route::get('/listas_asistenciasBGNED', [ListasAsistenciasController::class, 'bgneD'])->name('listas_asistenciasBGNED');

    // Extraordinarios
    Route::get('/boleta_calificaciones_extraordinarios', [BoletasBGNEExtraController::class, 'index'])
        ->name('boleta_calificaciones_extraordinarios');

});

Route::middleware(['auth.session', 'docente.admin'])->group(function () {

    // Alumnos
    Route::get('/alumnos', [AlumnoController::class, 'index'])->name('alumnos');
    Route::get('/alumnos/lista', [AlumnoController::class, 'lista']);
    Route::post('/alumnos', [AlumnoController::class, 'store']);
    Route::get('/alumnos/modalAlta', [AlumnoController::class, 'modalAlta']);
    Route::delete('/alumnos/{id}', [AlumnoController::class, 'destroy'])->name('alumnos.destroy');

    // Docentes
    Route::get('/docentes', [DocenteController::class, 'index'])->name('docentes');
    Route::get('/docentes/lista', [DocenteController::class, 'lista']);
    Route::post('/docentes', [DocenteController::class, 'store']);

    // Materias
    Route::get('/materias', [MateriaController::class, 'index'])->name('materias');
    Route::get('/materias/lista', [MateriaController::class, 'lista']);
    Route::post('/materias', [MateriaController::class, 'store']);

    // Grupos
    Route::get('/grupos', [GrupoController::class, 'index'])->name('grupos');
    Route::get('/grupos/lista', [GrupoController::class, 'lista']);
    Route::post('/grupos', [GrupoController::class, 'store']);
    Route::get('/grupos/modalAlta', [GrupoController::class, 'modalAlta']);

    // Equivalencias
    Route::get('/equivalencias', [EquivalenciaController::class, 'index'])->name('equivalencias');
    Route::get('/equivalencias/lista', [EquivalenciaController::class, 'lista']);
    Route::post('/equivalencias', [EquivalenciaController::class, 'store']);
    Route::get('/equivalencias/modalAlta', [EquivalenciaController::class, 'modalAlta']);

    // Boletas
    Route::get('/boleta_calificaciones_bti', [BoletasBtiController::class, 'index'])
        ->name('boleta_calificaciones_bti');

    // Kardex
    Route::get('/kardex_no_escolarizado', [KardexBgneController::class, 'index'])
        ->name('kardex_no_escolarizado');

    Route::get('/kardex_bgneS', [KardexBgneController::class, 'bgneS'])
        ->name('kardex_bgneS');

    Route::get('/kardex_bgneD', [KardexBgneController::class, 'bgneD'])
        ->name('kardex_bgneD');

});