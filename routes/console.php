<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Modalidad online: no hay suspensiones ni bajas automáticas por adeudo. El atraso se muestra con el semáforo
// y las semanas no pagadas se bloquean con los grupos de Moodle. La habilitación de semanas pagadas por
// adelantado (sábados 07:00) la ejecuta la API (app/utils/scheduler.py).

