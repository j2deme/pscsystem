<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Confronta extends Model
{
    protected $fillable = [
        'inf_psc',
        'inf_spyt',
        'inf_montana',
        'exc_psc',
        'exc_spyt',
        'exc_montana',
    ];
}
