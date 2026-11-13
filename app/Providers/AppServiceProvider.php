<?php

namespace App\Providers;

use App\Auth\ApiUserProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Psr\Http\Message\RequestInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        require_once app_path('helpers.php');
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (app()->environment('production')) {
            URL::forceScheme('https');
        }

        $this->autenticarLlamadasApi();

        // Usuarios administrativos (tb_users) autenticados a través de la API, no de la BD
        Auth::provider('api', fn ($app, array $config) => new ApiUserProvider());
    }

    /**
     * Agrega X-API-Token (y X-Usuario para la bitácora) a TODAS las peticiones Http:: dirigidas
     * al backend Flask. Solo a esas: Moodle, Google, etc. no deben recibir el token.
     */
    private function autenticarLlamadasApi(): void
    {
        $base = (string) config('services.api.base_url');
        $token = (string) config('services.api.token');
        if ($base === '' || $token === '') {
            return;
        }

        $destino = parse_url($base);
        $mismoServicio = function (RequestInterface $request) use ($destino): bool {
            $uri = $request->getUri();
            $puerto = fn ($esquema, $p) => $p ?: ($esquema === 'https' ? 443 : 80);
            return strcasecmp($uri->getHost(), $destino['host'] ?? '') === 0
                && $puerto($uri->getScheme(), $uri->getPort()) === $puerto($destino['scheme'] ?? 'http', $destino['port'] ?? null);
        };

        Http::globalRequestMiddleware(function (RequestInterface $request) use ($mismoServicio, $token) {
            if (!$mismoServicio($request)) {
                return $request;
            }
            $usuario = 'sistema';
            try {
                if (app()->bound('session') && app('session')->isStarted()) {
                    $usuario = (string) (session('usuario') ?: 'anónimo');
                }
            } catch (\Throwable) {
                // Consola / sin sesión
            }
            return $request
                ->withHeader('X-API-Token', $token)
                ->withHeader('X-Usuario', mb_substr($usuario, 0, 100));
        });
    }
}
