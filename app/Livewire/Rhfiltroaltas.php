<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\SolicitudAlta;

class Rhfiltroaltas extends Component
{
    use WithPagination;

    public $search = '';
    public $fecha = null;
    protected $queryString = ['search' => ['except' => '']];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingDate()
    {
        $this->resetPage();
    }

    public function render()
    {
        $usuario = auth()->user()->name;
        $solicitudes = SolicitudAlta::when($this->search, function ($query) {
            return $query->where('nombre', 'like', '%'.$this->search.'%')
                ->orWhere('apellido_paterno', 'like', '%'.$this->search.'%')
                ->orWhere('apellido_materno', 'like', '%'.$this->search.'%');
        })
        ->when($this->fecha, function ($query) {
            $query->whereDate('created_at', $this->fecha);
        })
        ->orderBy('created_at', 'desc')
        ->paginate(10);

        return view('livewire.rhfiltroaltas', [
            'solicitudes' => $solicitudes
        ]);
    }
}
