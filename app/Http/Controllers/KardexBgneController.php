<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KardexBgneController extends Controller
{
    public function index()
    {
        return view('kardex_bgne.index');
    }

public function bgneS()
{
    return view('kardex_bgne.bgneS');
}
public function bgneD()
{
    return view('kardex_bgne.bgneD');
}
}
