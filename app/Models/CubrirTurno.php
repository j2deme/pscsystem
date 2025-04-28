<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CubrirTurno extends Model
{
    protected $fillable = [
        'user_id',
        'punto_cobertura',
        'fecha',
        'hora_inicio',
        'hora_fin',
        'id_persona_cubierta'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
