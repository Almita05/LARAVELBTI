<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BoletasBtiController extends Controller
{
    public function index()
    {
        return view('boletas_calificaciones.index');
    }


}
