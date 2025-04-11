<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asistencia extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'fecha',
        'hora_asistencia',
        'elementos_enlistados',
        'observaciones',
        'punto',
    ];
}
