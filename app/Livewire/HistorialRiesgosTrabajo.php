<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\RiesgoTrabajo;

class HistorialRiesgosTrabajo extends Component
{
    use WithPagination;

    public $search = '';

    protected $queryString = [
        'search' => ['except' => ''],
    ];

    public function render()
    {
        $riesgos = RiesgoTrabajo::with(['user' => function($query) {
                $query->withTrashed(); // Cargar usuarios incluso si están soft deleted
            }])
            ->where(function($query) {
                // Buscar por tipo_riesgo o por el nombre del usuario asociado
                $query->where('tipo_riesgo', 'like', '%' . $this->search . '%')
                      ->orWhereHas('user', function ($q) {
                          $q->withTrashed() // Buscar también en usuarios soft deleted
                            ->where('name', 'like', '%' . $this->search . '%');
                      });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.historial-riesgos-trabajo', [
            'riesgos' => $riesgos,
        ]);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }
}
