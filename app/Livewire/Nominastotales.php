<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Nomina;
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
        $this->actualizarGrafica();
    }

    public function updatedFiltro()
    {
        if ($this->readyToLoad) {
            $this->actualizarGrafica();
        }
    }

    public function actualizarGrafica()
    {
        $this->labels = ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'];
        $this->periodo1 = array_fill(0, 12, 0);
        $this->periodo2 = array_fill(0, 12, 0);
        $this->total = 0;

        $anioActual = now()->year;

        $nominas = Nomina::whereYear('created_at', $anioActual)->get();

        foreach ($nominas as $nomina) {
            if (preg_match('/^(1°|2°) (\w+) (\d{4})$/u', $nomina->periodo, $matches)) {
                [$_, $quincena, $mesTexto, $anio] = $matches;
                if ((int) $anio !== $anioActual) continue;

                $mesIndex = $this->mesTextoANumero($mesTexto) - 1;
                if ($mesIndex < 0 || $mesIndex > 11) continue;

                if ($this->filtro === 'todos' || strtolower($this->filtro) === strtolower($mesTexto)) {
                    if ($quincena === '1°') {
                        $this->periodo1[$mesIndex] += $nomina->monto;
                    } elseif ($quincena === '2°') {
                        $this->periodo2[$mesIndex] += $nomina->monto;
                    }
                }
            }
        }

        $this->total = array_sum($this->periodo1) + array_sum($this->periodo2);

        $this->dispatch('chart-nominas-updated', [
            'labels' => $this->labels,
            'periodo1' => $this->periodo1,
            'periodo2' => $this->periodo2,
            'total' => $this->total
        ]);
    }

    private function mesTextoANumero(string $mes): int
    {
        return [
            'enero' => 1, 'febrero' => 2, 'marzo' => 3, 'abril' => 4,
            'mayo' => 5, 'junio' => 6, 'julio' => 7, 'agosto' => 8,
            'septiembre' => 9, 'octubre' => 10, 'noviembre' => 11, 'diciembre' => 12,
        ][strtolower($mes)] ?? 0;
    }
}
