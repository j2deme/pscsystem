<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Misiones extends Model
{
    protected $fillable = [
        'agentes_id',
        'nivel_amenaza',
        'tipo_servicio',
        'nombre_clave',
        'ubicacion',
        'armados',
        'fecha_inicio',
        'fecha_fin',
        'cliente',
        'pasajeros',
        'tipo_operacion',
        'num_vehiculos',
        'tipo_vehiculos',
        'arch_mision',
        'datos_hotel',
        'datos_aeropuerto',
        'datos_vuelo',
        'datos_hospital',
        'datos_embajada',
        'lat',
        'lng',
        'estatus',
    ];
}
