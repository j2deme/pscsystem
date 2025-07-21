<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Unidades;

class Servicio extends Model
{
    protected $fillable = [
        'unidad_id',
        'fecha',
        'descripcion',
        'costo',
        'responsable',
        'tipo',
        'siniestro_id',
        'observaciones',
    ];

    protected $casts = [
        'fecha' => 'datetime',
    ];

    public function unidad()
    {
        return $this->belongsTo(Unidades::class, 'unidad_id');
    }
    // Relación con siniestro se agregará posteriormente
}
