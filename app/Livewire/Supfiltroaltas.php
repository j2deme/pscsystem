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
    protected $queryString = ['search'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $usuario = auth()->user()->name;
        $solicitudes = SolicitudAlta::where('solicitante', $usuario)
            ->when($this->search, function ($query) {
                return $query->where('nombre', 'like', '%'.$this->search.'%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.supfiltroaltas', [
            'solicitudes' => $solicitudes
        ]);
    }
}
