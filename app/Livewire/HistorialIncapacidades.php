<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Incapacidad; // Importa el modelo Incapacidad

class HistorialIncapacidades extends Component
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
        // Obtener todas las incapacidades
        $incapacidades = Incapacidad::query()
            ->with('user')
            // Aplicar filtro de búsqueda
            ->when($this->search, function ($query) {
                $query->where('motivo', 'like', '%' . $this->search . '%')
                      ->orWhere('tipo_incapacidad', 'like', '%' . $this->search . '%')
                      ->orWhere('folio', 'like', '%' . $this->search . '%')
                      // Buscar también por el nombre del usuario relacionado
                      ->orWhereHas('user', function ($q) {
                          $q->where('name', 'like', '%' . $this->search . '%');
                      });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.historial-incapacidades', [
            'incapacidades' => $incapacidades,
        ]);
    }
}
