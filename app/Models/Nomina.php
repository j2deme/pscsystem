<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nomina extends Model
{
    protected $fillable = [
        'user_id',
        'periodo',
        'monto'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
