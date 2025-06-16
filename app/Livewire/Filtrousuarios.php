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
        if (strlen($this->search) > 2) {
            $this->usuarios = User::where('estatus', 'Activo')
                ->where('name', 'like', '%' . $this->search . '%')
                ->limit(5)
                ->get();
        } else {
            $this->usuarios = [];
        }

        return view('livewire.nuevadeduccion');
    }
}
