<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

/**
 * Constancia de estudios del BTI (oficio dirigido "A quien corresponda").
 * Los datos del alumno y del semestre se toman de la API; el front puede ajustar
 * semestre, periodo, número de oficio y fecha de expedición antes de generar el PDF.
 */
class ConstanciaEstudiosController extends Controller
{
    /** Datos sugeridos para prellenar el formulario de la constancia. */
    public function datosBti($idAlumno)
    {
        $datos = $this->datosAlumno($idAlumno);
        if (!$datos) {
            return response()->json(['success' => false, 'message' => 'No se pudo obtener la información del alumno.'], 404);
        }

        $hoy = Carbon::now();
        return response()->json([
            'success' => true,
            'data' => $datos + [
                'oficio'      => $hoy->format('Y/m') . '/BTI/001',
                'fecha_expedicion' => $hoy->toDateString(),
            ],
        ]);
    }

    public function pdfBti(Request $request)
    {
        $request->validate([
            'id_alumno'        => 'required|integer',
            'semestre'         => 'nullable|string|max:40',
            'fecha_inicio'     => 'nullable|date',
            'fecha_fin'        => 'nullable|date',
            'fecha_expedicion' => 'nullable|date',
            'oficio'           => 'nullable|string|max:60',
        ]);

        $datos = $this->datosAlumno($request->get('id_alumno'));
        if (!$datos) {
            abort(404, 'No se pudo obtener la información del alumno.');
        }

        $mayus = fn($v) => mb_strtoupper(trim((string) $v), 'UTF-8');
        $fecha = function ($valor) {
            if (!$valor) return null;
            try { return Carbon::parse($valor)->locale('es'); } catch (\Throwable $e) { return null; }
        };

        $inicio = $fecha($request->get('fecha_inicio') ?: $datos['fecha_inicio']);
        $fin = $fecha($request->get('fecha_fin') ?: $datos['fecha_fin']);
        $expedicion = $fecha($request->get('fecha_expedicion')) ?? Carbon::now()->locale('es');
        $mes = fn(Carbon $f) => $mayus($f->translatedFormat('F'));

        $pdf = Pdf::loadView('constancias.pdf_constancia_estudios_bti', [
            'plantel'     => config('plantel.bti'),
            'oficio'      => $mayus($request->get('oficio') ?: $expedicion->format('Y/m') . '/BTI/001'),
            'nombre'      => $datos['nombre_completo'],
            'matricula'   => $datos['numero_control'],
            'semestre'    => $mayus($request->get('semestre') ?: $datos['semestre']),
            // "DEL 31 DE AGOSTO DEL 2026 AL 18 DE DICIEMBRE DE 2026", como en el formato en papel
            'periodo'     => ($inicio && $fin)
                ? "DEL {$inicio->format('j')} DE {$mes($inicio)} DEL {$inicio->year} AL {$fin->format('j')} DE {$mes($fin)} DE {$fin->year}"
                : '',
            'diaTexto'    => "A LOS {$expedicion->format('j')} DÍAS DEL MES DE {$mes($expedicion)} DE {$expedicion->year}",
            'fechaFirma'  => "{$expedicion->format('j')} DE {$mes($expedicion)} DE {$expedicion->year}",
        ])->setPaper('letter', 'portrait');

        $archivo = 'constancia_estudios_' . preg_replace('/[^A-Za-z0-9_-]+/', '_', $datos['numero_control'] ?: (string) $request->get('id_alumno')) . '.pdf';
        return $pdf->stream($archivo);
    }

    /** Nombre, número de control, semestre y periodo del alumno según su grupo actual. */
    private function datosAlumno($idAlumno): ?array
    {
        $api = config('services.api.base_url');
        try {
            $res = Http::timeout(5)->get("{$api}/alumno/{$idAlumno}");
            if (!$res->successful()) return null;
            $al = $res->json()['data'] ?? null;
            if (!$al) return null;
        } catch (\Throwable $e) {
            return null;
        }

        $grupo = [];
        if (!empty($al['idGrupo'])) {
            try {
                $resG = Http::timeout(4)->get("{$api}/getGrupo/{$al['idGrupo']}");
                if ($resG->successful()) $grupo = $resG->json()['data'] ?? $resG->json() ?? [];
            } catch (\Throwable $e) {}
        }

        $mayus = fn($v) => mb_strtoupper(trim((string) $v), 'UTF-8');
        $iso = function ($valor) {
            if (!$valor) return null;
            try { return Carbon::parse($valor)->toDateString(); } catch (\Throwable $e) { return null; }
        };

        $nombre = trim(preg_replace('/\s+/', ' ', "{$al['nombre']} {$al['apPaterno']} " . ($al['apMaterno'] ?? '')));

        return [
            'id_alumno'       => $al['idAlumno'] ?? (int) $idAlumno,
            'nombre_completo' => $mayus($nombre),
            'numero_control'  => (string) ($al['numeroControl'] ?? ''),
            'grupo'           => $grupo['clave'] ?? $al['nombreGrupoTexto'] ?? '',
            'semestre'        => $mayus($grupo['nombre_nivel'] ?? ''),
            'fecha_inicio'    => $iso($grupo['fechaInicioNivel'] ?? $grupo['fechaInicio'] ?? $al['fechaInicioGrupo'] ?? null),
            'fecha_fin'       => $iso($grupo['fechaFinNivel'] ?? $grupo['fechaFin'] ?? null),
            'status'          => $al['statusAlumno'] ?? '',
        ];
    }
}
