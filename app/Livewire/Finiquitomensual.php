<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\SolicitudBajas;
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

    $finiquitosActual = $this->obtenerFiniquitosTotales($inicioMesActual, $finMesActual);
    $finiquitosPasado = $this->obtenerFiniquitosTotales($inicioMesPasado, $finMesPasado);



    $this->finiquitosMesActual = round($finiquitosActual, 2);
    $this->finiquitosMesPasado = round($finiquitosPasado, 2);
    if ($finiquitosPasado > 0) {
        $this->variacionFiniquitos = round((($finiquitosActual - $finiquitosPasado) / $finiquitosPasado) * 100, 2);
    } else {
        $this->variacionFiniquitos = $finiquitosActual > 0 ? 100 : 0;
    }
}

private function obtenerFiniquitosTotales($inicio, $fin)
{
    $solicitudes = SolicitudBajas::with(['user', 'user.solicitudAlta'])
        ->where('estatus', 'Aceptada')
        ->where('por', 'like', '%renuncia%')
        ->whereBetween('fecha_baja', [$inicio, $fin])
        ->get();

    $total = 0;

    foreach ($solicitudes as $solicitud) {
        $user = $solicitud->user;
        $alta = $user->solicitudAlta;
        if (!$alta) continue;

        $fechaBaja = Carbon::parse($solicitud->fecha_baja);
        $fechaIngreso = Carbon::parse($user->fecha_ingreso);
        $ultimaAsistencia = $solicitud->ultima_asistencia
            ? Carbon::parse($solicitud->ultima_asistencia)
            : $fechaBaja;

        $diasTrabajadosAnio = $fechaIngreso->diffInDays($fechaBaja) + 1;
        $diasNoLaborados = $ultimaAsistencia->diffInDays($fechaBaja);
        $descuentoNoLaborados = $diasNoLaborados * $alta->sd;

        $diasDisponibles = $alta->dias_vacaciones_disponibles ?? 6;
        $factorVacaciones = $diasDisponibles / 365;
        $diasVacaciones = $diasTrabajadosAnio * $factorVacaciones;
        $montoVacaciones = $diasVacaciones * $alta->sd;
        $primaVacacional = $montoVacaciones * 0.25;

        $factorAguinaldo = 15 / 365;
        $inicioAnio = now()->startOfYear();
        $diasTrabajAnio = $fechaIngreso->greaterThanOrEqualTo($inicioAnio)
            ? $fechaIngreso->diffInDays($ultimaAsistencia) + 1
            : $inicioAnio->diffInDays($ultimaAsistencia) + 1;

        $diasAguinaldo = $diasTrabajAnio * $factorAguinaldo;
        $montoAguinaldo = $diasAguinaldo * $alta->sd;
        $primaAguinaldo = $montoAguinaldo * 0.25;

        $descuentoNoEntregados = $solicitud->descuento ?? 0;

        $finiquito = $montoVacaciones + $primaVacacional + $montoAguinaldo + $primaAguinaldo - $descuentoNoLaborados - $descuentoNoEntregados;

        $total += $finiquito;
    }

    return $total;
}
}
