<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Unidades;

class VehiculoDetalle extends Component
{
  public $unidadId;
  public $unidad;

  public function mount($id)
  {
    $this->unidadId = $id;
    $this->unidad   = Unidades::findOrFail($id);
  }

  public function render()
  {
    return view('livewire.vehiculo-detalle');
  }
}
