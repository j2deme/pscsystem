<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Asistencia;
use Carbon\Carbon;

class Nominastotales extends Component
{
    public string $filtro = 'todos';
    public array $labels = [];
    public array $values = [];
    public array $debug = [];


    public function mount()
    {
        $this->actualizarGrafica();
    }

    public function updatedFiltro()
    {
        $this->actualizarGrafica();
    }

    public function render()
    {
        $total = $this->calcularNominaDelMes();

        return view('livewire.nominastotales', [
            'labels' => [$this->capitalizarMes($this->filtro)],
            'values' => [$total],
            'filtro' => $this->filtro,
        ]);
    }

    public function actualizarGrafica()
    {
        $total = $this->calcularNominaDelMes();

        $this->labels = [$this->capitalizarMes($this->filtro)];
        $this->values = [$total];

        $this->dispatch('chart-nominas-updated', [
            'labels' => $this->labels,
            'values' => $this->values,
        ]);
    }

    public function calcularNominaDelMes(): float
    {
        $mesNumero = $this->obtenerNumeroMes($this->filtro);
        $anio = now()->year;

        if ($this->filtro === 'todos') {
    $inicio = Carbon::now()->startOfYear();
    $fin = Carbon::now()->endOfYear();
} else {
    $mesNumero = $this->obtenerNumeroMes($this->filtro);
    $anio = now()->year;
    $inicio = Carbon::createFromDate($anio, $mesNumero, 1)->startOfMonth();
    $fin = $inicio->copy()->endOfMonth();
}


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
        $bono = $percepciones * 0.20;
        $percepciones += $bono;
    } else {
        $bono = 0;
    }

    $imss = $this->calcularIMSS($sdi, $diasTrabajados);
    $isr = $this->calcularISR($sd, $diasTrabajados, $faltas_count);

    $deducciones = $imss + $isr;
    $neto = $percepciones - $deducciones;

    $this->debug[] = [
        'usuario' => $user->name,
        'sd' => $sd,
        'sdi' => $sdi,
        'asistencias' => $asistencias_count,
        'descansos' => $descansos_count,
        'faltas' => $faltas_count,
        'diasTrabajados' => $diasTrabajados,
        'percepciones' => $percepciones,
        'bono' => $bono,
        'imss' => $imss,
        'isr' => $isr,
        'deducciones' => $deducciones,
        'neto' => $neto,
    ];

    if ($neto > 0) {
    $total += $neto;
}

}


        return round($total, 2);
    }

    private function calcularIMSS(float $sdi, int $diasTrabajados): float
    {
        $sueldo = $diasTrabajados * $sdi;

        $inv = $sueldo * 0.00625;
        $ces = $sueldo * 0.01125;
        $mat = $sdi * 0.05;

        return $inv + $ces + $mat;
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
                return $rango['cuotaFija'] + (($sueldo - $rango['limInf']) * ($rango['porcentaje'] / 100));
            }
        }

        return 0;
    }

    private function obtenerNumeroMes(string $mes): int
    {
        return match(strtolower($mes)) {
            'enero' => 1,
            'febrero' => 2,
            'marzo' => 3,
            'abril' => 4,
            'mayo' => 5,
            'junio' => 6,
            'julio' => 7,
            'agosto' => 8,
            'septiembre' => 9,
            'octubre' => 10,
            'noviembre' => 11,
            'diciembre' => 12,
            default => now()->month
        };
    }

    private function capitalizarMes(string $mes): string
    {
        return ucfirst(strtolower($mes));
    }
}
