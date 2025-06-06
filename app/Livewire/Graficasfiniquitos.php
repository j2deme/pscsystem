<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\SolicitudBajas;
use Carbon\Carbon;

class Graficasfiniquitos extends Component
{
   /* public $labels = [];
    public $dataPeriodo1 = [];
    public $dataPeriodo2 = [];
    public $filtro = 'anio';
    public $tipoGrafica = 'anual';
    public $detallesFiniquitos = [];

    public function mount()
    {
        $this->actualizarDatos();
    }

    public function updatedFiltro()
    {
        $this->tipoGrafica = in_array($this->filtro, ['hoy', 'semana', 'mes']) ? 'periodo' : 'anual';
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
        $totalPeriodo1 = 0;
        $totalPeriodo2 = 0;
        $this->detallesFiniquitos = [];

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
            $ultimaAsistencia = $solicitud->ultima_asistencia
                ? Carbon::parse($solicitud->ultima_asistencia)
                : $fechaBaja;

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
            $diasTrabajAnio = $fechaIngreso->greaterThanOrEqualTo($inicioAnio)
                ? $fechaIngreso->diffInDays($ultimaAsistencia) + 1
                : $inicioAnio->diffInDays($ultimaAsistencia) + 1;

            $diasAguinaldo = $diasTrabajAnio * $factorAguinaldo;
            $montoAguinaldo = $diasAguinaldo * $solicitudAlta->sd;
            $primaAguinaldo = $montoAguinaldo * 0.25;

            $descuentoNoEntregados = $solicitud->descuento ?? 0;

            $finiquito = $montoVacaciones + $primaVacacional + $montoAguinaldo + $primaAguinaldo - $descuentoNoLaborados - $descuentoNoEntregados;

            if ($this->tipoGrafica === 'anual') {
                if ($fechaBaja->between($periodo1Inicio, $periodo1Fin)) {
                    $montosPeriodo1[$mes] += $finiquito;
                } elseif ($fechaBaja->between($periodo2Inicio, $periodo2Fin)) {
                    $montosPeriodo2[$mes] += $finiquito;
                }
            } else {
                if ($fechaBaja->between($periodo1Inicio, $periodo1Fin)) {
                    $totalPeriodo1 += $finiquito;
                } elseif ($fechaBaja->between($periodo2Inicio, $periodo2Fin)) {
                    $totalPeriodo2 += $finiquito;
                }
            }
        }

        if ($this->tipoGrafica === 'anual') {
            $this->dataPeriodo1 = array_map(fn($m) => round($m, 2), $montosPeriodo1);
            $this->dataPeriodo2 = array_map(fn($m) => round($m, 2), $montosPeriodo2);
        } else {
            $this->dataPeriodo1 = [round($totalPeriodo1, 2)];
            $this->dataPeriodo2 = [round($totalPeriodo2, 2)];
            $this->labels = ['Total'];
        }

        $this->dispatch('chart-finiquitos-updated', [
    'labels' => $this->labels,
    'periodo1' => $this->dataPeriodo1,
    'periodo2' => $this->dataPeriodo2,
]);

    }

    public function render()
    {
        return view('livewire.graficasfiniquitos', [
            'labels' => $this->labels,
            'dataPeriodo1' => $this->dataPeriodo1,
            'dataPeriodo2' => $this->dataPeriodo2,
        ]);
    }*/

    public $filtro = 'anio';
    public $labels = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
    public $data = [];
    public $dataPeriodo1 = [];
    public $dataPeriodo2 = [];
    public $detallesFiniquitos = [];

    public function mount()
    {
        $this->actualizarDatos();
        $this->dispatch('chart-finiquitos-updated', data: $this->data);
    }

    public function updatedFiltro()
    {
        //$this->actualizarDatos();
        $this->dispatch('chart-finiquitos-updated', data: $this->data);
    }

    public function actualizarDatos()
    {
        [$inicio, $fin] = $this->obtenerRango();
        $this->labels = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];

        $montosPeriodo1 = array_fill(1, 12, 0);
        $montosPeriodo2 = array_fill(1, 12, 0);
        $totalPeriodo1 = 0;
        $totalPeriodo2 = 0;
        $this->detallesFiniquitos = [];

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
            $ultimaAsistencia = $solicitud->ultima_asistencia
                ? Carbon::parse($solicitud->ultima_asistencia)
                : $fechaBaja;

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
            $diasTrabajAnio = $fechaIngreso->greaterThanOrEqualTo($inicioAnio)
                ? $fechaIngreso->diffInDays($ultimaAsistencia) + 1
                : $inicioAnio->diffInDays($ultimaAsistencia) + 1;

            $diasAguinaldo = $diasTrabajAnio * $factorAguinaldo;
            $montoAguinaldo = $diasAguinaldo * $solicitudAlta->sd;
            $primaAguinaldo = $montoAguinaldo * 0.25;

            $descuentoNoEntregados = $solicitud->descuento ?? 0;

            $finiquito = $montoVacaciones + $primaVacacional + $montoAguinaldo + $primaAguinaldo - $descuentoNoLaborados - $descuentoNoEntregados;

            if ($fechaBaja->between($periodo1Inicio, $periodo1Fin, true)) {
                $montosPeriodo1[$mes] += $finiquito;
                $totalPeriodo1 += $finiquito;
            } elseif ($fechaBaja->between($periodo2Inicio, $periodo2Fin, true)) {
                $montosPeriodo2[$mes] += $finiquito;
                $totalPeriodo2 += $finiquito;
            }
        }
        $this->dataPeriodo1 = array_values($montosPeriodo1);
        $this->dataPeriodo2 = array_values($montosPeriodo2);

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
            'enero' => [ $hoy->copy()->startOfYear()->month(1)->startOfMonth(), $hoy->copy()->startOfYear()->month(1)->endOfMonth() ],
            'febrero' => [
                $hoy->copy()->startOfYear()->month(2)->startOfMonth(),
                $hoy->copy()->startOfYear()->month(2)->endOfMonth()
            ],
            'marzo' => [
                $hoy->copy()->startOfYear()->month(3)->startOfMonth(),
                $hoy->copy()->startOfYear()->month(3)->endOfMonth()
            ],
            'abril' => [
                $hoy->copy()->startOfYear()->month(4)->startOfMonth(),
                $hoy->copy()->startOfYear()->month(4)->endOfMonth()
            ],
            'junio' => [
                $hoy->copy()->startOfYear()->month(6)->startOfMonth(),
                $hoy->copy()->startOfYear()->month(6)->endOfMonth()
            ],
            'julio' => [
                $hoy->copy()->startOfYear()->month(7)->startOfMonth(),
                $hoy->copy()->startOfYear()->month(7)->endOfMonth()
            ],
            'agosto' => [
                $hoy->copy()->startOfYear()->month(8)->startOfMonth(),
                $hoy->copy()->startOfYear()->month(8)->endOfMonth()
            ],
            'octubre' => [
                $hoy->copy()->startOfYear()->month(10)->startOfMonth(),
                $hoy->copy()->startOfYear()->month(10)->endOfMonth()
            ],
            'noviembre' => [
                $hoy->copy()->startOfYear()->month(11)->startOfMonth(),
                $hoy->copy()->startOfYear()->month(11)->endOfMonth()
            ],
            'diciembre' => [
                $hoy->copy()->startOfYear()->month(12)->startOfMonth(),
                $hoy->copy()->startOfYear()->month(12)->endOfMonth()
            ],
        };
    }

    public function render()
    {
        return view('livewire.graficasfiniquitos');
    }
}
