<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Cedula extends Model
{
    protected $fillable = [
        'ema_spyt','ema_psc','ema_montana',
        'eva_spyt','eva_psc','eva_montana',
        'mes_ema','periodo_eva'
    ];
}

