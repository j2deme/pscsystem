<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class Admigestionusuarios extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'name';
    public $sortDirection = 'asc';

    protected $queryString = [
        'search' => ['except' => ''],
        'sortField' => ['except' => 'name'],
        'sortDirection' => ['except' => 'asc'],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }

        $this->resetPage();
    }

    public function render()
    {
        $query = User::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%'.$this->search.'%');
            });

        if(Auth()->user()->rol == 'admin' ||
           in_array(Auth::user()->solicitudAlta->rol ?? '', [
               'AUXILIAR RECURSOS HUMANOS', 'AUXILIAR RH', 'AUX RH',
               'Auxiliar RH', 'Auxiliar Recursos Humanos', 'Aux RH'
           ]) ||
           Auth::user()->solicitudAlta->departamento == 'Recursos Humanos')
        {
            $users = $query
                ->orderBy($this->sortField, $this->sortDirection)
                ->paginate(10);
        } else {
            $users = $query
                ->where('punto', Auth()->user()->punto)
                ->where('empresa', Auth()->user()->empresa)
                ->where('rol', '!=', 'Supervisor')
                ->orderBy($this->sortField, $this->sortDirection)
                ->paginate(10);
        }

        return view('livewire.admigestionusuarios', [
            'users' => $users
        ]);
    }
}
