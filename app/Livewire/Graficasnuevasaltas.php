<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use Carbon\Carbon;

class Graficasnuevasaltas extends Component
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
        $conteosPorMes = array_fill(1, 12, 0);

        $usuarios = User::where('estatus', 'Activo')
            ->whereBetween('fecha_ingreso', [$inicio, $fin])
            ->get(['fecha_ingreso']);

        foreach ($usuarios as $usuario) {
            $mes = Carbon::parse($usuario->fecha_ingreso)->month;
            $conteosPorMes[$mes]++;
        }

        $this->data = [];
        for ($mes = 1; $mes <= 12; $mes++) {
            $this->data[] = $conteosPorMes[$mes] ?? 0;
        }

        $this->dispatch('chart-altas-nuevas-updated', data: $this->data);
    }

    public function render()
    {
        return view('livewire.graficasnuevasaltas', [
            'filtro' => $this->filtro,
        ]);
    }
}
