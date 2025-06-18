<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Nomina;
use Carbon\Carbon;

class Nominamensual extends Component
{
    public float $totalMesActual = 0;
    public float $totalMesAnterior = 0;
    public float $variacion = 0;

    public function calcularResumen()
    {
        $hoy = now();
        $mesActual = $hoy->format('m');
        $anioActual = $hoy->format('Y');

        $this->totalMesActual = Nomina::whereYear('created_at', $anioActual)
            ->whereMonth('created_at', $mesActual)
            ->sum('monto');

        $mesAnterior = $hoy->copy()->subMonth();
        $this->totalMesAnterior = Nomina::whereYear('created_at', $mesAnterior->year)
            ->whereMonth('created_at', $mesAnterior->month)
            ->sum('monto');

        if ($this->totalMesAnterior > 0) {
            $this->variacion = round((($this->totalMesActual - $this->totalMesAnterior) / $this->totalMesAnterior) * 100, 2);
        } else {
            $this->variacion = 0;
        }
    }

    public function mount()
    {
        $this->calcularResumen();
    }

    public function render()
    {
        return view('livewire.nominamensual');
    }
}
