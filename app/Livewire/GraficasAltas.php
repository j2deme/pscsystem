<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\SolicitudBajas;
use App\Models\SolicitudVacaciones;
use App\Models\Asistencia;
use Carbon\Carbon;

class GraficasAltas extends Component
{
    public $filtro = 'anio';
    public $labels = ['Altas', 'Bajas', 'Inasistencias', 'Vacaciones'];
    public $data = [];

    public function mount()
    {
        $this->actualizarDatos();
    }

    public function updatedFiltro()
{
    $this->actualizarDatos();
    $this->dispatch('chart-altas-updated', data: $this->data);
}

    public function actualizarDatos()
    {
        [$inicio, $fin] = $this->obtenerRango();

        $altas = User::where('estatus', 'Activo')
            ->whereBetween('fecha_ingreso', [$inicio, $fin])
            ->count();

        $bajas = SolicitudBajas::where('estatus', 'Aceptada')
            ->whereBetween('fecha_baja', [$inicio, $fin])
            ->count();

        $inasistencias = Asistencia::whereBetween('fecha', [$inicio, $fin])
            ->get()
            ->reduce(function ($carry, $asistencia) {
                $faltas = json_decode($asistencia->faltas, true) ?? [];
                return $carry + count($faltas);
            }, 0);

        $vacaciones = SolicitudVacaciones::where('estatus', 'Aceptada')
            ->where(function ($query) use ($inicio, $fin) {
                $query->whereBetween('fecha_inicio', [$inicio, $fin])
                    ->orWhereBetween('fecha_fin', [$inicio, $fin]);
            })
            ->count();

        $this->data = [$altas, $bajas, $inasistencias, $vacaciones];
    }

    public function obtenerRango()
    {
        $hoy = Carbon::today();

        return match ($this->filtro) {
            'hoy' => [$hoy, $hoy],
            'semana' => [$hoy->copy()->startOfWeek(), $hoy->copy()->endOfWeek()],
            'mes' => [$hoy->copy()->startOfMonth(), $hoy->copy()->endOfMonth()],
            'anio' => [$hoy->copy()->startOfYear(), $hoy->copy()->endOfYear()],
        };
    }

    public function render()
    {
        return view('livewire.graficas-altas');
    }
}
