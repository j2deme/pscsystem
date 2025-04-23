<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TiemposExtra extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'fecha',
        'hora_inicio',
        'hora_fin',
        'total_horas',
        'autorizado_por',
        'observaciones'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
