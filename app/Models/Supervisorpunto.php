<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supervisorpunto extends Model
{
    protected $fillable =[
        'id_supervisores',
        'id_puntos',
        'id_subpuntos'
    ];
}
