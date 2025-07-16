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

    // Accesor para obtener si la unidad está activa
    public function getIsActivoAttribute()
    {
        $valor   = strtolower(trim($this->estado_vehiculo));
        $activos = ['activo', 'activa', 'en servicio', 'en uso', 'disponible', 'operando', 'operativa'];
        return in_array($valor, $activos);
    }
}
