<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Siniestro extends Model
{
  protected $fillable = [
    'tipo_siniestro',
    'unidad_id',
    'fecha',
    'tipo',
    'zona',
    'descripcion',
    'seguimiento',
    'costo',
  ];

  protected $casts = [
    'fecha' => 'date',
    'costo' => 'float',
  ];

  // Relación con usuarios involucrados
  public function usuarios(): BelongsToMany
  {
    return $this->belongsToMany(User::class, 'siniestro_user', 'siniestro_id', 'user_id');
  }

  // Relación con unidad (solo para siniestros de vehículo)
  public function unidad(): BelongsTo
  {
    return $this->belongsTo(Unidades::class, 'unidad_id');
  }
}
