<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Asistencia;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class GraficasInasistencias extends Component
{
    public $labels = [];
    public $data = [];
    public $filtro = 'anio';

    public function mount()
    {
        $this->actualizarDatos();
    }

    public function updatedFiltro()
    {
        $this->actualizarDatos();
    }

    public function obtenerRango()
    {
        $hoy = Carbon::today();

        return match ($this->filtro) {
            'hoy' => [$hoy, $hoy],
            'semana' => [$hoy->copy()->startOfWeek(), $hoy->copy()->endOfWeek()],
            'mes' => [$hoy->copy()->startOfMonth(), $hoy->copy()->endOfMonth()],
            'anio' => [$hoy->copy()->startOfYear(), $hoy->copy()->endOfYear()],
            default => [$hoy->copy()->startOfYear(), $hoy->copy()->endOfYear()],
        };
    }

    public function actualizarDatos()
    {
        [$inicio, $fin] = $this->obtenerRango();

        $this->labels = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];

        // Obtener asistencias dentro del rango
        $asistencias = Asistencia::whereBetween('fecha', [$inicio, $fin])->get(['fecha', 'faltas']);

        // Inicializar array con meses (1-12) y conteo 0
        $conteosPorMes = array_fill(1, 12, 0);

        foreach ($asistencias as $asistencia) {
            // Convertir 'faltas' JSON a array
            $faltas = json_decode($asistencia->faltas, true);

            if (is_array($faltas)) {
                $mes = Carbon::parse($asistencia->fecha)->month;
                // Sumar la cantidad de IDs en 'faltas' para ese mes
                $conteosPorMes[$mes] += count($faltas);
            }
        }

        // Convertir a array indexado de 0 a 11 para el gráfico
        $this->data = [];
        for ($mes = 1; $mes <= 12; $mes++) {
            $this->data[] = $conteosPorMes[$mes] ?? 0;
        }

        $this->dispatch('chart-inasistencias-updated', data: $this->data);
    }

    public function render()
    {
        return view('livewire.graficas-inasistencias', [
            'filtro' => $this->filtro,
        ]);
    }
}
