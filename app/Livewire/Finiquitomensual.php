<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Finiquito;
use Carbon\Carbon;

class Finiquitomensual extends Component
{
    public $finiquitosMesActual = 0;
    public $finiquitosMesPasado = 0;
    public $variacionFiniquitos = 0;

    public function mount()
    {
        $this->calcularFiniquitosMensuales();
    }

    public function calcularFiniquitosMensuales()
    {
        $inicioMesActual = now()->startOfMonth();
        $finMesActual = now()->endOfMonth();

        $inicioMesPasado = now()->subMonth()->startOfMonth();
        $finMesPasado = now()->subMonth()->endOfMonth();

        // Suma de finiquitos por mes
        $finiquitosActual = $this->obtenerSumaFiniquitos($inicioMesActual, $finMesActual);
        $finiquitosPasado = $this->obtenerSumaFiniquitos($inicioMesPasado, $finMesPasado);

        $this->finiquitosMesActual = round($finiquitosActual, 2);
        $this->finiquitosMesPasado = round($finiquitosPasado, 2);

        if ($finiquitosPasado > 0) {
            $this->variacionFiniquitos = round((($finiquitosActual - $finiquitosPasado) / $finiquitosPasado) * 100, 2);
        } else {
            $this->variacionFiniquitos = $finiquitosActual > 0 ? 100 : 0;
        }
    }

    private function obtenerSumaFiniquitos($inicio, $fin)
    {
        return Finiquito::whereHas('baja', function ($query) use ($inicio, $fin) {
                $query->where('estatus', 'Aceptada')
                    ->where('por', 'like', '%renuncia%')
                    ->whereBetween('fecha_baja', [$inicio, $fin]);
            })
            ->sum('monto');
    }

    public function render()
    {
        return view('livewire.finiquitomensual');
    }
}
