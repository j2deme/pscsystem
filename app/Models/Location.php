<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo Location
 *
 * Representa una ubicación registrada en el sistema.
 *
 * @property int $id
 * @property int $user_id
 * @property float $latitude
 * @property float $longitude
 * @property \App\Models\User $user
 */
class Location extends Model
{
  protected $table = 'locations';
  protected $fillable = [
    'user_id',
    'latitude',
    'longitude'
  ];

  protected $casts = [
    'created_at' => 'datetime',
    'updated_at' => 'datetime',
    'latitude' => 'decimal:8',
    'longitude' => 'decimal:8',
  ];

  /**
   * Relación: Usuario asociado a la ubicación
   * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
   */
  public function user()
  {
    return $this->belongsTo(User::class);
  }
}