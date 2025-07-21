<?php

namespace App\Livewire;

use Livewire\Component;
use App\Traits\EstilosServicio;
use App\Models\Servicio;

class ServicioDetalle extends Component
{
    use EstilosServicio;

    public $servicio;
    public $unidad;
    public $servicioPrevio = null;
    public $servicioSiguiente = null;

    public function mount($id)
    {
        $this->servicio = Servicio::findOrFail($id);
        $this->unidad   = $this->servicio->unidad;

        // Obtener todos los servicios de la unidad ordenados por fecha
        $serviciosUnidad         = Servicio::where('unidad_id', $this->servicio->unidad_id)
            ->orderBy('fecha', 'asc')
            ->get();
        $indexActual             = $serviciosUnidad->search(fn($s) => $s->id == $this->servicio->id);
        $this->servicioPrevio    = $indexActual > 0 ? $serviciosUnidad[$indexActual - 1] : null;
        $this->servicioSiguiente = ($indexActual !== false && $indexActual < $serviciosUnidad->count() - 1)
            ? $serviciosUnidad[$indexActual + 1] : null;
    }

    public function render()
    {
        $data = [
            'servicio' => $this->servicio,
            'unidad' => $this->unidad,
            'servicioPrevio' => $this->servicioPrevio,
            'servicioSiguiente' => $this->servicioSiguiente,
            'estilos' => $this->getEstilosServicio(),
        ];
        return view('livewire.servicio-detalle', $data)
            ->layout('layouts.app');
    }
}
