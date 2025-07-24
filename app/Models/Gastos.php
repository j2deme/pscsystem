<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


/**
 * Modelo Gastos
 *
 * Representa los gastos registrados en el sistema.
 *
 * @property int $id
 * @property int $user_id
 * @property string|null $user_name
 * @property float $Monto
 * @property string $Fecha
 * @property string $Hora
 * @property string $Evidencia
 * @property string $Tipo
 * @property float|null $Km
 * @property float|null $Gasolina_antes_carga
 * @property float|null $Gasolina_despues_carga
 * @property \App\Models\User $user
 */
class Gastos extends Model
{

  use HasFactory;

  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'gastos';

  /**
   * The primary key associated with the table.
   *
   * @var string
   */
  protected $primaryKey = 'id';

  /**
   * Indicates if the model should be timestamped.
   *
   * @var bool
   */
  public $timestamps = true;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'user_id',
    'user_name',
    'Monto',
    'Fecha',
    'Hora',
    'Evidencia',
    'Tipo',
    'Km',
    'Gasolina_antes_carga',
    'Gasolina_despues_carga'
  ];

  protected $casts = [
    'Fecha' => 'date',
    'Hora' => 'string',
    'Monto' => 'decimal:2',
    'Km' => 'decimal:2',
    'Gasolina_antes_carga' => 'decimal:2',
    'Gasolina_despues_carga' => 'decimal:2'
  ];


  /**
   * Relación: Usuario asociado al gasto
   * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
   */
  public function user()
  {
    return $this->belongsTo(User::class);
  }


  /**
   * Accessor para formatear la hora en formato HH:mm
   * @param string $value
   * @return string
   */
  public function getHoraAttribute($value)
  {
    return date('H:i', strtotime($value));
  }

}