<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\SolicitudBajas;
use Carbon\Carbon;

class GraficasBajas extends Component
{
    public $labels = [];
    public $data = [];
    public $anio = null;

    public function mount()
    {
        $this->anio = Carbon::now()->year;
        $this->actualizarDatos();
    }

    public function updatedAnio()
    {
        $this->actualizarDatos();
    }

    public function actualizarDatos()
    {
        $this->labels = [];
        $this->data = [];

        $bajas = SolicitudBajas::where('estatus', 'Aceptada')
            ->whereYear('fecha_baja', $this->anio)
            ->selectRaw('MONTH(fecha_baja) as mes, COUNT(*) as total')
            ->groupBy('mes')
            ->orderBy('mes')
            ->get()
            ->keyBy('mes');

        $nombresMeses = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];

        for ($mes = 1; $mes <= 12; $mes++) {
            $this->labels[] = $nombresMeses[$mes - 1];
            $this->data[] = $bajas->has($mes) ? $bajas->get($mes)->total : 0;
        }

        $this->dispatch('chart-updated', data: $this->data);
    }

    public function render()
    {
        $aniosDisponibles = range(Carbon::now()->year, Carbon::now()->year - 4);

        return view('livewire.graficas-bajas', [
            'aniosDisponibles' => $aniosDisponibles
        ]);
    }
}
