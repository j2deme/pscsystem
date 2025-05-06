<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\SolicitudBajas;

class Supfiltrobajas extends Component
{
    use WithPagination;

    public $search = '';
    public $por = 'Renuncia';
    protected $queryString = ['search' => ['except' => ''], 'por'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $user = auth()->user();

        if ($user->rol == 'Supervisor') {
        $solicitudes = SolicitudBajas::whereHas('user', function ($query) use ($user) {
                $query->where('empresa', $user->empresa)
                    ->where('punto', $user->punto)
                    ->where('por', $this->por);
            })
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->whereHas('user', function ($subQuery) {
                        $subQuery->where('name', 'like', '%'.$this->search.'%');
                    })
                    ->orWhere('motivo', 'like', '%'.$this->search.'%');
                });
            })
            ->with('user')
            ->orderBy('fecha_solicitud', 'desc')
            ->paginate(10);
        } else {
            $solicitudes = SolicitudBajas::query()
                ->when($this->search, function ($query) {
                    $query->where(function ($q) {
                        $q->whereHas('user', function ($subQuery) {
                            $subQuery->where('name', 'like', '%'.$this->search.'%');
                        })
                        ->orWhere('motivo', 'like', '%'.$this->search.'%')
                        ->orWhere('por', 'like', '%'.$this->search.'%');
                    });
                })
                ->with('user')
                ->orderBy('fecha_solicitud', 'desc')
                ->paginate(10);

        }
        return view('livewire.supfiltrobajas', [
            'solicitudes' => $solicitudes
        ]);
    }
}
