<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Archivonomina;
use Carbon\Carbon;

class NominaMensual extends Component
{
    public $totalMesActual = 0;
    public $totalMesAnterior = 0;
    public $variacion = 0;

    public function mount()
    {
        $this->cargarDatos();
    }

    public function cargarDatos()
{
    // Obtener todos los registros relevantes
    $registros = Archivonomina::whereNotNull('arch_nomina')
        ->where('arch_nomina', '!=', '')
        ->orWhere('subtotal', '>', 0)
        ->get();

    if ($registros->isEmpty()) {
        return;
    }

    // Mapear cada registro a un objeto con fecha y subtotal
    $datos = $registros->map(function ($registro) {
        $periodo = $registro->periodo;
        $palabras = explode(' ', $periodo);

        if (count($palabras) < 2) return null;

        $anio = (int) end($palabras);
        $mesStr = $palabras[count($palabras) - 2]; // "Julio", "Agosto", etc.

        $meses = [
            'enero' => 1, 'febrero' => 2, 'marzo' => 3, 'abril' => 4,
            'mayo' => 5, 'junio' => 6, 'julio' => 7, 'agosto' => 8,
            'septiembre' => 9, 'octubre' => 10, 'noviembre' => 11, 'diciembre' => 12
        ];

        $mes = $meses[strtolower($mesStr)] ?? null;
        if (!$mes) return null;

        $fecha = Carbon::create($anio, $mes, 1);

        // Extraer número de quincena: 1 o 2
        $quincena = str_contains(strtolower($periodo), '1°') ? 1 :
                   (str_contains(strtolower($periodo), '2°') ? 2 : null);

        return [
            'subtotal' => $registro->subtotal,
            'fecha' => $fecha,
            'quincena' => $quincena,
            'periodo_texto' => $periodo,
        ];
    })->filter()->sortByDesc('fecha')->values(); // Ordenar por fecha, más reciente primero

    if ($datos->isEmpty()) return;

    // ✅ El más reciente es el periodo actual
    $actual = $datos->first();
    $this->totalMesActual = $actual['subtotal'];

    // ✅ El siguiente en la lista es el periodo anterior (inmediato)
    $anterior = $datos->get(1); // El segundo más reciente

    $this->totalMesAnterior = $anterior['subtotal'] ?? 0;

    // Calcular variación
    if ($this->totalMesAnterior > 0) {
        $this->variacion = (($this->totalMesActual - $this->totalMesAnterior) / $this->totalMesAnterior) * 100;
    } else {
        $this->variacion = $this->totalMesActual > 0 ? 100 : 0;
    }

    $this->variacion = round($this->variacion, 1);

    \Log::info('Comparación de periodos', [
        'actual' => $actual['periodo_texto'],
        'anterior' => $anterior['periodo_texto'] ?? 'N/A',
        'subtotal_actual' => $this->totalMesActual,
        'subtotal_anterior' => $this->totalMesAnterior,
        'variacion' => $this->variacion . '%'
    ]);
}

    public function render()
    {
        return view('livewire.nominamensual');
    }
}
