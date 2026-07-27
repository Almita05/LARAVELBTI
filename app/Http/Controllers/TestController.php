<?php

namespace App\Http\Controllers;

use App\Services\GoogleSheetsService;

class TestController extends Controller
{
    public function index(GoogleSheetsService $google)
    {
        $datos = $google->getRows('USUARIOS');

        return response()->json($datos);
    }
}