<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BoletasBGNEExtraController extends Controller
{
    public function index()
    {
        return view('boleta_calificaciones_extraordinarios.index');
    }
}
