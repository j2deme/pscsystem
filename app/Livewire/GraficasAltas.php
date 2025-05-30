<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\SolicitudBajas;
use App\Models\SolicitudVacaciones;
use App\Models\Asistencia;
use Illuminate\Support\Carbon;

class GraficasAltas extends Component
{
    public $filtro = 'anio';
    public $labels = ['Altas', 'Bajas', 'Inasistencias', 'Vacaciones'];
    public $data = [];

    public function mount()
    {
        $this->calcularDatos();
    }

    public function updatedFiltro()
    {
        $this->calcularDatos();
    }

    public function calcularDatos()
    {
        $rango = $this->obtenerRangoFechas();
        $inicio = $rango['inicio'];
        $fin = $rango['fin'];

        $altas = User::where('estatus', 'Activo')
            ->whereBetween('fecha_ingreso', [$inicio, $fin])
            ->count();

        $bajas = SolicitudBajas::where('estatus', 'Aceptada')
            ->whereBetween('fecha_baja', [$inicio, $fin])
            ->count();

        $inasistencias = 0;
        $asistencias = Asistencia::whereBetween('fecha', [$inicio, $fin])->get();
        foreach ($asistencias as $asistencia) {
            $faltas = json_decode($asistencia->faltas ?? '[]', true);
            $inasistencias += is_array($faltas) ? count($faltas) : 0;
        }

        $vacaciones = SolicitudVacaciones::where('estatus', 'Aceptada')
            ->where(function ($query) use ($inicio, $fin) {
                $query->whereBetween('fecha_inicio', [$inicio, $fin])
                    ->orWhereBetween('fecha_fin', [$inicio, $fin]);
            })
            ->count();

        $this->data = [$altas, $bajas, $inasistencias, $vacaciones];
    }

    public function obtenerRangoFechas()
    {
        $hoy = now();

        return match ($this->filtro) {
            'hoy'    => ['inicio' => $hoy->copy()->startOfDay(), 'fin' => $hoy->copy()->endOfDay()],
            'semana' => ['inicio' => $hoy->copy()->startOfWeek(), 'fin' => $hoy->copy()->endOfWeek()],
            'mes'    => ['inicio' => $hoy->copy()->startOfMonth(), 'fin' => $hoy->copy()->endOfMonth()],
            'anio'    => ['inicio' => $hoy->copy()->startOfYear(), 'fin' => $hoy->copy()->endOfYear()],
            default  => ['inicio' => $hoy->copy()->startOfDay(), 'fin' => $hoy->copy()->endOfDay()],
        };
    }

    public function render()
    {
        return view('livewire.graficas-altas', [
            'labels' => $this->labels,
            'data' => $this->data,
        ]);
    }
}
