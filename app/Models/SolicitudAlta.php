<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SolicitudAlta extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        //id_supervisor
        'nombre',
        'apellido_paterno',
        'apellido_materno',
        'fecha_nacimiento',
        'curp',
        'rfc',
        'nss',
        'estado_civil',
        'domicilio_calle',
        'domicilio_numero',
        'domicilio_colonia',
        'domicilio_ciudad',
        'domicilio_estado',
        'telefono',
        'email',
        'estatura',
        'peso',
        'status',
        'observaciones',
        'rol',
        'created_at',
        'updated_at',
    ];
}
