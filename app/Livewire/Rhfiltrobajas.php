<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\SolicitudBajas;

class Rhfiltrobajas extends Component
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
        $solicitudes = SolicitudBajas::whereHas('user', function ($query) {
            $query->where('name', 'like', '%' . $this->search . '%');
        })
        ->with('user')
        ->orderBy('created_at', 'desc')
        ->paginate(10);

        return view('livewire.rhfiltrobajas', [
            'solicitudes' => $solicitudes
        ]);
    }
}
