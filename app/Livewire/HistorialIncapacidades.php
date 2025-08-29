<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Incapacidad;

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
            ->with(['user' => function($query) {
                $query->withTrashed(); // Cargar usuarios incluso si están soft deleted
            }])
            // Aplicar filtro de búsqueda
            ->when($this->search, function ($query) {
                $query->where('motivo', 'like', '%' . $this->search . '%')
                      ->orWhere('tipo_incapacidad', 'like', '%' . $this->search . '%')
                      ->orWhere('folio', 'like', '%' . $this->search . '%')
                      // Buscar también por el nombre del usuario relacionado
                      ->orWhereHas('user', function ($q) {
                          $q->withTrashed() // Buscar también en usuarios soft deleted
                            ->where('name', 'like', '%' . $this->search . '%');
                      });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.historial-incapacidades', [
            'incapacidades' => $incapacidades,
        ]);
    }
}
