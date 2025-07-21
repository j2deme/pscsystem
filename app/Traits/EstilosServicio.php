<?php

namespace App\Traits;

trait EstilosServicio
{
  public function getEstilosServicio()
  {
    return [
      'Preventivo' => [
        'badge' => 'bg-green-100 text-green-800',
        'border' => 'border-green-400 dark:border-green-600',
        'bg' => 'bg-white dark:bg-gray-900',
        'icon' => 'ti-shield-check',
        'iconColor' => 'text-green-600 dark:text-green-400',
      ],
      'Correctivo' => [
        'badge' => 'bg-yellow-100 text-yellow-800',
        'border' => 'border-yellow-400 dark:border-yellow-600',
        'bg' => 'bg-white dark:bg-gray-900',
        'icon' => 'ti-tool',
        'iconColor' => 'text-yellow-600 dark:text-yellow-400',
      ],
      'Incidencia' => [
        'badge' => 'bg-red-100 text-red-800',
        'border' => 'border-red-400 dark:border-red-600',
        'bg' => 'bg-white dark:bg-gray-900',
        'icon' => 'ti-alert-triangle',
        'iconColor' => 'text-red-600 dark:text-red-400',
      ],
      'Otros' => [
        'badge' => 'bg-gray-100 text-gray-800',
        'border' => 'border-gray-400 dark:border-gray-600',
        'bg' => 'bg-white dark:bg-gray-900',
        'icon' => 'ti-info-circle',
        'iconColor' => 'text-gray-600 dark:text-gray-400',
      ],
    ];
  }
}
