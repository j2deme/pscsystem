<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\SolicitudBajas;
use Carbon\Carbon;

class Graficasfiniquitos extends Component
{
    public $filtro = 'anio';
    public $labels = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
    public $dataPeriodo1 = [];
    public $dataPeriodo2 = [];
    public $detallesFiniquitos = [];
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

            $periodo1Inicio = $fechaBaja->copy()->subMonth()->day(26);
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
            } elseif ($fechaBaja->between($periodo2Inicio, $periodo2Fin, true)) {
                $montosPeriodo2[$mes] += $finiquito;
            }
        }

        $this->dataPeriodo1 = $montosPeriodo1;
        $this->dataPeriodo2 = $montosPeriodo2;
        $this->isCalculating = false;

        $this->dispatch('chart-finiquitos-updated', [
            'labels' => $this->labels,
            'periodo1' => $this->dataPeriodo1,
            'periodo2' => $this->dataPeriodo2
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
