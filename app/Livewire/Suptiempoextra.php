<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\TiemposExtra;
use App\Models\CubrirTurno;

class Suptiempoextra extends Component
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
        if(Auth::user()->rol == 'Supervisor')
        {
            $supervisor = Auth::user();

            $tiemposExtras = TiemposExtra::whereHas('user', function ($query) use ($supervisor) {
            $query->where('empresa', $supervisor->empresa)
                ->where('punto', $supervisor->punto)
                ->where('name', 'like', '%' . $this->search . '%');
            })
            ->with('user')
            ->orderBy('fecha', 'desc')
            ->paginate(10);
        }else{
            $tiemposExtras = TiemposExtra::whereHas('user', function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->with('user')
            ->orderBy('fecha', 'desc')
            ->paginate(10);
        }
        return view('livewire.suptiempoextra', compact('tiemposExtras'));
    }
}
