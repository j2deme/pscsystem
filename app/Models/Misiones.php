<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Misiones extends Model
{
    protected $fillable = [
        'agentes_id',
        'tipo_servicio',
        'ubicacion',
        'fecha_inicio',
        'fecha_fin',
        'cliente',
        'pasajeros',
        'tipo_operacion',
        'num_vehiculos',
        'tipo_vehiculos',
    ];
}
