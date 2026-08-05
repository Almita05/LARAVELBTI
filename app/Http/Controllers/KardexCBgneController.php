<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KardexCBgneController extends Controller
{

    public function kardex_bgne()
{
   return view('kardex_calificaciones.index');
}



public function bgneS()
{
   return view('kardex_calificaciones_s.index');
}


public function bgneD()
{
   return view('kardex_calificaciones_d.index');
}



public function modalAlta()
{
   
    return view('kardex_calificaciones.modalAlta');
}

}
