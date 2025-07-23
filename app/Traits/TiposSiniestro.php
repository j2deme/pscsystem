<?php

namespace App\Traits;

trait TiposSiniestro
{
  public function getTiposVehiculo()
  {
    return [
      'ASALTO' => [
        'label' => 'Asalto',
        'descripcion' => 'Robo con violencia directa hacia personas',
        'gravedad' => 'Alta'
      ],
      'ATAQUE A UNIDAD' => [
        'label' => 'Ataque a unidad',
        'descripcion' => 'Daño intencional grave al vehículo sin robo',
        'gravedad' => 'Alta'
      ],
      'ATASCO/INMOVILIZACIÓN' => [
        'label' => 'Atasco/inmovilización',
        'descripcion' => 'Unidad atrapada en lodo, vías, nieve, etc.',
        'gravedad' => 'Baja'
      ],
      'INTENTO DE ROBO A TREN' => [
        'label' => 'Intento de robo a tren',
        'descripcion' => 'Intento de robo a tren, con riesgo latente de violencia y daños',
        'gravedad' => 'Media'
      ],
      'INTENTO DE ROBO DE UNIDAD' => [
        'label' => 'Intento de robo de unidad',
        'descripcion' => 'Intento de robo de unidad, con riesgo latente de violencia y daños',
        'gravedad' => 'Media'
      ],
      'ROBO DE UNIDAD' => [
        'label' => 'Robo de unidad',
        'descripcion' => 'Pérdida total de la unidad, con posible violencia o fuerza',
        'gravedad' => 'Alta'
      ],
      'SINIESTRO EN BRECHA' => [
        'label' => 'Siniestro en brecha',
        'descripcion' => 'Accidente por derrape o salida de vía con posibles daños secundarios',
        'gravedad' => 'Media'
      ],
      'SINIESTRO EN PÉRDIDA TOTAL' => [
        'label' => 'Siniestro en pérdida total',
        'descripcion' => 'Destrucción del vehículo, daño irreparable',
        'gravedad' => 'Alta'
      ],
      'SINIESTRO NATURAL' => [
        'label' => 'Siniestro natural',
        'descripcion' => 'Daños por causas naturales como inundación, granizo, caída de árbol, etc.',
        'gravedad' => 'Baja'
      ],
      'SINIESTRO VIAL' => [
        'label' => 'Siniestro vial',
        'descripcion' => 'Accidente común sin agresión externa y lesiones leves',
        'gravedad' => 'Media'
      ],
      'SINIESTRO VIAL POR AGRESIÓN' => [
        'label' => 'Siniestro vial por agresión',
        'descripcion' => 'Accidente potencialmente grave, causado por agresión a la unidad o al personal',
        'gravedad' => 'Alta'
      ],
      'VANDALISMO' => [
        'label' => 'Vandalismo',
        'descripcion' => 'Daños superficiales al vehículo sin robo ni violencia a personas',
        'gravedad' => 'Baja'
      ],
      'OTROS' => [
        'label' => 'Otros',
        'descripcion' => 'Casos atípicos no clasificados, sin gravedad definida',
        'gravedad' => 'Variable'
      ],
    ];
  }

  public function getTiposPersonal()
  {
    return [
      'ATAQUE PERSONAL' => [
        'label' => 'Ataque personal',
        'descripcion' => 'Agresión física directa a personal, por parte de terceros',
        'gravedad' => 'Alta'
      ],
      'ACCIDENTE DE TRABAJO' => [
        'label' => 'Accidente de trabajo',
        'descripcion' => 'Lesiones sufridas por el trabajador en el ejercicio de sus funciones',
        'gravedad' => 'Media'
      ],
    ];
  }
}
