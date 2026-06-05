<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
}
