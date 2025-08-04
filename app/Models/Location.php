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

  public function scopeGravedad($query, $gravedad)
  {
    $ahora = now('America/Mexico_City');
    switch ($gravedad) {
      case 'critica':
        return $query->where('created_at', '>=', $ahora->copy()->subMinutes(10));
      case 'alta':
        return $query->where('created_at', '<', $ahora->copy()->subMinutes(10))
          ->where('created_at', '>=', $ahora->copy()->subMinutes(20));
      case 'media':
        return $query->where('created_at', '<', $ahora->copy()->subMinutes(20))
          ->where('created_at', '>=', $ahora->copy()->subMinutes(30));
      case 'baja':
        return $query->where('created_at', '<', $ahora->copy()->subMinutes(30))
          ->where('created_at', '>=', $ahora->copy()->subMinutes(60));
      case 'antigua':
        return $query->where('created_at', '<', $ahora->copy()->subMinutes(60));
      default:
        return $query;
    }
  }
}