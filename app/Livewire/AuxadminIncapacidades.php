<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User; // Importa el modelo User

class AuxadminIncapacidades extends Component
{
    use WithPagination;

    public $search = '';

    public function render()
    {
        $users = User::where(function($query) {
            $query->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
        })
        // Opcional: Si solo quiere mostrar usuarios activos o con ciertos roles
        // ->where('estatus', 'Activo')
        // ->whereIn('rol', ['Empleado', 'Supervisor', 'AUXILIAR NOMINAS', ...])
        ->paginate(10); // Pagina 10 usuarios por página

        return view('livewire.auxadmin-incapacidades', [
            'users' => $users,
        ]);
    }

    public function updatingSearch()
    {
        $this->resetPage(); // Resetear la paginación al buscar
    }
}
