<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {

        // Confiar en Cloudflare Tunnel / proxy HTTPS
        $middleware->trustProxies(at: '*');

        // Cabeceras de seguridad en todas las respuestas
        $middleware->append(\App\Http\Middleware\CabecerasSeguridad::class);

        // Quita "<" y ">" de los datos que llegan (defensa contra XSS almacenado)
        $middleware->web(append: [\App\Http\Middleware\LimpiarEntradaHtml::class]);

        $middleware->alias([
            'auth.session'  => \App\Http\Middleware\AuthSession::class,
            'docente.admin' => \App\Http\Middleware\DocenteOAdmin::class,
            'permiso'       => \App\Http\Middleware\Permiso::class,
            'sin.docente'   => \App\Http\Middleware\SinDocente::class,
            'solo.admin'    => \App\Http\Middleware\SoloAdmin::class,
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->create();