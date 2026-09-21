<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReporteIndisciplina extends Model
{
    use HasFactory;

    protected $table = 'tb_reportes_indisciplina';

    protected $fillable = [
        'folio',
        'id_alumno',
        'alumno_nombre',
        'tutor_nombre',
        'fecha',
        'incidente',
        'parcial',
    ];
}
