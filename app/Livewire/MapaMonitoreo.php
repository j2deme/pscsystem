<?php

namespace App\Livewire;

use Livewire\Component;
use Carbon\Carbon;
use App\Models\Location;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;

class MapaMonitoreo extends Component
{
  public $totalAlertas = 0;
  public $alertasActivas = 0;
  public $alertasRecientes = [];
  public $filtroGravedad = "todas";
  public $filtroUsuario = '';
  public $cargando = false;

  private $RECENT_SPAN = 30; // Minutos
  private $ACTIVE_SPAN = 300; // Antigüedad máxima para mostrar alertas activas (5 horas)
  private static $configuracionColoresCache = null;

  public function mount()
  {
    $this->cargando = true;
    $this->actualizarDatos();
  }

  public function iniciarCarga()
  {
    $this->cargando = true;
  }

  public function finalizarCarga()
  {
    $this->cargando = false;
  }

  public function updatedFiltroGravedad()
  {
    $this->iniciarCarga();
    $this->actualizarDatos();
    $this->dispatch('alertasActualizadas', alertas: $this->alertasRecientes);
  }

  public function updatedFiltroUsuario()
  {
    $this->iniciarCarga();
    $this->actualizarDatos();
    $this->dispatch('alertasActualizadas', alertas: $this->alertasRecientes);
  }

  #[On('solicitarActualizacionCompleta')]
  public function solicitarActualizacionCompleta()
  {
    Log::info("📡 Evento 'solicitarActualizacionCompleta' recibido desde el frontend. Iniciando actualización de datos.");
    $this->iniciarCarga();
    $this->actualizarDatos(); // Consulta BD con filtros actuales

    $this->dispatch('alertasActualizadas', alertas: $this->alertasRecientes);
    Log::info("✅ Datos actualizados y evento 'alertasActualizadas' emitido desde solicitarActualizacionCompleta.");
    $this->finalizarCarga();
  }

  public function refrescarAlertasDesdeServidor()
  {
    Log::info('📡 Iniciando refrescarAlertasDesdeServidor via wire:poll. Filtros actuales: Gravedad=' . $this->filtroGravedad . ', Usuario=' . $this->filtroUsuario);

    $this->actualizarDatos();

    Log::info('✅ Finalizando refrescarAlertasDesdeServidor via wire:poll. Alertas encontradas: ' . count($this->alertasRecientes));
  }

  private function actualizarDatos()
  {
    if (!$this->cargando) {
      $this->iniciarCarga();
    }

    $queryLocations = Location::with([
      'user' => function ($query) {
        $query->select(['id', 'name', 'punto']); // Asegúrate de incluir 'id' para la relación
      }
    ])
      ->select(['id', 'user_id', 'latitude', 'longitude', 'created_at'])
      ->where('created_at', '>=', Carbon::now()->subMinutes($this->ACTIVE_SPAN))
      ->gravedad($this->filtroGravedad)
      ->when(!empty($this->filtroUsuario), function ($query) {
        $query->whereHas('user', function ($userQuery) {
          $userQuery->where('name', 'like', '%' . $this->filtroUsuario . '%');
        });
      })
      ->orderBy('created_at', 'desc');

    $locations = $queryLocations->get();

    $alertas = $locations->map(function ($location) {
      $timestampCreacion    = $location->created_at;
      $minutosTranscurridos = (int) $timestampCreacion->diffInMinutes(Carbon::now('America/Mexico_City'));
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
      return ($alerta['minutosTranscurridos'] ?? 0) <= $this->RECENT_SPAN;
    })->count();

    $this->finalizarCarga();
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
