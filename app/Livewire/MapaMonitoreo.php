<?php

namespace App\Livewire;

use Livewire\Component;
use Carbon\Carbon;
use App\Models\Location;

class MapaMonitoreo extends Component
{
  public $totalAlertas = 0;
  public $alertasActivas = 0;
  public $autoRefresh = true;
  public $alertasRecientes = [];

  private $RECENT_SPAN = 30; // Minutos
  private $ACTIVE_SPAN = 300; // Antigüedad máxima para mostrar alertas activas (5 horas)
  private static $configuracionColoresCache = null;

  public function mount()
  {
    $this->actualizarDatos();
  }

  private function actualizarDatos()
  {
    $locations = Location::with([
      'user' => function ($query) {
        $query->select(['id', 'name', 'punto']); // Asegúrate de incluir 'id' para la relación
      }
    ])
      ->select(['id', 'user_id', 'latitude', 'longitude', 'created_at'])
      ->where('created_at', '>=', Carbon::now()->subMinutes($this->ACTIVE_SPAN))
      ->orderBy('created_at', 'desc')
      ->get();

    $alertas = $locations->map(function ($location) {
      $timestampCreacion    = $location->created_at;
      $minutosTranscurridos = $timestampCreacion->diffInMinutes(Carbon::now('America/Mexico_City'));
      $estado = $this->calcularEstadoPorTiempo($minutosTranscurridos);

      // Mapeo explícito de campos para la vista
      $alerta = [
        'usuario' => $location->user->name ?? 'Usuario desconocido',
        'estado' => $estado['estado'],
        'estadoTexto' => $estado['texto'],
        'minutosTranscurridos' => $minutosTranscurridos,
        'fecha' => $timestampCreacion->format('d/m/y'),
        'tiempo' => $timestampCreacion->format('h:i A'),
        'descripcion' => 'Ubicación reportada',
        'latitud' => $location->latitude,
        'longitud' => $location->longitude,
        'ubicacion' => $location->user->punto ?? null,
        'timestamp_creacion' => $timestampCreacion->timestamp,
        'id' => $location->id,
      ];
      return $alerta;
    });

    $this->alertasRecientes = $this->procesarAlertasParaVista($alertas);

    // Calcular estadísticas basadas en las alertas generadas
    $this->totalAlertas   = count($this->alertasRecientes);
    $this->alertasActivas = collect($this->alertasRecientes)->filter(function ($alerta) {
      // Consideramos activas las alertas de menos de 30 minutos
      return $alerta['minutosTranscurridos'] <= $this->RECENT_SPAN;
    })->count();
  }

  private function calcularEstadoPorTiempo($minutosTranscurridos)
  {
    if ($minutosTranscurridos <= 10) {
      return ['estado' => 'critica', 'texto' => 'CRÍTICA'];
    } elseif ($minutosTranscurridos <= 20) {
      return ['estado' => 'alta', 'texto' => 'ALTA'];
    } elseif ($minutosTranscurridos <= 30) {
      return ['estado' => 'media', 'texto' => 'MEDIA'];
    } elseif ($minutosTranscurridos <= 60) {
      return ['estado' => 'baja', 'texto' => 'BAJA'];
    } else {
      return ['estado' => 'antigua', 'texto' => 'ANTIGUA'];
    }
  }

  /**
   * Procesar alertas con toda la información necesaria para la vista
   */
  private function procesarAlertasParaVista($alertas)
  {
    // Filtrar alertas con más de 5 horas (300 minutos) de antigüedad
    return collect($alertas)
      ->filter(function ($alerta) {
        return ($alerta['minutosTranscurridos'] ?? 0) <= $this->ACTIVE_SPAN;
      })
      ->map(function ($alerta) {
        $estado  = $alerta['estado'] ?? 'critica';
        $colores = $this->obtenerConfiguracionColores($estado);
        return array_merge($alerta, [
          'colores' => $colores,
          'estadoCompleto' => [
            'codigo' => $estado,
            'texto' => $alerta['estadoTexto'] ?? 'N/A',
            'colores' => $colores
          ]
        ]);
      })
      ->toArray();
  }

  public function actualizarTiempoReal()
  {
    $ahora       = Carbon::now('America/Mexico_City');
    $huboCambios = false;

    foreach ($this->alertasRecientes as $index => $alerta) {
      if (isset($alerta['timestamp_creacion'])) {
        $minutosActuales = $ahora->diffInMinutes(Carbon::createFromTimestamp($alerta['timestamp_creacion'], 'America/Mexico_City'));

        // Solo actualizar si el tiempo realmente cambió (por ejemplo, pasó un minuto)
        // O si es necesario verificar cambios de estado (esto es más complejo)
        // Por simplicidad, actualizamos siempre, pero podrías añadir una condición.
        if ($minutosActuales != $alerta['minutosTranscurridos']) {
          $estadoDinamico = $this->calcularEstadoPorTiempo($minutosActuales);

          // Verificar si el estado cambió para optimizar la actualización de colores
          $estadoCambio = $alerta['estado'] !== $estadoDinamico['estado'];

          $this->alertasRecientes[$index]['minutosTranscurridos'] = $minutosActuales;
          $this->alertasRecientes[$index]['estado']               = $estadoDinamico['estado'];
          $this->alertasRecientes[$index]['estadoTexto']          = $estadoDinamico['texto'];

          // Solo recalcular colores si el estado cambió
          if ($estadoCambio) {
            $colores                                          = $this->obtenerConfiguracionColores($estadoDinamico['estado']);
            $this->alertasRecientes[$index]['colores']        = $colores;
            $this->alertasRecientes[$index]['estadoCompleto'] = [
              'codigo' => $estadoDinamico['estado'],
              'texto' => $estadoDinamico['texto'],
              'colores' => $colores
            ];
            $huboCambios                                      = true; // Indicar que al menos un marcador necesita actualización
          }
        }
      }
    }

    // Recalcular estadísticas solo si es necesario o periódicamente
    $this->totalAlertas   = count($this->alertasRecientes);
    $this->alertasActivas = collect($this->alertasRecientes)->filter(function ($alerta) {
      return ($alerta['minutosTranscurridos'] ?? 0) <= $this->RECENT_SPAN;
    })->count();

    // Enviar evento para actualizar la interfaz si hubo cambios relevantes
    if ($huboCambios) {
      $this->dispatch('actualizarMarcadores', alertas: $this->alertasRecientes);
    }
  }

  /**
   * Obtener configuración completa de colores para un estado
   */
  private function obtenerConfiguracionColores($estado)
  {
    if (self::$configuracionColoresCache === null) {
      self::$configuracionColoresCache = [
        'critica' => [
          'border' => 'border-red-600',
          'bg' => 'bg-red-50 dark:bg-red-900/20',
          'indicator' => 'bg-red-600',
          'text' => 'text-red-700 dark:text-red-300',
          'badge' => 'bg-red-600',
          'animate' => true
        ],
        'alta' => [
          'border' => 'border-orange-500',
          'bg' => 'bg-orange-50 dark:bg-orange-900/20',
          'indicator' => 'bg-orange-500',
          'text' => 'text-orange-700 dark:text-orange-300',
          'badge' => 'bg-orange-500',
          'animate' => true
        ],
        'media' => [
          'border' => 'border-yellow-500',
          'bg' => 'bg-yellow-50 dark:bg-yellow-900/20',
          'indicator' => 'bg-yellow-500',
          'text' => 'text-yellow-700 dark:text-yellow-300',
          'badge' => 'bg-yellow-500',
          'animate' => false
        ],
        'baja' => [
          'border' => 'border-blue-400',
          'bg' => 'bg-blue-50 dark:bg-blue-900/20',
          'indicator' => 'bg-blue-400',
          'text' => 'text-blue-700 dark:text-blue-300',
          'badge' => 'bg-blue-500',
          'animate' => false
        ],
        'antigua' => [
          'border' => 'border-gray-400',
          'bg' => 'bg-gray-50 dark:bg-gray-800',
          'indicator' => 'bg-gray-400',
          'text' => 'text-gray-600 dark:text-gray-400',
          'badge' => 'bg-gray-500',
          'animate' => false
        ]
      ];
    }

    return self::$configuracionColoresCache[$estado] ?? self::$configuracionColoresCache['antigua'];
  }

  public function render()
  {
    return view('livewire.mapa-monitoreo', [
      'alertasRecientes' => $this->alertasRecientes
    ]);
  }
}
