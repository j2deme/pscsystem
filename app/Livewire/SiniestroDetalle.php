<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Siniestro;
use App\Traits\TiposSiniestro;

class SiniestroDetalle extends Component
{
  use TiposSiniestro;
  public $siniestroId;
  public $siniestro;
  public $unidad;
  public $usuarios;
  public $tipoInfo;
  public $gravedad;
  public $badgeColor;

  protected $listeners = ['verDetalleSiniestro' => 'cargarDetalle'];

  public function mount($id)
  {
    $this->cargarDetalle($id);
  }

  public function cargarDetalle($id)
  {
    $siniestro         = Siniestro::with(['usuarios', 'unidad'])->findOrFail($id);
    $this->siniestro   = $siniestro;
    $this->siniestroId = $id;
    $this->unidad      = $siniestro->unidad;
    $this->usuarios    = $siniestro->usuarios;
    // Obtener info de tipo y gravedad
    if (is_object($siniestro)) {
      $tipo           = strtolower($siniestro->tipo_siniestro ?? '');
      $tiposVehiculo  = $this->getTiposVehiculo();
      $tiposPersonal  = $this->getTiposPersonal();
      $tipos          = $tipo === 'vehiculo' ? $tiposVehiculo : ($tipo === 'personal' ? $tiposPersonal : []);
      $this->tipoInfo = isset($siniestro->tipo) && isset($tipos[$siniestro->tipo]) ? $tipos[$siniestro->tipo] : null;
      $this->gravedad = $this->tipoInfo['gravedad'] ?? null;
    } else {
      $this->tipoInfo = null;
      $this->gravedad = null;
    }
  }

  public function render()
  {
    $badgeGravedadInfo = $this->getGravedadBadgeInfo($this->gravedad);
    $data              = [
      'siniestro' => $this->siniestro,
      'unidad' => $this->unidad,
      'usuarios' => $this->usuarios,
      'tipoInfo' => $this->tipoInfo,
      'gravedad' => $this->gravedad,
      'badgeGravedadInfo' => $badgeGravedadInfo,
    ];
    return view('livewire.siniestro-detalle', $data)
      ->layout('layouts.app');
  }
}
