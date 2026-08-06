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
use App\Http\Controllers\CalificacionesController;
use App\Http\Controllers\HorariosController;
use App\Http\Controllers\KardexCBgneController;
use App\Http\Controllers\AsistenciaDocenteController;



Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLogin'])
    ->name('login');

Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout');
Route::get('/materias-usuario', [CalificacionesController::class, 'materiasUsuario']);


    
Route::middleware('auth.session')->group(function () {

    Route::get('/home', function () {
        return view('principal.home');
    })->name('home');

    Route::get('/editarPerfil', [PerfilController::class, 'index']);

    // Planes
    Route::get('/planesBTI', [PlanEstudioController::class, 'bti'])->name('planesBTI');
    Route::get('/planesBGNE', [PlanEstudioController::class, 'bgne'])->name('planesBGNE');

   

});

Route::middleware(['auth.session', 'docente.admin'])->group(function () {


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
    Route::get('/boletas_bti', [BoletasBtiController::class, 'boletasBTI'])
        ->name('boletas_bti');

    // Horarios
    Route::get('/horarios', [HorariosController::class, 'index'])->name('horarios');
    Route::get('/horarios/escolarizado', [HorariosController::class, 'escolarizado'])->name('horarios.escolarizado');
    Route::get('/horarios/sabado', [HorariosController::class, 'sabado'])->name('horarios.sabado');
    Route::get('/horarios/domingo', [HorariosController::class, 'domingo'])->name('horarios.domingo');

    // Asistencias Docentes
    Route::get('/asistencias_docentes', [AsistenciaDocenteController::class, 'index'])->name('asistencias_docentes');
    Route::get('/asistencias_docentes/datos', [AsistenciaDocenteController::class, 'getHorasDocentes']);
    Route::get('/asistencias_docentes/detalle', [AsistenciaDocenteController::class, 'getDetalleHorasDocente']);
    Route::post('/asistencias/upload', [AsistenciaDocenteController::class, 'uploadBiometrico']);
    Route::get('/asistencias', [AsistenciaDocenteController::class, 'getAsistencias']);
});


    // Alumnos
    Route::get('/alumnos', [AlumnoController::class, 'index'])->name('alumnos');
    Route::get('/alumnos/lista', [AlumnoController::class, 'lista']);
    Route::post('/alumnos', [AlumnoController::class, 'store']);
    Route::get('/alumnos/modalAlta', [AlumnoController::class, 'modalAlta']);
    Route::delete('/alumnos/{id}', [AlumnoController::class, 'destroy'])->name('alumnos.destroy');
    Route::get('/alumnos/{id}', [AlumnoController::class, 'show'])->where('id', '[0-9]+');
    Route::put('/alumnos/{id}', [AlumnoController::class, 'update'])->where('id', '[0-9]+');
    Route::get('/grupos/{id_grupo}/alumnos', [AlumnoController::class, 'alumnosGrupo'])->where('id_grupo', '[0-9]+');
    Route::get('/alumnos/grupo/{id_grupo}', [AlumnoController::class, 'alumnosPorGrupo'])->where('id_grupo', '[0-9]+');

    // Docentes
    Route::get('/docentes', [DocenteController::class, 'index'])->name('docentes');
    Route::get('/docentes/lista', [DocenteController::class, 'lista']);
    Route::post('/docentes', [DocenteController::class, 'store']);
    Route::get('/docentes/{id}', [DocenteController::class, 'show']);
    Route::put('/docentes/{id}', [DocenteController::class, 'update']);
    Route::delete('/docentes/{id}', [DocenteController::class, 'destroy']);

    // Materias
    Route::get('/materias', [MateriaController::class, 'index'])->name('materias');
    Route::get('/materias/lista', [MateriaController::class, 'lista']);
    Route::post('/materias', [MateriaController::class, 'store']);
    Route::get('/materias/{id}', [MateriaController::class, 'show']);
    Route::put('/materias/{id}', [MateriaController::class, 'update']);
    Route::delete('/materias/{id}', [MateriaController::class, 'destroy']);

    // Grupos
    Route::get('/grupos', [GrupoController::class, 'index'])->name('grupos');
    Route::get('/grupos/lista', [GrupoController::class, 'lista']);
    Route::post('/grupos', [GrupoController::class, 'store']);
    Route::get('/grupos/modalAlta', [GrupoController::class, 'modalAlta']);
    Route::get('/grupos/{id}', [GrupoController::class, 'show'])->where('id', '[0-9]+');
    Route::put('/grupos/{id}', [GrupoController::class, 'update'])->where('id', '[0-9]+');

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

        Route::get(
    '/calificaciones/alumnos/{grado}/{grupo}/{materia}',
    [CalificacionesController::class, 'alumnos']
);

Route::get(
    '/calificaciones/materias/{grado}',
    [CalificacionesController::class,'materias']
);

Route::post('/calificaciones/guardar', 
[CalificacionesController::class,'guardar'])
->name('calificaciones.guardar');
    // API de Horarios
    Route::get('/horarios/grupo/{id_grupo}', [HorariosController::class, 'getHorariosGrupo']);
    Route::post('/horarios', [HorariosController::class, 'store']);
    Route::post('/horarios/validar', [HorariosController::class, 'validar']);
    Route::delete('/horarios/{id_horario}', [HorariosController::class, 'destroy']);

