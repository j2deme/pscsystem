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
        $usuariosQuery = User::where('estatus', 'Activo');

        if (strlen($this->search) > 1) {
            $usuariosQuery->where('name', 'like', '%' . $this->search . '%');
        }

        $usuarios = $usuariosQuery->paginate(10);

        return view('livewire.filtrousuarios', compact('usuarios'));
    }
}
