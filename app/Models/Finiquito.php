<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Finiquito extends Model
{
    protected $fillable = [
        'baja_id',
        'monto',
    ];

    public function baja()
{
    return $this->belongsTo(\App\Models\SolicitudBajas::class, 'baja_id');
}
}
