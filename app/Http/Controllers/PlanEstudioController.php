<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PlanEstudioController extends Controller
{
   public function bti()
{
    return view('planes.bti');
}

public function bgne()
{
    return view('planes.bgne');
}
}
