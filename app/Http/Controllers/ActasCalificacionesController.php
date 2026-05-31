<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ActasCalificacionesController extends Controller
{
    public function index()
    {
        return view('acta_calificaciones.index');
    }

   public function bti()
{
    return view('acta_calificaciones.bti');
}

public function bgneS()
{
    return view('acta_calificaciones.bgneS');
}
public function bgneD()
{
    return view('acta_calificaciones.bgneD');
}
}
