<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\SolicitudBajas;
use App\Models\SolicitudAltas;
use Carbon\Carbon;

class Graficasfiniquitos extends Component
{
    public $labels = [];
    public $data = [];
    public $filtro = 'anio';
    public $dataPeriodo1 = [];
    public $dataPeriodo2 = [];

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

    $montosPeriodo1 = array_fill(1, 12, 0);
    $montosPeriodo2 = array_fill(1, 12, 0);

    $solicitudes = SolicitudBajas::with(['user', 'user.solicitudAlta'])
        ->where('estatus', 'Aceptada')
        ->where('por', 'like', '%renuncia%')
        ->whereBetween('fecha_baja', [$inicio, $fin])
        ->get();

    foreach ($solicitudes as $solicitud) {
        $user = $solicitud->user;
        $solicitudAlta = $user->solicitudAlta;

        if (!$solicitudAlta) continue;

        $fechaBaja = Carbon::parse($solicitud->fecha_baja);
        $mes = $fechaBaja->month;

        $periodo1Inicio = $fechaBaja->copy()->startOfMonth()->subDays(5);
        $periodo1Fin = $fechaBaja->copy()->day(10);
        $periodo2Inicio = $fechaBaja->copy()->day(11);
        $periodo2Fin = $fechaBaja->copy()->day(25);

        $fechaIngreso = Carbon::parse($user->fecha_ingreso);
        $ultimaAsistencia = Carbon::parse($solicitud->ultima_asistencia);

        $diasQuincena = 0;

        $diasTrabajadosAnio = $fechaIngreso->diffInDays($fechaBaja) + 1;
        $diasNoLaborados = $ultimaAsistencia->diffInDays($fechaBaja);
        $descuentoNoLaborados = $diasNoLaborados * $solicitudAlta->sd;

        $diasDisponibles = $solicitudAlta->dias_vacaciones_disponibles ?? 6;
        $factorVacaciones = $diasDisponibles / 365;
        $diasVacaciones = $diasTrabajadosAnio * $factorVacaciones;
        $montoVacaciones = $diasVacaciones * $solicitudAlta->sd;
        $primaVacacional = $montoVacaciones * 0.25;

        $factorAguinaldo = 15 / 365;
        $inicioAnio = now()->startOfYear();

        if ($fechaIngreso->greaterThanOrEqualTo($inicioAnio)) {
            $diasTrabajAnio = $fechaIngreso->diffInDays($ultimaAsistencia) + 1;
        } else {
            $diasTrabajAnio = $inicioAnio->diffInDays($ultimaAsistencia) + 1;
        }

        $diasAguinaldo = $diasTrabajAnio * $factorAguinaldo;
        $montoAguinaldo = $diasAguinaldo * $solicitudAlta->sd;
        $primaAguinaldo = $montoAguinaldo * 0.25;

        $descuentoNoEntregados = $solicitud->descuento ?? 0;

        $finiquito = $montoVacaciones + $primaVacacional + $montoAguinaldo + $primaAguinaldo - $descuentoNoLaborados - $descuentoNoEntregados;

        if ($fechaBaja->between($periodo1Inicio, $periodo1Fin)) {
            $montosPeriodo1[$mes] += $finiquito;
        } elseif ($fechaBaja->between($periodo2Inicio, $periodo2Fin)) {
            $montosPeriodo2[$mes] += $finiquito;
        }
    }

    $this->dataPeriodo1 = [];
    $this->dataPeriodo2 = [];

    for ($mes = 1; $mes <= 12; $mes++) {
        $this->dataPeriodo1[] = round($montosPeriodo1[$mes], 2);
        $this->dataPeriodo2[] = round($montosPeriodo2[$mes], 2);
    }

    $this->dispatch('chart-finiquitos-updated', [
        'periodo1' => $this->dataPeriodo1,
        'periodo2' => $this->dataPeriodo2,
    ]);
}

    public function render()
    {
        return view('livewire.graficasfiniquitos', [
            'filtro' => $this->filtro,
        ]);
    }
}
