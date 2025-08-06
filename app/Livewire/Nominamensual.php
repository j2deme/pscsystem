<?php
/*
namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Nomina;
use Carbon\Carbon;

class Nominamensual extends Component
{
    public float $totalMesActual = 0;
    public float $totalMesAnterior = 0;
    public float $variacion = 0;

    public function calcularResumen()
    {
        $hoy = now();
        $mesActual = $hoy->format('m');
        $anioActual = $hoy->format('Y');

        $this->totalMesActual = Nomina::whereYear('created_at', $anioActual)
            ->whereMonth('created_at', $mesActual)
            ->sum('monto');

        $mesAnterior = $hoy->copy()->subMonth();
        $this->totalMesAnterior = Nomina::whereYear('created_at', $mesAnterior->year)
            ->whereMonth('created_at', $mesAnterior->month)
            ->sum('monto');

        if ($this->totalMesAnterior > 0) {
            $this->variacion = round((($this->totalMesActual - $this->totalMesAnterior) / $this->totalMesAnterior) * 100, 2);
        } else {
            $this->variacion = 0;
        }
    }

    public function mount()
    {
        $this->calcularResumen();
    }

    public function render()
    {
        return view('livewire.nominamensual');
    }
}<?php*/

namespace App\Livewire;

use Livewire\Component;
use App\Models\Archivonomina;

class Nominamensual extends Component
{
    public $totalMesActual = 0;
    public $variacion = 0;

    public function mount()
    {
        \Log::info('Iniciando carga de componente Nominamensual');
        $this->calcularTotalNomina();
        $this->calcularVariacion();
    }

    private function calcularTotalNomina()
    {
        \Log::info('Buscando registro más reciente de nómina');

        // Obtener el registro más reciente Y USAR EL SUBTOTAL PRE-CALCULADO
        $registroMasReciente = Archivonomina::latest('created_at')->first();

        if ($registroMasReciente) {
            \Log::info('Registro encontrado', [
                'id' => $registroMasReciente->id,
                'periodo' => $registroMasReciente->periodo,
                'subtotal' => $registroMasReciente->subtotal,
                'created_at' => $registroMasReciente->created_at
            ]);

            // USAR EL SUBTOTAL PRE-CALCULADO EN LUGAR DE PROCESAR EL ARCHIVO
            if ($registroMasReciente->subtotal !== null) {
                $this->totalMesActual = $registroMasReciente->subtotal;
                \Log::info('Total obtenido del subtotal pre-calculado', ['total' => $this->totalMesActual]);
            } else if ($registroMasReciente->arch_nomina) {
                // Fallback: si no hay subtotal, calcularlo (por registros antiguos)
                $this->totalMesActual = $this->calcularTotalDesdeExcel($registroMasReciente->arch_nomina);
                \Log::info('Total calculado del archivo (fallback)', ['total' => $this->totalMesActual]);
            } else {
                \Log::warning('No hay subtotal ni archivo de nómina en el registro');
            }
        } else {
            \Log::warning('No se encontraron registros de nómina');
        }
    }

    private function calcularVariacion()
    {
        \Log::info('Calculando variación porcentual');

        // USAR SUBTOTAL PRE-CALCULADO PARA LA VARIACIÓN
        $registros = Archivonomina::orderBy('created_at', 'desc')->limit(2)->get();

        if ($registros->count() >= 2) {
            $registroActual = $registros[0];
            $registroAnterior = $registros[1];

            \Log::info('Registros encontrados para variación', [
                'actual_id' => $registroActual->id,
                'anterior_id' => $registroAnterior->id
            ]);

            // USAR SUBTOTAL PRE-CALCULADO
            $totalActual = $registroActual->subtotal ?? 0;
            $totalAnterior = $registroAnterior->subtotal ?? 0;

            \Log::info('Totales para variación (desde subtotal)', [
                'actual' => $totalActual,
                'anterior' => $totalAnterior
            ]);

            if ($totalAnterior > 0) {
                $this->variacion = round((($totalActual - $totalAnterior) / $totalAnterior) * 100, 2);
                \Log::info('Variación calculada', ['porcentaje' => $this->variacion]);
            }
        } else {
            \Log::warning('No hay suficientes registros para calcular variación', ['cantidad' => $registros->count()]);
        }
    }

    // Mantén tu método calcularTotalDesdeExcel para registros antiguos
    private function calcularTotalDesdeExcel($rutaArchivo)
    {
        // Tu código existente...
    }

    public function render()
    {
        return view('livewire.nominamensual');
    }
}
