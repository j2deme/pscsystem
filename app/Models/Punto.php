<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Punto extends Model
{
    protected $fillable = [
        'nombre',
    ];

    public function subpuntos() {
        return $this->hasMany(Subpunto::class);
    }
}
