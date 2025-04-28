<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;

class Admigestionusuarios extends Component
{
    use WithPagination;

    public $search = '';
    protected $queryString = ['search' => ['except' => '']];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        if(Auth()->user()->rol == 'admin'){
            $users = User::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%'.$this->search.'%');
            })
            ->orderBy('name')
            ->paginate(10);
        }else{
            $users = User::where('punto', Auth()->user()->punto)
            ->where('empresa', Auth()->user()->empresa)
            ->where('rol', '!=', 'Supervisor')
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%'.$this->search.'%');
            })
            ->orderBy('name')
            ->paginate(10);
        }


        return view('livewire.admigestionusuarios', [
            'users' => $users
        ]);
    }
}
