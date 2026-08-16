<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ListasAsistenciasController extends Controller
{
    public function index()
    {
        return view('listas_asistencias.index');
    }

   public function bti()
{
    return view('listas_asistencias.bti');
}

public function bgneS()
{
    return view('listas_asistencias.bgneS');
}
public function bgneD()
{
    return view('listas_asistencias.bgneD');
}


    public function generarPdfAttendance()
    {

// Datos de prueba ficticios para diseñar nuestro formato
        $materia = 'Programación Orientada a Objetos';
        $docente = 'Ing. Juan Carlos Pérez Gómez';
        $grupo = '3° Semestre - Grupo A';
        
        $alumnos = [
            ['num' => 1, 'num_control' => '20260001', 'nombre' => 'Pérez López Juan'],
            ['num' => 2, 'num_control' => '20260002', 'nombre' => 'Gómez García María'],
            ['num' => 3, 'num_control' => '20260003', 'nombre' => 'Hernández Ruiz Carlos'],
            ['num' => 4, 'num_control' => '20260004', 'nombre' => 'Martínez Díaz Sofía'],
            ['num' => 5, 'num_control' => '20260005', 'nombre' => 'Sánchez Torres Pedro'],
        ];
        // Cargar la vista Blade que crearemos en el siguiente paso
        // Le pasamos las variables con compact()
        $pdf = Pdf::loadView('listas_asistencias.pdf_formato', compact('materia', 'docente', 'grupo', 'alumnos'));
        // stream() abre el PDF en una pestaña nueva del navegador en vez de descargarlo directamente.
        // Esto es comodísimo para ir refrescando la pantalla y ver cómo va quedando el diseño.
        return $pdf->stream('lista_asistencia.pdf');
    }
}