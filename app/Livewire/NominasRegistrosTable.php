<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Archivonomina;
use Carbon\Carbon;

class NominasRegistrosTable extends Component
{
    use WithPagination;

    public $search = '';
    public $anio = '';
    public $mes = '';
    public $orden = 'created_at';
    public $direccion = 'desc';

    protected $queryString = [
        'search' => ['except' => ''],
        'anio' => ['except' => ''],
        'mes' => ['except' => ''],
        'orden' => ['except' => 'created_at'],
        'direccion' => ['except' => 'desc'],
        'page' => ['except' => 1],
    ];

    public function mount()
    {
        $this->anio = now()->year;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingAnio()
    {
        $this->resetPage();
    }

    public function updatingMes()
    {
        $this->resetPage();
    }

    public function ordenarPor($campo)
    {
        if ($this->orden === $campo) {
            $this->direccion = $this->direccion === 'asc' ? 'desc' : 'asc';
        } else {
            $this->orden = $campo;
            $this->direccion = 'asc';
        }
    }

    public function render()
    {
        $registros = Archivonomina::query();

        // Búsqueda general
        if (!empty($this->search)) {
            $registros->where(function($query) {
                $query->where('periodo', 'like', '%' . $this->search . '%')
                      ->orWhere('arch_nomina', 'like', '%' . $this->search . '%')
                      ->orWhere('arch_destajo', 'like', '%' . $this->search . '%');
            });
        }

        // Filtro por año
        if (!empty($this->anio)) {
            $registros->whereYear('created_at', $this->anio);
        }

        // Filtro por mes
        if (!empty($this->mes)) {
            $registros->where('periodo', 'like', '%' . ucfirst($this->mes) . '%');
        }

        // Ordenamiento
        $registros->orderBy($this->orden, $this->direccion);

        $registros = $registros->paginate(10);

        return view('livewire.nominas-registros-table', [
            'registros' => $registros,
            'anios' => $this->obtenerAniosDisponibles()
        ]);
    }

    private function obtenerAniosDisponibles()
    {
        $anios = Archivonomina::selectRaw('YEAR(created_at) as anio')
                    ->distinct()
                    ->orderBy('anio', 'desc')
                    ->pluck('anio');

        return $anios->toArray();
    }

    public function formatearPeriodo($periodo)
    {
        // Convertir "1° Enero" a "1° Quincena Enero"
        return str_replace(['1°', '2°'], ['1° Quincena', '2° Quincena'], $periodo);
    }

    public function formatearMoneda($monto)
    {
        return '$' . number_format($monto, 2, '.', ',');
    }

    public function descargarArchivo($rutaArchivo)
    {
        if ($rutaArchivo && file_exists(storage_path('app/public/' . $rutaArchivo))) {
            return response()->download(storage_path('app/public/' . $rutaArchivo));
        }

        session()->flash('error', 'Archivo no encontrado');
    }
}
