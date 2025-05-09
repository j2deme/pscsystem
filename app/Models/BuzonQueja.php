<?php

namespace App\Models;
use App\Models\User;

use Illuminate\Database\Eloquent\Model;

class BuzonQueja extends Model
{
    protected $fillable = [
        'user_id',
        'fecha',
        'asunto',
        'mensaje'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
