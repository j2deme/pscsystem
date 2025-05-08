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
        if(Auth::user()->rol == 'Supervisor'){
            $user = Auth::user();

            $coberturas = CubrirTurno::where('autorizado_por', $user->id)
                ->whereHas('user', function($q) {
                    $q->where('name', 'like', '%' . $this->search . '%');
                })
                ->when($this->fecha, function ($query) {
                    $query->whereDate('fecha', $this->fecha);
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
                ->when($this->fecha, function ($query) {
                    $query->whereDate('fecha', $this->fecha);
                })
                ->with('user')
                ->orderBy('fecha', 'desc')
                ->paginate(10);

        }

        return view('livewire.supcoberturaturno', compact('coberturas'));
    }
}
