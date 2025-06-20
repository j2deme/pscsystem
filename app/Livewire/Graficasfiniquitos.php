<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Finiquito;
use Carbon\Carbon;

class Graficasfiniquitos extends Component
{
    public $filtro = 'anio';
    public $labels = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
    public $dataPeriodo1 = [];
    public $dataPeriodo2 = [];
    public $readyToLoad = false;
    public $isCalculating = false;

    public function mount()
    {
        $this->dataPeriodo1 = array_fill(0, 12, 0);
        $this->dataPeriodo2 = array_fill(0, 12, 0);
    }

    public function cargarGrafica()
    {
        $this->readyToLoad = true;
        $this->isCalculating = true;
        $this->actualizarDatos();
    }

    public function updatedFiltro()
    {
        if ($this->readyToLoad) {
            $this->isCalculating = true;
            $this->actualizarDatos();
        }
    }

    public function actualizarDatos()
    {
        [$inicio, $fin] = $this->obtenerRango();

        $montosPeriodo1 = array_fill(0, 12, 0);
        $montosPeriodo2 = array_fill(0, 12, 0);

        $finiquitos = Finiquito::with('baja')
            ->whereHas('baja', function ($q) use ($inicio, $fin) {
                $q->where('estatus', 'Aceptada')
                  ->where('por', 'like', '%renuncia%')
                  ->whereBetween('fecha_baja', [$inicio, $fin]);
            })
            ->get();

        foreach ($finiquitos as $finiquito) {
            $fecha = Carbon::parse($finiquito->baja->fecha_baja);
            $mesIndex = $fecha->month - 1;

            // Periodo 1: del 26 del mes anterior al 10 del mes actual
            $periodo1Inicio = $fecha->copy()->subMonth()->day(26);
            $periodo1Fin = $fecha->copy()->day(10);

            // Periodo 2: del 11 al 25 del mes actual
            $periodo2Inicio = $fecha->copy()->day(11);
            $periodo2Fin = $fecha->copy()->day(25);

            if ($fecha->between($periodo1Inicio, $periodo1Fin, true)) {
                $montosPeriodo1[$mesIndex] += $finiquito->monto;
            } elseif ($fecha->between($periodo2Inicio, $periodo2Fin, true)) {
                $montosPeriodo2[$mesIndex] += $finiquito->monto;
            }
        }

        $this->dataPeriodo1 = $montosPeriodo1;
        $this->dataPeriodo2 = $montosPeriodo2;
        $this->isCalculating = false;

        $this->dispatch('chart-finiquitos-updated', [
            'labels' => $this->labels,
            'periodo1' => $this->dataPeriodo1,
            'periodo2' => $this->dataPeriodo2,
        ]);
    }

    public function obtenerRango()
    {
        $hoy = Carbon::today();

        return match ($this->filtro) {
            'hoy' => [$hoy, $hoy],
            'anio' => [$hoy->copy()->startOfYear(), $hoy->copy()->endOfYear()],
            'enero' => [$hoy->copy()->startOfYear()->month(1)->startOfMonth(), $hoy->copy()->startOfYear()->month(1)->endOfMonth()],
            'febrero' => [$hoy->copy()->startOfYear()->month(2)->startOfMonth(), $hoy->copy()->startOfYear()->month(2)->endOfMonth()],
            'marzo' => [$hoy->copy()->startOfYear()->month(3)->startOfMonth(), $hoy->copy()->startOfYear()->month(3)->endOfMonth()],
            'abril' => [$hoy->copy()->startOfYear()->month(4)->startOfMonth(), $hoy->copy()->startOfYear()->month(4)->endOfMonth()],
            'mayo' => [$hoy->copy()->startOfYear()->month(5)->startOfMonth(), $hoy->copy()->startOfYear()->month(5)->endOfMonth()],
            'junio' => [$hoy->copy()->startOfYear()->month(6)->startOfMonth(), $hoy->copy()->startOfYear()->month(6)->endOfMonth()],
            'julio' => [$hoy->copy()->startOfYear()->month(7)->startOfMonth(), $hoy->copy()->startOfYear()->month(7)->endOfMonth()],
            'agosto' => [$hoy->copy()->startOfYear()->month(8)->startOfMonth(), $hoy->copy()->startOfYear()->month(8)->endOfMonth()],
            'septiembre' => [$hoy->copy()->startOfYear()->month(9)->startOfMonth(), $hoy->copy()->startOfYear()->month(9)->endOfMonth()],
            'octubre' => [$hoy->copy()->startOfYear()->month(10)->startOfMonth(), $hoy->copy()->startOfYear()->month(10)->endOfMonth()],
            'noviembre' => [$hoy->copy()->startOfYear()->month(11)->startOfMonth(), $hoy->copy()->startOfYear()->month(11)->endOfMonth()],
            'diciembre' => [$hoy->copy()->startOfYear()->month(12)->startOfMonth(), $hoy->copy()->startOfYear()->month(12)->endOfMonth()],
            default => [$hoy->copy()->startOfYear(), $hoy->copy()->endOfYear()],
        };
    }

    public function render()
    {
        return view('livewire.graficasfiniquitos');
    }
}

//AJUSTAR PERIODOS PARA QUE LOS AGRUPE CRRECTAMENTE
