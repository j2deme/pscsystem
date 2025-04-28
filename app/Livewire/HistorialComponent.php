<?php

namespace App\Livewire;

use Livewire\Component;

class HistorialComponent extends Component
{
    public $mostrarCoberturas = false;

    public function cambiarVista()
    {
        $this->mostrarCoberturas = !$this->mostrarCoberturas;
    }

    public function render()
    {
        return view('livewire.historial-component');
    }
}

