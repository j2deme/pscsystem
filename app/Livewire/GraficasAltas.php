<?php

namespace App\Livewire;

use App\Models\Asistencia;
use App\Models\SolicitudBajas;
use App\Models\SolicitudVacaciones;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Livewire\Component;

class GraficasAltas extends Component
{
    public string $filtro = 'anio';
    public array $data = [];
    public bool $readyToLoad = false;
    public bool $isCalculating = false;

    public function render()
    {
        return view('livewire.graficas-altas');
    }

    public function initChart()
    {
        $this->readyToLoad = true;
        $this->actualizarDatos();
    }

    public function actualizarDatos()
    {
        $this->isCalculating = true;
        logger('⏳ Iniciando cálculo de estadísticas...');

        // Llama directamente al método que calcula y actualiza $data
        $this->updateStatsData($this->filtro);
    }

    public function updateStatsData($filtro)
    {
        logger('🔄 Calculando datos con filtro: ' . $filtro);
        $this->filtro = $filtro;
        $this->data = [];

        $rango = match ($filtro) {
            'hoy' => [Carbon::today(), Carbon::today()],
            'semana' => [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()],
            'mes' => [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()],
            default => [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()],
        };

        [$inicio, $fin] = $rango;

        // Altas
        $altas = User::where('estatus', 'Activo')
            ->whereBetween('fecha_ingreso', [$inicio, $fin])
            ->count();
        $this->data['Altas'] = $altas;
        logger("✅ Altas: $altas");

        // Bajas
        $bajas = SolicitudBajas::where('estatus', 'Aceptada')
            ->whereBetween('fecha_baja', [$inicio, $fin])
            ->count();
        $this->data['Bajas'] = $bajas;
        logger("✅ Bajas: $bajas");

        // Inasistencias
        $inasistencias = Asistencia::whereBetween('fecha', [$inicio, $fin])
            ->get()
            ->reduce(function ($carry, $asistencia) {
                $faltas = json_decode($asistencia->faltas, true);
                return $carry + (is_array($faltas) ? count($faltas) : 0);
            }, 0);
        $this->data['Inasistencias'] = $inasistencias;
        logger("✅ Inasistencias: $inasistencias");

        // Vacaciones
        $vacaciones = SolicitudVacaciones::where('estatus', 'aceptado')
            ->whereBetween('fecha_inicio', [$inicio, $fin])
            ->orWhereBetween('fecha_fin', [$inicio, $fin])
            ->count();
        $this->data['Vacaciones'] = $vacaciones;
        logger("✅ Vacaciones: $vacaciones");

        $this->isCalculating = false;

        logger('✅ Cálculo terminado, datos listos.');

        $this->dispatch('chart-altas-updated', array_values($this->data));
    }
    public function updatedFiltro($value)
    {
        $this->actualizarDatos();
    }
}
