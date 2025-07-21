<?php

namespace App\Livewire;

use Livewire\Component;
use App\Traits\EstilosServicio;

class ServicioDetalle extends Component
{
    use EstilosServicio;

    public $servicio;
    public $unidad;

    public function mount($id)
    {
        $this->servicio = \App\Models\Servicio::findOrFail($id);
        $this->unidad   = $this->servicio->unidad;
    }

    public function render()
    {
        $data = [
            'servicio' => $this->servicio,
            'unidad' => $this->unidad,
            'estilos' => $this->getEstilosServicio(),
        ];
        return view('livewire.servicio-detalle', $data)
            ->layout('layouts.app');
    }
}
