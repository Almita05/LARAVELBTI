<?php

namespace App\Http\Controllers;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function test(UserRepository $users)
{
    $usuario = $users->findByUsername('admin');

    dd($usuario['Password']);
}



public function showLogin()
{
    return view('auth.login');
}



     public function login(Request $request, UserRepository $users)
{
    $usernameInput = trim($request->usuario);

    // 1. Intentar autenticar como Administrador / Google Sheet User
    $usuario = $users->findByUsername($usernameInput);

    if ($usuario) {
        if ($usuario['Estado'] != 'Activo') {
            return back()->with('error','Usuario inactivo');
        }

        if (!Hash::check($request->password, $usuario['Password'])) {
            return back()->with('error','Contraseña incorrecta');
        }

        session([
            'usuario_id' => $usuario['ID'],
            'usuario' => $usuario['Usuario'],
            'nombre' => $usuario['Nombre'],
            'rol' => $usuario['Rol'] ?? 'ADMIN'
        ]);

        return redirect('/home');
    }

    // 2. Intentar autenticar como Docente en MySQL
    $urlDocente = config('services.api.base_url') . '/docentes/by-username/' . urlencode($usernameInput);
    $resDocente = Http::get($urlDocente);

    if ($resDocente->successful()) {
        $docenteData = $resDocente->json()['data'] ?? null;

        if ($docenteData) {
            if (($docenteData['statusDocente'] ?? '') !== 'ACTIVO') {
                return back()->with('error', 'El docente se encuentra inactivo');
            }

            if (empty($docenteData['password'])) {
                return back()->with('error', 'Esta cuenta aún no tiene contraseña asignada');
            }

            if (!Hash::check($request->password, $docenteData['password'])) {
                return back()->with('error', 'Contraseña incorrecta');
            }

            $nombreCompleto = trim(($docenteData['nombreDocente'] ?? '') . ' ' . ($docenteData['apPaternoDocente'] ?? '') . ' ' . ($docenteData['apMaternoDocente'] ?? ''));

            session([
                'usuario_id' => $docenteData['idDocente'],
                'usuario' => $docenteData['usuario'] ?: $docenteData['correoDocente'],
                'nombre' => $nombreCompleto,
                'rol' => 'DOCENTE',
                'id_docente' => $docenteData['idDocente']
            ]);

            return redirect('/home');
        }
    }

    return back()->with('error','Usuario no encontrado');
}

   public function logout(Request $request)
{
    $request->session()->flush();      // Borra todas las variables de sesión
    $request->session()->invalidate(); // Invalida la sesión
    $request->session()->regenerateToken();

    return redirect('/login');
}
}
