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
    protected $queryString = ['search' => ['except' => '']];

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
        
        $query = SolicitudAlta::query();
    
        if ($user->rol == 'Supervisor') {
            $query->where('solicitante', $usuario);
        }
    
        if ($this->search) {
            $query->where('nombre', 'like', '%' . $this->search . '%');
        }
    
        if ($this->searchDate) {
            $query->whereDate('created_at', $this->searchDate);
        }
    
        $solicitudes = $query->orderBy('created_at', 'desc')->paginate(10);
        //dd($this->searchDate);
    
        return view('livewire.supfiltroaltas', [
            'solicitudes' => $solicitudes
        ]);
    }


}
