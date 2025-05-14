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
    public $fecha = null;
    protected $queryString = ['search'];

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
        $solicitudes = SolicitudBajas::whereHas('user', function ($query) {
            $query->where('name', 'like', '%' . $this->search . '%');
        })
        ->when($this->fecha, function ($query) {
            $query->whereDate('fecha_baja', $this->fecha);
        })
        ->with('user')
        ->orderBy('fecha_baja', 'desc')
        ->paginate(10);

        return view('livewire.rhfiltrobajas', [
            'solicitudes' => $solicitudes
        ]);
    }
}
