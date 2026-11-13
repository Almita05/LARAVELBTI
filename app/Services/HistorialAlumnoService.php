<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Registra y consulta el historial de cambios de un alumno.
 * Nunca interrumpe la operación principal: cualquier fallo se registra en el log y se ignora.
 */
class HistorialAlumnoService
{
    /** Campos vigilados → etiqueta legible. El grupo y la generación se comparan por su nombre. */
    private const CAMPOS_PERFIL = [
        'nombre' => 'Nombre',
        'apPaterno' => 'Apellido paterno',
        'apMaterno' => 'Apellido materno',
        'fechaNacimiento' => 'Fecha de nacimiento',
        'curp' => 'CURP',
        'numeroControl' => 'Matrícula',
        'nombreGeneracionTexto' => 'Generación',
        'tutor' => 'Tutor',
        'parentesco' => 'Parentesco',
        'telefonoTutor' => 'Teléfono del tutor',
        'celularAlumno' => 'Celular',
        'correoAlumno' => 'Correo',
        'calle' => 'Calle',
        'colonia' => 'Colonia',
        'localidad' => 'Localidad',
        'municipio' => 'Municipio',
        'escuelaProcedencia' => 'Escuela de procedencia',
        'observaciones' => 'Observaciones',
        'equivalencia' => 'Equivalencia',
        'certificado_incompleto' => 'Certificado incompleto',
        'trae_boleta' => 'Trae boleta',
        'estado_pago_equivalencia' => 'Pago de equivalencia',
        'folioCertificado' => 'Folio de certificado',
        'recogioCertificado' => 'Recogió certificado',
        'dia_pago' => 'Día de pago',
    ];

    /** Snapshot del alumno tal como lo devuelve la API (incluye nombres de grupo/generación). */
    public function snapshot($idAlumno): ?array
    {
        try {
            $res = Http::timeout(5)->get(config('services.api.base_url') . '/alumno/' . $idAlumno);
            return $res->successful() ? ($res->json()['data'] ?? null) : null;
        } catch (\Throwable $e) {
            Log::warning("Historial: no se pudo leer el alumno {$idAlumno}: " . $e->getMessage());
            return null;
        }
    }

    /** Compara dos snapshots y guarda un evento por cada tipo de cambio. */
    public function registrarDiferencias($idAlumno, ?array $antes, ?array $despues): void
    {
        if (!$antes || !$despues) return;

        $grupo = $this->cambio('Grupo', $antes['nombreGrupoTexto'] ?? null, $despues['nombreGrupoTexto'] ?? null, 'Sin grupo');
        if ($grupo) $this->registrar($idAlumno, 'GRUPO', 'Cambio de grupo', [$grupo]);

        $estatus = $this->cambio('Estatus', $antes['statusAlumno'] ?? null, $despues['statusAlumno'] ?? null);
        if ($estatus) $this->registrar($idAlumno, 'ESTATUS', 'Cambio de estatus', [$estatus]);

        $modalidad = $this->cambio('Modalidad', $antes['modalidad_estudio'] ?? null, $despues['modalidad_estudio'] ?? null);
        if ($modalidad) $this->registrar($idAlumno, 'MODALIDAD', 'Cambio de modalidad', [$modalidad]);

        $perfil = [];
        foreach (self::CAMPOS_PERFIL as $campo => $etiqueta) {
            $c = $this->cambio($etiqueta, $antes[$campo] ?? null, $despues[$campo] ?? null);
            if ($c) $perfil[] = $c;
        }
        if ($perfil) $this->registrar($idAlumno, 'PERFIL', 'Edición de perfil', $perfil);
    }

    public function registrar($idAlumno, string $tipo, string $titulo, array $cambios = []): void
    {
        try {
            $res = Http::timeout(5)->post(config('services.api.base_url') . "/alumnos/{$idAlumno}/historial", [
                'tipo'       => $tipo,
                'titulo'     => $titulo,
                'cambios'    => $cambios,
                'usuario'    => session('nombre') ?: (session('usuario') ?: null),
                'usuario_id' => session('usuario_id') ?: (session('usuario') ?: null),
            ]);
            if (!$res->successful()) {
                Log::error("Historial: la API rechazó '{$titulo}' del alumno {$idAlumno}: HTTP {$res->status()}");
            }
        } catch (\Throwable $e) {
            Log::error("Historial: no se pudo registrar '{$titulo}' del alumno {$idAlumno}: " . $e->getMessage());
        }
    }

    /** Eventos del alumno (vía API), más recientes primero. La API arma el alta con createAt/createBy si falta. */
    public function listar($idAlumno): array
    {
        $res = Http::timeout(8)->get(config('services.api.base_url') . '/alumnos/' . (int) $idAlumno . '/historial');
        $res->throw();
        return $res->json('data') ?? [];
    }

    private function cambio(string $campo, $antes, $despues, string $vacio = '—'): ?array
    {
        $a = $this->normalizar($antes);
        $d = $this->normalizar($despues);
        if ($a === $d) return null;
        return ['campo' => $campo, 'antes' => $a === '' ? $vacio : $a, 'despues' => $d === '' ? $vacio : $d];
    }

    private function normalizar($v): string
    {
        if ($v === null) return '';
        $v = trim((string) $v);
        return in_array(strtoupper($v), ['N/A', 'NONE', 'NULL'], true) ? '' : $v;
    }
}
