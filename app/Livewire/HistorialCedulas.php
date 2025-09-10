<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Cedula;
use Carbon\Carbon;

class HistorialCedulas extends Component
{
    use WithPagination;

    public $search = '';
    public $mesSeleccionado = '';
    protected $paginationTheme = 'tailwind';

    protected $queryString = ['search', 'mesSeleccionado'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingMesSeleccionado()
    {
        $this->resetPage();
    }

    public function render()
    {
        $cedulas = Cedula::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('periodo_eva', 'like', '%' . $this->search . '%')
                      ->orWhere('mes_ema', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->mesSeleccionado, function ($query) {
                $numeroMes = $this->obtenerNumeroMes($this->mesSeleccionado);
                if ($numeroMes) {
                    $query->where(function ($q) use ($numeroMes) {
                        $q->where('periodo_eva', 'like', '%' . $numeroMes . '%')
                          ->orWhere('mes_ema', 'like', '%' . $numeroMes . '%');
                    });
                }
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.historial-cedulas', [
            'cedulas' => $cedulas,
        ]);
    }

    private function obtenerNumeroMes($nombreMes)
    {
        $meses = [
            'Enero' => '01',
            'Febrero' => '02',
            'Marzo' => '03',
            'Abril' => '04',
            'Mayo' => '05',
            'Junio' => '06',
            'Julio' => '07',
            'Agosto' => '08',
            'Septiembre' => '09',
            'Octubre' => '10',
            'Noviembre' => '11',
            'Diciembre' => '12'
        ];

        return $meses[$nombreMes] ?? null;
    }
}
