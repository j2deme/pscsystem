<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;

class Nuevadeduccion extends Component
{
    public $search = '';
    public $usuarios = [];
    public $selectedUserId = null;
    public $showDropdown = false;

    protected $listeners = ['actualizarInput' => 'actualizarInput'];

    public function updatedSearch($value)
    {
        if (strlen($value) >= 2) {
            $this->usuarios = User::where('estatus', 'Activo')
                ->where('name', 'like', '%'.$value.'%')
                ->limit(5)
                ->get();
            $this->showDropdown = true;
        } else {
            $this->resetDropdown();
        }
    }

    public function seleccionarUsuario($id, $nombre)
    {
        $this->selectedUserId = $id;
        $this->search = $nombre;
        $this->resetDropdown();
        $this->dispatch('inputActualizado', usuarioId: $id, nombre: $nombre);
    }

    public function actualizarInput($nombre)
    {
        $this->search = $nombre;
    }

    private function resetDropdown()
    {
        $this->usuarios = [];
        $this->showDropdown = false;
    }

    public function render()
    {
        return view('livewire.nuevadeduccion');
    }
}
