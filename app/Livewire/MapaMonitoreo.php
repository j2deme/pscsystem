<?php

namespace App\Livewire;

use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class MapaMonitoreo extends Component
{
  protected $listeners = ['actualizarTiempoReal' => 'actualizarTiempoReal'];
  public $totalAlertas = 0;
  public $alertasActivas = 0;
  public $ultimaActualizacion;
  public $autoRefresh = true;
  public $alertasRecientes = [];

  private $RECENT_SPAN = 30; // Minutos
  private $ACTIVE_SPAN = 300; // Antigüedad máxima para mostrar alertas activas (5 horas)

  public function mount()
  {
    $this->actualizarDatos();
    // La actualización automática se maneja desde JavaScript
  }

  public function centrarMapa()
  {
    $this->dispatch('centrarMapa');
  }

  public function centrarEnAlerta($alertaId)
  {
    // Buscar la alerta específica
    $alerta = collect($this->alertasRecientes)->firstWhere('id', $alertaId);
    if ($alerta) {
      $this->dispatch(
        'centrarEnAlerta',
        latitud: $alerta['latitud'],
        longitud: $alerta['longitud'],
        usuario: $alerta['usuario']
      );
    }
  }

  private function actualizarDatos()
  {
    // TODO: Aquí realizará la lectura desde el websocket
    // Por ahora simulamos datos
    $this->ultimaActualizacion = Carbon::now('America/Mexico_City')->format('H:i:s');

    // Generar alertas y procesarlas con toda la información necesaria
    $alertasBase            = $this->generarAlertasSimuladas();
    $this->alertasRecientes = $this->procesarAlertasParaVista($alertasBase);

    // Calcular estadísticas basadas en las alertas generadas
    $this->totalAlertas   = count($this->alertasRecientes);
    $this->alertasActivas = collect($this->alertasRecientes)->filter(function ($alerta) {
      // Consideramos activas las alertas de menos de 30 minutos
      return $alerta['minutosTranscurridos'] <= $this->RECENT_SPAN;
    })->count();
  }

  private function generarAlertasSimuladas()
  {
    $alertas     = [];
    $usuarios    = ['Juan Pérez', 'María García', 'Carlos López', 'Ana Martínez', 'Luis Rodríguez'];
    $ubicaciones = ['Punto A', 'Punto B', 'Punto C', 'Zona Norte', 'Zona Sur', 'Entrada Principal', 'Almacén 1', 'Oficinas'];

    // Generar entre 5 y 10 alertas con rangos de tiempo realistas
    $numAlertas = rand(5, 10);

    for ($i = 0; $i < $numAlertas; $i++) {
      // Generar minutos de forma más orgánica con probabilidades diferentes
      $probabilidad = rand(1, 100);

      if ($probabilidad <= 20) {
        // 20% probabilidad: CRÍTICA (0-10 min) - MÁXIMA URGENCIA
        $minutosAtras = rand(0, 10);
        $estado       = 'critica';
      } elseif ($probabilidad <= 40) {
        // 20% probabilidad: ALTA (11-20 min) - ALTA URGENCIA
        $minutosAtras = rand(11, 20);
        $estado       = 'alta';
      } elseif ($probabilidad <= 60) {
        // 20% probabilidad: MEDIA (21-30 min) - MEDIA URGENCIA
        $minutosAtras = rand(21, 30);
        $estado       = 'media';
      } elseif ($probabilidad <= 80) {
        // 20% probabilidad: BAJA (31-60 min) - BAJA URGENCIA
        $minutosAtras = rand(31, 60);
        $estado       = 'baja';
      } else {
        // 20% probabilidad: ANTIGUA (+60 min) - SIN COMUNICACIÓN
        $minutosAtras = rand(61, 120);
        $estado       = 'antigua';
      }

      $tiposEstado = [
        'critica' => 'CRÍTICA',        // Rojo en UI - Máxima urgencia (0-10 min)
        'alta' => 'ALTA',              // Naranja en UI - Alta urgencia (11-20 min)
        'media' => 'MEDIA',            // Amarillo en UI - Media urgencia (21-30 min)
        'baja' => 'BAJA',              // Azul en UI - Baja urgencia (31-60 min)
        'antigua' => 'ANTIGUA'        // Gris en UI - Sin comunicación (+60 min)
      ];

      $alertas[] = [
        'id' => $i + 1,
        'usuario' => $usuarios[array_rand($usuarios)],
        'estado' => $estado,
        'estadoTexto' => $tiposEstado[$estado],
        'tiempo' => Carbon::now('America/Mexico_City')->subMinutes($minutosAtras)->format('H:i'),
        'ubicacion' => $ubicaciones[array_rand($ubicaciones)],
        'latitud' => 25.6866 + (rand(-100, 100) / 1000),
        'longitud' => -100.3161 + (rand(-100, 100) / 1000),
        'descripcion' => 'Activación de botón de pánico',
        'minutosTranscurridos' => $minutosAtras,
        'timestamp_creacion' => Carbon::now('America/Mexico_City')->subMinutes($minutosAtras)->timestamp
      ];
    }

    // Ordenar por tiempo (más recientes primero)
    usort($alertas, function ($a, $b) {
      return $a['minutosTranscurridos'] - $b['minutosTranscurridos'];
    });

    return $alertas;
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
   * Obtener configuración completa de colores para un estado
   */
  public function obtenerConfiguracionColores($estado)
  {
    $colores = [
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

    return $colores[$estado] ?? $colores['desconocida'];
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
    // Actualizar los tiempos transcurridos basados en timestamp
    foreach ($this->alertasRecientes as &$alerta) {
      if (isset($alerta['timestamp_creacion'])) {
        $minutosActuales                = Carbon::now('America/Mexico_City')->diffInMinutes(Carbon::createFromTimestamp($alerta['timestamp_creacion']));
        $alerta['minutosTranscurridos'] = $minutosActuales;

        // Calcular estado dinámico
        $estadoDinamico        = $this->calcularEstadoPorTiempo($minutosActuales);
        $alerta['estado']      = $estadoDinamico['estado'];
        $alerta['estadoTexto'] = $estadoDinamico['texto'];

        // Actualizar configuración de colores
        $alerta['colores']        = $this->obtenerConfiguracionColores($estadoDinamico['estado']);
        $alerta['estadoCompleto'] = [
          'codigo' => $estadoDinamico['estado'],
          'texto' => $estadoDinamico['texto'],
          'colores' => $alerta['colores']
        ];
      }
    }

    // Recalcular estadísticas
    $this->totalAlertas   = count($this->alertasRecientes);
    $this->alertasActivas = collect($this->alertasRecientes)->filter(function ($alerta) {
      return $alerta['minutosTranscurridos'] <= $this->RECENT_SPAN;
    })->count();

    // Enviar evento para actualizar la interfaz
    $this->dispatch('actualizarMarcadores', alertas: $this->alertasRecientes);
    $this->dispatch('mapaActualizado', alertas: $this->alertasRecientes);
  }

  /**
   * Obtener texto de urgencia basado en minutos transcurridos
   */
  public function obtenerTextoUrgencia($minutosTranscurridos)
  {
    if ($minutosTranscurridos <= 10) {
      return 'CRÍTICA';
    } elseif ($minutosTranscurridos <= 20) {
      return 'ALTA';
    } elseif ($minutosTranscurridos <= 30) {
      return 'MEDIA';
    } elseif ($minutosTranscurridos <= 60) {
      return 'BAJA';
    } else {
      return 'ANTIGUA';
    }
  }

  public function render()
  {
    return view('livewire.mapa-monitoreo', [
      'alertasRecientes' => $this->alertasRecientes
    ]);
  }
}
