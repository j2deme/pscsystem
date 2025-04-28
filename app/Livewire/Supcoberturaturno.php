<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\CubrirTurno;
use Illuminate\Support\Facades\Auth;

class Supcoberturaturno extends Component
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
        if(Auth::user()->rol == 'Supervisor'){
            $user = Auth::user();

            $coberturas = CubrirTurno::where('autorizado_por', $user->id)
                ->whereHas('user', function($q) {
                    $q->where('name', 'like', '%' . $this->search . '%');
                })
                ->with('user')
                ->orderBy('fecha', 'desc')
                ->paginate(10);
        }else{
            $coberturas = CubrirTurno::query()
                ->when($this->search, function($query) {
                    $query->whereHas('user', function($q) {
                        $q->where('name', 'like', '%' . $this->search . '%');
                    });
                })
                ->with('user')
                ->orderBy('fecha', 'desc')
                ->paginate(10);

        }

        return view('livewire.supcoberturaturno', compact('coberturas'));
    }
}
