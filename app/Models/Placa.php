<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Placa extends Model
{
  protected $fillable = [
    'unidad_id',
    'numero',
    'fecha_asignacion',
    'fecha_baja',
    'estado',
  ];

  // Relación inversa
  public function unidad(): BelongsTo
  {
    return $this->belongsTo(Unidades::class, 'unidad_id');
  }
}
