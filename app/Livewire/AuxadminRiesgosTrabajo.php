<?php

namespace App\Livewire;


use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class AuxadminRiesgosTrabajo extends Component
{
    use WithPagination;

    public $search = '';
    protected $paginationTheme = 'tailwind';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $users = User::query()
            ->where('name', 'like', '%' . $this->search . '%')
            ->orWhereHas('solicitudAlta', function ($query) {
                $query->where('punto', 'like', '%' . $this->search . '%');
            })
            ->orderBy('name')
            ->paginate(10);

        return view('livewire.auxadmin-riesgos-trabajo', [
            'users' => $users,
        ]);
    }
}
