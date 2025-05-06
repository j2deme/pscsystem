<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\SolicitudAlta;

class Supfiltroaltas extends Component
{
    use WithPagination;

    public $search = '';
    public $searchDate = null;
    protected $queryString = [
        'search' => ['except' => ''],
        'searchDate' => ['except' => null] // Agrega esto para que la fecha aparezca en la URL
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingSearchDate()
    {
        $this->resetPage();
    }

    public function render()
    {
        $user = auth()->user();
        $usuario = $user->name;

        $query = ($user->rol === 'Supervisor')
            ? SolicitudAlta::where('solicitante', $usuario)
            : SolicitudAlta::query();

        if ($this->search) {
            $query->where(function($q) {
                $q->where('nombre', 'like', '%'.$this->search.'%')
                  ->orWhere('apellido_paterno', 'like', '%'.$this->search.'%')
                  ->orWhere('apellido_materno', 'like', '%'.$this->search.'%');
            });
        }

        if ($this->searchDate) {
            $query->whereDate('created_at', $this->searchDate);
        }

        $solicitudes = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('livewire.supfiltroaltas', [
            'solicitudes' => $solicitudes
        ]);
    }
}
