<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\BuzonQueja;
use Livewire\WithPagination;

class Admibuzon extends Component
{
    use WithPagination;
    public $search = '';
    public $fecha = null;

    protected $queryString = ['search' => ['except' => '']];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingDate()
    {
        $this->resetPage();
    }

    public function render()
    {
        $quejas = BuzonQueja::with('user')
            ->when($this->search, function ($query) {
                $query->whereHas('user', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->fecha, function ($query) {
                $query->whereDate('fecha', $this->fecha);
            })
            ->orderBy('fecha', 'desc')
            ->paginate(10);
        return view('livewire.admibuzon', compact('quejas'));
    }
}
