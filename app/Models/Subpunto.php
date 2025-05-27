<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subpunto extends Model
{
    protected $fillable = [
        'punto_id',
        'nombre',
        'codigo',
    ];
}
