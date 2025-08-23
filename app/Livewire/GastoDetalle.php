<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Gastos;

class GastoDetalle extends Component
{
  public $gastoId;
  public $gasto;
  public $usuario;
  public $unidad; // Si se puede obtener a través del usuario

  public $gastoPrevio;
  public $kilometrosRecorridos;
  public $rendimientoKmLitro;
  public $costoPorKm;

  protected $listeners = ['verDetalleGasto' => 'cargarDetalle'];

  public function mount($id)
  {
    $this->cargarDetalle($id);
  }

  public function cargarDetalle($id)
  {
    // Cargar el gasto con sus relaciones
    $gasto = Gastos::with(['user.solicitudAlta'])->findOrFail($id);

    $this->gasto   = $gasto;
    $this->gastoId = $id;
    $this->usuario = $gasto->user;

    // Intentar obtener información de unidad si es relevante
    // Esto dependerá de cómo se relaciona el usuario con la unidad
    // Por ahora lo dejamos null
    $this->unidad = null;

    // Calcular métricas de rendimiento
    $this->calcularRendimiento($gasto);
  }

  private function calcularRendimiento($gasto)
  {
    // Solo calcular para gastos de gasolina con kilometraje
    if ($gasto->Tipo !== 'Gasolina' || $gasto->Km === null) {
      $this->gastoPrevio          = null;
      $this->kilometrosRecorridos = null;
      $this->rendimientoKmLitro   = null;
      $this->costoPorKm           = null;
      return;
    }

    // Obtener el user_id para buscar registros previos
    $userId = $gasto->user_id;

    // Buscar el gasto de gasolina más reciente anterior a este
    $gastoPrevio = Gastos::where('user_id', $userId)
      ->where('Tipo', 'Gasolina')
      ->where('Km', '!=', null)
      ->where('id', '!=', $gasto->id)
      ->where('Fecha', '<=', $gasto->Fecha)
      ->orderByDesc('Fecha')
      ->orderByDesc('Hora')
      ->first();

    $this->gastoPrevio = $gastoPrevio;

    if ($gastoPrevio) {
      $kmAnterior = $gastoPrevio->Km;
      $kmActual   = $gasto->Km;

      // Calcular kilometros recorridos
      if ($kmActual > $kmAnterior) {
        $this->kilometrosRecorridos = $kmActual - $kmAnterior;

        // Calcular rendimiento (Km/L)
        // Nota: Necesitamos saber cuántos litros se cargaron
        // Esto se puede calcular si tenemos el precio por litro
        // Por ahora asumimos que se puede obtener de los datos de rayas o se estima

        // Calcular costo por Km
        if ($this->kilometrosRecorridos > 0) {
          $this->costoPorKm = $gasto->Monto / $this->kilometrosRecorridos;
        }
      } else {
        // Si el kilometraje no es incremental, no se puede calcular
        $this->kilometrosRecorridos = null;
        $this->costoPorKm           = null;
      }
    } else {
      // No hay registro previo
      $this->kilometrosRecorridos = null;
      $this->costoPorKm           = null;
    }
  }

  public function render()
  {
    $data = [
      'gasto' => $this->gasto,
      'usuario' => $this->usuario,
      'unidad' => $this->unidad,
      'gastoPrevio' => $this->gastoPrevio,
      'kilometrosRecorridos' => $this->kilometrosRecorridos,
      'costoPorKm' => $this->costoPorKm,
      'breadcrumbItems' => [
        ['icon' => 'ti-home', 'url' => route('dashboard')],
        ['icon' => 'ti-receipt-2', 'url' => route('gastos.index'), 'label' => 'Gastos'],
        ['icon' => 'ti-eye', 'label' => 'Detalle del Gasto']
      ],
      'titleMain' => 'Detalle del Gasto',
      'helpText' => 'Información completa del gasto registrado'
    ];

    return view('livewire.gasto-detalle', $data)
      ->layout('layouts.app');
  }
}