<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mensaje extends Model
{
    protected $fillable= [
        'id_remitente',
        'id_destinatario',
        'mensaje',
    ];
}
