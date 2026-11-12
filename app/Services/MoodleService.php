<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class MoodleService
{
    protected string $baseUrl;
    protected string $token;
    protected int $roleOnlineId;

    public function __construct()
    {
        $this->baseUrl = config('services.moodle.base_url', 'https://moodle.btinteramericano.com');
        $this->token = config('services.moodle.token', '80d35103d66bd559a56a3bffd5893f3b');
        $this->roleOnlineId = (int)config('services.moodle.role_online_id', 9);
    }

    /**
     * Llamada genérica a la API REST de Moodle
     */
    public function call(string $function, array $params = []): array
    {
        $endpoint = rtrim($this->baseUrl, '/') . '/webservice/rest/server.php';

        $postData = array_merge([
            'wstoken'            => $this->token,
            'wsfunction'         => $function,
            'moodlewsrestformat' => 'json'
        ], $params);

        try {
            $response = Http::asForm()->timeout(15)->post($endpoint, $postData);

            if ($response->failed()) {
                Log::error("MoodleService error HTTP {$response->status()} en {$function}: " . $response->body());
                return ['success' => false, 'error' => "HTTP {$response->status()}: " . $response->body()];
            }

            $json = $response->json();

            if (isset($json['exception'])) {
                Log::error("MoodleService exception en {$function}: " . json_encode($json));
                return [
                    'success' => false,
                    'error'   => $json['message'] ?? 'Error en WebService de Moodle',
                    'detail'  => $json
                ];
            }

            return ['success' => true, 'data' => $json];
        } catch (\Throwable $e) {
            Log::error("MoodleService connection exception en {$function}: " . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Valida y formatea una contraseña para cumplir con la política estricta de Moodle:
     * - Mínimo 8 caracteres
     * - Al menos 1 número
     * - Al menos 1 minúscula
     * - Al menos 1 mayúscula
     * - Al menos 1 caracter especial (*, -, #, etc.)
     */
    public function formatPassword(?string $rawPassword, $idAlumno): string
    {
        $clean = trim((string)$rawPassword);

        // Si ya cumple las 5 reglas, se respeta tal cual
        if (
            strlen($clean) >= 8 &&
            preg_match('/[0-9]/', $clean) &&
            preg_match('/[a-z]/', $clean) &&
            preg_match('/[A-Z]/', $clean) &&
            preg_match('/[^a-zA-Z0-9]/', $clean)
        ) {
            return $clean;
        }

        // Si el usuario proporcionó una contraseña simple (ej. 123445)
        if (!empty($clean)) {
            return 'Bti.' . $clean . '*';
        }

        // Si no proporcionó ninguna, generamos una basada en el alumno
        return 'Bti.' . $idAlumno . '2026*';
    }

    /**
     * Consulta un usuario por su nombre de usuario (ej. alu4)
     */
    public function getUserByUsername(string $username): ?array
    {
        $res = $this->call('core_user_get_users_by_field', [
            'field'  => 'username',
            'values' => [$username]
        ]);

        if ($res['success'] && !empty($res['data']) && is_array($res['data'])) {
            return $res['data'][0] ?? null;
        }

        return null;
    }

    /**
     * Sincroniza al alumno en Moodle (creación o actualización)
     * Guarda el moodle_user_id en la base de datos local
     */
    public function syncAlumno($alumno, ?string $rawPassword = null): array
    {
        $idAlumno = is_object($alumno) ? $alumno->idAlumno : ($alumno['idAlumno'] ?? null);
        if (!$idAlumno) {
            return ['success' => false, 'error' => 'ID de alumno no válido'];
        }

        $nombre = is_object($alumno) ? $alumno->nombre : ($alumno['nombre'] ?? 'Alumno');
        $apPaterno = is_object($alumno) ? $alumno->apPaterno : ($alumno['apPaterno'] ?? '');
        $apMaterno = is_object($alumno) ? ($alumno->apMaterno ?? '') : ($alumno['apMaterno'] ?? '');
        $apellidos = trim($apPaterno . ' ' . $apMaterno) ?: 'General';
        $email = is_object($alumno) ? ($alumno->correoAlumno ?? '') : ($alumno['correoAlumno'] ?? '');

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $email = "alu{$idAlumno}@btinteramericano.com";
        }

        $username = "alu{$idAlumno}";
        $password = $this->formatPassword($rawPassword, $idAlumno);

        // 1. Verificar si el usuario ya existe en Moodle
        $existing = $this->getUserByUsername($username);

        if ($existing && !empty($existing['id'])) {
            $moodleId = $existing['id'];

            // Actualizar contraseña y asegurar que esté activo (suspended = 0)
            $updateParams = [
                'users' => [
                    [
                        'id'        => $moodleId,
                        'suspended' => 0,
                        'password'  => $password
                    ]
                ]
            ];
            $this->call('core_user_update_users', $updateParams);

            // Asignar rol Estudiante Online (para visualizar widgets y permisos de modalidad online)
            $this->assignRoleOnline($moodleId);

            // Guardar en base de datos local
            DB::table('tb_alumnos')->where('idAlumno', $idAlumno)->update([
                'moodle_user_id'   => $moodleId,
                'moodle_username'  => $username,
                'moodle_password'  => $password
            ]);

            return [
                'success'         => true,
                'action'          => 'updated',
                'moodle_user_id'  => $moodleId,
                'moodle_username' => $username,
                'moodle_password' => $password,
                'message'         => 'Usuario sincronizado y reactivado en Moodle exitosamente.'
            ];
        }

        // 2. Si no existe, crearlo
        $createParams = [
            'users' => [
                [
                    'username'    => $username,
                    'password'    => $password,
                    'firstname'   => $nombre,
                    'lastname'    => $apellidos,
                    'email'       => $email,
                    'auth'        => 'manual',
                    'lang'        => 'es_mx'
                ]
            ]
        ];

        $createRes = $this->call('core_user_create_users', $createParams);

        if (!$createRes['success']) {
            return [
                'success' => false,
                'error'   => 'Error de Moodle al crear usuario: ' . ($createRes['error'] ?? 'Desconocido')
            ];
        }

        $createdData = $createRes['data'];
        $moodleId = $createdData[0]['id'] ?? null;

        if (!$moodleId) {
            return [
                'success' => false,
                'error'   => 'Moodle no devolvió un ID de usuario válido.'
            ];
        }

        // Asignar rol Estudiante Online en Moodle
        $this->assignRoleOnline($moodleId);

        // Guardar en base de datos local
        DB::table('tb_alumnos')->where('idAlumno', $idAlumno)->update([
            'moodle_user_id'   => $moodleId,
            'moodle_username'  => $username,
            'moodle_password'  => $password
        ]);

        return [
            'success'         => true,
            'action'          => 'created',
            'moodle_user_id'  => $moodleId,
            'moodle_username' => $username,
            'moodle_password' => $password,
            'message'         => 'Usuario creado en Moodle exitosamente.'
        ];
    }

    /**
     * Asigna el rol Estudiante Online (ID 9) al alumno a nivel de sistema
     */
    public function assignRoleOnline(int $moodleUserId): array
    {
        return $this->call('core_role_assign_roles', [
            'assignments' => [
                [
                    'roleid'    => $this->roleOnlineId,
                    'userid'    => $moodleUserId,
                    'contextid' => 1
                ]
            ]
        ]);
    }

    /**
     * Retira el rol Estudiante Online al alumno
     */
    public function unassignRoleOnline(int $moodleUserId): array
    {
        return $this->call('core_role_unassign_roles', [
            'unassignments' => [
                [
                    'roleid'    => $this->roleOnlineId,
                    'userid'    => $moodleUserId,
                    'contextid' => 1
                ]
            ]
        ]);
    }

    /**
     * Suspende la cuenta del alumno en Moodle y le retira el rol online (cuando regresa a presencial o causa baja)
     */
    public function suspenderAlumno($idAlumno): array
    {
        $alumno = DB::table('tb_alumnos')->where('idAlumno', $idAlumno)->first();
        if (!$alumno || empty($alumno->moodle_user_id)) {
            return ['success' => true, 'message' => 'No requiere suspensión en Moodle'];
        }

        $moodleUserId = (int)$alumno->moodle_user_id;

        // 1. Quitar rol de estudiante online
        $this->unassignRoleOnline($moodleUserId);

        // 2. Suspender cuenta en Moodle
        $res = $this->call('core_user_update_users', [
            'users' => [
                [
                    'id'        => $moodleUserId,
                    'suspended' => 1
                ]
            ]
        ]);

        return $res;
    }

    /**
     * Enrola a un estudiante en un curso específico de Moodle
     */
    public function enrolStudentInCourse(int $moodleUserId, int $courseId, int $roleId = 5): array
    {
        return $this->call('enrol_manual_enrol_users', [
            'enrolments' => [
                [
                    'roleid'    => $roleId,
                    'userid'    => $moodleUserId,
                    'courseid'  => $courseId,
                    'timestart' => time(),
                    'timeend'   => 0,
                    'suspend'   => 0
                ]
            ]
        ]);
    }

    /**
     * Enrola al alumno en las 2 materias activas de su trimestre y mes bloque actual
     */
    public function enrolStudentInActiveMaterias(int $idAlumno): array
    {
        $alumno = DB::table('tb_alumnos')->where('idAlumno', $idAlumno)->first();
        if (!$alumno || empty($alumno->moodle_user_id)) {
            return ['success' => false, 'error' => 'Alumno no cuenta con ID de Moodle'];
        }

        $trimInicio = max(1, min(6, (int)($alumno->trimestre_inicio_online ?: 1)));
        $semanasTotales = max(0, (int)($alumno->semanas_totales_pagadas ?: 0));

        $trimestresAvanzados = (int)floor($semanasTotales / 12);
        $trimestreActual = min(6, $trimInicio + $trimestresAvanzados);
        $semanasEnTrimestre = $semanasTotales % 12;
        $mesActual = min(3, (int)floor($semanasEnTrimestre / 4) + 1);

        // Buscar materias de este bloque que tengan ID de Moodle configurado
        $materias = DB::table('tb_materias')
            ->where('idCentroTrabajo', 3)
            ->where('id_nivel_academico', $trimestreActual)
            ->where('mes_bloque', $mesActual)
            ->whereNotNull('id_curso_moodle')
            ->get();

        $enrolados = [];
        $errores = [];

        foreach ($materias as $mat) {
            $courseId = (int)$mat->id_curso_moodle;
            if ($courseId > 1) {
                $res = $this->enrolStudentInCourse((int)$alumno->moodle_user_id, $courseId);
                if ($res['success']) {
                    $enrolados[] = "{$mat->nombreMateria} (Curso {$courseId})";
                } else {
                    $errores[] = "{$mat->nombreMateria}: " . ($res['error'] ?? 'Error desconocido');
                }
            }
        }

        return [
            'success'   => count($errores) === 0,
            'enrolados' => $enrolados,
            'errores'   => $errores
        ];
    }

    /**
     * Reactiva la cuenta del alumno en Moodle (suspended = 0) y reasigna su rol online
     */
    public function reactivarAlumno($idAlumno): array
    {
        $alumno = DB::table('tb_alumnos')->where('idAlumno', $idAlumno)->first();
        if (!$alumno || empty($alumno->moodle_user_id)) {
            return ['success' => false, 'error' => 'Alumno no cuenta con cuenta Moodle'];
        }

        $moodleUserId = (int)$alumno->moodle_user_id;

        // 1. Quitar suspensión en Moodle
        $res = $this->call('core_user_update_users', [
            'users' => [
                [
                    'id'        => $moodleUserId,
                    'suspended' => 0
                ]
            ]
        ]);

        // 2. Asignar rol Estudiante Online
        $this->assignRoleOnline($moodleUserId);

        return $res;
    }

    /**
     * Evalúa el estatus de pago y actualiza el acceso a Moodle:
     * - Si tiene pago vencido: suspende el usuario en Moodle
     * - Si está al corriente: reactiva el usuario y garantiza que esté activo
     */
    public function actualizarEstatusAccesoPorPago(int $idAlumno): array
    {
        $alumno = DB::table('tb_alumnos')->where('idAlumno', $idAlumno)->first();
        if (!$alumno || $alumno->modalidad_estudio !== 'ONLINE' || empty($alumno->moodle_user_id)) {
            return ['success' => true, 'action' => 'none'];
        }

        $hoy = \Carbon\Carbon::today();
        $proximo = !empty($alumno->fecha_proximo_pago) ? \Carbon\Carbon::parse($alumno->fecha_proximo_pago) : null;

        // Si tiene fecha de pago vencida por más de 1 día
        if ($proximo && $proximo->lt($hoy)) {
            $this->suspenderAlumno($idAlumno);
            return ['success' => true, 'action' => 'suspended', 'message' => 'Cuenta suspendida en Moodle por pago vencido'];
        } else {
            $this->reactivarAlumno($idAlumno);
            return ['success' => true, 'action' => 'active', 'message' => 'Cuenta activa en Moodle'];
        }
    }
}
