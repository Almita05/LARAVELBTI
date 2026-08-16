<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EstadoCuentaController extends Controller
{
    public function index()
    {
        return view('estado_cuenta.index');
    }

    public function analizar(Request $request)
{
    $request->validate([
        'archivo' => [
            'required',
            'file',
            'max:20480',
        ],
    ]);

    $archivo = $request->file('archivo');

    return back()->with(
        'success',
        'Archivo recibido correctamente: ' . $archivo->getClientOriginalName()
    );
}
}