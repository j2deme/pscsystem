<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unidades extends Model
{
    protected $fillable = [
        'id',
        'nombre_propietario',
        'zona',
        'marca',
        'modelo',
        'placas',
        'kms',
        'asignacion_punto',
        'estado_vehiculo',
        'observaciones',
    ];
}
