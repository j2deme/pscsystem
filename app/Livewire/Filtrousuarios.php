<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;

class Filtrousuarios extends Component
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
        $usuarios = User::when($this->search, function ($query) {
                return $query->where('name', 'like', '%'.$this->search.'%');
            })
            ->where('estatus', 'Activo')
            ->orderBy('name')
            ->paginate(10);

        return view('livewire.filtrousuarios', [
            'usuarios' => $usuarios
        ]);
    }
}
