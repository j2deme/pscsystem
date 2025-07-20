<?php

namespace App\Livewire;

use Livewire\Component;

class ServicioDetalle extends Component
{
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
        ];
        return view('livewire.servicio-detalle', $data)
            ->layout('layouts.app');
    }
}
