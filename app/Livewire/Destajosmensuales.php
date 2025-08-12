<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Archivonomina;
use Carbon\Carbon;

class Destajosmensuales extends Component
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
        // Obtener todos los registros con arch_destajo no nulo
        $registros = Archivonomina::whereNotNull('arch_destajo')
            ->where('arch_destajo', '!=', '')
            ->get();

        if ($registros->isEmpty()) {
            return;
        }

        // Extraer mes y año del periodo (ej: "1° quincena Junio 2025" → Junio 2025)
        $datos = $registros->map(function ($registro) {
            $periodo = $registro->periodo;

            // Intentar extraer mes y año (asumimos que el nombre del mes y año están al final)
            $palabras = explode(' ', $periodo);
            $posibleMes = $palabras[count($palabras) - 2]; // Ej: "Junio"
            $anio = (int) $palabras[count($palabras) - 1]; // Ej: 2025

            // Mapear nombre del mes a número
            $meses = [
                'enero' => 1, 'febrero' => 2, 'marzo' => 3, 'abril' => 4,
                'mayo' => 5, 'junio' => 6, 'julio' => 7, 'agosto' => 8,
                'septiembre' => 9, 'octubre' => 10, 'noviembre' => 11, 'diciembre' => 12
            ];

            $mes = strtolower($posibleMes);
            $numeroMes = $meses[$mes] ?? null;

            if (!$numeroMes) {
                return null; // No válido
            }

            $fecha = Carbon::create($anio, $numeroMes, 1);

            return [
                'subtotal' => $registro->total_destajos,
                'fecha' => $fecha,
            ];
        })->filter(); // Elimina nulls

        if ($datos->isEmpty()) {
            return;
        }

        // Ordenar por fecha descendente
        $datos = $datos->sortByDesc('fecha');

        // Mes más reciente
        $actual = $datos->first();
        $this->totalMesActual = $actual['subtotal'];

        // Buscar mes anterior
        $fechaActual = $actual['fecha'];
        $fechaMesAnterior = $fechaActual->copy()->subMonth();

        $anterior = $datos->first(function ($item) use ($fechaMesAnterior) {
            return $item['fecha']->month === $fechaMesAnterior->month &&
                   $item['fecha']->year === $fechaMesAnterior->year;
        });

        $this->totalMesAnterior = $anterior['subtotal'] ?? 0;

        // Calcular variación porcentual
        if ($this->totalMesAnterior > 0) {
            $this->variacion = (($this->totalMesActual - $this->totalMesAnterior) / $this->totalMesAnterior) * 100;
        } else {
            $this->variacion = $this->totalMesActual > 0 ? 100 : 0;
        }

        $this->variacion = round($this->variacion, 1);
    }

    public function render()
    {
        return view('livewire.destajosmensuales');
    }
}
