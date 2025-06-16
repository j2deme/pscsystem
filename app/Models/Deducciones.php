<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Deducciones extends Model
{
    protected $fillable = [
        'user_id',
        'monto',
        'num_quincenas',
        'fecha_inicio',
        'concepto'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
