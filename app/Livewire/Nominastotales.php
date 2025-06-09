<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Asistencia;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class Nominastotales extends Component
{
    public string $filtro = 'todos';
    public array $labels = [];
    public array $periodo1 = [];
    public array $periodo2 = [];
    public float $total = 0;
    public bool $readyToLoad = false;

    public function render()
    {
        return view('livewire.nominastotales');
    }

    public function cargarGrafica()
    {
        $this->readyToLoad = true;
        $this->isCalculating = true;
        $this->actualizarGrafica();
    }

    public function updatedFiltro()
    {
        if ($this->readyToLoad) {
            $this->isCalculating = true;
            $this->actualizarGrafica();
        }
    }

    public function actualizarGrafica()
    {
        $datos = $this->calcularNominaDelMes();

        $this->labels = ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'];
        $this->periodo1 = array_fill(0, 12, 0);
        $this->periodo2 = array_fill(0, 12, 0);

        if ($this->filtro === 'todos') {
            foreach ($datos as $index => $valores) {
                $this->periodo1[$index] = $valores[0];
                $this->periodo2[$index] = $valores[1];
            }
            $this->total = array_sum($this->periodo1) + array_sum($this->periodo2);
        } else {
            $mesIndex = $this->obtenerNumeroMes($this->filtro) - 1;
            $this->periodo1[$mesIndex] = $datos[0][0] ?? 0;
            $this->periodo2[$mesIndex] = $datos[0][1] ?? 0;
            $this->total = $this->periodo1[$mesIndex] + $this->periodo2[$mesIndex];
        }

        $this->isCalculating = false;
        $this->dispatch('chart-nominas-updated', [
            'labels' => $this->labels,
            'periodo1' => $this->periodo1,
            'periodo2' => $this->periodo2,
            'total' => $this->total
        ]);
    }

    public function calcularNominaDelMes(): array
    {
        $anio = now()->year;
        $resultados = [];

        if ($this->filtro === 'todos') {
            for ($mes = 1; $mes <= 12; $mes++) {
                $resultados[] = $this->calcularPeriodosMes($anio, $mes);
            }
        } else {
            $mesNumero = $this->obtenerNumeroMes($this->filtro);
            $resultados[] = $this->calcularPeriodosMes($anio, $mesNumero);
        }

        return $resultados;
    }

    private function calcularPeriodosMes(int $anio, int $mes): array
    {
        $mesAnterior = $mes - 1;
        $anioPeriodo1 = $mesAnterior < 1 ? $anio - 1 : $anio;
        $mesAnterior = $mesAnterior < 1 ? 12 : $mesAnterior;

        $inicioPeriodo1 = Carbon::createFromDate($anioPeriodo1, $mesAnterior, 26)->startOfDay();
        $finPeriodo1 = Carbon::createFromDate($anio, $mes, 10)->endOfDay();

        $inicioPeriodo2 = Carbon::createFromDate($anio, $mes, 11)->startOfDay();
        $finPeriodo2 = Carbon::createFromDate($anio, $mes, 25)->endOfDay();

        return [
            $this->calcularNominaPorRango($inicioPeriodo1, $finPeriodo1),
            $this->calcularNominaPorRango($inicioPeriodo2, $finPeriodo2),
        ];
    }

    private function calcularNominaPorRango(Carbon $inicio, Carbon $fin): float
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
            if ($faltas_count === 0) $percepciones += $percepciones * 0.20;

            $imss = $this->calcularIMSS($sdi, $diasTrabajados);
            $isr = $this->calcularISR($sd, $diasTrabajados, $faltas_count);

            $deducciones = $imss + $isr;
            $neto = $percepciones - $deducciones;

            if ($neto > 0) $total += $neto;
        }

        return round($total, 2);
    }

    private function calcularIMSS(float $sdi, int $diasTrabajados): float
    {
        $sueldo = $diasTrabajados * $sdi;
        return round(
            ($sueldo * 0.00625) +  // Invalidez y vida
            ($sueldo * 0.01125) +  // Cesantía y vejez
            ($sdi * 0.05),         // Enfermedades y maternidad
        2);
    }

    private function calcularISR(float $sd, int $diasTrabajados, int $faltas): float
    {
        $sueldo = $sd * $diasTrabajados;

        if ($faltas === 0) {
            $sueldo += $sueldo * 0.20;
        }

        $tablaISR = [
            ['limInf' => 0.01, 'limSup' => 368.10, 'cuotaFija' => 0.00, 'porcentaje' => 1.92],
            ['limInf' => 368.11, 'limSup' => 3124.35, 'cuotaFija' => 7.05, 'porcentaje' => 6.4],
            ['limInf' => 3124.36, 'limSup' => 5437.91, 'cuotaFija' => 183.56, 'porcentaje' => 10.88],
            ['limInf' => 5437.92, 'limSup' => 7567.38, 'cuotaFija' => 544.68, 'porcentaje' => 16],
            ['limInf' => 7567.39, 'limSup' => INF, 'cuotaFija' => 913.63, 'porcentaje' => 17.92],
        ];

        foreach ($tablaISR as $rango) {
            if ($sueldo >= $rango['limInf'] && $sueldo <= $rango['limSup']) {
                return $rango['cuotaFija'] + (($sueldo - $rango['limInf']) * $rango['porcentaje'] / 100);
            }
        }

        return 0;
    }

    private function obtenerNumeroMes(string $mes): int
    {
        return [
            'enero' => 1, 'febrero' => 2, 'marzo' => 3, 'abril' => 4,
            'mayo' => 5, 'junio' => 6, 'julio' => 7, 'agosto' => 8,
            'septiembre' => 9, 'octubre' => 10, 'noviembre' => 11, 'diciembre' => 12,
        ][$mes] ?? now()->month;
    }
}
