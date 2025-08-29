<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Compra extends Model
{
  use HasFactory;

  protected $table = 'compras';
  protected $primaryKey = 'id';
  public $timestamps = true;

  protected $fillable = [
    'unidad_id',        // Relación con unidad/placas (puede ser null para compras generales)
    'fecha_hora',       // Fecha y hora combinadas (datetime)
    'tipo',             // Tipo de gasto/compra (Refacción, Insumo, Servicio Menor, Compra Directa, etc.)
    'descripcion',      // Descripción detallada del item o servicio adquirido
    'proveedor',        // Proveedor o entidad que suministra
    'costo',            // Costo (puede ser null, aunque lo común es que tenga costo)
    'kilometraje',      // Km del vehículo al momento de la compra (opcional)
    'garantia',         // Indica si el servicio/refacción se realizó bajo garantía (boolean)
    'notas',            // Notas adicionales
  ];

  protected $casts = [
    'fecha_hora' => 'datetime',
    'costo' => 'decimal:2',
    'kilometraje' => 'integer',
    'garantia' => 'boolean', // Cast a booleano para facilitar su uso
  ];

  // Relación con unidad (opcional, para permitir null)
  public function unidad()
  {
    return $this->belongsTo(Unidades::class, 'unidad_id')->withDefault();
  }

  // Scopes para filtros
  public function scopePorFecha($query, $fechaInicio, $fechaFin)
  {
    return $query->whereBetween('fecha_hora', [$fechaInicio, $fechaFin]);
  }

  public function scopePorUnidad($query, $unidadId)
  {
    return $query->where('unidad_id', $unidadId);
  }

  public function scopePorProveedor($query, $proveedor)
  {
    return $query->where('proveedor', 'LIKE', "%{$proveedor}%");
  }

  public function scopePorTipo($query, $tipo)
  {
    return $query->where('tipo', $tipo);
  }

  // Scope para filtrar por garantía
  public function scopeConGarantia($query, $tieneGarantia = true)
  {
    return $query->where('garantia', $tieneGarantia);
  }

  // Accessor para solo la fecha (si se necesita)
  public function getFechaAttribute()
  {
    return $this->fecha_hora?->toDateString();
  }

  // Accessor para solo la hora (si se necesita)
  public function getHoraAttribute()
  {
    return $this->fecha_hora?->format('H:i');
  }
}