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

        $limpio = fn(string $t) => trim(preg_replace('/\s+/', ' ', $t));
        $nombre = $limpio("{$al['nombre']} {$al['apPaterno']} " . ($al['apMaterno'] ?? ''));

        return [
            'id_alumno'        => $al['idAlumno'] ?? (int) $idAlumno,
            'nombre_completo'  => $mayus($nombre),
            // Formato de los documentos BGNE: apellidos primero
            'apellidos_nombre' => $mayus($limpio("{$al['apPaterno']} " . ($al['apMaterno'] ?? '') . " {$al['nombre']}")),
            'numero_control'   => (string) ($al['numeroControl'] ?? ''),
            'grupo'            => $grupo['clave'] ?? $al['nombreGrupoTexto'] ?? '',
            'semestre'         => $mayus($grupo['nombre_nivel'] ?? ''),
            'id_nivel'         => isset($grupo['id_nivel_academico']) ? (int) $grupo['id_nivel_academico'] : null,
            'modalidad'        => $mayus($grupo['modalidadHorario'] ?? $al['jornadaHorario'] ?? ''),
            'fecha_inicio'     => $iso($grupo['fechaInicioNivel'] ?? $grupo['fechaInicio'] ?? $al['fechaInicioGrupo'] ?? null),
            'fecha_fin'        => $iso($grupo['fechaFinNivel'] ?? $grupo['fechaFin'] ?? null),
            'status'           => $al['statusAlumno'] ?? '',
            'folio_certificado' => (string) ($al['folioCertificado'] ?? ''),
            'fecha_certificado' => $iso($al['fechaEmisionCertificado'] ?? null),
        ];
    }

    // =====================================================================
    // BGNE (Bachillerato General No Escolarizado)
    // =====================================================================

    private const ORDINALES_TRIMESTRE = [1 => 'PRIMER', 'SEGUNDO', 'TERCER', 'CUARTO', 'QUINTO', 'SEXTO'];

    /** Datos sugeridos para la constancia BGNE (cursando o egresado). */
    public function datosBgne($idAlumno)
    {
        $datos = $this->datosAlumno($idAlumno);
        if (!$datos) {
            return response()->json(['success' => false, 'message' => 'No se pudo obtener la información del alumno.'], 404);
        }

        // En BGNE id_nivel_academico 1–6 es el trimestre; si no, se lee de "2do Trimestre"
        $trimestre = ($datos['id_nivel'] >= 1 && $datos['id_nivel'] <= 6) ? $datos['id_nivel']
            : (preg_match('/(\d)/', $datos['semestre'], $m) ? (int) $m[1] : null);

        $modalidad = $datos['modalidad'];
        $dias = str_contains($modalidad, 'DOMINGO') ? 'DOMINGOS'
            : ((str_contains($modalidad, 'SABADO') || str_contains($modalidad, 'SÁBADO')) ? 'SÁBADOS' : '');

        $egresado = in_array(strtoupper($datos['status']), ['CERTIFICADO', 'EGRESADO'], true);
        $hoy = Carbon::now();

        return response()->json([
            'success' => true,
            'data' => $datos + [
                'tipo'             => $egresado ? 'concluyo' : 'cursando',
                'trimestre'        => $trimestre,
                'dias'             => $dias,
                'oficio'           => $hoy->format('Y/m') . '/BGNE/001',
                'fecha_expedicion' => $hoy->toDateString(),
            ],
        ]);
    }

    public function pdfBgne(Request $request)
    {
        $request->validate([
            'id_alumno'            => 'required|integer',
            'tipo'                 => 'required|in:cursando,concluyo',
            'tratamiento'          => 'nullable|in:EL,LA',
            'oficio'               => 'nullable|string|max:60',
            'trimestre'            => 'nullable|integer|between:1,6',
            'fecha_inicio'         => 'nullable|date',
            'fecha_fin'            => 'nullable|date',
            'extracurricular'      => 'nullable|boolean',
            'curso'                => 'nullable|string|max:80',
            'dias'                 => 'nullable|string|max:60',
            'horario'              => 'nullable|string|max:80',
            'fecha_ultima_materia' => 'nullable|date',
            'fecha_certificado'    => 'nullable|date',
            'folio_certificado'    => 'nullable|string|max:40',
            'fecha_expedicion'     => 'nullable|date',
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
        // "18 DE OCTUBRE DE 2026"
        $larga = fn(?Carbon $f) => $f ? $f->format('j') . ' DE ' . $mayus($f->translatedFormat('F')) . ' DE ' . $f->year : '';

        $exp = $fecha($request->get('fecha_expedicion')) ?? Carbon::now()->locale('es');
        $trimestre = (int) $request->get('trimestre');

        $pdf = Pdf::loadView('constancias.pdf_constancia_estudios_bgne', [
            'plantel'        => config('plantel.bgne'),
            'tipo'           => $request->get('tipo'),
            'tratamiento'    => $request->get('tratamiento', 'EL'),
            'oficio'         => $mayus($request->get('oficio')),
            'nombre'         => $datos['apellidos_nombre'],
            'matricula'      => $datos['numero_control'],
            'trimestre'      => self::ORDINALES_TRIMESTRE[$trimestre] ?? '',
            'periodo'        => ($request->filled('fecha_inicio') && $request->filled('fecha_fin'))
                ? 'DEL ' . $larga($fecha($request->get('fecha_inicio'))) . ' AL ' . $larga($fecha($request->get('fecha_fin')))
                : '',
            'extracurricular' => $request->boolean('extracurricular'),
            'curso'          => $mayus($request->get('curso') ?: 'COMPUTACIÓN'),
            'dias'           => $mayus($request->get('dias')),
            'horario'        => $mayus($request->get('horario') ?: 'JORNADA COMPLETA'),
            'ultimaMateria'  => $larga($fecha($request->get('fecha_ultima_materia'))),
            'fechaCertificado' => $larga($fecha($request->get('fecha_certificado'))),
            'folio'          => $mayus($request->get('folio_certificado')),
            'diaTexto'       => 'A LOS ' . $exp->format('d') . ' DÍAS DEL MES DE ' . $mayus($exp->translatedFormat('F')) . ' DE ' . $exp->year,
        ])->setPaper('letter', 'portrait');

        $archivo = 'constancia_estudios_bgne_' . preg_replace('/[^A-Za-z0-9_-]+/', '_', $datos['numero_control'] ?: (string) $request->get('id_alumno')) . '.pdf';
        return $pdf->stream($archivo);
    }
}
