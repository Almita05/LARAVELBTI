<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BoletasBtiController extends Controller
{
    public function index()
    {
        return view('boletas_calificaciones.index');
    }


    public function boletasBTI()
{
   return view('boletasBTI.index');
}


public function modalAlta()
{
   
    return view('boletasBTI.modalAlta');
}

}
