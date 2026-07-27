<?php

namespace App\Http\Controllers;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function test(UserRepository $users)
{
    $usuario = $users->findByUsername('admin');

    dd($usuario['Password']);
}

     public function login(Request $request, UserRepository $users)
    {
      $usuario = $users->findByUsername($request->usuario);


if (!$usuario) {
    return back()->with('error','Usuario no encontrado');
}


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
    'rol' => $usuario['Rol']
]);

return redirect('/home');
    } 

   public function logout(Request $request)
{
    $request->session()->flush();      // Borra todas las variables de sesión
    $request->session()->invalidate(); // Invalida la sesión
    $request->session()->regenerateToken();

    return redirect('/login');
}
}
