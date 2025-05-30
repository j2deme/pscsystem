<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;

class GraficasAltas extends Component
{
    public $activeUsersCount = 0;

    public function mount()
    {
        $this->activeUsersCount = User::where('estatus', 'Activo')->count();
    }

    public function render()
    {
        return view('livewire.graficas-altas');
    }
}
