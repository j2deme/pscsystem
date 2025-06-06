<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Asistencia;
use Carbon\Carbon;

class Nominamensual extends Component
{
    public float $totalMesActual = 0;
    public float $totalMesAnterior = 0;
    public float $variacion = 0;

    public function mount()
    {
        $this->calcularResumen();
    }

    public function calcularResumen()
    {
        $hoy = now();
        $anio = $hoy->year;
        $mes = $hoy->month;

        // Mes actual
        [$inicio1, $fin1, $inicio2, $fin2] = $this->periodosDelMes($anio, $mes);
        $this->totalMesActual = $this->calcularTotalNomina($inicio1, $fin1) + $this->calcularTotalNomina($inicio2, $fin2);

        // Mes anterior
        $mesAnterior = $mes - 1;
        $anioAnterior = $anio;
        if ($mesAnterior < 1) {
            $mesAnterior = 12;
            $anioAnterior--;
        }
        [$inicioAnt1, $finAnt1, $inicioAnt2, $finAnt2] = $this->periodosDelMes($anioAnterior, $mesAnterior);
        $this->totalMesAnterior = $this->calcularTotalNomina($inicioAnt1, $finAnt1) + $this->calcularTotalNomina($inicioAnt2, $finAnt2);

        // Variación %
        if ($this->totalMesAnterior > 0) {
            $this->variacion = round((($this->totalMesActual - $this->totalMesAnterior) / $this->totalMesAnterior) * 100, 2);
        } else {
            $this->variacion = 0;
        }
    }

    private function periodosDelMes(int $anio, int $mes): array
    {
        $mesAnterior = $mes - 1;
        $anioPeriodo1 = $anio;

        if ($mesAnterior < 1) {
            $mesAnterior = 12;
            $anioPeriodo1 = $anio - 1;
        }

        $inicioPeriodo1 = Carbon::create($anioPeriodo1, $mesAnterior, 26)->startOfDay();
        $finPeriodo1 = Carbon::create($anio, $mes, 10)->endOfDay();

        $inicioPeriodo2 = Carbon::create($anio, $mes, 11)->startOfDay();
        $finPeriodo2 = Carbon::create($anio, $mes, 25)->endOfDay();

        return [$inicioPeriodo1, $finPeriodo1, $inicioPeriodo2, $finPeriodo2];
    }

    private function calcularTotalNomina(Carbon $inicio, Carbon $fin): float
    {
        $usuarios = User::where('estatus', 'Activo')->get();
        $total = 0;

        foreach ($usuarios as $user) {
            $asistencias = Asistencia::whereBetween('fecha', [$inicio, $fin])
                ->where('punto', $user->punto)
                ->get();

            $asistencias_count = 0;
            $descansos_count = 0;
            $faltas_count = 0;

            foreach ($asistencias as $registro) {
                $enlistados = json_decode($registro->elementos_enlistados, true) ?? [];
                $descansos = json_decode($registro->descansos, true) ?? [];
                $faltas = json_decode($registro->faltas, true) ?? [];

                if (in_array($user->id, $enlistados)) $asistencias_count++;
                if (in_array($user->id, $descansos)) $descansos_count++;
                if (in_array($user->id, $faltas)) $faltas_count++;
            }

            $sd = $user->solicitudAlta->sd ?? 0;
            $sdi = $user->solicitudAlta->sdi ?? 0;
            $diasTrabajados = $asistencias_count + $descansos_count;
            $percepciones = $sd * $diasTrabajados;

            if ($faltas_count === 0) {
                $percepciones *= 1.2;
            }

            $imss = $this->calcularIMSS($sdi, $diasTrabajados);
            $isr = $this->calcularISR($sd, $diasTrabajados, $faltas_count);
            $neto = $percepciones - ($imss + $isr);

            $total += max(0, $neto);
        }

        return round($total, 2);
    }

    private function calcularIMSS(float $sdi, int $dias): float
    {
        $sueldo = $sdi * $dias;
        return ($sueldo * 0.00625) + ($sueldo * 0.01125) + ($sdi * 0.05);
    }

    private function calcularISR(float $sd, int $dias, int $faltas): float
    {
        $sueldo = $sd * $dias;
        if ($faltas === 0) $sueldo *= 1.2;

        $tablaISR = [
            ['limInf' => 0.01, 'limSup' => 368.10, 'cuotaFija' => 0.00, 'porcentaje' => 1.92],
            ['limInf' => 368.11, 'limSup' => 3124.35, 'cuotaFija' => 7.05, 'porcentaje' => 6.4],
            ['limInf' => 3124.36, 'limSup' => 5437.91, 'cuotaFija' => 183.56, 'porcentaje' => 10.88],
            ['limInf' => 5437.92, 'limSup' => 7567.38, 'cuotaFija' => 544.68, 'porcentaje' => 16],
            ['limInf' => 7567.39, 'limSup' => INF, 'cuotaFija' => 913.63, 'porcentaje' => 17.92],
        ];

        foreach ($tablaISR as $r) {
            if ($sueldo >= $r['limInf'] && $sueldo <= $r['limSup']) {
                return $r['cuotaFija'] + (($sueldo - $r['limInf']) * ($r['porcentaje'] / 100));
            }
        }

        return 0;
    }

    public function render()
    {
        return view('livewire.nominamensual');
    }
}
