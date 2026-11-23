<?php

namespace App\Http\Controllers;

use App\Services\HistorialAlumnoService;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Modalidad online BGNE (78 semanas según las fechas del grupo, cola FIFO de pagos y Moodle).
 * Toda la lógica vive en la API (endpoints /online/...); aquí solo se reenvían las peticiones.
 */
class AlumnoOnlineController extends Controller
{
    private function api(string $ruta): string
    {
        return rtrim(config('services.api.base_url'), '/') . '/' . ltrim($ruta, '/');
    }

    /** Responde lo que regresó la API; si no hubo JSON, un error legible. */
    private function reenviar(?Response $res, string $contexto)
    {
        if (!$res) {
            return response()->json(['success' => false, 'message' => 'No hubo conexión con el servidor de la API.'], 502);
        }
        $json = $res->json();
        if (!is_array($json)) {
            Log::error("API online sin JSON en {$contexto}: HTTP {$res->status()} " . mb_substr($res->body(), 0, 300));
            return response()->json(['success' => false, 'message' => 'La API respondió con un error.'], $res->status() ?: 502);
        }
        if (!$res->successful() && empty($json['message']) && !empty($json['error'])) {
            $json['message'] = $json['error'];
        }
        return response()->json($json, $res->status());
    }

    private function llamar(string $metodo, string $ruta, array $datos = [], int $timeout = 20, string $contexto = '')
    {
        try {
            $req = Http::timeout($timeout)->acceptJson();
            $res = $metodo === 'get' ? $req->get($this->api($ruta), $datos) : $req->{$metodo}($this->api($ruta), $datos);
            return $this->reenviar($res, $contexto ?: $ruta);
        } catch (\Throwable $e) {
            Log::error("API online {$metodo} {$ruta}: " . $e->getMessage());
            return $this->reenviar(null, $ruta);
        }
    }

    public function index()
    {
        return view('alumnos.online');
    }

    public function getAlumnos(Request $request)
    {
        return $this->llamar('get', '/online/alumnos', ['search' => trim((string)$request->search)]);
    }

    public function getDetalleAlumno($id)
    {
        return $this->llamar('get', "/online/alumnos/{$id}");
    }

    public function vistaPreviaPago(Request $request, $id)
    {
        return $this->llamar('get', "/online/alumnos/{$id}/pagos/vista-previa", [
            'semanas'    => (int)$request->query('semanas', 1),
            'fecha_pago' => (string)$request->query('fecha_pago', ''),
        ]);
    }

    /** Registra el pago (puede tardar: habilita semanas en Moodle). */
    public function registrarPago(Request $request, $id)
    {
        return $this->llamar('post', "/online/alumnos/{$id}/pagos", $request->only([
            'semanas', 'monto', 'metodo_pago', 'folio_ticket', 'fecha_pago', 'observaciones',
        ]), 90);
    }

    /** Si hoy se puede pasar a online según las fechas de su grupo BGNE y en qué trimestre iniciaría. */
    public function vistaIntegracion($id)
    {
        return $this->llamar('get', "/online/alumnos/{$id}/integracion");
    }

    /** Pasa a online en su mismo grupo: la API valida el fin de trimestre del grupo y crea la cuenta de Moodle. */
    public function activarModalidadOnline(Request $request, $id)
    {
        $historial = app(HistorialAlumnoService::class);
        $antes = $historial->snapshot($id);
        $respuesta = $this->llamar('post', "/online/alumnos/{$id}/modalidad-online", $request->only([
            'dia_pago', 'moodle_password',
        ]), 60);
        if ($respuesta->getStatusCode() < 300) {
            $historial->registrarDiferencias($id, $antes, $historial->snapshot($id));
        }
        return $respuesta;
    }

    public function sincronizarMoodleAlumno($id)
    {
        return $this->llamar('post', "/online/alumnos/{$id}/moodle/sincronizar", [], 120);
    }

    /** Suspensión o reactivación MANUAL de la cuenta de Moodle. */
    public function toggleSuspensionMoodle(Request $request, $id)
    {
        return $this->llamar('post', "/online/alumnos/{$id}/moodle/suspension", [
            'accion' => $request->input('accion') === 'reactivar' ? 'reactivar' : 'suspender',
        ], 30);
    }

    public function habilitarSemanas()
    {
        return $this->llamar('post', '/online/habilitar-semanas', [], 300);
    }

    /** Planes de bloques (cursos y materias) y grupos BGNE con su calendario, en una sola respuesta. */
    public function configuracion()
    {
        try {
            $planes = Http::timeout(20)->acceptJson()->get($this->api('/online/planes'));
            $grupos = Http::timeout(20)->acceptJson()->get($this->api('/online/grupos'));
            if (!$planes->successful() || !$grupos->successful()) {
                return response()->json(['success' => false, 'message' => 'No se pudo cargar la configuración online.'], 502);
            }
            return response()->json([
                'success' => true,
                'planes'  => $planes->json('planes') ?? [],
                'grupos'  => $grupos->json('grupos') ?? [],
            ]);
        } catch (\Throwable $e) {
            Log::error('API online configuracion: ' . $e->getMessage());
            return $this->reenviar(null, 'configuracion');
        }
    }

    public function actualizarBloques(Request $request, $id)
    {
        return $this->llamar('put', "/online/planes/{$id}/bloques", ['bloques' => $request->input('bloques', [])]);
    }

    public function actualizarCurso(Request $request, $id)
    {
        return $this->llamar('put', "/online/cursos/{$id}", $request->only(['moodle_course_id', 'nombre']));
    }

    public function prepararGrupos($id)
    {
        return $this->llamar('post', "/online/cursos/{$id}/preparar-grupos", [], 60);
    }

    /** Plan de bloques de un grupo BGNE (vacío = plan base). */
    public function asignarPlanGrupo(Request $request, $id)
    {
        return $this->llamar('put', "/online/grupos/{$id}/plan", ['id_plan' => $request->input('id_plan')]);
    }

    /** Busca alumnos (presenciales u online) para integrarlos a online. */
    public function buscarPresenciales(Request $request)
    {
        $s = trim((string)$request->search);
        if (mb_strlen($s) < 2) {
            return response()->json(['success' => true, 'alumnos' => []]);
        }
        try {
            $res = Http::timeout(15)->get($this->api('/alumnos'), ['search' => $s, 'limit' => 20]);
            $alumnos = [];
            foreach (($res->successful() ? ($res->json('data') ?? []) : []) as $item) {
                $alumnos[] = [
                    'idAlumno'          => $item['idAlumno'] ?? null,
                    'nombre'            => $item['nombre'] ?? '',
                    'apPaterno'         => $item['apPaterno'] ?? '',
                    'apMaterno'         => $item['apMaterno'] ?? '',
                    'numeroControl'     => $item['numeroControl'] ?? '',
                    'modalidad_estudio' => $item['modalidad_estudio'] ?? 'PRESENCIAL',
                    'grupo'             => $item['nombreGrupoTexto'] ?? ($item['clave_grupo'] ?? ''),
                ];
            }
            return response()->json(['success' => true, 'alumnos' => $alumnos]);
        } catch (\Throwable $e) {
            Log::error('Error en buscarPresenciales API: ' . $e->getMessage());
            return response()->json(['success' => false, 'alumnos' => [], 'message' => 'No hubo conexión con la API.']);
        }
    }
}
