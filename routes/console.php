<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

use App\Services\MoodleService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schedule;

Artisan::command('moodle:verificar-pagos', function (MoodleService $moodleService) {
    $this->info("Iniciando verificación de estatus de pago para alumnos online...");

    $hoy = Carbon::today();
    $alumnos = DB::table('tb_alumnos')
        ->where('modalidad_estudio', 'ONLINE')
        ->whereNotNull('moodle_user_id')
        ->get();

    $suspendidos = 0;
    $activos = 0;

    foreach ($alumnos as $al) {
        $nombre = trim("{$al->nombre} {$al->apPaterno}");
        $proximo = !empty($al->fecha_proximo_pago) ? Carbon::parse($al->fecha_proximo_pago) : null;

        if ($proximo && $proximo->lt($hoy)) {
            // Pago vencido -> suspender en Moodle
            $moodleService->suspenderAlumno($al->idAlumno);
            $this->warn("[-] Alumno ID {$al->idAlumno} ({$nombre}): Vencido desde {$al->fecha_proximo_pago}. Suspendido en Moodle.");
            $suspendidos++;
        } else {
            // Al corriente -> reactivar/asegurar activo
            $moodleService->reactivarAlumno($al->idAlumno);
            $this->info("[+] Alumno ID {$al->idAlumno} ({$nombre}): Al corriente. Activo en Moodle.");
            $activos++;
        }
    }

    $this->info("Verificación completada: {$activos} activos, {$suspendidos} suspendidos por mora.");
})->purpose('Verifica las fechas de pago de los alumnos online y suspende o reactiva su cuenta en Moodle');

Schedule::command('moodle:verificar-pagos')
    ->dailyAt('00:00')
    ->description('Verifica vencimiento de pagos online y actualiza suspensión o reactivación en Moodle.');

