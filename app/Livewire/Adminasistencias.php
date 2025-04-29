<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Asistencia;

class Adminasistencias extends Component
{
    use WithPagination;
    public $search = '';
    public $fecha = null;

    public $userId;
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
    $userId = Auth::user()->id;

    if (Auth::user()->rol == 'admin') {
        $asistencias = Asistencia::with('usuario')
            ->when($this->search, function ($query) {
                $query->whereHas('usuario', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->fecha, function ($query) {
                $query->whereDate('fecha', $this->fecha);
            })
            ->orderBy('fecha', 'desc')
            ->paginate(10);
    } else {
        $asistencias = Asistencia::with('usuario')
            ->where('user_id', $userId)
            ->where(function ($query) {
                $query->whereHas('usuario', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%');
                })->orWhere('fecha', 'like', '%' . $this->search . '%');
            })
            ->when($this->fecha, function ($query) {
                $query->whereDate('fecha', $this->fecha);
            })
            ->orderBy('fecha', 'desc')
            ->paginate(10);
    }

    return view('livewire.adminasistencias', compact('asistencias'));
}

}
